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

$modify_dt = date("y-m-d H:i:s");
$new_msg = replace_single($new_msg);

$sql = "SELECT msg_id FROM T_WHATS_NEW WHERE msg_id=$msg_id";
$result = db_exec($sql);
if (!pg_numrows($result)) {
	$msg = "�ǡ��������Ĥ���ޤ��󡣤���ǧ����������";
} else {
	$sql = "DELETE FROM T_WHATS_NEW WHERE msg_id=$msg_id";
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

<form method="post" name="form1" action="hon_touroku3.php">

<center>
<?= $msg ?><br><br>

<input type="button" value=" ��� " onclick="location.href='index.php'">
</center>
</form>

<? footer_jmk(0); ?>

</body>
</html>
