<?
include ("../inc/header_jmk.php");
include ("../inc/footer_jmk.php");
include ("../inc/common.php");
include ("../inc/database_inc.php");
include ("../inc/admin_inc.php");
include ("../inc/sub_func_inc.php");

/******************************************************
' System :めろんぱん事務局用ページ
' Content:広告バナー設定
'******************************************************/

$title_text = $TT_banner_set;
$title_color = $TC_OTHER;

//'=== 	日付書式変更 ===
$bn_start_dt = sprintf("%04d-%02d-%02d %02d:00:00", $bn_start_year, $bn_start_month, $bn_start_day, $bn_start_hour);
$bn_end_dt = sprintf("%04d-%02d-%02d %02d:00:00", $bn_end_year, $bn_end_month, $bn_end_day, $bn_end_hour);

//'=== Main ===
db_begin_trans();
if (!is_uploaded_file($banner_pict)) {
	$sql = "UPDATE T_BANNER SET"
		. " BN_kind = '$bn_kind'"
		. ", BN_title = '$bn_title'"
		. ", BN_send_url = '$bn_send_url'"
		. ", BN_start_dt = to_timestamp('$bn_start_dt', 'YYYY-MM-DD HH24:MI:SS')"
		. ", BN_end_dt = to_timestamp('$bn_end_dt', 'YYYY-MM-DD HH24:MI:SS')"
		. " WHERE BN_id=$bn_id";
} else {

  $oid = pg_locreate ($gConn);
  $lobj = pg_loopen ($gConn, $oid, "w");
	$fd = fopen($banner_pict, "r");
	$img = fread($fd, filesize($banner_pict));
  pg_lowrite ($lobj, $img);
  pg_loclose ($lobj);
	
	$sql = "UPDATE T_BANNER SET"
		. " BN_kind = '$bn_kind'"
		. ", BN_title = '$bn_title'"
		. ", BN_send_url = '$bn_send_url'"
		. ", BN_picture = $oid"
		. ", BN_start_dt = to_timestamp('$bn_start_dt', 'YYYY-MM-DD HH24:MI:SS')"
		. ", BN_end_dt = to_timestamp('$bn_end_dt', 'YYYY-MM-DD HH24:MI:SS')"
		. " WHERE BN_id=$bn_id";
}
if (db_exec($sql)) {
	db_commit_trans();
	$msg = "更新しました。";
} else {
	db_rollback();
	$msg = "処理中にエラーが発生したため、処理できませんでした。。";
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
<center>
<form method="post" name="form1">

更新しました。<br><br>

<input type="button" value=" 戻る " onclick="location.href='index.php'">
</form>
</center>
<? footer_jmk(0); ?>


</body>
</html>
