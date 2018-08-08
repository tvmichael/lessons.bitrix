<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("B");
?>

<pre>
<?
// інформаціє про правила роботи з кошиком

$sale = CSaleDiscount::GetByID(3);
print_r($sale);

$sale = CSaleDiscount::GetByID(4);
print_r($sale);

$sale = CSaleDiscount::GetByID(5);
print_r($sale);

$sale = CSaleDiscount::GetByID(6);
print_r($sale);

$sale = CSaleDiscount::GetByID(7);
print_r($sale);

$sale = CSaleDiscount::GetByID(8);
print_r($sale);




echo '<br><hr>';
// Выберем величину активной скидки для текущего сайта и стоимости
// заказа $ORDER_PRICE (в базовой валюте этого сайта)
$db_res = CSaleDiscount::GetList(
    array("SORT" => "ASC"),
    array(
        "LID" => SITE_ID,
        "ACTIVE" => "Y",
        //">=PRICE_TO" => $ORDER_PRICE,
        //"<=PRICE_FROM" => $ORDER_PRICE
    ),
    false,
    false,
    array()
);
while ($ar_res = $db_res->Fetch())
{
    print_r($ar_res);
}

?>

</pre>



<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>
