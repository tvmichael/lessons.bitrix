<?php
/**
 * Class.php - это зарезервированное имя файла, и этот файл автоматически подключается при вызове:
 * $APPLICATION->IncludeComponent()
*/
define("LOG_FILENAME", $_SERVER["DOCUMENT_ROOT"]."/test/log.txt");
//AddMessage2Log("onPrepareComponentParams", "ArhicodeBasketSale");

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
    die();

use Bitrix\Sale;
use Bitrix\Sale\Basket;
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

    protected $errors = [];

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
            $this->arResult['Order'] = [
                'UserId'=>$this->order->getUserId(), // ID пользователя
                'OrderId'=>$this->order->getId(), // ID заказа
                'SiteId'=>$this->order->getSiteId(), // ID сайта
                'PersonTypeId'=>$this->order->getPersonTypeId(), // ID типа покупателя

                'Price'=>$this->order->getPrice(),// Цена с учетом скидок
                'DeliveryPrice'=>$this->order->getDeliveryPrice(), // Стоимость доставки
                'DiscountPrice'=>$this->order->getDiscountPrice(), // Размер скидки
                'SumPaid'=>$this->order->getSumPaid(), // Оплаченная сумма
                'Currency'=>$this->order->getCurrency(), // Валюта заказа
                //'Fields_#Name#_'=>$this->order->getField('PRICE'), // Также любое поле можно получить по имени
                //'AvailableFields'=>$this->order->getAvailableFields(), // Список доступных полей
            ];

            $this->arResult['BasePrice'] = $this->basket->getBasePrice(); // Цена без учета скидок
            $this->arResult['QuantityList'] = $this->basket->getQuantityList(); // возвращает массив "количеств" товаров в корзине
            $this->arResult['ListOfFormatText'] = $this->basket->getListOfFormatText(); // возвращает корзину в читаемом виде

            // получение объекта корзины и массива товаров в корзине:
            // getBasketItems(); // все товары
            // getOrderableItems(); // только товары, доступные для заказа
            $arr = [];
            $productListId = [];
            foreach ($this->basket->getBasketItems() as $items)
            {
                $productListId[] = $items->getProductId();
                $arr[$items->getProductId()] = [
                    'id' => $items->getId(), // ID позиции в корзине
                    'BasketCode' => $items->getBasketCode(), // код корзины
                    'Quantity' => $items->getQuantity(), // количество товара
                    'ProductId' => $items->getProductId(), // код товара
                    'FUserId' => $items->getFUserId(), // id владельца корзины
                    'canBuy' => $items->canBuy(), // товар доступен для покупки
                    'Currency' => $items->getCurrency(), // код валюты
                    'FinalPrice' => $items->getFinalPrice(), // стоимость всех единиц позиции товара
                    'Price' => $items->getPrice(), // цена с учетом скидок
                    'BasePrice' => $items->getBasePrice(), // цена без учета скидок
                    'DefaultPrice' => $items->getDefaultPrice(), // цена по умолчанию
                    'DiscountPrice' => $items->getDiscountPrice(), // величина скидки
                    //'CustomPrice' => $items->isCustomPrice(), // цена указана вручную (без использования провайдера)
                    //'AvailableFields' => $items->getAvailableFields(), //  массив кодов всех полей
                ];
            }
            $this->arResult['ProductList'] = $arr;
            unset($arr);

            // формуємо масив даних по товарах
            $this->arResult['ProductDataList'] = $this->getProductDataList($productListId);

            $this->arResult['PaymentSystemId'] = $this->order->getPaymentSystemId(); // массив id способів оплат (в поточному замовленні)
            $this->arResult['DeliverySystemId'] = $this->order->getDeliverySystemId(); // массив id способів доставки (в поточному замовленні)

            //$this->arResult['DiscountApplyResult'] = $this->order->getDiscount()->getApplyResult(); // список застосованих до замавлення знижок

        } catch (Exception $e) {
            $this->errors[] = ['message'=>'getOrderProps', 'error'=>$e->getMessage()];
        }
    }

    /**
     * Витягуємо інформацію по товару з кошика
     * @param bool $listId - масив $id товарів з корзини
     * @return array - масив з інформацією по товарах в кошику
     */
    public function getProductDataList($listId = false)
    {
        $products = [];
        if(isset($listId) && is_array($listId))
        {
            foreach ($listId as $id)
            {
                // перевіряємо 'товар' чи 'торгова пропозиція' (false - в случае ошибки;)
                $productSku =  CCatalogSku::GetProductInfo($id); //Метод позволяет получить по ID торгового предложения ID товара.

                if(is_array($productSku)) // торгова пропозиція, беремо ІД товара по торговій пропозиції
                    $products[$id] = $this->makeProductDetailInfo($productSku['ID'], $productSku);
                else // товар
                    $products[$id] = $this->makeProductDetailInfo($id);
            }
        }
        return $products;
    }

    /**
     * Беремо дані по товару
     * @param integer $id - код товару (чи тог=ргової пропозиції)
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
        $this->arResult["PAY_SYSTEM"] = [];
        while ($arPaySystemItem = $resPaySystem->Fetch()) {
            $this->arResult["PAY_SYSTEM_1"][] = $arPaySystemItem;
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
            $this->getSalePaySystem(); //
            $this->getSaleDelivery(); //
        } catch (Exception $e) {
            $this->arResult['errors'] = ['error'=>'processOrderAction', 'message'=>$e->getMessage()];
        }
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

        $this->context = Main\Application::getInstance()->getContext();
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