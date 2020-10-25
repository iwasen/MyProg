<?
include ("../inc/header_jmk.php");
include ("../inc/footer_jmk.php");
include ("../inc/common.php");
include ("../inc/database_inc.php");
include ("../inc/admin_inc.php");
include ("../inc/sub_func_inc.php");

/******************************************************
' System :めろんぱん事務局用ページ
' Content:めろんぱん通信配信
'******************************************************/

$title_text = $TT_letter_tsuushin;
$title_color = $TC_MASTER;

$TS_id = $id;

$subject = replace_single($subject);
$from = replace_single($from);
$body = replace_single($body);

$send_date = sprintf("%04d-%02d-%02d %02d:%02d:00", $send_year, $send_month, $send_day, $send_hour, $send_minutes);

//=== Main ===
$sql = "UPDATE T_TSUUSHIN SET TS_subject='$subject', TS_from='$from', TS_body='$body'"
	. ", TS_send_date=to_timestamp('$send_date', 'YYYY-MM-DD HH24:MI:SS')"
	. " WHERE TS_id=$TS_id";
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
