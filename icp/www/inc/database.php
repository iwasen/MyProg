<?
/******************************************************
' System :ICP共通インクルードファイル
' Content:データベース関数
'******************************************************/

define('DBTRUE', 't');
define('DBFALSE', 'f');

// データベースオープン
function db_open() {
	global $g_conn, $g_db_err;

	if (!$g_conn) {
		$host = isset($_ENV['ICP_DB_HOST']) ? $_ENV['ICP_DB_HOST'] : 'localhost';
		$dbname = isset($_ENV['ICP_DB_NAME']) ? $_ENV['ICP_DB_NAME'] : 'icpdb';

		$g_conn = pg_conxxxt("host=$host port=5432 dbname=$dbname user=postgres");

		$g_db_err = '';
	}

	return $g_conn;
}

// SQL文実行
function db_exec($sql) {
	global $g_conn, $g_db_err, $g_sql_debug;

	$result = pg_query(db_open(), $sql);
	if (!$result) {
		if ($_ENV['SQL_DEBUG'])
			echo '<font color="red">', htmlspecialchars($sql), '</font><br>';
		$g_db_err = trim(pg_errormessage($g_conn));
	} elseif ($g_sql_debug)
		echo '<font color="blue">', htmlspecialchars($sql), '</font><br>';

	return $result;
}

// INSERT文実行
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

// UPDATE文実行
function db_update($table, &$rec, $where) {
	foreach($rec as $col => $val) {
		if ($set != '')
			$set .= ',';

		$set .= "$col=$val";
	}
	$rec = array();

	return db_exec("UPDATE $table SET $set WHERE $where");
}

// DELETE文実行
function db_delete($table, $where) {
	return db_exec("DELETE FROM $table WHERE $where");
}

// トランザクション開始
function db_begin_trans() {
	global $g_trans_count;

	if ($g_trans_count++ == 0)
		db_exec('begin');
}

// トランザクションコミット
function db_commit_trans() {
	global $g_trans_count;

	if (--$g_trans_count == 0)
		db_exec('commit');
}

// トランザクションロールバック
function db_rollback() {
	global $g_trans_count;

	if (--$g_trans_count == 0)
		db_exec('rollback');
}

// 文字列データ
function sql_char($data, $null='null') {
	if ($data != '') {
		$data = addslashes($data);
		return "'$data'";
 	} else
		return $null;
}

// 数値データ
function sql_number($data, $null='null') {
	$data = strval($data);
	if ($data != '')
		return $data;
	else
		return $null;
}

// 日付データ
function sql_date($data, $null='null') {
	if ($data != '' && $data{0} != '/')
		return "'$data'";
	else
		return $null;
}

// BOOLデータ
function sql_bool($bool) {
	return $bool ? 'true' : 'false';
}

// NULLデータ
function sql_null() {
	return 'null';
}

// SQL日時取得
function sql_datetime($check, $year, $month, $day, $hour = 0, $second = 0, $minute = 0) {
	if ($check)
		return "'$year/$month/$day $hour:$second:$minute'";
	else
		return 'null';
}

// システム情報取得
function get_system_info($key) {
	global $g_system_info;

	if ($g_system_info[$key] == '') {
		$sql = "SELECT sy_value FROM m_system WHERE sy_key='$key'";
		$result = db_exec($sql);
		$g_system_info[$key] = pg_fetch_result($result, 0, 0);
	}

	return $g_system_info[$key];
}

// カレントシーケンス取得
function get_current_seq($table, $column) {
	$sql = "SELECT currval('{$table}_{$column}_seq')";
	$result = db_exec($sql);
	return pg_fetch_result($result, 0, 0);
}

// 次のシーケンス取得
function get_next_seq($table, $column) {
	$sql = "SELECT nextval('{$table}_{$column}_seq')";
	$result = db_exec($sql);
	return pg_fetch_result($result, 0, 0);
}

// １データフェッチ
function db_fetch1($sql, $def='') {
	$result = db_exec($sql);
	if (pg_num_rows($result))
		return pg_fetch_result($result, 0, 0);

	return $def;
}

// 直近のエラーメッセージ取得
function db_errormessage() {
	global	$g_db_err;

	return $g_db_err;
}
?>