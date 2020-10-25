<?
/******************************************************
' System :fletteスタッフページ
' Content:フォーマット化関数
'******************************************************/

// 日付フォーマット
function format_date($date, $null = '') {
	if ($date == null)
		return $null;
	else
		return date('Y/m/d', sql_time($date));
}

// 日時フォーマット
function format_datetime($date, $null = '') {
	if ($date == null)
		return $null;
	else
		return date('Y/m/d H:i', sql_time($date));
}
?>