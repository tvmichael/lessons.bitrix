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
$APPLICATION->SetTitle('Моя корзина');

use Bitrix\Main,
    Bitrix\Main\Localization\Loc;

CJSCore::Init(['masked_input']);

$arrId = [
    'formId' => 'ahc-soa-order-form',

    'saleOrder'=>'ach-sale-order',
    'basePrice' => 'ach-base-price',
    'totalPrice' => 'ach-total-price',
    'discount' => 'ach-discount',
    'buttonStep'=>'ahc-btn-step',
    'allowOrder'=>'ahc-allow-order',

    'userName'=>'ach-user-name',
    'userPhone'=>'ach-user-phone',
    'userEmail'=>'ach-user-email',

];

CJSCore::Init(array('currency'));
$currencyFormat = CCurrencyLang::GetFormatDescription($arResult['ORDER']['CURRENCY']);
?>

<div class="ahc-sale-order">
    <form action="<?=POST_FORM_ACTION_URI;?>" method="POST" name="<?=$arrId['formId'];?>" id="<?=$arrId['formId'];?>" enctype="multipart/form-data">
        <div class="ahc-nav-steps">
            <div class="ahc-step-btn ahc-step-btn-1 current-step">
                <span>1</span>
                Корзина
            </div>
            <div class="ahc-step-btn ahc-step-btn-2">
                <span>2</span>
                Контактная информация
            </div>
            <div class="ahc-step-btn ahc-step-btn-3">
                <span>3</span>
                Доставка и оплата
            </div>
            <div class="ahc-step-btn ahc-step-btn-4">
                <span>4</span>
                Подтверждение
            </div>
            <div class="block-separator"></div>
        </div>

        <div class="ahc-panels">

            <!-- LEFT PANEL -->
            <div class="ahc-panel-left">
                <div class="ahc-panel-1">
                    <h3>Ваш заказ</h3>
                    <div class="ahc-product-block">
                        <? foreach ($arResult['PRODUCT_LIST'] as $key=>$item):?>
                            <div class="ahc-product">
                                <div class="ahc-product-img">
                                    <a href="<?=$item['PROPS']['DETAIL_PAGE_URL'];?>">
                                        <img src="<?=($item['PROPS']['PREVIEW_PICTURE']?$item['PROPS']['PREVIEW_PICTURE']:$item['PROPS']['DETAIL_PICTURE']);?>" alt="<?=$item['PROPS']['NAME'];?>">
                                    </a>
                                </div>
                                <div class="ahc-product-info">
                                    <a href="<?=$item['PROPS']['DETAIL_PAGE_URL'];?>"><?=$item['PROPS']['NAME'];?></a>
                                    <div class="ahc-product-panel" data-product-id="<?=$item['PRODUCT_ID'];?>">
                                        <div class="ahc-product-delete">
                                            <div class="ahc-delete">Удалить</div>
                                        </div>
                                        <div class="ahc-product-quantity">
                                            <div>Количество</div>
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
                                <div class="block-separator"></div>
                            </div>
                        <? endforeach;?>
                    </div>
                </div>

                <div class="ahc-panel-2">
                    <h3>Контактная информация</h3>
                    <div class="ahc-user-info">
                        <div class="ahc-user-group">
                            <label>Ваши имя и фамилия <span>*</span></label>
                            <?
                            $userName = '';
                            if(isset($arResult['USER_INFO']['NAME']))
                                $userName = $arResult['USER_INFO']['NAME'];
                            if(isset($arResult['USER_INFO']['LAST_NAME']))
                                $userName .= ' '.$arResult['USER_INFO']['LAST_NAME'];

                            $userEmail = '';
                            if(isset($arResult['USER_INFO']['EMAIL']))
                                $userEmail = $arResult['USER_INFO']['EMAIL'];
                            ?>
                            <input type="text" id="<?=$arrId['userName'];?>" value="<?=$userName;?>" placeholder="Имя и фамилия" required>
                        </div>
                        <div class="ahc-user-group">
                            <label>Номер телефона для связи <span>*</span></label>
                            <input id="<?=$arrId['userPhone'];?>" type="text" value="" required>
                        </div>
                        <div class="ahc-user-group">
                            <label>Email для счета и оплаты <span>*</span></label>
                            <input type="email" id="<?=$arrId['userEmail'];?>" value="<?=$userEmail;?>" placeholder="Email" required>
                        </div>
                    </div>
                </div>

                <div class="ahc-panel-3">
                    <h3>Доставка и оплата</h3>
                    <div class="ahc-delivery">
                        <div>
                            <label>Имя:</label>
                            <span data-id="name"></span>
                        </div>
                        <div>
                            <label>Телефон:</label>
                            <span data-id="phone"></span>
                        </div>
                        <div>
                            <label>Email:</label>
                            <span data-id="email"></span>
                        </div>
                        <div class="ahc-address">
                            <label>Адрес доставки:</label>
                            <textarea></textarea>
                        </div>
                        <div class="ahc-pay-system">
                            <label>Способ оплаты:</label>
                            <fieldset id="group-pay">
                                <?
                                $i = true;
                                foreach ($arResult['PAY_SYSTEM'] as $item):
                                    if($item['ACTIVE']=='Y'):?>
                                        <div>
                                            <label>
                                                <?=$item['NAME'];?>
                                                <input type="radio" value="<?=$item['ID'];?>" name="group-pay" data-name="<?=$item['NAME'];?>" <?=$i?'checked':'';?>>
                                            </label>
                                            <span><?=$item['DESCRIPTION'];?></span>
                                        </div>
                                        <? $i = false;
                                    endif;
                                endforeach;?>
                            </fieldset>
                        </div>
                    </div>
                </div>

                <div class="ahc-panel-4">
                    <h3>Подтверждение заказа</h3>
                    <div class="ahc-confirm-order">
                        <div>
                            <label>Имя:</label>
                            <span data-id="name"></span>
                        </div>
                        <div>
                            <label>Телефон:</label>
                            <span data-id="phone"></span>
                        </div>
                        <div>
                            <label>Email:</label>
                            <span data-id="email"></span>
                        </div>
                        <div>
                            <label>Адрес доставки:</label>
                            <span data-id="address"></span>
                        </div>
                        <div>
                            <label>Способ оплаты:</label>
                            <span data-id="delivery"></span>
                        </div>
                    </div>
                </div>
                <div class="block-separator"></div>
            </div>

            <!-- RIGHT PANEL -->
            <div class='ahc-panel-right'>
                <div class="ahc-total-info">
                    <div>
                        <div class="ahc-base-price">Стоимость без скидки
                            <span id="<?=$arrId['basePrice'];?>"><?=$arResult['PRICE_WITHOUT_DISCOUNT'];?></span>
                        </div>
                        <div class="ahc-discount">Скидка
                            <span id="<?=$arrId['discount'];?>"><?=$arResult['DISCOUNT_PRICE_FORMATED'];?></span>
                        </div>
                    </div>
                    <div class="ahc-total-price">
                        Сума заказа
                        <span id="<?=$arrId['totalPrice'];?>"><?=$arResult['ORDER_PRICE_FORMATED'];?></span>
                    </div>
                    <div class="ahc-btn-step" id="<?=$arrId['buttonStep'];?>" data-step="1">
                        Оформить заказ
                    </div>
                </div>

                <div class="ahc-swift-order">
                    <div>Быстрый заказ</div>
                    <p>Вам не нужно заполнять контактную информацию, адрес доставки и способ оплаты. Наш специалист перезвонит вам и уточнит эту информацию.</p>
                </div>

                <div class="ahc-allow-order">
                    <label>
                        <input id="<?=$arrId['allowOrder'];?>" type="checkbox">
                        Согласие на обработку персональных данных
                    </label>
                    <span>* Эти поля обязательны для заполнения</span>
                </div>

                <div class="ahc-back">Вернуться в корзину</div>
            </div>

        </div>
        <div class="block-separator"></div>
    </form>

    <div class="bascket-empty">Ваша корзина пуста</div>

    <div class="order-executed">
        <h2>Спасибо за покупку</h2>
        <p>В ближайшее время с вами свяжеться наш специалист для уточнения условий доставки.</p>
        <div id="order-executed">
            <label>Номер заказа: №<span id="oe-number"></span></label>
            <label>Сумма заказа: <span id="oe-sum"></span></label>
            <label>Имя: <span id="oe-name"></span></label>
            <label>Телефон: <span id="oe-phone"></span></label>
            <label>Email: <span id="oe-email"></span></label>
            <label>Адрес доставки: <span id="oe-address"></span></label>
            <label>Способ оплаты: <span id="oe-pay"></span></label>
        </div>
    </div>
</div>

<?
$signer = new Main\Security\Sign\Signer;
$signedParams = $signer->sign(base64_encode(serialize($arParams)), 'sale.order');
$messages = Loc::loadLanguageFile(__FILE__);
?>
<script>
    BX.Currency.setCurrencyFormat('<?=$arResult['ORDER']['CURRENCY'];?>', <? echo CUtil::PhpToJSObject($currencyFormat, false, true); ?>);

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






<hr style="padding-top: 20px">
<div style="">
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