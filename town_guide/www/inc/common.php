<?
/******************************************************
' System :�����󥬥���Web���󥱡���
' Content:���̥��󥯥롼�ɥե�����
'******************************************************/

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
	if ($value == $selected)
		return "value='$value' selected";
	else
		return "value='$value'";
}
?>