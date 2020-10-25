<?
/******************************************************
' System :ICP共通インクルードファイル
' Content:フォーマット化関数
'******************************************************/

// 日付フォーマット
function format_date($date, $null = '') {
	if ($date == null)
		return $null;
	else
		return date('Y/m/d', sql_time($date));
}

// 日付フォーマット（日本語）
function format_date_j($date, $null = '') {
	if ($date == null)
		return $null;
	else
		return date('Y年m月d日', sql_time($date));
}

// 日時フォーマット
function format_datetime($date, $null = '') {
	if ($date == null)
		return $null;
	else
		return date('Y/m/d H:i', sql_time($date));
}

// 日時フォーマット（日本語）
function format_datetime_j($date, $null = '') {
	if ($date == null)
		return $null;
	else
		return date('Y年m月d日 H時i分', sql_time($date));
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

// テキスト
function format_text(&$text, $null = '') {
	if ($text == '')
		return $null;
	else
		return htmlspecialchars($text);
}
?>