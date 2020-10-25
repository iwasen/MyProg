<?
/******************************************************
' System :きかせて・net共通
' Content:選択肢表示処理
'******************************************************/

// 年
function select_year($start_year, $default = '', $selected = '', $end_year = '') {
	if ($default)
		echo "<option value=''>$default</option>\n";

	if (!$end_year)
		$end_year = date('Y') + 4;

	for ($i = $start_year; $i <= $end_year; $i++) {
		echo '<option ', value_selected($i, $selected), ">$i</option>\n";
	}
}

// 月
function select_month($default = '', $selected = '') {
	if ($default)
		echo "<option value=''>$default</option>\n";

	for ($i = 1; $i <= 12; $i++)
		echo '<option ', value_selected($i, $selected), ">$i</option>\n";
}

// 日
function select_day($default = '', $selected = '') {
	if ($default)
		echo "<option value=''>$default</option>\n";

	for ($i = 1; $i <= 31; $i++)
		echo '<option ', value_selected($i, $selected), ">$i</option>\n";
}

// 時間
function select_hour($default = '', $selected = '') {
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
function select_displine($selected = '') {
	echo '<option ', value_selected(0, $selected), ">- 全て -</option>\n";
	for ($i = 10; $i <= 100; $i += 10)
		echo '<option ', value_selected($i, $selected), ">$i</option>\n";
}

// モニター種別
function select_monitor_type($default = '', $selected = '') {
	if ($default)
		echo "<option value=''>$default</option>\n";

	echo '<option ', value_selected('1', $selected), '>モニター</option>', "\n";
	echo '<option ', value_selected('2', $selected), '>リサーチパートナー</option>', "\n";
}

// モニター状態選択肢
function select_monitor_status($default = '', $selected = '') {
	if ($default)
		echo "<option value=''>$default</option>\n";

	echo "<option ", value_selected('0', $selected), ">有効</option>";
	echo "<option ", value_selected('2', $selected), ">不達</option>";
	echo "<option ", value_selected('9', $selected), ">退会</option>";
}

// マーケター種別
function select_marketer_type($default = '', $selected = '') {
	if ($default)
		echo "<option value=''>$default</option>\n";

	echo '<option ', value_selected('1', $selected), '>メールニュース登録</option>', "\n";
	// echo '<option ', value_selected('2', $selected), '>無料登録</option>', "\n";
	echo '<option ', value_selected('3', $selected), '>Myページ登録</option>', "\n";
	echo '<option ', value_selected('4', $selected), '>アシスタント</option>', "\n";
}

// マーケター状態選択肢
function select_marketer_status($sel) {
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
	$nrow = pg_numrows($result);
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
	$nrow = pg_numrows($result);
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
	$nrow = pg_numrows($result);
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
	$nrow = pg_numrows($result);
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);
		echo '<option ', value_selected($fetch->ss_shokushu_cd, $selected), '>', htmlspecialchars($fetch->ss_shokushu_name), '</option>', "\n";
	}
}

// ブラックリストフラグ
function select_blacklist($default, $selected) {
	if ($default)
		echo "<option value=''>$default</option>\n";

	$sql = "SELECT bl_blacklist_flg,bl_name FROM m_blacklist ORDER BY bl_blacklist_flg";
	$result = db_exec($sql);
	$nrow = pg_numrows($result);
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);
		echo '<option ', value_selected($fetch->bl_blacklist_flg, $selected), '>', htmlspecialchars($fetch->bl_name), '</option>', "\n";
	}
}

// エージェント
function select_agent($default, $selected, $type) {
	if ($default)
		echo "<option value=''>$default</option>\n";

	$sql = "SELECT ag_agent_id,ag_name1,ag_name2,ag_mail_addr FROM t_agent WHERE ag_type=$type ORDER BY ag_agent_id";
	$result = db_exec($sql);
	$nrow = pg_numrows($result);
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);
		echo '<option ', value_selected($fetch->ag_agent_id, $selected), '>', htmlspecialchars("$fetch->ag_name1 $fetch->ag_name2 <$fetch->ag_mail_addr>"), '</option>', "\n";
	}
}

// マーケター
function select_marketer($default, $selected, $type, $agent_id = null) {
	if ($default)
		echo "<option value=''>$default</option>\n";

	$where = "WHERE mr_type=$type AND mr_status<>9";
	if ($agent_id)
		$where .= " AND mr_agent_id=$agent_id";

	$sql = "SELECT mr_marketer_id,mr_name1,mr_name2 FROM t_marketer $where ORDER BY mr_marketer_id";
	$result = db_exec($sql);
	$nrow = pg_numrows($result);
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);
		echo '<option ', value_selected($fetch->mr_marketer_id, $selected), '>', htmlspecialchars("$fetch->mr_name1 $fetch->mr_name2 ($fetch->mr_marketer_id)"), '</option>', "\n";
	}
}

// 家族続柄
function select_family_rel($default, $selected) {
	if ($default)
		echo "<option value=''>$default</option>\n";

	$sql = "SELECT fr_family_rel_cd,fr_name FROM m_family_rel WHERE fr_family_rel_cd<>1 ORDER BY fr_family_rel_cd";
	$result = db_exec($sql);
	$nrow = pg_numrows($result);
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);
		echo '<option ', value_selected($fetch->fr_family_rel_cd, $selected), '>', htmlspecialchars($fetch->fr_name), '</option>', "\n";
	}
}

// 子供の学年
function select_child_gakunen($default, $selected) {
	if ($default)
		echo "<option value=''>$default</option>\n";

	$sql = "SELECT gk_gakunen_cd,gk_name FROM m_gakunen ORDER BY gk_gakunen_cd";
	$result = db_exec($sql);
	$nrow = pg_numrows($result);
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);
		echo '<option ', value_selected($fetch->gk_gakunen_cd, $selected), '>', htmlspecialchars($fetch->gk_name), '</option>', "\n";
	}
}
?>