<?
/******************************************************
' System :�֤���������� �������������ӥ��׻�̳���ѥڡ���
' Content:�����󥨥顼���̽���
'******************************************************/

$top = "..";
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/center.php");
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<title><? echo ("$title_text") ?></title>
<link rel="stylesheet" type="text/css" href="<?=$top?>/css/center.css">
</head>
<body onLoad="document.all.ok.focus()">

<? center_header('<div align="center">��̳�ɥ�����</div>') ?>

<div align="center">
<p class="msg">������˼��Ԥ��ޤ�����<br><br>���ܥ���򥯥�å����Ƥ⤦���٣ɣĤȥѥ���ɤ����Ϥ��Ƥ���������</p>
<p><input type="button" id="ok" value="����롡" onclick="history.back()"></p>
</div>

<? center_footer() ?>

</body>
</html>
