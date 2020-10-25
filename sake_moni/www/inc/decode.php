<?
/******************************************************
' System :�������ʪ�Υ��󥱡��ȥ�˥�������
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

// ��˥�������
function decode_net_kind($code, $def='����ʤ�') {
	switch ($code) {
	case 1:
		return '�������ơ�net';
	case 2:
		return 'iMi�ͥå�';
	}
	return $def;
}

// ��˥�������
function decode_monitor_status($code, $def='����ʤ�') {
	switch ($code) {
	case 0:
		return 'ͭ��';
	case 2:
		return '��ã����';
	case 8:
		return '�ƥ���';
	case 9:
		return '���';
	}
	return $def;
}

// �����åռ���
function decode_staff_type($code, $def='����ʤ�') {
	switch ($code) {
	case 1:
		return '�᡼��˥塼����Ͽ';
	case 2:
		return '̵����Ͽ';
	case 3:
		return 'My�ڡ�����Ͽ';
	case 4:
		return '�����������';
	}
	return $def;
}

// �����åվ���
function decode_staff_status($code, $def='����ʤ�') {
	switch ($code) {
	case 0:
		return 'ͭ��';
	case 2:
		return '��ã����';
	case 8:
		return '�ƥ���';
	case 9:
		return '���';
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

// �Ҷ���̵ͭ
function decode_child($code, $def='����ʤ�') {
	switch ($code) {
	case 1:
		return 'ͭ��';
	case 2:
		return '̵��';
	}
	return $def;
}

// ��ϢĴ���ؤλ���
function decode_research($code, $def='����ʤ�') {
	switch ($code) {
	case 1:
		return '��ǽ';
	case 2:
		return '�Բ�ǽ';
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

// �ϰ�
function decode_area($code) {
	if ($code != '') {
		$sql = "SELECT ar_area_name FROM m_area WHERE ar_area_cd=$code";
		$name = db_fetch1($sql);
	}
	return $name;
}

// ����
function decode_shokugyou($code) {
	if ($code != '') {
		$sql = "SELECT sg_shokugyou_name FROM m_shokugyou WHERE sg_shokugyou_cd=$code";
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

// ���󥱡��ȼ���
function decode_enq_type($code, $def='����') {
	switch ($code) {
	case '0':
		return '���󥱡���̵��';
	case '1':
		return '�᡼�륢�󥱡���';
	case '2':
		return '�ף�⥢�󥱡���';
	}
	return $def;
}

// ���󥱡��ȼ��̡�û�̡�
function decode_enq_type2($code, $def='����') {
	switch ($code) {
	case '0':
		return '̵��';
	case '1':
		return '�᡼��';
	case '2':
		return '�ף��';
	}
	return $def;
}

// ���󥱡��Ⱦ���
function decode_enq_status($code, $def='����') {
	switch ($code) {
	case 0:
		return '������';
	case 1:
		return '������λ';
	case 2:
		return '������';
	case 3:
		return '��̳�ɥ����å���';
	case 4:
		return '��ǧ�Ѥ�';
	case 5:
		return '�»���';
	case 6:
		return '������';
	case 7:
		return '��λ';
	case 8:
		return '�ƿ���';
	case 9:
		return '����Ѥ�';
	}
	return $def;
}

// �����ʧ��ˡ
function decode_payment_type($code, $def='') {
	switch ($code) {
	case 1:
		return 'iMi�ݥ����';
	case 2:
		return '͹�ذ���';
	case 3:
		return '�ͥåȥХ�';
	}
	return $def;
}

// ����
function decode_gyoutai($code) {
	if ($code != '') {
		$sql = "SELECT gt_gyoutai_name FROM m_gyoutai WHERE gt_gyoutai_cd=$code";
		$name = db_fetch1($sql);
	}
	return $name;
}

// �������󥨥ꥢ
function decode_chain_area($code) {
	if ($code != '') {
		$sql = "SELECT ca_area_name FROM m_chain_area WHERE ca_chain_area_cd=$code";
		$name = db_fetch1($sql);
	}
	return $name;
}

// ���ꥢ̾�ʥ���޶��ڤ�������󥳡��ɡ�
function decode_chain2area($chain_str){
	if ($chain_str){
		$chain_ary = explode(',', $chain_str);

		foreach($chain_ary as $val){
			$sql = "SELECT ch_chain_area_cd FROM m_chain WHERE ch_chain_cd=$val";
			$c_area_cd = db_fetch1($sql);
			$c_area_ary[] = decode_chain_area($c_area_cd);
		}
		$c_area_ary = array_unique($c_area_ary);
		$c_area_str = implode(',', $c_area_ary);
		return $c_area_str;
	}
}

// ����̾�ʥ���޶��ڤ�������󥳡��ɡ�
function decode_chain2gyoutai($chain_str){
	if ($chain_str){
		$chain_ary = explode(',', $chain_str);

		foreach($chain_ary as $val){
			$sql = "SELECT ch_gyoutai_cd FROM m_chain WHERE ch_chain_cd=$val";
			$g_cd = db_fetch1($sql);
			$g_ary[] = decode_gyoutai($g_cd);
		}
		$g_ary = array_unique($g_ary);
		$g_str = implode(',', $g_ary);
		return $g_str;
	}
}

// ��������̾�ʥ���޶��ڤ�������󥳡��ɡ�
function decode_chain($chain_str){
	if ($chain_str){
		$chain_ary = explode(',', $chain_str);

		foreach($chain_ary as $val){
			$sql = "SELECT ch_name FROM m_chain WHERE ch_chain_cd=$val";
			$ch_ary[] = db_fetch1($sql);
		}
		$ch_ary = array_unique($ch_ary);
		$ch_str = implode(',', $ch_ary);
		return $ch_str;
	}

}
?>