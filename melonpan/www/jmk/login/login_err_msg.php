<?
include ("../inc/header_jmk.php");
include ("../inc/footer_jmk.php");

/******************************************************
' System :����Ѥ��̳���ѥڡ���
' Content:�����󥨥顼���̽���
'******************************************************/

$title_text = "����Ѥ��̳�� ���󥿡��ե�����";
$title_color = "#9fc741";

?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta HTTP-EQUIV="Pragma" Content="no-cache">
<title><?= $title_text ?></title>
<link rel="stylesheet" type="text/css" href="../haimail.css">
<SCRIPT LANGUAGE=javascript>
<!--
function OnLoad_body() {
  document.form1.button.focus();
}
//-->
</SCRIPT>
</head>
<body onLoad="OnLoad_body()">

<? header_jmk($title_text,$title_color,1); ?>

<center>
<form name="form1">
<p>������˼��Ԥ��ޤ�����<br><br>���ܥ���򥯥�å����Ƥ⤦���٣ɣĤȥѥ���ɤ����Ϥ��Ƥ���������</p>
<input type="button" name="button" value=" ��� " onclick="history.back()">
<br>
</form>
</center>

<? footer_jmk(1); ?>

</body>
</html>
