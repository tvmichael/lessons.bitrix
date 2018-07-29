<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();?>
<?
$arComponentParameters = array(
  "GROUPS" => array(),
  "PARAMETERS" => array(
    "TEMPLATE_FOR_DATE" => array(
      "PARENT" => "BASE",
      "NAME" => "Шаблон для даты",
      "TYPE" => "STRING",
      "MULTIPLE" => "N",
      "DEFAULT" => "Y-m-d",
      "REFRESH" => "Y",
    ),
    "TEMPLATE_FOR_TIME" => array(
      "PARENT" => "BASE",
      "NAME" => "Шаблон для времени",
      "TYPE" => "STRING",
      "MULTIPLE" => "N",
      "DEFAULT" => "Y-m-d",
      "REFRESH" => "Y",
    ),
  ),
);
?>
