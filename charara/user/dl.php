<?
/******************************************************
' System :�֤�����Factory�ץ桼���ڡ���
' Content:���鳨��������ɥڡ���
'******************************************************/

$inc = "../inc";
include_once("$inc/database.php");
include_once("$inc/status.php");

$id = $_SERVER['QUERY_STRING'];
if ($id != '') {
	// ���鳨�ե�����̾����
	$sql = "SELECT nd_nigaoe_id,nd_nigaoe_file FROM t_nigaoe_data WHERE nd_random_id=" . sql_char($id);
	$result = db_exec($sql);
	if (pg_num_rows($result)) {
		$fetch = pg_fetch_object($result, 0);
		$nigaoe_id = $fetch->nd_nigaoe_id;
		$filename = $fetch->nd_nigaoe_file;

		if ($nigaoe_id) {
			// ���鳨����URL����
			$url = get_system_info('url_nigaoe');

			// �ե�����̾Ϣ��
			$url .= $filename;

			// ���鳨�ǡ����ơ��֥빹��
			$rec['nd_status'] = STATUS_DOWNLOADED;
			$rec['nd_download_date'] = 'current_timestamp';
			db_update('t_nigaoe_data', $rec, "nd_nigaoe_id=$nigaoe_id AND nd_status=" . STATUS_USER_NOTIFIED);
		}
	}
}

// Shift_JIS�ǽ���
mb_http_output('Shift_JIS');
ob_start('mb_output_handler');
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=Shift_JIS">
<title>������Factory</title>
</head>
<body>
<img src="<?=$url?>" alt="�ˤ�����">
</body>
</html>
