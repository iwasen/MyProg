<?
/******************************************************
' System :�������ʪ�Υ��󥱡��ȥ�˥�����̳���ѥڡ���
' Content:�����᡼��ƥ�ץ졼�ȹ�������
'******************************************************/

$top = "../../..";
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");

set_global('etc', '����¾�����åޥ������ƥʥ�', '�����᡼��ƥ�ץ졼��', BACK_TOP);

$sql = "UPDATE m_mail_template SET" .
			" mt_guide=" . sql_char($guide) .
			",mt_subject=" . sql_char($subject) .
			",mt_from=" . sql_char($from) .
			",mt_cc=" . sql_char($cc) .
			",mt_bcc=" . sql_char($bcc) .
			",mt_reply_to=" . sql_char($reply_to) .
			",mt_body=" . sql_char($body) .
			" WHERE mt_template_id='$template_id'";
db_exec($sql);
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
<p class="msg">�����᡼��ƥ�ץ졼�Ȥ򹹿����ޤ�����</p>
<p><input type="button" id="ok" value="����롡" onclick="location.href='list.php'"></p>
</div>

<? page_footer() ?>
</body>
</html>
