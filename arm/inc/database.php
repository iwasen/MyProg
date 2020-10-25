<?
/******************************************************
* system  : xxx リスク診断
* title   : データベース処理
* content : データベースにアクセスするための関数群
* version : 1.0
* author  : aizawa
*******************************************************/

define('DBTRUE', 't');
define('DBFALSE', 'f');

// ■データベースオープン
function db_open() {
	global $g_conn;

	if (!$g_conn) {
		include('db_param.php');

		$g_conn = pg_conxxxt("host=$host dbname=$dbname user=$user password=$password");
	}

	return $g_conn;
}

// ■SQL文実行
function db_exec($sql) {
	global $g_conn;

	$result = pg_query(db_open(), $sql);
	if (!$result) {
		if (isset($g_debug))
			echo '<font color="red">', htmlspecialchars($sql), '</font><br>';
	}

	return $result;
}

// ■INSERT文実行
function db_insert($table, &$rec) {
	$cols = join(',', array_keys($rec));
	$vals = join(',', $rec);

	$rec = array();

	return db_exec("INSERT INTO $table ($cols) VALUES ($vals)");
}

// ■UPDATE文実行
function db_update($table, &$rec, $where) {
	foreach($rec as $col => $val)
		$set[] = "$col=$val";
	$set = join(',', $set);

	$rec = array();

	return db_exec("UPDATE $table SET $set WHERE $where");
}

// ■DELETE文実行
function db_delete($table, $where) {
	return db_exec("DELETE FROM $table WHERE $where");
}

// ■トランザクション開始
function db_begin_trans() {
	global $g_trans_count;

	if ($g_trans_count++ == 0)
		db_exec('begin');
}

// ■トランザクションコミット
function db_commit_trans() {
	global $g_trans_count;

	if (--$g_trans_count == 0)
		db_exec('commit');
}

// ■トランザクションロールバック
function db_rollback() {
	global $g_trans_count;

	if (--$g_trans_count == 0)
		db_exec('rollback');
}

// ■文字列データ
function sql_char($data, $null='null') {
	if ($data != '') {
		$data = addslashes($data);
		return "'$data'";
 	} else
		return $null;
}

// ■数値データ
function sql_number($data, $null='null') {
	$data = strval($data);
	if ($data != '')
		return $data;
	else
		return $null;
}

// ■日付データ
function sql_date($data, $null='null') {
	if ($data != '' && $data{0} != '/')
		return "'$data'";
	else
		return $null;
}

// ■BOOLデータ
function sql_bool($bool) {
	return $bool ? 'true' : 'false';
}

// ■１データフェッチ
function db_fetch1($sql, $def='') {
	$result = db_exec($sql);
	if (pg_num_rows($result))
		return pg_fetch_result($result, 0, 0);

	return $def;
}
?>