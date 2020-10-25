<?
/******************************************************
' System :�������ơ�net��̳���ѥڡ���
' Content:�У�異�󥱡��ȥƥ��ȥ᡼������
'******************************************************/

$top = '../..';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");
include("$inc/enquete.php");
include("$inc/search.php");
include("$inc/pro_enquete.php");
include("$inc/enq_mail.php");
include("$inc/mail.php");
include("$inc/mye_temp.php");
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");

set_global('proenquete', '�У�異�󥱡��ȴ���', '�ƥ��ȥ᡼������', BACK_TOP);

// ���å�������
$pro_enq = new pro_enquete_class;
$pro_enq->read_db($marketer_id, $pro_enq_no);
$enquete = &$pro_enq->enquete;
$enquete2 = &$pro_enq->enquete2;

// �᡼�륿���ȥ뤬���ꤵ��Ƥ��ʤ����ϥ��󥱡��ȥ����ȥ��ɽ��
$mail_title = ($pro_enq->mail_title != '') ? $pro_enq->mail_title : $enquete->title;

// �᡼��ʬ�����ꤵ��Ƥ��ʤ����ϥƥ�ץ졼�Ȥ�ɽ��
if ($pro_enq->mail_contents == '')
	get_enq_body($pro_enq, $header, $body, $footer);
else {
	$header = $pro_enq->mail_header;
	$body = $pro_enq->mail_contents;
	$footer = $pro_enq->mail_footer;
}

// �᡼�륢�ɥ쥹�ȥ�˥���̾�򺹹�
$sql = "SELECT mr_mail_addr,mr_name1 FROM t_marketer WHERE mr_marketer_id=$marketer_id";
$result = db_exec($sql);
if (pg_numrows($result)) {
	$fetch = pg_fetch_object($result, 0);
	$header = str_replace('%MAIL_ADDR%', $fetch->mr_mail_addr, $header);
	$body = str_replace('%MONITOR_NAME%', $fetch->mr_name1, $body);
}

// �����᡼���Խ�
$mail_text = make_enq_mail($enquete, $header, $body, $footer);

// �ݥ���Ⱥ���
$mail_text = str_replace('%QR-POINT%', $pro_enq->finding_flag == DBTRUE ? number_format($enquete->point) : 0, $mail_text);
$mail_text = str_replace('%POINT%', number_format($enquete2->point), $mail_text);

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
