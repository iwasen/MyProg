<?
/******************************************************
* system  : xxx ¥ê¥¹¥¯¿ÇÃÇ
* title   : Â»ÊÝ¾ðÊó·×»»½èÍý
* content : ¥ê¥¹¥¯¿ÇÃÇ¤Ë±÷¤±¤ëÂ»ÊÝ¾ðÊó´ØÏ¢¤Î·×»»¤ò¹Ô¤¦
* version : 1.0
* author  : aizawa
*******************************************************/

// ¢£¥ê¥¹¥¯¿ÇÃÇ¡¿Â»ÊÝ¾ðÊó¥Ç¡¼¥¿·×»»
function calc_sonpo_info($sonpo_data) {
	global $g_encode, $g_const, $g_sheets;

	$n = 'Â»ÊÝ';
	$g_sheets[$n] = array();
	$s = &$g_sheets;
	$c = &$g_sheets[$n];

	// ¥Ç¡¼¥¿
	$c['C2'] = count($sonpo_data);		// Â»ÊÝ¾ðÊó¸Ä¿ô
	$i = 0;
	foreach ($sonpo_data as $fetch) {
		$i++;

		$c['C3'][$i] = $fetch->dama_insu_id;
		$c['C4'][$i] = $fetch->areas_number;
		$c['C5'][$i] = $fetch->brand_name;
		$c['C6'][$i] = $fetch->ins_company_name;
		$c['C8'][$i] = ymd_to_date($fetch->contract_start_date);
		$c['C9'][$i] = $fetch->period_year;
		$c['C10'][$i] = ymd_to_date($fetch->contract_period_date);
		$c['C12'][$i] = $fetch->item_kind;
		$c['C13'][$i] = $fetch->main_item_kind;
		$c['C16'][$i] = $fetch->dam_fire_ins_amount;
		$c['C17'][$i] = $fetch->dam_eart_ins_amount;
		$c['C18'][$i] = $fetch->dam_fire_hold_amount;
		$c['C19'][$i] = $fetch->dam_eart_hold_amount;
		$c['C20'][$i] = $fetch->dam_rent_comp_amount;
		$c['C21'][$i] = $fetch->dam_real_comp_amount;
		$c['C22'][$i] = $fetch->dam_person_comp_amount;
		$c['C23'][$i] = $fetch->dam_passenger_amount;
		$c['C24'][$i] = $fetch->dam_disadvantage_amount;
		$c['C25'][$i] = $fetch->dam_personal_amount;
		$c['C26'][$i] = $fetch->dam_vehicle_amount;
		$c['C27'][$i] = $fetch->dam_individual_amount;
		$c['C28'][$i] = $fetch->dam_other1;
		$c['C29'][$i] = $fetch->dam_other2;
		$c['C31'][$i] = $fetch->curr_pension_amount;
		$c['C32'][$i] = $fetch->curr_start_prov_age;
		$c['C33'][$i] = $fetch->curr_paym_period;
		$c['C34'][$i] = $fetch->curr_all_reg_kind;
		$c['C36'][$i] = $fetch->curr_expiration_age;
		$c['C37'][$i] = $fetch->curr_expiration_amount;
		$c['C39'][$i] = $fetch->cons_pension_amount;
		$c['C40'][$i] = $fetch->cons_start_prov_age;
		$c['C41'][$i] = $fetch->cons_paym_period;
		$c['C42'][$i] = $fetch->cons_all_reg_kind;
		$c['C44'][$i] = $fetch->cons_expiration_age;
		$c['C45'][$i] = $fetch->cons_expiration_amount;
		$c['C49'][$i] = $fetch->eval_fire_ins_amount;
		$c['C50'][$i] = $fetch->eval_eart_ins_amount;
		$c['C51'][$i] = $fetch->eval_fire_hold_amount;
		$c['C52'][$i] = $fetch->eval_eart_hold_amount;
//#		$c['C54'][$i] = $fetch->car_driving_age;
//#		$c['C55'][$i] = $fetch->car_family_limit_kind;
//#		$c['C56'][$i] = $fetch->car_usage_kind;
//#		$c['C57'][$i] = $fetch->car_grade;
//#		$c['C58'][$i] = $fetch->car_insurance_kind;
//#		$c['C59'][$i] = $fetch->eval_newvalue_presence;
//#		$c['C60'][$i] = $fetch->oth_special1;
//#		$c['C61'][$i] = $fetch->oth_special2;
		$c['C63'][$i] = $fetch->insurance_paym_kind;
		$c['C64'][$i] = $fetch->insurance_amount;
		$c['C67'][$i] = $fetch->join_details_kind;
	}

	// ·×»»¼°
	for ($i = 1; $i <= $c['C2']; $i++) {
		$c['C24'][$i] = VB_IF($c['C21'][$i] == 0, 0, 1500);
		$c['C65'][$i] = VB_IF($c['C63'][$i] == $g_encode['ÊÝ¸±ÎÁ»ÙÊ§ÊýË¡']['°ì»þÊ§¤¤'], 0, VB_IF($c['C63'][$i] == $g_encode['ÊÝ¸±ÎÁ»ÙÊ§ÊýË¡']['·îÊ§¤¤'], $c['C64'][$i] * 12, VB_IF($c['C63'][$i] == $g_encode['ÊÝ¸±ÎÁ»ÙÊ§ÊýË¡']['È¾Ç¯Ê§¤¤'], $c['C64'][$i] * 2, $c['C64'][$i])));
	}
	$c['G16'] = VB_SUM($n, 'C16');
	$c['G17'] = VB_SUM($n, 'C17');
	$c['G18'] = VB_SUM($n, 'C18');
	$c['G19'] = VB_SUM($n, 'C19');
	$c['G20'] = VB_SUM($n, 'C20');
	$c['G21'] = VB_SUM($n, 'C21');
	$c['G22'] = VB_SUM($n, 'C22');
//#	$c['G23'] = VB_SUM($n, 'C23');
//#	$c['G24'] = VB_SUM($n, 'C24');
//#	$c['G25'] = VB_SUM($n, 'C25');
//#	$c['G26'] = VB_SUM($n, 'C26');
	$c['G27'] = VB_SUM($n, 'C27');
//#	$c['G28'] = VB_SUM($n, 'C28');
//#	$c['G29'] = VB_SUM($n, 'C29');
//#	$c['G37'] = VB_SUM($n, 'C37');
//#	$c['G45'] = VB_SUM($n, 'C45');
	$c['G49'] = VB_SUM($n, 'C49');
//#	$c['G50'] = VB_SUM($n, 'C50');
	$c['G51'] = VB_SUM($n, 'C51');
//#	$c['G52'] = VB_SUM($n, 'C52');
//#	$c['G65'] = VB_SUM($n, 'C65');
}
?>