<?
include ("../inc/header_jmk.php");
include ("../inc/footer_jmk.php");
include ("../inc/common.php");
include ("../inc/database_inc.php");
include ("../inc/admin_inc.php");
include ("../inc/sub_func_inc.php");

/******************************************************
' System :����Ѥ��̳���ѥڡ���
' Content:�ץ쥼��ȱ������
'******************************************************/

$title_text = $TT_present;
$title_color = $TC_OTHER;

$ok = "";

//'=== Main ===
db_begin_trans();
$sql = "DELETE FROM M_PRESENT_DATA WHERE PD_present_id='$present_id'";
if (db_exec($sql)) {
	$sql = "DELETE FROM T_PRESENT_LIST WHERE PL_present_id='$present_id'";
	if (db_exec($sql)) {
		if(pg_lounlink($gConn, $oid)) {
			$ok = "ok";
		}
	}
}
if ($ok == "ok") {
	db_commit_trans();
	$msg = "������ޤ�����";
} else {
	db_rollback();
	$msg = "������˥��顼��ȯ���������ᡢ�����Ǥ��ޤ���Ǥ�������";
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

<input type="button" value=" ��� " onclick="location.href='index.php'">
</form>
</center>
<? footer_jmk(0); ?>


</body>
</html>
