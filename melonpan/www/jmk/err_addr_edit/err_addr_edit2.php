<?
include ("../inc/header_jmk.php");
include ("../inc/footer_jmk.php");
include ("../inc/common.php");
include ("../inc/database_inc.php");
include ("../inc/admin_inc.php");
include ("../inc/sub_func_inc.php");

/******************************************************
' System :����Ѥ��̳���ѥڡ���
' Content:�������ɥ쥹��������
'******************************************************/

$title_text = "�������ɥ쥹����";
$title_color = $TC_MASTER;

//'=== Main ===

//T_MAILADDR���᡼�륢�ɥ쥹���
if ($del_mag_list != "") {
	$sql = "DELETE FROM T_MAILADDR WHERE mail_id IN (" . $del_mag_list . ")";
	db_exec($sql);
}
$i = 0;
while ($edit_mail_id[$i]) {
	//T_MAILADDR����(err_count=0)
	$sql = "UPDATE T_MAILADDR SET mail_addr='$new_mail_addr[$i]', err_count=0 WHERE mail_id=$edit_mail_id[$i]";
	db_exec($sql);
	if ($reader_id[$i] > 0) {
	//M_MELONPA����
	$sql = "UPDATE M_MELONPA SET mail_addr='$new_mail_addr[$i]' WHERE reader_id=$reader_id[$i]";
	db_exec($sql);
	}
	$i++;
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

<br>�¹Ԥ��ޤ�����<br><br>

<input type="button" value=" ��� " onclick="location.href='index.php?kind=<?= $kind ?>'">

</center><br>

<? footer_jmk(0); ?>

</body>
</html>
