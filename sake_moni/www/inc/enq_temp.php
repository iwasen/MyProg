<?
/******************************************************
' System :�������ʪ�Υ��󥱡��ȥ�˥�������
' Content:���󥱡��ȥƥ�ץ졼�ȼ���
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
function get_enq_description(&$myp) {
	$enquete = &$myp->enquete;

	// ���󥱡�������ʸ�ƥ�ץ졼�Ȥμ���
	$sql = "SELECT wt_description FROM m_web_template WHERE wt_template_id='myp'";
	$template = db_fetch1($sql);

	// �������߽���
	$week_ary = Array('��', '��', '��', '��', '��', '��', '��');
	$week = $week_ary[date('w', strtotime("$myp->recruit_end_y/$myp->recruit_end_m/$myp->recruit_end_d"))];
	$template = str_replace('%TITLE%', $enquete->title, $template);
	$template = str_replace('%LIMIT_DATE%', mb_convert_kana($myp->recruit_end_y, 'N') . 'ǯ' . mb_convert_kana($myp->recruit_end_m, 'N') . '��' . mb_convert_kana($myp->recruit_end_d, 'N') . '����' . $week . '��' . mb_convert_kana($myp->recruit_end_h, 'N') . '������', $template);
	$space = get_space($template, '%SANKA_JOUKEN%');
	$space_flag = false;
	foreach ($myp->recruit_jouken as $recruit_jouken) {
		if ($recruit_jouken != '') {
			if ($space_flag)
				$ans_cond_str .= $space;
			$ans_cond_str .= "��{$recruit_jouken}\n";
			$space_flag = true;
		}
	}
	$template = str_replace('%SANKA_JOUKEN%', rtrim($ans_cond_str), $template);
	$template = str_replace('%ROOM_NAME%', $myp->room_name, $template);
	$template = str_replace('%ROOM_START_DATE%', mb_convert_kana("{$myp->room_start_m}��{$myp->room_start_d}����" . get_week(strtotime("$myp->room_start_y/$myp->room_start_m/$myp->room_start_d")) . '��', 'N'), $template);
	$template = str_replace('%ROOM_END_DATE%', mb_convert_kana("{$myp->room_end_m}��{$myp->room_end_d}����" . get_week(strtotime("$myp->room_end_y/$myp->room_end_m/$myp->room_end_d")) . '��', 'N'), $template);
	$template = str_replace('%DECIDE%', mb_convert_kana("{$myp->decision_date_m}��{$myp->decision_date_d}����" . get_week(strtotime("$myp->decision_date_y/$myp->decision_date_m/$myp->decision_date_d")) . '��', 'N'), $template);

	return $template;
}

// �᡼����ʸ
function get_enq_body(&$myp, &$header, &$body, &$footer) {
	$enquete = &$myp->enquete;

	$sql = "SELECT et_header,et_body,et_footer FROM m_enq_template WHERE et_template_id='myp_web'";
	$result = db_exec($sql);
	if (pg_num_rows($result)) {
		$fetch = pg_fetch_object($result, 0);

		$header = $fetch->et_header;
		$header = str_replace('%TITLE%', $enquete->title, $header);
		$header = str_replace('%LIMIT_DATE%', "{$myp->recruit_end_y}ǯ{$myp->recruit_end_m}��{$myp->recruit_end_d}��{$myp->recruit_end_h}��", $header);

		$body = $fetch->et_body;
		$space = get_space($body, '%SANKA_JOUKEN%');
		$space_flag = false;

		foreach ($myp->recruit_jouken as $recruit_jouken) {
			if ($recruit_jouken != '') {
				if ($space_flag)
					$ans_cond_str .= $space;
				$ans_cond_str .= "��{$recruit_jouken}\n";
				$space_flag = true;
			}
		}

		$fetch2 = get_system_info('sy_url_monitor');
		$url = "{$fetch2->sy_url_monitor}index.php?id=%ENQUETE_PARAM%";

		$body = str_replace('%TITLE%', $enquete->title, $body);
		$body = str_replace('%LIMIT_DATE%', mb_convert_kana("{$myp->recruit_end_m}��{$myp->recruit_end_d}����" . get_week(strtotime("$myp->recruit_end_y/$myp->recruit_end_m/$myp->recruit_end_d")) . "��{$myp->recruit_end_h}��", 'N'), $body);
		$body = str_replace('%QUESTION%', mb_convert_kana($enquete->get_question_num(), 'N'), $body);
		$body = str_replace('%SANKA_JOUKEN%', htmlspecialchars(trim($ans_cond_str)), $body);
		$body = str_replace('%ENQUETE_URL%', $url, $body);
		$body = str_replace('%ROOM_NAME%', $myp->room_name, $body);
		$body = str_replace('%ROOM_START_DATE%', mb_convert_kana("{$myp->room_start_m}��{$myp->room_start_d}����" . get_week(strtotime("$myp->room_start_y/$myp->room_start_m/$myp->room_start_d")) . '��', 'N'), $body);
		$body = str_replace('%ROOM_END_DATE%', mb_convert_kana("{$myp->room_end_m}��{$myp->room_end_d}����" . get_week(strtotime("$myp->room_end_y/$myp->room_end_m/$myp->room_end_d")) . '��', 'N'), $body);
		$body = str_replace('%DECIDE%', mb_convert_kana("{$myp->decision_date_m}��{$myp->decision_date_d}����" . get_week(strtotime("$myp->decision_date_y/$myp->decision_date_m/$myp->decision_date_d")) . '��', 'N'), $body);

		$footer = $fetch->et_footer;
	}
}
?>