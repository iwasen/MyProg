<?
/******************************************************
' System :�֤�����Factory�ץ��󥯥롼�ɥե�����
' Content:�ǥ����ɽ���
'******************************************************/

// ����
function decode_sex($code) {
	if ($code != '') {
		if ($code == 1)
			return '����';
		else
			return '����';
	}
}

// �����åս�°����
function decode_staff_kind($code) {
	if ($code != '') {
		$sql = "SELECT sk_name FROM m_staff_kind WHERE sk_kind_cd=$code";
		$name = db_fetch1($sql);
	}
	return $name;
}

// �����åս�°
function decode_staff_org($code) {
	if ($code != '') {
		$sql = "SELECT so_name FROM m_staff_org WHERE so_org_cd=$code";
		$name = db_fetch1($sql);
	}
	return $name;
}

// �����å�̾
function decode_staff_name($code) {
	if ($code != '') {
		$sql = "SELECT (st_name1 || st_name2) AS name FROM m_staff WHERE st_staff_id=$code";
		$name = db_fetch1($sql);
	}
	return $name;
}

// �ݶ⥵����
function decode_ac_server($id) {
	if ($id != '') {
		$sql = "SELECT as_name FROM m_account_server WHERE as_server_id='$id'";
		$name = db_fetch1($sql);
	}
	return $name;
}

// ����̾
function decode_product($code) {
	if ($code != '') {
		$sql = "SELECT pd_name AS name FROM m_product WHERE pd_product_cd='$code'";
		$name = db_fetch1($sql);
	}
	return $name;
}

// �����åե᡼�륢�ɥ쥹
function decode_staff_mail($code) {
	if ($code != '') {
		$sql = "SELECT st_mail_addr FROM m_staff WHERE st_staff_id=$code";
		$name = db_fetch1($sql);
	}
	return $name;
}
?>