<?
$graph_color = array('#b73277', '#bd301d', '#c0c030', '#20552a', '#762b72');

function enq_graph_common($enquete_id, $question_no, $scale, $html, $percent, $kind) {
	global $graph_color;

	if ($percent) {
		$sql = "SELECT em_sum FROM t_enquete_sum"
				. " WHERE em_enquete_id=$enquete_id AND em_question_no=$question_no AND em_sum_kind=$kind AND em_sel_no=0";
		$sum = db_fetch1($sql);
	}

	$sql = "SELECT es_sel_text_s,em_sum"
			. " FROM t_enq_select JOIN t_enquete_sum ON em_enquete_id=es_enquete_id AND em_question_no=es_question_no AND em_sum_kind=$kind AND em_sel_no=es_sel_no"
			. " WHERE es_enquete_id=$enquete_id AND es_question_no=$question_no"
			. " ORDER BY es_sel_no";
	$result = db_exec($sql);
	$nrow = pg_numrows($result);

	$scale_max = 0;
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);
		$scale_max = max($scale_max, $fetch->em_sum);
	}

	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);

		$count = number_format($fetch->em_sum);
		if ($percent) {
			if ($sum == 0)
				$count .= ' (0%)';
			else
				$count .= sprintf($percent === true ? ' (%.1f%%)' : $percent, $fetch->em_sum / $sum * 100);
		}

		$s = $html;
		$s = str_replace('%COLOR%', $graph_color[$i % 5], $s);
		$s = str_replace('%TEXT%', htmlspecialchars($fetch->es_sel_text_s), $s);
		$s = str_replace('%IMAGE%', $i % 5 + 1, $s);
		$s = str_replace('%WIDTH%', ($scale_max == 0) ? 0 : floor($fetch->em_sum / $scale_max * $scale * 0.93), $s);
		$s = str_replace('%COUNT%', $count, $s);

		echo "<tr>\n";
		echo "$s\n";
		echo "</tr>\n";
	}
}

function enq_graph_all($enquete_id, $question_no, $scale, $html, $percent = false) {
	enq_graph_common($enquete_id, $question_no, $scale, $html, $percent, 1);
}

function enq_graph_man($enquete_id, $question_no, $scale, $html, $percent = false) {
	enq_graph_common($enquete_id, $question_no, $scale, $html, $percent, 2);
}

function enq_graph_woman($enquete_id, $question_no, $scale, $html, $percent = false) {
	enq_graph_common($enquete_id, $question_no, $scale, $html, $percent, 3);
}

function enq_graph_mikon($enquete_id, $question_no, $scale, $html, $percent = false) {
	enq_graph_common($enquete_id, $question_no, $scale, $html, $percent, 4);
}

function enq_graph_kikon($enquete_id, $question_no, $scale, $html, $percent = false) {
	enq_graph_common($enquete_id, $question_no, $scale,  $html, $percent, 5);
}
?>