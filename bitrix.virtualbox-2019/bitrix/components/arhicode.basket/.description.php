<?php
/**
 * Файл .description.php, который содержит название, описание компонента
 * и его положение в дереве логического размещения (для редактора).
 * Этот файл должен всегда присутствовать в папке компонента.
 * Его отсутствие не скажется на работе компонента, но размещение
 * компонента через визуальный редактор станет невозможным.
 * https://dev.1c-bitrix.ru/learning/course/index.php?COURSE_ID=43&LESSON_ID=2828&LESSON_PATH=3913.4565.2828
 */
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

$arComponentDescription = array(
    "NAME" => GetMessage("COMP_NAME"),
    "DESCRIPTION" => GetMessage("COMP_DESCR"),
    "ICON" => "/images/sale.gif",
    "PATH" => array(
        "ID" => "arhicode",
        'NAME' => GetMessage("PATH_NAME"),
        "CHILD" => array(
            "ID" => "basket_sale",
            "NAME" => GetMessage("CHILD_NAME"),
        )
    ),
    "AREA_BUTTONS" => array(
        array(
            'URL' => "javascript:alert('AREA_BUTTONS_NAME');",
            'SRC' => '/images/cart.png',
            'TITLE' => GetMessage("AREA_BUTTONS_NAME"),
        ),
    ),
    "CACHE_PATH" => "Y",
    "COMPLEX" => "N"
);


?>
