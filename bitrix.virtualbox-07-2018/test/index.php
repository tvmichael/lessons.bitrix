<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("");
?><?$APPLICATION->IncludeComponent(
	"mv:sale.products.gift",
	"",
	Array(
		"ACTION_VARIABLE" => "action",
		"ADD_PROPERTIES_TO_BASKET" => "Y",
		"ADD_TO_BASKET_ACTION" => "ADD",
		"BASKET_URL" => "/personal/basket.php",
		"CACHE_GROUPS" => "Y",
		"CACHE_TIME" => "36000000",
		"CACHE_TYPE" => "A",
		"CONVERT_CURRENCY" => "N",
		"DEPTH" => "2",
		"DETAIL_URL" => "",
		"DISPLAY_COMPARE" => "N",
		"ELEMENT_SORT_FIELD" => "sort",
		"ELEMENT_SORT_FIELD2" => "id",
		"ELEMENT_SORT_ORDER" => "asc",
		"ELEMENT_SORT_ORDER2" => "desc",
		"HIDE_NOT_AVAILABLE" => "N",
		"HIDE_NOT_AVAILABLE_OFFERS" => "N",
		"IBLOCK_ID" => "2",
		"IBLOCK_TYPE" => "catalog",
		"MESS_BTN_ADD_TO_BASKET" => "В корзину",
		"MESS_BTN_BUY" => "Купить",
		"MESS_BTN_DETAIL" => "Подробнее",
		"MESS_BTN_SUBSCRIBE" => "Подписаться",
		"MESS_NOT_AVAILABLE" => "Нет в наличии",
		"PAGE_ELEMENT_COUNT" => "4",
		"PARTIAL_PRODUCT_PROPERTIES" => "N",
		"PRICE_CODE" => array(),
		"PRICE_VAT_INCLUDE" => "Y",
		"PRODUCT_ID_VARIABLE" => "id",
		"PRODUCT_PROPERTIES" => array(),
		"PRODUCT_PROPS_VARIABLE" => "prop",
		"PRODUCT_QUANTITY_VARIABLE" => "quantity",
		"PRODUCT_SUBSCRIPTION" => "Y",
		"PROPERTY_CODE" => array("",""),
		"SECTION_CODE" => "",
		"SECTION_ELEMENT_CODE" => "",
		"SECTION_ELEMENT_ID" => $GLOBALS["CATALOG_CURRENT_ELEMENT_ID"],
		"SECTION_ID" => $GLOBALS["CATALOG_CURRENT_SECTION_ID"],
		"SHOW_CLOSE_POPUP" => "N",
		"SHOW_DISCOUNT_PERCENT" => "N",
		"SHOW_FROM_SECTION" => "N",
		"SHOW_MAX_QUANTITY" => "N",
		"SHOW_OLD_PRICE" => "N",
		"SHOW_PRICE_COUNT" => "1",
		"TEMPLATE_THEME" => "blue",
		"USE_ENHANCED_ECOMMERCE" => "N",
		"USE_PRICE_COUNT" => "N",
		"USE_PRODUCT_QUANTITY" => "N"
	)
);?>

    <br>

    [CUSTOM_SITE_ID] =>
    [PRODUCT_ID_VARIABLE] => id
    [ACTION_VARIABLE] => action
    [PRODUCT_ROW_VARIANTS] =>
    [PAGE_ELEMENT_COUNT] => 0
    [DEFERRED_PRODUCT_ROW_VARIANTS] => [{"VARIANT":3,"BIG_DATA":false}]
    [DEFERRED_PAGE_ELEMENT_COUNT] => 4
    [SHOW_DISCOUNT_PERCENT] => Y
    [DISCOUNT_PERCENT_POSITION] => bottom-right
    [SHOW_OLD_PRICE] => Y
    [PRODUCT_DISPLAY_MODE] => Y
    [PRODUCT_BLOCKS_ORDER] => price,props,sku,quantityLimit,quantity,buttons
    [SHOW_SLIDER] => Y
    [SLIDER_INTERVAL] => 3000
    [SLIDER_PROGRESS] => N
    [TEXT_LABEL_GIFT] => Подарок
    [LABEL_PROP_2] => Array
    (
    )

    [LABEL_PROP_MOBILE_2] => Array
    (
    )

    [LABEL_PROP_POSITION] => top-left
    [ADD_TO_BASKET_ACTION] => Array
    (
    [0] => BUY
    )

    [MESS_BTN_BUY] => Выбрать
    [MESS_BTN_ADD_TO_BASKET] => Выбрать
    [MESS_BTN_DETAIL] => Подробнее
    [MESS_BTN_SUBSCRIBE] => Подписаться
    [SHOW_PRODUCTS_2] => Y
    [PROPERTY_CODE_2] =>
    [PROPERTY_CODE_MOBILE2] =>
    [PROPERTY_CODE_3] => Array
    (
    [0] => COLOR_REF
    [1] => SIZES_SHOES
    [2] => SIZES_CLOTHES
    )

    [OFFER_TREE_PROPS_3] => Array
    (
    [0] => COLOR_REF
    [1] => SIZES_SHOES
    [2] => SIZES_CLOTHES
    )

    [CART_PROPERTIES_3] => Array
    (
    [0] => COLOR_REF
    [1] => SIZES_SHOES
    [2] => SIZES_CLOTHES
    )

    [ADDITIONAL_PICT_PROP_2] => MORE_PHOTO
    [ADDITIONAL_PICT_PROP_3] => MORE_PHOTO
    [HIDE_NOT_AVAILABLE] => Y
    [HIDE_NOT_AVAILABLE_OFFERS] => Y
    [PRODUCT_SUBSCRIPTION] => Y
    [TEMPLATE_THEME] => black
    [PRICE_CODE] => Array
    (
    [0] => BASE
    )

    [SHOW_PRICE_COUNT] => 1
    [PRICE_VAT_INCLUDE] => 1
    [CONVERT_CURRENCY] => N
    [BASKET_URL] => /personal/cart/
    [ADD_PROPERTIES_TO_BASKET] => Y
    [PRODUCT_PROPS_VARIABLE] => prop
    [PARTIAL_PRODUCT_PROPERTIES] => N
    [USE_PRODUCT_QUANTITY] => N
    [PRODUCT_QUANTITY_VARIABLE] => quantity
    [CACHE_GROUPS] => Y
    [POTENTIAL_PRODUCT_TO_BUY] => Array
    (
    [ID] => 4
    [MODULE] => catalog
    [PRODUCT_PROVIDER_CLASS] => CCatalogProductProvider
    [QUANTITY] =>
    [IBLOCK_ID] => 2
    [PRIMARY_OFFER_ID] => 44
    [SECTION] => Array
    (
    [ID] => 8
    [IBLOCK_ID] => 2
    [LEFT_MARGIN] => 15
    [RIGHT_MARGIN] => 16
    )

    )

    [USE_ENHANCED_ECOMMERCE] => N
    [DATA_LAYER_NAME] =>
    [BRAND_PROPERTY] =>
    [CACHE_TYPE] => A
    [CURRENT_BASE_PAGE] => /catalog/pants/pants-striped-flight/
    [PARENT_NAME] => bitrix:catalog.element
    [PARENT_TEMPLATE_NAME] =>
    [PARENT_TEMPLATE_PAGE] =>
    [GLOBAL_FILTER] => Array
    (
    )

<?

?>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>