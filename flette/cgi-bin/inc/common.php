<?
/******************************************************
' System :flette������
' Content:�������Ѵؿ�
'******************************************************/

define('COPYRIGHT', 'Copyright &copy; 2004 M-out Inc. Life Create Div. All rights reserved.');
define('COOKIE_EXPIRE', time()+365*24*60*60);
define('MAX_CHOICE', 3);
define('DAY_SEC', 24*60*60);

header('Content-Type: text/html;charset=EUC-JP');
mb_internal_encoding('EUC-JP');
mb_language('Japanese');

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
		return "value=\"$value\" checked";
	else
		return "value=\"$value\"";
}

// value, selected ����
function value_selected($value, $selected) {
	if ($value == $selected)
		return "value=\"$value\" selected";
	else
		return "value=\"$value\"";
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

// �ץ���२�顼����
function program_err($file, $line) {
	echo "<br>";
	echo "PROGRAM ERROR";
	echo "<br>";
	echo "FILE:$file";
	echo "<br>";
	echo "LINE:$line";

	$msg =
		"flette Error\n\n" .
		"Date: " . strftime('%c') . "\n" .
		"File: $file\n" .
		"Line: $line\n\n" .
		'$_GET: ' . serialize($_GET) . "\n\n" .
		'$_POST: ' . serialize($_POST) . "\n\n" .
		'$_SESSION: ' . serialize($_SESSION) . "\n\n" .
		'$_SERVER: ' . serialize($_SERVER) . "\n\n";
	mb_send_mail("aizawa@xxxxx-xxxx.co.jp", "flette Error", $msg);
	exit;
}
?>