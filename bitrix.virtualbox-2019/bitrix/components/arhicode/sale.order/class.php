<?php
/**
 * Class.php - это зарезервированное имя файла, и этот файл автоматически подключается при вызове:
 * $APPLICATION->IncludeComponent()
*/
define("LOG_FILENAME", $_SERVER["DOCUMENT_ROOT"]."/test/log.txt");
//AddMessage2Log("onPrepareComponentParams", "ArhicodeBasketSale");

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
    die();

use Bitrix\Main\Application;
use Bitrix\Main\Web\Json;
use Bitrix\Sale;
use Bitrix\Sale\Basket;
use Bitrix\Sale\Discount;
use Bitrix\Sale\Order;
use Bitrix\Sale\Fuser;
use \Bitrix\Main;
use \Bitrix\Main\Localization\Loc;
use \Bitrix\Main\Loader;
use \Bitrix\Main\Context;


Class CArhicodeBasketSale extends CBitrixComponent
{
    /**
     * @var \Bitrix\Sale\Order
     */
    public $order;
    public $basket;
    public $context;
    public $ajaxData = NULL;
    public $basketStorage = NULL;

    protected $errors = [];
    protected $checkSession = false;


    /**
     * arhicodeBasketSale constructor.
     * @param null $component
     * @throws \Bitrix\Main\LoaderException
     */
    function __construct($component = null)
    {
        parent::__construct($component);

        if(!loader::includeModule('sale'))
            $this->errors[] = 'No sale module';

        if(!Loader::includeModule('catalog'))
            $this->errors[] = 'No catalog module';
    }

    /**
     * Підключаємо мовний файл
     */
    public function onIncludeComponentLang()
    {
        Loc::loadMessages(__FILE__);
    }

    /**
     * Метод обрабатывает параметры компонента. Нестатический метод.
     * Родительский метод проходит по всем параметрам переданным в $APPLICATION->IncludeComponent и применяет к ним функцию htmlspecialcharsex.
     * @param $arParams - Массив со значениями необработанных параметров компонента.
     * @return array
     */
    function onPrepareComponentParams($arParams)
    {
        if(isset($arParams['PERSON_TYPE_ID']) && intval($arParams['PERSON_TYPE_ID']) > 0)
            $arParams['PERSON_TYPE_ID'] = intval($arParams['PERSON_TYPE_ID']);
        else
            $arParams['PERSON_TYPE_ID'] = 1;

        $arParams['ACTION_VARIABLE'] = isset($arParams['ACTION_VARIABLE']) ? trim($arParams['ACTION_VARIABLE']) : '';
        if ($arParams['ACTION_VARIABLE'] == '')
        {
            $arParams['ACTION_VARIABLE'] = 'soa-action';
        }

        return parent::onPrepareComponentParams($arParams);
    }

    /**
     * Create new virtual order
     */
    protected function createVirtualOrder()
    {
        try
        {
            $siteId = Context::getCurrent()->getSite(); // імя сайта
            //$userId = $USER->GetID() ? $USER->GetID() : CSaleUser::GetAnonymousUserID();
            $userId = Fuser::getId(); // ід - поточного користувача
            $basket = Basket::loadItemsForFUser($userId, $siteId);

            if(count($basket->getOrderableItems()) == 0) // якщо кошик пустий перехід на головну сторінку сайта, або на вказану в налаштуванні
                LocalRedirect('/personal/cart/'); // TODO - доробити, якщо кошик пустий

            // створюємо віртуальний заказ
            $this->order = Order::create($siteId, $userId);
            $this->order->setPersonTypeId($this->arParams['PERSON_TYPE_ID']);
            // приєднеємо до заказу поточний кошик
            $this->order->setBasket($basket);

            $this->basket = $this->order->getBasket();
        }
        catch (Exception $e)
        {
            $this->errors[] = $e->getMessage();
        }
    }

    /**
     * Додаємо властивості замовлення
     */
    protected function setOrderProps()
    {
        global $USER;
        $arUser = $USER->GetByID(Fuser::getId())->Fetch(); // поточний покупець

        if(is_array($arUser))
        {
            $fio = $arUser['LAST_NAME'];
        }

        $this->arResult['props'] = [];
        foreach ($this->order->getPropertyCollection() as $prop)
        {
            $value = '';
            switch ($prop->getField('CODE')) {
                case 'FIO':
                    $value = $this->request['contact']['family'];
                    $value .= ' ' . $this->request['contact']['name'];
                    $value .= ' ' . $this->request['contact']['second_name'];

                    $value = trim($value);
                    if (empty($value)) {
                        $value = $arUser['FIO'];
                    }
                    break;

                default:
            }

            if (empty($value)) {
                foreach ($this->request as $key => $val) {
                    if (strtolower($key) == strtolower($prop->getField('CODE'))) {
                        $value = $val;
                    }
                }
            }

            if (empty($value)) {
                $value = $prop->getProperty()['DEFAULT_VALUE'];
            }

            if (!empty($value)) {
                $prop->setValue($value);

                //$this->arResult['props'][] = $value;
            }
        }
    }

    /**
     * Отримати дані для поточного замовлення
     */
    protected function getOrderProps()
    {
        try {
            // Дані по кошику покупця
            $this->arResult['ORDER'] = [
                'USER_ID'=>$this->order->getUserId(), // ID пользователя
                'ORDER_ID'=>$this->order->getId(), // ID заказа
                'SITE_ID'=>$this->order->getSiteId(), // ID сайта
                'PERSON_TYPE_ID'=>$this->order->getPersonTypeId(), // ID типа покупателя

                'PRICE'=>$this->order->getPrice(),// Цена с учетом скидок
                'DELIVERY_PRICE'=>$this->order->getDeliveryPrice(), // Стоимость доставки
                'DISCOUNT_PRICE'=>$this->order->getDiscountPrice(), // Размер скидки
                'SUM_PAID'=>$this->order->getSumPaid(), // Оплаченная сумма
                'CURRENCY'=>$this->order->getCurrency(), // Валюта заказа
                //'Fields_#Name#_'=>$this->order->getField('PRICE'), // Также любое поле можно получить по имени
                //'AVAILABLE_FIELDS'=>$this->order->getAvailableFields(), // Список доступных полей
            ];

            $this->arResult['BASE_PRICE'] = $this->basket->getBasePrice(); // Цена без учета скидок
            //$this->arResult['QUANTITY_LIST'] = $this->basket->getQuantityList(); // возвращает массив "количеств" товаров в корзине
            $this->arResult['LIST_OF_FORMAT_TEXT'] = $this->basket->getListOfFormatText(); // возвращает корзину в читаемом виде

            // получение объекта корзины и массива товаров в корзине:
            // getBasketItems(); // все товары
            // getOrderableItems(); // только товары, доступные для заказа
            $arr = [];

            // формуємо масив даних по товарах
            foreach ($this->basket->getBasketItems() as $items)
            {
                $arr[$items->getProductId()] = [
                    'ID' => $items->getId(), // ID позиции в корзине
                    'BASKET_CODE' => $items->getBasketCode(), // код корзины
                    'QUANTITY' => $items->getQuantity(), // количество товара
                    'PRODUCT_ID' => $items->getProductId(), // код товара
                    'FUSER_ID' => $items->getFUserId(), // id владельца корзины
                    'CAN_BUY' => $items->canBuy(), // товар доступен для покупки
                    'CURRENCY' => $items->getCurrency(), // код валюты
                    'FINAL_PRICE' => $items->getFinalPrice(), // стоимость всех единиц позиции товара
                    'PRICE' => $items->getPrice(), // цена с учетом скидок
                    'FORMAT_PRICE' => CurrencyFormat($items->getPrice(), $items->getCurrency()),
                    'BASE_PRICE' => $items->getBasePrice(), // цена без учета скидок
                    'FORMAT_BASE_PRICE' => CurrencyFormat($items->getBasePrice(), $items->getCurrency()),
                    'DEFAULT_PRICE' => $items->getDefaultPrice(), // цена по умолчанию
                    'DISCOUNT_PRICE' => $items->getDiscountPrice(), // величина скидки
                    //'CUSTOMP_RICE' => $items->isCustomPrice(), // цена указана вручную (без использования провайдера)
                    //'AVAILABLE_FIELDS' => $items->getAvailableFields(), //  массив кодов всех полей
                    'PROPS' => $this->getProductDataList($items->getProductId()),
                ];
            }
            $this->arResult['PRODUCT_LIST'] = $arr;
            unset($arr);

            $this->arResult['PAYMENT_SYSTEM_ORDER'] = $this->order->getPaymentSystemId(); // массив id способів оплат (в поточному замовленні)
            $this->arResult['DELIVERY_SYSTEM_ORDER'] = $this->order->getDeliverySystemId(); // массив id способів доставки (в поточному замовленні)

            //$this->arResult['DISCOUNT_APPLY_ORDER'] = $this->order->getDiscount()->getApplyResult(); // список застосованих до замавлення знижок

        } catch (Exception $e) {
            $this->errors[] = ['message'=>'getOrderProps', 'error'=>$e->getMessage()];
        }
    }

    /**
     * Витягуємо інформацію по товару з кошика
     * @param integer $id - товара з корзини
     * @return array - масив з інформацією по товарy в кошику
    */
    public function getProductDataList($id)
    {
        // перевіряємо 'товар' чи 'торгова пропозиція' (false - в случае ошибки;)
        $productSku =  CCatalogSku::GetProductInfo($id); // Метод позволяет получить по ID торгового предложения ID товара.

        if(is_array($productSku)) // торгова пропозиція, беремо ІД товара по торговій пропозиції
            $products = $this->makeProductDetailInfo($productSku['ID'], $productSku);
        else // товар
            $products = $this->makeProductDetailInfo($id);

        return $products;
    }

    /**
     * Беремо дані по товару
     * @param integer $id - код товару (чи тогргової пропозиції)
     * @param bool $sku - масив з торговою пропозицією
     * @return array
    */
    public function makeProductDetailInfo($id, $sku=false)
    {
        $arr = [];
        $element = CIBlockElement::GetByID($id)->GetNext();
        if(is_array($element))
        {
            $arr = [
                'ID'=>$element['ID'],
                'TIMESTAMP_X'=>$element['TIMESTAMP_X'],
                'DATE_CREATE'=>$element['DATE_CREATE'],
                'IBLOCK_ID'=>$element['IBLOCK_ID'],
                'IBLOCK_SECTION_ID'=>$element['IBLOCK_SECTION_ID'],
                'ACTIVE'=>$element['ACTIVE'],
                'NAME'=>$element['NAME'],
                'PREVIEW_PICTURE'=>$element['PREVIEW_PICTURE'],
                'DETAIL_PICTURE'=>$element['DETAIL_PICTURE'],
                'CODE'=>$element['CODE'],
                'XML_ID'=>$element['XML_ID'],
                'USER_NAME'=>$element['USER_NAME'],
                'IBLOCK_TYPE_ID'=>$element['IBLOCK_TYPE_ID'],
                'IBLOCK_CODE'=>$element['IBLOCK_CODE'],
                'IBLOCK_NAME'=>$element['IBLOCK_NAME'],
                'DETAIL_PAGE_URL'=>$element['DETAIL_PAGE_URL'],
                'DETAIL_PAGE_URL'=>$element['DETAIL_PAGE_URL'],
            ];
        }
        if($sku) $arr['SKU'] = $sku;

        return $arr;
    }

    /**
     * Отримати способи оплати товарів
     */
    public function getSalePaySystem()
    {
        $resPaySystem = CSalePaySystem::GetList();
        //$this->arResult["PAY_SYSTEM"] = [];
        while ($arPaySystemItem = $resPaySystem->Fetch()) {
            $this->arResult["PAY_SYSTEM"][] = $arPaySystemItem;
        }
        /*
        $dbPaySystem = CSalePaySystemAction::GetList(Array(), Array(), false, false, Array());
        while ($arPaySystem = $dbPaySystem->Fetch())
            $this->arResult["PAY_SYSTEM"][] = $arPaySystem;
        */
    }

    /**
     * Отримати способи доставки товарів
     */
    public function getSaleDelivery()
    {
        try {
            $resDelivery = CSaleDelivery::GetList(
                Array(),
                Array("LID" => SITE_ID, "ACTIVE" => "Y"),
                false,
                false,
                Array()
            );
            if($arDelivery = $resDelivery->Fetch()) {
                do {
                    $this->arResult["DELIVERY_TYPE"][] = $arDelivery;
                } while ($arDelivery = $resDelivery->Fetch());
            }

        } catch (Exception $e) {
            $this->errors[] = ['message'=>'getSaleDelivery', 'error'=>$e->getMessage()];
        }
    }

    public function executeTemplate()
    {

    }

    public function executeAjax()
    {

    }



    /* ACTION ------------------------------------------------------------------------------------------------------- */

    /**
     * Prepares action string to execute in doAction
     *  - processOrder - process order [including component template]
     *  - showOrder - show created order [including component template]
     *  - processOrderAjax - show created order [including component template]
     *
     * @return array|string|null
     */
    protected function prepareAction()
    {
        $action = $this->request->offsetExists($this->arParams['ACTION_VARIABLE'])
            ? $this->request->get($this->arParams['ACTION_VARIABLE'])
            : $this->request->get('action');

        if (empty($action))
        {
            if ($this->request->get('ORDER_ID') == '')
            {
                $action = 'processOrder';
            }
            else
            {
                $action = 'showOrder';
            }
        }

        $this->checkSession = check_bitrix_sessid();
        return $action;
    }

    /**
     * Executes prepared action with postfix 'Action'
     * @param $action
     */
    protected function doAction($action)
    {
        if (is_callable(array($this, $action."Action")))
        {
            call_user_func(
                array($this, $action."Action")
            );
        }
    }

    /**
     * Виводимо форму для оформлення замовлення
     * Формуємо масив `arResult` з основними параметрами
     */
    public function processOrderAction()
    {
        try {
            // Створюємо замовлення
            $this->createVirtualOrder();

            // формуємо масив з даними для сторінки оформлення замовлення
            $this->getOrderProps(); // збираємо основні властивості кошика і товарів
            $this->getSalePaySystem(); // доступні системи оплати
            $this->getSaleDelivery(); // отримати доступні доставки
        } catch (Exception $e) {
            $this->arResult['errors'] = ['error'=>'processOrderAction', 'message'=>$e->getMessage()];
        }
    }

    public function deleteProductAction()
    {
        $basket = $this->getBasketStorage()->getBasket();
        $basketCode = (int)$this->request->get('basketCode');

        $ajaxData = [
            'ACTION'=>'deleteProduct',
            'DATA'=>[
                'PRODUCT_ID'=>$basketCode,
            ]
        ];

        if (!$basket->isEmpty())
        {
            // @var Sale\BasketItem $item
            $item = $basket->getItemByBasketCode($basketCode);
            if ($item)
            {
                $deleteResult = $item->delete();

                if ($deleteResult->isSuccess())
                {
                    $saveResult = $basket->save();

                    if ($saveResult->isSuccess())
                    {
                        $ajaxData['ERROR'] = 'N';
                    }
                    else
                    {
                        $ajaxData['ERROR'] ='deleteProductAction';
                    }
                }
            }
            else $ajaxData['ERROR'] ='basket-item';
        }
        else $ajaxData['ERROR'] ='basket-isEmpty';

        $this->sendAjaxAnswer($ajaxData);
    }

    public function changeQuantityAction()
    {
        $quantity = (int)$this->request->get('quantity');
        $basketCode = (int)$this->request->get('basketCode');

        $ajaxData = [
            'ACTION' => 'changeQuantity',
            'DATA' => [
                'PRODUCT_ID' => $basketCode ,
            ]
        ];

        $basket = $this->getBasketStorage()->getBasket();
        $basketItem = $basket->getItemByBasketCode($basketCode);

        $basketItemQuanyity = $basketItem->getQuantity();
        $ajaxData['quantity'] = $basketItemQuanyity;

        if($basketItemQuanyity != $quantity && $quantity > 0 )
        {
            $res = $basketItem->setField('QUANTITY', $quantity);
            if ($res->isSuccess())
            {
                $saveResult = $basket->save();

                if ($saveResult->isSuccess())
                {
                    $ajaxData['ERROR'] = 'N';
                    $ajaxData['DATA']['QUANTITY_NEW'] = $basketItem->getQuantity();
                    $ajaxData['DATA']['BASE_PRICE'] = $basket->getBasePrice();
                    $ajaxData['DATA']['PRICE_DISCOUNT_D'] = $this->calculateBasketTotalDiscountPrice($basket);
                }
                else
                {
                    $ajaxData['ERROR'] = 'changeQuantityAction. Q='.$basketItem->getQuantity();
                }
            }
            else $ajaxData['ERROR'] = 'changeQuantity';
        } else $ajaxData['ERROR'] = 'quantity = 0 or quantity equal';



        AddMessage2Log(['changeQuantityAction'=>$ajaxData], "ArhicodeBasketSale");
        $this->sendAjaxAnswer($ajaxData);
    }

    /**
     * V1.
     * Метод помертає повний масив знижок на товари що в кошику
     * @param $basket
     * @return array
     */
    public function getDiscountBasket($basket)
    {
        $discounts = Discount::loadByBasket($basket);
        $basket->refreshData(array('PRICE', 'COUPONS'));
        $discounts->calculate();
        $discountResult = $discounts->getApplyResult();
        return $discountResult;
    }

    /**
     * V2.
     * Метод розраховує повну вартість кошика зі скидкою
     * а також повертає список цін для кожного з товарів
     * @basket - обєк кошика
     * @return array
    */
    public function calculateBasketTotalDiscountPrice($basket)
    {
        if ($basket->count() == 0)
            return 0;

        Bitrix\Sale\DiscountCouponsManager::freezeCouponStorage();
        $discounts = Discount::loadByBasket($basket);
        $basket->refreshData(array('PRICE', 'COUPONS'));
        $discounts->calculate();
        $discountResult = $discounts->getApplyResult();
        Bitrix\Sale\DiscountCouponsManager::unFreezeCouponStorage();
        if (empty($discountResult['PRICES']['BASKET']))
            return 0;
        $result = 0;
        $discountResult = $discountResult['PRICES']['BASKET'];
        /** @var BasketItem $basketItem */
        foreach ($basket as $basketItem)
        {
            if (!$basketItem->canBuy())
                continue;
            $code = $basketItem->getBasketCode();
            if (!empty($discountResult[$code]))
                $result += $discountResult[$code]['PRICE'] * $basketItem->getQuantity();
            unset($code);
        }
        unset($basketItem);
        return ['FULL_DISCOUNT_PRICE'=>$result, 'DISCOUNT_PRICE_LIST'=>$discountResult];
    }

    /**
     * V3.
     * Метод повертає всі скидки по кошику
     *  - Застарілий метод
    */
    public function getDiscountBasketOld()
    {
        $fuserId = CSaleBasket::GetBasketUserID();

        $dbBasketItems = CSaleBasket::GetList(
            array("ID" => "ASC"),
            array(
                "FUSER_ID" => $fuserId,
                "LID" => SITE_ID,
                "ORDER_ID" => "NULL",
                "DELAY"=>"N"
            ),
            false,
            false,
            array(
                "ID", "NAME", "CALLBACK_FUNC", "MODULE", "PRODUCT_ID", "PRODUCT_PRICE_ID", "QUANTITY", "DELAY", "CAN_BUY",
                "PRICE", "WEIGHT", "DETAIL_PAGE_URL", "NOTES", "CURRENCY", "VAT_RATE", "CATALOG_XML_ID",
                "PRODUCT_XML_ID", "SUBSCRIBE", "DISCOUNT_PRICE", "PRODUCT_PROVIDER_CLASS", "TYPE", "SET_PARENT_ID"
            )
        );

        $allSum = 0;
        $allWeight = 0;
        $arResult = array();

        while ($arBasketItems = $dbBasketItems->Fetch())
        {
            $allSum += ($arBasketItems["PRICE"] * $arBasketItems["QUANTITY"]);
            $allWeight += ($arBasketItems["WEIGHT"] * $arBasketItems["QUANTITY"]);
            $arResult[] = $arBasketItems;
        }

        $arOrder = array(
            'SITE_ID' => SITE_ID,
            'USER_ID' => $GLOBALS["USER"]->GetID(),
            'ORDER_PRICE' => $allSum, // сумма всей корзины
            'ORDER_WEIGHT' => $allWeight, // вес всей корзины
            'BASKET_ITEMS' => $arResult // товары сами
        );

        $arOptions = array(
            'COUNT_DISCOUNT_4_ALL_QUANTITY' => "Y",
        );

        $arErrors = array();

        CSaleDiscount::DoProcessOrder($arOrder, $arOptions, $arErrors);

        return $arOrder;
    }

    /**
     * Метод повертає кошик користувача
     * @return Object
    */
    protected function getBasketStorage()
    {
        if (!isset($this->basketStorage))
        {
            $this->basketStorage = Sale\Basket\Storage::getInstance(Fuser::getId(), Context::getCurrent()->getSite());
        }
        return $this->basketStorage;
    }

    /**
     * Ajax action - оновлюємо кошик відповідно до змін які прийшли
     */
    protected function refreshBasketAjaxAction()
    {

    }

    public function sendAjaxAnswer($data)
    {
        global $APPLICATION;

        AddMessage2Log(['sendAjaxAnswer'=>$data], "ArhicodeBasketSale");

        $APPLICATION->RestartBuffer();
        header('Content-Type: application/json');
        echo Json::encode($data);

        CMain::FinalActions();
        die();
    }

    /**
     * Виводимо результат оформлення замовлення
     */
    public function showOrderAction()
    {
        if(isset($this->request['d']) && $this->request['d'] == 'Y')
        {
            AddMessage2Log("executeComponent: d=Y", "ArhicodeBasketSale");
            $shipmentCollection = $this->order->getShipmentCollection();
            $shipment = $shipmentCollection->createItem();
            $shipment->setFields(array(
                'DELIVERY_ID' => 2,
                'DELIVERY_NAME' => 'Доставка курьером',
            ));
            // Сохраняем
            //$this->order->doFinalAction(true);
            //$this->order->save();
            //$this->order->getId();
            AddMessage2Log("executeComponent: ".$this->order->getId(), "ArhicodeBasketSale");
        }
    }

    /**
     * Без файла 'component.php' Для цього достатньо перекрити метод 'executeComponent'.
     *  - запускается при виконанні компонента.
     *  - Данний метод, підключає шаблон.
    */
    public function executeComponent()
    {
        global $APPLICATION;

        $this->context = Application::getInstance()->getContext();
        $isAjaxRequest = $this->request["is_ajax_post"] == "Y";
        if ($isAjaxRequest)
            $APPLICATION->RestartBuffer();

        $this->action = $this->prepareAction();
        Sale\Compatible\DiscountCompatibility::stopUsageCompatible();
        $this->doAction($this->action);
        Sale\Compatible\DiscountCompatibility::revertUsageCompatible();

        $this->includeComponentTemplate(); // підключається шаблон 'template.php'.

        if ($isAjaxRequest)
        {
            $APPLICATION->FinalActions();
            die();
        }

        return parent::executeComponent();
    }
}
?>