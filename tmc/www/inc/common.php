<?
/******************************************************
' System :「おクルマ選び お手伝いサービス」事務局用ページ
' Content:共通汎用関数
'******************************************************/

define('SYSTEM_NAME', 'おクルマ選び　お手伝いサービス');
define('COOKIE_EXPIRE', time()+365*24*60*60);
define('MAX_THREAD_DEPTH', 100);
define('MAX_FOLLOW_MAIL', 4);
define('MAX_RELATION_MAIL', 30);

// リダイレクト
function redirect($url) {
	header("location: $url");
	exit;
}

// SQLタイムスタンプ取得
function sql_time($time) {
	return strtotime(strtok($time, '.+'));
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

// checked 出力
function checked($flag) {
	return $flag ? 'checked' : '';
}

// disabled 出力
function disabled($flag) {
	if ($flag)
		return ' disabled';
}

// 最後の不要なCRLFを削除
function trim_crlf(&$text) {
	return rtrim($text) . "\r\n";
}

// Not Found
function http_not_found() {
	header("HTTP/1.0 404 Not Found");
	exit;
}
?>