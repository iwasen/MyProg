<?
include ("../inc/header_jmk.php");
include ("../inc/footer_jmk.php");
include ("../inc/common.php");
include ("../inc/database_inc.php");
include ("../inc/admin_inc.php");
include ("../inc/sub_func_inc.php");
include ("../inc/com_mail_inc.php");

/******************************************************
' System :めろんぱん事務局用ページ
' Content:めろんぱんレター配信
'******************************************************/

$title_text = $TT_letter_melonpan;
$title_color = $TC_MASTER;

$mag_id = "000049";
$new_mag = StripSlashes($new_mag);
$osusume_mag= StripSlashes($osusume_mag);

$index_id = update_squ_key ("T_BODY");
$index_id = sprintf("%d", $index_id);

$resrv_month = sprintf("%02d", $reserve_month);
$resrv_day = sprintf("%02d", $reserve_day);
$resrv_hour = sprintf("%04d-%02d-%02d %02d:%02d:00", $reserve_year, $reserve_month, $reserve_day, $reserve_hour, $reserve_minutes);

//max_body_id
$sql = "SELECT MAX(body_id) AS max_body_id FROM T_BODY WHERE mag_id='$mag_id'";
$result = db_exec($sql);
if (pg_numrows($result)) {
	$fetch = pg_fetch_object($result, 0);
	$max_body_id = $fetch->max_body_id;
	if ($max_body_id >= 9999) {
		$max_body_id = 0;
	}
}

//=== Main ===
$send_date = $reserve_year . "/" . sprintf("%02d",$reserve_month) . "/" . sprintf("%02d",$reserve_day);
$body = str_replace("@send_date@", $send_date, $body);
$subject = str_replace("@send_date@", $send_date, $subject);

$max_body_id = $max_body_id + 1;
$sql = "INSERT INTO T_BODY (index_id, mag_id, body_id, mag_titl, mag_body, modefy_dt)"
	. " VALUES($index_id, '$mag_id', $max_body_id, '$subject', '$body', 'now')";
db_exec($sql);

$sql = "INSERT INTO T_PUB_RESERVE(index_id, mag_id, body_id, resrv_month, resrv_day,  resrv_hour, resrv_flg, modefy_dt)"
	. " VALUES($index_id, '$mag_id', $max_body_id, '$resrv_month', '$resrv_day', to_timestamp('$resrv_hour', 'YYYY-MM-DD HH24:MI:SS'), '0 ', 'now')";
db_exec($sql);

//新着メルマガ掲載メール送信
if ($new_mag <> "") {
	$send_date = $reserve_month . "月" . $reserve_day . "日";
	$sql = "SELECT MP.owner_nm_kanj, MP.mail_add_main, MM.mag_nm"
		. " FROM M_MAGAZINE MM, M_PUBLISHER MP"
		. " WHERE MM.mag_id IN (" . $new_mag . ") AND MM.publisher_id=MP.publisher_id";
	$result = db_exec($sql);
	$nrow = pg_numrows($result);
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);
		$owner_nm_kanj = $fetch->owner_nm_kanj;
		$mail_add_main = $fetch->mail_add_main;
		$mag_nm = $fetch->mag_nm;
		new_mag_keisai($owner_nm_kanj, $mail_add_main, $mag_nm, $send_date);
	}
	//M_MAGAZINEに掲載日時挿入
	$sql = "UPDATE M_MAGAZINE SET keisai_date=to_timestamp('$resrv_hour', 'YYYY-MM-DD HH24:MI:SS') WHERE mag_id IN (" . $new_mag . ")";
	db_exec($sql);
}
//T_osusume_listに掲載日時挿入
if ($osusume_mag <> "") {
	$sql = "UPDATE T_OSUSUME_LIST SET keisai_date=to_timestamp('$resrv_hour', 'YYYY-MM-DD HH24:MI:SS') WHERE mag_id IN (" . $osusume_mag . ")";
	db_exec($sql);
}
$msg = "登録しました。";

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
