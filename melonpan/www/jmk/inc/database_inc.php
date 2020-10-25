<?
// データベースオープン
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

// SQL日時取得
function sql_datetime($check, $year, $month, $day, $hour = 0, $second = 0, $minute = 0) {
	if ($check)
		return "'$year/$month/$day $hour:$second:$minute'";
	else
		return 'null';
}

// システム情報取得
function get_system_info($column = '*') {
	$sql = "SELECT $column FROM t_system WHERE sy_system_id='01'";
	$result = db_exec($sql);
	if (pg_numrows($result))
		return pg_fetch_object($result, 0);
}

function msm_make_sql ($make_type, $key_count) {
	/* MAKE SQL */
	switch ($make_type) {
		case "GET_SQU_M_PUBLISHER":
			$sql = "SELECT * from M_SQU_MGR WHERE table_id = '0101' for update";
			break;
		case "PUT_SQU_M_PUBLISHER":
			$sql = "UPDATE  M_SQU_MGR SET key_count = " . $key_count . " WHERE table_id = '0101'";
			break;
		case "GET_SQU_M_MAGAZINE":
			$sql = "SELECT * from M_SQU_MGR WHERE table_id = '0102' for update";
			break;
		case "PUT_SQU_M_MAGAZINE":
			$sql = "UPDATE M_SQU_MGR SET key_count = " . $key_count . " WHERE table_id = '0102'";
			break;
		case "GET_SQU_T_BODY":
			$sql = "SELECT * from M_SQU_MGR WHERE table_id = '0103' for update";
			break;
		case "PUT_SQU_T_BODY":
			$sql = "UPDATE M_SQU_MGR SET key_count = " . $key_count . " WHERE table_id = '0103'";
			break;
		case "GET_SQU_T_PUB_DELREASON":
			$sql = "SELECT * from M_SQU_MGR WHERE table_id = '0104' for update";
			break;
		case "PUT_SQU_T_PUB_DELREASON":
			$sql = "UPDATE M_SQU_MGR SET key_count = " . $key_count . " WHERE table_id = '0104'";
			break;
		case "GET_SQU_T_MAG_DELREASON":
			$sql = "SELECT * from M_SQU_MGR WHERE table_id = '0105' for update";
			break;
		case "PUT_SQU_T_MAG_DELREASON":
			$sql = "UPDATE M_SQU_MGR SET key_count = " . $key_count . " WHERE table_id = '0105'";
			break;
		case "GET_SQU_T_MAG_STOPREASON":
			$sql = "SELECT * from M_SQU_MGR WHERE table_id = '0106' for update";
			break;
		case "PUT_SQU_T_MAG_STOPREASON":
			$sql = "UPDATE M_SQU_MGR SET key_count = " . $key_count . " WHERE table_id = '0106'";
			break;
		case "GET_SQU_M_MELONPA":
			$sql = "SELECT * from M_SQU_MGR WHERE table_id = '0201' for update";
			break;
		case "GET_SQU_T_WHATS_NEW":
			$sql = "SELECT * from M_SQU_MGR WHERE table_id = '0302' for update";
			break;
		case "GET_SQU_T_RIBA_SEND":
			$sql = "SELECT * from M_SQU_MGR WHERE table_id = '040'2 for update";
			break;
		case "PUT_SQU_M_MELONPA":
			$sql = "UPDATE  M_SQU_MGR SET key_count = " . $key_count . " WHERE table_id = '0201'";
			break;
		case "GET_SQU_T_MAILADDR":
			$sql = "SELECT * from M_SQU_MGR WHERE table_id = '0202' for update";
			break;
		case "PUT_SQU_T_MAILADDR":
			$sql = "UPDATE  M_SQU_MGR SET key_count = " . $key_count . " WHERE table_id = '0202'";
			break;
		case "GET_SQU_T_DELREASON":
			$sql = "SELECT * from M_SQU_MGR WHERE table_id = '0203' for update";
			break;
		case "PUT_SQU_T_DELREASON":
			$sql = "UPDATE  M_SQU_MGR SET key_count = " . $key_count . " WHERE table_id = '0203'";
			break;
		case "GET_SQU_T_RECOMEND":
			$sql = "SELECT * from M_SQU_MGR WHERE table_id = '0204' for update";
			break;
		case "PUT_SQU_T_RECOMEND":
			$sql = "UPDATE  M_SQU_MGR SET key_count = " . $key_count . " WHERE table_id = '0204'";
			break;
		case "GET_SQU_T_ADMIN_MSG":
			$sql = "SELECT * from M_SQU_MGR WHERE table_id = '0301' for update";
			break;
		case "PUT_SQU_T_ADMIN_MSG":
			$sql = "UPDATE  M_SQU_MGR SET key_count = " . $key_count . " WHERE table_id = '0301'";
			break;
		case "GET_SQU_T_CHOICE":
			$sql = "SELECT * from M_SQU_MGR WHERE table_id = '0401' for update";
			break;
		case "PUT_SQU_T_CHOICE":
			$sql = "UPDATE  M_SQU_MGR SET key_count = " . $key_count . " WHERE table_id = '0401'";
			break;
		case "PUT_SQU_T_WHATS_NEW":
			$sql = "UPDATE  M_SQU_MGR SET key_count = " . $key_count . " WHERE table_id = '0302'";
			break;
		case "PUT_SQU_T_RIBA_SEND":
			$sql = "UPDATE  M_SQU_MGR SET key_count = " . $key_count . " WHERE table_id = '0402'";
			break;
		case "PUT_SQU_T_MAG_FOOTER":
			$sql = "UPDATE  M_SQU_MGR SET key_count = " . $key_count . " WHERE table_id = '0403'";
			break;
		case "GET_SQU_T_MAG_FOOTER":
			$sql = "SELECT * from M_SQU_MGR WHERE table_id = '0403' for update";
			break;
		case "PUT_SQU_L_MELONPAI_LOG":
			$sql = "UPDATE  M_SQU_MGR SET key_count = " . $key_count . " WHERE table_id = '0501'";
			break;
		case "GET_SQU_L_MELONPAI_LOG":
			$sql = "SELECT * from M_SQU_MGR WHERE table_id = '0501' for update";
			break;
		case "PUT_SQU_M_BK_CATEGORY":
			$sql = "UPDATE  M_SQU_MGR SET key_count = " . $key_count . " WHERE table_id = '0502'";
			break;
		case "GET_SQU_M_BK_CATEGORY":
			$sql = "SELECT * from M_SQU_MGR WHERE table_id = '0502' for update";
			break;
		case "PUT_SQU_T_BOOKMARK":
			$sql = "UPDATE  M_SQU_MGR SET key_count = " . $key_count . " WHERE table_id = '0503'";
			break;
		case "GET_SQU_T_BOOKMARK":
			$sql = "SELECT * from M_SQU_MGR WHERE table_id = '0503' for update";
			break;
		case "PUT_SQU_M_RECOM_TXT":
			$sql = "UPDATE  M_SQU_MGR SET key_count = " . $key_count . " WHERE table_id = '0504'";
			break;
		case "GET_SQU_M_RECOM_TXT":
			$sql = "SELECT * from M_SQU_MGR WHERE table_id = '0504' for update";
			break;
		case "PUT_SQU_M_MELONPAI":
			$sql = "UPDATE M_SQU_MGR SET key_count = " . $key_count . " WHERE table_id = '0505'";
			break;
		case "GET_SQU_M_MELONPAI":
			$sql = "SELECT * from M_SQU_MGR WHERE table_id = '0505' for update";
			break;
		case "PUT_SQU_T_POINT_DETAIL":
			$sql = "UPDATE M_SQU_MGR SET key_count = " . $key_count . " WHERE table_id = '0601'";
			break;
		case "GET_SQU_T_POINT_DETAIL":
			$sql = "SELECT * from M_SQU_MGR WHERE table_id = '0601' for update";
			break;
		case "PUT_SQU_T_RESERVE_PT_DETAIL":
			$sql = "UPDATE M_SQU_MGR SET key_count = " . $key_count . " WHERE table_id = '0601'";
			break;
		case "GET_SQU_T_RESERVE_PT_DETAIL":
			$sql = "SELECT * from M_SQU_MGR WHERE table_id = '0601' for update";
			break;
		default:
			break;
	}
	return $sql;
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
?>