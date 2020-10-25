<?
include ("../inc/header_jmk.php");
include ("../inc/footer_jmk.php");
include ("../inc/common.php");
include ("../inc/database_inc.php");
include ("../inc/admin_inc.php");
include ("../inc/sub_func_inc.php");

/******************************************************
' System :めろんぱん事務局用ページ
' Content:読者情報メンテナンス
'******************************************************/

$title_text = $TT_dokusha_mainte;
$title_color = $TC_DOKUSHA;

$reader_birth = sprintf("%04d%02d%02d", $year, $month, $day);
if (!addr_check2($mail_addr)) {
	$msg = "メールアドレスが正しくないようです。ご確認ください。";
	$err_flg = "1";
} elseif ($mail_addr != $old_mail_addr) {
	$sql = "SELECT * FROM M_MELONPA WHERE mail_addr='$mail_addr'";
	$result = db_exec($sql);
	if (pg_numrows($result)) {
		header ("location: ../admin/err_msg.php?id=2107");
		exit;
	} else {
		$sql = "UPDATE M_MELONPA"
			. " SET MAIL_ADDR = '$mail_addr'"
			. ", READER_PSWD = '$password'"
			. ", READER_BIRTH = to_date('$reader_birth', 'YYYYMMDD')"
			. ", READER_SEX = '$reader_sex'"
			. ", READER_MRG = '$reader_mrg'"
			. ", READER_ZIP1 = '$zip1'"
			. ", READER_ZIP2 = '$zip2'"
			. ", READER_OCCUP = '$reader_occup'"
			. ", MODIFY_DT = 'now'"
			. " WHERE READER_ID = $reader_id";
		db_exec($sql);

		$sql = "UPDATE T_CHOICE SET send_mail_addr = '$mail_addr' WHERE send_mail_addr='$old_mail_addr'";
		db_exec($sql);
		$sql = "UPDATE T_MAILADDR SET mail_addr='$mail_addr' WHERE mail_addr='$old_mail_addr'";
		db_exec($sql);
		$sub = "SELECT PL_mail_adr FROM M_PRESENT_DATA WHERE PD_status='0' AND PL_present_id=PD_present_id";
		$sql = "UPDATE T_PRESENT_LIST SET PL_mail_adr='$mail_addr' WHERE '$old_mail_addr'=(" . $sub . ")";
		db_exec($sql);
		$msg = "更新しました。";
	}
} else {
	$sql = "UPDATE M_MELONPA"
		. " SET READER_PSWD = '$password'"
		. ", READER_BIRTH = to_date('$reader_birth', 'YYYYMMDD')"
		. ", READER_SEX = '$reader_sex'"
		. ", READER_MRG = '$reader_mrg'"
		. ", READER_ZIP1 = '$zip1'"
		. ", READER_ZIP2 = '$zip2'"
		. ", READER_OCCUP = '$reader_occup'"
		. ", MODIFY_DT = 'now'"
		. " WHERE READER_ID = $reader_id";
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
<input type="button" value=" 戻る " onclick="location.href='dokusha_mainte1.php'">
<?
}
?>

</form>
</center>

<? footer_jmk(0); ?>

</body>
</html>
