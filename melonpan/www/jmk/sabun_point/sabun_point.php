<?
include ("../inc/header_jmk.php");
include ("../inc/footer_jmk.php");
include ("../inc/common.php");
include ("../inc/database_inc.php");
include ("../inc/admin_inc.php");
include ("../inc/sub_func_inc.php");

/******************************************************
' System :����Ѥ��̳���ѥڡ���
' Content:�ɼ���Ͽ����ʬ/�ݥ������Ϳ
'******************************************************/

$title_text = $TT_sabun_point;
$title_color = $TC_MASTER;

$err_flg = "";
if (count($chk) == 0) {
	$msg = "��������ǡ���������ޤ��󡣤���ǧ����������";
	$err_flg = "1";
} else {
/* **
	$sql = "SELECT mag_id FROM M_MAGAZINE WHERE mag_id=:mm_mag_id AND reg1_max_volume>=:reg1_max_volume";
	$parse = OCIParse($conn, $sql);
	OCIBindByName($parse, ":MM_MAG_ID", &$mm_mag_id, 6);
	OCIBindByName($parse, ":REG1_MAX_VOLUME", &$reg1_max_volume, 6);
	for ($i = 0; $i < count($mag_id); $i++){
		if ($chk[$i]) {
			$mm_mag_id = $mag_id[$i];
			$reg1_max_volume = $mag_volume[$i];
		  OCIExecute($parse, OCI_DEFAULT);
			if (OCIFetch($parse)) {
				$msg = "�����ѥǡ������ޤޤ�Ƥ���褦�Ǥ�������ǧ����������";
				$err_flg = "1";
				break;
			}
		}
	}
*/
	for ($i = 0; $i < count($mag_id); $i++){
		if ($chk[$i]) {
			$mm_mag_id = $mag_id[$i];
			$reg1_max_volume = $mag_volume[$i];
			$sql = "SELECT mag_id FROM M_MAGAZINE WHERE mag_id='$mm_mag_id' AND reg1_max_volume>=$reg1_max_volume";
			$result = db_exec($sql);
			if (pg_numrows($result)) {
				$msg = "�����ѥǡ������ޤޤ�Ƥ���褦�Ǥ�������ǧ����������";
				$err_flg = "1";
				break;
			}
		}
	}
	if ($err_flg != "1") {
		$ok = 0;
		db_begin_trans();
		for ($i = 0; $i < count($mag_id); $i++){
			if ($chk[$i] && $total[$i] > 0) {
				$trans_id = update_squ_key ("T_POINT_DETAIL");
				$owner_id = $publisher_id[$i];
				$trans_point = $total[$i];
				$available_point = $total[$i];
				$pd_mag_id = $mag_id[$i];

				//�ݥ���������ɲ�
				$sql1 = "INSERT INTO T_POINT_DETAIL (trans_id, owner_kind, owner_id, attribution_cd, trans_date, trans_point, available_point, counter_id, mag_id)"
					. " VALUES ($trans_id, '2', $owner_id, '05',  'now', $trans_point, $available_point, 0, '$pd_mag_id')";

				//ȯ�Լԥݥ���Ȳû�
				$pt_melonpan = $total[$i];
				$pub_id = $publisher_id[$i];
				$sql2 = "UPDATE M_PUBLISHER SET pt_melonpan = pt_melonpan + $pt_melonpan WHERE publisher_id =$pub_id";

				//���ޥ���reg1_max_volume�ɲ�
				$reg1_max_volume = $mag_volume[$i];
				$mm_mag_id = $mag_id[$i];
				$sql3 = "UPDATE M_MAGAZINE SET reg1_max_volume = $reg1_max_volume WHERE mag_id = '$pd_mag_id'";

				if (db_exec($sql1) && db_exec($sql2) && db_exec($sql3)) {
				} else {
					$ok++;
				}
			}
		}
		if ($ok == 0) {
			db_commit_trans();
			$msg = "�¹Ԥ��ޤ�����";
		} else {
			db_rollback();
			$msg = "������˥��顼��ȯ���������ᡢ�����Ǥ��ޤ���Ǥ�����1";
		}
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
