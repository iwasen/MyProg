<?
include ("../inc/header_jmk.php");
include ("../inc/footer_jmk.php");
include ("../inc/common.php");
include ("../inc/database_inc.php");
include ("../inc/admin_inc.php");
include ("../inc/sub_func_inc.php");

/******************************************************
' System :めろんぱん事務局用ページ
' Content:プレゼント応募管理
'******************************************************/

$title_text = $TT_present;
$title_color = $TC_OTHER;

//'=== 	日付書式変更 ===
$insert_start = sprintf("%04d", $insert_s_year)  . "-" . sprintf("%02d", $insert_s_month) . "-" . sprintf("%2d", $insert_s_day);
$insert_end = sprintf("%04d", $insert_e_year)  . "-" . sprintf("%02d", $insert_e_month) . "-" . sprintf("%2d", $insert_e_day);
$campaign_start = sprintf("%04d", $campaign_s_year)  . "-" . sprintf("%02d", $campaign_s_month) . "-" . sprintf("%2d", $campaign_s_day);
$campaign_end = sprintf("%04d", $campaign_e_year)  . "-" . sprintf("%02d", $campaign_e_month) . "-" . sprintf("%2d", $campaign_e_day);
$lottery_date = sprintf("%04d", $lottery_year)  . "-" . sprintf("%02d", $lottery_month) . "-" . sprintf("%2d", $lottery_day);

//'=== Main ===
db_begin_trans();
if (!is_uploaded_file($prize_pict)) {
	$sql = "UPDATE M_PRESENT_DATA SET"
		. " PD_present_type = '$present_type'"
		. ", PD_present_name = '$present_name'"
		. ", PD_link_text =" . sql_char($link_text)
		. ", PD_page_title =" . sql_char($page_title)
		. ", PD_explain =" . sql_char($explain)
		. ", PD_presenter =" . sql_char($presenter)
		. ", PD_link_url =" . sql_char($link_url)
		. ", PD_keyword_flag =" . sql_char($keyword_flag)
		. ", PD_keyword1 =" . sql_char($keyword1)
		. ", PD_keyword2 =" . sql_char($keyword2)
		. ", PD_keyword3 =" . sql_char($keyword3)
		. ", PD_insert_start = to_date('$insert_start', 'YYYY-MM-DD')"
		. ", PD_insert_end = to_date('$insert_end', 'YYYY-MM-DD')"
		. ", PD_campaign_start = to_date('$campaign_start', 'YYYY-MM-DD')"
		. ", PD_campaign_end = to_date('$campaign_end', 'YYYY-MM-DD')"
		. ", PD_lottery_date = to_date('$lottery_date', 'YYYY-MM-DD')"
		. ", PD_prize_1 =" . sql_number($prize_1)
		. ", PD_prize_2 =" . sql_number($prize_2)
		. ", PD_prize_3 =" . sql_number($prize_3)
		. ", PD_prize_name1 =" . sql_char($prize_name1)
		. ", PD_prize_name2 =" . sql_char($prize_name2)
		. ", PD_prize_name3 =" . sql_char($prize_name3)
		. ", PD_status = '$status'"
		. " WHERE PD_present_id='$present_id'";
} else {

  $oid = pg_locreate ($gConn);
  $lobj = pg_loopen ($gConn, $oid, "w");
	$fd = fopen($prize_pict, "r");
	$img = fread($fd, filesize($prize_pict));
  pg_lowrite ($lobj, $img);
  pg_loclose ($lobj);

	$sql = "UPDATE M_PRESENT_DATA SET"
		. " PD_present_type =" . sql_char($present_type)
		. ", PD_present_name =" . sql_char($present_name)
		. ", PD_explain =" . sql_char($explain)
		. ", PD_presenter =" . sql_char($presenter)
		. ", PD_link_url =" . sql_char($link_url)
		. ", PD_picture = $oid"
		. ", PD_keyword_flag =" . sql_char($keyword_flag)
		. ", PD_keyword1 =" . sql_char($keyword1)
		. ", PD_keyword2 =" . sql_char($keyword2)
		. ", PD_keyword3 =" . sql_char($keyword3)
		. ", PD_insert_start = to_date('$insert_start', 'YYYY-MM-DD')"
		. ", PD_insert_end = to_date('$insert_end', 'YYYY-MM-DD')"
		. ", PD_campaign_start = to_date('$campaign_start', 'YYYY-MM-DD')"
		. ", PD_campaign_end = to_date('$campaign_end', 'YYYY-MM-DD')"
		. ", PD_lottery_date = to_date('$lottery_date', 'YYYY-MM-DD')"
		. ", PD_prize_1 =" . sql_number($prize_1)
		. ", PD_prize_2 =" . sql_number($prize_2)
		. ", PD_prize_3 =" . sql_number($prize_3)
		. ", PD_prize_name1 =" . sql_char($prize_name1)
		. ", PD_prize_name2 =" . sql_char($prize_name2)
		. ", PD_prize_name3 =" . sql_char($prize_name3)
		. ", PD_status = '$status'"
		. " WHERE PD_present_id='$present_id'";
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

<?= $msg ?><br><br>

<input type="button" value=" 戻る " onclick="location.href='index.php'">
</form>
</center>
<? footer_jmk(0); ?>


</body>
</html>
