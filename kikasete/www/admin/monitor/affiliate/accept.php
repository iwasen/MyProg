<?
/******************************************************
' System :�������ơ�net��̳���ѥڡ���
' Content:���ե��ꥨ���Ⱦ�ǧ����
'******************************************************/

$top = '../..';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");

// �ᥤ�����
set_global('monitor', '��˥�������', '���ե��ꥨ���ȴ���', BACK_TOP);

db_begin_trans();

if (is_array($accept)) {
	foreach ($accept as $affiliate_id) {
		$rec['af_status'] = sql_number(1);
		db_update('t_affiliate', $rec, "af_affiliate_id=$affiliate_id");
	}
}

if (is_array($cancel)) {
	foreach ($cancel as $affiliate_id) {
		$rec['af_status'] = sql_number(2);
		db_update('t_affiliate', $rec, "af_affiliate_id=$affiliate_id");
	}
}

db_commit_trans();
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
<p class="msg">���ե��ꥨ���Ȥ�ǧ���ޤ�����</p>
<p><input type="button" id="ok" value="����롡" onclick="location.href='mem_list.php'"></p>
</div>

<? page_footer() ?>
</body>
</html>
