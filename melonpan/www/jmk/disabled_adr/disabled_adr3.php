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

$sql = "SELECT * FROM T_MAILADDR_DISABLED WHERE mail_addr='$mail_addr'";
$result = db_exec($sql);

if (pg_numrows($result)) {
	$msg = "������Ͽ����Ƥ���褦�Ǥ�������ǧ����������";
	$err_flg = "1";
} elseif (!addr_check2($mail_addr)) {
	$msg = "�᡼�륢�ɥ쥹���������ʤ��褦�Ǥ�������ǧ��������";
	$err_flg = "1";
} else {
	$sql = "INSERT INTO T_MAILADDR_DISABLED(mail_addr, register_dt) values('$mail_addr', 'now')";
	db_exec($sql);
	$msg = "��Ͽ���ޤ�����";
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
