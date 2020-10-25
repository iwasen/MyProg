<?
/******************************************************
' System :�������ơ�net����
' Content:ICP�ǡ����١�������
'******************************************************/

// �ǡ����١��������ץ�
function icp_db_open() {
	global $g_icp_conn, $g_icp_db_err;

	if (!$g_icp_conn) {
		$host = isset($_ENV['ICP_DB_HOST']) ? $_ENV['ICP_DB_HOST'] : 'localhost';
		$dbname = isset($_ENV['ICP_DB_NAME']) ? $_ENV['ICP_DB_NAME'] : 'mlbbs';

		$g_icp_conn = pg_conxxxt("host=$host port=5432 dbname=$dbname user=postgres");

		if ($g_icp_conn)
			$g_icp_db_err = '';
		else
			$g_icp_db_err = "Warning: pg_conxxxt() unable to conxxxt to PostgreSQL server: FATAL: Database \"$dbname\"";
	}

	return $g_icp_conn;
}

// SQLʸ�¹�
function icp_db_exec($sql) {
	global $g_icp_conn, $g_icp_db_err;

	$result = pg_query(icp_db_open(), $sql);
	if (!$result) {
		if ($_ENV['SQL_DEBUG'])
			echo '<font color="red">', htmlspecialchars($sql), '</font><br>';

		if ($g_icp_db_err == '')
			$g_icp_db_err = trim(pg_errormessage($g_icp_conn));
	}

	return $result;
}

// INSERTʸ�¹�
function icp_db_insert($table, &$rec) {
	foreach($rec as $col => $val) {
		if ($cols != '')
			$cols .= ',';
		$cols .= $col;

		if ($vals != '')
			$vals .= ',';
		$vals .= $val;
	}
	$rec = array();

	return icp_db_exec("INSERT INTO $table ($cols) VALUES ($vals)");
}

// UPDATEʸ�¹�
function icp_db_update($table, &$rec, $where) {
	foreach($rec as $col => $val) {
		if ($set != '')
			$set .= ',';

		$set .= "$col=$val";
	}
	$rec = array();

	return icp_db_exec("UPDATE $table SET $set WHERE $where");
}

// DELETEʸ�¹�
function icp_db_delete($table, $where) {
	return icp_db_exec("DELETE FROM $table WHERE $where");
}

// �ȥ�󥶥�����󳫻�
function icp_db_begin_trans() {
	global $g_icp_trans_count;

	if ($g_icp_trans_count++ == 0)
		db_exec('begin');
}

// �ȥ�󥶥�����󥳥ߥå�
function icp_db_commit_trans() {
	global $g_icp_trans_count;

	if (--$g_icp_trans_count == 0)
		icp_db_exec('commit');
}

// �ȥ�󥶥���������Хå�
function icp_db_rollback() {
	global $g_icp_trans_count;

	if (--$g_icp_trans_count == 0)
		icp_db_exec('rollback');
}

// ���ǡ����ե��å�
function icp_db_fetch1($sql, $def='') {
	$result = icp_db_exec($sql);
	if (pg_num_rows($result))
		return pg_fetch_result($result, 0, 0);

	return $def;
}

// ľ��Υ��顼��å���������
function icp_db_errormessage() {
	global	$g_icp_db_err;

	return $g_icp_db_err;
}

// �����ȥ������󥹼���
function icp_get_current_seq($table, $column) {
	$sql = "SELECT currval('{$table}_{$column}_seq')";
	$result = icp_db_exec($sql);
	return pg_fetch_result($result, 0, 0);
}

// �����ƥ�������
function icp_get_system_info($key) {
	global $g_icp_system_info;

	if ($g_icp_system_info[$key] == '') {
		$sql = "SELECT sy_value FROM m_system WHERE sy_key='$key'";
		$result = icp_db_exec($sql);
		$g_icp_system_info[$key] = pg_fetch_result($result, 0, 0);
	}

	return $g_icp_system_info[$key];
}
?>
