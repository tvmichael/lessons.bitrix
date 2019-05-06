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
            "NAME" => "PARAMS_PHR"
        ),
    ),
    "PARAMETERS" => array(
        "BASKET_PAGE_TEMPLATE" => array(
            "PARENT" => "PARAMS",
            "NAME" => "BASKET_LINK_PHR",
            "TYPE" => "STRING",
            "MULTIPLE" => "N",
            "DEFAULT" => "/personal/basket.php",
            "COLS" => 25
        ),
    )
);
?>