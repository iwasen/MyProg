<?
/******************************************************
' System :�������ơ�net����
' Content:���̥��󥯥롼�ɥե�����
'******************************************************/

ini_set('session.cache_limiter', 'none');

define('CD_MONITOR', '1');
define('CD_PARTNER', '2');
define('CD_MAN', '1');
define('CD_WOMAN', '2');
define('CD_MIKON', '1');
define('CD_KIKON', '2');

// ��˥�������
define('MN_MONITOR', 1);
define('MN_PARTNER', 2);

// �ޡ�����������
define('MK_MAILNEWS', 1);
define('MK_FREE', 2);
define('MK_PAY', 3);
define('MK_ASSISTANT', 4);

// ����������ȼ���
define('AG_MAIN', 1);
define('AG_SUB', 1);

define('COOKIE_EXPIRE', time()+365*24*60*60);

// ����������ʸ�����
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

// ������쥯��
function redirect($url) {
	header("location: $url");
	exit;
}

// �����ɣ�
function make_id($id, $len) {
	return substr(str_repeat('0', $len) . $id, -$len);
}

// SQL�����ॹ����׼���
function sql_time($time) {
	return strtotime(strtok($time, '.+'));
}

// value ����
function value($value) {
	return 'value="' . htmlspecialchars(trim($value)) . '"';
}

// checked ����
function checked($flag) {
	return $flag ? 'checked' : '';
}

// value, checked ����
function value_checked($value, $checked) {
	if ($value == $checked)
		return "value='$value' checked";
	else
		return "value='$value'";
}

// selected ����
function selected($flag) {
	return $flag ? 'selected' : '';
}

// value, selected ����
function value_selected($value, $selected) {
	if ($value == $selected)
		return "value='$value' selected";
	else
		return "value='$value'";
}

// disabled ����
function disabled($flag) {
	if ($flag)
		return ' disabled';
}

// ʣ���ǡ�������
function get_multi_data($name) {
	if ($name)
		$data = join(',', $name);
	return $data;
}

// ʣ���ե饰����
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

// ��å�����ɽ��
function disp_msg($msg, $pre='', $post='') {
	if (is_array($msg)) {
		foreach($msg as $str)
			echo "$pre$str$post\n";
	} elseif ($msg != '')
		echo "$pre$msg$post\n";
}

// ������
function strip_blank($str) {
	return str_replace(' ', '', mb_convert_kana($str, 's'));
}

// ��SSL�ڡ���URL����
function http_url($page) {
	$url = "http://{$_SERVER['SERVER_NAME']}{$_SERVER['PHP_SELF']}";
	$pos = strrpos($url, '/');
	return substr($url, 0, $pos + 1) . $page;
}

// SSL�ڡ���URL����
function https_url($page) {
	$url = "https://{$_SERVER['SERVER_NAME']}{$_SERVER['PHP_SELF']}";
	$pos = strrpos($url, '/');
	return substr($url, 0, $pos + 1) . $page;
}

// ���������
function set_return_page($page, $param = '') {
	$self = $_SERVER['SCRIPT_NAME'];

	if ($param != '')
		$self .= "?$param";

	$_SESSION['s_return_page'][$page] = $self;
}

// ��������
function get_return_page($def = '') {
	$page = substr(strrchr($_SERVER['SCRIPT_NAME'], '/'), 1);
	return $_SESSION['s_return_page'][$page] ? $_SESSION['s_return_page'][$page] : $def;
}
?>
