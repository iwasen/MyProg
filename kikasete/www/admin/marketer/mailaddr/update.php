<?
/******************************************************
' System :�������ơ�net��̳���ѥڡ���
' Content:�᡼�륢�ɥ쥹ȯ��
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

// ���Υ᡼������
function mail_send($mail_addr, $name, $free_email, $password) {
	get_mail_template('eml_mk', $subject, $from, $cc, $bcc, $repty_to, $body);
	$body = str_replace('%MARKETER_NAME%', $name, $body);
	$body = str_replace('%MAIL_ADDR%', $free_email, $body);
	$body = str_replace('%PASSWORD%', $password, $body);
	send_mail($subject, $mail_addr, $from, $body, $cc, $bcc, $reply_to);
}

// �᡼�륵���Ф˥᡼�륢������Ⱥ���
function make_account($free_email, $password) {
	$fetch = get_system_info('sy_pop_server');
	$output = exec("rsh -l vpopmail $fetch->sy_pop_server bin/vadduser $free_email $password", $arg, $ret);
	if ($ret <> 0)
		$output = '�᡼�륢������Ⱥ������ޥ�ɤ��¹ԤǤ��ޤ���Ǥ�����';

	return $output;
}

// �ᥤ�����
set_global('marketer', '�ޡ�����������', '�᡼�륢�ɥ쥹ȯ��', BACK_TOP);

// ��˾�᡼��ɥᥤ�����
$fetch = get_system_info('sy_mail_domain');
$mail_domain = $fetch->sy_mail_domain;

// ȯ�ԥ᡼�륢�ɥ쥹����
if ($account == '@')
	$free_email = $account2;
$free_email = (($account == '@') ? $account2 : $account) . '@' . $mail_domain;

// ���ѺѤߥ����å�
$sql = "SELECT mr_marketer_id FROM t_marketer WHERE mr_free_email=" . sql_char($free_email);
$result = db_exec($sql);
if (pg_numrows($result) == 0) {
	$sql = "SELECT mr_mail_addr,mr_name1,mr_password FROM t_marketer WHERE mr_marketer_id=$marketer_id AND mr_free_email IS NULL";
	$result = db_exec($sql);
	if (pg_numrows($result)) {
		$fetch = pg_fetch_object($result, 0);

		$output = make_account($free_email, $fetch->mr_password);
		if ($output == '') {
			$sql = "UPDATE t_marketer SET mr_free_email=" . sql_char($free_email) . " WHERE mr_marketer_id=$marketer_id";
			db_exec($sql);

			mail_send($fetch->mr_mail_addr, $fetch->mr_name1, $free_email, $fetch->mr_password);

			$msg = '�᡼�륢�ɥ쥹��ȯ�Ԥ��ޤ�����';
			$back = "location.href='list.php'";
		} else {
			$msg = "�᡼�륢������Ⱥ����˼��Ԥ��ޤ�����[$output]";
			$back = 'history.back()';
		}
	} else
		redirect('list.php');
} else {
	$msg = '���ꤷ���᡼�륢�ɥ쥹�ϻ��ѺѤߤǤ���';
	$back = 'history.back()';
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
