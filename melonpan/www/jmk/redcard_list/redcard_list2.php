<?
include ("../inc/header_jmk.php");
include ("../inc/footer_jmk.php");
include ("../inc/common.php");
include ("../inc/database_inc.php");
include ("../inc/admin_inc.php");
include ("../inc/sub_func_inc.php");

/******************************************************
' System :����Ѥ��̳���ѥڡ���
' Content:���Υ�åɥ����ɥꥹ��
'******************************************************/

$title_text = "���Υ�åɥ����ɥꥹ��";
$title_color = $TC_HAKKOUSHA;

$mag_id = sprintf("%06d", $mag_id);
$ok = "";

db_begin_trans();
$sql = "SELECT mag_id FROM M_MAGAZINE WHERE mag_id='$mag_id' AND mag_status_flg!='99' AND mag_pub_status_flg!='99'";
$result = db_exec($sql);
$nrow = pg_numrows($result);
if ($nrow <= 0) {
	$msg = "�ǡ��������˹�������Ƥ���褦�Ǥ�������ǧ����������";
} else {

	//M_MAGAZINE �Ѵ�����
	$sql = "UPDATE M_MAGAZINE SET mag_status_flg='99', mag_pub_status_flg='99', modefy_dt='now' WHERE mag_id='$mag_id'";
	if (db_exec($sql)) {

		//T_MAILADDR ���ɲ��
		$sql = "DELETE FROM T_MAILADDR WHERE mag_id='$mag_id'";
		if (db_exec($sql)) {

			//T_MAG_DELREASON�˲����ͳ�ɲ�
			$mag_del_id = update_squ_key ("T_MAG_DELREASON");
			$reason = "��̳�ɤˤ���������";
			$sql = "INSERT INTO T_MAG_DELREASON (mag_del_id, mag_id, reason, modefy_dt) VALUES ($mag_del_id, '$mag_id', '$reason', 'now')";
			db_exec($sql);
			$ok = "ok";
		}
	}
}
if ($ok != "") {
	db_commit_trans();
	$msg = "���ޥ��ɣġ�" . $mag_id . "���Ѵ�������¹Ԥ��ޤ�����";
} else {
	db_rollback();
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

<center>
<form method="post" name="form1">

<?= $msg ?><br><br>

<input type="button" value=" ��� " onclick="location.href='redcard_list.php'">

</form>
</center>

<? footer_jmk(0); ?>

</body>
</html>
