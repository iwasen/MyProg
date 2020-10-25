<?
/******************************************************
' System :�֤���������� �������������ӥ��׻�̳���ѥڡ���
' Content:�ǥ����ɴؿ�
'******************************************************/

// �桼�����ơ�����
function decode_user_status($code) {
	switch ($code) {
	case 1:
		return '����ե�����';
	case 2:
		return '��³��졼�������';
	case 3:
		return '���Ѥ��Ѥ�';
	case 7:
		return '�᡼����ã';
	case 8:
		return '�᡼����ɲ��';
	case 9:
		return '��λ�Ѥ�';
	case '':
		return '̤����';
	}
	return "����($code)";
}

// �������
function decode_oubo_shubetsu($code) {
	switch ($code) {
	case 'C':
		return '������';
	case 'P':
		return '�Ϥ���';
	case 'W':
		return '���󥿡��ͥå�';
	case 'K':
		return '��������';
	case '':
		return '̤����';
	}
	return "����($code)";
}

// ����
function decode_sex_cd($code) {
	switch ($code) {
	case '1':
		return '����';
	case '2':
		return '����';
	case '3':
		return 'ˡ��';
	case '':
		return '̤����';
	}
	return "����($code)";
}

// ����¾�Ҷ�ʬ
function decode_jisha_tasha_kubun($code) {
	switch ($code) {
	case '1':
		return '����';
	case '':
		return '¾�ҵҡ�����';
	}
	return "����($code)";
}

// �ե�����ʬ
function decode_follow_kubun($code) {
	switch ($code) {
	case '1':
		return '�����å�';
	case '2':
		return 'TEL';
	case '3':
		return 'TEL+Mail';
	case '4':
		return 'Mail';
	case '':
		return '����';
	}
	return "����($code)";
}

// ��ξ�᡼��
function decode_car_maker($code) {
	switch ($code) {
	case '01':
		return '�ȥ西';
	case '02':
		return '����';
	case '03':
		return '�ۥ��';
	case '04':
		return '��ɩ';
	case '05':
		return '�ޥĥ�';
	case '06':
		return '���Х�';
	case '07':
		return '������';
	case '08':
		return '�����ϥ�';
	case '09':
		return '������';
	case '10':
		return '͢����';
	case '99':
		return '����¾';
	case '':
		return '̤����';
	}
	return "����($code)";
}

// ��������Ź����
function decode_dealer_type($code) {
	switch ($code) {
	case '1':
		return '�ȥ西����Ź';
	case '2':
		return '�ȥ西�ʳ�����Ź';
	case '3':
		return '����';
	case '':
		return '̤����';
	}
	return "����($code)";
}

// ��Ź��Ū
function decode_raiten_mokuteki($code) {
	switch ($code) {
	case '1':
		return '�֤򸫤��褿';
	case '2':
		return '����������';
	case '3':
		return '����¾';
	case '':
		return '̤����';
	}
	return "����($code)";
}

// �����ڡ���ǧ��
function decode_campaign_ninchi($code) {
	$len = strlen($code);
	for ($i = 0; $i < $len; $i++) {
		if ($code{$i} == '1') {
			if ($str)
				$str .= '��';

			switch ($i + 1) {
			case 1:
				$str .= '�ԣ�';
				break;
			case 2:
				$str .= '��ʹ';
				break;
			case 3:
				$str .= '����';
				break;
			case 4:
				$str .= '���饷';
				break;
			case 5:
				$str .= '�ģ�';
				break;
			case 6:
				$str .= '���󥿡��ͥå�';
				break;
			case 7:
				$str .= '��������';
				break;
			case 8:
				$str .= '��Ź��';
				break;
			case 9:
				$str .= '����¾';
				break;
			case 10:
				$str .= 'ͽ��';
				break;
			}
		}
	}
	return $str;
}

// �����󶡵���
function decode_jouhou_teikyou_kyodaku($code) {
	switch ($code) {
	case '1':
		return '��˾���ʤ�';
	case '':
		return '̤����';
	}
	return "����($code)";
}

// ����
function decode_privilege($code) {
	switch ($code) {
	case PRIV_DHSTAFF:
		return 'DH�����å�';
	case PRIV_OPERATOR:
		return '���ڥ졼��';
	case '':
		return '̤����';
	}
	return '����';
}

// �����åե��ơ�����
function decode_staff_status($code) {
	switch ($code) {
	case 0:
		return 'ͭ��';
	case 9:
		return '̵��';
	case '':
		return '̤����';
	}
	return "����($code)";
}

// ���ߥ�˥�����������
function decode_comm_type($code) {
	switch ($code) {
	case 1:
		return '����Ź�᡼��';
	case 2:
		return '����ե����᡼��';
	case 3:
		return '��³��졼�����᡼��';
	case 4:
		return 'OneToOne�᡼��';
	case 5:
		return '�䤤��碌�᡼��';
	case '':
		return '̤����';
	}
	return "����($code)";
}

// ���ߥ�˥����������̡�û�̷���
function decode_comm_type2($code) {
	switch ($code) {
	case 1:
		return '����Ź';
	case 2:
		return '���';
	case 3:
		return '��³';
	case 4:
		return '1to1';
	case 5:
		return '��礻';
	case '':
		return '̤����';
	}
	return "����($code)";
}

// ������
function decode_send_recv($code) {
	switch ($code) {
	case 'R':
		return '����';
	case 'S':
		return '����';
	case '':
		return '̤����';
	}
	return "����($code)";
}

// ����Ź
function decode_dealer($code) {
	$sql = "SELECT dlr_dealer_name FROM t_dealer WHERE dlr_dealer_cd='$code'";
	$result = db_exec($sql);
	return pg_fetch_result($result, 0, 0);
}

// �ּ�
function decode_car_model($code) {
	$sql = "SELECT crm_car_model_name FROM t_car_model WHERE crm_car_model_cd=$code";
	$result = db_exec($sql);
	return pg_fetch_result($result, 0, 0);
}

// �ּ參�ƥ���
function decode_car_category($code) {
	$sql = "SELECT crc_car_category_name FROM t_car_category WHERE crc_car_category_cd=$code";
	$result = db_exec($sql);
	return pg_fetch_result($result, 0, 0);
}
?>