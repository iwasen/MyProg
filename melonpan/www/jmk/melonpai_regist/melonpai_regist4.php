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

$err_flg = "";

if (!addr_check2($mail_addr)) {
	$msg = "メールアドレスが正しくないようです。ご確認下さい。";
	$err_flg = "1";
} else {
	$sql = "UPDATE M_MELONPAI"
			. " SET MELONPAI_PSWD =" . sql_char($pswd)
			. ", MELONPAI_NAME =" . sql_char($name)
			. ", MELONPAI_NIC =" . sql_char($nic)
			. ", MELONPAI_MAIL_ADDR =" . sql_char($mail_addr)
			. ", MELONPAI_ZIP1 =" . sql_char($zip1)
			. ", MELONPAI_ZIP2 =" . sql_char($zip2)
			. ", MELONPAI_ADD =" . sql_char($add)
			. ", MELONPAI_TEL =" . sql_char($tel)
			. ", MELONPAI_PRO =" . sql_char($pro)
			. ", MELONPAI_M_FLG =" . sql_char($m_flg)
			. ", MELONPAI_POINT =" . sql_number($point)
			. ", OSUSUME_NUM =" . sql_number($osusume_num)
			. ", RECOM_NUM =" . sql_number($recom_num)
			. ", ABUNAI_NUM =" . sql_number($abunai_num)
			. ", CHOICE_NUM =" . sql_number($choice_num)
			.	" WHERE MELONPAI_ID=$melonpai_id";
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
<input type="button" value=" 戻る " onclick="location.href='index.php'">
<?
}
?>

</form>
</center>
<? footer_jmk(0); ?>

</body>
</html>
