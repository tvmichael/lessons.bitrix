<?php
/**
 * файл .parameters.php, который содержит описание
 * входных параметров компонента для редактора.
 * Если у компонента есть входные параметры,
 * то этот файл должен присутствовать в папке компонента
*/
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

use Bitrix\Main\Loader;
use Bitrix\Catalog;
use Bitrix\Iblock;

if (!Loader::includeModule('sale'))
    return;

if (!Loader::includeModule('iblock'))
    return;

$arIBlock = array();
$rsIBlock = CIBlock::GetList(array('ID' => 'ASC',), array('ACTIVE'=>'Y',));
while ($arr = $rsIBlock->Fetch())
{
    $arIBlock[$arr['ID']] = $arr['ID'].'. '.$arr['NAME'].' ('.$arr['CODE'].')';
}
unset($rsIBlock, $arr);

$arComponentParameters = array(
    "GROUPS" => array(
        "PARAMS" => array(
            "NAME" => GetMessage('SOA_PARAMS_PHR'),
        ),
    ),
    "PARAMETERS" => array(
        "BASKET_PAGE_TEMPLATE" => array(
            "PARENT" => "PARAMS",
            "NAME" => GetMessage('SOA_BASKET_PAGE_TEMPLATE'),
            "TYPE" => "STRING",
            "MULTIPLE" => "N",
            "DEFAULT" => "/personal/cart/",
            "COLS" => 25
        ),
        "ACTION_VARIABLE" => array(
            "NAME" => GetMessage('SOA_ACTION_VARIABLE'),
            "TYPE" => "STRING",
            "MULTIPLE" => "N",
            "DEFAULT" => "soa-action",
            "PARENT" => "ADDITIONAL_SETTINGS",
        ),
        "IBLOCK_TYPE_ID" => array(
            "PARENT" => "SETTINGS",
            "NAME" => GetMessage("SOA_INFOBLOCK_TYPE_PHR"),
            "TYPE" => "LIST",
            "ADDITIONAL_VALUES" => "Y",
            "VALUES" => $arIBlock,
            "REFRESH" => "Y"
        ),
    )
);
?>