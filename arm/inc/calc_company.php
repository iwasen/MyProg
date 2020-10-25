<?
/******************************************************
* system  : xxx リスク診断
* title   : 会社情報計算処理
* content : リスク診断に於ける会社情報関連の計算を行う
* version : 1.0
* author  : aizawa
*******************************************************/

// ■リスク診断／会社情報データ計算
function calc_company_info($guarantee, $retire_rate) {
	global $g_encode, $g_const, $g_sheets;

	$n = '会社';
	$g_sheets[$n] = array();
	$s = &$g_sheets;
	$c = &$g_sheets[$n];

	// 定数
	$t = &$g_encode['所得基準'];
	$c['F6'] = $t['年収'];
	$c['F7'] = $t['年収/12'];
	$c['F8'] = $t['標準報酬月額'];
	$c['L6'] = $t['年収'];
	$c['L7'] = $t['年収/12'];
	$c['L8'] = $t['標準報酬月額'];

	// 企業保障情報
	$c['D8'] = $guarantee->pension_start_age;
	$c['D11'] = $guarantee->paidholiday_monthly;
	$c['D13'] = $guarantee->sick_extension_monthly;
	$c['D14'] = $guarantee->addition_benefit_rate / 100;
	$c['D15'] = $guarantee->individual_benefit_rate / 100;
	$c['D16'] = $guarantee->regular_self_amount;
	$c['D17'] = $guarantee->regular_family_amount;
	$c['D18'] = $guarantee->group_selfhosp_amount;
	$c['D19'] = $guarantee->group_familyhosp_amount;
	$c['D20'] = $guarantee->group_selfattend_amount;
	$c['D21'] = $guarantee->group_familyattend_amount;
	$c['D22'] = $guarantee->impossible_benefit_form;
	$c['D23'] = $guarantee->impossible_benefit_amount;
	$c['D24'] = $guarantee->impossible_benefit_rate / 100;
	$c['D26'] = $guarantee->expire60_whether;
	$c['D27'] = $guarantee->regular_year;
	$c['D29'] = $guarantee->exemption_days;
	$c['D30'] = $guarantee->mentaldisorder_whether;
	$c['D31'] = $guarantee->publicdeduction_whether;
	$c['G5'] = $g_encode['計算フラグ']['自動計算'];
	$c['G9'] = $guarantee->retire_income_base;
	$c['G10'] = $guarantee->retire_income_update_rate;
	$c['G11'] = $guarantee->retire_income_max;
	$c['G12'] = $guarantee->retire_income_min;
	$c['I5'] = $g_encode['計算フラグ']['自動計算'];
	$c['I9'] = $guarantee->death_income_base;
	$c['I10'] = $guarantee->death_income_update_rate;
	$c['I11'] = $guarantee->death_income_max;
	$c['I12'] = $guarantee->death_income_min;
	$c['J5'] = $guarantee->old_income_calc;
	$c['J13'] = $guarantee->old_income_manual;
	$c['J9'] = $guarantee->old_income_base;
	$c['J10'] = $guarantee->old_income_update_rate;
	$c['J11'] = $guarantee->old_income_max;
	$c['J12'] = $guarantee->old_income_min;
	$c['K5'] = $guarantee->bereaved_income_calc;
	$c['K13'] = $guarantee->bereaved_income_manual;
	$c['K9'] = $guarantee->bereaved_income_base;
	$c['K10'] = $guarantee->bereaved_income_update_rate;
	$c['K11'] = $guarantee->bereaved_income_max;
	$c['K12'] = $guarantee->bereaved_income_min;
	$c['M9'] = $guarantee->inposs_income_base;
	$c['M10'] = $guarantee->inposs_income_update_rate;
	$c['M11'] = $guarantee->inposs_income_max;
	$c['M12'] = $guarantee->inposs_income_min;

	// 企業退職金乗率情報
	$r = 19;
	foreach ($retire_rate as $fetch) {
		$c["F$r"] = $fetch->continuous_of_year;
		$c["G$r"] = $fetch->retirement_rate;
		$c["I$r"] = $fetch->death_rate;
		$c["J$r"] = $fetch->old_pension_rate;
		$c["K$r"] = $fetch->survivor_pension_rate;
		$c["L$r"] = $fetch->extension_rate / 100;
		$r++;
	}

	// 計算式
	$c['G6'] = $s['基本']['D9'];
	$c['G7'] = $c['G6'] / 12;
	$c['G8'] = $s['基本']['D14'];
	$c['I6'] = $s['基本']['D9'];
	$c['I7'] = $c['I6'] / 12;
	$c['I8'] = $s['基本']['D14'];
	$c['J6'] = $s['基本']['D9'];
	$c['J7'] = $c['J6'] / 12;
	$c['J8'] = $s['基本']['D14'];
	$c['K6'] = $s['基本']['D9'];
	$c['K7'] = $c['K6'] / 12;
	$c['K8'] = $s['基本']['D14'];
	$c['M6'] = $s['基本']['D9'];
	$c['M7'] = $c['M6'] / 12;
	$c['M8'] = $s['基本']['D14'];
	$c['G14'] = $s['基本']['D11'];
	$c['F19'] = 1;
	$c['D9'] = VB_IF($c['J5'] == $g_encode['計算フラグ']['手入力'], $c['J13'], MAX($c['J12'], MIN($c['J11'], VB_VLOOKUP($n, $c['J9'], 'F6:K8', 5, TRUE) * $c['G14'] * VB_VLOOKUP($n, $c['G14'], 'F19:K65', 5, TRUE) * $c['J10'])));
	$c['D10'] = VB_IF($c['K5'] == $g_encode['計算フラグ']['手入力'], $c['K13'], MAX($c['K12'], MIN($c['K11'], VB_VLOOKUP($n, $c['K9'], 'F6:K8', 6, TRUE) * $c['G14'] * VB_VLOOKUP($n, $c['G14'], 'F19:K65', 6, TRUE) * $c['K10'])));
	$c['D12'] = VB_VLOOKUP($n, $s['基本']['D11'], 'F19:L65', 7, FALSE);
	$c['D25'] = MAX($c['M12'], MIN($c['M11'], VB_VLOOKUP($n, $c['M9'], 'L6:M8', 2, TRUE) * $c['M10']));
	$c['D28'] = VB_IF($c['D26'] == $g_encode['60歳満了']['◎'], ($g_const['定年'] - $s['基本']['D8']) * 12, $c['D27'] * 12);
}
?>