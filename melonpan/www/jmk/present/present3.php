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

$sql = "SELECT MAX(PD_present_id) AS PD_present_id FROM M_PRESENT_DATA";
$result = db_exec($sql);
if (pg_numrows($result)) {
	$fetch = pg_fetch_object($result, 0);
	$present_id = $fetch->pd_present_id;
	$present_id = sprintf("%05d", $present_id + 1);
}
//'=== 	日付書式変更 ===
$insert_start = sprintf("%04d", $insert_s_year)  . "-" . sprintf("%02d", $insert_s_month) . "-" . sprintf("%02d", $insert_s_day);
$insert_end = sprintf("%04d", $insert_e_year)  . "-" . sprintf("%02d", $insert_e_month) . "-" . sprintf("%02d", $insert_e_day);
$campaign_start = sprintf("%04d", $campaign_s_year)  . "-" . sprintf("%02d", $campaign_s_month) . "-" . sprintf("%02d", $campaign_s_day);
$campaign_end = sprintf("%04d", $campaign_e_year)  . "-" . sprintf("%02d", $campaign_e_month) . "-" . sprintf("%02d", $campaign_e_day);
$lottery_date = sprintf("%04d", $lottery_year)  . "-" . sprintf("%02d", $lottery_month) . "-" . sprintf("%02d", $lottery_day);

//'=== Main ===
if (!is_uploaded_file($prize_pict)) {
	$msg = "画像データが正しく登録されていないようです。ご確認下さい。";
	$err_flag = '1';
} else {
	db_begin_trans();
  $oid = pg_locreate ($gConn);
  $lobj = pg_loopen ($gConn, $oid, "w");
	$fd = fopen($prize_pict, "r");
	$img = fread($fd, filesize($prize_pict));
  pg_lowrite ($lobj, $img);
  pg_loclose ($lobj);

	$sql = "insert into M_PRESENT_DATA (PD_present_id, PD_present_type, PD_present_name, PD_link_text, PD_page_title, PD_explain, PD_presenter, PD_link_url, PD_picture, PD_keyword_flag, PD_keyword1, PD_keyword2, PD_keyword3, PD_campaign_start, PD_campaign_end, PD_insert_start, PD_insert_end, PD_lottery_date, PD_prize_1, PD_prize_2, PD_prize_3, PD_prize_name1, PD_prize_name2, PD_prize_name3, PD_status)"
	 . " values ('$present_id'"
	 . ", " . sql_char($present_type)
	 . ", " . sql_char($present_name)
	 . ", " . sql_char($link_text)
	 . ", " . sql_char($page_title)
	 . ", " . sql_char($explain)
	 . ", " . sql_char($presenter)
	 . ", " . sql_char($link_url)
	 . ", $oid"
	 . ", " . sql_char($keyword_flag)
	 . ", " . sql_char($keyword1)
	 . ", " . sql_char($keyword2)
	 . ", " . sql_char($keyword3)
	 . ", '$campaign_start'"
	 . ", '$campaign_end'"
	 . ", '$insert_start'"
	 . ", '$insert_end'"
	 . ", '$lottery_date'"
	 . ", " . sql_number($prize_1)
	 . ", " . sql_number($prize_2)
	 . ", " . sql_number($prize_3)
	 . ", " . sql_char($prize_name1)
	 . ", " . sql_char($prize_name2)
	 . ", " . sql_char($prize_name3)
	 . ", " . sql_char($status) . ")";
	if (db_exec($sql)) {
		db_commit_trans();
		$msg = "登録しました。";
		$err_flag = "";
	} else {
		db_rollback();
		$msg = "処理中にエラーが発生したため、処理できませんでした。。";
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
<center>
<form method="post" name="form1">

<?= $msg ?><br><br>

<?php
if ($err_flag == '1') {
?>
<input type="button" value=" 戻る " onclick="history.back()">
<?php
} else {
?>
<input type="button" value=" 戻る " onclick="location.href='index.php'">
<?php
}
?>
</form>
</center>
<? footer_jmk(0); ?>

</body>
</html>
