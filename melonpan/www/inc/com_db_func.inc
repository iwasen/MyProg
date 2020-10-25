<?php
include ("../inc/com_path.inc");
include ("$compath/sql_msm.inc");

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
//print $sql."<br>";
	return pg_exec(db_open(), $sql);
}

// 文字列データ
function sql_char($data) {
	if ($data != '')
		return "'$data'";
	else
		return 'null';
}

// 数値データ
function sql_number($data) {
	if ($data != '')
		return "$data";
	else
		return 'null';
}

// 日付データ
function sql_date($data) {
	if ($data != '')
		return "'$data'";
	else
		return 'null';
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

function update_squ_key ($table) {
	$i_ret = "-1";
	$getsql = "GET_SQU_" . $table;
	$putsql = "PUT_SQU_" . $table;
	$sql = msm_make_sql ($getsql, '');
	$result = db_exec($sql);
	$nrow = pg_numrows($result);
	if ($nrow == 1) {
		$fetch = pg_fetch_object($result, 0);
		$key_count = $fetch->key_count + 1;
		$len_max = $fetch->len_max;
		if (strlen( strval($key_count) ) > $len_max) {
			$key_count = 1;
		}
		$sql = msm_make_sql ($putsql, $key_count);
		if (db_exec($sql)) {
			$id_form = "%0" . strval($len_max) . "d";
			$i_ret = sprintf($id_form, $key_count);
		} else {
		}
	}
	return $i_ret;
}
/* **
function update_squ_key ($conn, $table) {
	$i_ret = "-1";
	$getsql = "GET_SQU_" . $table;
	$putsql = "PUT_SQU_" . $table;
	$sql = msm_make_sql ($getsql);
	$stmt = OCIParse($conn, $sql);
	$ret01 = OCIDefinebyname($stmt, "KEY_COUNT", &$key_count);
	$ret01 = OCIDefinebyname($stmt, "LEN_MAX", &$len_max);
	if (OCIExecute($stmt)) {
		if (OCIFetch ($stmt)) {
			$key_count += 1;
			if (strlen( strval($key_count) ) > $len_max) {
				$key_count = 1;
			}
			$sql = msm_make_sql ($putsql);
			$stmt = OCIParse($conn, $sql);
			$ret01 = OCIbindbyname ($stmt, ":KEY_COUNT", &$key_count, -1);
			if (OCIExecute($stmt, OCI_DEFAULT)) {
				$id_form = "%0" . strval($len_max) . "d";
				$i_ret = sprintf($id_form, $key_count);
			}
		}
	}
	return $i_ret;
}
*/
?>
