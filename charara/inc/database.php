<?
/******************************************************
' System :「きゃららFactory」インクルードファイル
' Content:データベース関数
'******************************************************/

define('DBTRUE', 't');
define('DBFALSE', 'f');
define('BACKUPFAIL', '/tmp/pg_fail_backup');

// データベースオープン
function db_open() {
	global $g_conn, $g_db_err;

	if (!$g_conn) {
		if (($host = $_ENV['CHARARA_DB_HOST']) == '')
			$host = 'localhost';

		if (($dbname = $_ENV['CHARARA_DB_NAME']) == '')
			$dbname = 'charara';

		$g_conn = pg_conxxxt("host=$host port=5432 dbname=$dbname user=postgres");

		$g_db_err = '';
	}

	return $g_conn;
}

// バックアップデータベースオープン
function db_open_backup() {
	global $g_conn_backup;

	if (!$g_conn_backup) {
		if (($host = $_ENV['CHARARA_DB_HOST_BACKUP']) == '')
			return;

		if (($dbname = $_ENV['CHARARA_DB_NAME_BACKUP']) == '')
			$dbname = 'charara';

		$conn_str = "host=$host port=5432 dbname=$dbname user=postgres";
		$g_conn_backup = pg_conxxxt($conn_str);
		if (!$g_conn_backup)
			fail_backup("DB Open Error: $conn_str");
	}

	return $g_conn_backup;
}

// SQL文実行
function db_exec($sql, $backup = false) {
	global $g_conn, $g_db_err, $g_sql_debug;

	$result = pg_query(db_open(), $sql);
	if (!$result) {
		if ($_ENV['SQL_DEBUG'])
			echo '<font color="red">', htmlspecialchars($sql), '</font><br>';
		$g_db_err = pg_errormessage($g_conn);
	} else {
		if ($g_sql_debug)
			echo '<font color="blue">', htmlspecialchars($sql), '</font><br>';

		if (strtoupper(substr($sql, 0, 6)) != "SELECT" || $backup) {
			if (!file_exists(BACKUPFAIL)) {
				$conn_backup = db_open_backup();
				if ($conn_backup) {
					if (!pg_query($conn_backup, $sql)) {
						$err_msg = pg_errormessage($conn_backup);
						fail_backup("DB Exec Error: $err_msg\nSQL :$sql");
					}
				}
			}
		}
	}

	return $result;
}

// バックアップサーバエラー処理
function fail_backup($msg) {
	// バックアップ障害発生ファイル生成
	umask(0);
	touch(BACKUPFAIL);

	// メールで通知
	$to = get_system_info('error_notice_mail');
	if ($to)
		mb_send_mail ($to, 'きゃららＤＢ障害', $msg); 

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
function sql_bool($bool) {
	return $bool ? 'true' : 'false';
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
	$sql = "SELECT sy_value FROM m_system WHERE sy_key='$key'";
	$result = db_exec($sql);
	return pg_fetch_result($result, 0, 0);
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
	$result = db_exec($sql, true);
	return pg_fetch_result($result, 0, 0);
}

// AND条件結合
function and_join(&$where, $cond) {
	if ($where != '')
		$where .= ' AND ';

	$where .= $cond;
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

	return trim($g_db_err);
}

// VACUUM実行
function db_vacuum($table, $analyze = false) {
	if ($analyze)
		$sql = "VACUUM ANALYZE $table";
	else
		$sql = "VACUUM $table";

	db_exec($sql);
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

// 日付の計算
function add_date($date, $y, $m, $d) {
	sscanf($date, "%d-%d-%d", &$year, &$month, &$day);
	return date('Y-m-d', mktime(0, 0, 0, $month + $m, $day + $d, $year + $y));
}

// 日時の計算
function add_datetime($date, $y, $m, $d, $h, $m, $s) {
	sscanf($date, "%d-%d-%d %d:%d", &$year, &$month, &$day, &$hour, &$minute);
	return date('Y-m-d H:i:s', mktime($hour + $h, $minute + $m, $second + $s, $month + $m, $day + $d, $year + $y));
}
?>