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
        'INFO' => [
            'ACTIONS_LIST'=> [],
            'RECURSIVE'=>[],
        ],
    ];

    public $bb = [];

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

    private function initBadge()
    {
        if ( is_array($this->arrayResult) )
        {            
            $this->obDiscountIterator = $this->discountIterator();

            switch ($this->arrayParams['BADGE_CATALOG'])
            {
                case 0: $this->badgeCatalogElement();
                    break;
                case 1: $this->badgeCatalogSection();
                    break;
                case 2: $this->badgeCatalogItem();
                    break;
            }
        }
    }

    private function discountIterator()
    {
        return Bitrix\Sale\Internals\DiscountTable::getList ([
            'select' => ['ID', 'XML_ID', 'ACTIVE_FROM', 'ACTIVE_TO', 'UNPACK', 'CONDITIONS_LIST', 'ACTIONS_LIST'],
            'filter' => ['ACTIVE' => 'Y'], 
        ]);
    }

    /**
     * Рекурсивна функція пошуку полів зі значеннями для порівняння
     *
     * @array array вхідний масив
     * @return array
     */
    private function recursiveSearchChildren($array)
    {
        if(is_array($array['CHILDREN'])) {
            foreach ($array['CHILDREN'] as $key => $value) {
                if (is_array($value)) {
                    if (!isset($value['CHILDREN']) || (is_array($value['CHILDREN']) && count($value['CHILDREN']) == 0)) {
                        array_push($this->countRuls['INFO']['RECURSIVE'], $value);
                    } elseif (isset($value['DATA'])) {
                        array_push($this->countRuls['INFO']['RECURSIVE'], ['CLASS_ID' => $value['CLASS_ID'], 'DATA' => $value['DATA']]);
                    }
                    $this->recursiveSearchChildren($value);
                } else {
                    array_push($this->countRuls['INFO']['RECURSIVE'], $key);
                }
            }
        }
    }


    /**
     * Змінена вартість доставки - ActSaleDelivery
     *
     * @param array $discount     
     * @return array $arCondition   масив з виконаними умовами
     */ 
    public function getActSaleDelivery($discount)
    {
        $arCondition = 0;

        $this->countRuls['INFO']['RECURSIVE'] = [];
        foreach ($discount['CONDITIONS_LIST']['CHILDREN'] as $key => $arChildren )
        {
            if(isset($arChildren['DATA']))
                array_push($this->countRuls['INFO']['RECURSIVE'], ['LIST'=>$key, 'CLASS_ID' => $arChildren['CLASS_ID'], 'DATA' => $arChildren['DATA']]);

            $this->recursiveSearchChildren($arChildren);
        }








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
        }
                
                
                
        return $arCondition;
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

    // виконуєми при умові - детального перегляду товара    
    public function badgeCatalogElement()
    {
        // проходимо всі активні правила
        while ($discount = $this->obDiscountIterator->fetch()) 
        { 
            // перебираємо всі дії для поточного правила і виконуємо відповідні функції
            foreach ($discount['ACTIONS_LIST']['CHILDREN'] as $actionsList) 
            {
                $arTemp = []; // тимчасовий масив виконаних умов

                if ( $actionsList['CLASS_ID'] == 'ActSaleDelivery' ) 
                {
                    $arTemp = $this->getActSaleDelivery($discount);
                    //if(count($arTemp) > 0) 
                        array_push( $this->countRuls['DELIVERY'], $arTemp);
                }
                elseif ($actionsList['CLASS_ID'] == 'GiftCondGroup' ) 
                {
                    $arTemp = $this->getGiftCondGroup($discount);
                    //if(count($arTemp) > 0) 
                        array_push( $this->countRuls['GIFT'], $arTemp);
                }
                elseif ($actionsList['CLASS_ID'] == 'ActSaleBsktGrp' )
                {
                    $arTemp = $this->getActSaleBsktGrp($discount);
                    //if(count($arTemp) > 0) 
                        array_push( $this->countRuls['DISCOUNT'], $arTemp); 
                }
                //---
                array_push( $this->countRuls['INFO']['ACTIONS_LIST'], $actionsList['CLASS_ID'] );
            }
        }
    }
    
    public function badgeCatalogSection()
    {
        //Bitrix\Main\Diag\Debug::writeToFile(array('Section' => 2,),"","/test.one/log.txt");
    }
    
    public function badgeCatalogItem()
    {
        //Bitrix\Main\Diag\Debug::writeToFile(array('Item' => 3,),"","/test.one/log.txt");
    }

    
    public function executeComponent()
    {
        if($this->startResultCache())
        {
            $this->initBadge();
            $this->arResult = $this->countRuls;
            // Bitrix\Main\Diag\Debug::writeToFile(array(
            //     'params' => $this->$arParams,
            //     'result' => $this->arResult,
            // ),"","/test.one/log.txt");
            $this->includeComponentTemplate();
        }
        return $this->arResult;
    }

}?>
