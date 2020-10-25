<?
/******************************************************
' System :Eco-footprint ����
' Content:CO2����
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
function cnv_ele_co2($use, $boiler_type = 0) {
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

	// �����ͼ���
	$cnv_val = get_cnv_val($eng_type);

	// CO2�ӽ��̤�׻�
	return $use * $cnv_val * 0.86;
}

// ����[��3]
function cnv_gas_co2($use) {
	// �������̼���
	$sql = "SELECT mb_gas_kind_cd FROM t_member WHERE mb_seq_no={$_SESSION['ss_seq_no']}";
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

	// �����ͼ���
	$cnv_val = get_cnv_val($eng_type);

	// CO2�ӽ��̤�׻�
	switch ($gas_kind_cd) {
	case GAS_KIND_1:
		return $use * $cnv_val * gas_calorie();
	case GAS_KIND_2:
		return $use * $cnv_val * 26.34;
	}
}

// ��ƻ[��3]
function cnv_wtr_co2($use) {
	// �����ͼ���
	$cnv_val = get_cnv_val(ENG_TYPE_5);

	// CO2�ӽ��̤�׻�
	return $use * $cnv_val;
}

// ����[L]
function cnv_oil_co2($use, $boiler_type = '') {
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

	// �����ͼ���
	$cnv_val = get_cnv_val($eng_type);

	// CO2�ӽ��̤�׻�
	return $use * $cnv_val * 8.9;
}

// �������[L]
function cnv_gso_co2($use, $car_type_cd = '') {
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

	// �����ͼ���
	$cnv_val_9 = get_cnv_val(ENG_TYPE_9);
	$cnv_val_10 = get_cnv_val(ENG_TYPE_10);

	// CO2�ӽ��̤�׻�
	return $use * ($cnv_val_9 * $eng_9 * 8.4 + $cnv_val_10 * $eng_10 * 9.2);
}

// ����[kg]
function cnv_dst_co2($use) {

	// �����ͼ���
	$cnv_val = get_cnv_val(ENG_TYPE_8);

	// CO2�ӽ��̤�׻�
	return $use * $cnv_val;
}

// �����ͼ���
function get_cnv_val($eng_type) {
	$sql = "SELECT co_co2_rate"
			. " FROM m_co2_rate"
			. " WHERE co_eng_type_cd=$eng_type";
	return db_fetch1($sql);
}
?>