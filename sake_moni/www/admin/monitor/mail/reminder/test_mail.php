<?
/******************************************************
' System :�������ʪ�Υ��󥱡��ȥ�˥�����̳���ѥڡ���
' Content:��¥�᡼���ۿ��ƥ��ȥ᡼����������
'******************************************************/

$top = '../../..';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");
include("$inc/mail.php");
include("reminder.php");
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");

set_global('monitor', '������᡼���ۿ�', '��¥�᡼���ۿ�����', BACK_TOP);

// ���å�������
if (!isset($_SESSION['ss_enq_rm']))
	redirect('list.php');
$rm = &$_SESSION['ss_enq_rm'];
$rm->set_mail_data();

// �᡼�����
$mail_title = $rm->mail_title;
$header = $rm->mail_header;
$body = $rm->mail_body;
$footer = $rm->mail_footer;
$mail_text = trim($header) ."\n\n". trim($body) ."\n\n". trim($footer);

// ���������ɥ쥹����(Web���󥱡���)
$fetch = get_system_info('sy_enq_domain,sy_enq_account,sy_enq_web AS from_name');

if ($fetch->from_name != '')
	$from = "$fetch->from_name <$fetch->sy_enq_account@$fetch->sy_enq_domain>";
else
	$from = "$fetch->sy_enq_account@$fetch->sy_enq_domain";

// �᡼������
send_mail($mail_title, $_COOKIE['admin_mail_addr'], $from, $mail_text);
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<title><?=$g_title?></title>
<link rel="stylesheet" type="text/css" href="<?=$top?>/css/main.css">
</head>
<body>
<? page_header() ?>

<div align="center">
<p class="msg">�ƥ��ȥ᡼����������ޤ�����</p>
<br>
<input type="button" value="����롡" onclick="location.href='edit.php'">
</div>

<? page_footer() ?>
</body>
</html>
