<?
include ("../inc/header_jmk.php");
include ("../inc/footer_jmk.php");
include ("../inc/database.php");
include ("../inc/admin_inc.php");
include ("../inc/com_func_inc.php");

/******************************************************
' System :����Ѥ��̳���ѥڡ���
' Content:��������/�ݥ������Ϳ
'******************************************************/

$title_text = $TT_osusume_point;
$title_color = $TC_MASTER;

$msg = "�¹Ԥ��ޤ�����";
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta HTTP-EQUIV="Pragma" Content="no-cache">
<title><?= $title_text ?></title>
<link rel="stylesheet" type="text/css" href="../css/melonpan.css">
</head>
<body>

<? header_jmk($title_text,$title_color,0); ?>

<center>
<form method="post" name="form1">

<?= $msg ?><br><br>

<input type="button" value=" ��� " onclick="location.href='index.php'">

</form>
</center>

<? footer_jmk(0); ?>

</body>
</html>
