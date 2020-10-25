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

$access_day = sprintf("%04d%02d%02d",$info_year, $info_month, $info_day);

$sql = "SELECT * FROM T_ADMIN_MSG WHERE access_day='$access_day' AND msg_id='2'";
$result = db_exec($sql);
if (pg_numrows($result)) {
	$msg = "既に登録されています。ご確認ください。";
	$err_flg = "1";
} else {
	$sqmsg_id = update_squ_key ("T_ADMIN_MSG");
	if ($sqmsg_id == 1) {
		update_squ_key ("T_ADMIN_MSG");
	}
	if ($sqmsg_id != "-1") {
		$admin_msg = replace_single($admin_msg);
		$sql = "INSERT INTO T_ADMIN_MSG (sqmsg_id, msg_id, admin_msg, access_day, modify_dt) VALUES ($sqmsg_id, 2, '$admin_msg', '$access_day', 'now')";
		db_exec($sql);
		$msg = "登録しました。";
	} else {
		$msg = "処理中にエラーが発生したため、処理できませんでした。";
	}
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

<input type="button" value=" 戻る " onclick="location.href='index.php'">
</center>
</form>

<? footer_jmk(0); ?>

</body>
</html>
