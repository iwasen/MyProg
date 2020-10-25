<?
/******************************************************
' System :きかせて・netマーケター有料Ｍｙページ
' Content:共通ヘッダ、フッタ
'******************************************************/
if (!isset($_SERVER['HTTPS'])) header("Location: https://" . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI']);

include_once('../inc/count.php');
include_once("../inc/database.php");

include_once("../inc/mr_permission.php");

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
define('PG_HINT', 11);

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
	
	$jpeg = sprintf("./images/common_my/%02d.jpg", $jpeg);
	
	return $jpeg;
}


function marketer_header($title, $curr_page, $body_option='', $nocache=true) {
	global $my_agent_id, $owner_id;

	$owner_id = ($_SESSION['ss_marketer_type'] == 4) ? $_SESSION['ss_owner_id'] : $_SESSION['ss_marketer_id'];

	// エージェントID取得
	$sql = "SELECT mr_agent_id"
			. " FROM t_marketer"
			. " WHERE mr_marketer_id=$owner_id";
	$result = db_exec($sql);
	if (pg_numrows($result)) {
		$fetch = pg_fetch_object($result, 0);
		$my_agent_id = $fetch->mr_agent_id;
	}
	
	// 登録日取得
	$sql = "SELECT mr_regist_date FROM t_marketer WHERE mr_marketer_id={$_SESSION['ss_marketer_id']}";
	$result = db_exec($sql);
	if (pg_numrows($result)) {
		$fetch = pg_fetch_row($result, 0);
		$start_y = get_datepart('Y', $fetch[0]);
		$start_m = get_datepart('M', $fetch[0]);
	}
	$end_y = date('Y');
	$end_m = (int)date('m');
	$sdate = sprintf("%4d 年 %2d 月 〜 %4d 年 %2d 月", $start_y,$start_m, $end_y,$end_m);
	
	// エージェント名取得
	$sql = "SELECT mr_name1,ag_name1"
			. " FROM t_marketer JOIN t_agent ON mr_agent_id=ag_agent_id"
			. " WHERE mr_marketer_id=$owner_id";
	$result = db_exec($sql);
	if (pg_numrows($result)) {
		$fetch = pg_fetch_object($result, 0);
		$marketer_name = htmlspecialchars($fetch->mr_name1);
		$agent_name = htmlspecialchars($fetch->ag_name1);
	}
	
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=euc-jp" />
<? if ($nocache || $_SERVER["SERVER_PORT"] != 443) { ?>
<meta http-equiv="Pragma" content="no-cache">
<? } ?>
<title><?=$title?></title>
<link href="css/style.css" rel="stylesheet" type="text/css" />
<script language="JavaScript" type="text/JavaScript" src="js/w_open.js"></script>
<script language="JavaScript" type="text/JavaScript" src="js/menu.js"></script>
<link rel="stylesheet" href="kikasete.css">


<script language="JavaScript">
<!--
function mmLoadMenus() {
  if (window.mm_menu_1105102848_0) return;
    window.mm_menu_1105102848_0 = new Menu("root",150,16,"ＭＳ Ｐゴシック, Osaka, ヒラギノ角ゴ Pro W3",10,"#0000FF","#00CCFF","#FFFFFF","#FFFFFF","left","middle",3,0,1000,-5,7,true,true,true,0,true,true);
  mm_menu_1105102848_0.addMenuItem("ネットリサーチの本質","location='mkk_kh_basic01.php'");
  mm_menu_1105102848_0.addMenuItem("返信率カーブの変遷","location='mkk_kh_basic02.php'");
  mm_menu_1105102848_0.addMenuItem("調査票の作り方　６つのステップ","location='mkk_kh_basic03.php'");
   mm_menu_1105102848_0.hideOnMouseOut=true;
   mm_menu_1105102848_0.bgColor='#FFFFFF';
   mm_menu_1105102848_0.menuBorder=1;
   mm_menu_1105102848_0.menuLiteBgColor='#FFFFFF';
   mm_menu_1105102848_0.menuBorderBgColor='#FF5C5C';
window.mm_menu_1105103255_0 = new Menu("root",205,16,"ＭＳ Ｐゴシック, Osaka, ヒラギノ角ゴ Pro W3",10,"#0000FF","#00CCFF","#FFFFFF","#FFFFFF","left","middle",3,0,1000,-5,7,true,true,true,0,true,true);
  mm_menu_1105103255_0.addMenuItem("サブジェクトは簡潔に内容を要約する","location='mkk_kh_emc01.php'");
  mm_menu_1105103255_0.addMenuItem("引用は必要な部分だけ","location='mkk_kh_emc02.php'");
  mm_menu_1105103255_0.addMenuItem("インタビュアーは横展開と縦の深堀を繰り返す","location='mkk_kh_emc03.php'");
  mm_menu_1105103255_0.addMenuItem("コミュニケーションメールの書き方","location='mkk_kh_emc04.php'");
   mm_menu_1105103255_0.hideOnMouseOut=true;
   mm_menu_1105103255_0.bgColor='#FFFFFF';
   mm_menu_1105103255_0.menuBorder=1;
   mm_menu_1105103255_0.menuLiteBgColor='#FFFFFF';
   mm_menu_1105103255_0.menuBorderBgColor='#FFA30A';

  window.mm_menu_1105103826_0 = new Menu("root",200,16,"ＭＳ Ｐゴシック, Osaka, ヒラギノ角ゴ Pro W3",10,"#0000FF","#00CCFF","#FFFFFF","#FFFFFF","left","middle",3,0,1000,-5,7,true,true,true,0,true,true);
  mm_menu_1105103826_0.addMenuItem("純粋想起から助成想起へ","location='mkk_kh_nrp01.php'");
  mm_menu_1105103826_0.addMenuItem("例文を付ける","location='mkk_kh_nrp02.php'");
  mm_menu_1105103826_0.addMenuItem("解答を求めない","location='mkk_kh_nrp03.php'");
  mm_menu_1105103826_0.addMenuItem("５段階評価の集計を加重平均を使って行う","location='mkk_kh_nrp04.php'");
   mm_menu_1105103826_0.hideOnMouseOut=true;
   mm_menu_1105103826_0.bgColor='#FFFFFF';
   mm_menu_1105103826_0.menuBorder=1;
   mm_menu_1105103826_0.menuLiteBgColor='#FFFFFF';
   mm_menu_1105103826_0.menuBorderBgColor='#5CA329';

mm_menu_1105103826_0.writeMenus();
} // mmLoadMenus()
//-->
</script>
<script language="JavaScript" src="mm_menu.js"></script>


<SCRIPT LANGUAGE="JavaScript">
<!--
function new_window(url, width, height) {
	var attributes = "toolbar=no,location=no,status=0,scrollbars=1,resizable=1,width=";
	attributes += width + ",height=" + height;
	window.open(url, 'mainhelp', attributes);
}
// -->
</SCRIPT>
</head>
<body bgcolor="#ffffff" onLoad="MM_preloadImages('images/common_my/bt_01_ov.gif','images/common_my/bt_02_ov.gif','images/common_my/bt_04_ov.gif','images/common_my/title_05_ov.gif','images/common_my/bt_serch_ov.gif','images/common_my/bt_agent_ov.gif','images/common_my/bt_koushin_ov.gif','images/common_my/bt_assi_ov.gif','images/common_my/bt_henkou_ov.gif','images/common_my/bt_pro_ov.gif','images/common_my/bt_office_ov.gif','images/common_my/bt_sitemap_ov.gif','images/common_my/bt_contact_ov.gif','images/common_my/bt_case_ov.gif')" <?=$body_option?>>
<a name="top"></a>
<table border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
  <tr>
    <td width="788">
<table width="788" border="0" cellpadding="0" cellspacing="0">
<tr> 
<td colspan="2" bgcolor="#002b68"><img src="images/common/spacer.gif" width="10" height="3"></td>
</tr>
<tr> 
<td width="410"><a href="mkk_index.php"><img src="images/common_my/logo.jpg" alt="きかせて・net" width="228" height="64"><img src="images/common_my/img_head2.gif" width="182" height="64"></a></td>
<td width="378" align="right" valign="bottom"> 
<table width="100%" border="0" cellpadding="0" cellspacing="8" class="header_text">
<tr> 
<td valign="bottom" align=right> 
<table width="88" border="0" cellspacing="0" cellpadding="0">
<tr> 
<td><a href="mkk_index.php" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('Image80','','images/common_my/bt_01_ov.gif',1)"><img src="images/common_my/bt_01.gif" alt="My Page Top" name="Image80" width="88" height="20" border="0"></a></td>
</tr>
<tr> 
<td><img src="images/common/spacer.gif" width="10" height="4" /></td>
</tr>
<tr> 
<td><a href="mk_logout.php" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('Image82','','images/common_my/bt_02_ov.gif',1)"><img src="images/common_my/bt_02.gif" alt="Log Out" name="Image82" width="88" height="20" border="0"></a></td>
</tr>
</table>
</td>
</tr>
</table>
</td>
</tr>
<tr> 
<td colspan="2">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
<tr>
<td height="1"  bgcolor="#dbdbdb">&nbsp;</td>
</tr>
</table>
</td>
</tr>
</table>
     
</td>
    <td width="1" bgcolor="#dbdbdb"><img src="images/common/spacer.gif" width="1" height="1"></td>
  </tr>
</table>
<?
}


/*--------------------------------------------------------------------
 *	マイページサイドメニュー
 *-------------------------------------------------------------------*/
define("M_NULL",  0);
define("M_MYENQ",     1);
define("M_MYPARTNER", 2);
define("M_MYSEARCH",  3);
define("M_MYAGENT",   4);
define("M_REGIST",    5);
define("M_ASSISTANT", 6);
define("M_AGENTEDIT", 7);
define("M_PROENQ",    8);
define("M_OFFICE",    9);
define("M_SITEMAP",  10);
define("M_CONTACT",  11);
define("M_MONITOR",  12);

function marketer_menu($page=M_NULL){
	$menu = array();


	$menu[M_MYENQ] = "<a href=\"mkk_myenq.php\" onMouseOut=\"MM_swapImgRestore()\" onMouseOver=\"MM_swapImage('Image42','','images/common_my/bt_04_ov.gif',1)\"><img src=\"images/common_my/bt_04.gif\" alt=\"Ｍｙアンケート\" name=\"Image42\" width=\"197\" height=\"30\" border=\"0\"></a>";

if (check_permission(PMR_MY_MONITOR)) {
	$menu[M_MYPARTNER] = "<a href=\"mp_pjt_list.php\" onMouseOut=\"MM_swapImgRestore()\" onMouseOver=\"MM_swapImage('Image43','','images/common_my/title_05_ov.gif',1)\"><img src=\"images/common_my/title_05.gif\" alt=\"Ｍｙパートナー\" name=\"Image43\" width=\"197\" height=\"30\" border=\"0\"></a>";
}
if (check_permission(PMR_MY_SEARCH)) {
	$menu[M_MYSEARCH] = "<a href=\"mkk_mysearch.php\" onMouseOut=\"MM_swapImgRestore()\" onMouseOver=\"MM_swapImage('Image44','','images/common_my/bt_serch_ov.gif',1)\"><img src=\"images/common_my/bt_serch.gif\" alt=\"Myサーチ\" name=\"Image44\" width=\"197\" height=\"30\" border=\"0\"></a>";
}
if (check_permission(PMR_MY_AGENT)) {
	$menu[M_MYAGENT] = "<a href=\"mkk_myagent.php\" onMouseOut=\"MM_swapImgRestore()\" onMouseOver=\"MM_swapImage('Image45','','images/common_my/bt_agent_ov.gif',1)\"><img src=\"images/common_my/bt_agent.gif\" alt=\"Myエージェント\" name=\"Image45\" width=\"197\" height=\"30\" border=\"0\"></a>";
}
if (check_permission(PMR_REGIST_INF)) {
	$menu[M_REGIST] = "<a href=\"mkk_update.php\" onMouseOut=\"MM_swapImgRestore()\" onMouseOver=\"MM_swapImage('Image46','','images/common_my/bt_koushin_ov.gif',1)\"><img src=\"images/common_my/bt_koushin.gif\" alt=\"登録情報の更新\" name=\"Image46\" width=\"197\" height=\"30\" border=\"0\"></a>";
}
if (check_permission(PMR_ASSISTANT)) {
	$menu[M_ASSISTANT] = "<a href=\"mkk_assistant.php\" onMouseOut=\"MM_swapImgRestore()\" onMouseOver=\"MM_swapImage('Image47','','images/common_my/bt_assi_ov.gif',1)\"><img src=\"images/common_my/bt_assi.gif\" alt=\"アシスタントの設定\" name=\"Image47\" width=\"197\" height=\"30\" border=\"0\"></a>";
}
if (check_permission(PMR_CHG_AGENT)) {
	$menu[M_AGENTEDIT] = "<a href=\"mkk_achange.php\" onMouseOut=\"MM_swapImgRestore()\" onMouseOver=\"MM_swapImage('Image48','','images/common_my/bt_henkou_ov.gif',1)\"><img src=\"images/common_my/bt_henkou.gif\" alt=\"エージェントの変更\" name=\"Image48\" width=\"197\" height=\"30\" border=\"0\"></a>";
}
	$menu[M_PROENQ] = "<a href=\"pe_index.php\" onMouseOut=\"MM_swapImgRestore()\" onMouseOver=\"MM_swapImage('Image49','','images/common_my/bt_pro_ov.gif',1)\"><img src=\"images/common_my/bt_pro.gif\" alt=\"Proアンケート\" name=\"Image49\" width=\"197\" height=\"30\" border=\"0\"></a>";

	$menu[M_OFFICE] = "<a href=\"mkk_kkstfrom.php\" onMouseOut=\"MM_swapImgRestore()\" onMouseOver=\"MM_swapImage('Image51','','images/common_my/bt_office_ov.gif',1)\"><img src=\"images/common_my/bt_office.gif\" alt=\"事務局より\" name=\"Image51\" width=\"197\" height=\"30\" border=\"0\"></a>";
	
	$menu[M_SITEMAP] = "<a href=\"mkk_map.php\" onMouseOut=\"MM_swapImgRestore()\" onMouseOver=\"MM_swapImage('Image52','','images/common_my/bt_sitemap_ov.gif',1)\"><img src=\"images/common_my/bt_sitemap.gif\" alt=\"sitemap\" name=\"Image52\" width=\"197\" height=\"30\" border=\"0\"></a>";
	
if (check_permission(PMR_MY_AGENT)) {
	$menu[M_CONTACT] = "<a href=\"mkk_contact.php\" onMouseOut=\"MM_swapImgRestore()\" onMouseOver=\"MM_swapImage('Image53','','images/common_my/bt_contact_ov.gif',1)\"><img src=\"images/common_my/bt_contact.gif\" alt=\"contact us\" name=\"Image53\" width=\"197\" height=\"32\" border=\"0\"></a>";
}

	$menu[M_MONIOTR] = "<a href=\"mkk_monitor.php\" onMouseOut=\"MM_swapImgRestore()\" onMouseOver=\"MM_swapImage('Image55','','images/common_my/bt_monitor_ov.gif',1)\"><img src=\"images/common_my/bt_monitor.gif\" alt=\"モニタープロファイル\" name=\"Image55\" width=\"197\" height=\"30\" border=\"0\"></a>";

	
	switch($page) {
	case M_MYENQ:
		$menu[M_MYENQ] = "<a href=\"mkk_myenq.php\"><img src='images/common_my/bt_04_ov.gif' alt='Myアンケート' width='197' height='30'></a>";
		break;
	case M_MYPARTNER:
		$menu[M_MYPARTNER] = "<a href=\"mp_pjt_list.php\"><img src='images/common_my/title_05_ov.gif' alt='Myパートナー' width='197' height='30'></a>";
		break;
	case M_MYSEARCH:
		$menu[M_MYSEARCH] = "<a href=\"mkk_mysearch.php\"><img src='images/common_my/bt_serch_ov.gif' alt='Myサーチ' width='197' height='30'></a>";
		break;
	case M_MYAGENT:
		$menu[M_MYAGENT] = "<a href=\"mkk_myagent.php\"><img src='images/common_my/bt_agent_ov.gif' alt='Myエージェント' width='197' height='30'></a>";
		break;
	case M_REGIST:
		$menu[M_REGIST] = "<a href=\"mkk_update.php\"><img src='images/common_my/bt_koushin_ov.gif' alt='登録情報の更新' width='197' height='30'></a>";
		break;
	case M_ASSISTANT:
		$menu[M_ASSISTANT] = "<a href=\"mkk_assistant.php\"><img src='images/common_my/bt_assi_ov.gif' alt='アシスタントの設定' width='197' height='30'></a>";
		break;
	case M_AGENTEDIT:
		$menu[M_AGENTEDIT] = "<a href=\"mkk_achange.php\"><img src='images/common_my/bt_henkou_ov.gif' alt='エージェントの変更' width='197' height='30'></a>";
		break;
	case M_PROENQ:
		$menu[M_PROENQ] = "<a href=\"pe_index.php\"><img src='images/common_my/bt_pro_ov.gif' alt='Proアンケート' width='197' height='30'></a>";
		break;
	case M_OFFICE:
		$menu[M_OFFICE] = "<a href=\"mkk_kkstfrom.php\"><img src='images/common_my/bt_office_ov.gif' alt='事務局より' width='197' height='30'></a>";
		break;
	case M_SITEMAP:
		$menu[M_SITEMAP] = "<a href=\"mkk_map.php\"><img src='images/common_my/bt_sitemap_ov.gif' alt='sitemap' width='197' height='30'></a>";
		break;
	case M_CONTACT:
		$menu[M_CONTACT] = "<a href=\"mkk_contact.php\"><img src='images/common_my/bt_contact_ov.gif' alt='contact us' width='197' height='32'></a>";
		break;
	case M_MONITOR:
		$menu[M_MONIOTR] = "<a href=\"mkk_monitor.php\"><img src='images/common_my/bt_monitor_ov.gif' alt='モニタープロファイル' width='197' height='32'></a>";
		break;
	}
	
	echo("
<table width='100%'  border='0' cellspacing='0' cellpadding='0'>
<tr> 
<td bgcolor='#FFFFFF'><img src='images/common_my/img_03.gif' alt='My Menu' width='121' height='16'></td>
</tr>
<tr> 
<td>".$menu[M_MYENQ]."</td>
</tr>
<tr> 
<td>".$menu[M_PROENQ]."</td>
</tr>
<tr> 
<td>".$menu[M_MYPARTNER]."</td>
</tr>
<tr> 
<td><img src='images/common_my/img_08.gif' alt='My　setting' width='197' height='30'></td>
</tr>
<!--
<tr> 
<td>".$menu[M_MYAGENT]."</td>
</tr>
-->
<tr> 
<td>".$menu[M_REGIST]."</td>
</tr>
<tr> 
<td>".$menu[M_ASSISTANT]."</td>
</tr>
<!-- 
<tr>
<td>".$menu[M_AGENTEDIT]."</td>
</tr>
-->
<tr> 
<td><img src='images/common_my/img_common.gif' alt='common' width='197' height='30'></td>
</tr>
<tr> 
<td>".$menu[M_MONIOTR]."</td>
</tr>
<tr> 
<td>".$menu[M_OFFICE]."</td>
</tr>
<tr> 
<td>".$menu[M_CONTACT]."</td>
</tr>
<tr> 
<td align=center><br><a href='mk/service/mk_3_price.php' target=_blank><img src='images/menu_price.jpg' alt='料金表' width='170' height='36'></a></td>
</tr>
<tr> 
<td align=center><br><a href='pdf/researchmanual_kikasete.pdf' target=_blank><img src='images/menu_manual.jpg' alt='操作マニュアル' width='170' height='36' border=0></a></td>
</tr>
<tr> 
<td align=center><br><a href='enqview/enqview1.0.0.3.zip' target=_blank><img src='images/menu_enqview.jpg' alt='EnqViewダウンロード' width='170' height='36' border=0></a></td>
</tr>

</table>
	");
}

function page_img1($page, $current_page) {
	return ($page == $current_page) ? 'redsquare.gif' : 'square.gif';
}

function page_img2($page, $current_page) {
	switch ($page) {
	case PG_KIKASETE:
		$img = 'mk_kikasete';
		break;
	case PG_SERVICE:
		$img = 'mk_service';
		break;
	case PG_PRICE:
		$img = 'mk_price';
		break;
	case PG_REGIST:
		$img = 'mk_kregist';
		break;
	case PG_SURVEY:
		$img = 'mk_survey';
		break;
	case PG_BUSINESS:
		$img = 'mk_business';
		break;
	case PG_CENTER:
		$img = 'mk_kkstfrom';
		break;
	case PG_HINT:
		$img = 'mk_hint';
		break;
	}

	if ($page == $current_page)
		$img .= '_b';

	return $img . '.gif';
}



define('BLUE', true);
define('NOBLUE', false);

function marketer_footer($blue=NOBLUE) {

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

?>
<table border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
<tr> 
<td width="788">
<table width="788" border="0" cellpadding="0" cellspacing="0" class="footer_text">
<tr> 
<td colspan="2" align="right"> 
<table width="100%"  border="0" cellspacing="0" cellpadding="0">
<tr> 
<td width="170" bgcolor="<?=$color1 ?>"><img src="images/common/spacer.gif" width="198" height="5"></td>
<td width="1" bgcolor="<?=$color2 ?>" align="center"><img src="images/common/spacer.gif" width="1" height="1"></td>
<td align="right" class="footer_text"><a href="mkk_index.php">HOME</a>｜<a href="mkk_kkstfrom.php">事務局より</a>｜<a href="mk/kkstfrom/mk_contact.php" target="_blank">CONTACT US</a><img src="images/common/spacer.gif" width="18" height="5"></td>
</tr>
<tr> 
<td width="170" bgcolor="<?=$color1 ?>"><img src="images/common/spacer.gif" width="170" height="5"></td>
<td width="1" bgcolor="<?=$color2 ?>" align="center"><img src="images/common/spacer.gif" width="1" height="1"></td>
<td align="right" class="footer_text"><img src="images/common/spacer.gif" width="1" height="5"></td>
</tr>
</table></td>
</tr>
<tr> 
<td colspan="2" bgcolor="#002b68"><img src="images/common/spacer.gif" width="10" height="10" /></td>
</tr>
<tr> 
<td colspan="2" valign="top"><img src="images/common/spacer.gif" width="10" height="5"></td>
</tr>
<tr> 
<td width="170" align="right"><a href="http://privacymark.jp" target="_blank"><img src="images/common/pmark_s.gif" alt="プライバシーマーク" width="75" height="80" /><br>
  <br>
</a></td>
<td width="618" valign="top" class="footer_text"><img src="images/common/spacer.gif" width="10" height="8">Copyright <?php echo date("Y"); ?> <a href="http://www.xxxxxxx.co.jp/" target="_blank">xx xxxxx Inc.</a> All rights reserved.本サイトは、  
  株式会社○○○○○○により運営されております。<br>
  <br>
  <img src="images/common/spacer.gif" width="10" height="8"><a href="http://www.xxxxxxx.co.jp/" target="_blank">マーケターの皆様へ（会社概要）</a>｜<a href="http://www.xxxxxxx.co.jp/monitor/" target="_blank">生活者の皆様へ（モニター登録）</a>｜<a href="mkk_privacy.php">ウェブサイトプライバシーポリシー</a><br>
  <img src="images/common/spacer.gif" width="10" height="8">株式会社○○○○○○はプライバシーマークを取得しています。
当社の「個人情報保護方針」は<a href="http://www.xxxxxxx.co.jp/info/privacypolicy.html" target="_blank">こちら</a>です。<br>  <br></td>
</tr>
</table>
</td>
<td width="1" rowspan="2" bgcolor="#dbdbdb"><img src="images/common/spacer.gif" width="1" height="10"></td>
</tr>
<tr>
<td bgcolor="#dbdbdb"><img src="images/common/spacer.gif" width="1" height="1"></td>
</tr>
</table>
</body>
</html>
<?
}



// エージェント表示
function my_disp_agent($url) {
	$sql = "SELECT ag_agent_id,ag_name1 FROM t_agent WHERE ag_status=0 AND ag_disp_flag ORDER BY ag_agent_id DESC";
	$result = db_exec($sql);
	$nrow = pg_numrows($result);
	
	//echo "<table>\n";
	
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);

		if ($i % 2 == 0) {
			echo '<tr><td colspan="3"><img src="images/common/spacer.gif" width="1" height="3" border="0"></td></tr>', "\n";
			echo "<tr>\n";
		}

		echo "<td width=67 align='center'><img src='images/agent{$fetch->ag_agent_id}.jpg' width=60 height=60 border=0><br>\n";
		echo "<a href='javascript:void(0)' onclick='open(\"$url{$fetch->ag_agent_id}\", \"_blank\", \"width=742,height=730,scrollbars=yes,resizable=yes,status=yes\")'><font size=3 class='honbun1'>{$fetch->ag_name1}です</font></a></td>\n";

		if ($i % 2 == 0)
			echo "<td><img src='images/common/spacer.gif' width=3 height=1 border=0></td>\n";
		else
			echo "</tr>\n";
	}

	if ($i % 2 == 1) {
		echo "<td width=67 align='center'><br></td>\n";
		echo "</tr>\n";
	}
	
	//echo "</table>\n";
}

// マーケター名表示
function my_disp_marketer_name() {
	$sql = "SELECT mr_name1 FROM t_marketer WHERE mr_marketer_id={$_SESSION['ss_marketer_id']}";
	echo htmlspecialchars(db_fetch1($sql));
}



?>
