<?
/******************************************************
' System :Eco-footprint ����
' Content:ñ���Ѵ�
'******************************************************/

// �ŵ�ñ���Ѵ���Mcal->kWh��
function ele_mcal_kwh($mcal) {
	return $mcal / 0.86;
}

// �ŵ�ñ���Ѵ���kWh->Mcal��
function ele_kwh_mcal($kwh) {
	return $kwh * 0.86;
}

// ����ñ���Ѵ���Mcal->��3��
function gas_mcal_m3($mcal) {
	return $mcal / gas_calorie();
}

// ����ñ���Ѵ��ʣ�3->Mcal��
function gas_m3_mcal($m3) {
	return $m3 * gas_calorie();
}

// ����ñ���Ѵ���Mcal->L��
function oil_mcal_l($mcal) {
	return $mcal / 8.9;
}

// ����ñ���Ѵ���L->Mcal��
function oil_l_mcal($l) {
	return $l * 8.9;
}

// �������ñ���Ѵ���Mcal->L��
function gso_mcal_l($mcal) {
	return $mcal / 8.4;
}

// �������ñ���Ѵ���L->Mcal��
function gso_l_mcal($l) {
	return $l * 8.4;
}

// ����ñ���Ѵ���Mcal->L��
function dsl_mcal_l($mcal) {
	return $mcal / 9.2;
}

// ����ñ���Ѵ���L->Mcal��
function dsl_l_mcal($l) {
	return $l * 9.2;
}
// ����ɸ��Ǯ�̼���
function gas_calorie($mb_seq_no = 0) {
	if ($mb_seq_no == 0)
		$mb_seq_no = $_SESSION['ss_seq_no'];

	$sql = "SELECT mb_gas_kind_cd FROM t_member WHERE mb_seq_no=$mb_seq_no";
	switch (db_fetch1($sql)) {
	case 1:		// �Իԥ���
		$sql = "SELECT gt_calorie FROM t_member JOIN m_gas_type ON mb_gas_type_cd=gt_gas_type_cd WHERE mb_seq_no=$mb_seq_no";
		$calorie = db_fetch1($sql);
		break;
	case 2:		// LP����
		$calorie = 26.0;
		break;
	}
	return $calorie;
}
?>