<?
include ("../inc/header_jmk.php");
include ("../inc/footer_jmk.php");
include ("../inc/common.php");
include ("../inc/database_inc.php");
include ("../inc/admin_inc.php");
include ("../inc/sub_func_inc.php");

/******************************************************
' System :めろんぱん事務局用ページ
' Content:「事務局からのお知らせ」入力
'******************************************************/

$title_text = $TT_pub_msg;
$title_color = $TC_HAKKOUSHA;

$sql = "UPDATE T_ADMIN_MSG SET"
	. " ADMIN_MSG=" . sql_char($admin_msg)
	. ", MODIFY_DT = 'now'"
	.  " WHERE SQMSG_ID=1 AND MSG_ID=1";
db_exec($sql);

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

<form method="post" name="form1" action="hon_touroku3.php">

<center>
登録しました。<br><br>

<input type="button" value=" 戻る " onclick="location.href='pub_msg1.php'">
</center>
</form>

<? footer_jmk(0); ?>

</body>
</html>
