<?
/******************************************************
' System :�֤���������� �������������ӥ��׻�̳���ѥڡ���
' Content:��̳�ɥڡ������̡�ź�եե�����ɽ��
'******************************************************/

$top = "..";
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");

// ���ϥѥ�᡼��
$comm_id = $_GET['comm_id'];
$file_no = $_GET['file_no'];

$sql = sprintf("SELECT mat_file_type,mat_data FROM t_mail_attachment WHERE mat_comm_id=%s AND mat_file_no=%s",
		sql_number($comm_id),
		sql_number($file_no));
$result = db_exec($sql);
if (pg_num_rows($result) != 0) {
	$fetch = pg_fetch_object($result, 0);

	header("Cache-control: no-cache");
	header("Content-type: $fetch->mat_file_type");

	echo base64_decode($fetch->mat_data);
}
exit;
?>