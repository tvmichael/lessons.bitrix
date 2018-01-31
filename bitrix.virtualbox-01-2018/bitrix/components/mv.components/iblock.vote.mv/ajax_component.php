<?
define("NO_KEEP_STATISTIC", true);
require($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_before.php');

use \Bitrix\Main\Application;
$context = Application::getInstance()->getContext();
$request = $context->getRequest();

$arIblock_type = intval( $request->get("IBLOCK_TYPE") );
$arIblock_id = intval( $request->get("IBLOCK_ID") );
$arElement_id = intval( $request->get("ELEMENT_ID") );

?>
<script type="text/javascript">
    var selectVoteWriteRaiting = <? echo json_encode($request->get("ELEMENT_LIST_ID")); ?>;
</script>
<?
$APPLICATION->IncludeComponent(
    'arhicode:iblock.vote.mv',
    'ajax',
    array(
        'IBLOCK_TYPE' => $arIblock_type,
        'IBLOCK_ID' => $arIblock_id,
        'ELEMENT_ID' => $arElement_id,
        'ELEMENT_CODE' => '',
        'MAX_VOTE' => '10',
        'VOTE_NAMES' => array('1', '2', '3', '4', '5', '6', '7', '8', '9', '10'),
        'SET_STATUS_404' => 'N',
        'DISPLAY_AS_RATING' => $request->get("DISPLAY_AS_RATING"),  //$arParams['VOTE_DISPLAY_AS_RATING'],
        'CACHE_TYPE' => $request->get('CACHE_TYPE'),                //$arParams['CACHE_TYPE'],
        'CACHE_TIME' => $request->get('CACHE_TIME'),                // $arParams['CACHE_TIME']                
    ),
    $component,
    array('HIDE_ICONS' => 'Y')
);    
?>

<? require($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/epilog_after.php'); ?>