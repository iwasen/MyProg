<?php
/**
 * ひとびと･net 共通ファイル
 *
 * 日付処理
 *
 *
 * @package
 * @author
 * @version
 */

// ■年月日から日付取得
function get_date_from_ymd($y, $m, $d) {
	return array('y' => $y, 'm' => $m, 'd' => $d);
}

// ■UNIXタイムスタンプから日時取得
function get_time_from_ut($time) {
	$ary = getdate($time);
	return array('y' => $ary['year'], 'm' => $ary['mon'], 'd' => $ary['mday'], 'h' => $ary['hours'], 'n' => $ary['minutes'], 's' => $ary['seconds']);
}

// ■UNIXタイムスタンプから日付取得
function get_date_from_ut($time) {
	$ary = getdate($time);
	return array('y' => $ary['year'], 'm' => $ary['mon'], 'd' => $ary['mday']);
}

// ■現在日時取得
function get_current_time() {
	return get_time_from_ut(time());
}

// ■現在日付取得
function get_current_date() {
	return get_date_from_ut(time());
}

// ■月の加算
function add_month($date, $month) {
	$date['m'] += $month;
	if ($month >= 0) {
		while ($date['m'] > 12) {
			$date['y']++;
			$date['m'] -= 12;
		}
	} else {
		while ($date['m'] < 1) {
			$date['y']--;
			$date['m'] += 12;
		}
	}

	return $date;
}

// ■日の加算
function add_day($date, $add) {
	$year = $date['y'];
	$month = $date['m'];
	$day = $date['d'];

	$century = (int)substr($year, 0, 2);
	$year = (int)substr($year, 2, 2);
	if ($month > 2) {
		$month -= 3;
	} else {
			$month += 9;
		if ($year) {
			$year--;
		} else {
			$year = 99;
			$century --;
		}
	}

	$days = (floor((146097 * $century) / 4 ) +
			floor((1461 * $year) / 4 ) +
			floor((153 * $month + 2) / 5 ) +
			$day + 1721119);

	$days += $add;

	$days   -= 1721119;
	$century = floor((4 * $days - 1) / 146097);
	$days    = floor(4 * $days - 1 - 146097 * $century);
	$day     = floor($days / 4);

	$year    = floor((4 * $day + 3) / 1461);
	$day     = floor(4 * $day + 3 - 1461 * $year);
	$day     = floor(($day + 4) / 4);

	$month   = floor((5 * $day - 3) / 153);
	$day     = floor(5 * $day - 3 - 153 * $month);
	$day     = floor(($day + 5) / 5);

	if ($month < 10) {
		$month += 3;
	} else {
		$month -= 9;
		if ($year++ == 99) {
			$year = 0;
			$century++;
		}
	}

	$date['y'] = (int)($century * 100 + $year);
	$date['m'] = (int)$month;
	$date['d'] = (int)$day;

	return $date;
}

// ■日付の比較
function compare_date($date1, $date2) {
	if ($date1['y'] != $date2['y'])
		return $date1['y'] - $date2['y'];
	elseif ($date1['m'] != $date2['m'])
		return $date1['m'] - $date2['m'];
	elseif ($date1['d'] != $date2['d'])
		return $date1['d'] - $date2['d'];
	elseif ($date1['h'] != $date2['h'])
		return $date1['h'] - $date2['h'];
	elseif ($date1['n'] != $date2['n'])
		return $date1['n'] - $date2['n'];
	elseif ($date1['s'] != $date2['s'])
		return $date1['s'] - $date2['s'];
	else
		return 0;
}
?>