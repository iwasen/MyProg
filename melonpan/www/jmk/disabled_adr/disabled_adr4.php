<?
include ("../inc/header_jmk.php");
include ("../inc/footer_jmk.php");
include ("../inc/common.php");
include ("../inc/database_inc.php");
include ("../inc/admin_inc.php");
include ("../inc/sub_func_inc.php");

/******************************************************
' System :めろんぱん事務局用ページ
' Content:購読禁止メールアドレス登録
'******************************************************/

$title_text = $TT_disabled_adr;
$title_color = $TC_DOKUSHA;

$mail_addr = replace_single($mail_addr);
$new_mail_addr = replace_single($new_mail_addr);

if (!addr_check2($new_mail_addr)) {
	$msg = "メールアドレスが正しくないようです。ご確認下さい。";
	$err_flg = "1";
} else {
	if ($new_mail_addr != $mail_addr) {
		$sql = "SELECT * FROM T_MAILADDR_DISABLED WHERE mail_addr='$new_mail_addr'";
		$result = db_exec($sql);
		if (pg_numrows($result)) {
			$msg = "既にメールアドレスが登録されているようです。ご確認ください。";
			$err_flg = "1";
		} else {
			$sql = "UPDATE T_MAILADDR_DISABLED SET mail_addr='$new_mail_addr', register_dt='now' WHERE mail_addr='$mail_addr'";
			db_exec($sql);
			$msg = "更新しました。";
		}
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
