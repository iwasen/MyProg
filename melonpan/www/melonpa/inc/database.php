<?php

//'=== データベースオープン ===
function db_open() {
	global  $gConn;

	if (!$gConn) {
		if (($host = $_ENV['MELONPAN_DB']) == '')
			$host = 'localhost';
		$gConn = pg_conxxxt("host=$host port=5432 dbname=melonpan user=postgres");
	}

	return $gConn;
}

// SQL文実行
function db_exec($sql) {
	return pg_exec(db_open(), $sql);
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
?>
