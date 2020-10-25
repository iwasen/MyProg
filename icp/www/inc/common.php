<?
/******************************************************
' System :ICP共通インクルードファイル
' Content:共通汎用関数
'******************************************************/

ini_set('session.cache_limiter', 'none');
define('COOKIE_EXPIRE', time()+365*24*60*60);

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
		return "value=\"$value\" checked";
	else
		return "value=\"$value\"";
}

// value, selected 出力
function value_selected($value, $selected) {
	if ($value == $selected)
		return "value=\"$value\" selected";
	else
		return "value=\"$value\"";
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

// プログラムエラー処理
function program_err($file, $line) {
	echo "<br>";
	echo "PROGRAM ERROR";
	echo "<br>";
	echo "FILE:$file";
	echo "<br>";
	echo "LINE:$line";

	$msg =
		"ICP Error\n\n" .
		"Date: " . strftime('%c') . "\n" .
		"File: $file\n" .
		"Line: $line\n\n" .
		'$_GET: ' . serialize($_GET) . "\n\n" .
		'$_POST: ' . serialize($_POST) . "\n\n" .
		'$_SESSION: ' . serialize($_SESSION) . "\n\n" .
		'$_SERVER: ' . serialize($_SERVER) . "\n\n";
	mb_send_mail("aizawa@xxxxx-xxxx.co.jp", "ICP Error", $msg, "From: icp\n");
	exit;
}
?>