<?
/******************************************************
' System :きかせて・netモニターＭｙページ
' Content:ポイント関連処理
'******************************************************/

// ポイキャラ画像ファイル取得
function get_chara_image($monitor_id) {
	$sql = "SELECT mp_total_point,mp_chara_type,mp_enq_history"
			. " FROM t_monitor_point"
			. " WHERE mp_monitor_id=$monitor_id";
	$result = db_exec($sql);
	if (pg_numrows($result)) {
		$fetch = pg_fetch_object($result, 0);
		$chara_type = $fetch->mp_chara_type;
		if ($chara_type != '') {
			$chara_state = get_chara_state($fetch->mp_total_point);
			$chara_condition = get_chara_condition($fetch->mp_enq_history, $text);
		}
	}
	return get_chara_image_file($chara_type, $chara_state, $chara_condition);
}

// ポイキャラ画像ファイル名取得
function get_chara_image_file($chara_type, $chara_state, $chara_condition) {
	if ($chara_type != '')
		return sprintf("%02d%02d%02d.gif", $chara_type - 1, $chara_state - 1, $chara_condition - 1);
	else
		return "999999.gif";
}

// ポイキャラ成長度取得
function get_chara_state($total_point) {
	$sql = "SELECT ct_state_cd FROM m_chara_state WHERE ct_point>=$total_point ORDER BY ct_point LIMIT 1";
	$result = db_exec($sql);
	if (pg_numrows($result)) {
		$fetch = pg_fetch_object($result, 0);
		return $fetch->ct_state_cd;
	}
}

// ポイキャラ状態取得
function get_chara_condition($enq_history, &$name) {
	$len = strlen($enq_history);
	$h0 = $h1 = 0;
	for ($i = 0; $i < $len; $i++) {
		if ($enq_history{$i} == '1')
			$h1++;
		else
			$h0++;
	}
	$percent = floor(($h1 - $h0 + 10) * 5);
	$sql = "SELECT co_condition_cd,co_name FROM m_chara_condition WHERE co_percent<=$percent ORDER BY co_percent DESC LIMIT 1";
	$result = db_exec($sql);
	if (pg_numrows($result)) {
		$fetch = pg_fetch_object($result, 0);
		$condition = $fetch->co_condition_cd;
		$name = $fetch->co_name;
	}

	return $condition;
}
?>