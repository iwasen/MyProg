<?
/******************************************************
' System :�֤���������� �������������ӥ��׻�̳���ѥڡ���
' Content:�᡼��ƥ�ץ졼�ȹ�������
'******************************************************/

$top = "../..";
$inc = "$top/../inc";
include("$inc/login_check.php");
include("$inc/common.php");
include("$inc/center.php");
include("$inc/database.php");

$rec['mtm_subject'] = sql_char($_POST['subject']);
$rec['mtm_from'] = sql_char($_POST['from']);
$rec['mtm_cc'] = sql_char($_POST['cc']);
$rec['mtm_bcc'] = sql_char($_POST['bcc']);
$rec['mtm_reply_to'] = sql_char($_POST['reply_to']);
$rec['mtm_body'] = sql_char($_POST['body']);
$rec['mtm_footer'] = sql_char($_POST['footer']);
db_update('t_mail_template', $rec, "mtm_template_id='{$_POST['template_id']}'");
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<link rel="stylesheet" type="text/css" href="<?=$top?>/css/center.css">
</head>
<body onLoad="document.all.ok.focus()">

<? center_header('�ޥ������ƥʥ󥹡å᡼��ƥ�ץ졼�ȡù���') ?>

<div align="center">
<p class="msg">�᡼��ƥ�ץ졼�Ȥ򹹿����ޤ�����</p>
<p><input type="button" id="ok" value="����롡" onclick="location.href='../menu.php'"></p>
</div>

<? center_footer() ?>

</body>
</html>
