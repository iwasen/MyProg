<?
include ("../inc/header_jmk.php");
include ("../inc/footer_jmk.php");
include ("../inc/common.php");
include ("../inc/database_inc.php");
include ("../inc/admin_inc.php");
include ("../inc/sub_func_inc.php");

/******************************************************
' System :めろんぱん事務局用ページ
' Content:めろんぱんレター配信
'******************************************************/

$title_text = $TT_letter_melonpan;
$title_color = $TC_MASTER;

$sql = "SELECT * FROM T_PUB_RESERVE WHERE index_id=$index_id";
$result = db_exec($sql);
if (!pg_numrows($result)) {
	$msg = 'データが既に更新されているようです。ご確認ください。';
} else {

	$resrv_month = sprintf("%02d", $reserve_month);
	$resrv_day = sprintf("%02d", $reserve_day);
	$resrv_hour = sprintf("%04d-%02d-%02d %02d:%02d:00", $reserve_year, $reserve_month, $reserve_day, $reserve_hour, $reserve_minutes);

	$sql = "UPDATE T_BODY SET mag_titl='$subject', mag_body='$body', modefy_dt='now' WHERE index_id=$index_id";
	db_exec($sql);

	$sql = "UPDATE T_PUB_RESERVE"
		. " SET resrv_month='$resrv_month', resrv_day='$resrv_day'"
		. ", resrv_hour=to_timestamp('$resrv_hour','YYYY-MM-DD HH24:MI:SS')"
		. ", modefy_dt='now'"
		. " WHERE index_id=$index_id";
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

<form method="post" name="form1">
<center>

<?= $msg ?><br><br>

<input type="button" value=" 戻る " onclick="location.href='index.php'">
</center>
</form>

<? footer_jmk(0); ?>

</body>
</html>
