<?
/******************************************************
' System :「おクルマ選び お手伝いサービス」事務局用ページ
' Content:フォーマット化関数
'******************************************************/

// 日付フォーマット
function format_date($date) {
	if ($date == null)
		return '指定なし';
	else
		return date('Y/m/d', sql_time($date));
}

// 日時フォーマット
function format_datetime($date) {
	if ($date == null)
		return '指定なし';
	else
		return date('Y/m/d H:i', sql_time($date));
}

// 数字フォーマット
function format_number($number) {
	if ($number < 0)
		return '<font color="red">'. number_format($number). '</font>';
	else
		return number_format($number);
}

// パーセント
function format_percent($a, $b, $c) {
	return number_format($b != 0 ? 100.0 * $a / $b : 0, $c) . '%';
}
?>