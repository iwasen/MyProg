<?php
/**
 * �ҤȤӤȎ�net ���̥ե�����
 *
 * ��̳�ɶ��̴ؿ�
 *
 *
 * @package
 * @author
 * @version
 */

// ������쥯��
function redirect($url) {
	header("location: $url");
	exit;
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
	if (is_array($selected)) {
		if (in_array($value, $selected))
			return "value='$value' selected";
	} else {
		if ($value == $selected)
			return "value='$value' selected";
	}
	return "value='$value'";
}

// disabled ����
function disabled($flag) {
	if ($flag)
		return ' disabled';
}

// SQL�����ॹ����׼���
function sql_time($time) {
	return strtotime(strtok($time, '.+'));
}

// ��å�����ɽ��
function disp_msg($msg, $pre='', $post='') {
	if (is_array($msg)) {
		foreach($msg as $str)
			echo "$pre$str$post\n";
	} elseif ($msg != '')
		echo "$pre$msg$post\n";
}
?>