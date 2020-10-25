<?
/******************************************************
' System :flette������
' Content:�ǡ����١����ؿ�
'******************************************************/

define('DBTRUE', 't');
define('DBFALSE', 'f');

// �ǡ����١��������ץ�
function db_open() {
	global $g_conn, $g_db_err;

	if (!$g_conn) {
		$g_conn = pg_conxxxt("host=localhost port=5432 dbname=flette user=flette password=password");

		$g_db_err = '';
	}

	return $g_conn;
}

// SQLʸ�¹�
function db_exec($sql) {
	global $g_conn, $g_db_err, $g_sql_debug;

	$result = pg_query(db_open(), $sql);
	if (!$result) {
//		if ($_ENV['SQL_DEBUG'])
			echo '<font color="red">', htmlspecialchars($sql), '</font><br>';
		$g_db_err = trim(pg_errormessage($g_conn));
	} elseif ($g_sql_debug)
		echo '<font color="blue">', htmlspecialchars($sql), '</font><br>';

	return $result;
}

// INSERTʸ�¹�
function db_insert($table, &$rec) {
	foreach($rec as $col => $val) {
		if ($cols != '')
			$cols .= ',';
		$cols .= $col;

		if ($vals != '')
			$vals .= ',';
		$vals .= $val;
	}
	$rec = array();

	return db_exec("INSERT INTO $table ($cols) VALUES ($vals)");
}

// UPDATEʸ�¹�
function db_update($table, &$rec, $where) {
	foreach($rec as $col => $val) {
		if ($set != '')
			$set .= ',';

		$set .= "$col=$val";
	}
	$rec = array();

	return db_exec("UPDATE $table SET $set WHERE $where");
}

// DELETEʸ�¹�
function db_delete($table, $where) {
	return db_exec("DELETE FROM $table WHERE $where");
}

// �ȥ�󥶥�����󳫻�
function db_begin_trans() {
	global $g_trans_count;

	if ($g_trans_count++ == 0)
		db_exec('begin');
}

// �ȥ�󥶥�����󥳥ߥå�
function db_commit_trans() {
	global $g_trans_count;

	if (--$g_trans_count == 0)
		db_exec('commit');
}

// �ȥ�󥶥���������Хå�
function db_rollback() {
	global $g_trans_count;

	if (--$g_trans_count == 0)
		db_exec('rollback');
}

// ʸ����ǡ���
function sql_char($data, $null='null') {
	if ($data != '') {
		$data = addslashes($data);
		return "'$data'";
 	} else
		return $null;
}

// ���ͥǡ���
function sql_number($data, $null='null') {
	$data = strval($data);
	if ($data != '' && is_numeric($data))
		return $data;
	else
		return $null;
}

// ���եǡ���
function sql_date($data, $null='null') {
	if ($data != '' && $data{0} != '/')
		return "'$data'";
	else
		return $null;
}

// BOOL�ǡ���
function sql_bool($bool) {
	return $bool ? 'true' : 'false';
}

// NULL�ǡ���
function sql_null() {
	return 'null';
}

// �����ȥ������󥹼���
function get_current_seq($table, $column) {
	$sql = "SELECT currval('{$table}_{$column}_seq')";
	$result = db_exec($sql);
	return pg_fetch_result($result, 0, 0);
}

// ���Υ������󥹼���
function get_next_seq($table, $column) {
	$sql = "SELECT nextval('{$table}_{$column}_seq')";
	$result = db_exec($sql);
	return pg_fetch_result($result, 0, 0);
}

// ���ǡ����ե��å�
function db_fetch1($sql, $def='') {
	$result = db_exec($sql);
	if (pg_num_rows($result))
		return pg_fetch_result($result, 0, 0);

	return $def;
}

// ľ��Υ��顼��å���������
function db_errormessage() {
	global	$g_db_err;

	return $g_db_err;
}
?>