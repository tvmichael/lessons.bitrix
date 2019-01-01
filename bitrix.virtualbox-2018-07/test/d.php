<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("D");
?>
<pre>
<?

$db_res = CSaleDiscount::GetList(
    array("SORT" => "ASC"),
    array( "LID" => SITE_ID, "ACTIVE" => "Y", ),
    false,
    false,
    array('ID')
);
while ($ar_res = $db_res->Fetch())
{
    //print_r($ar_res);
    $arCSaleDiscount = CSaleDiscount::GetByID($ar_res['ID']);
    print_r($arCSaleDiscount);


}







?>
</pre>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>