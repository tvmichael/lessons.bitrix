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

$carouselCountItems = count($arResult['ITEMS']);
$carouselId = 'carouselExampleIndicators-'.$arResult['ID'];
?>


<div id="<? echo $carouselId;?>" class="carousel slide ch-slider-container" data-ride="carousel" data-interval="false">
    <!-- Indicators -->
    <div class="carousel-indicators">
        <? for ($i = 0; $i < $carouselCountItems; $i++):?>
            <div class="ch-carousel-indicators" data-target="#<? echo $carouselId;?>" data-slide-to="<? echo $i;?>"
                <?if($i == 0) echo "class='active'";?>>
            </div>
        <?endfor;?>
    </div>
    <!-- Wrapper for slides -->
    <div class="carousel-inner ch-slider-wrapper">

        <? foreach ($arResult['ITEMS'] as $i => $res):?>
            <div class="item <?if($i == 0) echo "active";?>">
                <? if ( is_array( $res['PREVIEW_PICTURE']) ):?>
                    <div class="ch-slider-img-container">
                        <img class="ch-slider-img-center" src="<? echo $res['PREVIEW_PICTURE']['SRC'] ;?>" alt="<? echo $res['NAME']?>">
                    </div>
                <? elseif ( strlen($res['PROPERTIES']['SLIDER_YOUTUBE_URL']['VALUE']) > 1 ):?>
                    <div class="ch-slider-video-container">


                        <?php
                        // resurs:  https://sourcey.com/youtube-html5-embed-from-url-with-php/

                        $url = $res['PROPERTIES']['SLIDER_YOUTUBE_URL']['VALUE'];
                        preg_match('/[\\?\\&]v=([^\\?\\&]+)/', $url, $matches);
                        $id = $matches[1];
                        $width = '100%';
                        $height = '100%';
                        // &rel=0&showinfo=0&color=white&iv_load_policy=3"
                        ?>
                        <!--
                        <iframe id="<? echo $carouselId;?>_ytplayer" type="text/html" width="<?php echo $width ?>" height="<?php echo $height ?>"
                        src="https://www.youtube.com/embed/<?php echo $id ?>?autoplay=1"
                        frameborder="0" allowfullscreen>
                        </iframe>
                        -->
                        <div id="video-id-111"></div>
                        <div id="video-id-222"></div>
                        <script>
                            // 2. This code loads the IFrame Player API code asynchronously.
                            var divVideo = document.getElementById('video-id-111');
                            console.log(divVideo);


                            var tag = document.createElement('script');
                            tag.src = "https://www.youtube.com/iframe_api";
                            //divVideo.innerHTML(tag);
                            document.getElementById("video-id-111").appendChild(tag);

                            console.log(divVideo);

                            var firstScriptTag = divVideo.getElementsByTagName('script')[0];
                            firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);

                            // 3. This function creates an <iframe> (and YouTube player)
                            //    after the API code downloads.
                            var player;
                            function onYouTubeIframeAPIReady() {
                                player = new YT.Player('video-id-222', {
                                    height: '100%',
                                    width: '100%',
                                    videoId: '<?=$id;?>',
                                    events: {
                                        'onReady': onPlayerReady,
                                        'onStateChange': onPlayerStateChange
                                    }
                                });
                            }

                            // 4. The API will call this function when the video player is ready.
                            function onPlayerReady(event) {
                                event.target.playVideo();
                            }

                            // 5. The API calls this function when the player's state changes.
                            //    The function indicates that when playing a video (state=1),
                            //    the player should play for six seconds and then stop.
                            var done = false;
                            function onPlayerStateChange(event) {
                                if (event.data == YT.PlayerState.PLAYING && !done) {
                                    setTimeout(stopVideo, 6000);
                                    done = true;
                                }
                            }
                            function stopVideo() {
                                player.stopVideo();
                            }
                        </script>


                    </div>
                <?endif;?>
            </div>
        <?endforeach;?>

    </div>
    <!-- Controls -->
    <a class="left carousel-control" href="#<? echo $carouselId;?>" role="button" data-slide="prev">
        <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
        <span class="sr-only">Previous</span>
    </a>
    <a class="right carousel-control" href="#<? echo $carouselId;?>" role="button" data-slide="next">
        <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
        <span class="sr-only">Next</span>
    </a>
</div>





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
<div>
    <pre>
        <?
        if ( $USER->IsAdmin() && $USER->GetID() == 1 ) {
            //echo '<div class="col-md-12"><pre>'; print_r($arResult); echo '</pre></div>';
            //echo '<div class="col-md-12"><pre>'; print_r($arParams); echo '</pre></div>';
        };
        ?>
    </pre>
</div>
