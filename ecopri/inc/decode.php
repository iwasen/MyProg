<?
/******************************************************
' System :Eco-footprint ����
' Content:�ǥ����ɽ���
'******************************************************/

// admin���ơ�����
function decode_admin_status($code, $def='����ʤ�') {
	switch ($code) {
	case 0:
		return 'ͭ��';
	case 9:
		return '̵��';
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
	}
	return $def;
}

// ̤����
function decode_mikikon($code, $def='����ʤ�') {
	switch ($code) {
	case 1:
		return '̤��';
	case 2:
		return '����';
	}
	return $def;
}

// AND/OR
function decode_andor($code, $def='����ʤ�') {
	switch ($code) {
	case 'A':
		return 'AND';
	case 'O':
		return 'OR';
	}
	return $def;
}
// �ϰ���̳�ƻ�����̎�������
function decode_area2($code) {
	if ($code != '') {
		$sql = "SELECT aa_area2_name FROM m_area2 WHERE aa_area2_cd=$code";
		$name = db_fetch1($sql);
	}
	return $name;
}

// ��ƻ�ܸ�
function decode_area($code) {
	if ($code != '') {
		$sql = "SELECT ar_area_name FROM m_area WHERE ar_area_cd=$code";
		$name = db_fetch1($sql);
	}
	return $name;
}

// ��ƻ�ܸ������ɤ����ϰ���̳�ƻ�����̎�������
function decode_area3($code) {
	if ($code != '') {
		$sql = "SELECT aa_area2_name FROM m_area2 JOIN m_area ON aa_area2_cd=ar_area2_cd WHERE ar_area_cd=$code";
		$name = db_fetch1($sql);
	}
	return $name;
}

// ��²³��
function decode_family_rel($code) {
	if ($code != '') {
		$sql = "SELECT fr_name FROM m_family_rel WHERE fr_family_rel_cd=$code";
		$name = db_fetch1($sql);
	}
	return $name;
}

// �������
function decode_keitai($code) {
	if ($code != '') {
		$sql = "SELECT kt_keitai_name FROM m_keitai WHERE kt_keitai_cd=$code";
		$name = db_fetch1($sql);
	}
	return $name;
}

// �����Ǻ�
function decode_sozai($code) {
	if ($code != '') {
		$sql = "SELECT sz_sozai_name FROM m_sozai WHERE sz_sozai_cd=$code";
		$name = db_fetch1($sql);
	}
	return $name;
}

// ����ǯ��
function decode_chikunen($code) {
	if ($code != '') {
		$sql = "SELECT cn_chikunen_text FROM m_chikunen WHERE cn_chikunen_cd=$code";
		$name = db_fetch1($sql);
	} else {
		$name = '̤����';
	}
	return $name;
}

// �ｻ����
function decode_space($code) {
	if ($code != '') {
		$sql = "SELECT sp_space_text FROM m_space WHERE sp_space_cd=$code";
		$name = db_fetch1($sql);
	}
	return $name;
}

// ����勵����
function decode_boiler($code) {
	if ($code != '') {
		$sql = "SELECT bt_boiler_name FROM m_boiler_type WHERE bt_boiler_type_cd=$code";
		$name = db_fetch1($sql);
	}
	return $name;
}

// ������
function decode_gas_kind($code) {
	if ($code != '') {
		$sql = "SELECT gk_gas_kind_name FROM m_gas_kind WHERE gk_gas_kind_cd=$code";
		$name = db_fetch1($sql);
	}
	return $name;
}

// ����������
function decode_gas_type($code) {
	if ($code != '') {
		$sql = "SELECT gt_gas_type_name FROM m_gas_type WHERE gt_gas_type_cd=$code";
		$name = db_fetch1($sql);
	}
	return $name;
}

// ��ƻ���������
function decode_water($code) {
	switch ($code) {
	case 1:
		return '�����';
	case 2:
		return '������';
	}
	return $def;
}

// ���ͥ륮��ʬ�ॳ����
function decode_energy($code) {
	if ($code != '') {
		$sql = "SELECT en_text FROM m_energy WHERE en_class_cd=$code";
		$name = db_fetch1($sql);
	}
	return $name;
}

// ��Ͽ����
function decode_regist($code, $def='����') {
	switch ($code) {
	case 1:
		return '̤��Ͽ';
	case 2:
		return '��Ͽ�Ѥ�';
	}
	return $def;
}

// ��²���ƥ���
function decode_family_ctg($code) {
	if ($code != '') {
		$sql = "SELECT fc_text FROM m_family_ctg WHERE fc_family_ctg_cd=$code";
		$name = db_fetch1($sql);
	}
	return $name;
}

// �ּ��
function decode_room($code) {
	if ($code != '') {
		$sql = "SELECT rm_room_name FROM m_room WHERE rm_room_cd=$code";
		$name = db_fetch1($sql);
	} else
		$name = '̤����';
	return $name;
}

// ��������
function decode_input($code) {
	if ($code == '')
		$def = '̤����';
	elseif ($code == 1)
		$def = '���Ϥ���';
	elseif ($code == '0')
		$def = '���Ϥ��ʤ�';
	return $def;
}

// ���ͥ륮��
function deocde_eng($code) {
	if ($code == 1)
		$def = '�ŵ�';
	elseif ($code == 2)
		$def = '����';
	elseif ($code == 3)
		$def = '��ƻ';
	elseif ($code == 4)
		$def = '����';
	elseif ($code == 5)
		$def = '�������';
	elseif ($code == 6)
		$def = '����';
	return $def;
}

// ʬ��
function decode_bunrui($code) {
	if ($code == 1)
		$def = '����';
	elseif ($code == 2)
		$def = '���';
	elseif ($code == 3)
		$def = '�Ȼ�';
	elseif ($code == 4)
		$def = '';
	elseif ($code == 5)
		$def = '��Ŭ����';
	elseif ($code == 6)
		$def = '����';
	return $def;
}

// �����ͳ
function decode_taikai($code) {
	if ($code != '') {
		$sql = "SELECT ta_reason FROM m_taikai WHERE ta_taikai_cd=$code";
		$name = db_fetch1($sql);
	} else
		$name = '����';
	return $name;
}

?>