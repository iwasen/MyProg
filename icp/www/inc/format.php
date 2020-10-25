<?
/******************************************************
' System :ICP���̥��󥯥롼�ɥե�����
' Content:�ե����ޥåȲ��ؿ�
'******************************************************/

// ���եե����ޥå�
function format_date($date, $null = '') {
	if ($date == null)
		return $null;
	else
		return date('Y/m/d', sql_time($date));
}

// ���եե����ޥåȡ����ܸ��
function format_date_j($date, $null = '') {
	if ($date == null)
		return $null;
	else
		return date('Yǯm��d��', sql_time($date));
}

// �����ե����ޥå�
function format_datetime($date, $null = '') {
	if ($date == null)
		return $null;
	else
		return date('Y/m/d H:i', sql_time($date));
}

// �����ե����ޥåȡ����ܸ��
function format_datetime_j($date, $null = '') {
	if ($date == null)
		return $null;
	else
		return date('Yǯm��d�� H��iʬ', sql_time($date));
}

// �����ե����ޥå�
function format_number($number) {
	if ($number < 0)
		return '<font color="red">'. number_format($number). '</font>';
	else
		return number_format($number);
}

// �ѡ������
function format_percent($a, $b, $c) {
	return number_format($b != 0 ? 100.0 * $a / $b : 0, $c) . '%';
}

// �ƥ�����
function format_text(&$text, $null = '') {
	if ($text == '')
		return $null;
	else
		return htmlspecialchars($text);
}
?>