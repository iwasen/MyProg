<?
include ("../inc/header_jmk.php");
include ("../inc/footer_jmk.php");
include ("../inc/common.php");
include ("../inc/database_inc.php");
include ("../inc/admin_inc.php");
include ("../inc/sub_func_inc.php");

/******************************************************
' System :めろんぱん事務局用ページ
' Content:メルマガナビ登録
'******************************************************/

$title_text = $TT_melonpai_regist;
$title_color = $TC_DOKUSHA;

if (!addr_check2($mail_addr)) {
	$msg = "メールアドレスが正しくないようです。ご確認下さい。";
	$err_flg = "1";
} else {
	if (($melonpai_id = update_squ_key ("M_MELONPAI")) != "-1") {

		$sql = "INSERT INTO M_MELONPAI(MELONPAI_ID, MELONPAI_PSWD, MELONPAI_NAME, MELONPAI_NIC, MELONPAI_MAIL_ADDR, MELONPAI_ZIP1, MELONPAI_ZIP2, MELONPAI_ADD, MELONPAI_TEL, MELONPAI_PRO, MELONPAI_M_FLG, MELONPAI_POINT, OSUSUME_NUM, RECOM_NUM, ABUNAI_NUM, CHOICE_NUM, MODIFY_DT)"
			. " VALUES($melonpai_id"
			. "," . sql_char($pswd)
			. "," . sql_char($name)
			. "," . sql_char($nic)
			. "," . sql_char($mail_addr)
			. "," . sql_char($zip1)
			. "," . sql_char($zip2)
			. "," . sql_char($add)
			. "," . sql_char($tel)
			. "," . sql_char($pro)
			. "," . sql_char($m_flg)
			. "," . sql_number($point)
			. "," . sql_number($osusume_num)
			. "," . sql_number($recom_num)
			. "," . sql_number($abunai_num)
			. "," . sql_number($choice_num)
			. ", 'now')";
		db_exec($sql);
		$msg = "登録しました。";
	} else {
		$msg = "処理中にエラーが発生したため、処理できませんでした。";
		exit;
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
