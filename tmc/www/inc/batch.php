<?
/******************************************************
' System :�֤���������� �������������ӥ��׻�̳���ѥڡ���
' Content:�Хå������Ѵؿ�
'******************************************************/

// �Хå����񤭹���
function batch_log($status, $file_name, $description) {
	if ($n = strrpos($file_name, '/'))
		$file_name = substr($file_name, $n + 1);

	$sql = sprintf("INSERT INTO t_batch_log (btl_status,btl_file_name,btl_description) VALUES (%s,%s,%s)",
			sql_number($status),
			sql_char($file_name),
			sql_char($description));
	db_exec($sql);
}

// ��ŵ�ư�����å�
function dup_check($key) {
	global $fp_lock;

	umask(0);
	$filename = "/tmp/$key";
	if (($fp_lock = fopen($filename, 'w')) == false)
		return false;

	return flock($fp_lock, LOCK_EX | LOCK_NB);
}
?>