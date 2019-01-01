<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Інфоблоки");
?>
<div style="font-size: 16px">
    <div class="col-md-12">
        <h4>"bitrix:iblock.vote", "ajax"</h4>
        <?
        /*
        $APPLICATION->IncludeComponent(
            "bitrix:iblock.vote",
            "ajax",
            Array(
                'IBLOCK_TYPE' => '', // 4, //$arParams['IBLOCK_TYPE'],
                'IBLOCK_ID' => 3, //$arParams['IBLOCK_ID'],
                'ELEMENT_ID' => 177, //$arResult['ID'],
                "CACHE_TIME" => "36000000",
                "CACHE_TYPE" => "A",
                "ELEMENT_CODE" => $_REQUEST["code"],
                "MAX_VOTE" => "5",
                "MESSAGE_404" => "",
                "SET_STATUS_404" => "N",
                "VOTE_NAMES" => array("1","2","3","4","5","")
            )
        );

        // "IBLOCK_TYPE" => "catalog",              // Тип инфоблока
        // "IBLOCK_ID" => $arParams["IBLOCK_ID"],   // Инфоблок
		// "ELEMENT_ID" => $arElement["ID"],        // ID элемента
        // "ELEMENT_CODE" => "",
        /**/
        ?>
        <br>
    </div>



    <div class="col-md-12">
        <h5>масив назв інфоблоків </h5>
        <pre>
            <?php
                /*
            if(!CModule::IncludeModule("iblock"))
                return;
            $arIBlockType = CIBlockParameters::GetIBlockTypes(); // список усіх інфоблоків
            $arIBlockType = Bitrix\Iblock\TypeTable::getList(array('select' => array('*')))->FetchAll();
            //$arIBlockType = Bitrix\Iblock\TypeTable::getList(array('select' => array('*', 'LANG_MESSAGE')))->FetchAll();
            var_dump($arIBlockType); //
            /**/
            ?>
        </pre>
        </br>


        <h5>масив інфоблоків в2 </h5>
        <pre>
            <?php
/*
            use Bitrix\Iblock;
            $propertyIterator = Iblock\PropertyTable::getList(array(
                'select' => array('ID', 'IBLOCK_ID', 'NAME', 'CODE', 'PROPERTY_TYPE', 'MULTIPLE', 'LINK_IBLOCK_ID', 'USER_TYPE', 'SORT'),
                //'filter' => array('=IBLOCK_ID' => '2', '=ACTIVE' => 'Y'),
                'order' => array('SORT' => 'ASC', 'NAME' => 'ASC')
            ));
            //print_r($propertyIterator);
            while ($property = $propertyIterator->fetch())
            {
                print_r($property);
            }
            /**/
            ?>
        </pre>
        </br>


        <h5>масив інфоблоків в3 </h5>
        <pre>
            <?php

            $arIBlock = array();
            $rsIBlock = CIBlock::GetList(Array("sort" => "asc"), Array("LID" => 's1'));
            //var_dump($rsIBlock); //
            //echo '<hr></br>';
            while($arr = $rsIBlock->Fetch())
            {
                print_r($arr);
                //$arIBlock[$arr["ID"]] = "[".$arr["ID"]."] ".$arr["NAME"];
            }
            /**/
            ?>
        </pre>
        </br>


        <h5>інфоблок 'ID' => '181' </h5>
        <pre>
            <?php
            /*
            $rsEls = CIBlockElement::GetList(array(
                //'select' => array('ID', 'NAME', 'SORT'),
                //'filter' => array('=ID' => '2'),
                //'order' => array('ID' => 'ASC')
            ),
                array(
                    'ID' => '181'
                )
            );
            while ($property = $rsEls->fetch())
            {
                print_r($property);
            }
            /**/
            ?>
        </pre>
        </br>

        <h5>інфоблок за номером 'ID' => '181', блоки менше 18 </h5>
        <pre>
            <?php
            /*
            $res = CIBlockElement::GetByID(181);
            if($ar_res = $res->GetNext())
                print_r($ar_res);
            echo '<hr> усі блоки < 18 <br><br>';

            $arSelect = Array("ID", "IBLOCK_ID");
            $arFilter = Array("<ID"=>"18");
            $res = CIBlockElement::GetList(Array(), $arFilter, false, false, $arSelect);
            while($ob = $res->GetNext())
            {
                print_r($ob);
            }
            /**/
            ?>
        </pre>
        </br>

        <h5>інфоблок групи-3 з номером-179 </h5>
        <pre>
            <?php
            /*
            $db_props = CIBlockElement::GetProperty(3, 179, array("sort" => "asc"), Array());
            $PROPS = array();
            while($ar_props = $db_props->Fetch())
                $PROPS[] = $ar_props;
            print_r($PROPS);

            // формула для обчислення рейтингу
            // $arProperties["rating"]["VALUE"] = round(($arProperties["vote_sum"]["VALUE"]+31.25/5*$arParams["MAX_VOTE"])/($arProperties["vote_count"]["VALUE"]+10),2);
            /*
            $DB->StartTransaction();
            CIBlockElement::SetPropertyValuesEx(179, 3, array(
                "vote_count" => array(
                    "VALUE" => 111, //  $arProperties["vote_count"]["VALUE"],
                    //DESCRIPTION" => $arProperties["vote_count"]["DESCRIPTION"],
                ),
                "vote_sum" => array(
                    "VALUE" => 222, // $arProperties["vote_sum"]["VALUE"],
                    //"DESCRIPTION" => $arProperties["vote_sum"]["DESCRIPTION"],
                ),
                "rating" => array(
                    "VALUE" => 333, // $arProperties["rating"]["VALUE"],
                    //"DESCRIPTION" => $arProperties["rating"]["DESCRIPTION"],
                ),
            ));
            $DB->Commit();
            /**/
            ?>
        </pre>
        </br>


        <h5>значення усіх VALUE для усіх властивостей даного блоку (групи-2 з номером-14) </h5>
        <pre>
            <?php

            $db_props = CIBlockElement::GetProperty(2, 14, "sort", "asc", array());
            $PROPS = array();
            while($ar_props = $db_props->Fetch())
                $PROPS[$ar_props['CODE']] = $ar_props['VALUE'];

            print_r($PROPS);
            /**/
            ?>
        </pre>
        </br>


    </div>







</div>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>