<?php
/**
 * ひとびと･net 共通ファイル
 *
 * DB接続設定
 *
 *
 * @package
 * @author
 * @version
 */

define('DBTRUE', 't');
define('DBFALSE', 'f');

// データベースオープン
function db_open() {
	global $g_conn, $g_db_err_msg;

	if (!$g_conn) {
		if (isset($_ENV['HITOBITO_DB_HOST']))
			$host = $_ENV['HITOBITO_DB_HOST'];
		else
			$host = 'localhost';

		if (isset($_ENV['HITOBITO_DB_NAME']))
			$dbname = $_ENV['HITOBITO_DB_NAME'];
		else
			$dbname = 'hitobito';

		$g_conn = pg_conxxxt("host=$host port=5432 dbname=$dbname user=postgres");

		if ($g_conn)
			$g_db_err_msg = '';
		else
			$g_db_err_msg = "Warning: pg_conxxxt() unable to conxxxt to PostgreSQL server: FATAL: Database \"$dbname\"";
	}

	return $g_conn;
}

// データベースクローズ
function db_close() {
	global $g_conn;

	if ($g_conn) {
		pg_close($g_conn);
		$g_conn = null;
	}
}

// SQL文実行
function db_exec($sql) {
	global $g_conn, $g_db_err_msg, $g_sql_debug;

	$result = pg_query(db_open(), $sql);
	if (!$result) {
		if ($_ENV['SQL_DEBUG'])
			echo '<font color="red">', htmlspecialchars($sql), '</font><br>';

		if ($g_db_err_msg == '')
			$g_db_err_msg = pg_last_error($g_conn);
	} elseif ($g_sql_debug)
		echo '<font color="blue">', htmlspecialchars($sql), '</font><br>';

	return $result;
}

// INSERT文実行
function db_insert($table, &$rec) {
	$cols = '';
	$vals = '';
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
	$set = '';
	foreach($rec as $col => $val) {
		if ($set != '')
			$set .= ',';

		$set .= "$col=$val";
	}
	$rec = array();

	return db_exec("UPDATE $table SET $set WHERE $where");
}

// キーが無ければINSERT、あればUPDATE文実行
function db_insert_update($table, &$rec, $key, $value) {
	$sql = "SELECT $key FROM $table WHERE $key=$value FOR UPDATE";
	$result = db_exec($sql);
	if (pg_num_rows($result) == 0) {
		$rec[$key] = $value;
		return db_insert($table, $rec);
	} else
		return db_update($table, $rec, "$key=$value");
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
		return "$data";
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

// 日付データ（パート別）
function sql_date2($data, $null='null') {
	if (isset($data['y'])) {
		if (isset($data['h']))
			return sprintf("'%04d-%02d-%02d %02d:%02d:%02d'", $data['y'], $data['m'], $data['d'], $data['h'], $data['n'], $data['s']);
		else
			return sprintf("'%04d-%02d-%02d'", $data['y'], $data['m'], $data['d']);
	} else
		return $null;
}

// BOOLデータ
function sql_bool($bool, $null='null') {
	if ($bool == DBTRUE || $bool == DBFALSE)
		return "'$bool'";
	else
		return $null;
}

// バイト配列データ
function sql_bytea($data, $null='null') {
	if ($data != '')
		return "'" . pg_escape_bytea($data) . "'";
	else
		return $null;
}

// 年月日時分秒取得
function get_datepart($date) {
	if (strlen($date) == 10)
		list($ymd['y'], $ymd['m'], $ymd['d']) = sscanf($date, "%d-%d-%d");
	else
		list($ymd['y'], $ymd['m'], $ymd['d'], $ymd['h'], $ymd['n'], $ymd['s']) = sscanf($date, "%d-%d-%d %d:%d:%d");

	return $ymd;
}

// UNIXタイムスタンプから日時フォーマット作成
function sql_unix_time($time) {
	$ary = getdate($time);
	return sql_date2(array('y' => $ary['year'], 'm' => $ary['mon'], 'd' => $ary['mday'], 'h' => $ary['hours'], 'n' => $ary['minutes'], 's' => $ary['seconds']));
}

// UNIXタイムスタンプから日付フォーマット作成
function sql_unix_date($time) {
	$ary = getdate($time);
	return sql_date2(array('y' => $ary['year'], 'm' => $ary['mon'], 'd' => $ary['mday']));
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

// AND条件結合
function and_join(&$where, $cond) {
	and_or_join($where, $cond, 'A');
}

// OR条件結合
function or_join(&$where, $cond) {
	and_or_join($where, $cond, 'O');
}

// AND/OR条件結合
function and_or_join(&$where, $cond, $and_or) {
	if ($cond != '') {
		if ($where != '')
			$where .= ($and_or == 'A') ? ' AND ' : ' OR ';

		$where .= $cond;
	}
}

// 直近のエラーメッセージ取得
function db_errormessage() {
	global	$g_db_err_msg;

	return trim($g_db_err_msg);
}

// バキューム
function db_vacuum($table, $full = false) {
	if ($full)
		db_exec("VACUUM FULL ANALYZE $table");
	else
		db_exec("VACUUM ANALYZE $table");
}
?>