<?
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
	return strtotime(strtok($time, '+'));
}

// ���եե����ޥå�
function format_date($date) {
	if ($date == null)
		return '����ʤ�';
	else
		return  date('Y/m/d', sql_time($date));
}

// �����ե����ޥå�
function format_datetime($date) {
	if ($date == null)
		return '����ʤ�';
	else
		return date('Y/m/d H:i', sql_time($date));
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

// disabled ����
function disabled($flag) {
	if ($flag)
		return ' disabled';
}
?>