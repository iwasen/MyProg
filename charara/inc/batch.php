<?
/******************************************************
' System :�֤�����Factory�ץ��󥯥롼�ɥե�����
' Content:�Хå������Ѵؿ�
'******************************************************/

// �Хå����񤭹���
function batch_log($status, $file_name, $description) {
	if ($n = strrpos($file_name, '/'))
		$file_name = substr($file_name, $n + 1);

	$sql = sprintf("INSERT INTO l_batch (lb_status,lb_date,lb_file_name,lb_description) VALUES (%s,current_timestamp,%s,%s)",
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