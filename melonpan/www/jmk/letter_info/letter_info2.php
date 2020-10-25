<?
include ("../inc/header_jmk.php");
include ("../inc/footer_jmk.php");
include ("../inc/database.php");
include ("../inc/admin_inc.php");
include ("../inc/com_func_inc.php");

/******************************************************
' System :めろんぱん事務局用ページ
' Content:メルマガ基本情報メンテナンス
'******************************************************/
if($mag_id == '000013') {
	$title_text = $TT_letter_bakyun;
	$title_color = $TC_MASTER;
}
$mail_add_publish = replace_single($mail_add_publish);
$mag_nm = replace_single($mag_nm);
$mag_intro_long = replace_single($mag_intro_long);
$mag_intro_short = replace_single($mag_intro_short);
$mag_hp = replace_single($mag_hp);
$mag_cycle_day = replace_single($mag_cycle_day);
$mag_from_addr = replace_single($mag_from_addr);
$mag_subject_tmpl = replace_single($mag_subject_tmpl);
$mag_header_tmpl = replace_single($mag_header_tmpl);
$mag_footer_tmpl = replace_single($mag_footer_tmpl);

$sql = "UPDATE M_MAGAZINE SET"
	. " melmel_id='$melmel_id'"
	. ", mail_add_publish='$mail_add_publish'"
	. ", mag_nm='$mag_nm'"
	. ", mag_intro_long='$mag_intro_long'"
	. ", mag_intro_short='$mag_intro_short'"
	. ", mag_hp='$mag_hp'"
	. ", mag_type='$mag_type'"
	. ", mag_cycle_day='$mag_cycle_day'"
	. ", mag_catg_1='$mag_catg_1'"
	. ", mag_catg_2='$mag_catg_2'"
	. ", mag_bg_1='$mag_bg_1'"
	. ", mag_bg_2='$mag_bg_2'"
	. ", mag_reader_sex='$mag_reader_sex'"
	. ", mag_reader_age='$mag_reader_age'"
	. ", mag_reader_area='$mag_reader_area'"
	. ", mag_reader_occup='$mag_reader_occup'"
	. ", mag_bn_pub='$mag_bn_pub'"
	. ", mag_present_ins_flg='$mag_present_ins_flg'"
	. ", mag_from_addr='$mag_from_addr'"
	. ", mag_subject_tmpl='$mag_subject_tmpl'"
	. ", mag_header_tmpl='$mag_header_tmpl'"
	. ", mag_footer_tmpl='$mag_footer_tmpl'"
	. ", mag_original_flg='$mag_original_flg'"
	. " WHERE mag_id='$mag_id'";
update_sql($sql);
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

<? if ($mag_id == '000013') { ?>
<input type="button" value=" 戻る " onclick="location.href='../letter_bakyun/index.php'">
<? } ?>

</center>
</form>

<? footer_jmk(0); ?>

</body>
</html>
