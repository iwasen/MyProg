<?
/******************************************************
' System :�������ʪ�Υ��󥱡��ȥ�˥�������
' Content:My���󥱡��ȥƥ�ץ졼�ȼ���
'******************************************************/

// ��Ƭ�Υ��ڡ��������
function get_space($text, $macro) {
	$pos = strpos($text, $macro);
	if ($pos !== false) {
		$space = substr(strrchr(substr($text, 0, $pos), "\n"), 1);
	} else
		$space = '';

	return $space;
}

// ��������
function get_week($date) {
	$week = array('��', '��', '��', '��', '��', '��', '��');
	return $week[date('w', $date)];
}

// ����ʸ
function get_enq_description(&$myenq) {
	$enquete = &$myenq->enquete;

	// ���󥱡�������ʸ�ƥ�ץ졼�Ȥμ���
	$sql = "SELECT wt_description FROM m_web_template WHERE wt_template_id='mye'";
	$template = db_fetch1($sql);

	// �������߽���
	$week_ary = Array('��', '��', '��', '��', '��', '��', '��');
	$week = $week_ary[date('w', strtotime("$enquete->end_date_y/$enquete->end_date_m/$enquete->end_date_d"))];
	$template = str_replace('%TITLE%', $enquete->title, $template);
	$template = str_replace('%LIMIT_DATE%', mb_convert_kana($enquete->end_date_y, 'N') . 'ǯ' . mb_convert_kana($enquete->end_date_m, 'N') . '��' . mb_convert_kana($enquete->end_date_d, 'N') . '����' . $week . '��' . mb_convert_kana($enquete->end_date_h, 'N') . '������', $template);

	$space = get_space($template, '%SANKA_JOUKEN%');
	$space_flag = false;
	$ans_cond_ary = explode("\n", $myenq->ans_cond);
	foreach ($ans_cond_ary as $ans_cond) {
		if ($ans_cond != '') {
			if ($space_flag)
				$ans_cond_str .= $space;
			$ans_cond_str .= "�� {$ans_cond}\n";
			$space_flag = true;
		}
	}
	$template = str_replace('%SANKA_JOUKEN%', rtrim($ans_cond_str), $template);

	return $template;
}

// �᡼����ʸ
function get_enq_body(&$myenq, &$header, &$body, &$footer) {
	$enquete = &$myenq->enquete;

	$sql = "SELECT et_header,et_body,et_footer FROM m_enq_template WHERE et_template_id='mye_web'";
	$result = db_exec($sql);
	if (pg_num_rows($result)) {
		$fetch = pg_fetch_object($result, 0);

		$header = $fetch->et_header;
		$header = str_replace('%TITLE%', $enquete->title, $header);
		$header = str_replace('%LIMIT_DATE%', "{$enquete->end_date_y}ǯ{$enquete->end_date_m}��{$enquete->end_date_d}��{$enquete->end_date_h}��", $header);

		$body = $fetch->et_body;
		$space = get_space($body, '%SANKA_JOUKEN%');
		$space_flag = false;
		$ans_cond_ary = explode("\n", $myenq->ans_cond);
		foreach ($ans_cond_ary as $ans_cond) {
			if ($ans_cond != '') {
				if ($space_flag)
					$ans_cond_str .= $space;
				$ans_cond_str .= "��$ans_cond\n";
				$space_flag = true;
			}
		}

		$fetch2 = get_system_info('sy_url_monitor');
		$url = "{$fetch2->sy_url_monitor}index.php?id=%ENQUETE_PARAM%";

		$body = str_replace('%TITLE%', $enquete->title, $body);
		$body = str_replace('%LIMIT_DATE%', mb_convert_kana("{$enquete->end_date_m}��{$enquete->end_date_d}����" . get_week(strtotime("$enquete->end_date_y/$enquete->end_date_m/$enquete->end_date_d")) . "��{$enquete->end_date_h}��", 'N'), $body);
		$body = str_replace('%QUESTION%', mb_convert_kana($enquete->get_question_num(), 'N'), $body);
		$body = str_replace('%SANKA_JOUKEN%', htmlspecialchars(trim($ans_cond_str)), $body);
		$body = str_replace('%ENQUETE_URL%', $url, $body);

		$footer = $fetch->et_footer;
	}
}
?>