<?
/******************************************************
' System :�֤���������� �������������ӥ��׻�̳���ѥڡ���
' Content:�ե����ޥåȲ��ؿ�
'******************************************************/

// ���եե����ޥå�
function format_date($date) {
	if ($date == null)
		return '����ʤ�';
	else
		return date('Y/m/d', sql_time($date));
}

// �����ե����ޥå�
function format_datetime($date) {
	if ($date == null)
		return '����ʤ�';
	else
		return date('Y/m/d H:i', sql_time($date));
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
?>