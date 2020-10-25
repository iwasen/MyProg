<?
/******************************************************
' System :flette�����åեڡ���
' Content:�������ߥޥ������
'******************************************************/

// ͽ��ǡ������麹�����߽���
function set_macro(&$text, &$reserve) {
	replace_macro($text, get_macro($reserve));
}

// ͽ��ǡ������麹�����ߥǡ�������
function get_macro(&$reserve) {
	// ����˺������ߥǡ������Խ�
	$ary['SEQ'] = $reserve->seq;
	$ary['DATE'] = $reserve->date;
	$ary['COURSE'] = $reserve->course;
	$ary['SHOP'] = $reserve->shop;
	for ($i = 0; $i < MAX_CHOICE; $i++) {
		$n = $i + 1;
		$ary["CHOICE_ROOM_$n"] = $reserve->choice[$i]->room;
		if ($reserve->choice[$i]->date)
			$ary["CHOICE_DATE_$n"] = "{$reserve->choice[$i]->date} {$reserve->choice[$i]->start_time} �� {$reserve->choice[$i]->end_time}";
		else
			$ary["CHOICE_DATE_$n"] = "";
		$ary["CHOICE_SEX_$n"] = decode_sex($reserve->choice[$i]->sex, '');
	}
	$ary['NAME_1'] = $reserve->name_1;
	$ary['NAME_2'] = $reserve->name_2;
	$ary['KANA_1'] = $reserve->kana_1;
	$ary['KANA_2'] = $reserve->kana_2;
	$ary['MAIL_ADDR'] = $reserve->mail_addr;

	if ($reserve->contact_flag[0]) {
		$contact[] = '�������嵭��Ʊ���᡼�륢�ɥ쥹';
		$contact2[] = '�嵭��Ʊ���᡼�륢�ɥ쥹';
	} else
		$contact2[] = '';

	if ($reserve->contact_flag[1]) {
		$contact[] = '�������᡼�� ��' . $reserve->contact_mail;
		$contact2[] = $reserve->contact_mail;
	} else
		$contact2[] = '';

	if ($reserve->contact_flag[2]) {
		$contact[] = '��������������  ��' . $reserve->contact_keitai;
		$contact2[] = $reserve->contact_keitai;
	} else
		$contact2[] = '';

	if ($reserve->contact_flag[3]) {
		$contact[] = '��������������  ��' . $reserve->contact_jitaku;
		$contact2[] = $reserve->contact_jitaku;
	} else
		$contact2[] = '';

	$ary['CONTACT_POINT'] = join("\n", $contact);
	$ary['CONTACT_POINT_2'] = join("\n", $contact2);

	$ary['CONTACT_TIME'] = decode_contact_time($reserve->contact_time);
	$ary['CONTACT_TIME_MEMO'] = $reserve->contact_time_memo;
//	$ary['BIRTHDAY'] = $reserve->birthday;
	$ary['CHANGE'] = decode_change($reserve->change);
	$ary['COMMENT'] = $reserve->comment;
	$ary['COMMENT_2'] = str_replace("\n", '', str_replace("\r", '', $reserve->comment));

	$sql = "SELECT cs_counseling_id,cs_level_no,cs_parent_counseling_id,cs_content"
			. " FROM m_counseling"
			. " WHERE cs_course_id=" . sql_number($reserve->course_id)
			. " ORDER BY cs_display_order";
	$result = db_exec($sql);
	$nrow = pg_numrows($result);
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);
		$counseling_ary[$fetch->cs_level_no][$fetch->cs_parent_counseling_id][$fetch->cs_counseling_id] = $fetch->cs_content;
	}

	if (is_array($counseling_ary[1][0])) {
		$count1 = 0;
		foreach ($counseling_ary[1][0] as $counseling_id => $content) {
			if ($count1 != 0)
				$counseling[] = '';

			$counseling[] = " ��{$content}";

			if (is_array($counseling_ary[2][$counseling_id])) {
				$count2 = 0;
				foreach ($counseling_ary[2][$counseling_id] as $counseling_id => $content) {
					if (is_array($counseling_ary[3][$counseling_id])) {
						foreach ($counseling_ary[3][$counseling_id] as $counseling_id => $content) {
							if (in_array($counseling_id, $reserve->counseling_id)) {
								$counseling[] = "��������{$content}";
								$data2[] = $content;
							} else
								$data2[] = '';
						}
					}
					$count2++;
				}
			}
			$count1++;
		}
	}
	$ary['DATA'] = join("\n", $counseling);
	$ary['DATA_2'] = join("\n", $data2);

	foreach ($ary as $data)
		set_csv($csv, $data);

	$ary['CSV'] = $csv;

	return $ary;
}

// ���������ѿ��ִ�����
function replace_macro(&$text, &$ary) {
	// �������ߥޥ���̾��ǡ������֤�����
	foreach ($ary as $macro => $data)
		$text = str_replace("%$macro%", $data, $text);
}
?>