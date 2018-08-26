<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("База даних");
?>
<pre>
<?
$connection = Bitrix\Main\Application::getConnection();
$sqlHelper = $connection->getSqlHelper();

$sql = "SELECT * FROM b_sale_order_discount";

$recordset = $connection->query($sql);
while ($record = $recordset->fetch()) {
    print_r($record);
    echo '<br>';
}
?>
</pre>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>