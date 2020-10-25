<?
/******************************************************
' System :ICP���̥��󥯥롼�ɥե�����
' Content:�������Ѵؿ�
'******************************************************/

ini_set('session.cache_limiter', 'none');
define('COOKIE_EXPIRE', time()+365*24*60*60);

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