<?
include ("../inc/header_jmk.php");
include ("../inc/footer_jmk.php");
include ("../inc/common.php");
include ("../inc/database_inc.php");
include ("../inc/admin_inc.php");
include ("../inc/sub_func_inc.php");

/******************************************************
' System :めろんぱん事務局用ページ
' Content:りばっチャ登録
'******************************************************/

$title_text = $TT_riba_regist;
$title_color = $TC_HAKKOUSHA;

$sql = "SELECT mail_add_main FROM M_PUBLISHER WHERE mail_add_main='$mail_add_main'";
$result = db_exec($sql);
if (pg_numrows($result)) {
	$err_flg = "1";
	$msg = "メールアドレス「" . $mail_add_main . "」は、既に登録されているようです。ご確認下さい。";
} elseif (!addr_check2($mail_add_main) || !addr_check2($mail_add_sub)) {
	$err_flg = "1";
	$msg = "メールアドレスが正しくないようです。ご確認下さい。";
} else {
	if (($publisher_id = update_squ_key ("M_PUBLISHER")) != "-1") {
		$sql = "INSERT INTO M_PUBLISHER"
			. " (PUBLISHER_ID, MAIL_ADD_MAIN, MAIL_ADD_SUB, OWNER_PSWD, OWNER_NM_KANJ, OWNER_NM_KANA, OWNER_ZIP1, OWNER_ZIP2, OWNER_PREFEC, OWNER_ADD"
			. ", OWNER_TEL, OWNER_STATUS_FLG, PT_MELONPAN, PT_MELMEL, MODEFY_DT, OWNER_RIBA_FLG, REGIST_DT)"
			. " VALUES ($publisher_id"
			. ", " . sql_char($mail_add_main)
			. ", " . sql_char($mail_add_sub)
			. ", " . sql_char($owner_pswd)
			. ", " . sql_char($owner_nm_kanj)
			. ", " . sql_char($owner_nm_kana)
			. ", " . sql_char($owner_zip1)
			. ", " . sql_char($owner_zip2)
			. ", " . sql_char($owner_prefec)
			. ", " . sql_char($owner_add)
			. ", " . sql_char($owner_tel)
			. ", '00'"
			. ", " . sql_number($pt_melonpan)
			. ", 0"
			. ", 'now'"
			. ", 1"
			. ", 'now')";
		db_exec($sql);
		$msg = "登録しました。";
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
<?
if ($err_flg == "1") {
?>
<input type="button" value=" 戻る " onclick="history.back()">
<?
} else {
?>
<input type="button" value=" 戻る " onclick="location.href='index.php'">
<?
}
?>
</form>
</center>
<? footer_jmk(0); ?>

</body>
</html>
