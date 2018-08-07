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

?>

</pre>


<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>
