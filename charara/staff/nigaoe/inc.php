<?
// ����ɽ��
function disp_sex($code) {
	if ($code != '') {
		if ($code == 1)
			return '<font color="blue">����</font>';
		else
			return '<font color="red">����</font>';
	}
}

// ����
function disp_age($age) {
	if ($age != '')
		return $age;
	else
		return '̤����';
}

// ����
function disp_status($status) {
	switch ($status) {
	case STATUS_PHOTO_RECEIVED:
		return '̤���';
	case STATUS_STAFF_ASSIGNED:
		return '������ƺѤ�';
	case STATUS_MAKING:
		return '���鳨������';
	case STATUS_COMPLETED:
		return '���鳨����';
	case STATUS_CHECK_OK:
		return '�����å�OK';
	}
}
?>