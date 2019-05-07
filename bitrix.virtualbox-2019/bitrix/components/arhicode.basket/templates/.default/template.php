<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)die();

use Bitrix\Main,
    Bitrix\Main\Localization\Loc,
    Bitrix\Main\Page\Asset;

Asset::getInstance()->addJs($templateFolder."/vue.min.js");
?>
<div id="ahc-order">
    <div class="ahc-nav-steps">
        <h2>{{message}}</h2>
        <div class="ahc-step-1">
            <span>1</span>
            Корзина
        </div>
        <div class="ahc-step-2">
            <span>2</span>
            Контактная информация
        </div>
        <div class="ahc-step-3">
            <span>3</span>
            Доставка и оплата
        </div>
        <div class="ahc-step-4">
            <span>4</span>
            Подтверждение
        </div>
    </div>
    <div class="ahc-panels">
        <div class="ahc-panel-1">

        </div>
        <div class="ahc-panel-2">

        </div>
        <div class="ahc-panel-3">

        </div>
        <div class="ahc-panel-4">

        </div>
    </div>
</div>

<script>
    var arhicodeSale = {
        message: 'TEST__1',
        result: <?=CUtil::PhpToJSObject($arResult);?>,
    };
</script>





<!-- ---------------------------------------------------------- -->
<hr>
<div>
    <h2>SALE</h2>
    <pre>
    <?php
        echo 'PARAMS:<br>';
        print_r($arParams);
        echo '<hr>';
        echo 'RESULT:<br>';
        print_r($arResult);
        //echo $templateFolder.'<br>';
        //echo SITE_TEMPLATE_PATH;
    ?>
    </pre>
</div>


