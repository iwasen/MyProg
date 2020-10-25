<?
/******************************************************
' System :ICP���̥��󥯥롼�ɥե�����
' Content:�ǥ����ɴؿ�
'******************************************************/

// ��ļ�������
function decode_room_type($code, $default = '̤����') {
	switch ($code) {
	case '1':
		return '�ͥåȥ��륤��';
	case '2':
		return '�����᡼�륢�󥱡���';
	case '3':
		return '�Хå��롼��';
	case '':
		return $default;
	}
	return "����($code)";
}

// ���ڥ졼�����ơ�����
function decode_operator_status($code, $default = '̤����') {
	switch ($code) {
	case '1':
		return 'ͭ��';
	case '9':
		return '̵��';
	}
	return "����($code)";
}

// �ץ������ȥ��ơ�����
function decode_pjt_status($code, $default = '̤����') {
	switch ($code) {
	case '1':
		return '��ư��';
	case '9':
		return '��λ';
	}
	return "����($code)";
}

// ����֥��ơ�����
function decode_job_status($code, $default = '̤����') {
	switch ($code) {
	case '1':
		return '������';
	case '2':
		return '������';
	case '3':
		return '��ǧ�Ѥ�';
	case '4':
		return '�Ե���';
	case '5':
		return '�»���';
	case '6':
		return '�ٻ���';
	case '7':
		return '�ƿ���ͱͽ����';
	case '9':
		return '��λ';
	}
	return "����($code)";
}

// �ȿ����ơ�����
function decode_org_status($code, $default = '̤����') {
	switch ($code) {
	case '1':
		return 'ͭ��';
	case '9':
		return '̵��';
	}
	return "����($code)";
}

// �ȿ�������
function decode_org_type($code, $default = '̤����') {
	switch ($code) {
	case '1':
		return '���';
	case '2':
		return '�ͥåȥ��';
	case '3':
		return '����¾';
	case '';
		return $default;
	}
	return "����($code)";
}

// ���⡦���ϡ���λ�᡼�륹�ơ�����
function decode_mail_status($code, $default = '̤����') {
	switch ($code) {
	case 0:
		return '̤����';
	case 1:
		return '������';
	case 2:
		return '������λ';
	case 3:
		return '�ƥ����ۿ���';
	case 9:
		return '�ۿ���λ';
	}
	return "����($code)";
}
?>