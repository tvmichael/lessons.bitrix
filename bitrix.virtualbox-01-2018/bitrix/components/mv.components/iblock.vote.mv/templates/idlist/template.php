<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
$this->setFrameMode(true); // Код в шаблоне $this->setFrameMode(true); означает что компонент "голосует" за.


// ----------------------------------
// $vkusData = array(
//	 'VKUS'=> array(),
//	 'DATA' => array()
// );	
// ----------------------------------


CJSCore::Init(array("ajax"));

$mainId = $this->GetEditAreaId($arResult['ID']);
$itemIds = array(
	'ID_CONTAINER' => $mainId.'_container',
	'ID_VKUS' => $mainId.'_vkus',	
	'ID_RATING' => $mainId.'_rating',	
	'SESSION_ID'=> bitrix_sessid(),
	'BACK_PAGE_URL' => $arResult['BACK_PAGE_URL'],
	'ID_SAVE_BUTTON' => $mainId.'_vote_save_rating',
	'VKUS_DATA' => $arParams['VOTE_LIST_DATA'],
	'URL' => $componentPath.'/component.php',	
	'IBLOCK_ID' =>$arParams['IBLOCK_ID'],
	'ELEMENT_ID' =>$arParams['ELEMENT_ID'],
	'AJAX_PARAMS' => $arResult['~AJAX_PARAMS'],
);
$obName = $templateData['JS_OBJ'] = 'ob'.preg_replace('/[^a-zA-Z0-9_]/', 'x', $mainId);
?>

<div id="<?echo $itemIds['ID_CONTAINER'];?>" class="pvs-iblock-vote">

	<div class="pvs-modal-content">
		<p>Ваша оценка товара</p>
		<select id="<?=$itemIds['ID_RATING'];?>" name="rating">
			<option value="-1">Оценить этот продукт</option>
			<?foreach($arResult["VOTE_NAMES"] as $i=>$name):?>
				<option value="<?=$i;?>"><?=$name?></option>
			<?endforeach?>
		</select>

		<p>Оценка вкуса <span>(не обязательно)</span></p>
		<select id="<?=$itemIds['ID_VKUS'];?>" >
			<option id="<?=$itemIds['ID_VKUS'];?>_0" value="-1">Оценить вкус</option>
		    <?			
		    $i=0;
		    foreach ($arParams['VOTE_LIST_DATA']['VKUS'] as $key => $value) {
		    	echo "<option id='".$value['ID']."' value='".$i."'>";
		        echo $value['NAME'].' - '.$value['VALUE'];
		        echo '</option>';	    	
		        $i++;
		    }		    
			?>
		</select>
		
		<p>Коментарий <span>(не обязательно)</span></p>
		<textarea maxlength="250"></textarea>
		<p>
			<input type="checkbox" name="checkbox_agree" value="0">
			текст!
		</p>												
	</div>

	<div class="pvs-modal-footer">
		<button id="<? echo $itemIds['ID_SAVE_BUTTON'];?>" class="pvs-modal-footer-button-disable" type="button" disabled="disabled">
			<?=GetMessage("T_IBLOCK_VOTE_BUTTON")?>			
		</button>
	</div>

	<script>
		BX.ready(function(){
		   	var <?=$obName?> = new JCCatalogVoteRating(<?=CUtil::PhpToJSObject($itemIds, false, true)?>);			
		});
	</script>
</div>
<? unset($itemIds, $obName); ?>




<?
//ASDSA ---------------------------------------------------------------------------------------------------------------
if ( $USER->IsAdmin() && $USER->GetID() == 5 ) 
{ 
	//echo "<pre>";  
	//print_r($arParams);	
	// print_r($templateFolder);
	//echo '</pre>';	
	//echo "<hr><pre>"; 
	//print_r($arResult);
	//echo "</pre>";
}; 
/* // OLD TEMPLATE ---
<div class="iblock-vote">
	<form method="post" action="<?=POST_FORM_ACTION_URI?>">
		<select name="rating">
			<?foreach($arResult["VOTE_NAMES"] as $i=>$name):?>
				<option value="<?=$i?>"><?=$name?></option>
			<?endforeach?>
		</select>
		<?echo bitrix_sessid_post();?>
		<input type="hidden" name="back_page" value="<?=$arResult["BACK_PAGE_URL"]?>" />
		<input type="hidden" name="vote_id" value="<?=$arResult["ID"]?>" />
		<input type="submit" name="vote" value="<?=GetMessage("T_IBLOCK_VOTE_BUTTON")?>" />
	</form>
</div>
/**/
?>