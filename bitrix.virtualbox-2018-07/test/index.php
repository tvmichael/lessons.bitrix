<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("");
?>
<div style="display: none">
<pre>
<?

$params = Array
(
    'CUSTOM_SITE_ID' => NULL,
    'PRODUCT_ID_VARIABLE' => 'id',
    'ACTION_VARIABLE' => 'action',
    'PRODUCT_ROW_VARIANTS' => '',
    'PAGE_ELEMENT_COUNT' => 0,
    'DEFERRED_PRODUCT_ROW_VARIANTS' => "[{'VARIANT':3,'BIG_DATA':false}]",
    'DEFERRED_PAGE_ELEMENT_COUNT' => '4',
    'SHOW_DISCOUNT_PERCENT' => 'Y',
    'DISCOUNT_PERCENT_POSITION' => 'bottom-right',
    'SHOW_OLD_PRICE' => 'Y',
    'PRODUCT_DISPLAY_MODE' => 'Y',
    'PRODUCT_BLOCKS_ORDER' => 'price,props,sku,quantityLimit,quantity,buttons',
    'SHOW_SLIDER' => 'Y',
    'SLIDER_INTERVAL' => '3000',
    'SLIDER_PROGRESS' => 'N',
    'TEXT_LABEL_GIFT' => 'Подарок',
    'LABEL_PROP_2' => Array(),
    'LABEL_PROP_MOBILE_2' => Array(),
    'LABEL_PROP_POSITION' => 'top-left',
    'ADD_TO_BASKET_ACTION' => Array( 0 => 'BUY'),
    'MESS_BTN_BUY' => 'Выбрать',
    'MESS_BTN_ADD_TO_BASKET' => 'Выбрать',
    'MESS_BTN_DETAIL' => 'Подробнее',
    'MESS_BTN_SUBSCRIBE' => 'Подписаться',
    'SHOW_PRODUCTS_2' => 'Y',
    'PROPERTY_CODE_2' => NULL,
    'PROPERTY_CODE_MOBILE2' => NULL,
    'PROPERTY_CODE_3' => Array
    (
        '0' => 'COLOR_REF',
        '1' => 'SIZES_SHOES',
        '2' => 'SIZES_CLOTHES',
    ),
    'OFFER_TREE_PROPS_3' => Array
    (
        '0' => 'COLOR_REF',
        '1' => 'SIZES_SHOES',
        '2' => 'SIZES_CLOTHES',
    ),
    'CART_PROPERTIES_3' => Array
    (
        '0' => 'COLOR_REF',
        '1' => 'SIZES_SHOES',
        '2' => 'SIZES_CLOTHES',
    ),
    'ADDITIONAL_PICT_PROP_2' => 'MORE_PHOTO',
    'ADDITIONAL_PICT_PROP_3' => 'MORE_PHOTO',
    'HIDE_NOT_AVAILABLE' => 'Y',
    'HIDE_NOT_AVAILABLE_OFFERS' => 'Y',
    'PRODUCT_SUBSCRIPTION' => 'Y',
    'TEMPLATE_THEME' => 'black',
    'PRICE_CODE' => Array
    (
        '0' => 'BASE',
    ),
    'SHOW_PRICE_COUNT' => 1,
    'PRICE_VAT_INCLUDE' => true,
    'CONVERT_CURRENCY' => 'N',
    'BASKET_URL' => '/personal/cart/',
    'ADD_PROPERTIES_TO_BASKET' => 'Y',
    'PRODUCT_PROPS_VARIABLE' => 'prop',
    'PARTIAL_PRODUCT_PROPERTIES' => 'N',
    'USE_PRODUCT_QUANTITY' => 'N',
    'PRODUCT_QUANTITY_VARIABLE' => 'quantity',
    'CACHE_GROUPS' => 'Y',
    'POTENTIAL_PRODUCT_TO_BUY' => Array
    (
        'ID' => 4,                                            // --
        'MODULE' => 'catalog',
        'PRODUCT_PROVIDER_CLASS' => 'CCatalogProductProvider',
        'QUANTITY' => NULL,
        'IBLOCK_ID' => 2,
        'PRIMARY_OFFER_ID' => 44,
        'SECTION' => Array
        (
            'ID' => '8',
            'IBLOCK_ID' => '2',
            'LEFT_MARGIN' => '15',
            'RIGHT_MARGIN' => '16',
        ),
    ),
    'USE_ENHANCED_ECOMMERCE' => 'N',
    'DATA_LAYER_NAME' => '',
    'BRAND_PROPERTY' => '',
    'CACHE_TYPE' => 'A',

//    'CURRENT_BASE_PAGE' => '/catalog/pants/pants-striped-flight/',  // --
//    'PARENT_NAME' => 'bitrix:catalog.element',                      // --
//    'PARENT_TEMPLATE_NAME' => '',
//    'PARENT_TEMPLATE_PAGE' => '',
//    'GLOBAL_FILTER' => Array(),
);

//CBitrixComponent::includeComponentClass('mv:sale.products.gift');
//$templateName = '.default';
//$signer = new \Bitrix\Main\Security\Sign\Signer;
//$signedTemplate = $signer->sign($templateName, 'sale.products.gift');
//$signedParams = $signer->sign(base64_encode(serialize($params)), 'sale.products.gift');

echo '<p>SaleProductsGiftComponent:</p>';
//(new SaleProductsGiftComponent());
//$g = new SaleProductsGiftComponent();
//$g->onPrepareComponentParams($params);
//$g->executeComponent();
echo '<p>END: SaleProductsGiftComponent.</p>';
?>

<div>
    <h3>sale.products.gift</h3>
    <?
    /*
    $APPLICATION->IncludeComponent(
        'mv:sale.products.gift',
        $templateName,
        unserialize(base64_decode($signedParams)),
        false
    );
    */
    ?>
    <hr>
</div>

</pre>

<div id="gift">
</div>
<script>
    /*
    var locationUrl = '/bitrix/components/mv/sale.products.gift/ajax.php';

    var defaultData = {
        action: "deferredLoad",
        siteId: 's1',
        template: '?=CUtil::JSEscape($signedTemplate);?>', //'.default.48828b520c88ff1c8e84060fc8815e6a1468246bc3503f8bf9955291a882668d',
        parameters: '?=CUtil::JSEscape($signedParams);?>', //'YTo1Nzp7czoxNDoiQ1VTVE9NX1NJVEVfSUQiO047czoxOToiUFJPRFVDVF9JRF9WQVJJQUJMRSI7czoyOiJpZCI7czoxNToiQUNUSU9OX1ZBUklBQkxFIjtzOjY6ImFjdGlvbiI7czoyMDoiUFJPRFVDVF9ST1dfVkFSSUFOVFMiO3M6MDoiIjtzOjE4OiJQQUdFX0VMRU1FTlRfQ09VTlQiO2k6MDtzOjI5OiJERUZFUlJFRF9QUk9EVUNUX1JPV19WQVJJQU5UUyI7czozMjoiW3siVkFSSUFOVCI6MywiQklHX0RBVEEiOmZhbHNlfV0iO3M6Mjc6IkRFRkVSUkVEX1BBR0VfRUxFTUVOVF9DT1VOVCI7czoxOiI0IjtzOjIxOiJTSE9XX0RJU0NPVU5UX1BFUkNFTlQiO3M6MToiWSI7czoyNToiRElTQ09VTlRfUEVSQ0VOVF9QT1NJVElPTiI7czoxMjoiYm90dG9tLXJpZ2h0IjtzOjE0OiJTSE9XX09MRF9QUklDRSI7czoxOiJZIjtzOjIwOiJQUk9EVUNUX0RJU1BMQVlfTU9ERSI7czoxOiJZIjtzOjIwOiJQUk9EVUNUX0JMT0NLU19PUkRFUiI7czo0NjoicHJpY2UscHJvcHMsc2t1LHF1YW50aXR5TGltaXQscXVhbnRpdHksYnV0dG9ucyI7czoxMToiU0hPV19TTElERVIiO3M6MToiWSI7czoxNToiU0xJREVSX0lOVEVSVkFMIjtzOjQ6IjMwMDAiO3M6MTU6IlNMSURFUl9QUk9HUkVTUyI7czoxOiJOIjtzOjE1OiJURVhUX0xBQkVMX0dJRlQiO3M6MTQ6ItCf0L7QtNCw0YDQvtC6IjtzOjEyOiJMQUJFTF9QUk9QXzIiO2E6MDp7fXM6MTk6IkxBQkVMX1BST1BfTU9CSUxFXzIiO2E6MDp7fXM6MTk6IkxBQkVMX1BST1BfUE9TSVRJT04iO3M6ODoidG9wLWxlZnQiO3M6MjA6IkFERF9UT19CQVNLRVRfQUNUSU9OIjthOjE6e2k6MDtzOjM6IkJVWSI7fXM6MTI6Ik1FU1NfQlROX0JVWSI7czoxNDoi0JLRi9Cx0YDQsNGC0YwiO3M6MjI6Ik1FU1NfQlROX0FERF9UT19CQVNLRVQiO3M6MTQ6ItCS0YvQsdGA0LDRgtGMIjtzOjE1OiJNRVNTX0JUTl9ERVRBSUwiO3M6MTg6ItCf0L7QtNGA0L7QsdC90LXQtSI7czoxODoiTUVTU19CVE5fU1VCU0NSSUJFIjtzOjIyOiLQn9C+0LTQv9C40YHQsNGC0YzRgdGPIjtzOjE1OiJTSE9XX1BST0RVQ1RTXzIiO3M6MToiWSI7czoxNToiUFJPUEVSVFlfQ09ERV8yIjtOO3M6MjE6IlBST1BFUlRZX0NPREVfTU9CSUxFMiI7TjtzOjE1OiJQUk9QRVJUWV9DT0RFXzMiO2E6Mzp7aTowO3M6OToiQ09MT1JfUkVGIjtpOjE7czoxMToiU0laRVNfU0hPRVMiO2k6MjtzOjEzOiJTSVpFU19DTE9USEVTIjt9czoxODoiT0ZGRVJfVFJFRV9QUk9QU18zIjthOjM6e2k6MDtzOjk6IkNPTE9SX1JFRiI7aToxO3M6MTE6IlNJWkVTX1NIT0VTIjtpOjI7czoxMzoiU0laRVNfQ0xPVEhFUyI7fXM6MTc6IkNBUlRfUFJPUEVSVElFU18zIjthOjM6e2k6MDtzOjk6IkNPTE9SX1JFRiI7aToxO3M6MTE6IlNJWkVTX1NIT0VTIjtpOjI7czoxMzoiU0laRVNfQ0xPVEhFUyI7fXM6MjI6IkFERElUSU9OQUxfUElDVF9QUk9QXzIiO3M6MTA6Ik1PUkVfUEhPVE8iO3M6MjI6IkFERElUSU9OQUxfUElDVF9QUk9QXzMiO3M6MTA6Ik1PUkVfUEhPVE8iO3M6MTg6IkhJREVfTk9UX0FWQUlMQUJMRSI7czoxOiJZIjtzOjI1OiJISURFX05PVF9BVkFJTEFCTEVfT0ZGRVJTIjtzOjE6IlkiO3M6MjA6IlBST0RVQ1RfU1VCU0NSSVBUSU9OIjtzOjE6IlkiO3M6MTQ6IlRFTVBMQVRFX1RIRU1FIjtzOjU6ImJsYWNrIjtzOjEwOiJQUklDRV9DT0RFIjthOjE6e2k6MDtzOjQ6IkJBU0UiO31zOjE2OiJTSE9XX1BSSUNFX0NPVU5UIjtpOjE7czoxNzoiUFJJQ0VfVkFUX0lOQ0xVREUiO2I6MTtzOjE2OiJDT05WRVJUX0NVUlJFTkNZIjtzOjE6Ik4iO3M6MTA6IkJBU0tFVF9VUkwiO3M6MTU6Ii9wZXJzb25hbC9jYXJ0LyI7czoyNDoiQUREX1BST1BFUlRJRVNfVE9fQkFTS0VUIjtzOjE6IlkiO3M6MjI6IlBST0RVQ1RfUFJPUFNfVkFSSUFCTEUiO3M6NDoicHJvcCI7czoyNjoiUEFSVElBTF9QUk9EVUNUX1BST1BFUlRJRVMiO3M6MToiTiI7czoyMDoiVVNFX1BST0RVQ1RfUVVBTlRJVFkiO3M6MToiTiI7czoyNToiUFJPRFVDVF9RVUFOVElUWV9WQVJJQUJMRSI7czo4OiJxdWFudGl0eSI7czoxMjoiQ0FDSEVfR1JPVVBTIjtzOjE6IlkiO3M6MjQ6IlBPVEVOVElBTF9QUk9EVUNUX1RPX0JVWSI7YTo3OntzOjI6IklEIjtpOjQ7czo2OiJNT0RVTEUiO3M6NzoiY2F0YWxvZyI7czoyMjoiUFJPRFVDVF9QUk9WSURFUl9DTEFTUyI7czoyMzoiQ0NhdGFsb2dQcm9kdWN0UHJvdmlkZXIiO3M6ODoiUVVBTlRJVFkiO047czo5OiJJQkxPQ0tfSUQiO2k6MjtzOjE2OiJQUklNQVJZX09GRkVSX0lEIjtpOjQ0O3M6NzoiU0VDVElPTiI7YTo0OntzOjI6IklEIjtzOjE6IjgiO3M6OToiSUJMT0NLX0lEIjtzOjE6IjIiO3M6MTE6IkxFRlRfTUFSR0lOIjtzOjI6IjE1IjtzOjEyOiJSSUdIVF9NQVJHSU4iO3M6MjoiMTYiO319czoyMjoiVVNFX0VOSEFOQ0VEX0VDT01NRVJDRSI7czoxOiJOIjtzOjE1OiJEQVRBX0xBWUVSX05BTUUiO3M6MDoiIjtzOjE0OiJCUkFORF9QUk9QRVJUWSI7czowOiIiO3M6MTA6IkNBQ0hFX1RZUEUiO3M6MToiQSI7czoxNzoiQ1VSUkVOVF9CQVNFX1BBR0UiO3M6MzY6Ii9jYXRhbG9nL3BhbnRzL3BhbnRzLXN0cmlwZWQtZmxpZ2h0LyI7czoxMToiUEFSRU5UX05BTUUiO3M6MjI6ImJpdHJpeDpjYXRhbG9nLmVsZW1lbnQiO3M6MjA6IlBBUkVOVF9URU1QTEFURV9OQU1FIjtzOjA6IiI7czoyMDoiUEFSRU5UX1RFTVBMQVRFX1BBR0UiO3M6MDoiIjtzOjEzOiJHTE9CQUxfRklMVEVSIjthOjA6e319.9bd63ac436f0860d0196fe2ce4cdcb6fbb8084b6ef4b20ff223ef77a51cc79f3'
    };

    console.log('BX.ajax');
    BX.ajax({
        url: locationUrl,
        method: 'POST',
        dataType: 'json',
        timeout: 60,
        data: defaultData,
        onsuccess: BX.delegate(function(result){

            console.log(':::BX.ajax');
            console.log(result);

            if (!result || !result.JS)
            {
                this.hideHeader();
                BX.cleanNode(this.container);
                return;
            }
            var gift = document.getElementById('gift');
            gift.outerHTML = result.items;

            BX.ajax.processScripts(
                BX.processHTML(result.JS).SCRIPT,
                false,
                BX.delegate(function(){this.showAction(result, data);}, this)
            );

        }, this)
    });
    /**/
</script>
</div>


<pre>
    <p>getGiftIds:</p>
    <?
    use Bitrix\Sale\Compatible\DiscountCompatibility;
    use Bitrix\Sale\Basket;
    use Bitrix\Sale\Discount\Gift;
    use Bitrix\Sale\Fuser;

    function getGiftIds($productId)
    {
        $giftProductIds = [];
        if (!$productId) {
            return $giftProductIds;
        }
        DiscountCompatibility::stopUsageCompatible();
        $giftManager = Gift\Manager::getInstance();
        $potentialBuy = [
            'ID'                     => $productId,
            'MODULE'                 => 'catalog',
            'PRODUCT_PROVIDER_CLASS' => 'CCatalogProductProvider',
            'QUANTITY'               => 1,
        ];

//        $basket = Basket::loadItemsForFUser(Fuser::getId(), SITE_ID);
//        $basketPseudo = $basket->copy();
//        foreach ($basketPseudo as $basketItem) {
//            $basketItem->delete();
//        }

        $deliveryId = 2;
        $obBasket = \Bitrix\Sale\Basket::create(SITE_ID);
        $obItem = $obBasket->createItem("catalog", '4');
        $arProductFields = array(
            'NAME' => "Штаны Полосатый Рейс",
            'PRICE' => 1999.00,
            'CURRENCY' => 'UAN',
            'QUANTITY' => 1,
            'LID' => \Bitrix\Main\Context::getCurrent()->getSite(),
            'PRODUCT_PROVIDER_CLASS' => 'CCatalogProductProvider',
        );
        $obItem->setFields($arProductFields);

        $obOrder = \Bitrix\Sale\Order::create(SITE_ID, 1);
        $obOrder->setPersonTypeId(1);
        $obOrder->setBasket($obBasket);

        $collections = $giftManager->getCollectionsByProduct($obBasket, $potentialBuy);
        foreach ($collections as $collection) {
            /** @var \Bitrix\Sale\Discount\Gift\Gift $gift */
            foreach ($collection as $gift) {
                $giftProductIds[] = $gift->getProductId();
            }
        }
        DiscountCompatibility::revertUsageCompatible();
        return $obBasket;
    }

   //print_r(getGiftIds(4) );


    echo '<hr>';
    $deliveryId = 2;
    //print_r($arDeliveryParams = \Bitrix\Sale\Delivery\Services\Manager::getById($deliveryId));



    $obBasket = \Bitrix\Sale\Basket::create(SITE_ID);
    $obItem = $obBasket->createItem("catalog", '5');
    $arProductFields = array(
        'NAME' => "Штаны Жизнь в Абстракции",
        'PRICE' => 1899.00,
        'CURRENCY' => 'RUB',
        'QUANTITY' => 1,
        'LID' => \Bitrix\Main\Context::getCurrent()->getSite(),
        'PRODUCT_PROVIDER_CLASS' => 'CCatalogProductProvider',
    );
    $obItem->setFields($arProductFields);

    $obOrder = \Bitrix\Sale\Order::create(SITE_ID, 1);
    $obOrder->setPersonTypeId(1);
    $obOrder->setBasket($obBasket);
    $obShipmentCollection = $obOrder->getShipmentCollection();
    $obShipment = $obShipmentCollection->createItem(\Bitrix\Sale\Delivery\Services\Manager::getObjectById($deliveryId));
    $shipmentItemCollection = $obShipment->getShipmentItemCollection();
    foreach ($basket as $basketItem)
    {
        $item = $shipmentItemCollection->createItem($basketItem);
        $item->setQuantity($basketItem->getQuantity());
    }

    $arDelivery = \Bitrix\Sale\Delivery\Services\Manager::calculateDeliveryPrice($obShipment, $deliveryId, array());

    //print_r($arDelivery);
    print_r($obBasket);
    ?>
</pre>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>