<?
/******************************************************
' System :�֤���������� �������������ӥ��׻�̳���ѥڡ���
' Content:�桼������ɽ��
'******************************************************/

$top = "..";
$inc = "$top/../inc";
include("$inc/login_check.php");
include("$inc/common.php");
include("$inc/center.php");

?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<title><?=SYSTEM_NAME?></title>
<link rel="stylesheet" type="text/css" href="<?=$top?>/css/center.css">
</head>
<body onLoad="document.all.ok.focus()">

<? center_header('�桼������ɽ���å��顼��å�����') ?>

<div align="center">
<p class="msg">���ꤷ���桼�������Ĥ���ޤ���</p>
<p><input type="button" id="ok" value="����롡" onclick="location.href='input_id.php'"></p>
</div>

<? center_footer() ?>

</body>
</html>
