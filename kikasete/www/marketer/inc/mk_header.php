<?
/******************************************************
' System :きかせて・netマーケターページ
' Content:共通ヘッダ、フッタ
'******************************************************/


require_once ('mk_common.php');

define('PG_NULL', 0);
define('PG_HOME', 1);
define('PG_CONTACT', 2);
define('PG_MAP', 3);
define('PG_KIKASETE', 4);
define('PG_SERVICE', 5);
define('PG_PRICE', 6);
define('PG_REGIST', 7);
define('PG_SURVEY', 8);
define('PG_BUSINESS', 9);
define('PG_CENTER', 10);

define('BLUE', true);
define('NOBLUE', false);

header('Content-Type: text/html; charset=EUC-JP');

function arrayGetValue( $array, $key, $default = NULL) {
	$result = NULL;
	if ( is_array( $array) && isset( $array[ $key])) {
		$result = $array[ $key];
	} else {
		$result = $default;
	}
	return $result;
}

function getImage()
{
	$month = arrayGetValue($_GET, 'month', date('m'));
	$day = arrayGetValue($_GET, 'day', date('d'));
	
	$now = sprintf("%d%02d", $month, $day);
	
	if($now < 111) $jpeg = 1;
	else if($now < 201) $jpeg = 2;
	else if($now < 301) $jpeg = 3;
	else if($now < 401) $jpeg = 4;
	else if($now < 425) $jpeg = 5;
	else if($now < 506) $jpeg = 6;
	else if($now < 601) $jpeg = 7;
	else if($now < 701) $jpeg = 8;
	else if($now < 801) $jpeg = 9;
	else if($now < 821) $jpeg = 10;
	else if($now < 906) $jpeg = 11;
	else if($now < 1001) $jpeg = 12;
	else if($now < 1101) $jpeg = 13;
	else if($now < 1201) $jpeg = 14;
	else if($now < 1216) $jpeg = 15;
	else if($now < 1226) $jpeg = 16;
	else  $jpeg = 17;
	
	$jpeg = sprintf("./images/common/%02d.jpg", $jpeg);
	
	return $jpeg;
}

//共通ヘッダ
function marketer_header($title, $curr_page, $body_option='') {

//現ファイルから、marketerディレクトリへの相対パスの取得
$top_path = getPathToMarketer();


$menu[PG_HOME] = "<a href='" . $top_path . "index.php' onMouseOut='MM_swapImgRestore()' onMouseOver=\"MM_swapImage('Image63','','" . $top_path ."images/common/menu1_on.gif',1)\"><img src='" . $top_path . "images/common/menu1_off.gif' alt='HOME' name='Image63' width='86' height='47' border='0'></a>";

$menu[PG_KIKASETE] = "<a href='" . $top_path . "mk/about/mk_about.php' onMouseOut='MM_swapImgRestore()' onMouseOver=\"MM_swapImage('menu2','','" . $top_path . "images/common/menu2_on.gif',1)\"><img src='" . $top_path . "images/common/menu2_off.gif' alt='きかせて・net について' name='menu2' width='159' height='47' border='0'></a>";

$menu[PG_SERVICE] = "<a href='" . $top_path . "mk/service/mk_service.php' onMouseOut='MM_swapImgRestore()' onMouseOver=\"MM_swapImage('menu3','','" . $top_path . "images/common/menu3_on.gif',1)\"><img src='" . $top_path . "images/common/menu3_off.gif' alt='サービスMENU' name='menu3' width='122' height='47' border='0'></a>";

$menu[PG_REGIST] = "<a href='" . $top_path . "mk/regist/mk_regist.php' onMouseOut='MM_swapImgRestore()' onMouseOver=\"MM_swapImage('menu4','','" . $top_path . "images/common/menu4_on.gif',1)\"><img src='" . $top_path . "images/common/menu4_off.gif' alt='登録' name='menu4' width='70' height='47' border='0'></a>";

$menu[PG_HINT] = "<a href='" . $top_path . "mk/hint/hint.php' onMouseOut='MM_swapImgRestore()' onMouseOver=\"MM_swapImage('menu7','','" . $top_path . "images/common/menu7_on.gif',1)\"><img src='" . $top_path . "images/common/menu7_off.gif' alt='きかせてヒント' name='menu7' width='108' height='47' border='0'></a>";

$menu[PG_SURVEY] = "<a href='" . $top_path . "mk/survey/mk_survey.php' onMouseOut='MM_swapImgRestore()' onMouseOver=\"MM_swapImage('menu5','','" . $top_path . "images/common/menu5_on.gif',1)\"><img src='" . $top_path . "images/common/menu5_off.gif' alt='きかせて・net 調べ' name='menu5' width='141' height='47' border='0'></a>";

$menu[PG_CENTER] = "<a href='" . $top_path . "mk/kkstfrom/mk_kkstfrom.php' onMouseOut='MM_swapImgRestore()' onMouseOver=\"MM_swapImage('menu6','','" . $top_path . "images/common/menu6_on.gif',1)\"><img src='" . $top_path . "images/common/menu6_off.gif' alt='事務局より' name='menu6' width='103' height='47' border='0'></a>";

$menu[PG_CONTACT] = "<a href='" . $top_path . "mk/kkstfrom/mk_contact.php'><img src='" . $top_path . "images/common/icon_mail.gif' width='14' height='9' align='absmiddle' /> メールでのお問い合わせ</a>";

$menu[PG_MAP] = "<a href='" . $top_path . "mk/kkstfrom/mk_map.php'><img src='" . $top_path . "images/common/icon_sitemap.gif' width='11' height='9' /> SITE MAP</a>";

$menu[PG_QUE] = "<a href='" . $top_path . "mk/kkstfrom/mk_faq.php'><img src='" . $top_path . "images/common/icon_que.gif' width='15' height='11' align='absmiddle' /> よくあるご質問</a>";


switch($curr_page)
{
	case PG_HOME:
		$menu[PG_HOME] = "<img src='" . $top_path . "images/common/menu1_on.gif' alt='HOME' name='Image63' width='86' height='47' border='0'>";
		break;
	case PG_KIKASETE:
		$menu[PG_KIKASETE] = "<img src='" . $top_path . "images/common/menu2_on.gif' alt='きかせて・net について' name='menu2' width='159' height='47' border='0'>";
		break;
	case PG_SERVICE:
		$menu[PG_SERVICE] = "<img src='" . $top_path . "images/common/menu3_on.gif' alt='サービスMENU' name='menu3' width='122' height='47' border='0'>";
		break;
	case PG_REGIST:
		$menu[PG_REGIST] = "<img src='" . $top_path . "images/common/menu4_on.gif' alt='登録' name='menu4' width='70' height='47' border='0'>";
		break;
	case PG_HINT:
		$menu[PG_HINT] = "<img src='" . $top_path . "images/common/menu7_on.gif' alt='きかせてヒント' name='menu7' width='108' height='47' border='0'>";
		break;
	case PG_SURVEY:
		$menu[PG_SURVEY] = "<img src='" . $top_path . "images/common/menu5_on.gif' alt='きかせて・net 調べ' name='menu5' width='141' height='47' border='0'>";
		break;
	case PG_CENTER:
		$menu[PG_CENTER] = "<img src='" . $top_path . "images/common/menu6_on.gif' alt='事務局より' name='menu6' width='103' height='47'>";
		break;
}


echo("
<!DOCTYPE HTML PUBLIC '-//W3C//DTD HTML 4.01 Transitional//EN'>
<html>
<head>
<title>$title</title>
<meta name='keywords' content='ネットリサーチ,インターネット調査,リサーチ,インターネットリサーチ,マーケティングリサーチ,市場調査,グループインタビュー,アンケート,ウェブ調査'>
<meta name='description' content='ネットリサーチASPサービス'>
<meta http-equiv='Content-Type' content='text/html; charset=EUC-JP' />
<link href='" . $top_path . "css/style.css' rel='stylesheet' type='text/css' /> 
<script language='JavaScript' type='text/JavaScript' src='" . $top_path . "common.js'></script>
<script language='JavaScript' type='text/JavaScript' src='" . $top_path . "js/w_open.js'></script>
<script language='JavaScript' type='text/JavaScript' src='" . $top_path . "js/menu.js'></script>
<script language='JavaScript' type='text/JavaScript' src='" . $top_path . "js/rollover.js'></script>

</head>
<body bgcolor='#ffffff' onLoad='MM_preloadImages(\"" . $top_path . "images/common/menu3_on.gif\",\"" . $top_path . "images/common/menu4_on.gif\",\"" . $top_path . "images/common/menu1_on.gif\",\"" . $top_path . "images/common/menu2_on.gif\",\"" . $top_path . "images/common/menu6_on.gif\",\"" . $top_path . "images/service/bt02_ov.gif\",\"" . $top_path . "images/service/bt04_ov.gif\",\"" . $top_path . "images/service/bt05_ov.gif\",\"" . $top . "images/service/bt06_ov.gif\",\"" . $top_path . "images/service/bt07_ov.gif\",\"" . $top_path . "images/service/bt08_ov.gif\",\"" . $top_path . "images/service/bt09_ov.gif\"),\"" . $top_path . "images/mk_survey/menu_01-over.gif\",\"" . $top_path . "images/mk_survey/menu_02-over.gif\",\"" . $top_path . "images/mk_survey/menu_03-over.gif\",\"" . $top_path . "images/mk_survey/menu_04-over.gif\",\"" . $top_path . "images/mk_survey/menu_07-over.gif\",\"" . $top_path . "images/common/menu7_on.gif\"'>
<a name='top'></a>
<table border='0' cellpadding='0' cellspacing='0' bgcolor='#FFFFFF'>
  <tr>
    <td width='788'><table width='788' border='0' cellpadding='0' cellspacing='0'>
      <tr>
        <td colspan='2' bgcolor='#002b68'><img src='" . $top_path . "images/common/spacer.gif' width='10' height='3'></td>
      </tr>
      <tr>
        
<td width='232'><a href='" . $top_path . "index.php'><img src='" . $top_path . "images/common/logo.gif' alt='調査(リサーチ)ネットリサーチサービスのきかせて・net' width='232' height='57' border='0' /></a></td>
        <td align='right' valign='bottom' width='556'>
		<!-- ここからヘッダー -->		
		<table border='0' cellpadding='0' cellspacing='8' class='header_text'>
            <tr>
              <td colspan='5' align='left'><h1>ネットリサーチASPならきかせて･net｜ネットリサーチをもっと身近に、もっと簡単に</h1></td>
              </tr>
            <tr>
                <td width='128' align='right' class='footer_text'>".$menu[PG_QUE]." </td>
              <td width='1' bgcolor='#dbdbdb'><img src='" . $top_path . "images/common/spacer.gif' width='1' height='28' /></td>
                <td width='149' align='right' class='footer_text'>
<a href='mk/kkstfrom/mk_contact.php'>".$menu[PG_CONTACT]." </td>
              <td width='1' bgcolor='#dbdbdb'><img src='" . $top_path . "images/common/spacer.gif' width='1' height='28' /></td>
              <td width='276' align='right'><img src='" . $top_path . "images/top/tel.gif' alt='お気軽にお電話ください03-5472-7907'></td>
            </tr>
        </table>
		<!-- ここまでヘッダー -->
		</td>
      </tr>
      <tr>
        <td colspan='2'><table border='0' cellspacing='0' cellpadding='0'>
          <tr>
            
<td>".$menu[PG_HOME]."</td>
<td>".$menu[PG_KIKASETE]."</td>
            <td>".$menu[PG_SERVICE]."</td>
            <td>".$menu[PG_REGIST]."</td>
                        <td>".$menu[PG_HINT]."</td>
<td>".$menu[PG_SURVEY]."</td>
<td>".$menu[PG_CENTER]."</td>
          </tr>
        </table></td>
      </tr>
    </table>
      
</td>
    <td width='1' bgcolor='#dbdbdb'><img src='" . $top_path . "images/common/spacer.gif' width='1' height='10'></td>
  </tr>
</table>

");


}



// 共通フッタ
function marketer_footer($blue=NOBLUE) {

//現ファイルから、marketerディレクトリへの相対パスの取得
$top_path = getPathToMarketer();


if($blue) 
{
	$color1 = "#E5EAF0";
	$color2 = "#DBDBDB";
}
else
{
	$color1 = "#FFFFFF";
	$color2 = "#FFFFFF";
}

echo("
<table border='0' cellpadding='0' cellspacing='0' bgcolor='#FFFFFF'>
<tr> 
<td width='788'> 
<table width='788' border='0' cellpadding='0' cellspacing='0' class='footer_text'>
<tr> 
<td colspan='2' align='right'>
<table width='100%'  border='0' cellspacing='0' cellpadding='0'>
<tr> 
<td width='170' bgcolor='".$color1."'><img src='" . $top_path . "images/common/spacer.gif' width='170' height='5'></td>
<th width='1' bgcolor='".$color2."'><img src='" . $top_path . "images/common/spacer.gif' width='1' height='1'></th>
<td align='right' class='footer_text'><a href='" . $top_path . "index.php'>HOME</a>｜<a href='" . $top_path . "mk/about/mk_about.php'>きかせて 
.netについて</a>｜<a href='" . $top_path . "mk/service/mk_service.php'>リサーチサービスMENU</a>｜<a href='" . $top_path . "mk/regist/mk_regist.php'>登録</a>｜<a href='" . $top_path . "mk/survey/mk_survey.php'>きかせて 
.net 調べ</a>｜<a href='" . $top_path . "mk/kkstfrom/mk_kkstfrom.php'>事務局より</a>｜<a href='" . $top_path . "mk/kkstfrom/mk_map.php'>SITE MAP</a>｜<a href='" . $top_path . "mk/kkstfrom/mk_contact.php'>CONTACT 
US</a><img src='" . $top_path . "images/common/spacer.gif' width='1' height='5'></td>
</tr>
<tr> 
<td width='170' bgcolor='".$color1."'><img src='" . $top_path . "images/common/spacer.gif' width='170' height='5'></td>
<th width='1' bgcolor='".$color2."'><img src='" . $top_path . "images/common/spacer.gif' width='1' height='1'></th>
<td align='right' class='footer_text'><img src='" . $top_path . "images/common/spacer.gif' width='1' height='5'></td>
</tr>
</table>
</td>
</tr>
<tr> 
<td colspan='2' bgcolor='#002b68'><img src='" . $top_path . "images/common/spacer.gif' width='10' height='10' /></td>
</tr>
<tr> 
<td colspan='2' valign='top'><img src='" . $top_path . "images/common/spacer.gif' width='10' height='5'></td>
</tr>
<tr> 
<td align='right' width='65'><a href='http://privacymark.jp' target='_blank'><img src='" . $top_path . "images/common/pmark_s.gif' alt='プライバシーマーク'></a><br /><br />
</td>
<td valign='top' class='footer_text'><img src='" . $top_path . "images/common/spacer.gif' width='5' height='1' />Copyright " . date("Y") . " <a href='http://www.xxxxxxx.co.jp/'>DO 
HOUSE Inc.</a> All rights reserved. 本サイトは、株式会社○○○○○○により運営されております。<br>
<img src='" . $top_path . "images/common/spacer.gif' width='5' height='1' /><span class='footer_text'><a href='http://www.xxxxxxx.co.jp/' target='_blank'>マーケターの皆様へ（会社概要）</a>｜<a href='http://www.xxxxxxx.co.jp/monitor/' target='_blank'>生活者の皆様へ（モニター登録）</a>｜<a href='" . $top_path . "mk/regist/mk_privacy.php'>ウェブサイトプライバシーポリシー</a></span><br />
<img src='" . $top_path . "images/common/spacer.gif' width='5' height='1' />株式会社○○○○○○はプライバシーマークを取得しています。
当社の「個人情報保護方針」は<a href='http://www.xxxxxxx.co.jp/info/privacypolicy.html' target='_blank'>こちら</a>です。<br /><br />
<img src='" . $top_path . "images/common/arrow.gif' hspace='5'>xx xxxxxモニターネットワーク　ネットリサーチの<span style='background-color:#E5EAF0;'><a href='https://www.kikasete.net/monitor/' target='_blank'>きかせて･net</a>｜<a href='http://www.haimail.net/' target='_blank'>はいめーる・net</a>｜<a href='http://do-san.net/' target='_blank'>DOさん・ネット</a>｜<a href='http://www.hitobito.net/' target='_blank'>ひとびと・net</a>｜<a href='http://www.moratame.net/' target='_blank'>モラタメ</a>｜<a href='http://www.melonpan.net/' target='_blank'>めろんぱん</a></span>
</td>
</tr>
</table>
</td>
<td width='1' rowspan='2' bgcolor='#dbdbdb'><img src='" . $top_path . "images/common/spacer.gif' width='1' height='1'></td>
</tr>
<tr>
<td width='1' bgcolor='#dbdbdb'><img src='" . $top_path . "images/common/spacer.gif' width='1' height='1'></td>
</tr>
</table>
</body>
</html>
");
}
?>
