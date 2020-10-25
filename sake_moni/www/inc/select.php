<?
/******************************************************
' System :お酒と買物のアンケートモニター共通
' Content:選択肢表示処理
'******************************************************/

// 年
function select_year($start_year, $default, $selected) {
	if ($default)
		echo "<option value=''>$default</option>\n";

	$end_year = date('Y') + 4;
	for ($i = $start_year; $i < $end_year; $i++) {
		echo '<option ', value_selected($i, $selected), ">$i</option>\n";
	}
}

// 月
function select_month($default, $selected) {
	if ($default)
		echo "<option value=''>$default</option>\n";

	for ($i = 1; $i <= 12; $i++)
		echo '<option ', value_selected($i, $selected), ">$i</option>\n";
}

// 日
function select_day($default, $selected) {
	if ($default)
		echo "<option value=''>$default</option>\n";

	for ($i = 1; $i <= 31; $i++)
		echo '<option ', value_selected($i, $selected), ">$i</option>\n";
}

// 時間
function select_hour($default, $selected) {
	if ($default)
		echo "<option value=''>$default</option>\n";

	for ($i = 0; $i < 24; $i++) {
		if ($i < 12)
			echo '<option ', value_selected($i, $selected), ">AM $i</option>\n";
		else
			echo '<option ', value_selected($i, $selected), ">PM ", $i - 12, "</option>\n";
	}
}

// 表示行数
function select_displine($selected) {
	echo '<option ', value_selected(0, $selected), ">- 全て -</option>\n";
	for ($i = 10; $i <= 100; $i += 10)
		echo '<option ', value_selected($i, $selected), ">$i</option>\n";
}

// モニター種別
function select_net_kind($default, $selected) {
	if ($default)
		echo "<option value=''>$default</option>\n";

	echo '<option ', value_selected('1', $selected), '>きかせて・net</option>', "\n";
	echo '<option ', value_selected('2', $selected), '>iMiネット</option>', "\n";
}

// モニター状態選択肢
function select_monitor_status($sel) {
	echo "<option ", value_selected('0', $sel), ">有効</option>";
	echo "<option ", value_selected('2', $sel), ">不達</option>";
	echo "<option ", value_selected('9', $sel), ">退会</option>";
}

// スタッフ状態選択肢
function select_staff_status($sel) {
	echo "<option ", value_selected('0', $sel), ">有効</option>";
	echo "<option ", value_selected('2', $sel), ">不達</option>";
	echo "<option ", value_selected('9', $sel), ">退会</option>";
}

// 地域
function select_area($default, $selected) {
	if ($default)
		echo "<option value=''>$default</option>\n";

	$sql = "SELECT ar_area_cd,ar_area_name FROM m_area ORDER BY ar_area_cd";
	$result = db_exec($sql);
	$nrow = pg_num_rows($result);
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);
		echo '<option ', value_selected($fetch->ar_area_cd, $selected), '>', htmlspecialchars($fetch->ar_area_name), '</option>', "\n";
	}
}

// 職業
function select_shokugyou($default, $selected) {
	if ($default)
		echo "<option value=''>$default</option>\n";

	$sql = "SELECT sg_shokugyou_cd,sg_shokugyou_name FROM m_shokugyou ORDER BY sg_order";
	$result = db_exec($sql);
	$nrow = pg_num_rows($result);
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);
		echo '<option ', value_selected($fetch->sg_shokugyou_cd, $selected), '>', htmlspecialchars($fetch->sg_shokugyou_name), '</option>', "\n";
	}
}

// 業種
function select_gyoushu($default, $selected, $type_cd) {
	if ($default)
		echo "<option value=''>$default</option>\n";

	$sql = "SELECT gs_gyoushu_cd,gs_gyoushu_name FROM m_gyoushu WHERE gs_type_cd=$type_cd ORDER BY gs_order";
	$result = db_exec($sql);
	$nrow = pg_num_rows($result);
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);
		echo '<option ', value_selected($fetch->gs_gyoushu_cd, $selected), '>', htmlspecialchars($fetch->gs_gyoushu_name), '</option>', "\n";
	}
}

// 職種
function select_shokushu($default, $selected) {
	if ($default)
		echo "<option value=''>$default</option>\n";

	$sql = "SELECT ss_shokushu_cd,ss_shokushu_name FROM m_shokushu ORDER BY ss_order";
	$result = db_exec($sql);
	$nrow = pg_num_rows($result);
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);
		echo '<option ', value_selected($fetch->ss_shokushu_cd, $selected), '>', htmlspecialchars($fetch->ss_shokushu_name), '</option>', "\n";
	}
}

// スタッフ
function select_staff($default, $selected) {
	if ($default)
		echo "<option value=''>$default</option>\n";

	$sql = "SELECT st_staff_id,st_name1,st_name2 FROM t_staff WHERE st_status<>9 ORDER BY st_staff_id";
	$result = db_exec($sql);
	$nrow = pg_num_rows($result);
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);
		echo '<option ', value_selected($fetch->st_staff_id, $selected), '>', htmlspecialchars("$fetch->st_name1 $fetch->st_name2 ($fetch->st_staff_id)"), '</option>', "\n";
	}
}

// チェーン
function select_chain($default, $selected) {
	if ($default)
		echo "<option value=''>$default</option>\n";

	$sql = "SELECT ch_chain_cd,ch_name FROM m_chain WHERE ch_status=0 ORDER BY ch_order";
	$result = db_exec($sql);
	$nrow = pg_num_rows($result);
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);
		echo '<option ', value_selected($fetch->ch_chain_cd, $selected), '>', htmlspecialchars($fetch->ch_name), '</option>', "\n";
	}
}

// 業態
function select_gyoutai($default, $selected) {
	if ($default)
		echo "<option value=''>$default</option>\n";

	$sql = "SELECT gt_gyoutai_cd,gt_gyoutai_name FROM m_gyoutai ORDER BY gt_order";
	$result = db_exec($sql);
	$nrow = pg_num_rows($result);
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);
		echo '<option ', value_selected($fetch->gt_gyoutai_cd, $selected), '>', htmlspecialchars($fetch->gt_gyoutai_name), '</option>', "\n";
	}
}

?>