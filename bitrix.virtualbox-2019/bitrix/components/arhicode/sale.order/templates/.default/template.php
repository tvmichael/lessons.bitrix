<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)die();

/**
 * @var array $arParams
 * @var array $arResult
 * @var CMain $APPLICATION
 * @var CUser $USER
 * @var SaleOrderAjax $component
 * @var string $templateFolder
 */

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


<?
$signer = new Main\Security\Sign\Signer;
$signedParams = $signer->sign(base64_encode(serialize($arParams)), 'sale.order');
$messages = Loc::loadLanguageFile(__FILE__);
?>
<script>
    BX.message(<?=CUtil::PhpToJSObject($messages)?>);
    var arhicodeSaleOrder = {
        signedParamsString: '<?=CUtil::JSEscape($signedParams)?>',
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
        echo '<br>';
        print_r($signedParams);
        print_r($messages);
        echo '<hr>';
        echo 'RESULT:<br>';
        print_r($arResult);
        //echo $templateFolder.'<br>';
        //echo SITE_TEMPLATE_PATH;
    ?>
    </pre>
</div>


