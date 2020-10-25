<?
/******************************************************
' System :Eco-footprint �����ڡ���
' Content:��λ��¥�᡼���ۿ�����
'******************************************************/

$top = '../..';
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");
include("$inc/mail.php");

function test_mail($from) {
	global $subject, $body;

	// ���������ɥ쥹����
	$fetch = get_system_info('sy_mail_from,sy_mail_name');
	$from = "$fetch->sy_mail_name <$fetch->sy_mail_from>";

	// �����襢�ɥ쥹����
	$sql = "SELECT ad_mail_addr FROM m_admin WHERE ad_admin_id={$_COOKIE['admin_login_id']}";
	$to = db_fetch1($sql);

	send_mail($subject, $to, $from, $body);
}

// �ᥤ�����
set_global('member', '����������', '��λ��¥�᡼���ۿ�����', BACK_TOP);

switch ($next_action) {
case 'new':
	$sql = "INSERT INTO t_close_mail (cm_subject,cm_body,cm_send_date) VALUES (" .
				sql_char($subject) . "," .
				sql_char($body) . "," .
				sql_datetime($send_date_check, $send_date_y, $send_date_m, $send_date_d, $send_date_h) . ")";
	db_exec($sql);
	$msg = '��λ��¥�᡼�����Ͽ���ޤ�����';
	$back = "location.href='list.php'";
	break;
case 'update':
	$sql = "UPDATE t_close_mail SET" .
				" cm_subject=" . sql_char($subject) .
				",cm_body=" . sql_char($body) .
				",cm_send_date=" . sql_datetime($send_date_check, $send_date_y, $send_date_m, $send_date_d, $send_date_h) .
				" WHERE cm_seq_no=$cm_id";
	db_exec($sql);
	$msg = '��λ��¥�᡼��򹹿����ޤ�����';
	$back = "location.href='list.php'";
	break;
case 'delete':
	$sql = "DELETE FROM t_close_mail WHERE cm_seq_no=$cm_id";
	db_exec($sql);
	$msg = '��λ��¥�᡼��������ޤ�����';
	$back = "location.href='list.php'";
	break;
case 'test':
	test_mail($from);
	$msg = '�ƥ��ȥ᡼����������ޤ�����';
	$back = "history.back()";
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
<p><input type="button" id="ok" value="����롡" onclick="<?=$back?>"></p>
</div>

<? page_footer() ?>
</body>
</html>
