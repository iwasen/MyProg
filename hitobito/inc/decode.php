<?php
/**
 * �ҤȤӤȎ�net ���̥ե�����
 *
 * �ǥ����ɽ���
 *
 *
 * @package
 * @author
 * @version
 */

// �ҤȤӤ��ؤ꾵ǧ
function decode_letter_status1($code, $def='') {
	switch ($code) {
	case '1':
	case '2':
		return '̤�ۿ�';
	case '3':
		return '�ۿ���';
	case '4':
		return '�ۿ���';
	}
	return $def;
}

// �ҤȤӤ��ؤ�ȯ������
function decode_letter_status2($code) {
	if ($code == '1')
		return '̤��ǧ';
	else
		return '��ǧ';
}

// �ҤȤӤ��ؤ�Хå��ʥ�С�
function decode_backnumber($code, $def='') {
	switch($code) {
	case '0':
		return '��Ǻ�';
	case '1':
		return '�Ǻ�';
	}
	return $def;
}

// �ʥӱ��祹�ơ�����
function decode_navi_page_status($code, $def='') {
	switch($code) {
	case '0':
		return '̤��ǧ';
	case '1':
		return '��ǧ��';
	case '2':
		return '���Ǥ�';
	}
	return $def;
}

// �ʥӥ���ƥ�ĥ����å��ե饰
function decode_navi_contents_check($code, $def='') {
	switch($code) {
	case '0':
		return '̤����';
	case '1':
		return '������';
	case '2':
		return '���ᤷ';
	case '3':
		return '��ǧ��';
	}
	return $def;
}

// �ʥӥڡ��������ե饰
function decode_navi_open_flag($code, $def='') {
	switch($code) {
	case '0':
		return '̤����';
	case '1':
		return '������';
	case '2':
		return '������';
	case '9':
		return '�ĺ�';
	}
	return $def;
}

// �ʥӥڡ�������
function decode_navi_page_type($code, $def='') {
	switch($code) {
	case '1':
		return 'Blog+ML';
	case '2':
		return 'Blog�Τ�';
	}
	return $def;
}

// ����ͥ�
function decode_channel($code, $def='') {
	if ($code != '') {
		$sql = "SELECT chn_cnannel_name FROM m_channel WHERE chn_channel_id=$code";
		$name = db_fetch1($sql);
	}
	return $name;
}

// ���֥���ͥ�
function decode_sub_channel($code, $def='') {
	if ($code != '') {
		$sql = "SELECT sch_subchannel_name FROM m_sub_channel WHERE sch_subchannel_id=$code";
		$name = db_fetch1($sql);
	}
	return $name;
}

// ̵ͭ�����ѡ�
function decode_umu($code, $def='') {
	switch ($code) {
	case '0':
		return '�ʤ�';
	case '1':
		return '����';
	}
	return $def;
}

// �������ơ�����
function decode_open_status($code, $def='') {
	switch($code) {
	case '0':
		return '�����';
	case '1':
		return '������';
	}
	return $def;
}

// ���С����ơ�����
function decode_member_status($code, $def = '') {
	switch ($code) {
	case '0':
		return '����Ͽ';
	case '1':
		return 'ͭ��';
	case '2':
		return '��ã����';
	case '3':
		return '������';
	case '9':
		return '���';
	}
	return $def;
}

// ���С�ML���ơ�����
function decode_member_ml_status($code, $def = '') {
	switch ($code) {
	case '1':
	case '2':
		return '��������Ͽ';
	case '3':
		return '����Ͽ';
	case '9':
		return '���';
	}
	return $def;
}

// �ҤȤӤ��ؤ��ۿ�����
function decode_hitobito_letter($code, $def = '') {
	switch ($code) {
	case '0':
		return '�ۿ����';
	case '1':
		return '�ۿ���';
	}
	return $def;
}

// ������̾
function decode_admin_name($code, $def = '') {
	if ($code != '') {
		$sql = "SELECT adm_name1 ||' '|| adm_name2 FROM m_admin WHERE adm_admin_id=$code";
		$name = db_fetch1($sql);
	}
	return $name;
}

// �ʥ�̾
function decode_navi_name($code, $def = '') {
	if ($code != '') {
		$sql = "SELECT trim(nav_name1||' '||COALESCE(nav_name2,'')) FROM t_navi WHERE nav_navi_id=$code";
		$name = db_fetch1($sql);
	}
	return $name;
}

// �ѥȥ�����̾
function decode_patrol_name($code, $def = '') {
	if ($code != '') {
		$sql = "SELECT ptl_name1 ||' '|| ptl_name2 FROM m_patrol WHERE ptl_patrol_id=$code";
		$name = db_fetch1($sql);
	}
	return $name;
}

// �ѥȥ���������å������
function decode_patrol_check($code, $def = '') {
	switch ($code) {
	case '1':
		return '̤�����å�';
	case '2':
		return '����ʤ�';
	case '3':
		return '���ꤢ��';
	case '4':
		return '�ץ����å�';
	}
	return $def;
}

// ��̳�ɥ����å������
function decode_admin_check($code, $def = '') {
	switch ($code) {
	case '1':
		return '̤�����å�';
	case '2':
		return '����ʤ�';
	case '3':
		return '���ꤢ��';
	}
	return $def;
}

// �ʥӤ��������󥯥��ƥ���
function decode_navi_osusume_link($code, $def = '') {
	if ($code != '') {
		$sql = "SELECT lct_category_name FROM m_link_category WHERE lct_link_category_id=$code";
		$name = db_fetch1($sql);
	}
	return $name;
}

// Pickup
function decode_pickup($code, $def = '') {
	if ($code == 1)
		return 'pickup';
	else
		return $def;
}

// �ʥӥ��쥯��
function decode_navi_select($code, $def = '') {
	if ($code == 1)
		return '�ʥӥ��쥯��';
	else
		return $def;
}

// ���ܼ���
function decode_contents_kind($code, $def = '') {
	switch($code) {
	case 1:
		return '��ʸ';
	case 2:
		return '�ȥ�å��Хå�';
	case 3:
		return '������';
	default:
		return $def;
	}
}

// �ʥӥڡ���̾
function decode_navi_page_name($code, $def = '') {
	if ($code != '') {
		$sql = "SELECT nvp_title FROM t_navi_page WHERE nvp_navi_page_id=$code";
		$name = db_fetch1($sql);
	}
	return $name;
}

// �䤤��碌���ƥǥ�����
function decode_inquiry_type($code, $def = '') {
	switch ($code) {
	case '1':
		return '�����ˤĤ���';
	case '2':
		return '�磻�磻��ĤˤĤ���';
	case '3':
		return '�Ż��ΰ���';
	case '4':
		return '���ΰ���';
	case '9':
		return '����¾';
	}
	return $def;
}

// �ʥӥڡ������ơ�����
function decode_navi_page_status2($status, $open_flag, $def = '') {
	switch ($status) {
	case '0':
		return '̤��ǧ';
	case '1':
		switch ($open_flag) {
		case '0':
			return '̤����';
		case '1':
			return '������';
		case '2':
			return '����������';
		}
		break;
	case '2':
		return '���Ǥ�';
	default:
		return '�ĺ�';
	}
	return $def;
}

// �磻�磻��ĥ��ơ�����
function decode_room_status($code, $def='') {
	switch($code) {
	case '1':
		return '����';
	case '2':
		return '�����';
	case '9':
		return '�ĺ�';
	}
	return $def;
}

// ���̥ǥ�����
function decode_sex_cd($code, $def='') {
	if ($code != '') {
		if ($code == 1)
			$name = '����';
		else
			$name = '����';
	}
	return $name;
}

// ͹���ֹ�ǥ�����
function decode_zip_cd($code, $def='') {
	if ($code != '') {
		$name = substr($code, 0, 3) . '-' . substr($code, 3, 4);
	}
	return $name;
}

// ��ƻ�ܸ��ǥ�����
function decode_address1($code, $def='') {
	if ($code != '') {
		$sql = "SELECT tdf_name FROM m_todofuken WHERE tdf_area_id=$code";
		$name = db_fetch1($sql);
	}
	return $name;
}
?>