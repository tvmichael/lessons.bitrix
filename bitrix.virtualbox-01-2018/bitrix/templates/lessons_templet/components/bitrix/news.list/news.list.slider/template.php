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
$this->setFrameMode(true);

$mainId = $this->GetEditAreaId($arResult['ID']);
$itemIds = array(
	'id' => $mainId.'_carusel',
    'video' => array(),
);
$obName = 'ob'.preg_replace('/[^a-zA-Z0-9_]/', 'x', $mainId);
$carouselListCount = count($arResult["ITEMS"]);
?>

<div id="<?echo $itemIds['id'];?>" class="carousel slide cs-carusel" data-ride="carousel" data-interval="false">
	<!-- Indicators -->
	<div class="carousel-indicators">
		<?for ($i = 0; $i < $carouselListCount; $i++)
		{?>
			<span data-target="#<?echo $itemIds['id'];?>" data-slide-to="<? echo $i;?>" class="cs-carousel-indicators <?if($i == 0) echo 'active';?>"></span>
		<?};?>		
	</div>

	<!-- Wrapper for slides -->
	<div class="carousel-inner cs-height" role="listbox">

		<? foreach( $arResult["ITEMS"] as $i => $arItem ):

			if( strlen($arItem['PREVIEW_PICTURE']['SRC']) > 1):?>
				<div class="item <? if($i == 0) echo'active'; ?>" data-type="img">
    			    <img class="cs-inner-img" src="<? echo $arItem['PREVIEW_PICTURE']['SRC']; ?>" alt="<? echo $arItem['NAME']; ?>">
				</div>

			<? elseif (strlen($arItem['PROPERTIES']['SLIDER_YOUTUBE_LINK']['~VALUE']) > 1 ):?>
				<div class="item cs-inner-video <? if($i == 0) echo'active'; ?>" data-type="video">
                    <?
                    $url = $arItem['PROPERTIES']['SLIDER_YOUTUBE_LINK']['~VALUE'];
                    preg_match('/[\\?\\&]v=([^\\?\\&]+)/', $url, $matches);
                    $videoId = $matches[1];

                    $itemIds['video'][$i] = array(
                        'id' => $mainId.'_video'.$arItem['ID'],
                        'name' => $arItem['NAME'],
                        'src' => $arItem['PROPERTIES']['SLIDER_YOUTUBE_LINK']['~VALUE'],
                        'videoId' =>$videoId,
                    );
                    ?>
                    <div id="<? echo $itemIds['video'][$i]['id'];?>"></div>
				</div>
			<? endif; ?>

		<? endforeach; ?>
	</div>

	<!-- Controls -->
    <div class="cs-carousel-control">
        <a class="cs-carousel-control-button left cs-center" href="#<?echo $itemIds['id'];?>" role="button" data-slide="prev">
            <span class="glyphicon glyphicon-menu-left cs-text cs-center" aria-hidden="true"></span>
        </a>
        <a class="cs-carousel-control-button right  cs-center" href="#<?echo $itemIds['id'];?>" role="button" data-slide="next">
            <span class="glyphicon glyphicon-menu-right cs-text cs-center" aria-hidden="true"></span>
        </a>
    </div>
</div>

<script>
    BX.ready(function(){
        var <?=$obName?> = new JSCarouselElement(<?=CUtil::PhpToJSObject($itemIds, false, true)?>);
    });
</script>
<? // unset($itemIds, $jsParams); ?>






<?/*?>
<div class="news-list">
<?if($arParams["DISPLAY_TOP_PAGER"]):?>
	<?=$arResult["NAV_STRING"]?><br />
<?endif;?>
<?foreach($arResult["ITEMS"] as $arItem):?>
	<?
	$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
	$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
	?>
	<p class="news-item" id="<?=$this->GetEditAreaId($arItem['ID']);?>">
		<?if($arParams["DISPLAY_PICTURE"]!="N" && is_array($arItem["PREVIEW_PICTURE"])):?>
			<?if(!$arParams["HIDE_LINK_WHEN_NO_DETAIL"] || ($arItem["DETAIL_TEXT"] && $arResult["USER_HAVE_ACCESS"])):?>
				<a href="<?=$arItem["DETAIL_PAGE_URL"]?>"><img
						class="preview_picture"
						border="0"
						src="<?=$arItem["PREVIEW_PICTURE"]["SRC"]?>"
						width="<?=$arItem["PREVIEW_PICTURE"]["WIDTH"]?>"
						height="<?=$arItem["PREVIEW_PICTURE"]["HEIGHT"]?>"
						alt="<?=$arItem["PREVIEW_PICTURE"]["ALT"]?>"
						title="<?=$arItem["PREVIEW_PICTURE"]["TITLE"]?>"
						style="float:left"
						/></a>
			<?else:?>
				<img
					class="preview_picture"
					border="0"
					src="<?=$arItem["PREVIEW_PICTURE"]["SRC"]?>"
					width="<?=$arItem["PREVIEW_PICTURE"]["WIDTH"]?>"
					height="<?=$arItem["PREVIEW_PICTURE"]["HEIGHT"]?>"
					alt="<?=$arItem["PREVIEW_PICTURE"]["ALT"]?>"
					title="<?=$arItem["PREVIEW_PICTURE"]["TITLE"]?>"
					style="float:left"
					/>
			<?endif;?>
		<?endif?>
		<?if($arParams["DISPLAY_DATE"]!="N" && $arItem["DISPLAY_ACTIVE_FROM"]):?>
			<span class="news-date-time"><?echo $arItem["DISPLAY_ACTIVE_FROM"]?></span>
		<?endif?>
		<?if($arParams["DISPLAY_NAME"]!="N" && $arItem["NAME"]):?>
			<?if(!$arParams["HIDE_LINK_WHEN_NO_DETAIL"] || ($arItem["DETAIL_TEXT"] && $arResult["USER_HAVE_ACCESS"])):?>
				<a href="<?echo $arItem["DETAIL_PAGE_URL"]?>"><b><?echo $arItem["NAME"]?></b></a><br />
			<?else:?>
				<b><?echo $arItem["NAME"]?></b><br />
			<?endif;?>
		<?endif;?>
		<?if($arParams["DISPLAY_PREVIEW_TEXT"]!="N" && $arItem["PREVIEW_TEXT"]):?>
			<?echo $arItem["PREVIEW_TEXT"];?>
		<?endif;?>
		<?if($arParams["DISPLAY_PICTURE"]!="N" && is_array($arItem["PREVIEW_PICTURE"])):?>
			<div style="clear:both"></div>
		<?endif?>
		<?foreach($arItem["FIELDS"] as $code=>$value):?>
			<small>
			<?=GetMessage("IBLOCK_FIELD_".$code)?>:&nbsp;<?=$value;?>
			</small><br />
		<?endforeach;?>
		<?foreach($arItem["DISPLAY_PROPERTIES"] as $pid=>$arProperty):?>
			<small>
			<?=$arProperty["NAME"]?>:&nbsp;
			<?if(is_array($arProperty["DISPLAY_VALUE"])):?>
				<?=implode("&nbsp;/&nbsp;", $arProperty["DISPLAY_VALUE"]);?>
			<?else:?>
				<?=$arProperty["DISPLAY_VALUE"];?>
			<?endif?>
			</small><br />
		<?endforeach;?>
	</p>
<?endforeach;?>
<?if($arParams["DISPLAY_BOTTOM_PAGER"]):?>
	<br /><?=$arResult["NAV_STRING"]?>
<?endif;?>
</div>
<?/**/?>

<br>
<?
if ( $USER->IsAdmin() && $USER->GetID() == 1 ) {
    //echo '<div class="col-md-8"><pre>';
    //print_r($arResult);
    //echo "<hr>";
    //print_r($arParams);
    //echo '</pre></div>';
};
?>
