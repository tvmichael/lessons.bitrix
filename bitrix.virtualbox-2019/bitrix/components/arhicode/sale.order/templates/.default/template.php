<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)die();
/**
 * @var array $arParams
 * @var array $arResult
 * @var CMain $APPLICATION
 * @var CUser $USER
 * @var SaleOrder $component
 * @var string $templateFolder
 */

use Bitrix\Main,
    Bitrix\Main\Localization\Loc;

$arrId = [
    'formId' => 'ahc-soa-order-form',
    'saleOrder'=>'ach-sale-order',
];
?>

<div class="ahc-sale-order">
    <form action="<?=POST_FORM_ACTION_URI;?>" method="POST" name="<?=$arrId['formId'];?>" id="<?=$arrId['formId'];?>" enctype="multipart/form-data">
        <div class="ahc-nav-steps">
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
            <div class="ahc-panel-left">
                <div class="ahc-panel-1">
                    <h3>Ваш заказ</h3>
                    <div class="ahc-product-block">
                        <? foreach ($arResult['PRODUCT_LIST'] as $key=>$item):?>
                            <div class="ahc-product">
                                <div class="ahc-product-img">
                                    <a href="<?=$item['PROPS']['DETAIL_PAGE_URL'];?>">
                                        <img src="#" alt="<?=$item['PROPS']['NAME'];?>">
                                    </a>
                                </div>
                                <div class="ahc-product-info">
                                    <h4><?=$item['PROPS']['NAME'];?></h4>
                                    <div class="ahc-product-panel" data-product-id="<?=$item['PRODUCT_ID'];?>">
                                        <div class="ahc-product-delete">
                                            <div class="ahc-delete">Удалить</div>
                                        </div>
                                        <div class="ahc-product-quantity">
                                            <label>Количество</label>
                                            <div>
                                                <span class="ahc-minus">&#8211;</span>
                                                <input class="ahc-quantity" type="text" value="<?=$item['QUANTITY'];?>">
                                                <span class="ahc-plus">+</span>
                                            </div>
                                        </div>
                                        <div class="ahc-product-price">
                                            <div>Стоимость</div>
                                            <div class="ahc-price-discount"><?=$item['FORMAT_PRICE'];?></div>
                                            <? if($item['PRICE']!=$item['BASE_PRICE']):?>
                                                <div class="ahc-price"><?=$item['FORMAT_BASE_PRICE'];?></div>
                                            <? endif;?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <? endforeach;?>
                    </div>
                </div>
                <div class="ahc-panel-2">

                </div>
                <div class="ahc-panel-3">

                </div>
                <div class="ahc-panel-4">

                </div>
            </div>
            <div class='ahc-panel-right'>
                <div class="ahc-total-info">

                </div>
            </div>
        </div>
    </form>
</div>

<?
$signer = new Main\Security\Sign\Signer;
$signedParams = $signer->sign(base64_encode(serialize($arParams)), 'sale.order');
$messages = Loc::loadLanguageFile(__FILE__);
?>
<script>
    BX.message(<?=CUtil::PhpToJSObject($messages)?>);
    BX.Sale.ArhicodeSaleOrder.init({
        signedParamsString: '<?=CUtil::JSEscape($signedParams)?>',
        result: <?=CUtil::PhpToJSObject($arResult);?>,
        arrId: <?=CUtil::PhpToJSObject($arrId);?>,
        siteID: '<?=CUtil::JSEscape($component->getSiteId());?>',
        ajaxUrl: '<?=CUtil::JSEscape($component->getPath().'/ajax.php');?>',
        actionVariable: '<?=CUtil::JSEscape($arParams['ACTION_VARIABLE']);?>',
    });
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


