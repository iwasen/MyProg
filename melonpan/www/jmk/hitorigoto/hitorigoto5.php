<?
include ("../inc/header_jmk.php");
include ("../inc/footer_jmk.php");
include ("../inc/common.php");
include ("../inc/database_inc.php");
include ("../inc/admin_inc.php");
include ("../inc/sub_func_inc.php");

/******************************************************
' System :����Ѥ��̳���ѥڡ���
' Content:�ֺ����ΤҤȤꤴ�ȡ�����
'******************************************************/

$title_text = $TT_hitorigoto;
$title_color = $TC_MASTER;

$access_day = sprintf("%04d%02d%02d",$info_year, $info_month, $info_day);
$modify_dt = date("y-m-d H:i:s");

$sql = "DELETE FROM T_ADMIN_MSG WHERE sqmsg_id=$sqmsg_id";
db_exec($sql);
$msg = "������ޤ�����";

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
