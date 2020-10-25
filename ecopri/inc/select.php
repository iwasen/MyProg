<?
/******************************************************
' System :Eco-footprint ¶¦ÄÌ
' Content:ÁªÂò»èÉ½¼¨½èÍý
'******************************************************/

// Ç¯
function select_year($start_year, $default, $selected) {
	if ($default)
		echo "<option value=''>$default</option>\n";

	$end_year = date('Y') + 4;
	for ($i = $start_year; $i < $end_year; $i++) {
		echo '<option ', value_selected($i, $selected), ">$i</option>\n";
	}
}

// ·î
function select_month($default, $selected) {
	if ($default)
		echo "<option value=''>$default</option>\n";

	for ($i = 1; $i <= 12; $i++)
		echo '<option ', value_selected($i, $selected), ">$i</option>\n";
}

// Æü
function select_day($default, $selected) {
	if ($default)
		echo "<option value=''>$default</option>\n";

	for ($i = 1; $i <= 31; $i++)
		echo '<option ', value_selected($i, $selected), ">$i</option>\n";
}

// »þ´Ö
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

// É½¼¨¹Ô¿ô
function select_displine($selected) {
	echo '<option ', value_selected(0, $selected), ">- Á´¤Æ -</option>\n";
	for ($i = 10; $i <= 100; $i += 10)
		echo '<option ', value_selected($i, $selected), ">$i</option>\n";
}

// ÃÏ°è¶èÊ¬(ËÌ³¤Æ»¡¢ÅìËÌŽ¥Ž¥Ž¥)
function select_area2($default, $selected) {
	if ($default)
		echo "<option value=''>$default</option>\n";

	$sql = "SELECT aa_area2_cd,aa_area2_name FROM m_area2 ORDER BY aa_area2_cd";
	$result = db_exec($sql);
	$nrow = pg_numrows($result);
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);
		echo '<option ', value_selected($fetch->aa_area2_cd, $selected), '>', htmlspecialchars($fetch->aa_area2_name), '</option>', "\n";
	}
}

// ÃÏ°è(ÅÔÆ»ÉÜ¸©)
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
// ²ÈÂ²Â³ÊÁ
function select_family_rel($default, $selected) {
	if ($default)
		echo "<option value=''>$default</option>\n";

	$sql = "SELECT fr_family_rel_cd,fr_name FROM m_family_rel WHERE fr_family_rel_cd<>1 ORDER BY fr_order";
	$result = db_exec($sql);
	$nrow = pg_numrows($result);
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);
		echo '<option ', value_selected($fetch->fr_family_rel_cd, $selected), '>', htmlspecialchars($fetch->fr_name), '</option>', "\n";
	}
}
// ½»µï·ÁÂÖ
function select_keitai($default, $selected) {
	if ($default)
		echo "<option value=''>$default</option>\n";

	$sql = "SELECT kt_keitai_cd,kt_keitai_name FROM m_keitai ORDER BY kt_order";
	$result = db_exec($sql);
	$nrow = pg_numrows($result);
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);
		echo '<option ', value_selected($fetch->kt_keitai_cd, $selected), '>', htmlspecialchars($fetch->kt_keitai_name), '</option>', "\n";
	}
}
// ½»µïÁÇºà
function select_sozai($default, $selected) {
	if ($default)
		echo "<option value=''>$default</option>\n";

	$sql = "SELECT sz_sozai_cd,sz_sozai_name FROM m_sozai ORDER BY sz_sozai_cd";
	$result = db_exec($sql);
	$nrow = pg_numrows($result);
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);
		echo '<option ', value_selected($fetch->sz_sozai_cd, $selected), '>', htmlspecialchars($fetch->sz_sozai_name), '</option>', "\n";
	}
}

// ½»µïÁÇºà
function select_room($default, $selected) {
	if ($default)
		echo "<option value=''>$default</option>\n";

	$sql = "SELECT rm_room_cd,rm_room_name FROM m_room ORDER BY rm_room_cd";
	$result = db_exec($sql);
	$nrow = pg_numrows($result);
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);
		echo '<option ', value_selected($fetch->rm_room_cd, $selected), '>', htmlspecialchars($fetch->rm_room_name), '</option>', "\n";
	}
}

// µëÅò´ï¥¿¥¤¥×
function select_boiler($default, $selected) {
	if ($default)
		echo "<option value=''>$default</option>\n";

	$sql = "SELECT bt_boiler_type_cd,bt_boiler_name FROM m_boiler_type ORDER BY bt_boiler_type_cd";
	$result = db_exec($sql);
	$nrow = pg_numrows($result);
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);
		echo '<option ', value_selected($fetch->bt_boiler_type_cd, $selected), '>', htmlspecialchars($fetch->bt_boiler_name), '</option>', "\n";
	}
}
// ¥¬¥¹¼ï¥¿¥¤¥×
function select_gas_kind($default, $selected) {
	if ($default)
		echo "<option value=''>$default</option>\n";

	$sql = "SELECT gk_gas_kind_cd,gk_gas_kind_name FROM m_gas_kind ORDER BY gk_gas_kind_cd";
	$result = db_exec($sql);
	$nrow = pg_numrows($result);
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);
		echo '<option ', value_selected($fetch->gk_gas_kind_cd, $selected), '>', htmlspecialchars($fetch->gk_gas_kind_name), '</option>', "\n";
	}
}
// ¥¬¥¹¥¿¥¤¥×
function select_gas_type($default, $selected) {
	if ($default)
		echo "<option value=''>$default</option>\n";

	$sql = "SELECT gt_gas_type_cd,gt_gas_type_name FROM m_gas_type ORDER BY gt_gas_type_cd";
	$result = db_exec($sql);
	$nrow = pg_numrows($result);
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);
		echo '<option ', value_selected($fetch->gt_gas_type_cd, $selected), '>', htmlspecialchars($fetch->gt_gas_type_name), '</option>', "\n";
	}
}

// ¥¨¥Í¥ë¥®¡¼Ê¬Îà
function select_energy($default, $selected) {
	if ($default)
		echo "<option value=''>$default</option>\n";

	$sql = "SELECT en_class_cd,en_text FROM m_energy ORDER BY en_class_cd";
	$result = db_exec($sql);
	$nrow = pg_numrows($result);
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);
		echo '<option ', value_selected($fetch->en_class_cd, $selected), '>', htmlspecialchars($fetch->en_text), '</option>', "\n";
	}
}

// ²ÈÂ²¥«¥Æ¥´¥ê
function select_family_ctg($default, $selected) {
	if ($default)
		echo "<option value=''>$default</option>\n";

	$sql = "SELECT fc_family_ctg_cd,fc_text FROM m_family_ctg ORDER BY fc_family_ctg_cd";
	$result = db_exec($sql);
	$nrow = pg_numrows($result);
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);
		echo '<option ', value_selected($fetch->fc_family_ctg_cd, $selected), '>', htmlspecialchars($fetch->fc_text), '</option>', "\n";
	}
}

?>