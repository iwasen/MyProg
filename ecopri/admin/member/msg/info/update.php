<?
/******************************************************
' System :Eco-footprint �����ڡ���
' Content:��̳�ɤ���Τ��Τ餻����
'******************************************************/

$top = '../../..';
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");

set_global('member', '����ȥåץڡ�������', '��̳�ɤ���Τ��Τ餻', BACK_TOP);

switch ($next_action) {
case 'new':
	$sql = "INSERT INTO t_admin_msg (an_title,an_text,an_start_date,an_end_date) VALUES (" .
					sql_char($title) . "," .
					sql_char($msg) . "," .
					sql_date("$start_y/$start_m/$start_d") . "," .
					sql_date("$end_y/$end_m/$end_d") . ")";
	db_exec($sql);
	$msg = '��̳�ɤ���Τ��Τ餻����Ͽ���ޤ�����';
	$ret = 'location.href=\'list.php\'';
	break;
case 'update':
	$sql = "UPDATE t_admin_msg SET" .
				" an_title=" . sql_char($title) .
				",an_text=" . sql_char($msg) .
				",an_start_date=" . sql_date("$start_y/$start_m/$start_d") .
				",an_end_date=" . sql_date("$end_y/$end_m/$end_d") .
				" WHERE an_seq_no=$an_no";
	db_exec($sql);
	$msg = '��̳�ɤ���Τ��Τ餻����򹹿����ޤ�����';
	$ret = 'location.href=\'list.php\'';
	break;
case 'delete':
	$sql = "DELETE FROM t_admin_msg WHERE an_seq_no=$an_no";
	db_exec($sql);
	$msg = '��̳�ɤ���Τ��Τ餻�������ޤ�����';
	$ret = 'location.href=\'list.php\'';
	break;
default:
	redirect('list.php');
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
<p class="msg"><?=$msg?></p>
<p><input type="button" id="ok" value="����롡" onclick="<?=$ret?>"></p>
</div>

<? page_footer() ?>
</body>
</html>
