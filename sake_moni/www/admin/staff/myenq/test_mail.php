<?
/******************************************************
' System :�������ʪ�Υ��󥱡��ȥ�˥�����̳���ѥڡ���
' Content:�ͣ����󥱡��ȥƥ��ȥ᡼������
'******************************************************/

$top = '../..';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");
include("$inc/enquete.php");
include("$inc/search.php");
include("$inc/my_enquete.php");
include("$inc/enq_mail.php");
include("$inc/mail.php");
include("$inc/mye_temp.php");
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");

set_global('staff', '�����åմ����ãͣ����󥱡��ȴ���', '�ƥ��ȥ᡼������', BACK_TOP);

// ���å�������
$myenq = new my_enquete_class;
$myenq->read_db($staff_id, $my_enq_no);
$enquete = &$myenq->enquete;

// �᡼�륿���ȥ뤬���ꤵ��Ƥ��ʤ����ϥ��󥱡��ȥ����ȥ��ɽ��
$mail_title = ($myenq->mail_title != '') ? $myenq->mail_title : $enquete->title;

// �᡼��ʬ�����ꤵ��Ƥ��ʤ����ϥƥ�ץ졼�Ȥ�ɽ��
if ($myenq->mail_contents == '')
	get_enq_body($myenq, $header, $body, $footer);
else {
	$header = $myenq->mail_header;
	$body = $myenq->mail_contents;
	$footer = $myenq->mail_footer;
}

// �᡼�륢�ɥ쥹�ȥ�˥���̾�򺹹�
$sql = "SELECT st_mail_addr,st_name1 FROM t_staff WHERE st_staff_id=$staff_id";
$result = db_exec($sql);
if (pg_num_rows($result)) {
	$fetch = pg_fetch_object($result, 0);
	$header = str_replace('%MAIL_ADDR%', $fetch->st_mail_addr, $header);
	$body = str_replace('%MONITOR_NAME%', $fetch->st_name1, $body);
}

// �����᡼���Խ�
$mail_text = make_enq_mail($enquete, $header, $body, $footer);

// �ݥ���Ⱥ���
$mail_text = str_replace('%POINT%', number_format($enquete->point), $mail_text);

// ���������ɥ쥹����
if ($enquete->enq_type == 1)
	$fetch = get_system_info('sy_enq_domain,sy_enq_account,sy_enq_mail AS from_name');
else
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
<input type="button" value="����롡" onclick="history.back()">
</div>

<? page_footer() ?>
</body>
</html>
