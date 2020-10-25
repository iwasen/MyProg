<?
/******************************************************
' System :Eco-footprint ����
' Content:�ե����ޥåȽ���
'******************************************************/

// ����(YYYY/MM/DD)
function format_date($date, $def='����ʤ�') {
	if ($date == '')
		return $def;
	else
		return  date('Y/m/d', sql_time($date));
}

// ����(YYYY/MM/DD hh:mm)
function format_datetime($date, $def='����ʤ�') {
	if ($date == '')
		return $def;
	else
		return date('Y/m/d H:i', sql_time($date));
}

// ͹���ֹ�(999-9999)
function format_zip($zip, $def='') {
	if ($zip == '')
		return $def;
	else
		return substr($zip, 0, 3) . '-' . substr($zip, 3, 4);
}

// �����ֹ�(99-9999-9999)
function format_tel($tel1, $tel2, $tel3, $def='') {
	if ($tel1 == '' && $tel2 == '' && $tel3 == '')
		return $def;
	else
		return "$tel1-$tel2-$tel3";
}
?>