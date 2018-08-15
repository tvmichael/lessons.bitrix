<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();


CModule::IncludeModule("iblock");

$dbIBlockType = CIBlockType::GetList(
    array("sort" => "asc"),
    array("ACTIVE" => "Y")
);
while ($arIBlockType = $dbIBlockType->Fetch())
{
    if ($arIBlockTypeLang = CIBlockType::GetByIDLang($arIBlockType["ID"], LANGUAGE_ID))
        $arIblockType[$arIBlockType["ID"]] = "[".$arIBlockType["ID"]."] ".$arIBlockTypeLang["NAME"];
}


$arComponentParameters = array(
    "GROUPS" => array(
        "SETTINGS" => array(
            "NAME" => "SETTINGS_PHR-100",
            "SORT" => "1000",
        ),
        "PARAMS" => array(
            "NAME" => "PARAMS_PHR-10",
            "SORT" => "2000",
        ),
    ),
    "PARAMETERS" => array(
        "TEMPLATE_FOR_ARRESULT" => array(
            "PARENT" => "BASE",
            "NAME" => "Ar Result",
            "TYPE" => "STRING",
            "DEFAULT" => '={$arResult}',
            "VALUES"=> '={$arResult}',
        ),
        "TEMPLATE_FOR_BADGE" => array(
            "PARENT" => "BASE",
            "NAME" => GetMessage("BADGE_ACTION_NAME"),
            "TYPE" => "STRING",
            "MULTIPLE" => "N",
            "DEFAULT" => "Y",
            "REFRESH" => "Y",
        ),
        "TEMPLATE_FOR_ARRAY" => array(
            "PARENT" => "BASE",
            "NAME" => "BADGE_ACTION_NAME_ARRAY-1",
            "TYPE" => "LIST",
            "MULTIPLE" => "Y",
            "ADDITIONAL_VALUES" => 'N',
            "DEFAULT" => "Y",
            "REFRESH" => "Y",
            "VALUES"=>array(1,2,3,4,5),
            "SIZE"=> 10,
        ),
        "IBLOCK_TYPE_ID" => array(
            "PARENT" => "SETTINGS",
            "NAME" => "INFOBLOCK_TYPE_PHR-2",
            "TYPE" => "LIST",
            "ADDITIONAL_VALUES" => "Y",
            "VALUES" => $arIblockType,
            "REFRESH" => "Y"
        ),
        "BASKET_PAGE_TEMPLATE" => array(
            "PARENT" => "PARAMS",
            "NAME" => "BASKET_LINK_PHR-3",
            "TYPE" => "STRING",
            "MULTIPLE" => "N",
            "DEFAULT" => "/personal/basket.php",
            "COLS" => 25
        ),
        "ECO_DATA_SOURCE" =>array(
            "PARENT"=>"DATA_SOURCE",
            "NAME" => "DATA_SOURCE_BASKET_LINK_PHR-3-3",
            "TYPE" => "STRING",
            "MULTIPLE" => "N",
            "DEFAULT" => "DATA",
            "COLS" => 25
        ),

        "SET_TITLE" => array(),
        "CACHE_TIME" => array(),

        "VARIABLE_ALIASES" => array(
            "IBLOCK_ID" => array(
                "NAME" => "CATALOG_ID_VARIABLE_PHR-4",
            ),
            "SECTION_ID" => array(
                "NAME" => "SECTION_ID_VARIABLE_PHR-5",
            ),
        ),

        "SEF_MODE" => array(
            "list" => array(
                "NAME" => "CATALOG_LIST_PATH_TEMPLATE_PHR-6",
                "DEFAULT" => "index.php",
                "VARIABLES" => array()
            ),
            "section1" => array(
                "NAME" => "SECTION_LIST_PATH_TEMPLATE_PHR-7",
                "DEFAULT" => "#IBLOCK_ID#",
                "VARIABLES" => array("IBLOCK_ID")
            ),
            "section2" => array(
                "NAME" => "SUB_SECTION_LIST_PATH_TEMPLATE_PHR-8",
                "DEFAULT" => "#IBLOCK_ID#/#SECTION_ID#",
                "VARIABLES" => array("IBLOCK_ID", "SECTION_ID")
            ),
        ),
    ),
);
?>