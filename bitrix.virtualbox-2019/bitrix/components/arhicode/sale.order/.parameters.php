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

$siteId = isset($_REQUEST['src_site']) && is_string($_REQUEST['src_site']) ? $_REQUEST['src_site'] : '';
$siteId = substr(preg_replace('/[^a-z0-9_]/i', '', $siteId), 0, 2);


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
    )
);
?>