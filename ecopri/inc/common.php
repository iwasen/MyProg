<?
/******************************************************
' System :Eco-footprint ����
' Content:���̥��󥯥롼�ɥե�����
'******************************************************/

//header('Content-Type: text/html; charset=EUC-JP');
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
?>