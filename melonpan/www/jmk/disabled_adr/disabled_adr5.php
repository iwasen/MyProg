<?
include ("../inc/header_jmk.php");
include ("../inc/footer_jmk.php");
include ("../inc/common.php");
include ("../inc/database_inc.php");
include ("../inc/admin_inc.php");
include ("../inc/sub_func_inc.php");

/******************************************************
' System :����Ѥ��̳���ѥڡ���
' Content:���ɶػߥ᡼�륢�ɥ쥹��Ͽ
'******************************************************/

$title_text = $TT_disabled_adr;
$title_color = $TC_DOKUSHA;

$mail_addr = replace_single($mail_addr);

$sql = "SELECT * FROM T_MAILADDR_DISABLED WHERE mail_addr='$mail_addr'";
$result = db_exec($sql);

if (pg_numrows($result) == 0) {
	$msg = "�᡼�륢�ɥ쥹�����Ĥ���ޤ��󡣤���ǧ����������";
	$err_flg = "1";
} else {
	$sql = "DELETE FROM T_MAILADDR_DISABLED WHERE mail_addr='$mail_addr'";
	db_exec($sql);
	$msg = "������ޤ�����";
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
<input type="button" value=" ��� " onclick="history.back()">
<?
} else {
?>
<input type="button" value=" ��� " onclick="location.href='index.php'">
<?
}
?>

</form>
</center>

<? footer_jmk(0); ?>

</body>
</html>
