<?
/******************************************************
' System :�������ʪ�Υ��󥱡��ȥ�˥�����̳���ѥڡ���
' Content:���饤����Ⱦ��󹹿�����
'******************************************************/

$top = "../..";
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");

set_global('client', '���饤����ȴ���', '���饤�������Ͽ', BACK_TOP);

switch ($next_action) {
case 'new':
	$sql = "SELECT cl_client_id FROM m_client WHERE cl_mail_addr='$mail_addr'";
	$result = db_exec($sql);
	if (pg_num_rows($result) == 0) {
		$sql = "INSERT INTO m_client (cl_name1,cl_name2,cl_mail_addr,cl_password) VALUES (" .
						sql_char($name1) . "," .
						sql_char($name2) . "," .
						sql_char($mail_addr) . "," .
						sql_char($password) . ")";
		db_exec($sql);
		$msg = '���饤����Ȥ���Ͽ���ޤ�����';
		$ret = 'location.href=\'list.php\'';
	} else {
		$msg = '���Ϥ����᡼�륢�ɥ쥹�ϴ�����Ͽ����Ƥ��ޤ���';
		$ret = 'history.back()';
	}
	break;
case 'update':
	$sql = "SELECT cl_client_id FROM m_client WHERE cl_mail_addr='$mail_addr' AND cl_client_id<>$client_id AND cl_status<>9";
	$result = db_exec($sql);
	if (pg_num_rows($result) == 0) {
		$sql = "UPDATE m_client SET" .
					" cl_status=" . sql_char($status) .
					",cl_name1=" . sql_char($name1) .
					",cl_name2=" . sql_char($name2) .
					",cl_mail_addr=" . sql_char($mail_addr) .
					",cl_password=" . sql_char($password) .
					",cl_update_date=current_timestamp" .
					" WHERE cl_client_id=$client_id";
		db_exec($sql);
		$msg = '���饤����Ⱦ���򹹿����ޤ�����';
		$ret = 'location.href=\'list.php\'';
	} else {
		$msg = '���Ϥ����᡼�륢�ɥ쥹�ϴ�����Ͽ����Ƥ��ޤ���';
		$ret = 'history.back()';
	}
	break;
case 'delete':
	$sql = "UPDATE m_client SET cl_status=9 WHERE cl_client_id=$client_id";
	db_exec($sql);
	$msg = '���饤����Ȥ������ޤ�����';
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
