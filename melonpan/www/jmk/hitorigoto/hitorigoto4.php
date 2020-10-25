<?
include ("../inc/header_jmk.php");
include ("../inc/footer_jmk.php");
include ("../inc/common.php");
include ("../inc/database_inc.php");
include ("../inc/admin_inc.php");
include ("../inc/sub_func_inc.php");

/******************************************************
' System :めろんぱん事務局用ページ
' Content:「今日のひとりごと」入力
'******************************************************/

$title_text = $TT_hitorigoto;
$title_color = $TC_MASTER;

$sql = "UPDATE T_ADMIN_MSG SET admin_msg='$admin_msg'"
		. ", access_day='$access_day'"
		. ", modify_dt='now'"
		. " WHERE sqmsg_id=$sqmsg_id";
db_exec($sql);
$msg = "更新しました。";
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

<?= $msg ?><br><br>

<input type="button" value=" 戻る " onclick="location.href='index.php'">
</center>
</form>

<? footer_jmk(0); ?>

</body>
</html>
