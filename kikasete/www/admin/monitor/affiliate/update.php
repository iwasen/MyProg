<?
/******************************************************
' System :�������ơ�net��̳���ѥڡ���
' Content:���ե��ꥨ���ȹ�������
'******************************************************/

$top = '../..';
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");

// �ᥤ�����
set_global('monitor', '��˥�������', '���ե��ꥨ���ȴ���', BACK_TOP);

$affiliate_mgr_id = $_REQUEST['affiliate_mgr_id'];

$rec['afm_affiliate_name'] = sql_char($affiliate_name);
$rec['afm_affiliate_type'] = sql_number($affiliate_type);
$rec['afm_condition'] = sql_char($condition);
$rec['afm_appl_start_date'] = sql_date2($appl_start_y, $appl_start_m, $appl_start_d);
$rec['afm_appl_end_date'] = sql_date2($appl_end_y, $appl_end_m, $appl_end_d);
$rec['afm_affiliate_start_date'] = sql_date2($affiliate_start_y, $affiliate_start_m, $affiliate_start_d);
$rec['afm_affiliate_end_date'] = sql_date2($affiliate_end_y, $affiliate_end_m, $affiliate_end_d);

switch ($next_action) {
case 'new':
	db_insert('t_affiliate_mgr', $rec);
	$action = '��Ͽ';
	break;
case 'update':
	db_update('t_affiliate_mgr', $rec, "afm_affiliate_mgr_id=$affiliate_mgr_id");
	$action = '����';
	break;
case 'delete':
	db_delete('t_affiliate_mgr', "afm_affiliate_mgr_id=$affiliate_mgr_id");
	$action = '���';
	break;
}
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<title><?=$g_title?></title>
<link rel="stylesheet" type="text/css" href="<?=$top?>/css/main.css">
</head>
<body onLoad="document.all.ok.focus()">
<? page_header() ?>

<div align="center">
<p class="msg">���ե��ꥨ���Ȥ�<?=$action?>���ޤ�����</p>
<p><input type="button" id="ok" value="����롡" onclick="location.href='list.php'"></p>
</div>

<? page_footer() ?>
</body>
</html>
