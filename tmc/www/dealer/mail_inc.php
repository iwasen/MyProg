<?
/******************************************************
' System :�֤���������� �������������ӥ�������Ź���ѥڡ���
' Content:�᡼��ɽ���ط�include�ե�����
'******************************************************/

// �᡼�����ɽ��
function mail_type($comm_type, $send_recv) {
	if ($send_recv == 'R')
		return '1to1';
	else {
		switch ($comm_type) {
		case 1:
			return '��';
		case 2:
			return '��';
		case 3:
			return '��';
		case 4:
			return '1to1';
		case 5:
			return '��';
		}
	}
}

// �᡼�����ɽ��
function mail_type2($comm_type, $send_recv) {
	if ($send_recv == 'R')
		return '1to1';
	else {
		switch ($comm_type) {
		case 1:
			return '����';
		case 2:
			return '���';
		case 3:
			return '��³';
		case 4:
			return '1to1';
		case 5:
			return '�䤤';
		}
	}
}

// ������ɽ��
function send_name($comm_type, $send_recv, $user_name, $operator_name) {
	if ($send_recv == 'R')
		return $user_name . '����';
	else {
		if ($comm_type == 4)
			return '���ڥ졼����' . $operator_name;
		else
			return '��̳��';
	}
}

// ���������ֹ����
function get_seq_no($user_id) {
	$sql = "SELECT cml_comm_id FROM t_comm_log WHERE cml_user_id=$user_id ORDER BY cml_comm_id";
	$result = db_exec($sql);
	$nrow = pg_num_rows($result);
	for ($i = 0; $i < $nrow; $i++)
		$seq_ary[pg_fetch_result($result, $i, 0)] = sprintf('%03d', $i + 1);

	return $seq_ary;
}
?>