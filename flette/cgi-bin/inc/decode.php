<?
/******************************************************
' System :flette�����åեڡ���
' Content:�ǥ����ɽ���
'******************************************************/

// �����åո���
function decode_staff_auth($code, $def='����ʤ�') {
	switch ($code) {
	case 1:
		return '�����ԥ����å�';
	case 2:
		return 'Ź�ޥ����å�';
	}
	return $def;
}

// ����
function decode_sex($code, $def='����ʤ�') {
	switch ($code) {
	case 1:
		return '����';
	case 2:
		return '����';
	case 3:
		return '�ä˴�˾���ʤ�';
	}
	return $def;
}

// �����󥻥顼�ѹ���˾
function decode_change($code) {
	if ($code)
		return '�̤Υ����󥻥顼���˾����';
	else
		return '�����Ʊ�������󥻥顼���˾����';
}

// �������ơ�����
function decode_room_status($code, $def='����ʤ�') {
	switch ($code) {
	case 1:
		return 'ͭ��';
	case 9:
		return '̵��';
	}
	return $def;
}

// Ź��̾
function decode_shop($code, $def='����ʤ�') {
	$sql = "SELECT sh_shop_name FROM m_shop WHERE sh_shop_id=" . sql_number($code);
	return db_fetch1($sql, $def);
}

// ����̾
function decode_room($code, $def='����ʤ�') {
	$sql = "SELECT rm_room_name FROM m_room WHERE rm_room_id=" . sql_number($code);
	return db_fetch1($sql, $def);
}

// ������̾
function decode_course($code, $def='����ʤ�') {
	$sql = "SELECT co_course_name FROM m_course WHERE co_course_id=" . sql_number($code);
	return db_fetch1($sql, $def);
}

// ����
function decode_week($code, $def='����ʤ�') {
	$week = array('��', '��', '��', '��', '��', '��', '��');
	if ($code >= 0 && $code < 7)
		return $week[$code];
	return $def;
}

// ��˾Ϣ�������
function decode_contact_time($code, $def='') {
	switch ($code) {
	case 1:
		return '�ä˴�˾�ʤ�';
	case 2:
		return '�������10:00��12:00��';
	case 3:
		return '����12:00��17:00��';
	case 4:
		return '���17:00��21:00��';
	case 5:
		return '����¾';
	}
	return $def;
}

// �䤤��碌��̾
function decode_inquiry_subject($code, $def='����ʤ�') {
	$sql = "SELECT iq_inquiry_text FROM m_inquiry WHERE iq_inquiry_id=" . sql_number($code);
	return db_fetch1($sql, $def);
}

// �����åե��ơ�����
function decode_staff_status($code, $def='') {
	switch ($code) {
	case 1:
		return 'ͭ��';
	case 9:
		return '̵��';
	}
	return $def;
}
?>