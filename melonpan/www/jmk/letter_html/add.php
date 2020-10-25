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

$title_text = $TT_letter_html;
$title_color = $TC_MASTER;

$mag_id = "S00001";
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

db_begin_trans();

$max_body_id = $max_body_id + 1;
$sql = "INSERT INTO T_BODY (index_id, mag_id, body_id, mag_titl, mag_body, modefy_dt)"
	. " VALUES($index_id, '$mag_id', $max_body_id, '$subject', '$body', 'now')";
db_exec($sql);

$sql = "INSERT INTO T_PUB_RESERVE(index_id, mag_id, body_id, resrv_month, resrv_day,  resrv_hour, resrv_flg, modefy_dt)"
	. " VALUES($index_id, '$mag_id', $max_body_id, '$resrv_month', '$resrv_day', to_timestamp('$resrv_hour', 'YYYY-MM-DD HH24:MI:SS'), '0 ', 'now')";
db_exec($sql);

$sql = sprintf("INSERT INTO T_HTML_LETTER (hl_subject,hl_body,hl_send_date,hl_index_id) VALUES (%s,%s,%s,%s)",
		sql_char($subject), sql_char($body), sql_date($resrv_hour), sql_number($index_id));
db_exec($sql);

db_commit_trans();

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
