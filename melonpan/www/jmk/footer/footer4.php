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

$admin_msg = trim($admin_msg);
if ($user_footer != "1") { $user_footer = "0"; }
if ($letter_footer != "1") { $letter_footer = "0"; }
if ($riba_footer != "1") { $riba_footer = "0"; }
if ($bakyun_footer != "1") { $bakyun_footer = "0"; }
$insert_position = '00';
if ($position_header == '1') { $insert_position[0] = '1'; }
if ($position_footer == '1') { $insert_position[1] = '1'; }

$sql = "UPDATE T_MAG_FOOTER"
	. " SET admin_msg = '$admin_msg'"
	. ", user_footer = to_number('$user_footer', '9')"
	. ", riba_footer = to_number('$riba_footer', '9')"
	. ", letter_footer = to_number('$letter_footer', '9')"
	. ", bakyun_footer = to_number('$bakyun_footer', '9')"
	. ", modify_dt = 'now'"
	. ", access_day = '$access_day'"
	. ", access_day_end = '$access_day_end'"
	. ", insert_position = '$insert_position'"
	. " WHERE footer_id = $footer_id";
db_exec($sql);
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
更新しました。<br><br>

<input type="button" value=" 戻る " onclick="location.href='index.php'">
</center>
</form>

<? footer_jmk(0); ?>

</body>
</html>
