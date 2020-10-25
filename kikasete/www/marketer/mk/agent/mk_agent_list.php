<?
/******************************************************
' System :きかせて・netマーケターページ
' Content:エージェント一覧表示選択処理
'******************************************************/

// エージェント選択
function agent_select($agent_id, $cols=10) {
	$sql = "SELECT ag_agent_id,ag_name1 FROM t_agent WHERE ag_status=0 AND ag_disp_flag ORDER BY ag_agent_id DESC";
	$result = db_exec($sql);
	$nrow = pg_numrows($result);
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);

		if ($i % $cols == 0)
			echo "<tr>\n";

		echo "<td width=65 align='center' valign='top'>\n";
		echo "<img src='images/agent{$fetch->ag_agent_id}.jpg' alt='{$fetch->ag_name1}です' width=60 height=60 border=0>\n";
		echo "<a href='mk_agintro.php#{$fetch->ag_agent_id}' target='_blank'>{$fetch->ag_name1}です</a>\n";
		echo "<br><input type='radio' name='agent_id'", value_checked($fetch->ag_agent_id, $agent_id), ">\n";
		echo "</td>\n";

		if (($i + 1) % $cols == 0)
			echo "</tr><tr><td colspan=$cols><hr width='100%'></td></tr>\n";
	}

	if ($i % $cols != 0) {
		for ( ; $i % $cols != 0; $i++)
			echo "<td width=65></td>\n";
		echo "</tr><tr><td colspan=$cols><hr width='100%'></td></tr>\n";
	}
}

// エージェントリスト
function agent_list($url, $cols=10) {
	$sql = "SELECT ag_agent_id,ag_name1 FROM t_agent WHERE ag_status=0 AND ag_disp_flag ORDER BY ag_agent_id DESC";
	$result = db_exec($sql);
	$nrow = pg_numrows($result);
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);

		if ($i % $cols == 0)
			echo "<tr>\n";

		echo "<td width='10%' align='center' valign='top'>\n";
		echo "<img src='images/agent{$fetch->ag_agent_id}.jpg' alt='{$fetch->ag_name1}です' width=60 height=60 border=0><br>\n";
		echo "<a href='$url#{$fetch->ag_agent_id}' target='_blank'>{$fetch->ag_name1}です</a>\n";
		echo "</td>\n";

		if (($i + 1) % $cols == 0)
			echo "</tr>\n";
	}

	if ($i % $cols != 0) {
		for ( ; $i % $cols != 0; $i++)
			echo "<td width=65></td>\n";
		echo "</tr>\n";
	}
}
?>