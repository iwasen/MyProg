<?
/******************************************************
' System :�֤���������� �������������ӥ��׻�̳���ѥڡ���
' Content:�᡼�륢�ɥ쥹�ѹ�����
'******************************************************/

$top = "..";
$inc = "$top/../inc";
include("$inc/login_check.php");
include("$inc/common.php");
include("$inc/center.php");
include("$inc/database.php");
include("$inc/decode.php");

// �᡼�륢�ɥ쥹����
$rec['stf_mail_addr'] = sql_char($_POST['mail_addr']);
db_update('t_staff', $rec, "stf_staff_id=$g_staff_id");

$_SESSION['s_mail_addr'] = $_POST['mail_addr'];

$msg = '�᡼�륢�ɥ쥹���ѹ����ޤ�����';
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<link rel="stylesheet" type="text/css" href="<?=$top?>/css/center.css">
</head>
<body onLoad="document.all.ok.focus()">

<? center_header(decode_privilege($g_privilege) . '�����ѹ��å᡼�륢�ɥ쥹�ѹ�') ?>

<div align="center">
<p class="msg"><?=$msg?></p>
<p><input type="button" id="ok" value="����롡" onclick="location.href='change.php'"></p>
</div>

<? center_footer() ?>

</body>
</html>
