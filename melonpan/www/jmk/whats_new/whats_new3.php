<?
include ("../inc/header_jmk.php");
include ("../inc/footer_jmk.php");
include ("../inc/common.php");
include ("../inc/database_inc.php");
include ("../inc/admin_inc.php");
include ("../inc/sub_func_inc.php");

/******************************************************
' System :����Ѥ��̳���ѥڡ���
' Content:��What's New������
'******************************************************/

$title_text = $TT_whats_new;
$title_color = $TC_MASTER;

if (($msg_id = update_squ_key ("T_WHATS_NEW")) != "-1") {
	$sql = "INSERT INTO T_WHATS_NEW (msg_id, new_msg, msg_url, modify_dt, target) VALUES ($msg_id, '$new_msg', '$msg_url', 'now', '$target')";
	db_exec($sql);
	$msg = "��Ͽ���ޤ�����";
} else {
	$msg = "������˥��顼��ȯ���������ᡢ�����Ǥ��ޤ���Ǥ�����";
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

<form method="post" name="form1" action="hon_touroku3.php">

<center>
<?= $msg ?><br><br>

<input type="button" value=" ��� " onclick="location.href='index.php'">
</center>
</form>

<? footer_jmk(0); ?>

</body>
</html>
