<?
/******************************************************
' System :flette�����åեڡ���
' Content:�ե����ޥåȲ��ؿ�
'******************************************************/

// ���եե����ޥå�
function format_date($date, $null = '') {
	if ($date == null)
		return $null;
	else
		return date('Y/m/d', sql_time($date));
}

// �����ե����ޥå�
function format_datetime($date, $null = '') {
	if ($date == null)
		return $null;
	else
		return date('Y/m/d H:i', sql_time($date));
}
?>