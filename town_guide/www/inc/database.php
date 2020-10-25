<?
/******************************************************
' System :タウンガイドWebアンケート
' Content:データベース処理
'******************************************************/

// データベースオープン
function db_open() {
	global $gConn, $gDbErrMsg;

	if (!$gConn) {
		if (($host = $_ENV['TOWN_GUIDE_DB_HOST']) == '')
			$host = 'localhost';

		if (($dbname = $_ENV['TOWN_GUIDE_DB_NAME']) == '')
			$dbname = 'town_guide';

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

	$result = pg_query(db_open(), $sql);
	if (!$result) {
		if ($_ENV['SQL_DEBUG'])
			echo '<font color="red">', htmlspecialchars($sql), '</font><br>';

		if ($gDbErrMsg == '')
			$gDbErrMsg = pg_last_error($gConn);
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

// BOOLデータ
function sql_bool($bool, $null='null') {
	if ($bool == 't' || $bool == 'f')
		return "'$bool'";
	else
		return $null;
}

// currval取得
function get_currval($seqtbl) {
	$sql = "SELECT currval('$seqtbl')";
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

// nextval取得
function get_nextval($seqtbl) {
	$sql = "SELECT nextval('$seqtbl')";
	$result = db_exec($sql);
	return pg_fetch_result($result, 0, 0);
}

// 直近のエラーメッセージ取得
function db_errormessage() {
	global	$gDbErrMsg;

	return trim($gDbErrMsg);
}
?>