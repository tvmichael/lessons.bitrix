<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

class CBadgesCheck extends CBitrixComponent
{
    private $arrayResult = null;        // масив результатів

    private $arrayParams = null;        // масив параметрів

    private $obDiscountIterator = null; // масив з вихідними правилами

    private $countRuls = [              // масив виконаних правил для показу бейджів
        'DELIVERY' => [],        
        'DISCOUNT' => [],
        'GIFT' => [],
        'CERTIFICATE' => [],
        'STOCK' => [],
    ];

    private $arTempCondition = [];      // тимчасовий масив з виконаними внітрішніми умовами

    private $arRecursion = null;        // масив для рекурсивної функції

    /**
     * Processing parameters unique to badge component.
     *
     * @param array $params       Component parameters.
     * @return array
     */
    public function onPrepareComponentParams($arParams)
    {
        //$params = parent::onPrepareComponentParams($arParams);
        if ($arParams['BADGE_CATALOG'] == 0)
            $this->arrayResult = $arParams['BADGE_ARRAY'];

        $arParams['BADGE_ARRAY'] = null;
        $this->arrayParams = $arParams;
        // Bitrix\Main\Diag\Debug::writeToFile(array(
        //         'params-0' => $this->arParams,
        //         'result-0' => $this->arResult,
        //     ),"","/test.one/log.txt");
        return $arParams;
    }

    /**
     * Вхідна функція.
     */
    private function initBadge()
    {
        if ( is_array($this->arrayResult) )
        {            
            $this->obDiscountIterator = $this->discountIterator();

            switch ($this->arrayParams['BADGE_CATALOG'])
            {
                case 0: $this->badgeCatalogElement();
                    break;
                case 1: $this->badgeCatalogItem();
                    break;
            }
        }
    }

    /**
     * Вибірка масиву правил.
     */
    private function discountIterator()
    {
        return Bitrix\Sale\Internals\DiscountTable::getList ([
            'select' => ['ID', 'XML_ID', 'ACTIVE_FROM', 'ACTIVE_TO', 'CONDITIONS_LIST', 'ACTIONS_LIST', 'USE_COUPONS'],
            'filter' => ['ACTIVE' => 'Y'], 
        ]);
    }

    /**
     * Рекурсивна функція пошуку полів зі значеннями для порівняння
     *
     * @array array вхідний масив
     * @return - (заповнюємо масив - countRuls)
     */
    private function recursiveSearchChildren($array)
    {
        if(is_array($array['CHILDREN'])) {
            foreach ($array['CHILDREN'] as $key => $value) {
                if (is_array($value)) {
                    if (!isset($value['CHILDREN']) || (is_array($value['CHILDREN']) && count($value['CHILDREN']) == 0)) {
                        array_push($this->arRecursion, $value);
                    } elseif (isset($value['DATA'])) {
                        array_push($this->arRecursion, ['CLASS_ID' => $value['CLASS_ID'], 'DATA' => $value['DATA']]);
                    }
                    $this->recursiveSearchChildren($value);
                } else {
                    array_push($this->arRecursion, $key);
                }
            }
        }
    }

    /**
     * Передаємо конкрутну умову для пошуку
     *
     * @array $children     - вхідна умова
     * @return              - масив
     */
    private function searchAccomplishConditions($children)
    {
        //$this->PPPP($children, 'searchAccomplishConditions::');
        $arConditions = []; // виконана умова
        // може бути прописано по різному
        if (isset($children['DATA']['value'])) $dataValue = $children['DATA']['value'];
            elseif (isset($children['DATA']['Value'])) $dataValue = $children['DATA']['Value'];
        // вибираємо конкретну умову
        switch ($children['CLASS_ID'])
        {
            case 'CondIBElement': // товар
                $i = 0;
                foreach ($children['DATA']['value'] as $value) {
                    if ($value == $this->arrayResult['ID'] && $children['DATA']['logic'] == 'Equal'){
                        array_push($arConditions, $value);
                        $i++;
                    }
                }
                break;
            case 'CondIBIBlock': // інфоблок
                if ($dataValue == $this->arrayResult['IBLOCK_ID'] && $children['DATA']['logic'] == 'Equal') {
                    array_push($arConditions, $dataValue);
                }
                break;
            case 'CondIBSection': // секція
                $s =  $this->arrayResult['IBLOCK_SECTION_ID'] || $this->arrayResult['SECTION']['IBLOCK_SECTION_ID'];
                //$this->PPPP([$dataValue, $s ], 'section');
                if (($dataValue == $this->arrayResult['IBLOCK_SECTION_ID'] || $dataValue == $this->arrayResult['SECTION']['IBLOCK_SECTION_ID']) && $children['DATA']['logic'] == 'Equal'){
                    array_push($arConditions, $dataValue);
                }
                break;
            case 'CondIBCode': //символьний код
                if ($dataValue == $this->arrayResult['CODE'] && $children['DATA']['logic'] == 'Equal') {
                    array_push($arConditions, $dataValue);
                }
                break;
            case 'CondBsktFldPrice':        // ціна товару
            case 'CondBsktAmtGroup':        // загальна вартість товарів

                $dbPrice = CPrice::GetList(
                    array("SORT" => "ASC"),
                    array("PRODUCT_ID" => $this->arrayResult['ID']),
                    false,
                    false,
                    array("ID", "CATALOG_GROUP_ID", "PRICE", "CURRENCY")
                );
                $arPrice = $dbPrice->Fetch();
                $arDiscounts = CCatalogDiscount::GetDiscountByPrice(
                    $arPrice["ID"],
                    array(),
                    "N",
                    SITE_ID
                );
                $discountPrice = CCatalogProduct::CountPriceWithDiscount(
                    $arPrice["PRICE"],
                    $arPrice["CURRENCY"],
                    $arDiscounts
                );
                $arPrice["DISCOUNT_PRICE"] = $discountPrice;
                //$this->PPPP([$arPrice["DISCOUNT_PRICE"], $children['DATA']['Value'] ], 'PRICE');
                switch ($children['DATA']['logic'])
                {
                    case 'EqGr':
                    case 'Great':
                        if ( $arPrice["DISCOUNT_PRICE"] > $dataValue){ array_push($arConditions, $dataValue); }
                        break;
                    case 'Equal':
                        if ( $arPrice["DISCOUNT_PRICE"] == $dataValue){ array_push($arConditions, $dataValue); }
                        break;
                    case 'EqLs':
                    case 'Less':
                        if ( $arPrice["DISCOUNT_PRICE"] < $dataValue){ array_push($arConditions, $dataValue); }
                        break;
                    case 'Not':
                        if ( $arPrice["DISCOUNT_PRICE"] != $dataValue){ array_push($arConditions, $dataValue); }
                        break;
                }
                break;
            default: // властивості
                $property = explode(":", $children['CLASS_ID']);
                if( is_array($property) && count($property) == 3 )
                {
                    $db_props = CIBlockElement::GetProperty($property[1], $this->arrayResult['ID'], array("sort" => "asc"), Array('ID'=>$property[2]));
                    while ($props = $db_props->GetNext())
                    {
                        if($props['VALUE'] == $children['DATA']['value'] && $children['DATA']['logic'] == 'Equal') {
                            array_push($arConditions, $children['DATA']['value']);
                        }
                    }
                }
        }
        //$this->PPPP($arConditions, 'arConditions', 'blue');
        return $arConditions;
    }

    /**
     * Знаходимо всі умови для ПОТОЧНОГО правила. Змінена вартість доставки - ActSaleDelivery
     *
     * @param array $discount     - масив з даними для поточного правила
     * @return array $arCondition   масив з виконаними умовами
     */ 
    public function getActSaleDelivery($discount)
    {
        $arTempCondition = [];  // очищаємо тимчасовий масив умов для виконаних умов
        $parentCondition = 0;   //

        // перебираємо всі ГОЛОВНІ умови для поточного правила і створюємо масив для вибірки
        foreach ($discount['CONDITIONS_LIST']['CHILDREN'] as $key => $arChildren )
        {
            //$this->PPPP($arChildren, 'arChildren==='.$discount['ID']);
            $this->arRecursion = [];

            // 1 якщо існує зовнішня умова
            if (isset($arChildren['DATA']['Value']) && isset($arChildren['DATA']['logic']))
            {
                $i = [ 'CHILDREN'=> ['0' => ['CLASS_ID' => $arChildren['CLASS_ID'],'DATA' => $arChildren['DATA'],'CHILDREN' =>[]]] ];
                //$this->PPPP($i, '$i');
                $this->recursiveSearchChildren($i);
            }
            // 2 для загального масиву
            $this->recursiveSearchChildren($arChildren);

            $nCondGroup = 0;    // кількість груп треба забрати від загальної кількості
            $delivery = [];     //
            //$this->PPPP($this->arRecursion, '$this->arRecursion');
            foreach ($this->arRecursion as $rec)
            {
                if ($rec['CLASS_ID'] !== 'CondGroup') { // підраховуємо кількість пунктів без умов
                    $ar = $this->searchAccomplishConditions($rec);
                    if (count($ar) > 0)
                        array_push($delivery, $ar);
                } else {
                    $nCondGroup++;
                }
            }

            // перевіряємо (і - або) умови виконалися
            if(count($delivery) > 0) // виконалося хоча б одна умова (внутрішній рівень)
                if ( $arChildren['DATA']['All']=='AND' && (count($this->arRecursion)-$nCondGroup) == count($delivery) ){
                    array_push($arTempCondition, $delivery);
                } elseif ($arChildren['DATA']['All'] == 'OR'){
                    array_push($arTempCondition, $delivery);
                }
            //$this->PPPP($delivery, 'delivery');
        }

        //$this->PPPP([count($arTempCondition),count($discount['CONDITIONS_LIST']['CHILDREN'])], 'COND:', '#006400');
        // перевіряємо чи всі умови для даного правила виконані (і - або) або лише одна
        if(count($arTempCondition) > 0) // виконалося хоча б одна загальна умова (зовнішній рівень)
            if ( $discount['CONDITIONS_LIST']['DATA']['All']=='AND' && count($discount['CONDITIONS_LIST']['CHILDREN']) == count($arTempCondition) ){
                return $arTempCondition;
            } elseif ($discount['CONDITIONS_LIST']['DATA']['All'] == 'OR'){
                return $arTempCondition;
            }

        //$this->PPPP($arTempCondition, 'arTempCondition', '#006400');
        return [];
    }

    // надано подарок для товару
    public function getGiftCondGroup()
    {
        // очищаємо тимчасовий масив умов для виконаних умов
        $arTempCondition = [];


        return $arTempCondition;
    }

    // змінена ціна товару
    public function getActSaleBsktGrp()
    {
        // очищаємо тимчасовий масив умов для виконаних умов
        $arTempCondition = [];


        return $arTempCondition;
    }

    /**
     * Виконуєми при умові - детального перегляду товара
     */
    public function badgeCatalogElement()
    {
        // проходимо всі активні правила
        while ($discount = $this->obDiscountIterator->fetch()) 
        { 
            $operation = 0;
            $arTempCondition = [];
            // перебираємо всі дії для ПОТОЧНОГО ПРАВИЛА і виконуємо відповідні функції
            foreach ($discount['ACTIONS_LIST']['CHILDREN'] as $actionsList) 
            {
                if ( $actionsList['CLASS_ID'] == 'ActSaleDelivery' )
                {
                    $arTempCondition['ActSaleDelivery'] = [];
                    $ar = $this->getActSaleDelivery($discount);
                    //$this->PPPP($ar, '$ar-DELIVERY-if >>>', 'red');
                    if( count($ar) > 0 ) {
                        //$this->PPPP($ar, '$ar-DELIVERY');
                        array_push($arTempCondition['ActSaleDelivery'], $ar);
                        $operation++; 
                    }
                    $this->PPPP($arTempCondition, 'ActSaleDelivery');
                }
                elseif ($actionsList['CLASS_ID'] == 'GiftCondGroup' ) 
                {
                    $this->getGiftCondGroup($discount);
                    //$operation++;
                }
                elseif ($actionsList['CLASS_ID'] == 'ActSaleBsktGrp' )
                {
                    $this->getActSaleBsktGrp($discount); 
                    //$operation++;
                }      
            }

            $this->PPPP([$operation, count($discount['ACTIONS_LIST']['CHILDREN'])], 'ALL');
            // УВАГА: перевіряємо ДІЇ
            // перевіряємо чи повинні виконатися усі ДІЇ для правила чи хоча б одна
            if($discount['ACTIONS_LIST']['DATA']['All'] == 'AND' && $operation == count($discount['ACTIONS_LIST']['CHILDREN']))
            {
                if(count($arTempCondition['ActSaleDelivery']) > 0) // для доставки
                {
                    array_push($this->countRuls['DELIVERY'], [
                        'CONDITIONS'=>$arTempCondition['ActSaleDelivery'],
                        'XML_ID'=>$discount['XML_ID'],
                        'ACTIVE_FROM'=>$discount['ACTIVE_FROM'],
                        'ACTIVE_TO'=>$discount['ACTIVE_TO'],
                        'USE_COUPONS'=>$discount['USE_COUPONS'],
                        ]);
                    if ($discount['USE_COUPONS'] == 'Y')
                        array_push($this->countRuls['CERTIFICATE'], [                            
                            'XML_ID'=>$discount['XML_ID'],
                            'ACTIVE_FROM'=>$discount['ACTIVE_FROM'],
                            'ACTIVE_TO'=>$discount['ACTIVE_TO'],
                            'USE_COUPONS'=>$discount['USE_COUPONS'],
                        ]);
                    if ($discount['XML_ID'] == $this->arrayParams['SHOW_BADGES_STOCK_XML_ID'])
                        array_push($this->countRuls['STOCK'], [                            
                            'XML_ID'=>$discount['XML_ID'],
                            'ACTIVE_FROM'=>$discount['ACTIVE_FROM'],
                            'ACTIVE_TO'=>$discount['ACTIVE_TO'],
                        ]);
                }
            }
            elseif ($discount['ACTIONS_LIST']['DATA']['All'] == 'OR' && $operation > 0) // виконалась одна з дій
            {

                if(count($arTempCondition['ActSaleDelivery']) > 0)
                    array_push($this->countRuls['DELIVERY'], $arTempCondition['ActSaleDelivery']);
            }
        }
    }

    /**
     * Виконуєми при умові - що завантажено catalog.item
     */
    public function badgeCatalogItem()
    {
        //Bitrix\Main\Diag\Debug::writeToFile(array('Section' => 2,),"","/test.one/log.txt");
    }


    /**
     * Очищаємо масиви з даними
     */
    private function unsetArray()
    {
        unset($this->arrayParams, $this->arrayResult, $this->arTempCondition, $this->obDiscountIterator);
    }

    /**
     * Виконує фінальні налаштування компонента
     */
    public function executeComponent()
    {
        if($this->startResultCache())
        {
            $this->initBadge();
            $this->arResult = $this->countRuls;
            $this->includeComponentTemplate();
        }
        $this->unsetArray();
        // Bitrix\Main\Diag\Debug::writeToFile(array(
        //     'params' => $this->$arParams,
        //     'result' => $this->arResult,
        // ),"","/test.one/log.txt");
        return $this->arResult;
    }

    //
    public function PPPP($arr,$inf = '',$color='black')
    {   
        echo "<div style='position: relative; z-index: 999;'><h4 style='background-color: white; color:$color'>".$inf."</h4><pre>";
        print_r($arr);
        echo "</pre></div>";
    }

}?>
