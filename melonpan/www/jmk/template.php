<?
include ("../inc/header_jmk.php");
include ("../inc/footer_jmk.php");
include ("../inc/database.php");
include ("../inc/admin_inc.php");
include ("../inc/com_func_inc.php");

/******************************************************
' System :めろんぱん事務局用ページ
' Content:
'******************************************************/

$title_text = "";
$title_color = $TC_MASTER;

?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta HTTP-EQUIV="Pragma" Content="no-cache">
<title><? echo ("$title_text") ?></title>
<link rel="stylesheet" type="text/css" href="../css/melonpan.css">
</head>
<body>

<? header_jmk($title_text,$title_color,0); ?>

<form method="post" name="form1">


<form>

<? footer_jmk(0); ?>

</body>
</html>
