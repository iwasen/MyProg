<?
/******************************************************
* system  : xxx リスク診断
* title   : 生保系情報計算処理
* content : リスク診断に於ける生保系情報関連の計算を行う
* version : 1.0
* author  : aizawa
*******************************************************/

// ■リスク診断／生保系情報データ計算
function calc_seiho_info($seiho_data) {
	global $g_encode, $g_const, $g_sheets;

	$n = '生保';
	$g_sheets[$n] = array();
	$s = &$g_sheets;
	$c = &$g_sheets[$n];

	// 定数・外部参照
	$c['B4'] = $s['基本']['D7'];
	$c['C5'] = $s['基本']['D12'];
	$c['C123'] = 18;
	$c['C124'] = $s['会社']['D13'];
	$c['C125'] = $s['基本']['D21'] * 12 - $c['C123'] - $c['C124'];
	$c['C130'] = $s['基本']['FN4'] + $s['基本']['FO4'];
	$c['C131'] = $s['基本']['FN5'] + $s['基本']['FO5'];
	$c['C138'] = $s['基本']['E12'];
	$c['B137'] = $s['基本']['E7'];
	$c['C248'] = 18;
	$c['C249'] = 0;
	$c['C250'] = $s['基本']['E21'] * 12 - $c['C248'] - $c['C249'];
	$c['C255'] = $s['基本']['FN9'] + $s['基本']['FO9'];
	$c['C256'] = $s['基本']['FN10'] + $s['基本']['FO10'];

	$i = $j = 0;
	foreach ($seiho_data as $t) {
		if ($t['基本']->insurant_kind == '0') {
			// 本人
			$i++;
			$c['D6'][$i] = $t['基本']->life_insu_id;
			$c['D7'][$i] = $t['基本']->areas_number;
			$c['D8'][$i] = $t['基本']->item_name;
			$c['D9'][$i] = $t['基本']->insurance_company;
			$c['D10'][$i] = trim($t['基本']->main_contract_kind);
			$c['D11'][$i] = ymd_to_date($t['基本']->contract_date);
			$c['D13'][$i] = $t['基本']->main_contractor_kind;
			$c['D14'][$i] = $t['基本']->death_continue_kind;
			if ($t['死亡']->execute_insurance_kind == '1') {
				$c['D16'][$i] = $t['死亡']->duration_policy_kind;
				$c['D17'][$i] = $t['死亡']->regular_update_year;
				$c['D18'][$i] = $t['死亡']->dont_update_age;
				$c['D19'][$i] = $t['死亡']->expiration_age;
				$c['D21'][$i] = $t['死亡']->unsmoking_discount_kind;
				$c['D22'][$i] = $t['死亡']->health_discount_kind;
				$c['D24'][$i] = $t['死亡']->death_main_covere_amount;
				$c['D25'][$i] = $t['死亡']->death_main_educat_amount;
				$c['D26'][$i] = $t['死亡']->death_main_period_year;
				$c['D27'][$i] = $t['死亡']->death_main_dim_amount;
				$c['D28'][$i] = $t['死亡']->death_main_dis_amount;
			}
			$c['D30'][$i] = trim($t['特約']->special_kind);
			if ($t['特約']->death_select_kind == '1' && $t['特約']->death_execute_insurance_kind == '1') {
				$c['D31'][$i] = $t['特約']->death_covere_amount;
				$c['D32'][$i] = $t['特約']->death_educat_amount;
				$c['D33'][$i] = $t['特約']->death_period_year;
				$c['D34'][$i] = $t['特約']->death_dim_amount;
				$c['D35'][$i] = $t['特約']->death_dis_amount;
				$c['D39'][$i] = $t['特約']->death_policy_kind;
				$c['D40'][$i] = $t['特約']->death_poss_regular_year;
				$c['D41'][$i] = $t['特約']->death_imposs_regular_age;
				$c['D43'][$i] = $t['特約']->death_unsmoking_discount_kind;
				$c['D44'][$i] = $t['特約']->death_health_discount_kind;
			}
//#			$c['D47'][$i] = $t['介護']->amount;
//#			$c['D48'][$i] = $t['介護']->supply_period;
			if ($t['医療']->execute_insurance_kind == '1') {
				$c['D50'][$i] = $t['医療']->hosp_amount;
				$c['D51'][$i] = $t['医療']->injury_amount;
				$c['D52'][$i] = $t['医療']->adult_amount;
				$c['D53'][$i] = $t['医療']->cancer_amount;
				$c['D57'][$i] = $t['医療']->disease_lump;
				$c['D58'][$i] = $t['医療']->cancer_lump;
				$c['D60'][$i] = $t['医療']->start_prov_kind;
				$c['D61'][$i] = $t['医療']->before_begin_kind;
				$c['D62'][$i] = $t['医療']->medi_main_supply_period;
				$c['D64'][$i] = $t['医療']->duration_policy_kind;
				$c['D65'][$i] = $t['医療']->regular_update_year;
				$c['D66'][$i] = $t['医療']->dont_update_age;
				$c['D67'][$i] = $t['医療']->expiration_age;
			}
			if ($t['特約']->medi_select_kind == '1' && $t['特約']->medi_execute_insurance_kind == '1') {
				$c['D70'][$i] = $t['特約']->medi_disease_dayamount;
				$c['D71'][$i] = $t['特約']->medi_injury_dayamount;
				$c['D72'][$i] = $t['特約']->medi_geriatric_dayamount;
				$c['D73'][$i] = $t['特約']->medi_cancer_dayamount;
				$c['D77'][$i] = $t['特約']->medi_disease_lumpamount;
				$c['D78'][$i] = $t['特約']->medi_cancer_lumpamount;
				$c['D80'][$i] = $t['特約']->medi_supply_begin_kind;
				$c['D81'][$i] = $t['特約']->medi_for_period_kind;
				$c['D82'][$i] = $t['特約']->medi_supply_period_day;
				$c['D84'][$i] = $t['特約']->medi_policy_kind;
				$c['D85'][$i] = $t['特約']->medi_poss_regular_year;
				$c['D86'][$i] = $t['特約']->medi_imposs_regular_age;
				$c['D87'][$i] = $t['特約']->medi_duration_policy_age;
			}
			if ($t['老後']->oldage_pension_amount != 0) {
				$c['D91'][$i] = $t['老後']->oldage_pension_amount;
				$c['D92'][$i] = $t['老後']->oldage_start_prov_age;
				$c['D93'][$i] = $t['老後']->oldage_supply_period_year;
				$c['D94'][$i] = $t['老後']->oldage_all_reg_kind;
				$c['D96'][$i] = $t['老後']->oldage_expiration_age;
				$c['D97'][$i] = $t['老後']->oldage_expiration_amount;
			} elseif ($t['特約']->oldage_select_kind == '1') {
				$c['D91'][$i] = $t['特約']->oldage_pension_amount;
				$c['D92'][$i] = $t['特約']->oldage_supply_begin_age;
				$c['D93'][$i] = $t['特約']->oldage_supply_period;
				$c['D94'][$i] = $t['特約']->oldage_life_regular_kind;
				$c['D96'][$i] = $t['特約']->oldage_expiration_age;
				$c['D97'][$i] = $t['特約']->oldage_expiration_amount;
			}
			if ($t['就業不能']->supply_kind != '') {
				$c['D99'][$i] = $t['就業不能']->supply_kind;
				$c['D100'][$i] = $t['就業不能']->fixed_amount;
				$c['D101'][$i] = $t['就業不能']->fixed_rate / 100;
				$c['D103'][$i] = $t['就業不能']->expiration_60_kind;
				$c['D104'][$i] = $t['就業不能']->regular_60_year;
				$c['D106'][$i] = $t['就業不能']->immunity_day;
			} elseif ($t['特約']->imposs_select_kind == '1') {
				$c['D99'][$i] = $t['特約']->imposs_supply_kind;
				$c['D100'][$i] = $t['特約']->imposs_fixed_amount;
				$c['D101'][$i] = $t['特約']->imposs_fixed_rate / 100;
				$c['D103'][$i] = $t['特約']->imposs_60expiration_kind;
				$c['D104'][$i] = $t['特約']->imposs_regular_year;
				$c['D106'][$i] = $t['特約']->imposs_immunity_period;
			}
			if ($t['特約']->ind_liability_kind == '1') {
				$c['D108'][$i] = $t['特約']->ind_liability_amount;
			}
			$c['D110'][$i] = $t['基本']->insurance_paym_kind;
			$c['D111'][$i] = $t['基本']->insurance_amount;
			$c['D113'][$i] = $t['基本']->life_invari_amount_b;
			$c['D119'][$i] = $t['基本']->contract_form_kind;
		} elseif ($t['基本']->insurant_kind == '1') {
			// 配偶者
			$j++;
			$c['D140'][$j] = $t['基本']->life_insu_id;
			$c['D141'][$j] = $t['基本']->areas_number;
			$c['D142'][$j] = $t['基本']->item_name;
			$c['D143'][$j] = $t['基本']->insurance_company;
			$c['D144'][$j] = trim($t['基本']->main_contract_kind);
			$c['D145'][$j] = ymd_to_date($t['基本']->contract_date);
			$c['D147'][$j] = $t['基本']->main_contractor_kind;
			$c['D148'][$j] = $t['基本']->death_continue_kind;
			if ($t['死亡']->execute_insurance_kind == '1') {
				$c['D150'][$j] = $t['死亡']->duration_policy_kind;
				$c['D151'][$j] = $t['死亡']->regular_update_year;
				$c['D152'][$j] = $t['死亡']->dont_update_age;
				$c['D153'][$j] = $t['死亡']->expiration_age;
				$c['D155'][$j] = $t['死亡']->unsmoking_discount_kind;
				$c['D156'][$j] = $t['死亡']->health_discount_kind;
				$c['D158'][$j] = $t['死亡']->death_main_covere_amount;
				$c['D159'][$j] = $t['死亡']->death_main_educat_amount;
				$c['D160'][$j] = $t['死亡']->death_main_period_year;
				$c['D161'][$j] = $t['死亡']->death_main_dim_amount;
				$c['D162'][$j] = $t['死亡']->death_main_dis_amount;
			}
			$c['D164'][$j] = trim($t['特約']->special_kind);
			if ($t['特約']->death_select_kind == '1' && $t['特約']->death_execute_insurance_kind == '1') {
				$c['D165'][$j] = $t['特約']->death_covere_amount;
				$c['D166'][$j] = $t['特約']->death_educat_amount;
				$c['D167'][$j] = $t['特約']->death_period_year;
				$c['D168'][$j] = $t['特約']->death_dim_amount;
				$c['D169'][$j] = $t['特約']->death_dis_amount;
				$c['D173'][$j] = $t['特約']->death_policy_kind;
				$c['D174'][$j] = $t['特約']->death_poss_regular_year;
				$c['D175'][$j] = $t['特約']->death_imposs_regular_age;
				$c['D176'][$j][1] = $t['特約']->death_unsmoking_discount_kind;
				$c['D176'][$j][2] = $t['特約']->death_health_discount_kind;
			}
//#			$c['D177'][$j] = $t['介護']->amount;
//#			$c['D178'][$j] = $t['介護']->supply_period;
			if ($t['医療']->execute_insurance_kind == '1') {
				$c['D180'][$j] = $t['医療']->hosp_amount;
				$c['D181'][$j] = $t['医療']->injury_amount;
				$c['D182'][$j] = $t['医療']->adult_amount;
				$c['D183'][$j] = $t['医療']->cancer_amount;
				$c['D187'][$j] = $t['医療']->disease_lump;
				$c['D188'][$j] = $t['医療']->cancer_lump;
				$c['D190'][$j] = $t['医療']->start_prov_kind;
				$c['D191'][$j] = $t['医療']->before_begin_kind;
				$c['D192'][$j] = $t['医療']->medi_main_supply_period;
				$c['D193'][$j][1] = $t['医療']->duration_policy_kind;
				$c['D193'][$j][2] = $t['医療']->regular_update_year;
				$c['D193'][$j][3] = $t['医療']->dont_update_age;
				$c['D193'][$j][4] = $t['医療']->expiration_age;
			}
			if ($t['特約']->medi_select_kind == '1' && $t['特約']->medi_execute_insurance_kind == '1') {
				$c['D194'][$j] = $t['特約']->medi_disease_dayamount;
				$c['D195'][$j] = $t['特約']->medi_injury_dayamount;
				$c['D196'][$j] = $t['特約']->medi_geriatric_dayamount;
				$c['D197'][$j] = $t['特約']->medi_cancer_dayamount;
				$c['D201'][$j] = $t['特約']->medi_disease_lumpamount;
				$c['D202'][$j] = $t['特約']->medi_cancer_lumpamount;
				$c['D204'][$j] = $t['特約']->medi_supply_begin_kind;
				$c['D205'][$j] = $t['特約']->medi_for_period_kind;
				$c['D206'][$j] = $t['特約']->medi_supply_period_day;
				$c['D208'][$j] = $t['特約']->medi_policy_kind;
				$c['D209'][$j] = $t['特約']->medi_poss_regular_year;
				$c['D210'][$j] = $t['特約']->medi_imposs_regular_age;
				$c['D211'][$j] = $t['特約']->medi_duration_policy_age;
			}
			if ($t['老後']->oldage_pension_amount != 0) {
				$c['D215'][$j] = $t['老後']->oldage_pension_amount;
				$c['D216'][$j] = $t['老後']->oldage_start_prov_age;
				$c['D217'][$j] = $t['老後']->oldage_supply_period_year;
				$c['D218'][$j] = $t['老後']->oldage_all_reg_kind;
				$c['D220'][$j] = $t['老後']->oldage_expiration_age;
				$c['D221'][$j] = $t['老後']->oldage_expiration_amount;
			} elseif ($t['特約']->oldage_select_kind == '1') {
				$c['D215'][$j] = $t['特約']->oldage_pension_amount;
				$c['D216'][$j] = $t['特約']->oldage_supply_begin_age;
				$c['D217'][$j] = $t['特約']->oldage_supply_period;
				$c['D218'][$j] = $t['特約']->oldage_life_regular_kind;
				$c['D220'][$j] = $t['特約']->oldage_expiration_age;
				$c['D221'][$j] = $t['特約']->oldage_expiration_amount;
			}
			if ($t['就業不能']->supply_kind != '') {
				$c['D224'][$j] = $t['就業不能']->supply_kind;
				$c['D225'][$j] = $t['就業不能']->fixed_amount;
				$c['D226'][$j] = $t['就業不能']->fixed_rate / 100;
				$c['D228'][$j] = $t['就業不能']->expiration_60_kind;
				$c['D229'][$j] = $t['就業不能']->regular_60_year;
				$c['D231'][$j] = $t['就業不能']->immunity_day;
			} elseif ($t['特約']->imposs_select_kind == '1') {
				$c['D224'][$j] = $t['特約']->imposs_supply_kind;
				$c['D225'][$j] = $t['特約']->imposs_fixed_amount;
				$c['D226'][$j] = $t['特約']->imposs_fixed_rate / 100;
				$c['D228'][$j] = $t['特約']->imposs_60expiration_kind;
				$c['D229'][$j] = $t['特約']->imposs_regular_year;
				$c['D231'][$j] = $t['特約']->imposs_immunity_period;
			}
			if ($t['特約']->ind_liability_kind == '1') {
				$c['D233'][$j] = $t['特約']->ind_liability_amount;
			}
			$c['D235'][$j] = $t['基本']->insurance_paym_kind;
			$c['D236'][$j] = $t['基本']->insurance_amount;
			$c['D238'][$j] = $t['基本']->life_invari_amount_b;
			$c['D244'][$j] = $t['基本']->contract_form_kind;
		}
	}

	$c['D5'] = $i;			// 本人生保系情報個数
	$c['D139'] = $j;		// 配偶者生保系情報個数

	// 名前参照
	$p['病気有給'] = &$s['会社']['D11'];
	$p['給付月数'] = &$s['会社']['D13'];

	// 計算式
	for ($i = 1; $i <= $c['D5']; $i++) {
		// 名前参照
		$p['給付形態Ｐ'] = &$c['D99'][$i];
		$p['定額Ｐ'] = &$c['D100'][$i];
		$p['定率Ｐ'] = &$c['D101'][$i];
		$p['てんぽ期間Ｐ'] = &$c['D105'][$i];
		$p['免責期間Ｐ'] = &$c['D106'][$i];

		$c['D12'][$i] = VB_IF(VB_OR(VB_MONTH($c['D11'][$i]) < VB_MONTH($c['B4']), VB_AND(VB_MONTH($c['D11'][$i]) == VB_MONTH($c['B4']), VB_DAY($c['D11'][$i]) < VB_DAY($c['B4']))), -1, 0) + VB_YEAR($c['D11'][$i]) - VB_YEAR($c['B4']);
		$c['D37'][$i] = $c['D24'][$i] + $c['D27'][$i] + ($c['D25'][$i] * $c['D26'][$i]) + $c['D31'][$i] + $c['D34'][$i] + ($c['D32'][$i] * $c['D33'][$i]);
		$c['D54'][$i] = (($c['D58'][$i] + $c['D57'][$i]) / 200) * 10000;
		$c['D55'][$i] = (($c['D57'][$i]) / 200) * 10000;
		$c['D74'][$i] = (($c['D78'][$i] + $c['D77'][$i]) / 200) * 10000;
		$c['D75'][$i] = (($c['D77'][$i]) / 200) * 10000;
//#		$c['D89'][$i] = $c['D50'][$i] + $c['D70'][$i];
		$c['D102'][$i] = VB_IF($c['D99'][$i] != "", $s['会社']['D25'], "");
		$c['D105'][$i] = VB_IF($c['D103'][$i] == $g_encode['60歳満了']['◎'], ($g_const['定年'] - $s['基本']['D8']) * 12, $c['D104'][$i] * 12);
		$c['D112'][$i] = VB_IF($c['D110'][$i] == $g_encode['保険料支払方法']['一時払い'], 0, VB_IF($c['D110'][$i] == $g_encode['保険料支払方法']['月払い'], $c['D111'][$i] * 12, VB_IF($c['D110'][$i] == $g_encode['保険料支払方法']['半年払い'], $c['D111'][$i] * 2, $c['D111'][$i])));
//#		$c['D114'][$i] = $c['D112'][$i] - $c['D113'][$i];
		$c['D121'][$i] = MAX(0, $p['免責期間Ｐ'] / 30 - $p['病気有給']);
		$c['D123'][$i] = MAX(0, 18 - $c['D121'][$i]);
		$c['D124'][$i] = MAX(0, $c['C124'] - MAX(0, $c['D121'][$i] - 18));
		$c['D125'][$i] = MIN(MAX(0, $c['C125'] - MAX(0, $c['D121'][$i] - $p['給付月数'] - 18)), MAX(0, $p['てんぽ期間Ｐ'] - $c['D123'][$i] - $c['D124'][$i]));
		$c['D126'][$i] = VB_IF($p['給付形態Ｐ'] == $g_encode['給付形態']['定額'], $p['定額Ｐ'], $c['D102'][$i] * 0.4 * $p['定率Ｐ']);
		$c['D127'][$i] = VB_IF($p['給付形態Ｐ'] == $g_encode['給付形態']['定額'], $p['定額Ｐ'], $c['D102'][$i] * $p['定率Ｐ']);
		$c['D128'][$i] = VB_IF($p['給付形態Ｐ'] == $g_encode['給付形態']['定額'], $p['定額Ｐ'], $c['D102'][$i] * $p['定率Ｐ']);
		$c['D129'][$i] = $c['D123'][$i] * $c['D126'][$i];
		$c['D130'][$i] = $c['D124'][$i] * $c['D127'][$i] - VB_IF($p['給付形態Ｐ'] == $g_encode['給付形態']['定率'], $p['定率Ｐ'] * $c['C130'], 0);
		$c['D131'][$i] = $c['D125'][$i] * $c['D128'][$i] - VB_IF($p['給付形態Ｐ'] == $g_encode['給付形態']['定率'], $p['定率Ｐ'] * $c['C131'] / $c['C125'] * $c['D125'][$i], 0);
//#		$c['D132'][$i] = VB_SUM2($n, $i, 'D129:D131');
	}

	for ($i = 1; $i <= $c['D139']; $i++) {
		// 名前参照
		$p['給付形態P２'] = &$c['D224'][$i];
		$p['定額P２'] = &$c['D225'][$i];
		$p['定率P２'] = &$c['D226'][$i];
		$p['てんぽ期間P２'] = &$c['D230'][$i];
		$p['免責期間P２'] = &$c['D231'][$i];

		$c['D146'][$i] = VB_IF(VB_OR(VB_MONTH($c['D145'][$i]) < VB_MONTH($c['B137']), VB_AND(VB_MONTH($c['D145'][$i]) == VB_MONTH($c['B137']), VB_DAY($c['D145'][$i]) < VB_DAY($c['B137']))), -1, 0) + VB_YEAR($c['D145'][$i]) - VB_YEAR($c['B137']);
		$c['D171'][$i] = $c['D158'][$i] + $c['D161'][$i] + ($c['D159'][$i] * $c['D160'][$i]) + $c['D165'][$i] + $c['D168'][$i] + ($c['D166'][$i] * $c['D167'][$i]);
		$c['D184'][$i] = (($c['D188'][$i] + $c['D187'][$i]) / 200) * 10000;
		$c['D185'][$i] = (($c['D187'][$i]) / 200) * 10000;
		$c['D198'][$i] = (($c['D202'][$i] + $c['D201'][$i]) / 200) * 10000;
		$c['D199'][$i] = (($c['D201'][$i]) / 200) * 10000;
//#		$c['D213'][$i] = $c['D180'][$i] + $c['D194'][$i];
		$c['D227'][$i] = VB_IF($c['D224'][$i] != "", $s['会社']['D25'], "");
		$c['D230'][$i] = VB_IF($c['D228'][$i] == $g_encode['60歳満了']['◎'], ($g_const['定年'] - $s['基本']['D8']) * 12, $c['D229'][$i] * 12);
		$c['D237'][$i] = VB_IF($c['D235'][$i] == $g_encode['保険料支払方法']['一時払い'], 0, VB_IF($c['D235'][$i] == $g_encode['保険料支払方法']['月払い'], $c['D236'][$i] * 12, VB_IF($c['D235'][$i] == "半年払い", $c['D236'][$i] * 2, $c['D236'][$i])));
//#		$c['D239'][$i] = $c['D237'][$i] - $c['D238'][$i];
		$c['D246'][$i] = MAX(0, $p['免責期間P２'] / 30);
		$c['D248'][$i] = MAX(0, 18 - $c['D246'][$i]);
		$c['D249'][$i] = MAX(0, $c['C249'] - MAX(0, $c['D246'][$i] - 18));
		$c['D250'][$i] = MIN(MAX(0, $c['C250'] - MAX(0, $c['D246'][$i] - 18)), MAX(0, $p['てんぽ期間P２'] - $c['D248'][$i] - $c['D249'][$i]));
		$c['D251'][$i] = VB_IF($p['給付形態P２'] == $g_encode['給付形態']['定額'], $p['定額P２'], $c['D227'][$i] * 0.4 * $p['定率P２']);
		$c['D252'][$i] = VB_IF($p['給付形態P２'] == $g_encode['給付形態']['定額'], $p['定額P２'], $c['D227'][$i] * $p['定率P２']);
		$c['D253'][$i] = VB_IF($p['給付形態P２'] == $g_encode['給付形態']['定額'], $p['定額P２'], $c['D227'][$i] * $p['定率P２']);
		$c['D254'][$i] = $c['D248'][$i] * $c['D251'][$i];
		$c['D255'][$i] = $c['D249'][$i] * $c['D252'][$i] - VB_IF($p['給付形態P２'] == $g_encode['給付形態']['定率'], $p['定率P２'] * $c['C255'], 0);
		$c['D256'][$i] = $c['D250'][$i] * $c['D253'][$i] - VB_IF($p['給付形態P２'] == $g_encode['給付形態']['定率'], $p['定率P２'] * $c['C256'] / $c['C250'] * $c['D250'][$i], 0);
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
//	$c['I102'] = $s['会社']['D25'];
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
//#	$c['I227'] = $s['会社']['D25'];
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