<?
/******************************************************
' System :�������ơ�net�ޡ��������ڡ���
' Content:����������Ȱ���ɽ���������
'******************************************************/

require_once ('mk_common.php');


// ���������������
function agent_select($agent_id, $cols=10, $url='') {

	//���ե����뤫�顢marketer�ǥ��쥯�ȥ�ؤ����Хѥ��μ���
	$top_path = getPathToMarketer();

	$sql = "SELECT ag_agent_id,ag_name1 FROM t_agent WHERE ag_status=0 AND ag_disp_flag ORDER BY ag_agent_id DESC";
	$result = db_exec($sql);
	$nrow = pg_numrows($result);
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);

		if ($i % $cols == 0)
			echo "<tr>\n";

		echo "<td width=65 align='center' valign='top'>\n";
		echo "<img src='" . $top_path . "images/agent{$fetch->ag_agent_id}.jpg' alt='{$fetch->ag_name1}' width=50 height=50 border=0>\n";
		echo "<a href='{$url}mk_agintro.php#{$fetch->ag_agent_id}' target='_blank'>{$fetch->ag_name1}</a>\n";
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

// ����������ȥꥹ��
function agent_list($url, $cols=10) {
	//���ե����뤫�顢marketer�ǥ��쥯�ȥ�ؤ����Хѥ��μ���
	$top_path = getPathToMarketer();
	
	$sql = "SELECT ag_agent_id,ag_name1 FROM t_agent WHERE ag_status=0 AND ag_disp_flag ORDER BY ag_agent_id DESC";
	$result = db_exec($sql);
	$nrow = pg_numrows($result);
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);

		if ($i % $cols == 0)
			echo "<tr>\n";

		echo "<td width='10%' align='center' valign='top'>\n";
		echo "<img src='" . $top_path . "images/agent{$fetch->ag_agent_id}.jpg' alt='{$fetch->ag_name1}�Ǥ�' width=60 height=60 border=0><br>\n";
		echo "<font size=2 class='honbun2'><a href='javascript:void(0)' onclick='open(\"$url{$fetch->ag_agent_id}\", \"_blank\", \"width=742,height=730,scrollbars=yes,resizable=yes,status=yes\")'><font size=3 class='honbun1'>{$fetch->ag_name1}�Ǥ�</font></a></td>\n";
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