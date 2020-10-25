<?
/******************************************************
' System :きかせて・netマーケターＭｙページ
' Content:各種表示処理
'******************************************************/

// エージェント表示
function disp_agent($url) {
	$sql = "SELECT ag_agent_id,ag_name1 FROM t_agent WHERE ag_status=0 AND ag_disp_flag ORDER BY ag_agent_id DESC";
	$result = db_exec($sql);
	$nrow = pg_numrows($result);
	
	//echo "<table>\n";
	
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);

		if ($i % 2 == 0) {
			echo '<tr><td colspan="3"><img src="images/common/spacer.gif" width="1" height="3" border="0"></td></tr>', "\n";
			echo "<tr>\n";
		}

		echo "<td width=67 align='center'><img src='images/agent{$fetch->ag_agent_id}.jpg' width=60 height=60 border=0><br>\n";
		echo "<a href='javascript:void(0)' onclick='open(\"$url{$fetch->ag_agent_id}\", \"_blank\", \"width=742,height=730,scrollbars=yes,resizable=yes,status=yes\")'><font size=3 class='honbun1'>{$fetch->ag_name1}です</font></a></td>\n";

		if ($i % 2 == 0)
			echo "<td><img src='images/common/spacer.gif' width=3 height=1 border=0></td>\n";
		else
			echo "</tr>\n";
	}

	if ($i % 2 == 1) {
		echo "<td width=67 align='center'><br></td>\n";
		echo "</tr>\n";
	}
	
	//echo "</table>\n";
}

// マーケター名表示
function disp_marketer_name() {
	$sql = "SELECT mr_name1 FROM t_marketer WHERE mr_marketer_id={$_SESSION['ss_marketer_id']}";
	echo htmlspecialchars(db_fetch1($sql));
}
?>