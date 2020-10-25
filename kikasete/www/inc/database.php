<?
/******************************************************
' System :きかせて・net共通
' Content:データベース処理
'******************************************************/

define('DBTRUE', 't');
define('DBFALSE', 'f');

// データベースオープン
function db_open() {
	global $gConn, $gDbErrMsg;

	if (!$gConn) {
		if (($host = $_ENV['KIKASETE_DB_HOST']) == '')
			$host = '211.133.248.151';

		if (($dbname = $_ENV['KIKASETE_DB_NAME']) == '')
			$dbname = 'kikasete';

		$gConn = pg_conxxxt("host=$host port=5432 dbname=$dbname user=postgres");

		if ($gConn)
			$gDbErrMsg = '';
		else
			$gDbErrMsg = "Warning: pg_conxxxt() unable to conxxxt to PostgreSQL server: FATAL: Database \"$dbname\"";
	}

	return $gConn;
}

// SQL文実行
function db_exec($sql) {
	global $gConn, $gDbErrMsg, $g_sql_debug;
	$result = pg_exec(db_open(), $sql);
	if (!$result) {
//		mb_language("japanese");
//		mb_send_mail("hirotaka-n@xxxxxxx.co.jp", "kikasete_DB_error", $sql, "From: hirotaka-n@xxxxxxx.co.jp", "hirotaka-n@xxxxxxx.co.jp");
		if ($_ENV['SQL_DEBUG'])
			echo '<font color="red">', htmlspecialchars($sql), '</font><br>';

		if ($gDbErrMsg == '')
			$gDbErrMsg = pg_errormessage($gConn);
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
	global $trans_count;

	if ($trans_count++ == 0)
		db_exec('begin');
}

// トランザクションコミット
function db_commit_trans() {
	global $trans_count;

	if (--$trans_count == 0)
		db_exec('commit');
}

// トランザクションロールバック
function db_rollback() {
	global $trans_count;

	if (--$trans_count == 0)
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
function sql_date2($year, $month, $day, $hour=0, $minute=0, $second=0) {
	if ($year != '' && $month != '' && $day != '') {
		if ($hour == 0 && $minute == 0 && $second == 0)
			return "'$year/$month/$day'";
		else
			return "'$year/$month/$day $hour:$minute:$second'";
	} else
		return 'null';
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

// SQL日時取得
function sql_datetime($check, $year, $month, $day, $hour = 0, $second = 0, $minute = 0) {
	if ($check)
		return "'$year/$month/$day $hour:$second:$minute'";
	else
		return 'null';
}

// 年月日時分秒取得
function get_datepart($part, $date) {
	if (strlen($date) == 10)
		sscanf($date, "%d-%d-%d", &$year, &$month, &$day);
	else
		sscanf($date, "%d-%d-%d %d:%d:%d", &$year, &$month, &$day, &$hour, &$minute, &$second);

	switch ($part) {
	case 'Y':
		return $year;
	case 'M':
		return $month;
	case 'D':
		return $day;
	case 'h':
		return $hour;
	case 'm':
		return $minute;
	case 's':
		return $second;
	}
}

// UNIXタイムスタンプから日時フォーマット作成
function sql_unix_time($time) {
	$ary = getdate($time);
	return sql_date2($ary['year'], $ary['mon'], $ary['mday'], $ary['hours'], $ary['minutes'], $ary['seconds']);
}

// UNIXタイムスタンプから日付フォーマット作成
function sql_unix_date($time) {
	$ary = getdate($time);
	return sql_date2($ary['year'], $ary['mon'], $ary['mday']);
}

// currval取得
function get_currval($seqtbl) {
	$sql = "SELECT currval('$seqtbl')";
	$result = db_exec($sql);
	return pg_fetch_result($result, 0, 0);
}

// nextval取得
function get_nextval($seqtbl) {
	$sql = "SELECT nextval('$seqtbl')";
	$result = db_exec($sql);
	return pg_fetch_result($result, 0, 0);
}

// システム情報取得
function get_system_info($column = '*') {
	$sql = "SELECT $column FROM m_system WHERE sy_system_id=1";
	$result = db_exec($sql);
	if (pg_numrows($result))
		return pg_fetch_object($result, 0);
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
	global	$gDbErrMsg;

	return trim($gDbErrMsg);
}

// バキューム
function db_vacuum($table) {
	db_exec("VACUUM $table");
}
?>
