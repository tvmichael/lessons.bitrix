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

    private $arTempCondition = [
        'ACTIONS_LIST'=> null,
        'RECURSIVE'=> null,
        ];

    private $arRecursion = null;

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
        $arConditions = []; // виконана умова

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
                if ($children['DATA']['value'] == $this->arrayResult['IBLOCK_ID'] && $children['DATA']['logic'] == 'Equal') {
                    array_push($arConditions, $children['DATA']['value']);
                }
                break;
            case 'CondIBSection': // секція
                if ($children['DATA']['value'] == $this->arrayResult['SECTION']['IBLOCK_SECTION_ID'] && $children['DATA']['logic'] == 'Equal'){
                    array_push($arConditions, $children['DATA']['value']);
                }
                break;
            case 'CondIBCode': //символьний код
                if ($children['DATA']['value'] == $this->arrayResult['CODE'] && $children['DATA']['logic'] == 'Equal') {
                    array_push($arConditions, $children['DATA']['value']);
                }
                break;
            case 'CondBsktFldPrice': // ціна
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
                //Bitrix\Main\Diag\Debug::writeToFile(array('searchAccomplishConditions_price' => $arPrice), "", "/test/log.txt");
                switch ($children['DATA']['logic'])
                {
                    case 'Great':
                        if ( $arPrice["DISCOUNT_PRICE"] > $children['DATA']['value']){
                            array_push($arConditions, $children['DATA']['value']);
                        }
                        break;
                    case 'Equal':
                        if ( $arPrice["DISCOUNT_PRICE"] == $children['DATA']['value']){
                            array_push($arConditions, $children['DATA']['value']);
                        }
                        break;
                    case 'Less':
                        if ( $arPrice["DISCOUNT_PRICE"] < $children['DATA']['value']){
                            array_push($arConditions, $children['DATA']['value']);
                        }
                        break;
                }
                break;
            default:
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
        //Bitrix\Main\Diag\Debug::writeToFile(array('searchAccomplishConditions' => $children), "", "/test/log.txt");
        return $arConditions;
        /*
        // CONDITIONS_LIST
        if( is_array($discount['CONDITIONS_LIST']['CHILDREN']) )
        {
            // може бути умова ('або'-'і') виконані всі або лише одна
            $orAnd = 0;
            $orAndCount = 0;
            if ($discount['CONDITIONS_LIST']['DATA']['All'] == 'AND' &&
                $discount['CONDITIONS_LIST']['DATA']['True'] == 'True')
                $orAnd = count($discount['CONDITIONS_LIST']['CHILDREN']);

        // перебираємо масив умов
        foreach ($discount['CONDITIONS_LIST']['CHILDREN'] as $conditions)
        {
            if ( is_array($conditions['CHILDREN']) )
                foreach ( $conditions['CHILDREN'] as $children)
                {
                    switch ($children['CLASS_ID'])
                    {
                    case 'CondIBElement': // товар
                        $i = 0;
                        foreach ($children['DATA']['value'] as $value) {
                            if ($value == $this->arrayResult['ID'] && $children['DATA']['logic'] == 'Equal'){
                                array_push($this->countRuls['DELIVERY'], $value);
                                $i++;
                            }
                        }
                        if ($i > 0 ) $orAndCount ++;
                        break;
                    case 'CondIBIBlock': // інфоблок
                        if ($children['DATA']['value'] == $this->arrayResult['IBLOCK_ID'] && $children['DATA']['logic'] == 'Equal') {
                            array_push($this->countRuls['DELIVERY'], $children['DATA']['value']);
                            $orAndCount ++;
                        }
                        break;
                    case 'CondIBSection': // секція
                        if ($children['DATA']['value'] == $this->arrayResult['SECTION']['IBLOCK_SECTION_ID'] && $children['DATA']['logic'] == 'Equal'){
                            array_push($this->countRuls['DELIVERY'], $children['DATA']['value']);
                            $orAndCount ++;
                        }
                        break;
                    case 'CondIBCode': //символьний код
                        if ($children['DATA']['value'] == $this->arrayResult['CODE'] && $children['DATA']['logic'] == 'Equal') {
                            array_push($this->countRuls['DELIVERY'], $children['DATA']['value']);
                            $orAndCount ++;
                        }
                        break;
                    case 'CondBsktFldPrice': // ціна
                        switch ($children['DATA']['logic'])
                        {
                            case 'Great':
                                if ( $this->arrayResult['MIN_PRICE']['DISCOUNT_VALUE'] > $children['DATA']['value']){
                                    array_push($this->countRuls['DELIVERY'], $children['DATA']['value']);
                                    $orAndCount ++;
                                }
                                break;
                            case 'Equal':
                                if ( $this->arrayResult['MIN_PRICE']['DISCOUNT_VALUE'] == $children['DATA']['value']){
                                    array_push($this->countRuls['DELIVERY'], $children['DATA']['value']);
                                    $orAndCount ++;
                                }
                                break;
                            case 'Less':
                                if ( $this->arrayResult['MIN_PRICE']['DISCOUNT_VALUE'] < $children['DATA']['value']){
                                    array_push($this->countRuls['DELIVERY'], $children['DATA']['value']);
                                    $orAndCount ++;
                                }
                                break;
                         }
                        break;
                    default:
                        $property = explode(":", $children['CLASS_ID']);
                        if( is_array($property) && count($property) == 3 )
                        {
                            $db_props = CIBlockElement::GetProperty($property[1], $this->arrayResult['ID'], array("sort" => "asc"), Array('ID'=>$property[2]));
                            while ($props = $db_props->GetNext())
                            {
                                if($props['VALUE'] == $children['DATA']['value'] && $children['DATA']['logic'] == 'Equal') {
                                    //$this->countRuls['DELIVERY'] = $value;
                                    array_push($this->countRuls['DELIVERY'], $children['DATA']['value']);
                                    $orAndCount ++;
                                }
                            }
                        }
                    }
                }
        }

        if( $orAnd!=0 && $orAnd == $orAndCount)
            array_push($this->countRuls['DELIVERY'], $orAnd.'-Equal-'.$orAndCount );
        elseif($orAnd!=0) $this->countRuls['DELIVERY']=[];
        // array_push($this->countRuls['INFO'], array('Type'=>$actionsList['DATA']['Type']) );
        */
    }

    /**
     * Знаходимо всі умови для поточного правила. Змінена вартість доставки - ActSaleDelivery
     *
     * @param array $discount     - масив з даними для поточного правила
     * @return array $arCondition   масив з виконаними умовами
     */ 
    public function getActSaleDelivery($discount)
    {
        // очищаємо тимчасовий масив умов для правила
        $this->arTempCondition = [
            'ACTIONS_LIST'=> [],
            'RECURSIVE'=> [],
        ];
        // перебираємо всі ГОЛОВНІ умови для поточного правила і створюємо масив для вибірки
        foreach ($discount['CONDITIONS_LIST']['CHILDREN'] as $key => $arChildren )
        {
            $this->arRecursion = [];
            $this->recursiveSearchChildren($arChildren);

            $nCondGroup = 0;    // кількість груп треба забрати від загальної кількості
            $delivery = [];     //
            foreach ($this->arRecursion as $rec)
            {
                if ($rec['CLASS_ID'] !== 'CondGroup') {
                    $ar = $this->searchAccomplishConditions($rec);
                    //Bitrix\Main\Diag\Debug::writeToFile(array('ar' => $ar,), "", "/test/log.txt");
                    if (count($ar) > 0)
                        array_push($delivery, $ar);
                } else {
                    $nCondGroup++;
                }
            }

            //Bitrix\Main\Diag\Debug::writeToFile(array(
            //    'data' => $arChildren['DATA']['All'],
            //    'nCondGroup'=>$nCondGroup,
            //    'rec'=>count($this->arRecursion),
            //    'del'=>count($delivery),
            //    ), "", "/test/log.txt");

            // перевіряємо умову
            if ( $arChildren['DATA']['All']=='AND' && (count($this->arRecursion)-$nCondGroup) == count($delivery) ){
                array_push($this->arTempCondition['RECURSIVE'], $delivery);
            } elseif ($arChildren['DATA']['All'] == 'OR' && count($delivery) > 0 ){
                array_push($this->arTempCondition['RECURSIVE'], $delivery);
            }
        }


    }

    // надано подарок для товару
    public function getGiftCondGroup()
    {
        return [];
    }

    // змінена ціна товару
    public function getActSaleBsktGrp()
    {
        return [];
    }

    /**
     * Виконуєми при умові - детального перегляду товара
     */
    public function badgeCatalogElement()
    {
        // проходимо всі активні правила
        while ($discount = $this->obDiscountIterator->fetch()) 
        { 
            // перебираємо всі дії для ПОТОЧНОГО ПРАВИЛА і виконуємо відповідні функції
            foreach ($discount['ACTIONS_LIST']['CHILDREN'] as $actionsList) 
            {
                if ( $actionsList['CLASS_ID'] == 'ActSaleDelivery' ) 
                    $this->getActSaleDelivery($discount);
                elseif ($actionsList['CLASS_ID'] == 'GiftCondGroup' ) 
                    $this->getGiftCondGroup($discount);
                elseif ($actionsList['CLASS_ID'] == 'ActSaleBsktGrp' )
                    $this->getActSaleBsktGrp($discount);
                //---
                array_push( $this->arTempCondition['ACTIONS_LIST'], $actionsList['CLASS_ID'] );
            }
        }
        //---
        $this->countRuls = $this->arTempCondition;
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

}?>
