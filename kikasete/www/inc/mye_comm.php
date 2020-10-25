<?
/******************************************************
' System :きかせて・net共通
' Content:Myアンケート関連
'******************************************************/

// Myアンケート
define('MAX_ENQ', 10);		// アンケート質問最大数
define('MAX_SEL', 32);		// 選択肢最大数

// Proアンケート
define('MAX_PRO_FINDING', 10);	// ファインデング質問最大数
define('MAX_PRO_ENQ', 80);	// アンケート質問最大数
define('MAX_PRO_SEL', 32);	// 選択肢最大数

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

// Myアンケート最速発信開始日時取得
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

// PROアンケート最速発信開始日時取得
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

// 翌営業日取得
function next_working_day2($time, $count = 1) {
	for ($i = 0; $i < $count; $i++) {
		for (;;) {
			$time += 24 * 60 * 60;
			$date = getdate($time);
			if ($date['wday'] != 6) {
				// 休日マスタ取得
				$sql = "SELECT COUNT(*) FROM m_holiday WHERE hd_holiday='{$date['year']}/{$date['mon']}/{$date['mday']}'";
				if (db_fetch1($sql) == 0) {
					if ($date['wday'] != 0)
						break;
				} else {
					// 振り替え休日
					if ($date['wday'] == 0)
						$time += 24 * 60 * 60;
				}
			}
		}
	}

	return $time;
}

// 最大送信数選択肢
function select_max_send_num($selected) {
	$ary = array(600, 900, 1200, 1500, 1800, 3000,10000);

	if (!in_array($selected, $ary))
		echo "<option value='$selected' selected>$selected</option>\n";

	foreach ($ary as $val)
		echo '<option ', value_selected($val, $selected), '>', $val, '</option>', "\n";
}

// キャッチ利用者判別
function is_catch_user($marketer_id) {
	switch ($marketer_id) {
	case 587:
		return true;
	}
	return false;
}
?>
