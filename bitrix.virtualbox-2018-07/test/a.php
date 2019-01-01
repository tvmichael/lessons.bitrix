<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("A");
?>

<?php
$db_res = CSaleDiscount::GetList(
    array("SORT" => "ASC"),
    array(
        "LID" => SITE_ID
    ),
    false,
    false,
    array()
);
while ($ar_res = $db_res->Fetch()){
    echo "<pre>";
    print_r($ar_res);
    echo "</pre>";
}
echo '<hr>';





$dbBasketItems = CSaleBasket::GetList(
    array("ID" => "ASC"),
    array(
        'FUSER_ID' => CSaleBasket::GetBasketUserID(),
        'LID' => SITE_ID,
        'ORDER_ID' => 'NULL'
    ),
    false,
    false,
    array(
        //'ID', 'PRODUCT_ID', 'QUANTITY', 'PRICE', 'DISCOUNT_PRICE', 'WEIGHT'
    )
);

$allSum = 0;
$allWeight = 0;
$arItems = array();

while ($arBasketItems = $dbBasketItems->Fetch())
{
    echo '<pre>';
    print_r($arBasketItems);
    echo '</pre>';

    $arItems[] = $arBasketItems;
    $allSum += ($arItem["PRICE"] * $arItem["QUANTITY"]);
    $allWeight += ($arItem["WEIGHT"] * $arItem["QUANTITY"]);

}

$arOrder = array(
    'SITE_ID' => SITE_ID,
    'USER_ID' => $GLOBALS["USER"]->GetID(),
    'ORDER_PRICE' => $allSum,
    'ORDER_WEIGHT' => $allWeight,
    'BASKET_ITEMS' => $arItems
);

$arOptions = array(
    'COUNT_DISCOUNT_4_ALL_QUANTITY' => 'Y',
);

$arErrors = array();

CSaleDiscount::DoProcessOrder($arOrder, $arOptions, $arErrors);

$PRICE_ALL = 0;
$DISCOUNT_PRICE_ALL = 0;
$QUANTITY_ALL = 0;

foreach ($arOrder["BASKET_ITEMS"] as $arOneItem)
{
    $PRICE_ALL += $arOneItem["PRICE"] * $arOneItem["QUANTITY"];
    $DISCOUNT_PRICE_ALL += $arOneItem["DISCOUNT_PRICE"] * $arOneItem["QUANTITY"];
    $QUANTITY_ALL += $arOneItem['QUANTITY'];
}

$result['PRICE_ALL'] = $PRICE_ALL;
$result['DISCOUNT_PRICE_ALL'] = $DISCOUNT_PRICE_ALL;
$result['QUANTITY_ALL'] = $QUANTITY_ALL;

echo '<pre>';
print_r($result);
echo '</pre>';

?>





<h3>CPrice::GetList</h3>
<?
$ID = 5;

$dbPrice = CPrice::GetList(
    array("QUANTITY_FROM" => "ASC", "QUANTITY_TO" => "ASC",
        "SORT" => "ASC"),
    array("PRODUCT_ID" => $ID),
    false,
    false,
    array(
        // "ID", "CATALOG_GROUP_ID", "PRICE", "CURRENCY", "QUANTITY_FROM", "QUANTITY_TO"
    )
);
while ($arPrice = $dbPrice->Fetch())
{
    $arDiscounts = CCatalogDiscount::GetDiscountByPrice(
        $arPrice["ID"],
        $USER->GetUserGroupArray(),
        "N",
        SITE_ID
    );
    $discountPrice = CCatalogProduct::CountPriceWithDiscount(
        $arPrice["PRICE"],
        $arPrice["CURRENCY"],
        $arDiscounts
    );
    $arPrice["DISCOUNT_PRICE"] = $discountPrice;

    echo "<pre>";
    print_r($arPrice);
    echo "</pre>";
}

?>



<h3>CCatalogDiscount::GetDiscountByProduct</h3>
<?
$arDiscounts = CCatalogDiscount::GetDiscountByProduct(
    5,
    $USER->GetUserGroupArray(),
    'Y',
    array(),
    SITE_ID,
    false
);
echo "<pre>".SITE_ID;
print_r($arDiscounts);
?>





<h3>CCatalogDiscount::GetList</h3>
<?
CModule::IncludeModule("catalog");
$rsDiscount = CCatalogDiscount::GetList(
        array("SORT" => "ASC"),
        array("ACTIVE" => "Y", )
);
while ($arDiscount = $rsDiscount->Fetch()) {
    print_r($arDiscount);
}


echo "</pre>";
?>







<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>
