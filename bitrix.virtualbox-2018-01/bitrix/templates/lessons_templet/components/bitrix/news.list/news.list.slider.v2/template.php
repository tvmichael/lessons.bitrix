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


// https://developers.google.com/youtube/iframe_api_reference?hl=ru#Playback_controls
// https://v4-alpha.getbootstrap.com/components/carousel/

?>

<div>
    <h2>SLIEDR</h2>
    <button id="cycle1">cycle</button>
    <button id="pause1">pause</button>
    <button id="prev1">prev</button>
    <button id="next1">next</button>
</div>

<?
$mainId = $this->GetEditAreaId($arResult['ID']);
$itemIds = array(
    'id' => $mainId.'_carusel',
    'video' => array(),
);
$obName = 'ob'.preg_replace('/[^a-zA-Z0-9_]/', 'x', $mainId);
$carouselListCount = count($arResult["ITEMS"]);
?>

<div id="<?echo $itemIds['id'];?>" class="carousel slide cs-carusel" data-ride="carousel">
    <!-- Indicators -->
    <div class="carousel-indicators cs-carousel-indicators-width" >
        <?for ($i = 0; $i < $carouselListCount; $i++)
        {?>
            <span data-target="#<?echo $itemIds['id'];?>" data-slide-to="<? echo $i;?>" class="cs-carousel-indicators <?if($i == 0) echo 'active';?>"></span>
        <?};?>
    </div>

    <!-- Wrapper for slides -->
    <div class="carousel-inner cs-height" role="listbox">

        <div class="carousel-item active">
            <img class="d-block img-fluid" src="/upload/iblock/59e/59ec55c2245b97984823dee46a76af13.jpg" alt="First slide">
        </div>
        <div class="carousel-item">
            <img class="d-block img-fluid" src="/upload/iblock/59e/59ec55c2245b97984823dee46a76af13.jpg" alt="Second slide">
        </div>
        <div class="carousel-item">
            <img class="d-block img-fluid" src="/upload/iblock/59e/59ec55c2245b97984823dee46a76af13.jpg" alt="Third slide">
        </div>

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
    $('#<? echo $itemIds['id'];?>').carousel({
        interval: 1000
    });

    $('#cycle1').click(function () {
        $('#<? echo $itemIds['id'];?>').carousel('cycle')
    });
    $('#pause1').click(function () {
        $('#<? echo $itemIds['id'];?>').carousel('pause')
    });
    $('#prev1').click(function () {
        $('#<? echo $itemIds['id'];?>').carousel('prev')
    });
    $('#next1').click(function () {
        $('#<? echo $itemIds['id'];?>').carousel('next')
    });

    $('#<? echo $itemIds['id'];?>').on('slide.bs.carousel', function (e) {
        console.log(e);
    });
</script>

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
    echo '<div class="col-md-8"><pre>';
    print_r($arResult);
    //echo "<hr>";
    //print_r($arParams);
    echo '</pre></div>';
};
?>
