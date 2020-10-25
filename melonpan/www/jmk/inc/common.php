<?
// リダイレクト
function redirect($url) {
	header("location: $url");
	exit;
}


// 固定桁ＩＤ
function make_id($id, $len) {
	return substr(str_repeat('0', $len) . $id, -$len);
}

// SQLタイムスタンプ取得
function sql_time($time) {
	return strtotime(strtok($time, '+'));
}

// 日付フォーマット
function format_date($date) {
	if ($date == null)
		return '指定なし';
	else
		return  date('Y/m/d', sql_time($date));
}

// 日時フォーマット
function format_datetime($date) {
	if ($date == null)
		return '指定なし';
	else
		return date('Y/m/d H:i', sql_time($date));
}

// value 出力
function value($value) {
	return 'value="' . htmlspecialchars(trim($value)) . '"';
}

// value, checked 出力
function value_checked($value, $checked) {
	if ($value == $checked)
		return "value='$value' checked";
	else
		return "value='$value'";
}

// value, selected 出力
function value_selected($value, $selected) {
	if ($value == $selected)
		return "value='$value' selected";
	else
		return "value='$value'";
}

// disabled 出力
function disabled($flag) {
	if ($flag)
		return ' disabled';
}
?>