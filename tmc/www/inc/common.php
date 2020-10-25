<?
/******************************************************
' System :�֤���������� �������������ӥ��׻�̳���ѥڡ���
' Content:�������Ѵؿ�
'******************************************************/

define('SYSTEM_NAME', '����������ӡ��������������ӥ�');
define('COOKIE_EXPIRE', time()+365*24*60*60);
define('MAX_THREAD_DEPTH', 100);
define('MAX_FOLLOW_MAIL', 4);
define('MAX_RELATION_MAIL', 30);

// ������쥯��
function redirect($url) {
	header("location: $url");
	exit;
}

// SQL�����ॹ����׼���
function sql_time($time) {
	return strtotime(strtok($time, '.+'));
}

// value ����
function value($value) {
	return 'value="' . htmlspecialchars(trim($value)) . '"';
}

// value, checked ����
function value_checked($value, $checked) {
	if ($value == $checked)
		return "value='$value' checked";
	else
		return "value='$value'";
}

// value, selected ����
function value_selected($value, $selected) {
	if ($value == $selected)
		return "value='$value' selected";
	else
		return "value='$value'";
}

// checked ����
function checked($flag) {
	return $flag ? 'checked' : '';
}

// disabled ����
function disabled($flag) {
	if ($flag)
		return ' disabled';
}

// �Ǹ�����פ�CRLF����
function trim_crlf(&$text) {
	return rtrim($text) . "\r\n";
}

// Not Found
function http_not_found() {
	header("HTTP/1.0 404 Not Found");
	exit;
}
?>