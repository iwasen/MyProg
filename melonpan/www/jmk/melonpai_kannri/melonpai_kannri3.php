<?
include ("../inc/header_jmk.php");
include ("../inc/footer_jmk.php");
include ("../inc/common.php");
include ("../inc/database_inc.php");
include ("../inc/admin_inc.php");
include ("../inc/sub_func_inc.php");

/******************************************************
' System :めろんぱん事務局用ページ
' Content:メルマガナビ管理
'******************************************************/

$title_text = $TT_melonpai_kannri;
$title_color = $TC_DOKUSHA;

$sql = "SELECT * FROM M_MELONPAI WHERE melonpai_id=$melonpai_id";
$result = db_exec($sql);
if (!pg_numrows($result)) {
	$msg = "データが既に更新されているようです。ご確認ください。";
} else {
	$sql = "UPDATE M_MELONPAI SET melonpai_m_flg='$melonpai_m_flg', melonpai_point=$melonpai_point WHERE melonpai_id=$melonpai_id";
	db_exec($sql);
	$msg = "更新しました。";
}

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

<input type="button" value=" 戻る " onclick="location.href='melonpai_kannri1.php'">
</center>
</form>

<? footer_jmk(0); ?>

</body>
</html>
