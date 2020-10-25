<?
/******************************************************
' System :�������ơ�net�ޡ�������ͭ���ͣ��ڡ���
' Content:���̥إå����եå�
'******************************************************/

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

	// �����������ID����
	$sql = "SELECT mr_agent_id"
			. " FROM t_marketer"
			. " WHERE mr_marketer_id=$owner_id";
	$result = db_exec($sql);
	if (pg_numrows($result)) {
		$fetch = pg_fetch_object($result, 0);
		$my_agent_id = $fetch->mr_agent_id;
	}
	
	// ��Ͽ������
	$sql = "SELECT mr_regist_date FROM t_marketer WHERE mr_marketer_id={$_SESSION['ss_marketer_id']}";
	$result = db_exec($sql);
	if (pg_numrows($result)) {
		$fetch = pg_fetch_row($result, 0);
		$start_y = get_datepart('Y', $fetch[0]);
		$start_m = get_datepart('M', $fetch[0]);
	}
	$end_y = date('Y');
	$end_m = (int)date('m');
	$sdate = sprintf("%4d ǯ %2d �� �� %4d ǯ %2d ��", $start_y,$start_m, $end_y,$end_m);
	
	// �����������̾����
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
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP" />
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
    window.mm_menu_1105102848_0 = new Menu("root",150,16,"�ͣ� �Х����å�, Osaka, �ҥ饮�γѥ� Pro W3",10,"#0000FF","#00CCFF","#FFFFFF","#FFFFFF","left","middle",3,0,1000,-5,7,true,true,true,0,true,true);
  mm_menu_1105102848_0.addMenuItem("�ͥåȥꥵ�������ܼ�","location='mkk_kh_basic01.php'");
  mm_menu_1105102848_0.addMenuItem("�ֿ�Ψ�����֤�����","location='mkk_kh_basic02.php'");
  mm_menu_1105102848_0.addMenuItem("Ĵ��ɼ�κ���������ĤΥ��ƥå�","location='mkk_kh_basic03.php'");
   mm_menu_1105102848_0.hideOnMouseOut=true;
   mm_menu_1105102848_0.bgColor='#FFFFFF';
   mm_menu_1105102848_0.menuBorder=1;
   mm_menu_1105102848_0.menuLiteBgColor='#FFFFFF';
   mm_menu_1105102848_0.menuBorderBgColor='#FF5C5C';
window.mm_menu_1105103255_0 = new Menu("root",205,16,"�ͣ� �Х����å�, Osaka, �ҥ饮�γѥ� Pro W3",10,"#0000FF","#00CCFF","#FFFFFF","#FFFFFF","left","middle",3,0,1000,-5,7,true,true,true,0,true,true);
  mm_menu_1105103255_0.addMenuItem("���֥������Ȥϴʷ�����Ƥ����󤹤�","location='mkk_kh_emc01.php'");
  mm_menu_1105103255_0.addMenuItem("���Ѥ�ɬ�פ���ʬ����","location='mkk_kh_emc02.php'");
  mm_menu_1105103255_0.addMenuItem("���󥿥ӥ奢���ϲ�Ÿ���ȽĤο��٤򷫤��֤�","location='mkk_kh_emc03.php'");
  mm_menu_1105103255_0.addMenuItem("���ߥ�˥��������᡼��ν���","location='mkk_kh_emc04.php'");
   mm_menu_1105103255_0.hideOnMouseOut=true;
   mm_menu_1105103255_0.bgColor='#FFFFFF';
   mm_menu_1105103255_0.menuBorder=1;
   mm_menu_1105103255_0.menuLiteBgColor='#FFFFFF';
   mm_menu_1105103255_0.menuBorderBgColor='#FFA30A';

  window.mm_menu_1105103826_0 = new Menu("root",200,16,"�ͣ� �Х����å�, Osaka, �ҥ饮�γѥ� Pro W3",10,"#0000FF","#00CCFF","#FFFFFF","#FFFFFF","left","middle",3,0,1000,-5,7,true,true,true,0,true,true);
  mm_menu_1105103826_0.addMenuItem("����۵���������۵���","location='mkk_kh_nrp01.php'");
  mm_menu_1105103826_0.addMenuItem("��ʸ���դ���","location='mkk_kh_nrp02.php'");
  mm_menu_1105103826_0.addMenuItem("��������ʤ�","location='mkk_kh_nrp03.php'");
  mm_menu_1105103826_0.addMenuItem("���ʳ�ɾ���ν��פ�ý�ʿ�Ѥ�ȤäƹԤ�","location='mkk_kh_nrp04.php'");
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
<script src="DWConfiguration/ActiveContent/IncludeFiles/AC_RunActiveContent.js" type="text/javascript"></script>
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
<td width="410"><a href="mkk_index.php"><img src="images/common_my/logo.jpg" alt="�������ơ�net" width="228" height="64"><img src="images/common_my/img_head2.gif" width="182" height="64"></a></td>
<td width="378" align="right" valign="bottom"> 
<table width="100%" border="0" cellpadding="0" cellspacing="8" class="header_text">
<tr> 
<td> 
<table width="100%" border="0" cellspacing="0" cellpadding="0">
<tr> 
<td><img src="images/common_my/img_01.gif" alt="���ߤΥ�˥�����"></td>
<td class="header_text"> <?=number_format(count_monitor())?>�� </td>
</tr>
<tr> 
<td><img src="images/common_my/img_02.gif" alt="My��page���Ѵ���" width="121" height="18"></td>
<td class="header_text"> <?=$sdate?> </td>
</tr>
</table>
</td>
<td bgcolor="#dbdbdb"><img src="images/common/spacer.gif" width="1" height="28" /></td>
<td valign="bottom"> 
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
<td height="100" background="<? echo getImage() ?>">
<table width="350" height="60" border="0" cellpadding="0" cellspacing="0">
<tr> 
<td width="14" rowspan="2"><img src="images/common/spacer.gif" width="14" height="10" /></td>
<td width="50" rowspan="2"><a href="mkk_contact.php"><img src="images/agent<?=$my_agent_id?>.jpg" width="50" height="50" border="0"></a></td>
<td width="5" rowspan="2"><img src="images/common/spacer.gif" width="5" height="10" /></td>
<td height="36" valign="bottom" class="header_text"> <? my_disp_marketer_name() ?>���󡢤���ˤ��ϡ�����������Ȥ� <?=$agent_name?> �Ǥ���</td>
</tr>
<tr> 
<td height="24"><img src="images/common_my/img_agent.gif" width="113" height="24"></td>
</tr>
</table>
</td>
</tr>
</table>
</td>
</tr>
</table>
      
</td>
    <td width="1" bgcolor="#dbdbdb"><img src="images/common/spacer.gif" width="1" height="10"></td>
  </tr>
</table>
<?
}


/*--------------------------------------------------------------------
 *	�ޥ��ڡ��������ɥ�˥塼
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
define("M_CYCLE",  12);
define("M_CASE",  13);
define("M_INVEST",  14);
define("M_OUTPUT",  15);

function marketer_menu($page=M_NULL){
	$menu = array();

	$menu[M_CYCLE] = "<a href=\"mkk_index.php\" onMouseOut=\"MM_swapImgRestore()\" onMouseOver=\"MM_swapImage('Image99','','images/common_my/bt_hint_ov.gif',1)\"><img src=\"images/common_my/bt_hint.gif\" alt=\"�֤������ƥҥ�ȡפ���ͤ���\" name=\"Image99\" width=\"197\" height=\"30\" border=\"0\"></a>";
	
	$menu[M_CASE] = "<a href=\"mkk_case.php\" onMouseOut=\"MM_swapImgRestore()\" onMouseOver=\"MM_swapImage('Image98','','images/common_my/bt_case_ov.gif',1)\"><img src=\"images/common_my/bt_case.gif\" alt=\"�����������ǥ�����ͤ���\" name=\"Image98\" width=\"197\" height=\"30\" border=\"0\"></a>";	
	
	$menu[M_INVEST] = "<a href=\"mkk_invest.php\" onMouseOut=\"MM_swapImgRestore()\" onMouseOver=\"MM_swapImage('Image97','','images/common_my/bt_invest_ov.gif',1)\"><img src=\"images/common_my/bt_invest.gif\" alt=\"Ĵ�����ܤ���ͤ���\" name=\"Image97\" width=\"197\" height=\"30\" border=\"0\"></a>";
	
	$menu[M_OUTPUT] = "<a href=\"mkk_output.php\" onMouseOut=\"MM_swapImgRestore()\" onMouseOver=\"MM_swapImage('Image96','','images/common_my/bt_output_ov.gif',1)\"><img src=\"images/common_my/bt_output.gif\" alt=\"�����ȥץåȤ���ͤ���\" name=\"Image96\" width=\"197\" height=\"30\" border=\"0\"></a>";		
	
	$menu[M_OFFICE] = "<a href=\"mkk_kkstfrom.php\" onMouseOut=\"MM_swapImgRestore()\" onMouseOver=\"MM_swapImage('Image51','','images/common_my/bt_office_ov.gif',1)\"><img src=\"images/common_my/bt_office.gif\" alt=\"��̳�ɤ��\" name=\"Image51\" width=\"197\" height=\"30\" border=\"0\"></a>";
	$menu[M_SITEMAP] = "<a href=\"mkk_map.php\" onMouseOut=\"MM_swapImgRestore()\" onMouseOver=\"MM_swapImage('Image52','','images/common_my/bt_sitemap_ov.gif',1)\"><img src=\"images/common_my/bt_sitemap.gif\" alt=\"sitemap\" name=\"Image52\" width=\"197\" height=\"30\" border=\"0\"></a>";
if (check_permission(PMR_MY_AGENT)) {
	$menu[M_CONTACT] = "<a href=\"mkk_contact.php\" onMouseOut=\"MM_swapImgRestore()\" onMouseOver=\"MM_swapImage('Image53','','images/common_my/bt_contact_ov.gif',1)\"><img src=\"images/common_my/bt_contact.gif\" alt=\"contact us\" name=\"Image53\" width=\"197\" height=\"32\" border=\"0\"></a>";
}


	
	switch($page) {
	case M_CYCLE:
		$menu[M_CYCLE] = "<a href=\"mkk_index.php\"><img src='images/common_my/bt_hint_ov.gif' alt='�������ƥҥ�Ȥ���ͤ���' width='197' height='32'></a>";
		break;
	case M_CASE:
		$menu[M_CASE] = "<a href=\"mkk_case.php\"><img src='images/common_my/bt_case_ov.gif' alt='�����������ǥ�����ͤ���' width='197' height='32'></a>";
		break;
	case M_INVEST:
		$menu[M_INVEST] = "<a href=\"mkk_invest.php\"><img src='images/common_my/bt_invest_ov.gif' alt='Ĵ���㤫��ͤ���' width='197' height='32'></a>";
		break;
	case M_OUTPUT:
		$menu[M_OUTPUT] = "<a href=\"mkk_output.php\"><img src='images/common_my/bt_output_ov.gif' alt='�����ȥץåȤ���ͤ���' width='197' height='32'></a>";
		break;
	case M_OFFICE:
		$menu[M_OFFICE] = "<a href=\"mkk_kkstfrom.php\"><img src='images/common_my/bt_office_ov.gif' alt='��̳�ɤ��' width='197' height='30'></a>";
		break;
	case M_SITEMAP:
		$menu[M_SITEMAP] = "<a href=\"mkk_map.php\"><img src='images/common_my/bt_sitemap_ov.gif' alt='sitemap' width='197' height='30'></a>";
		break;
	case M_CONTACT:
		$menu[M_CONTACT] = "<a href=\"mkk_contact.php\"><img src='images/common_my/bt_contact_ov.gif' alt='contact us' width='197' height='32'></a>";
		break;
	}
	
	echo("
<table width='100%'  border='0' cellspacing='0' cellpadding='0'>
<tr> 
<td bgcolor='#FFFFFF'><img src='images/common_my/img_04.gif' alt='�������ƥҥ��' width='150' height='16'></td>
</tr>
<tr> 
<td>".$menu[M_CYCLE]."</td>
</tr>
<tr> 
<td>".$menu[M_CASE]."</td>
</tr>
<tr> 
<td>".$menu[M_OUTPUT]."</td>
</tr>
<tr> 
<td>".$menu[M_INVEST]."</td>
</tr>
<tr> 
<td><img src='images/common_my/img_common.gif' alt='common' width='197' height='30'></td>
</tr>
<tr> 
<td>".$menu[M_OFFICE]."</td>
</tr>
<tr> 
<td>".$menu[M_SITEMAP]."</td>
</tr>
<tr> 
<td>".$menu[M_CONTACT]."</td>
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
<td align="right" class="footer_text"><a href="mkk_index.php">HOME</a>��<a href="mkk_kkstfrom.php">��̳�ɤ��</a>��<a href="mkk_map.php">SITE 
MAP</a>��<a href="mk/kkstfrom/mk_contact.php" target="_blank">CONTACT US</a><img src="images/common/spacer.gif" width="18" height="5"></td>
</tr>
<tr> 
<td width="170" bgcolor="<?=$color1 ?>"><img src="images/common/spacer.gif" width="170" height="5"></td>
<td width="1" bgcolor="<?=$color2 ?>" align="center"><img src="images/common/spacer.gif" width="1" height="1"></td>
<td align="right" class="footer_text"><img src="images/common/spacer.gif" width="1" height="5"></td>
</tr>
</table>
</td>
</tr>
<tr> 
<td colspan="2" bgcolor="#002b68"><img src="images/common/spacer.gif" width="10" height="10" /></td>
</tr>
<tr> 
<td colspan="2" valign="top"><img src="images/common/spacer.gif" width="10" height="5"></td>
</tr>
<tr> 
<td valign="top"><img src="images/common/spacer.gif" width="10" height="8">Copyright 2006 <a href="http://www.xxxxxxx.co.jp/" target="_blank">xx xxxxx Inc.</a> All rights reserved.�ܥ����Ȥϡ�������ҡ������������ˤ�걿�Ĥ���Ƥ���ޤ���<br><br>
<img src="images/common/spacer.gif" width="10" height="8"><span class="footer_text"><a href="http://www.xxxxxxx.co.jp/" target="_blank">�ޡ��������γ��ͤءʲ�ҳ��ס�</a>��<a href="http://www.xxxxxxx.co.jp/monitor/" target="_blank">����Ԥγ��ͤءʥ�˥�����Ͽ��</a>��<a href="http://www.xxxxxxx.co.jp/info/privacypolicy.html" target="_blank">�Ŀ;����ݸ�����</a>��<a href="mkk_privacy.php">�����֥����ȥץ饤�Х����ݥꥷ��</a></span></td>
<td align="right"><img src="images/common/A820926_01_75.gif" alt="�ץ饤�Х����ޡ���" width="75" height="75" /></td>
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



// �����������ɽ��
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
		echo "<a href='javascript:void(0)' onclick='open(\"$url{$fetch->ag_agent_id}\", \"_blank\", \"width=742,height=730,scrollbars=yes,resizable=yes,status=yes\")'><font size=3 class='honbun1'>{$fetch->ag_name1}�Ǥ�</font></a></td>\n";

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

// �ޡ�������̾ɽ��
function my_disp_marketer_name() {
	$sql = "SELECT mr_name1 FROM t_marketer WHERE mr_marketer_id={$_SESSION['ss_marketer_id']}";
	echo htmlspecialchars(db_fetch1($sql));
}



?>
