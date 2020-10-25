<?
/******************************************************
' System :きかせて・netバッチ処理
' Content:バッチログ書き込み処理
'******************************************************/

// バッチログ書き込み
function batch_log($status, $file_name, $description) {
	if ($n = strrpos($file_name, '/'))
		$file_name = substr($file_name, $n + 1);

	$sql = "INSERT INTO l_batch (lb_status,lb_date,lb_file_name,lb_description) VALUES (" .
					sql_number($status) . "," .
					"current_timestamp," .
					sql_char($file_name) . "," .
					sql_char($description) . ")";
	db_exec($sql);
}

// 二重起動チェック
function dup_check($key) {
	global $fp_lock;

	umask(0);
	$filename = "/tmp/$key";
	if (($fp_lock = fopen($filename, 'w')) == false)
		return false;
	return flock($fp_lock, LOCK_EX | LOCK_NB);
}
?>