<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
IncludeTemplateLangFile($_SERVER["DOCUMENT_ROOT"]."/bitrix/templates/".SITE_TEMPLATE_ID."/header.php");

CJSCore::Init(array("fx", "jquery"));
$curPage = $APPLICATION->GetCurPage(true);
?>
<!DOCTYPE html>
<html xml:lang="<?=LANGUAGE_ID?>" lang="<?=LANGUAGE_ID?>">
<head>
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
	<meta name="viewport" content="user-scalable=no, initial-scale=1.0, maximum-scale=1.0, width=device-width">
	<link rel="shortcut icon" type="image/x-icon" href="<?=SITE_DIR?>favicon.ico" />
	<?$APPLICATION->ShowHead();?>
    <?
	$APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH."/colors.css", true);
	$APPLICATION->SetAdditionalCSS("/bitrix/css/main/bootstrap.css");
	$APPLICATION->SetAdditionalCSS("/bitrix/css/main/font-awesome.css");
    $APPLICATION->AddHeadScript('https://code.jquery.com/jquery-3.1.1.slim.min.js');
	?>
    <title><?$APPLICATION->ShowTitle()?></title>
</head>

<body class="bx-background-image">
<div id="panel"><?$APPLICATION->ShowPanel();?></div>

<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div>
                <?$APPLICATION->IncludeComponent(
                    "bitrix:menu",
                    "catalog_horizontal",
                    Array(
                        "ALLOW_MULTI_SELECT" => "N",
                        "CHILD_MENU_TYPE" => "left",
                        "COMPONENT_TEMPLATE" => "catalog_horizontal",
                        "DELAY" => "N",
                        "MAX_LEVEL" => "1",
                        "MENU_CACHE_GET_VARS" => array(),
                        "MENU_CACHE_TIME" => "3600",
                        "MENU_CACHE_TYPE" => "N",
                        "MENU_CACHE_USE_GROUPS" => "Y",
                        "MENU_THEME" => "site",
                        "ROOT_MENU_TYPE" => "left",
                        "USE_EXT" => "N"
                    )
                );?><br>
            </div>
        </div>

