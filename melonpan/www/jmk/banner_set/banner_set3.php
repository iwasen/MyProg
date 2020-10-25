<?
include ("../inc/header_jmk.php");
include ("../inc/footer_jmk.php");
include ("../inc/common.php");
include ("../inc/database_inc.php");
include ("../inc/admin_inc.php");
include ("../inc/sub_func_inc.php");

/******************************************************
' System :����Ѥ��̳���ѥڡ���
' Content:����Хʡ�����
'******************************************************/

$title_text = $TT_banner_set;
$title_color = $TC_OTHER;

$sql = "SELECT MAX(BN_id) AS BN_id FROM T_BANNER";
$result = db_exec($sql);
if (pg_numrows($result)) {
	$fetch = pg_fetch_object($result, 0);
	$bn_id = $fetch->bn_id;
	$bn_id = $bn_id + 1;
}
//'=== 	���ս��ѹ� ===
$bn_start_dt = sprintf("%04d-%02d-%02d %02d:00:00", $bn_start_year, $bn_start_month, $bn_start_day, $bn_start_hour);
$bn_end_dt = sprintf("%04d-%02d-%02d %02d:00:00", $bn_end_year, $bn_end_month, $bn_end_day, $bn_end_hour);

//'=== Main ===
if (!is_uploaded_file($banner_pict)) {
	$msg = "�����ǡ�������������Ͽ����Ƥ��ʤ��褦�Ǥ�������ǧ��������";
	$err_flag = '1';
} else {

	db_begin_trans();
  $oid = pg_locreate ($gConn);
  $lobj = pg_loopen ($gConn, $oid, "w");
	$fd = fopen($banner_pict, "r");
	$img = fread($fd, filesize($banner_pict));
  pg_lowrite ($lobj, $img);
  pg_loclose ($lobj);

	$sql = "insert into T_BANNER (BN_id, BN_kind, BN_title, BN_send_url, BN_picture, BN_start_dt, BN_end_dt)"
		. " values ($bn_id, '$bn_kind', '$bn_title', '$bn_send_url', $oid, to_timestamp('$bn_start_dt', 'YYYY-MM-DD HH24:MI:SS'), to_timestamp('$bn_end_dt', 'YYYY-MM-DD HH24:MI:SS'))";

	if (db_exec($sql)) {
		db_commit_trans();
		$msg = "��Ͽ���ޤ�����";
		$err_flag = "";
	} else {
		db_rollback();
		$msg = "������˥��顼��ȯ���������ᡢ�����Ǥ��ޤ���Ǥ�������";
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

<?php
if ($err_flag == '1') {
?>
<input type="button" value=" ��� " onclick="history.back()">
<?php
} else {
?>
<input type="button" value=" ��� " onclick="location.href='index.php'">
<?php
}
?>
</form>
</center>
<? footer_jmk(0); ?>

</body>
</html>
