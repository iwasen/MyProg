<?
/******************************************************
* system  : xxx リスク診断
* title   : 共通計算関数
* content : 各計算処理で使用する共通関数群
* version : 1.0
* author  : aizawa
*******************************************************/

// ■日付加算
function add_date($date, $add) {
	list($year, $month, $day) = explode('/', $date);

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

	return sprintf('%04d/%02d/%02d', $century * 100 + $year, $month, $day);
}

// ■YYYYMMDDをYYYY/MM/DDに変換
function ymd_to_date($ymd) {
	return $ymd == '' ? '' : sprintf('%04d/%02d/%02d', substr($ymd, 0, 4), substr($ymd, 4, 2), substr($ymd, 6, 2));
}

// ■YYYY/MM/DD を YYYYMMDD に変換
function date_to_ymd($date) {
	if ($date != '') {
		list($y, $m, $d) = explode('/', $date);
		return sprintf("%04d%02d%02d", $y, $m, $d);
	} else
		return '';
}

// ■YYYY-MM-DD を YYYY/MM/DD に変換
function format_date($date) {
	sscanf($date, "%d-%d-%d", &$y, &$m, &$d);
	return sprintf("%04d/%02d/%02d", $y, $m, $d);
}
?>