#!/usr/local/bin/php4
<?
/******************************************************
' System :flette�����åեڡ���
' Content:�����󥨥顼���̽���
'******************************************************/

$top = '..';
$inc = "$top/../inc";
include("$inc/common.php");

$inc = "$top/inc";
include("$inc/header.php");

?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<title><?=$g_title?></title>
<link rel="stylesheet" type="text/css" href="/css/staff.css">
</head>
<body onLoad="document.all.ok.focus()">

<? page_header('�����åե����󥨥顼', false, false) ?>

<div align="center">
<p class="msg">������˼��Ԥ��ޤ�����<br><br>���ܥ���򥯥�å����Ƥ⤦���٥᡼�륢�ɥ쥹�ȥѥ���ɤ����Ϥ��Ƥ���������</p>
<p><input type="button" id="ok" value="����롡" onclick="history.back()"></p>
</div>

<? page_footer() ?>

</body>
</html>
