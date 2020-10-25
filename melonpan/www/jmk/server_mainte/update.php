<?
include ("../inc/header_jmk.php");
include ("../inc/footer_jmk.php");
include ("../inc/common.php");
include ("../inc/database_inc.php");
include ("../inc/admin_inc.php");

/******************************************************
' System :めろんぱん事務局用ページ
' Content:サーバ停止期間設定
'******************************************************/

$title_text = $TT_server_mainte;
$title_color = $TC_OTHER;

$start_date = sprintf("%04d-%02d-%02d %02d:00:00", $start_year, $start_month, $start_day, $start_hour);
$end_date = sprintf("%04d-%02d-%02d %02d:00:00", $end_year, $end_month, $end_day, $end_hour);
$info_s_date = sprintf("%04d-%02d-%02d 00:00:00", $info_s_year, $info_s_month, $info_s_day);
$info_e_date = sprintf("%04d-%02d-%02d 00:00:00", $info_e_year, $info_e_month, $info_e_day);

// 差込み文字変換
function form_info($month,$day,$hour) {
	$month = i18n_ja_jp_hantozen($month, "A");
	$day = i18n_ja_jp_hantozen($day, "A");
	$hour = i18n_ja_jp_hantozen($hour, "A") . "：００";
	return $month . "月" . $day . "日". $hour;
}
$sp_msg = str_replace("@start_date@", form_info($start_month,$start_day,$start_hour), $sp_msg);
$sp_msg = str_replace("@end_date@", form_info($end_month,$end_day,$end_hour), $sp_msg);


switch ($next_action) {
case 'new':
	$sql = sprintf("insert into t_stop_period(sp_start_date,sp_end_date,sp_info_start,sp_info_end,sp_msg) values (%s,%s,%s,%s,%s)",
				sql_date($start_date),
				sql_date($end_date),
				sql_date($info_s_date),
				sql_date($info_e_date),
				sql_char(trim($sp_msg)));
				db_exec($sql);
	$msg = "登録しました。";
	break;
case 'update':
	$sql = sprintf("update t_stop_period set sp_start_date=%s,sp_end_date=%s,sp_info_start=%s,sp_info_end=%s,sp_msg=%s,sp_regist_date='now'"
				. " where sp_seq_no='$seq_no'",
				sql_date($start_date),
				sql_date($end_date),
				sql_date($info_s_date),
				sql_date($info_e_date),
				sql_char($sp_msg));
				db_exec($sql);
	$msg = "更新しました。";
	break;
case 'delete':
	$sql = "delete from t_stop_period where sp_seq_no=" . $seq_no;
	db_exec($sql);
	$msg = "削除しました。";
	break;
default:
	redirect('index.php');
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
