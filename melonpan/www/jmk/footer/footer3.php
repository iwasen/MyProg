<?
include ("../inc/header_jmk.php");
include ("../inc/footer_jmk.php");
include ("../inc/common.php");
include ("../inc/database_inc.php");
include ("../inc/admin_inc.php");
include ("../inc/sub_func_inc.php");

/******************************************************
' System :めろんぱん事務局用ページ
' Content:ヘッダー／フッターデータ登録
'******************************************************/

$title_text = $TT_footer;
$title_color = $TC_MASTER;

$access_day = sprintf("%04d%02d%02d",$insert_year, $insert_month, $insert_day);
$access_day_end = sprintf("%04d%02d%02d",$insert_year_end, $insert_month_end, $insert_day_end);

if (($footer_id = update_squ_key ("T_MAG_FOOTER")) != "-1") {
	$admin_msg = trim($admin_msg);
	if ($user_footer != "1") { $user_footer = "0"; }
	if ($letter_footer != "1") { $letter_footer = "0"; }
	if ($riba_footer != "1") { $riba_footer = "0"; }
	if ($bakyun_footer != "1") { $bakyun_footer = "0"; }
	$insert_position = '00';
	if ($position_header == '1') { $insert_position[0] = '1'; }
	if ($position_footer == '1') { $insert_position[1] = '1'; }
	$sql = "INSERT INTO T_MAG_FOOTER (footer_id, admin_msg, access_day, user_footer, riba_footer, letter_footer, bakyun_footer, modify_dt, insert_position, access_day_end) values ($footer_id, '$admin_msg', '$access_day', to_number('$user_footer', '9'), to_number('$riba_footer', '9'), to_number('$letter_footer', '9'), to_number('$bakyun_footer', '9'), 'now', '$insert_position', '$access_day_end')";
	db_exec($sql);
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
登録しました。<br><br>

<input type="button" value=" 戻る " onclick="location.href='index.php'">
</center>
</form>

<? footer_jmk(0); ?>

</body>
</html>
