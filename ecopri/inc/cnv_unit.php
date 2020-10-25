<?
/******************************************************
' System :Eco-footprint 共通
' Content:単位変換
'******************************************************/

// 電気単位変換（Mcal->kWh）
function ele_mcal_kwh($mcal) {
	return $mcal / 0.86;
}

// 電気単位変換（kWh->Mcal）
function ele_kwh_mcal($kwh) {
	return $kwh * 0.86;
}

// ガス単位変換（Mcal->ｍ3）
function gas_mcal_m3($mcal) {
	return $mcal / gas_calorie();
}

// ガス単位変換（ｍ3->Mcal）
function gas_m3_mcal($m3) {
	return $m3 * gas_calorie();
}

// 灯油単位変換（Mcal->L）
function oil_mcal_l($mcal) {
	return $mcal / 8.9;
}

// 灯油単位変換（L->Mcal）
function oil_l_mcal($l) {
	return $l * 8.9;
}

// ガソリン単位変換（Mcal->L）
function gso_mcal_l($mcal) {
	return $mcal / 8.4;
}

// ガソリン単位変換（L->Mcal）
function gso_l_mcal($l) {
	return $l * 8.4;
}

// 軽油単位変換（Mcal->L）
function dsl_mcal_l($mcal) {
	return $mcal / 9.2;
}

// 軽油単位変換（L->Mcal）
function dsl_l_mcal($l) {
	return $l * 9.2;
}
// ガス標準熱量取得
function gas_calorie($mb_seq_no = 0) {
	if ($mb_seq_no == 0)
		$mb_seq_no = $_SESSION['ss_seq_no'];

	$sql = "SELECT mb_gas_kind_cd FROM t_member WHERE mb_seq_no=$mb_seq_no";
	switch (db_fetch1($sql)) {
	case 1:		// 都市ガス
		$sql = "SELECT gt_calorie FROM t_member JOIN m_gas_type ON mb_gas_type_cd=gt_gas_type_cd WHERE mb_seq_no=$mb_seq_no";
		$calorie = db_fetch1($sql);
		break;
	case 2:		// LPガス
		$calorie = 26.0;
		break;
	}
	return $calorie;
}
?>