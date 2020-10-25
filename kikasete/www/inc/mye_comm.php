<?
/******************************************************
' System :�������ơ�net����
' Content:My���󥱡��ȴ�Ϣ
'******************************************************/

// My���󥱡���
define('MAX_ENQ', 10);		// ���󥱡��ȼ�������
define('MAX_SEL', 32);		// ���������

// Pro���󥱡���
define('MAX_PRO_FINDING', 10);	// �ե�����ǥ󥰼�������
define('MAX_PRO_ENQ', 80);	// ���󥱡��ȼ�������
define('MAX_PRO_SEL', 32);	// ���������

$question_color = array(
	1 => '#afeeee',
	2 => '#bfefdf',
	3 => '#fffff0',
	4 => '#ffeeee',
	5 => '#eeeeff',
	6 => '#afeeee',
	7 => '#bfefdf',
	8 => '#fffff0',
	9 => '#ffeeee',
 10 => '#eeeeff'
);

// My���󥱡��Ⱥ�®ȯ��������������
function get_quickest_date(&$send_date_y, &$send_date_m, &$send_date_d, &$send_date_h) {
	$time = time();
	if (date('H', $time) < 11)
		$time -= 60 * 60 * 24;

	$time = next_working_day2($time);

	$send_date_y = date('Y', $time);
	$send_date_m = date('m', $time);
	$send_date_d = date('d', $time);
	$send_date_h = 18;
}

// PRO���󥱡��Ⱥ�®ȯ��������������
function get_quickest_date_pro(&$send_date_y, &$send_date_m, &$send_date_d, &$send_date_h) {
	$time = time();
	if (date('H', $time) < 15)
		$time -= 60 * 60 * 24;

	$time = next_working_day2($time, 2);

	$send_date_y = date('Y', $time);
	$send_date_m = date('m', $time);
	$send_date_d = date('d', $time);
	$send_date_h = 18;
}

// ��Ķ�������
function next_working_day2($time, $count = 1) {
	for ($i = 0; $i < $count; $i++) {
		for (;;) {
			$time += 24 * 60 * 60;
			$date = getdate($time);
			if ($date['wday'] != 6) {
				// �����ޥ�������
				$sql = "SELECT COUNT(*) FROM m_holiday WHERE hd_holiday='{$date['year']}/{$date['mon']}/{$date['mday']}'";
				if (db_fetch1($sql) == 0) {
					if ($date['wday'] != 0)
						break;
				} else {
					// �����ؤ�����
					if ($date['wday'] == 0)
						$time += 24 * 60 * 60;
				}
			}
		}
	}

	return $time;
}

// ���������������
function select_max_send_num($selected) {
	$ary = array(600, 900, 1200, 1500, 1800, 3000,10000);

	if (!in_array($selected, $ary))
		echo "<option value='$selected' selected>$selected</option>\n";

	foreach ($ary as $val)
		echo '<option ', value_selected($val, $selected), '>', $val, '</option>', "\n";
}

// ����å����Ѽ�Ƚ��
function is_catch_user($marketer_id) {
	switch ($marketer_id) {
	case 587:
		return true;
	}
	return false;
}
?>
