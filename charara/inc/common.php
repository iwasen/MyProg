<?
/******************************************************
' System :�֤�����Factory�ץ��󥯥롼�ɥե�����
' Content:�������Ѵؿ�
'******************************************************/

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