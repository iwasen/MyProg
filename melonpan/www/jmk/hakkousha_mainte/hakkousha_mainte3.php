<?
include ("../inc/header_jmk.php");
include ("../inc/footer_jmk.php");
include ("../inc/common.php");
include ("../inc/database_inc.php");
include ("../inc/admin_inc.php");
include ("../inc/sub_func_inc.php");

/******************************************************
' System :めろんぱん事務局用ページ
' Content:発行者情報メンテナンス
'******************************************************/

$title_text = $TT_hakkousha_mainte;
$title_color = $TC_HAKKOUSHA;

$err_flg = "";
if ($mail_add_main != $old_mail_add_main) {
	$sql = "SELECT mail_add_main FROM M_PUBLISHER WHERE mail_add_main='$mail_add_main'";
	$result = db_exec($sql);
	if (pg_numrows($result)) {
		$msg = "メールアドレス「" . $mail_add_main . "」は、既に登録されているようです。ご確認下さい。";
		$err_flg = "1";
	}
}
if (!addr_check2($mail_add_main) || !addr_check2($mail_add_sub)) {
	$msg = "メールアドレスが正しくないようです。ご確認下さい。";
	$err_flg = "1";
}

if ($err_flg != "1") {
	$sql = "UPDATE M_PUBLISHER"
		. " SET MAIL_ADD_MAIN =" . sql_char($mail_add_main)
		. ", MAIL_ADD_SUB =" . sql_char($mail_add_sub)
		. ", OWNER_PSWD =" . sql_char($owner_pswd)
		. ", OWNER_NM_KANJ =" . sql_char($owner_nm_kanj)
		. ", OWNER_NM_KANA =" . sql_char($owner_nm_kana)
		. ", OWNER_ZIP1 =" . sql_char($owner_zip1)
		. ", OWNER_ZIP2 =" . sql_char($owner_zip2)
		. ", OWNER_PREFEC =" . sql_char($owner_prefec)
		. ", OWNER_ADD =" . sql_char($owner_add)
		. ", OWNER_TEL =" . sql_char($owner_tel)
		. ", OWNER_RIBA_FLG =" . sql_number($owner_riba_flg)
		. ", MODEFY_DT = 'now'";
	$sql = $sql . " WHERE PUBLISHER_ID = '$publisher_id'";
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
<center>
<form method="post" name="form1">

<?= $msg ?><br><br>

<?
if ($err_flg == "1") {
?>
<input type="button" value=" 戻る " onclick="history.back()">
<?
} else {
?>
<input type="button" value=" 戻る " onclick="location.href='hakkousha_mainte1.php'">
<?
}
?>
</form>
</center>
<? footer_jmk(0); ?>

</body>
</html>
