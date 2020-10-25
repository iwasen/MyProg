<?
// チェックボックス表示処理
function checkbox_common($sql, $name, $code, $cols) {
	$ary = explode(',', $code);

	$result = db_exec($sql);
	$nrow = pg_numrows($result);

	echo '<table border=0 width="100%" cellspacing=0 cellpadding=0>', "\n";
	$col = 0;
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_row($result, $i);
		if ($col % $cols == 0)
			echo "<tr>\n";

		echo "<td width='50%'><input type='checkbox' name='${name}[]' value='$fetch[0]'", in_array($fetch[0], $ary) ? ' checked' : '', '>', htmlspecialchars($fetch[1]), "</td>\n";

		if (++$col % $cols == 0)
			echo "</tr>\n";
	}
	if ($col % $cols != 0)
		echo "</tr>\n";

	echo "</table>\n";
}

// 送信先エージェント表示
function checkbox_agant($agent_id) {
	$sql = "SELECT ag_agent_id,ag_name1||' '||ag_name2||' <'||ag_mail_addr||'>' AS name FROM t_agent WHERE ag_status=0 ORDER BY ag_name1_kana||ag_name2_kana";
	checkbox_common($sql, 'agent_id', $agent_id, 2);
}
?>