<?
include ("../inc/header_jmk.php");
include ("../inc/footer_jmk.php");
include ("../inc/common.php");
include ("../inc/database_inc.php");
include ("../inc/admin_inc.php");
include ("../inc/sub_func_inc.php");

/******************************************************
' System :����Ѥ��̳���ѥڡ���
' Content:�С����塼���ۿ�
'******************************************************/

$title_text = $TT_letter_bakyun;
$title_color = $TC_MASTER;

$sql = "SELECT * FROM T_PUB_RESERVE WHERE index_id='$index_id'";
$result = db_exec($sql);
if (!pg_numrows($result)) {
	$msg = '�ǡ��������˹�������Ƥ���褦�Ǥ�������ǧ����������';
} else {
	$resrv_month = sprintf("%02d", $reserve_month);
	$resrv_day = sprintf("%02d", $reserve_day);
	$resrv_hour = sprintf("%04d-%02d-%02d %02d:%02d:00", $reserve_year, $reserve_month, $reserve_day, $reserve_hour, $reserve_minutes);

	db_begin_trans();
	$sql = "UPDATE T_BODY SET mag_titl='$subject', mag_body='$body', modefy_dt='now' WHERE index_id=$index_id";
	if (db_exec($sql)) {
		$sql = "UPDATE T_PUB_RESERVE"
			. " SET resrv_month='$resrv_month', resrv_day='$resrv_day'"
			. ", resrv_hour=to_timestamp('$resrv_hour','YYYY-MM-DD HH24:MI:SS')"
			. ", modefy_dt='now'"
			. " WHERE index_id=$index_id";
		if (db_exec($sql)) {
			$ok = "ok";
		}
	}
	if ($ok == "ok") {
		db_commit_trans();
		$msg = "�������ޤ�����";
	} else {
		db_rollback();
		$msg = "������˥��顼��ȯ���������ᡢ�¹ԤǤ��ޤ���Ǥ�����";
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

<form method="post" name="form1" action="hon_touroku3.php">
<center>

<?= $msg ?><br><br>

<input type="button" value=" ��� " onclick="location.href='index.php'">
</center>
</form>

<? footer_jmk(0); ?>

</body>
</html>
