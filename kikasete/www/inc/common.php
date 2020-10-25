<?
/******************************************************
' System :きかせて・net共通
' Content:共通インクルードファイル
'******************************************************/
#if( $_SERVER['REMOTE_ADDR'] !== '59.139.21.66') { redirect('/mente/index.html'); }

define('CD_MONITOR', '1');
define('CD_PARTNER', '2');
define('CD_MAN', '1');
define('CD_WOMAN', '2');
define('CD_MIKON', '1');
define('CD_KIKON', '2');

// モニター種別
define('MN_MONITOR', 1);
define('MN_PARTNER', 2);

// マーケター種別
define('MK_MAILNEWS', 1);
define('MK_FREE', 2);
define('MK_PAY', 3);
define('MK_ASSISTANT', 4);

// エージェント種別
define('AG_MAIN', 1);
define('AG_SUB', 1);

define('MAX_MONITOR_CHILD', 10);

define('COOKIE_EXPIRE', time()+365*24*60*60);

// エスケープ文字削除
if (get_magic_quotes_gpc()) {
	foreach (array_keys($_GET) as $varname) {
		if (!is_array($_GET[$varname]))
			$_GET[$varname] = stripslashes($_GET[$varname]);
		if (!is_array($GLOBALS[$varname]))
			$GLOBALS[$varname] = stripslashes($GLOBALS[$varname]);
	}
	foreach (array_keys($_POST) as $varname) {
		if (!is_array($_POST[$varname]))
			$_POST[$varname] = stripslashes($_POST[$varname]);
		if (!is_array($GLOBALS[$varname]))
			$GLOBALS[$varname] = stripslashes($GLOBALS[$varname]);
	}
}

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
	return strtotime(strtok($time, '.+'));
}

// value 出力
function value($value) {
	return 'value="' . htmlspecialchars(trim($value)) . '"';
}

// checked 出力
function checked($flag) {
	return $flag ? 'checked' : '';
}

// value, checked 出力
function value_checked($value, $checked) {
	if ($value == $checked)
		return "value='$value' checked";
	else
		return "value='$value'";
}

// value, checked 出力（カンマ区切りデータ）
function value_checked_multi($value, $checked) {
	$ary = explode(',', $checked);
	if (in_array($value, $ary))
		return "value='$value' checked";
	else
		return "value='$value'";
}

// selected 出力
function selected($flag) {
	return $flag ? 'selected' : '';
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

// 複数データ取得
function get_multi_data($name) {
	$ary = array();
	if (is_array($name))
		get_multi_data_sub($name, $ary);
	return join(',', $ary);
}

function get_multi_data_sub($data, &$ary) {
	foreach ($data as $val) {
		if (is_array($val))
			get_multi_data_sub($val, $ary);
		elseif ($val != '')
			$ary[] = $val;
	}
}

// 複数フラグ取得
function get_multi_flag($name, $len) {
	$flags = str_repeat('0', $len);
	if (is_array($name)) {
		foreach ($name as $val) {
			if ($val >= 0 && $val < $len)
				$flags{$val} = '1';
		}
	}
	return $flags;
}

// メッセージ表示
function disp_msg($msg, $pre='', $post='') {
	if (is_array($msg)) {
		foreach($msg as $str)
			echo "$pre$str$post\n";
	} elseif ($msg != '')
		echo "$pre$msg$post\n";
}

// 空白削除
function strip_blank($str) {
	return str_replace(' ', '', mb_convert_kana($str, 's'));
}

// 非SSLページURL取得
function http_url($page) {
	$url = "http://{$_SERVER['SERVER_NAME']}{$_SERVER['PHP_SELF']}";
	$pos = strrpos($url, '/');
	return substr($url, 0, $pos + 1) . $page;
}

// SSLページURL取得
function https_url($page) {
	$url = "https://{$_SERVER['SERVER_NAME']}{$_SERVER['PHP_SELF']}";
	$pos = strrpos($url, '/');
	return substr($url, 0, $pos + 1) . $page;
}

// 戻り先設定
function set_return_page($page, $param = '') {
	$self = $_SERVER['SCRIPT_NAME'];

	if ($param != '')
		$self .= "?$param";

	$_SESSION['s_return_page'][$page] = $self;
}

// 戻り先取得
function get_return_page($def = '') {
	$page = substr(strrchr($_SERVER['SCRIPT_NAME'], '/'), 1);
	return $_SESSION['s_return_page'][$page] ? $_SESSION['s_return_page'][$page] : $def;
}

// explode（空の場合は要素0の配列を返す）
function my_explode($separator, $str) {
	if ($str != '')
		return explode($separator, $str);
	else
		return array();
}

// ランダムパラメータ生成
function random_param() {
	return '_p' . mt_rand();
}

// 文字列の切り詰め
function str_trim($str, $len) {
	if (mb_strlen($str) > $len)
		return mb_substr($str, 0, $len) . '…';
	else
		return $str;
}
?>
