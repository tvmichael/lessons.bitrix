<?php
/*
Bitrix\Main\Diag\Debug::writeToFile(array('url_1' => SITE_DIR."FORM_LOG.txt"),"","log.txt");
$fhandle = fopen("FORM_LOG.txt", "a");
$params='['.date('d-m-Y G:i:s').'] '.var_export($_REQUEST,true);
fwrite($fhandle, "$params\n");
fclose($fhandle);
if ($fhandle)
Bitrix\Main\Diag\Debug::writeToFile(array('url_2' => $params),"","log.txt");
*/

$name=isset($_GET["name"])?($_GET["name"]):'pusto1';
$phone=isset($_GET["phone"])?($_GET["phone"]):'pusto2';

$sbjs=explode('|||',$_COOKIE['sbjs_current']);
$ga=substr($_COOKIE['_ga'],6);
if (!empty($_SERVER['HTTP_CLIENT_IP']))
 {
  $ip=$_SERVER['HTTP_CLIENT_IP'];
 }
elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))
 {
  $ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
 }
else
 {
  $ip=$_SERVER['REMOTE_ADDR'];
 }
$agent=trim($_SERVER['HTTP_USER_AGENT']);

$typ=explode('=',$sbjs[0]);
$src=explode('=',$sbjs[1]);
$mdm=explode('=',$sbjs[2]);
$cmp=explode('=',$sbjs[3]);
$cnt=explode('=',$sbjs[4]);
$trm=explode('=',$sbjs[5]);
$date=date("d-m-Y H:i:s", time());
$from='form1';

		$bx_service_url = 'https://ua/form.php?';
			$encoded = '';
			$encoded .= urlencode('TEXT_FROM_FORM').'='.urlencode("Заказать звонок" ).'&';
			$encoded .= urlencode('NAME_FROM_FORM').'='.urlencode($name).'&'; // "ИМЯ З ФОРМИ" ,
			$encoded .= urlencode('PHONE_FROM_FORM').'='.urlencode($phone).'&'; // "ТЕЛЕФОН З ФОРМИ" ,
			$encoded .= urlencode('UF_CRM_1498593021').'='.urlencode($typ[1]).'&';
			$encoded .= urlencode('UF_CRM_1497442008').'='.urlencode($src[1]).'&';
			$encoded .= urlencode('UF_CRM_1497442036').'='.urlencode($mdm[1]).'&';
			$encoded .= urlencode('UF_CRM_1497442053').'='.urlencode($cmp[1]).'&';
			$encoded .= urlencode('UF_CRM_1497442073').'='.urlencode($cnt[1]).'&';
			$encoded .= urlencode('UF_CRM_1497442089').'='.urlencode($trm[1]).'&';
			$encoded .= urlencode('UF_CRM_1497443716').'='.urlencode($ga).'&';
			$encoded .= urlencode('UF_CRM_1497443772').'='.urlencode($ip).'&';
			$encoded .= urlencode('UF_CRM_1497443808').'='.urlencode($agent).'&';
			$encoded .= urlencode('UF_CRM_1498676996').'='.urlencode($date).'&';
			$encoded .= urlencode('UF_CRM_1498830766').'='.urlencode($rv).'&';
			$encoded .= urlencode('UF_CRM_1499340529').'='.urlencode($from);
         $my_URL=$bx_service_url.$encoded;

/*
         $ch = curl_init($my_URL);
//         curl_setopt($ch, CURLOPT_HEADER, true);
         curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
         $c2=curl_exec($ch);

//        print "CURL: $my_URL\n";
       if ($c2 === false) {
         die("Problem with curl aceess");
         }
*/
//curl_close($ch);
//$fhandle = fopen("ssk-market-form1.ua_GET_new.log", "a");
//$params=var_export($_GET,true);
//fputs($fhandle, "$my_URL\n");
//fclose($fhandle);

//echo $c2;

//Bitrix\Main\Diag\Debug::writeToFile(array('url_form_g' => $_GET, 'url_form_p' => $_POST, '_url_form_request'=>$_REQUEST ),"","log.txt");
?>

