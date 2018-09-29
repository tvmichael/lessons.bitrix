<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
IncludeTemplateLangFile($_SERVER["DOCUMENT_ROOT"]."/bitrix/templates/".SITE_TEMPLATE_ID."/header.php");
CJSCore::Init(array("fx"));
$curPage = $APPLICATION->GetCurPage(true);
$theme = COption::GetOptionString("main", "wizard_eshop_bootstrap_theme_id", "blue", SITE_ID);
?>
<!DOCTYPE html>
<html xml:lang="<?=LANGUAGE_ID?>" lang="<?=LANGUAGE_ID?>">
<head>
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
	<meta name="viewport" content="user-scalable=no, initial-scale=1.0, maximum-scale=1.0, width=device-width">
	<link rel="shortcut icon" type="image/x-icon" href="<?=htmlspecialcharsbx(SITE_DIR)?>favicon.ico" />
	<?$APPLICATION->ShowHead();?>
	<?
	$APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH."/colors.css", true);
	$APPLICATION->SetAdditionalCSS("/bitrix/css/main/bootstrap.css");
	$APPLICATION->SetAdditionalCSS("/bitrix/css/main/font-awesome.css");
	?>
	<title><?$APPLICATION->ShowTitle()?></title>
</head>
<body class="bx-background-image bx-theme-<?=$theme?>" <?=$APPLICATION->ShowProperty("backgroundImage")?>>
<div id="panel"><?$APPLICATION->ShowPanel();?></div>
<?$APPLICATION->IncludeComponent("bitrix:eshop.banner", "", array());?>
<div class="bx-wrapper" id="bx_eshop_wrap">
	<header class="bx-header">
		<div class="bx-header-section container">
			<div class="row">
				<div class="col-lg-3 col-md-3 col-sm-4 col-xs-12">
					<div class="bx-logo">
						<a class="bx-logo-block hidden-xs" href="<?=htmlspecialcharsbx(SITE_DIR)?>">
							<?$APPLICATION->IncludeComponent("bitrix:main.include", "", array("AREA_FILE_SHOW" => "file", "PATH" => SITE_DIR."include/company_logo.php"), false);?>
						</a>
						<a class="bx-logo-block hidden-lg hidden-md hidden-sm text-center" href="<?=htmlspecialcharsbx(SITE_DIR)?>">
							<?$APPLICATION->IncludeComponent("bitrix:main.include", "", array("AREA_FILE_SHOW" => "file", "PATH" => SITE_DIR."include/company_logo_mobile.php"), false);?>
						</a>
					</div>
				</div>
				<div class="col-lg-3 col-md-3 col-sm-4 col-xs-12">
					<div class="bx-inc-orginfo">
						<div>
							<span class="bx-inc-orginfo-phone"><i class="fa fa-phone"></i> <?$APPLICATION->IncludeComponent("bitrix:main.include", "", array("AREA_FILE_SHOW" => "file", "PATH" => SITE_DIR."include/telephone.php"), false);?></span>
						</div>
					</div>
				</div>
				<div class="col-lg-3 col-md-3 hidden-sm hidden-xs">
					<div class="bx-worktime">
						<div class="bx-worktime-prop">
							<?$APPLICATION->IncludeComponent("bitrix:main.include", "", array("AREA_FILE_SHOW" => "file", "PATH" => SITE_DIR."include/schedule.php"), false);?>
						</div>
					</div>
				</div>
				<div class="col-lg-3 col-md-3 col-sm-4 col-xs-12 hidden-xs">
					<?$APPLICATION->IncludeComponent("bitrix:sale.basket.basket.line", "", array(
							"PATH_TO_BASKET" => SITE_DIR."personal/cart/",
							"PATH_TO_PERSONAL" => SITE_DIR."personal/",
							"SHOW_PERSONAL_LINK" => "N",
							"SHOW_NUM_PRODUCTS" => "Y",
							"SHOW_TOTAL_PRICE" => "Y",
							"SHOW_PRODUCTS" => "N",
							"POSITION_FIXED" =>"N",
							"SHOW_AUTHOR" => "Y",
							"PATH_TO_REGISTER" => SITE_DIR."login/",
							"PATH_TO_PROFILE" => SITE_DIR."personal/"
						),
						false,
						array()
					);?>
				</div>
			</div>
			<div class="row">
				<div class="col-md-12 hidden-xs">
					<?$APPLICATION->IncludeComponent("bitrix:menu", "catalog_horizontal", array(
							"ROOT_MENU_TYPE" => "left",
							"MENU_CACHE_TYPE" => "A",
							"MENU_CACHE_TIME" => "36000000",
							"MENU_CACHE_USE_GROUPS" => "Y",
							"MENU_THEME" => "site",
							"CACHE_SELECTED_ITEMS" => "N",
							"MENU_CACHE_GET_VARS" => array(
							),
							"MAX_LEVEL" => "3",
							"CHILD_MENU_TYPE" => "left",
							"USE_EXT" => "Y",
							"DELAY" => "N",
							"ALLOW_MULTI_SELECT" => "N",
						),
						false
					);?>
				</div>
			</div>
			<?if ($curPage != SITE_DIR."index.php"):?>
			<div class="row">
				<div class="col-lg-12">
					<?$APPLICATION->IncludeComponent(
					        "mv:search.title",
                            "visual",
                            Array(
                                "NUM_CATEGORIES" => "1",	// Количество категорий поиска
                                "TOP_COUNT" => "5",	// Количество результатов в каждой категории
                                "CHECK_DATES" => "N",	// Искать только в активных по дате документах
                                "SHOW_OTHERS" => "N",	// Показывать категорию "прочее"
                                "PAGE" => SITE_DIR."catalog/",	// Страница выдачи результатов поиска (доступен макрос #SITE_DIR#)
                                "CATEGORY_0_TITLE" => GetMessage("SEARCH_GOODS"),	// Название категории
                                "CATEGORY_0" => array(	// Ограничение области поиска
                                    0 => "iblock_catalog",
                                ),
                                "CATEGORY_0_iblock_catalog" => array(	// Искать в информационных блоках типа "iblock_catalog"
                                    0 => "all",
                                ),
                                "CATEGORY_OTHERS_TITLE" => GetMessage("SEARCH_OTHER"),
                                "SHOW_INPUT" => "Y",	// Показывать форму ввода поискового запроса
                                "INPUT_ID" => "title-search-input",	// ID строки ввода поискового запроса
                                "CONTAINER_ID" => "search",	// ID контейнера, по ширине которого будут выводиться результаты
                                "PRICE_CODE" => array(	// Тип цены
                                    0 => "BASE",
                                ),
                                "SHOW_PREVIEW" => "Y",	// Показать картинку
                                "PREVIEW_WIDTH" => "75",	// Ширина картинки
                                "PREVIEW_HEIGHT" => "75",	// Высота картинки
                                "CONVERT_CURRENCY" => "Y",	// Показывать цены в одной валюте
                                "COMPONENT_TEMPLATE" => "visual",
                                "ORDER" => "date",	// Сортировка результатов
                                "USE_LANGUAGE_GUESS" => "Y",	// Включить автоопределение раскладки клавиатуры
                                "PRICE_VAT_INCLUDE" => "Y",	// Включать НДС в цену
                                "PREVIEW_TRUNCATE_LEN" => "",	// Максимальная длина анонса для вывода
                                "CURRENCY_ID" => "RUB",	// Валюта, в которую будут сконвертированы цены
                            ),
                            false
                        );?>
				</div>
			</div>
			<?endif?>

			<?
			if ($curPage != SITE_DIR."index.php")
			{
			?>
			<div class="row">
				<div class="col-lg-12" id="navigation">
					<?$APPLICATION->IncludeComponent("bitrix:breadcrumb", "", array(
							"START_FROM" => "0",
							"PATH" => "",
							"SITE_ID" => "-"
						),
						false,
						Array('HIDE_ICONS' => 'Y')
					);?>
				</div>
			</div>
			<h1 class="bx-title dbg_title" id="pagetitle"><?=$APPLICATION->ShowTitle(false);?></h1>
			<?
			}
			else
			{
			?>
			<h1 style="display: none"><?$APPLICATION->ShowTitle()?></h1>
			<?
			}
			?>
		</div>
	</header>

	<div class="workarea">
		<div class="container bx-content-seection">
			<div class="row">
			<?
			$hideSidebar =
				defined("HIDE_SIDEBAR") && HIDE_SIDEBAR == true
				|| preg_match("~^".SITE_DIR."(catalog|personal\\/cart|personal\\/order\\/make)/~", $curPage)
			? true : false;
			?>
				<div class="bx-content <?=($hideSidebar ? "col-xs-12" : "col-md-9 col-sm-8")?>">