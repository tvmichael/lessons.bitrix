<?
define('STOP_STATISTICS', true);
define('NO_KEEP_STATISTIC', 'Y');
define('NO_AGENT_STATISTIC','Y');
define('DisableEventsCheck', true);
define('BX_SECURITY_SHOW_MESSAGE', true);
define('NOT_CHECK_PERMISSIONS', true);

//define("LOG_FILENAME", $_SERVER["DOCUMENT_ROOT"]."/test/log.txt");

// якщо вказано сайт, то реєструємо його (для випадку декількох сайтів на хості)
if (isset($_REQUEST['site_id']) && is_string($_REQUEST['site_id']))
{
    $siteId = substr(preg_replace('/[^a-z0-9_]/i', '', $_REQUEST['site_id']), 0, 2);
    if (!empty($siteId) && is_string($siteId))
    {
        define('SITE_ID', $siteId);
    }
}

// підключаємо 'пролог' (для доступу до модулів Бітрікса)
require_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_before.php');

// беремо контекст
$request = Bitrix\Main\Application::getInstance()->getContext()->getRequest();
$request->addFilter(new \Bitrix\Main\Web\PostDecodeFilter);

// перевіряємо сесію і метод запиту
if (!check_bitrix_sessid() || !$request->isPost())
    return;

if (!Bitrix\Main\Loader::includeModule('sale'))
    return;

Bitrix\Main\Localization\Loc::loadMessages(dirname(__FILE__).'/class.php');

$signer = new \Bitrix\Main\Security\Sign\Signer;
try
{
    $signedParamsString = $request->get('signedParamsString') ?: '';
    $params = $signer->unsign($signedParamsString, 'sale.order');
    $params = unserialize(base64_decode($params));
}
catch (\Bitrix\Main\Security\Sign\BadSignatureException $e)
{
    die();
}

$action = $request->get($params['ACTION_VARIABLE']);
if (empty($action))
    return;

global $APPLICATION;

//AddMessage2Log("___AJAX___3", "ArhicodeBasketSale");
$APPLICATION->IncludeComponent(
    'arhicode:sale.order',
    '.default',
    $params
);