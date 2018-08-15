<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("E");
?>

<pre>
<?
$discountIterator = Bitrix\Sale\Internals\DiscountTable::getList ([
    //'select' => ['ID', 'XML_ID', 'NAME', 'ACTIVE_FROM', 'ACTIVE_TO', 'CONDITIONS_LIST', 'ACTIONS_LIST'],
    'filter' => ['ACTIVE' => 'Y'],
]);
while ($discount = $discountIterator->fetch()){
    echo '<h3 style="color:red;">'.$discount['ID'].'</h3>';
    print_r($discount);
    //var_dump($discount['ACTIVE_FROM']);
    //if( gettype($discount['ACTIVE_FROM']) == 'object'  )
    //echo '<br>'.$discount['ACTIVE_FROM']->toString();
}
?>
</pre>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>
