<?
/******************************************************
' System :�������ơ�net��̳���ѥڡ���
' Content:��Ⱦ��󹹿�����
'******************************************************/

$top = "../../..";
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");

set_global('etc', '����¾�����åޥ������ƥʥ�', '��ȥޥ���', BACK_TOP);

switch ($next_action) {
case 'new':
	$sql = "SELECT cl_client_id FROM m_client WHERE cl_name='$cl_name'";
	$result = db_exec($sql);
	if (pg_numrows($result) == 0) {
		$sql = "INSERT INTO m_client (cl_name,cl_name_kana,cl_zip_code,cl_address1,cl_address2,cl_address3,cl_address4,cl_gyoushu_cd) VALUES (" .
						sql_char($cl_name) . "," .
						sql_char($cl_name_kana) . "," .
						sql_char($cl_zip1. $cl_zip2) . "," .
						sql_number($cl_address1) . "," .
						sql_char($cl_address2) . "," .
						sql_char($cl_address3) . "," .
						sql_char($cl_address4) . "," .
						sql_number($cl_gyoushu_cd) . ")";
		db_exec($sql);
		$msg = '��Ȥ���Ͽ���ޤ�����';
		$ret = 'location.href=\'list.php\'';
	} else {
		$msg = '���Ϥ������̾�ϴ�����Ͽ����Ƥ��ޤ���';
		$ret = 'history.back()';
	}
	break;
case 'update':
	$sql = "SELECT cl_client_id FROM m_client WHERE cl_name='$cl_name' AND cl_client_id<>$cl_id";
	$result = db_exec($sql);
	if (pg_numrows($result) == 0) {
		$sql = "UPDATE m_client SET" .
					" cl_name=" . sql_char($cl_name) .
					",cl_name_kana=" . sql_char($cl_name_kana) .
					",cl_zip_code=" . sql_char($cl_zip1. $cl_zip2) .
					",cl_address1=" . sql_char($cl_address1) .
					",cl_address2=" . sql_char($cl_address2) .
					",cl_address3=" . sql_date($cl_address3) .
					",cl_address4=" . sql_char($cl_address4) .
					",cl_gyoushu_cd=" . sql_number($cl_gyoushu_cd) .
					" WHERE cl_client_id=$cl_id";
		db_exec($sql);

		$msg = '��Ⱦ���򹹿����ޤ�����';
		$ret = 'location.href=\'list.php\'';
	} else {
		$msg = '���Ϥ������̾�ϴ�����Ͽ����Ƥ��ޤ���';
		$ret = 'history.back()';
	}
	break;
case 'delete':
	$sql = "DELETE FROM m_client WHERE cl_client_id=$cl_id";
	db_exec($sql);
	$msg = '��Ȥ������ޤ�����';
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
