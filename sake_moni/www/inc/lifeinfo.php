<?
/******************************************************
' System :お酒と買物のアンケートモニター共通
' Content:生活情報関連処理
'******************************************************/

// チェーン
function checkbox_chain($monitor_id, $cols, $readonly = false) {
	if ($monitor_id) {
		$sql = "SELECT ch_chain_cd,ch_name,dc_chain_cd"
				. " FROM m_chain LEFT JOIN t_chain ON dc_monitor_id=$monitor_id AND ch_chain_cd=dc_chain_cd"
				. " WHERE ch_status=0"
				. " ORDER BY ch_order";
	} else {
		$sql = "SELECT ch_chain_cd,ch_name,NULL FROM m_chain WHERE ch_status=0 ORDER BY ch_order";
	}
	$result = db_exec($sql);
	$nrow = pg_num_rows($result);

	$readonly = $readonly ? ' onclick="return false"' : '';

	echo '<table border=0 cellspacing=0 cellpadding=1>', "\n";
	$col = 0;
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);
		if ($col % $cols == 0)
			echo "<tr>\n";

		echo '<td><input type="checkbox" name="chain_cd[]"', value_checked($fetch->ch_chain_cd, $fetch->dc_chain_cd), "$readonly>$fetch->ch_name</td>\n";

		if (++$col % $cols == 0)
			echo "</tr>\n";
	}
	if ($col % $cols != 0)
		echo "</tr>\n";

	echo "</table>\n";
}
?>