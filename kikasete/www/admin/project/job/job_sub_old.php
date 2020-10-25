<?
function decode_mail_format($code) {
	switch ($code) {
	case 0:
		return 'TEXT';
	case 1:
		return 'HTML';
	}
	return '����ʤ�';
}

function decode_job_type($code) {
	switch ($code) {
	case 0:
		return 'ȯ�������';
	case 1:
		return '���󥱡��ȥ쥹�ݥ󥹥����';
	case 2:
		return '����å��쥹�ݥ󥹥����';
	}
	return '����ʤ�';
}

function show_and_or($data) {
	switch ($data) {
	case 'A':
		echo 'AND';
		break;
	case 'O':
		echo 'OR';
		break;
	default:
		echo '����ʤ�';
		break;
	}
}

function show_virgin_mail($data) {
	switch ($data) {
	case 0:
		echo '��¸����Τ�';
		break;
	Case 1:
		echo '��������Τ�';
		break;
	Case 2:
		echo '��¸����ȿ������';
		break;
	default:
		echo '����ʤ�';
		break;
	}
}

function show_send_timing($timing, $timing_h, $timing_d) {
	switch ($timing) {
	case 1:
		echo '�����塢ȯ��������������Ƥˡ����ȤϤ������١���������';
		break;
	case 2:
		echo "�������֤��� $timing_h ���ָ�˸�������";
		break;
	case 3:
		echo "���������� $timing_d ����˸�������";
		break;
	default:
		echo '����ʤ�';
		break;
	}
}

// �귿�����ֹ�ɽ��
function show_teikei_question_no($qno) {
	if ($qno == '')
		echo '����ʤ�';
	else
		echo $qno;
}

// �귿������ֹ�ɽ��
function show_teikei_answer_no($ano) {
	for ($i = 0; $i < 15; $i++) {
		if ($ano{$i} == '1') {
			if ($s != '')
				$s .= ',';
			$s .= $i + 1;
		}
	}
	if ($s == '')
		$s = '����ʤ�';

	echo $s;
}

function show_monitor_type($data) {
	echo decode_monitor_type($data, '����ʤ�');
}

function show_sex($data) {
	echo decode_sex($data, '����ʤ�');
}

function show_age($from, $to) {
	if ($from != '' || $to != '') {
		if ($from != '')
			echo "${from}��";

			echo '��';

		if ($to != '')
			echo "${to}��";
	} else
		echo '����ʤ�';
}

function show_mikikon($data) {
	echo decode_mikikon($data, '����ʤ�');
}

function show_name($sql) {
	$result = db_exec($sql);
	$nrow = pg_numrows($result);
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_row($result, $i);
		echo "$fetch[0]��";
	}
}

function show_shokugyou($data) {
	if ($data != '') {
		$sql = "SELECT sg_shokugyou_name FROM m_shokugyou WHERE sg_shokugyou_cd IN ($data) ORDER BY sg_order";
		show_name($sql);
	} else
		echo '����ʤ�';
}

function show_gyoushu($data) {
	if ($data != '') {
		$sql = "SELECT gs_gyoushu_name FROM m_gyoushu WHERE gs_gyoushu_cd IN ($data) ORDER BY gs_order";
		show_name($sql);
	} else
		echo '����ʤ�';
}

function show_shokushu($data) {
	if ($data != '') {
		$sql = "SELECT ss_shokushu_name FROM m_shokushu WHERE ss_shokushu_cd IN ($data) ORDER BY ss_order";
		show_name($sql);
	} else
		echo '����ʤ�';
}

function show_area($data) {
	if ($data != '')
		echo str_replace(',', ' ', str_replace('/', '', $data));
	else
		echo '����ʤ�';
}

function show_genre($data) {
	if ($data != '') {
		$sql = "SELECT kg_name FROM m_genre WHERE kg_genre_cd IN ($data) ORDER BY kg_order";
		show_name($sql);
	} else
		echo '����ʤ�';
}

function show_conveni($data) {
	if ($data != '') {
		$sql = "SELECT cv_name FROM m_conveni WHERE cv_conveni_cd IN ($data) ORDER BY cv_order";
		show_name($sql);
	} else
		echo '����ʤ�';
}

function show_super($data) {
	if ($data != '') {
		$sql = "SELECT sp_name FROM m_super WHERE sp_super_cd IN ($data) ORDER BY sp_order";
		show_name($sql);
	} else
		echo '����ʤ�';
}

function show_family_sex($man, $woman) {
	if ($man == 't' || $woman == 't') {
		if ($man == 't')
			echo '������';
		if ($woman == 't')
			echo '����';
	} else
		echo '����ʤ�';
}

function show_family_rel($data) {
	if ($data != '') {
		$sql = "SELECT fr_name FROM m_family_rel WHERE fr_family_rel_cd IN ($data) ORDER BY fr_family_rel_cd";
		show_name($sql);
	} else
		echo '����ʤ�';
}

function show_child($data) {
	if ($data == 't')
		echo '����';
	elseif ($data == 'f')
		echo '�ʤ�';
	else
		echo '����ʤ�';
}

function show_condition($data) {
	if ($data != '')
		echo $data;
	else
		echo '����ʤ�';
}
?>