<?
/******************************************************
' System :�������ơ�net������������ѥڡ���
' Content:�����󥨥顼���̽���
'******************************************************/

$top = '..';
$inc = "$top/inc";
include("$inc/header.php");
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");

set_global('kikasete', '', '�������ơ�net��̳�ɥ�����', BACK_NONE);
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<title><?=$g_title?></title>
<link rel="stylesheet" type="text/css" href="<?=$top?>/css/main.css">
</head>
<body onLoad="document.all.ok.focus()">

<? page_header() ?>

<div align="center">
<p class="msg">������˼��Ԥ��ޤ�����<br><br>���ܥ���򥯥�å����Ƥ⤦���٣ɣĤȥѥ���ɤ����Ϥ��Ƥ���������</p>
<p><input type="button" id="ok" value="����롡" onclick="history.back()"></p>
</div>

<? page_footer() ?>

</body>
</html>
