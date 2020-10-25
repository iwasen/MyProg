<?
/******************************************************
' System :Eco-footprint ����
' Content:���ⴹ��
'******************************************************/

// ���ͥ륮��ʬ�ॳ����
define('ENG_TYPE_1', 1);		// �ŵ��ʰ���������
define('ENG_TYPE_2', 2);		// �ŵ�(����������
define('ENG_TYPE_3', 3);		// �Իԥ���
define('ENG_TYPE_4', 4);		// �̣Х���
define('ENG_TYPE_5', 5);		// ��ƻ
define('ENG_TYPE_6', 6);		// �����ʵ��򥢥��
define('ENG_TYPE_7', 7);		// ����(����ʥ���
define('ENG_TYPE_8', 8);		// ����
define('ENG_TYPE_9', 9);		// �������
define('ENG_TYPE_10', 10);	// ����

// ����勵���ץ�����
define('BOILER_TYPE_1', 1);
define('BOILER_TYPE_2', 2);

// �������̥�����
define('GAS_KIND_1', 1);
define('GAS_KIND_2', 2);

// �ּ拾����
define('CAR_TYPE_1', 1);
define('CAR_TYPE_2', 2);

// �ŵ�[kWh]
function cnv_ele_mny($use, $boiler_type = 0, $mb_seq_no = 0) {
	// ����勵���׼���
	if ($boiler_type == -1) {
		if ($mb_seq_no == 0)
			$mb_seq_no = $_SESSION['ss_seq_no'];
		$sql = "SELECT mb_boiler_cd FROM t_member WHERE mb_seq_no=$mb_seq_no";
		$boiler_type = db_fetch1($sql);
	}

	// ���ͥ륮��ʬ�����
	switch ($boiler_type) {
	case BOILER_TYPE_1:
	case BOILER_TYPE_2:
		$eng_type = ENG_TYPE_2;
		break;
	default:
		$eng_type = ENG_TYPE_1;
		break;
	}

	// ñ������
	$price_unit = get_price_unit($eng_type, $mb_seq_no);

	// �����׻�
	return $use * $price_unit;
}

// ����[��3]
function cnv_gas_mny($use, $mb_seq_no = 0) {
	// ����ֹ����
	if ($mb_seq_no == 0)
		$mb_seq_no = $_SESSION['ss_seq_no'];

	// �������̼���
	$sql = "SELECT mb_gas_kind_cd FROM t_member WHERE mb_seq_no=$mb_seq_no";
	$gas_kind_cd = db_fetch1($sql);

	// ���ͥ륮��ʬ�����
	switch ($gas_kind_cd) {
	case GAS_KIND_1:
		$eng_type = ENG_TYPE_3;
		break;
	case GAS_KIND_2:
		$eng_type = ENG_TYPE_4;
		break;
	default:
		return 0;
	}

	// ñ������
	$price_unit = get_price_unit($eng_type, $mb_seq_no);

	// �����׻�
	switch ($gas_kind_cd) {
	case GAS_KIND_1:
		return $use * $price_unit * gas_calorie($mb_seq_no);
	case GAS_KIND_2:
		return $use * $price_unit;
	}
}

// ��ƻ[��3]
function cnv_wtr_mny($use, $mb_seq_no = 0) {
	// ñ������
	$price_unit = get_price_unit(ENG_TYPE_5, $mb_seq_no);

	// �����׻�
	return $use * $price_unit;
}

// ����[L]
function cnv_oil_mny($use, $boiler_type = '') {
	// ���ͥ륮��ʬ�����
	if ($boiler_type) {
		switch ($boiler_type) {
		case 3:
			$eng_type = ENG_TYPE_6;
			break;
		default:
			$eng_type = ENG_TYPE_7;
			break;
		}
	} else {
		$sql = "SELECT mb_boiler_cd FROM t_member WHERE mb_seq_no={$_SESSION['ss_seq_no']}";
		switch (db_fetch1($sql)) {
		case 5:		// �����������
			$eng_type = ENG_TYPE_6;
			break;
		default:
			$eng_type = ENG_TYPE_7;
			break;
		}
	}

	// ñ������
	$price_unit = get_price_unit($eng_type);

	// �����׻�
	return $use * $price_unit;
}

// �������[L]
function cnv_gso_mny($use, $car_type_cd = '') {
	if ($car_type_cd) {
		// ���ͥ륮��ʬ�����
		switch ($car_type_cd) {
		case CAR_TYPE_2:
			$eng_9 = 0;
			$eng_10 = 1;
			break;
		default:
			$eng_9 = 1;
			$eng_10 = 0;
			break;
		}
	} else {
		$sql = "SELECT mb_gcar_num,mb_dcar_num FROM t_member WHERE mb_seq_no={$_SESSION['ss_seq_no']}";
		$result = db_exec($sql);
		if (pg_numrows($result)) {
			$fetch = pg_fetch_object($result, 0);
			$car_num = $fetch->mb_gcar_num + $fetch->mb_dcar_num;
			if ($car_num) {
				$eng_9 = $fetch->mb_gcar_num / $car_num;
				$eng_10 = $fetch->mb_dcar_num / $car_num;
			} else {
				$eng_9 = 1;
				$eng_10 = 0;
			}
		}
	}

	// ñ������
	$price_unit_9 = get_price_unit(ENG_TYPE_9);
	$price_unit_10 = get_price_unit(ENG_TYPE_10);

	// �����׻�
	return $use * ($price_unit_9 * $eng_9 + $price_unit_10 * $eng_10);
}

// ñ������[\]
function get_price_unit($eng_type, $mb_seq_no = 0) {
	if ($mb_seq_no == 0)
		$mb_seq_no = $_SESSION['ss_seq_no'];

	$sql = "SELECT pr_price_unit"
			. " FROM t_member"
			. " JOIN m_area ON mb_area_cd=ar_area_cd"
			. " JOIN m_price_rate ON ar_area2_cd=pr_area2_cd"
			. " WHERE pr_eng_type_cd=$eng_type AND mb_seq_no=$mb_seq_no";
	return db_fetch1($sql);
}
?>