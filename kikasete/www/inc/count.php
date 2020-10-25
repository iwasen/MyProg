<?
/******************************************************
' System :きかせて・net共通
' Content:カウント値取得
'******************************************************/

// 有効モニター数取得
function count_monitor() {
	$gmo_count = counter_get(3, "SELECT COUNT(*) FROM g_monitor WHERE mn_status=0");
	return counter_get(1, "SELECT COUNT(*) FROM t_monitor WHERE mn_status=0") + $gmo_count;// + 467104;
}

// アフィリエイト応募数取得
function count_affiliate() {
	return counter_get(2, "SELECT COUNT(*) FROM t_affiliate");
}

// カウンタ値取得
function counter_get($sum_id, $sum_sql) {
	$sql = "SELECT su_sum_data FROM t_sum_data WHERE su_sum_id=$sum_id";
	$result = db_exec($sql);
	if (pg_numrows($result)) {
		$fetch = pg_fetch_row($result, 0);
		$num = $fetch[0];
	} else {
		$num = db_fetch1($sum_sql, 0);
		$sql = "INSERT INTO t_sum_data (su_sum_id,su_date,su_sum_data) VALUES ($sum_id,CURRENT_TIMESTAMP,$num)";
		db_exec($sql);
	}

	return $num;
}

// カウントアップ
function count_up($sum_id, $up = 1) {
	$sql = "UPDATE t_sum_data SET su_sum_data=su_sum_data+$up WHERE su_sum_id=$sum_id";
	db_exec($sql);
}
?>
