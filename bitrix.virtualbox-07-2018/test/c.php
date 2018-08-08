<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("C");
?>
<pre>
<?


$arDeliveryParams = \Bitrix\Sale\Delivery\Services\Manager::getById(4);
print_r($arDeliveryParams);
echo '<br>';



CModule::IncludeModule("sale");
global $USER;
$element_id = 4;


$deliveryId = 4;
$obBasket = \Bitrix\Sale\Basket::create(SITE_ID);
$obItem = $obBasket->createItem("catalog", $element_id);
$arProductFields = array(
    'NAME' => "Футболка Мужская Чистота",
    'PRICE' => 799.00,
    'CURRENCY' => 'UAN',
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


print_r($obShipment);

$arDelivery = \Bitrix\Sale\Delivery\Services\Manager::calculateDeliveryPrice($obShipment, $deliveryId);

print_r($arDelivery);


?>
</pre>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>
