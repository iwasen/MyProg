<?
/******************************************************
* system  : xxx �ꥹ������
* title   : ���ݷϾ���׻�����
* content : �ꥹ�����Ǥ˱��������ݷϾ����Ϣ�η׻���Ԥ�
* version : 1.0
* author  : aizawa
*******************************************************/

// ���ꥹ�����ǡ����ݷϾ���ǡ����׻�
function calc_seiho_info($seiho_data) {
	global $g_encode, $g_const, $g_sheets;

	$n = '����';
	$g_sheets[$n] = array();
	$s = &$g_sheets;
	$c = &$g_sheets[$n];

	// �������������
	$c['B4'] = $s['����']['D7'];
	$c['C5'] = $s['����']['D12'];
	$c['C123'] = 18;
	$c['C124'] = $s['���']['D13'];
	$c['C125'] = $s['����']['D21'] * 12 - $c['C123'] - $c['C124'];
	$c['C130'] = $s['����']['FN4'] + $s['����']['FO4'];
	$c['C131'] = $s['����']['FN5'] + $s['����']['FO5'];
	$c['C138'] = $s['����']['E12'];
	$c['B137'] = $s['����']['E7'];
	$c['C248'] = 18;
	$c['C249'] = 0;
	$c['C250'] = $s['����']['E21'] * 12 - $c['C248'] - $c['C249'];
	$c['C255'] = $s['����']['FN9'] + $s['����']['FO9'];
	$c['C256'] = $s['����']['FN10'] + $s['����']['FO10'];

	$i = $j = 0;
	foreach ($seiho_data as $t) {
		if ($t['����']->insurant_kind == '0') {
			// �ܿ�
			$i++;
			$c['D6'][$i] = $t['����']->life_insu_id;
			$c['D7'][$i] = $t['����']->areas_number;
			$c['D8'][$i] = $t['����']->item_name;
			$c['D9'][$i] = $t['����']->insurance_company;
			$c['D10'][$i] = trim($t['����']->main_contract_kind);
			$c['D11'][$i] = ymd_to_date($t['����']->contract_date);
			$c['D13'][$i] = $t['����']->main_contractor_kind;
			$c['D14'][$i] = $t['����']->death_continue_kind;
			if ($t['��˴']->execute_insurance_kind == '1') {
				$c['D16'][$i] = $t['��˴']->duration_policy_kind;
				$c['D17'][$i] = $t['��˴']->regular_update_year;
				$c['D18'][$i] = $t['��˴']->dont_update_age;
				$c['D19'][$i] = $t['��˴']->expiration_age;
				$c['D21'][$i] = $t['��˴']->unsmoking_discount_kind;
				$c['D22'][$i] = $t['��˴']->health_discount_kind;
				$c['D24'][$i] = $t['��˴']->death_main_covere_amount;
				$c['D25'][$i] = $t['��˴']->death_main_educat_amount;
				$c['D26'][$i] = $t['��˴']->death_main_period_year;
				$c['D27'][$i] = $t['��˴']->death_main_dim_amount;
				$c['D28'][$i] = $t['��˴']->death_main_dis_amount;
			}
			$c['D30'][$i] = trim($t['����']->special_kind);
			if ($t['����']->death_select_kind == '1' && $t['����']->death_execute_insurance_kind == '1') {
				$c['D31'][$i] = $t['����']->death_covere_amount;
				$c['D32'][$i] = $t['����']->death_educat_amount;
				$c['D33'][$i] = $t['����']->death_period_year;
				$c['D34'][$i] = $t['����']->death_dim_amount;
				$c['D35'][$i] = $t['����']->death_dis_amount;
				$c['D39'][$i] = $t['����']->death_policy_kind;
				$c['D40'][$i] = $t['����']->death_poss_regular_year;
				$c['D41'][$i] = $t['����']->death_imposs_regular_age;
				$c['D43'][$i] = $t['����']->death_unsmoking_discount_kind;
				$c['D44'][$i] = $t['����']->death_health_discount_kind;
			}
//#			$c['D47'][$i] = $t['���']->amount;
//#			$c['D48'][$i] = $t['���']->supply_period;
			if ($t['����']->execute_insurance_kind == '1') {
				$c['D50'][$i] = $t['����']->hosp_amount;
				$c['D51'][$i] = $t['����']->injury_amount;
				$c['D52'][$i] = $t['����']->adult_amount;
				$c['D53'][$i] = $t['����']->cancer_amount;
				$c['D57'][$i] = $t['����']->disease_lump;
				$c['D58'][$i] = $t['����']->cancer_lump;
				$c['D60'][$i] = $t['����']->start_prov_kind;
				$c['D61'][$i] = $t['����']->before_begin_kind;
				$c['D62'][$i] = $t['����']->medi_main_supply_period;
				$c['D64'][$i] = $t['����']->duration_policy_kind;
				$c['D65'][$i] = $t['����']->regular_update_year;
				$c['D66'][$i] = $t['����']->dont_update_age;
				$c['D67'][$i] = $t['����']->expiration_age;
			}
			if ($t['����']->medi_select_kind == '1' && $t['����']->medi_execute_insurance_kind == '1') {
				$c['D70'][$i] = $t['����']->medi_disease_dayamount;
				$c['D71'][$i] = $t['����']->medi_injury_dayamount;
				$c['D72'][$i] = $t['����']->medi_geriatric_dayamount;
				$c['D73'][$i] = $t['����']->medi_cancer_dayamount;
				$c['D77'][$i] = $t['����']->medi_disease_lumpamount;
				$c['D78'][$i] = $t['����']->medi_cancer_lumpamount;
				$c['D80'][$i] = $t['����']->medi_supply_begin_kind;
				$c['D81'][$i] = $t['����']->medi_for_period_kind;
				$c['D82'][$i] = $t['����']->medi_supply_period_day;
				$c['D84'][$i] = $t['����']->medi_policy_kind;
				$c['D85'][$i] = $t['����']->medi_poss_regular_year;
				$c['D86'][$i] = $t['����']->medi_imposs_regular_age;
				$c['D87'][$i] = $t['����']->medi_duration_policy_age;
			}
			if ($t['Ϸ��']->oldage_pension_amount != 0) {
				$c['D91'][$i] = $t['Ϸ��']->oldage_pension_amount;
				$c['D92'][$i] = $t['Ϸ��']->oldage_start_prov_age;
				$c['D93'][$i] = $t['Ϸ��']->oldage_supply_period_year;
				$c['D94'][$i] = $t['Ϸ��']->oldage_all_reg_kind;
				$c['D96'][$i] = $t['Ϸ��']->oldage_expiration_age;
				$c['D97'][$i] = $t['Ϸ��']->oldage_expiration_amount;
			} elseif ($t['����']->oldage_select_kind == '1') {
				$c['D91'][$i] = $t['����']->oldage_pension_amount;
				$c['D92'][$i] = $t['����']->oldage_supply_begin_age;
				$c['D93'][$i] = $t['����']->oldage_supply_period;
				$c['D94'][$i] = $t['����']->oldage_life_regular_kind;
				$c['D96'][$i] = $t['����']->oldage_expiration_age;
				$c['D97'][$i] = $t['����']->oldage_expiration_amount;
			}
			if ($t['������ǽ']->supply_kind != '') {
				$c['D99'][$i] = $t['������ǽ']->supply_kind;
				$c['D100'][$i] = $t['������ǽ']->fixed_amount;
				$c['D101'][$i] = $t['������ǽ']->fixed_rate / 100;
				$c['D103'][$i] = $t['������ǽ']->expiration_60_kind;
				$c['D104'][$i] = $t['������ǽ']->regular_60_year;
				$c['D106'][$i] = $t['������ǽ']->immunity_day;
			} elseif ($t['����']->imposs_select_kind == '1') {
				$c['D99'][$i] = $t['����']->imposs_supply_kind;
				$c['D100'][$i] = $t['����']->imposs_fixed_amount;
				$c['D101'][$i] = $t['����']->imposs_fixed_rate / 100;
				$c['D103'][$i] = $t['����']->imposs_60expiration_kind;
				$c['D104'][$i] = $t['����']->imposs_regular_year;
				$c['D106'][$i] = $t['����']->imposs_immunity_period;
			}
			if ($t['����']->ind_liability_kind == '1') {
				$c['D108'][$i] = $t['����']->ind_liability_amount;
			}
			$c['D110'][$i] = $t['����']->insurance_paym_kind;
			$c['D111'][$i] = $t['����']->insurance_amount;
			$c['D113'][$i] = $t['����']->life_invari_amount_b;
			$c['D119'][$i] = $t['����']->contract_form_kind;
		} elseif ($t['����']->insurant_kind == '1') {
			// �۶���
			$j++;
			$c['D140'][$j] = $t['����']->life_insu_id;
			$c['D141'][$j] = $t['����']->areas_number;
			$c['D142'][$j] = $t['����']->item_name;
			$c['D143'][$j] = $t['����']->insurance_company;
			$c['D144'][$j] = trim($t['����']->main_contract_kind);
			$c['D145'][$j] = ymd_to_date($t['����']->contract_date);
			$c['D147'][$j] = $t['����']->main_contractor_kind;
			$c['D148'][$j] = $t['����']->death_continue_kind;
			if ($t['��˴']->execute_insurance_kind == '1') {
				$c['D150'][$j] = $t['��˴']->duration_policy_kind;
				$c['D151'][$j] = $t['��˴']->regular_update_year;
				$c['D152'][$j] = $t['��˴']->dont_update_age;
				$c['D153'][$j] = $t['��˴']->expiration_age;
				$c['D155'][$j] = $t['��˴']->unsmoking_discount_kind;
				$c['D156'][$j] = $t['��˴']->health_discount_kind;
				$c['D158'][$j] = $t['��˴']->death_main_covere_amount;
				$c['D159'][$j] = $t['��˴']->death_main_educat_amount;
				$c['D160'][$j] = $t['��˴']->death_main_period_year;
				$c['D161'][$j] = $t['��˴']->death_main_dim_amount;
				$c['D162'][$j] = $t['��˴']->death_main_dis_amount;
			}
			$c['D164'][$j] = trim($t['����']->special_kind);
			if ($t['����']->death_select_kind == '1' && $t['����']->death_execute_insurance_kind == '1') {
				$c['D165'][$j] = $t['����']->death_covere_amount;
				$c['D166'][$j] = $t['����']->death_educat_amount;
				$c['D167'][$j] = $t['����']->death_period_year;
				$c['D168'][$j] = $t['����']->death_dim_amount;
				$c['D169'][$j] = $t['����']->death_dis_amount;
				$c['D173'][$j] = $t['����']->death_policy_kind;
				$c['D174'][$j] = $t['����']->death_poss_regular_year;
				$c['D175'][$j] = $t['����']->death_imposs_regular_age;
				$c['D176'][$j][1] = $t['����']->death_unsmoking_discount_kind;
				$c['D176'][$j][2] = $t['����']->death_health_discount_kind;
			}
//#			$c['D177'][$j] = $t['���']->amount;
//#			$c['D178'][$j] = $t['���']->supply_period;
			if ($t['����']->execute_insurance_kind == '1') {
				$c['D180'][$j] = $t['����']->hosp_amount;
				$c['D181'][$j] = $t['����']->injury_amount;
				$c['D182'][$j] = $t['����']->adult_amount;
				$c['D183'][$j] = $t['����']->cancer_amount;
				$c['D187'][$j] = $t['����']->disease_lump;
				$c['D188'][$j] = $t['����']->cancer_lump;
				$c['D190'][$j] = $t['����']->start_prov_kind;
				$c['D191'][$j] = $t['����']->before_begin_kind;
				$c['D192'][$j] = $t['����']->medi_main_supply_period;
				$c['D193'][$j][1] = $t['����']->duration_policy_kind;
				$c['D193'][$j][2] = $t['����']->regular_update_year;
				$c['D193'][$j][3] = $t['����']->dont_update_age;
				$c['D193'][$j][4] = $t['����']->expiration_age;
			}
			if ($t['����']->medi_select_kind == '1' && $t['����']->medi_execute_insurance_kind == '1') {
				$c['D194'][$j] = $t['����']->medi_disease_dayamount;
				$c['D195'][$j] = $t['����']->medi_injury_dayamount;
				$c['D196'][$j] = $t['����']->medi_geriatric_dayamount;
				$c['D197'][$j] = $t['����']->medi_cancer_dayamount;
				$c['D201'][$j] = $t['����']->medi_disease_lumpamount;
				$c['D202'][$j] = $t['����']->medi_cancer_lumpamount;
				$c['D204'][$j] = $t['����']->medi_supply_begin_kind;
				$c['D205'][$j] = $t['����']->medi_for_period_kind;
				$c['D206'][$j] = $t['����']->medi_supply_period_day;
				$c['D208'][$j] = $t['����']->medi_policy_kind;
				$c['D209'][$j] = $t['����']->medi_poss_regular_year;
				$c['D210'][$j] = $t['����']->medi_imposs_regular_age;
				$c['D211'][$j] = $t['����']->medi_duration_policy_age;
			}
			if ($t['Ϸ��']->oldage_pension_amount != 0) {
				$c['D215'][$j] = $t['Ϸ��']->oldage_pension_amount;
				$c['D216'][$j] = $t['Ϸ��']->oldage_start_prov_age;
				$c['D217'][$j] = $t['Ϸ��']->oldage_supply_period_year;
				$c['D218'][$j] = $t['Ϸ��']->oldage_all_reg_kind;
				$c['D220'][$j] = $t['Ϸ��']->oldage_expiration_age;
				$c['D221'][$j] = $t['Ϸ��']->oldage_expiration_amount;
			} elseif ($t['����']->oldage_select_kind == '1') {
				$c['D215'][$j] = $t['����']->oldage_pension_amount;
				$c['D216'][$j] = $t['����']->oldage_supply_begin_age;
				$c['D217'][$j] = $t['����']->oldage_supply_period;
				$c['D218'][$j] = $t['����']->oldage_life_regular_kind;
				$c['D220'][$j] = $t['����']->oldage_expiration_age;
				$c['D221'][$j] = $t['����']->oldage_expiration_amount;
			}
			if ($t['������ǽ']->supply_kind != '') {
				$c['D224'][$j] = $t['������ǽ']->supply_kind;
				$c['D225'][$j] = $t['������ǽ']->fixed_amount;
				$c['D226'][$j] = $t['������ǽ']->fixed_rate / 100;
				$c['D228'][$j] = $t['������ǽ']->expiration_60_kind;
				$c['D229'][$j] = $t['������ǽ']->regular_60_year;
				$c['D231'][$j] = $t['������ǽ']->immunity_day;
			} elseif ($t['����']->imposs_select_kind == '1') {
				$c['D224'][$j] = $t['����']->imposs_supply_kind;
				$c['D225'][$j] = $t['����']->imposs_fixed_amount;
				$c['D226'][$j] = $t['����']->imposs_fixed_rate / 100;
				$c['D228'][$j] = $t['����']->imposs_60expiration_kind;
				$c['D229'][$j] = $t['����']->imposs_regular_year;
				$c['D231'][$j] = $t['����']->imposs_immunity_period;
			}
			if ($t['����']->ind_liability_kind == '1') {
				$c['D233'][$j] = $t['����']->ind_liability_amount;
			}
			$c['D235'][$j] = $t['����']->insurance_paym_kind;
			$c['D236'][$j] = $t['����']->insurance_amount;
			$c['D238'][$j] = $t['����']->life_invari_amount_b;
			$c['D244'][$j] = $t['����']->contract_form_kind;
		}
	}

	$c['D5'] = $i;			// �ܿ����ݷϾ���Ŀ�
	$c['D139'] = $j;		// �۶������ݷϾ���Ŀ�

	// ̾������
	$p['�µ�ͭ��'] = &$s['���']['D11'];
	$p['���շ��'] = &$s['���']['D13'];

	// �׻���
	for ($i = 1; $i <= $c['D5']; $i++) {
		// ̾������
		$p['���շ��֣�'] = &$c['D99'][$i];
		$p['��ۣ�'] = &$c['D100'][$i];
		$p['��Ψ��'] = &$c['D101'][$i];
		$p['�Ƥ�ݴ��֣�'] = &$c['D105'][$i];
		$p['���մ��֣�'] = &$c['D106'][$i];

		$c['D12'][$i] = VB_IF(VB_OR(VB_MONTH($c['D11'][$i]) < VB_MONTH($c['B4']), VB_AND(VB_MONTH($c['D11'][$i]) == VB_MONTH($c['B4']), VB_DAY($c['D11'][$i]) < VB_DAY($c['B4']))), -1, 0) + VB_YEAR($c['D11'][$i]) - VB_YEAR($c['B4']);
		$c['D37'][$i] = $c['D24'][$i] + $c['D27'][$i] + ($c['D25'][$i] * $c['D26'][$i]) + $c['D31'][$i] + $c['D34'][$i] + ($c['D32'][$i] * $c['D33'][$i]);
		$c['D54'][$i] = (($c['D58'][$i] + $c['D57'][$i]) / 200) * 10000;
		$c['D55'][$i] = (($c['D57'][$i]) / 200) * 10000;
		$c['D74'][$i] = (($c['D78'][$i] + $c['D77'][$i]) / 200) * 10000;
		$c['D75'][$i] = (($c['D77'][$i]) / 200) * 10000;
//#		$c['D89'][$i] = $c['D50'][$i] + $c['D70'][$i];
		$c['D102'][$i] = VB_IF($c['D99'][$i] != "", $s['���']['D25'], "");
		$c['D105'][$i] = VB_IF($c['D103'][$i] == $g_encode['60����λ']['��'], ($g_const['��ǯ'] - $s['����']['D8']) * 12, $c['D104'][$i] * 12);
		$c['D112'][$i] = VB_IF($c['D110'][$i] == $g_encode['�ݸ�����ʧ��ˡ']['���ʧ��'], 0, VB_IF($c['D110'][$i] == $g_encode['�ݸ�����ʧ��ˡ']['��ʧ��'], $c['D111'][$i] * 12, VB_IF($c['D110'][$i] == $g_encode['�ݸ�����ʧ��ˡ']['Ⱦǯʧ��'], $c['D111'][$i] * 2, $c['D111'][$i])));
//#		$c['D114'][$i] = $c['D112'][$i] - $c['D113'][$i];
		$c['D121'][$i] = MAX(0, $p['���մ��֣�'] / 30 - $p['�µ�ͭ��']);
		$c['D123'][$i] = MAX(0, 18 - $c['D121'][$i]);
		$c['D124'][$i] = MAX(0, $c['C124'] - MAX(0, $c['D121'][$i] - 18));
		$c['D125'][$i] = MIN(MAX(0, $c['C125'] - MAX(0, $c['D121'][$i] - $p['���շ��'] - 18)), MAX(0, $p['�Ƥ�ݴ��֣�'] - $c['D123'][$i] - $c['D124'][$i]));
		$c['D126'][$i] = VB_IF($p['���շ��֣�'] == $g_encode['���շ���']['���'], $p['��ۣ�'], $c['D102'][$i] * 0.4 * $p['��Ψ��']);
		$c['D127'][$i] = VB_IF($p['���շ��֣�'] == $g_encode['���շ���']['���'], $p['��ۣ�'], $c['D102'][$i] * $p['��Ψ��']);
		$c['D128'][$i] = VB_IF($p['���շ��֣�'] == $g_encode['���շ���']['���'], $p['��ۣ�'], $c['D102'][$i] * $p['��Ψ��']);
		$c['D129'][$i] = $c['D123'][$i] * $c['D126'][$i];
		$c['D130'][$i] = $c['D124'][$i] * $c['D127'][$i] - VB_IF($p['���շ��֣�'] == $g_encode['���շ���']['��Ψ'], $p['��Ψ��'] * $c['C130'], 0);
		$c['D131'][$i] = $c['D125'][$i] * $c['D128'][$i] - VB_IF($p['���շ��֣�'] == $g_encode['���շ���']['��Ψ'], $p['��Ψ��'] * $c['C131'] / $c['C125'] * $c['D125'][$i], 0);
//#		$c['D132'][$i] = VB_SUM2($n, $i, 'D129:D131');
	}

	for ($i = 1; $i <= $c['D139']; $i++) {
		// ̾������
		$p['���շ���P��'] = &$c['D224'][$i];
		$p['���P��'] = &$c['D225'][$i];
		$p['��ΨP��'] = &$c['D226'][$i];
		$p['�Ƥ�ݴ���P��'] = &$c['D230'][$i];
		$p['���մ���P��'] = &$c['D231'][$i];

		$c['D146'][$i] = VB_IF(VB_OR(VB_MONTH($c['D145'][$i]) < VB_MONTH($c['B137']), VB_AND(VB_MONTH($c['D145'][$i]) == VB_MONTH($c['B137']), VB_DAY($c['D145'][$i]) < VB_DAY($c['B137']))), -1, 0) + VB_YEAR($c['D145'][$i]) - VB_YEAR($c['B137']);
		$c['D171'][$i] = $c['D158'][$i] + $c['D161'][$i] + ($c['D159'][$i] * $c['D160'][$i]) + $c['D165'][$i] + $c['D168'][$i] + ($c['D166'][$i] * $c['D167'][$i]);
		$c['D184'][$i] = (($c['D188'][$i] + $c['D187'][$i]) / 200) * 10000;
		$c['D185'][$i] = (($c['D187'][$i]) / 200) * 10000;
		$c['D198'][$i] = (($c['D202'][$i] + $c['D201'][$i]) / 200) * 10000;
		$c['D199'][$i] = (($c['D201'][$i]) / 200) * 10000;
//#		$c['D213'][$i] = $c['D180'][$i] + $c['D194'][$i];
		$c['D227'][$i] = VB_IF($c['D224'][$i] != "", $s['���']['D25'], "");
		$c['D230'][$i] = VB_IF($c['D228'][$i] == $g_encode['60����λ']['��'], ($g_const['��ǯ'] - $s['����']['D8']) * 12, $c['D229'][$i] * 12);
		$c['D237'][$i] = VB_IF($c['D235'][$i] == $g_encode['�ݸ�����ʧ��ˡ']['���ʧ��'], 0, VB_IF($c['D235'][$i] == $g_encode['�ݸ�����ʧ��ˡ']['��ʧ��'], $c['D236'][$i] * 12, VB_IF($c['D235'][$i] == "Ⱦǯʧ��", $c['D236'][$i] * 2, $c['D236'][$i])));
//#		$c['D239'][$i] = $c['D237'][$i] - $c['D238'][$i];
		$c['D246'][$i] = MAX(0, $p['���մ���P��'] / 30);
		$c['D248'][$i] = MAX(0, 18 - $c['D246'][$i]);
		$c['D249'][$i] = MAX(0, $c['C249'] - MAX(0, $c['D246'][$i] - 18));
		$c['D250'][$i] = MIN(MAX(0, $c['C250'] - MAX(0, $c['D246'][$i] - 18)), MAX(0, $p['�Ƥ�ݴ���P��'] - $c['D248'][$i] - $c['D249'][$i]));
		$c['D251'][$i] = VB_IF($p['���շ���P��'] == $g_encode['���շ���']['���'], $p['���P��'], $c['D227'][$i] * 0.4 * $p['��ΨP��']);
		$c['D252'][$i] = VB_IF($p['���շ���P��'] == $g_encode['���շ���']['���'], $p['���P��'], $c['D227'][$i] * $p['��ΨP��']);
		$c['D253'][$i] = VB_IF($p['���շ���P��'] == $g_encode['���շ���']['���'], $p['���P��'], $c['D227'][$i] * $p['��ΨP��']);
		$c['D254'][$i] = $c['D248'][$i] * $c['D251'][$i];
		$c['D255'][$i] = $c['D249'][$i] * $c['D252'][$i] - VB_IF($p['���շ���P��'] == $g_encode['���շ���']['��Ψ'], $p['��ΨP��'] * $c['C255'], 0);
		$c['D256'][$i] = $c['D250'][$i] * $c['D253'][$i] - VB_IF($p['���շ���P��'] == $g_encode['���շ���']['��Ψ'], $p['��ΨP��'] * $c['C256'] / $c['C250'] * $c['D250'][$i], 0);
//#		$c['D257'][$i] = VB_SUM2($n, $i, 'D254:D256');
	}

	$c['I24'] = VB_SUM($n, 'D24');
	$c['I25'] = VB_SUM($n, 'D25');
	$c['I26'] = VB_SUM($n, 'D26');
	$c['I27'] = VB_SUM($n, 'D27');
	$c['I28'] = VB_SUM($n, 'D28');
	$c['I31'] = VB_SUM($n, 'D31');
	$c['I32'] = VB_SUM($n, 'D32');
	$c['I33'] = VB_SUM($n, 'D33');
	$c['I34'] = VB_SUM($n, 'D34');
	$c['I35'] = VB_SUM($n, 'D35');
	$c['I37'] = VB_SUM($n, 'D37');
//#	$c['I47'] = VB_SUM($n, 'D47');
//#	$c['I48'] = VB_SUM($n, 'D48');
//#	$c['I50'] = VB_SUM($n, 'D50');
//#	$c['I51'] = VB_SUM($n, 'D51');
//#	$c['I52'] = VB_SUM($n, 'D52');
//#	$c['I53'] = VB_SUM($n, 'D53');
//#	$c['I57'] = VB_SUM($n, 'D57');
//#	$c['I58'] = VB_SUM($n, 'D58');
//#	$c['I54'] = (($c['I58'] + $c['I57']) / 200) * 10000;
//#	$c['I55'] = (($c['I57']) / 200) * 10000;
//#	$c['I70'] = VB_SUM($n, 'D70');
//#	$c['I71'] = VB_SUM($n, 'D71');
//#	$c['I72'] = VB_SUM($n, 'D72');
//#	$c['I73'] = VB_SUM($n, 'D73');
//#	$c['I77'] = 0;
//#	$c['I78'] = 0;
//#	$c['I74'] = (($c['I78'] + $c['I77']) / 200) * 10000;
//#	$c['I75'] = (($c['I77']) / 200) * 10000;
//#	$c['I89'] = $c['I50'] + $c['I70'];
	$c['I97'] = VB_SUM($n, 'D97');
	$c['I100'] = VB_SUM($n, 'D100');
	$c['I101'] = VB_SUM($n, 'D101');
//	$c['I102'] = $s['���']['D25'];
	$c['I108'] = VB_SUM($n, 'D108');
//#	$c['I112'] = VB_SUM($n, 'D112');
//#	$c['I113'] = VB_SUM($n, 'D113');
//#	$c['I114'] = VB_SUM($n, 'D114');
//#	$c['I126'] = VB_SUM($n, 'D126');
//#	$c['I127'] = VB_SUM($n, 'D127');
//#	$c['I128'] = VB_SUM($n, 'D128');
	$c['I129'] = VB_SUM($n, 'D129');
	$c['I130'] = VB_SUM($n, 'D130');
	$c['I131'] = VB_SUM($n, 'D131');
//#	$c['I132'] = VB_SUM($n, 'I129:I131');

	$c['I158'] = VB_SUM($n, 'D158');
	$c['I159'] = VB_SUM($n, 'D159');
	$c['I160'] = VB_SUM($n, 'D160');
	$c['I161'] = VB_SUM($n, 'D161');
	$c['I162'] = VB_SUM($n, 'D162');
	$c['I165'] = VB_SUM($n, 'D165');
	$c['I166'] = VB_SUM($n, 'D166');
	$c['I167'] = VB_SUM($n, 'D167');
	$c['I168'] = VB_SUM($n, 'D168');
	$c['I169'] = VB_SUM($n, 'D169');
	$c['I171'] = VB_SUM($n, 'D171');
//#	$c['I177'] = VB_SUM($n, 'D177');
//#	$c['I178'] = VB_SUM($n, 'D178');
//#	$c['I180'] = VB_SUM($n, 'D180');
//#	$c['I181'] = VB_SUM($n, 'D181');
//#	$c['I182'] = VB_SUM($n, 'D182');
//#	$c['I183'] = VB_SUM($n, 'D183');
//#	$c['I187'] = VB_SUM($n, 'D187');
//#	$c['I188'] = VB_SUM($n, 'D188');
//#	$c['I184'] = (($c['I188'] + $c['I187']) / 200) * 10000;
//#	$c['I185'] = (($c['I187']) / 200) * 10000;
//#	$c['I194'] = VB_SUM($n, 'D194');
//#	$c['I195'] = VB_SUM($n, 'D195');
//#	$c['I196'] = VB_SUM($n, 'D196');
//#	$c['I197'] = VB_SUM($n, 'D197');
//#	$c['I201'] = 0;
//#	$c['I202'] = 0;
//#	$c['I198'] = (($c['I202'] + $c['I201']) / 200) * 10000;
//#	$c['I199'] = (($c['I201']) / 200) * 10000;
//#	$c['I213'] = $c['I180'] + $c['I194'];
	$c['I221'] = VB_SUM($n, 'D221');
	$c['I225'] = VB_SUM($n, 'D225');
	$c['I226'] = 0.7;
//#	$c['I227'] = $s['���']['D25'];
	$c['I230'] = VB_SUM($n, 'D230');
	$c['I231'] = 540;
	$c['I233'] = VB_SUM($n, 'D233');
//#	$c['I237'] = VB_SUM($n, 'D237');
//#	$c['I238'] = VB_SUM($n, 'D238');
//#	$c['I239'] = VB_SUM($n, 'D239');
//#	$c['I251'] = VB_SUM($n, 'D251');
//#	$c['I252'] = VB_SUM($n, 'D252');
//#	$c['I253'] = VB_SUM($n, 'D253');
	$c['I254'] = VB_SUM($n, 'D254');
//#	$c['I255'] = VB_SUM($n, 'D255');
	$c['I256'] = VB_SUM($n, 'D256');
//#	$c['I257'] = VB_SUM($n, 'I254:I256');
}
?>