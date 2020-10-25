<?
/******************************************************
* system  : xxx リスク診断
* title   : リスク診断結果取得処理
* content : リスク診断結果をレスポンス形式で取得する
* version : 1.0
* author  : aizawa
*******************************************************/

// ■診断結果取得
function get_risk_data($basic_info, $company_info, $hoken_info, $osusume_info) {
	global $g_risk, $g_osusume;

	// リスク診断計算処理
	set_current_sheets($g_risk);
	_calc_risk_data($basic_info, $company_info, $hoken_info);

	// おすすめ計算処理
	if ($osusume_info->m_exist_flag) {
		set_current_sheets($g_osusume);
		_calc_risk_data($basic_info, $company_info, $osusume_info);
	}

	// ステータス
	$data['ステータス'] = !$hoken_info->m_exist_flag ? 1 : (!$osusume_info->m_exist_flag ? 2 : 3);

	// 更新日
	$data['更新日'] = format_date($basic_info->m_update_date);

	// 結婚種別
	$data['結婚'] = $basic_info->m_basic_info['あなた']['本人']['結婚'];

	// 各診断結果を配列に取得
	$data['総合'] = _get_risk_data_sg();
	$data['死亡'] = _get_risk_data_sb();
	$data['医療'] = _get_risk_data_ir();
	$data['就業不能'] = _get_risk_data_sf();
	$data['老後'] = _get_risk_data_rg();
	$data['損害'] = _get_risk_data_sn();

	set_current_sheets($g_risk);

	return $data;
}

// ■リスク診断全計算処理
function _calc_risk_data($basic_info, $company_info, $hoken_info) {
	calc_basic_info1($basic_info->m_basic_info);
	calc_company_info($company_info->m_guarantee, $company_info->m_retire_rate);
	calc_basic_info2();
	calc_seiho_info($hoken_info->m_seiho_data);
	calc_sonpo_info($hoken_info->m_sonpo_data);
	calc_basic_info3();
	calc_sb_data();
	calc_ir_data();
	calc_sf_data();
	calc_rg_data();
	calc_sn_data();
	calc_sg_data();
}

// ■総合診断データ取り出し処理
function _get_risk_data_sg() {
	global $g_risk, $g_osusume;

	$risk = &$g_risk['総合'];
	$osusume = &$g_osusume['総合'];

	// 本人・リスト・死亡
	$d = &$data['本人']['リスト']['死亡'];
	$d['リスク'] = round($risk['J4'] / 10000);
	$d['カバー'] = round($risk['J6'] / 10000);
	$d['ポイント'] = round($risk['J7'], 1);
	$d['おすすめ保障'] = round($osusume['J6'] / 10000);
	$d['おすすめポイント'] = round($osusume['J7'], 1);

	// 本人・リスト・医療
	$d = &$data['本人']['リスト']['医療'];
	$d['リスク1'] = round($risk['J8'] / 10000);
	$d['リスク2'] = round($risk['J9'] / 10000);
	$d['カバー1'] = round($risk['J10'] / 10000);
	$d['カバー2'] = round($risk['J11'] / 10000);
	$d['ポイント'] = round($risk['J12'], 1);
	$d['おすすめ保障1'] = round($osusume['J10'] / 10000);
	$d['おすすめ保障2'] = round($osusume['J11'] / 10000);
	$d['おすすめポイント'] = round($osusume['J12'], 1);

	// 本人・リスト・就業不能
	$d = &$data['本人']['リスト']['就業不能'];
	$d['リスク1'] = round($risk['J13'] / 10000);
	$d['リスク2'] = round($risk['J14'] / 10000);
	$d['カバー1'] = round($risk['J15'] / 10000);
	$d['カバー2'] = round($risk['J16'] / 10000);
	$d['ポイント'] = round($risk['J17'], 1);
	$d['おすすめ保障1'] = round($osusume['J15'] / 10000);
	$d['おすすめ保障2'] = round($osusume['J16'] / 10000);
	$d['おすすめポイント'] = round($osusume['J17'], 1);

	// 本人・リスト・老後
	$d = &$data['本人']['リスト']['老後'];
	$d['リスク'] = round($risk['J18'] / 10000);
	$d['カバー'] = round($risk['J20'] / 10000);
	$d['ポイント'] = round($risk['J21'], 1);
	$d['おすすめ保障'] = round($osusume['J20'] / 10000);
	$d['おすすめポイント'] = round($osusume['J21'], 1);

	// 本人・リスト・損害
	$d = &$data['本人']['リスト']['損害'];
	$d['リスク1'] = round($risk['J22'] / 10000);
	$d['リスク2'] = round($risk['J23'] / 10000);
	$d['カバー1'] = round($risk['J24'] / 10000);
	$d['カバー2'] = round($risk['J25'] / 10000);
	$d['ポイント'] = round($risk['J26'], 1);
	$d['おすすめ保障1'] = round($osusume['J24'] / 10000);
	$d['おすすめ保障2'] = round($osusume['J25'] / 10000);
	$d['おすすめポイント'] = round($osusume['J26'], 1);

	// 本人・グラフ・社会保障
	$d = &$data['本人']['グラフ']['社会保障'];
	$d['死亡'] = round($risk['C37'], 1);
	$d['医療'] = round($risk['C38'], 1);
	$d['就業不能'] = round($risk['C39'], 1);
	$d['老後'] = round($risk['C40'], 1);
	$d['損害'] = round($risk['C41'], 1);

	// 本人・グラフ・会社制度
	$d = &$data['本人']['グラフ']['会社制度'];
	$d['死亡'] = round($risk['D37'], 1);
	$d['医療'] = round($risk['D38'], 1);
	$d['就業不能'] = round($risk['D39'], 1);
	$d['老後'] = round($risk['D40'], 1);
	$d['損害'] = round($risk['D41'], 1);

	// 本人・グラフ・その他
	$d = &$data['本人']['グラフ']['その他'];
	$d['死亡'] = round($risk['E37'], 1);
	$d['医療'] = round($risk['E38'], 1);
	$d['就業不能'] = round($risk['E39'], 1);
	$d['老後'] = round($risk['E40'], 1);
	$d['損害'] = round($risk['E41'], 1);

	// 本人・グラフ・今の保障
	$d = &$data['本人']['グラフ']['今の保障'];
	$d['死亡'] = round($risk['F37'], 1);
	$d['医療'] = round($risk['F38'], 1);
	$d['就業不能'] = round($risk['F39'], 1);
	$d['老後'] = round($risk['F40'], 1);
	$d['損害'] = round($risk['F41'], 1);

	// 配偶者・リスト・死亡
	$d = &$data['配偶者']['リスト']['死亡'];
	$d['リスク'] = round($risk['W4'] / 10000);
	$d['カバー'] = round($risk['W6'] / 10000);
	$d['ポイント'] = round($risk['W7'], 1);
	$d['おすすめ保障'] = round($osusume['W6'] / 10000);
	$d['おすすめポイント'] = round($osusume['W7'], 1);

	// 配偶者・リスト・医療
	$d = &$data['配偶者']['リスト']['医療'];
	$d['リスク1'] = round($risk['W8'] / 10000);
	$d['リスク2'] = round($risk['W9'] / 10000);
	$d['カバー1'] = round($risk['W10'] / 10000);
	$d['カバー2'] = round($risk['W11'] / 10000);
	$d['ポイント'] = round($risk['W12'], 1);
	$d['おすすめ保障1'] = round($osusume['W10'] / 10000);
	$d['おすすめ保障2'] = round($osusume['W11'] / 10000);
	$d['おすすめポイント'] = round($osusume['W12'], 1);

	// 配偶者・リスト・就業不能
	$d = &$data['配偶者']['リスト']['就業不能'];
	$d['リスク1'] = round($risk['W13'] / 10000);
	$d['リスク2'] = round($risk['W14'] / 10000);
	$d['カバー1'] = round($risk['W15'] / 10000);
	$d['カバー2'] = round($risk['W16'] / 10000);
	$d['ポイント'] = round($risk['W17'], 1);
	$d['おすすめ保障1'] = round($osusume['W15'] / 10000);
	$d['おすすめ保障2'] = round($osusume['W16'] / 10000);
	$d['おすすめポイント'] = round($osusume['W17'], 1);

	// 配偶者・リスト・老後
	$d = &$data['配偶者']['リスト']['老後'];
	$d['リスク'] = round($risk['W18'] / 10000);
	$d['カバー'] = round($risk['W20'] / 10000);
	$d['ポイント'] = round($risk['W21'], 1);
	$d['おすすめ保障'] = round($osusume['W20'] / 10000);
	$d['おすすめポイント'] = round($osusume['W21'], 1);

	// 配偶者・リスト・損害
	$d = &$data['配偶者']['リスト']['損害'];
	$d['リスク1'] = round($risk['W22'] / 10000);
	$d['リスク2'] = round($risk['W23'] / 10000);
	$d['カバー1'] = round($risk['W24'] / 10000);
	$d['カバー2'] = round($risk['W25'] / 10000);
	$d['ポイント'] = round($risk['W26'], 1);
	$d['おすすめ保障1'] = round($osusume['W24'] / 10000);
	$d['おすすめ保障2'] = round($osusume['W25'] / 10000);
	$d['おすすめポイント'] = round($osusume['W26'], 1);

	// 配偶者・グラフ・社会保障
	$d = &$data['配偶者']['グラフ']['社会保障'];
	$d['死亡'] = round($risk['P37'], 1);
	$d['医療'] = round($risk['P38'], 1);
	$d['就業不能'] = round($risk['P39'], 1);
	$d['老後'] = round($risk['P40'], 1);
	$d['損害'] = round($risk['P41'], 1);

	// 配偶者・グラフ・会社制度
	$d = &$data['配偶者']['グラフ']['会社制度'];
	$d['死亡'] = round($risk['Q37'], 1);
	$d['医療'] = round($risk['Q38'], 1);
	$d['就業不能'] = round($risk['Q39'], 1);
	$d['老後'] = round($risk['Q40'], 1);
	$d['損害'] = round($risk['Q41'], 1);

	// 配偶者・グラフ・その他
	$d = &$data['配偶者']['グラフ']['その他'];
	$d['死亡'] = round($risk['R37'], 1);
	$d['医療'] = round($risk['R38'], 1);
	$d['就業不能'] = round($risk['R39'], 1);
	$d['老後'] = round($risk['R40'], 1);
	$d['損害'] = round($risk['R41'], 1);

	// 配偶者・グラフ・今の保障
	$d = &$data['配偶者']['グラフ']['今の保障'];
	$d['死亡'] = round($risk['S37'], 1);
	$d['医療'] = round($risk['S38'], 1);
	$d['就業不能'] = round($risk['S39'], 1);
	$d['老後'] = round($risk['S40'], 1);
	$d['損害'] = round($risk['S41'], 1);

	return $data;
}

// ■死亡診断データ取り出し処理
function _get_risk_data_sb() {
	global $g_risk, $g_osusume;

	$risk = &$g_risk['死亡'];
	$osusume = &$g_osusume['死亡'];

	// 本人・リスト・必要保障額
	$d = &$data['本人']['リスト']['必要保障額'];
	$d['葬儀費用'] = round($risk['J4']);
	$d['遺族生活費'] = round($risk['J5']);
	$d['子供教育費'] = round($risk['J6']);
	$d['住宅費'] = round($risk['J7']);
	$d['育児代行'] = round($risk['J8']);
	$d['合計'] = round($risk['J9']);

	// 本人・リスト・準備済資金
	$d = &$data['本人']['リスト']['準備済資金'];
	$d['社会保障'] = round($risk['J11']);
	$d['会社制度'] = round($risk['J12']);
	$d['その他'] = round($risk['J13']);
	$d['今の保障'] = round($risk['J14']);
	$d['合計'] = round($risk['J15']);
	$d['カバー率'] = round($risk['J16'] * 100, 1);
	$d['おすすめ保障'] = round($osusume['J14']);
	$d['おすすめポイント'] = round($osusume['J16'] * 100, 1);

	// 本人・グラフ
	$d = &$data['本人']['グラフ'];
	$d['社会保障'] = round($risk['C32'], 1);
	$d['会社制度'] = round($risk['D32'], 1);
	$d['その他'] = round($risk['E32'], 1);
	$d['今の保障'] = round($risk['F32'], 1);

	// 配偶者・リスト・必要保障額
	$d = &$data['配偶者']['リスト']['必要保障額'];
	$d['葬儀費用'] = round($risk['X4']);
	$d['遺族生活費'] = round($risk['X5']);
	$d['子供教育費'] = round($risk['X6']);
	$d['住宅費'] = round($risk['X7']);
	$d['育児代行'] = round($risk['X8']);
	$d['合計'] = round($risk['X9']);

	// 配偶者・リスト・準備済資金
	$d = &$data['配偶者']['リスト']['準備済資金'];
	$d['社会保障'] = round($risk['X11']);
	$d['会社制度'] = round($risk['X12']);
	$d['その他'] = round($risk['X13']);
	$d['今の保障'] = round($risk['X14']);
	$d['合計'] = round($risk['X15']);
	$d['カバー率'] = round($risk['X16'] * 100, 1);
	$d['おすすめ保障'] = round($osusume['X14']);
	$d['おすすめポイント'] = round($osusume['X16'] * 100, 1);

	// 配偶者・グラフ
	$d = &$data['配偶者']['グラフ'];
	$d['社会保障'] = round($risk['Q32'], 1);
	$d['会社制度'] = round($risk['R32'], 1);
	$d['その他'] = round($risk['S32'], 1);
	$d['今の保障'] = round($risk['T32'], 1);

	return $data;
}

// ■医療診断データ取り出し処理
function _get_risk_data_ir() {
	global $g_risk, $g_osusume;

	$risk = &$g_risk['医療'];
	$osusume = &$g_osusume['医療'];

	// 本人・リスト・病気
	$d = &$data['本人']['リスト']['病気'];
	$d['必要額'] = round($risk['K4']);
	$d['保障']['社会保障'] = round($risk['C46'] * 10000);
	$d['保障']['会社制度'] = round($risk['D46'] * 10000);
	$d['保障']['今の保障'] = round($risk['E46'] * 10000);
	$d['カバー率']['社会保障'] = round($risk['C24'], 1);
	$d['カバー率']['会社制度'] = round($risk['D24'], 1);
	$d['カバー率']['今の保障'] = round($risk['E24'], 1);
	$d['おすすめ保障'] = round($osusume['E46'] * 10000);
	$d['おすすめカバー率'] = round($osusume['E24'], 1);

	// 本人・リスト・けが
	$d = &$data['本人']['リスト']['けが'];
	$d['必要額'] = round($risk['K7']);
	$d['保障']['社会保障'] = round($risk['C47'] * 10000);
	$d['保障']['会社制度'] = round($risk['D47'] * 10000);
	$d['保障']['今の保障'] = round($risk['E47'] * 10000);
	$d['カバー率']['社会保障'] = round($risk['C25'], 1);
	$d['カバー率']['会社制度'] = round($risk['D25'], 1);
	$d['カバー率']['今の保障'] = round($risk['E25'], 1);
	$d['おすすめ保障'] = round($osusume['E47'] * 10000);
	$d['おすすめカバー率'] = round($osusume['E25'], 1);

	// 本人・リスト・脳卒中
	$d = &$data['本人']['リスト']['脳卒中'];
	$d['必要額'] = round($risk['K10']);
	$d['保障']['社会保障'] = round($risk['C48'] * 10000);
	$d['保障']['会社制度'] = round($risk['D48'] * 10000);
	$d['保障']['今の保障'] = round($risk['E48'] * 10000);
	$d['カバー率']['社会保障'] = round($risk['C26'], 1);
	$d['カバー率']['会社制度'] = round($risk['D26'], 1);
	$d['カバー率']['今の保障'] = round($risk['E26'], 1);
	$d['おすすめ保障'] = round($osusume['E48'] * 10000);
	$d['おすすめカバー率'] = round($osusume['E26'], 1);

	// 本人・リスト・がん
	$d = &$data['本人']['リスト']['がん'];
	$d['必要額'] = round($risk['K13']);
	$d['保障']['社会保障'] = round($risk['C49'] * 10000);
	$d['保障']['会社制度'] = round($risk['D49'] * 10000);
	$d['保障']['今の保障'] = round($risk['E49'] * 10000);
	$d['カバー率']['社会保障'] = round($risk['C27'], 1);
	$d['カバー率']['会社制度'] = round($risk['D27'], 1);
	$d['カバー率']['今の保障'] = round($risk['E27'], 1);
	$d['おすすめ保障'] = round($osusume['E49'] * 10000);
	$d['おすすめカバー率'] = round($osusume['E27'], 1);

	// 本人・リスト・心筋梗塞>
	$d = &$data['本人']['リスト']['心筋梗塞'];
	$d['必要額'] = round($risk['K16']);
	$d['保障']['社会保障'] = round($risk['C50'] * 10000);
	$d['保障']['会社制度'] = round($risk['D50'] * 10000);
	$d['保障']['今の保障'] = round($risk['E50'] * 10000);
	$d['カバー率']['社会保障'] = round($risk['C28'], 1);
	$d['カバー率']['会社制度'] = round($risk['D28'], 1);
	$d['カバー率']['今の保障'] = round($risk['E28'], 1);
	$d['おすすめ保障'] = round($osusume['E50'] * 10000);
	$d['おすすめカバー率'] = round($osusume['E28'], 1);

	// 本人・リスト・糖尿病>
	$d = &$data['本人']['リスト']['糖尿病'];
	$d['必要額'] = round($risk['K19']);
	$d['保障']['社会保障'] = round($risk['C51'] * 10000);
	$d['保障']['会社制度'] = round($risk['D51'] * 10000);
	$d['保障']['今の保障'] = round($risk['E51'] * 10000);
	$d['カバー率']['社会保障'] = round($risk['C29'], 1);
	$d['カバー率']['会社制度'] = round($risk['D29'], 1);
	$d['カバー率']['今の保障'] = round($risk['E29'], 1);
	$d['おすすめ保障'] = round($osusume['E51'] * 10000);
	$d['おすすめカバー率'] = round($osusume['E29'], 1);

	// 本人・グラフ・社会保障
	$d = &$data['本人']['グラフ']['社会保障'];
	$d['病気'] = round($risk['C55'], 1);
	$d['けが'] = round($risk['C56'], 1);
	$d['脳卒中'] = round($risk['C57'], 1);
	$d['がん'] = round($risk['C58'], 1);
	$d['心筋梗塞'] = round($risk['C59'], 1);
	$d['糖尿病'] = round($risk['C60'], 1);

	// 本人・グラフ・会社制度
	$d = &$data['本人']['グラフ']['会社制度'];
	$d['病気'] = round($risk['D55'], 1);
	$d['けが'] = round($risk['D56'], 1);
	$d['脳卒中'] = round($risk['D57'], 1);
	$d['がん'] = round($risk['D58'], 1);
	$d['心筋梗塞'] = round($risk['D59'], 1);
	$d['糖尿病'] = round($risk['D60'], 1);

	// 本人・グラフ・今の保障
	$d = &$data['本人']['グラフ']['今の保障'];
	$d['病気'] = round($risk['E55'], 1);
	$d['けが'] = round($risk['E56'], 1);
	$d['脳卒中'] = round($risk['E57'], 1);
	$d['がん'] = round($risk['E58'], 1);
	$d['心筋梗塞'] = round($risk['E59'], 1);
	$d['糖尿病'] = round($risk['E60'], 1);

	// 配偶者・リスト・病気
	$d = &$data['配偶者']['リスト']['病気'];
	$d['必要額'] = round($risk['X4']);
	$d['保障']['社会保障'] = round($risk['P46'] * 10000);
	$d['保障']['会社制度'] = round($risk['Q46'] * 10000);
	$d['保障']['今の保障'] = round($risk['R46'] * 10000);
	$d['カバー率']['社会保障'] = round($risk['P24'], 1);
	$d['カバー率']['会社制度'] = round($risk['Q24'], 1);
	$d['カバー率']['今の保障'] = round($risk['R24'], 1);
	$d['おすすめ保障'] = round($osusume['R46'] * 10000);
	$d['おすすめカバー率'] = round($osusume['R24'], 1);

	// 配偶者・リスト・けが
	$d = &$data['配偶者']['リスト']['けが'];
	$d['必要額'] = round($risk['X7']);
	$d['保障']['社会保障'] = round($risk['P47'] * 10000);
	$d['保障']['会社制度'] = round($risk['Q47'] * 10000);
	$d['保障']['今の保障'] = round($risk['R47'] * 10000);
	$d['カバー率']['社会保障'] = round($risk['P25'], 1);
	$d['カバー率']['会社制度'] = round($risk['Q25'], 1);
	$d['カバー率']['今の保障'] = round($risk['R25'], 1);
	$d['おすすめ保障'] = round($osusume['R47'] * 10000);
	$d['おすすめカバー率'] = round($osusume['R25'], 1);

	// 配偶者・リスト・脳卒中
	$d = &$data['配偶者']['リスト']['脳卒中'];
	$d['必要額'] = round($risk['X10']);
	$d['保障']['社会保障'] = round($risk['P48'] * 10000);
	$d['保障']['会社制度'] = round($risk['Q48'] * 10000);
	$d['保障']['今の保障'] = round($risk['R48'] * 10000);
	$d['カバー率']['社会保障'] = round($risk['P26'], 1);
	$d['カバー率']['会社制度'] = round($risk['Q26'], 1);
	$d['カバー率']['今の保障'] = round($risk['R26'], 1);
	$d['おすすめ保障'] = round($osusume['R48'] * 10000);
	$d['おすすめカバー率'] = round($osusume['R26'], 1);

	// 配偶者・リスト・がん
	$d = &$data['配偶者']['リスト']['がん'];
	$d['必要額'] = round($risk['X13']);
	$d['保障']['社会保障'] = round($risk['P49'] * 10000);
	$d['保障']['会社制度'] = round($risk['Q49'] * 10000);
	$d['保障']['今の保障'] = round($risk['R49'] * 10000);
	$d['カバー率']['社会保障'] = round($risk['P27'], 1);
	$d['カバー率']['会社制度'] = round($risk['Q27'], 1);
	$d['カバー率']['今の保障'] = round($risk['R27'], 1);
	$d['おすすめ保障'] = round($osusume['R49'] * 10000);
	$d['おすすめカバー率'] = round($osusume['R27'], 1);

	// 配偶者・リスト・心筋梗塞
	$d = &$data['配偶者']['リスト']['心筋梗塞'];
	$d['必要額'] = round($risk['X16']);
	$d['保障']['社会保障'] = round($risk['P50'] * 10000);
	$d['保障']['会社制度'] = round($risk['Q50'] * 10000);
	$d['保障']['今の保障'] = round($risk['R50'] * 10000);
	$d['カバー率']['社会保障'] = round($risk['P28'], 1);
	$d['カバー率']['会社制度'] = round($risk['Q28'], 1);
	$d['カバー率']['今の保障'] = round($risk['R28'], 1);
	$d['おすすめ保障'] = round($osusume['R50'] * 10000);
	$d['おすすめカバー率'] = round($osusume['R28'], 1);

	// 配偶者・リスト・糖尿病
	$d = &$data['配偶者']['リスト']['糖尿病'];
	$d['必要額'] = round($risk['X19']);
	$d['保障']['社会保障'] = round($risk['P51'] * 10000);
	$d['保障']['会社制度'] = round($risk['Q51'] * 10000);
	$d['保障']['今の保障'] = round($risk['R51'] * 10000);
	$d['カバー率']['社会保障'] = round($risk['P29'], 1);
	$d['カバー率']['会社制度'] = round($risk['Q29'], 1);
	$d['カバー率']['今の保障'] = round($risk['R29'], 1);
	$d['おすすめ保障'] = round($osusume['R51'] * 10000);
	$d['おすすめカバー率'] = round($osusume['R29'], 1);

	// 配偶者・グラフ・社会保障
	$d = &$data['配偶者']['グラフ']['社会保障'];
	$d['病気'] = round($risk['P55'], 1);
	$d['けが'] = round($risk['P56'], 1);
	$d['脳卒中'] = round($risk['P57'], 1);
	$d['がん'] = round($risk['P58'], 1);
	$d['心筋梗塞'] = round($risk['P59'], 1);
	$d['糖尿病'] = round($risk['P60'], 1);

	// 配偶者・グラフ・会社制度
	$d = &$data['配偶者']['グラフ']['会社制度'];
	$d['病気'] = round($risk['Q55'], 1);
	$d['けが'] = round($risk['Q56'], 1);
	$d['脳卒中'] = round($risk['Q57'], 1);
	$d['がん'] = round($risk['Q58'], 1);
	$d['心筋梗塞'] = round($risk['Q59'], 1);
	$d['糖尿病'] = round($risk['Q60'], 1);

	// 配偶者・グラフ・今の保障
	$d = &$data['配偶者']['グラフ']['今の保障'];
	$d['病気'] = round($risk['R55'], 1);
	$d['けが'] = round($risk['R56'], 1);
	$d['脳卒中'] = round($risk['R57'], 1);
	$d['がん'] = round($risk['R58'], 1);
	$d['心筋梗塞'] = round($risk['R59'], 1);
	$d['糖尿病'] = round($risk['R60'], 1);

	return $data;
}

// ■就業不能診断データ取り出し処理
function _get_risk_data_sf() {
	global $g_risk, $g_osusume;

	$risk = &$g_risk['就業不能'];
	$osusume = &$g_osusume['就業不能'];

	// 本人・リスト・短期・月数
	$d = &$data['本人']['リスト']['短期'];
	$d['月数'] = $risk['C4'];

	// 本人・リスト・短期・必要資金
	$d = &$data['本人']['リスト']['短期']['必要資金'];
	$d['生活費'] = round($risk['C5']);
	$d['教育費'] = round($risk['C6']);
	$d['住宅費'] = round($risk['C7']);
	$d['育児代行費'] = round($risk['C8']);
	$d['合計'] = round($risk['C9']);

	// 本人・リスト・短期・準備済資金
	$d = &$data['本人']['リスト']['短期']['準備済資金'];
	$d['社会保障'] = round($risk['C11']);
	$d['会社制度'] = round($risk['C12']);
	$d['その他'] = round($risk['C13']);
	$d['今の保障'] = round($risk['C14']);
	$d['合計'] = round($risk['C15']);
	$d['カバー率'] = round($risk['C16'] * 100, 1);
	$d['おすすめ保障'] = round($osusume['C14']);
	$d['おすすめカバー率'] = round($osusume['C16'] * 100, 1);

	// 本人・リスト・中期・月数
	$d = &$data['本人']['リスト']['中期'];
	$d['月数'] = $risk['E4'];

	// 本人・リスト・中期・必要資金
	$d = &$data['本人']['リスト']['中期']['必要資金'];
	$d['生活費'] = round($risk['E5']);
	$d['教育費'] = round($risk['E6']);
	$d['住宅費'] = round($risk['E7']);
	$d['育児代行費'] = round($risk['E8']);
	$d['合計'] = round($risk['E9']);

	// 本人・リスト・中期・準備済資金
	$d = &$data['本人']['リスト']['中期']['準備済資金'];
	$d['社会保障'] = round($risk['E11']);
	$d['会社制度'] = round($risk['E12']);
	$d['その他'] = round($risk['E13']);
	$d['今の保障'] = round($risk['E14']);
	$d['合計'] = round($risk['E15']);
	$d['カバー率'] = round($risk['E16'] * 100, 1);
	$d['おすすめ保障'] = round($osusume['E14']);
	$d['おすすめカバー率'] = round($osusume['E16'] * 100, 1);

	// 本人・リスト・長期・月数
	$d = &$data['本人']['リスト']['長期'];
	$d['月数'] = $risk['G4'];

	// 本人・リスト・長期・必要資金
	$d = &$data['本人']['リスト']['長期']['必要資金'];
	$d['生活費'] = round($risk['G5']);
	$d['教育費'] = round($risk['G6']);
	$d['住宅費'] = round($risk['G7']);
	$d['育児代行費'] = round($risk['G8']);
	$d['合計'] = round($risk['G9']);

	// 本人・リスト・長期・準備済資金
	$d = &$data['本人']['リスト']['長期']['準備済資金'];
	$d['社会保障'] = round($risk['G11']);
	$d['会社制度'] = round($risk['G12']);
	$d['その他'] = round($risk['G13']);
	$d['今の保障'] = round($risk['G14']);
	$d['合計'] = round($risk['G15']);
	$d['カバー率'] = round($risk['G16'] * 100, 1);
	$d['おすすめ保障'] = round($osusume['G14']);
	$d['おすすめカバー率'] = round($osusume['G16'] * 100, 1);

	// 本人・リスト・合計・月数
	$d = &$data['本人']['リスト']['合計'];
	$d['月数'] = $risk['J4'];

	// 本人・リスト・合計・必要資金
	$d = &$data['本人']['リスト']['合計']['必要資金'];
	$d['生活費'] = round($risk['J5']);
	$d['教育費'] = round($risk['J6']);
	$d['住宅費'] = round($risk['J7']);
	$d['育児代行費'] = round($risk['J8']);
	$d['合計'] = round($risk['J9']);

	// 本人・リスト・合計・準備済資金
	$d = &$data['本人']['リスト']['合計']['準備済資金'];
	$d['社会保障'] = round($risk['J11']);
	$d['会社制度'] = round($risk['J12']);
	$d['その他'] = round($risk['J13']);
	$d['今の保障'] = round($risk['J14']);
	$d['合計'] = round($risk['J15']);
	$d['カバー率'] = round($risk['J16'] * 100, 1);
	$d['おすすめ保障'] = round($osusume['J14']);
	$d['おすすめカバー率'] = round($osusume['J16'] * 100, 1);

	// 本人・グラフ・社会保障
	$d = &$data['本人']['グラフ']['社会保障'];
	$d['短期'] = round($risk['C36'], 1);
	$d['中期'] = round($risk['C37'], 1);
	$d['長期'] = round($risk['C38'], 1);

	// 本人・グラフ・会社制度
	$d = &$data['本人']['グラフ']['会社制度'];
	$d['短期'] = round($risk['D36'], 1);
	$d['中期'] = round($risk['D37'], 1);
	$d['長期'] = round($risk['D38'], 1);

	// 本人・グラフ・その他
	$d = &$data['本人']['グラフ']['その他'];
	$d['短期'] = round($risk['E36'], 1);
	$d['中期'] = round($risk['E37'], 1);
	$d['長期'] = round($risk['E38'], 1);

	// 本人・グラフ・今の保障
	$d = &$data['本人']['グラフ']['今の保障'];
	$d['短期'] = round($risk['F36'], 1);
	$d['中期'] = round($risk['F37'], 1);
	$d['長期'] = round($risk['F38'], 1);

	// 配偶者・リスト・短期・月数
	$d = &$data['配偶者']['リスト']['短期'];
	$d['月数'] = $risk['R4'];

	// 配偶者・リスト・短期・必要資金
	$d = &$data['配偶者']['リスト']['短期']['必要資金'];
	$d['生活費'] = round($risk['R5']);
	$d['教育費'] = round($risk['R6']);
	$d['住宅費'] = round($risk['R7']);
	$d['育児代行費'] = round($risk['R8']);
	$d['合計'] = round($risk['R9']);

	// 配偶者・リスト・短期・準備済資金
	$d = &$data['配偶者']['リスト']['短期']['準備済資金'];
	$d['社会保障'] = round($risk['R11']);
	$d['会社制度'] = round($risk['R12']);
	$d['その他'] = round($risk['R13']);
	$d['今の保障'] = round($risk['R14']);
	$d['合計'] = round($risk['R15']);
	$d['カバー率'] = round($risk['R16'] * 100, 1);
	$d['おすすめ保障'] = round($osusume['R14']);
	$d['おすすめカバー率'] = round($osusume['R16'] * 100, 1);

	// 配偶者・リスト・中期・月数
	$d = &$data['配偶者']['リスト']['中期'];
	$d['月数'] = $risk['T4'];

	// 配偶者・リスト・中期・必要資金
	$d = &$data['配偶者']['リスト']['中期']['必要資金'];
	$d['生活費'] = round($risk['T5']);
	$d['教育費'] = round($risk['T6']);
	$d['住宅費'] = round($risk['T7']);
	$d['育児代行費'] = round($risk['T8']);
	$d['合計'] = round($risk['T9']);

	// 配偶者・リスト・中期・準備済資金
	$d = &$data['配偶者']['リスト']['中期']['準備済資金'];
	$d['社会保障'] = round($risk['T11']);
	$d['会社制度'] = round($risk['T12']);
	$d['その他'] = round($risk['T13']);
	$d['今の保障'] = round($risk['T14']);
	$d['合計'] = round($risk['T15']);
	$d['カバー率'] = round($risk['T16'] * 100, 1);
	$d['おすすめ保障'] = round($osusume['T14']);
	$d['おすすめカバー率'] = round($osusume['T16'] * 100, 1);

	// 配偶者・リスト・長期・月数
	$d = &$data['配偶者']['リスト']['長期'];
	$d['月数'] = $risk['V4'];

	// 配偶者・リスト・長期・必要資金
	$d = &$data['配偶者']['リスト']['長期']['必要資金'];
	$d['生活費'] = round($risk['V5']);
	$d['教育費'] = round($risk['V6']);
	$d['住宅費'] = round($risk['V7']);
	$d['育児代行費'] = round($risk['V8']);
	$d['合計'] = round($risk['V9']);

	// 配偶者・リスト・長期・準備済資金
	$d = &$data['配偶者']['リスト']['長期']['準備済資金'];
	$d['社会保障'] = round($risk['V11']);
	$d['会社制度'] = round($risk['V12']);
	$d['その他'] = round($risk['V13']);
	$d['今の保障'] = round($risk['V14']);
	$d['合計'] = round($risk['V15']);
	$d['カバー率'] = round($risk['V16'] * 100, 1);
	$d['おすすめ保障'] = round($osusume['V14']);
	$d['おすすめカバー率'] = round($osusume['V16'] * 100, 1);

	// 配偶者・リスト・合計・月数
	$d = &$data['配偶者']['リスト']['合計'];
	$d['月数'] = $risk['Y4'];

	// 配偶者・リスト・合計・必要資金
	$d = &$data['配偶者']['リスト']['合計']['必要資金'];
	$d['生活費'] = round($risk['Y5']);
	$d['教育費'] = round($risk['Y6']);
	$d['住宅費'] = round($risk['Y7']);
	$d['育児代行費'] = round($risk['Y8']);
	$d['合計'] = round($risk['Y9']);

	// 配偶者・リスト・合計・準備済資金
	$d = &$data['配偶者']['リスト']['合計']['準備済資金'];
	$d['社会保障'] = round($risk['Y11']);
	$d['会社制度'] = round($risk['Y12']);
	$d['その他'] = round($risk['Y13']);
	$d['今の保障'] = round($risk['Y14']);
	$d['合計'] = round($risk['Y15']);
	$d['カバー率'] = round($risk['Y16'] * 100, 1);
	$d['おすすめ保障'] = round($osusume['Y14']);
	$d['おすすめカバー率'] = round($osusume['Y16'] * 100, 1);

	// 配偶者・グラフ・社会保障
	$d = &$data['配偶者']['グラフ']['社会保障'];
	$d['短期'] = round($risk['R36'], 1);
	$d['中期'] = 0;
	$d['長期'] = round($risk['R37'], 1);

	// 配偶者・グラフ・会社制度
	$d = &$data['配偶者']['グラフ']['会社制度'];
	$d['短期'] = 0;
	$d['中期'] = 0;
	$d['長期'] = 0;

	// 配偶者・グラフ・その他
	$d = &$data['配偶者']['グラフ']['その他'];
	$d['短期'] = round($risk['T36'], 1);
	$d['中期'] = 0;
	$d['長期'] = round($risk['T37'], 1);

	// 配偶者・グラフ・今の保障
	$d = &$data['配偶者']['グラフ']['今の保障'];
	$d['短期'] = round($risk['U36'], 1);
	$d['中期'] = 0;
	$d['長期'] = round($risk['U37'], 1);

	return $data;
}

// ■老後診断データ取り出し処理
function _get_risk_data_rg() {
	global $g_risk, $g_osusume;

	$risk = &$g_risk['老後'];
	$osusume = &$g_osusume['老後'];

	// リスト・老後生活
	$d = &$data['リスト']['老後生活'];
	$d['希望生活費'] = round($g_risk['基本']['I47']);
	$d['余裕資金'] = round($risk['L16']);
	$d['社会保障依存度'] = round($risk['C26'] * 100, 1);
	$d['就業依存度'] = round($risk['C27'] * 100, 1);
	$d['生活費削減額'] = round($risk['J26']);
	$d['年間必要貯蓄額'] = round($risk['J27']);

	// リスト・前期・必要老後資金
	$d = &$data['リスト']['前期']['必要老後資金'];
	$d['生活費'] = round($risk['C4']);
	$d['住宅費'] = round($risk['C5']);
	$d['教育費'] = round($risk['C6']);
	$d['合計'] = round($risk['C7']);

	// リスト・前期・準備済資金
	$d = &$data['リスト']['前期']['準備済資金'];
	$d['社会保障'] = round($risk['C8']);
	$d['会社制度'] = round($risk['C9']);
	$d['その他'] = round($risk['C10']);
	$d['今の保障'] = round($risk['C11']);
	$d['合計'] = round($risk['C12']);
	$d['カバー率'] = round($risk['C13'] * 100, 1);
	$d['おすすめ保障'] = round($osusume['C11']);
	$d['おすすめカバー率'] = round($osusume['C13'] * 100, 1);

	// リスト・後期・必要老後資金
	$d = &$data['リスト']['後期']['必要老後資金'];
	$d['生活費'] = round($risk['E4']);
	$d['住宅費'] = round($risk['E5']);
	$d['教育費'] = round($risk['E6']);
	$d['合計'] = round($risk['E7']);

	// リスト・後期・準備済資金
	$d = &$data['リスト']['後期']['準備済資金'];
	$d['社会保障'] = round($risk['E8']);
	$d['会社制度'] = round($risk['E9']);
	$d['その他'] = round($risk['E10']);
	$d['今の保障'] = round($risk['E11']);
	$d['合計'] = round($risk['E12']);
	$d['カバー率'] = round($risk['E13'] * 100, 1);
	$d['おすすめ保障'] = round($osusume['E11']);
	$d['おすすめカバー率'] = round($osusume['E13'] * 100, 1);

	// リスト・合計・必要老後資金
	$d = &$data['リスト']['合計']['必要老後資金'];
	$d['生活費'] = round($risk['G4']);
	$d['住宅費'] = round($risk['G5']);
	$d['教育費'] = round($risk['G6']);
	$d['合計'] = round($risk['G7']);

	// リスト・合計・準備済資金
	$d = &$data['リスト']['合計']['準備済資金'];
	$d['社会保障'] = round($risk['G8']);
	$d['会社制度'] = round($risk['G9']);
	$d['その他'] = round($risk['G10']);
	$d['今の保障'] = round($risk['G11']);
	$d['合計'] = round($risk['G12']);
	$d['カバー率'] = round($risk['G13'] * 100, 1);
	$d['おすすめ保障'] = round($osusume['G11']);
	$d['おすすめカバー率'] = round($osusume['G13'] * 100, 1);

	// グラフ・社会保障
	$d = &$data['グラフ']['社会保障'];
	$d['前期'] = round($risk['C32'], 1);
	$d['後期'] = round($risk['C33'], 1);

	// グラフ・会社制度
	$d = &$data['グラフ']['会社制度'];
	$d['前期'] = round($risk['D32'], 1);
	$d['後期'] = round($risk['D33'], 1);

	// グラフ・その他
	$d = &$data['グラフ']['その他'];
	$d['前期'] = round($risk['E32'], 1);
	$d['後期'] = round($risk['E33'], 1);

	// グラフ・今の保障
	$d = &$data['グラフ']['今の保障'];
	$d['前期'] = round($risk['F32'], 1);
	$d['後期'] = round($risk['F33'], 1);

	return $data;
}

// ■損害診断データ取り出し処理
function _get_risk_data_sn() {
	global $g_risk, $g_osusume;

	$risk = &$g_risk['損害'];
	$osusume = &$g_osusume['損害'];

	// リスト・火災
	$d = &$data['リスト']['火災'];
	$d['必要額']['建物'] = round($risk['C33']);
	$d['必要額']['家財'] = round($risk['C34']);
	$d['保障']['建物'] = round($risk['D33']);
	$d['保障']['家財'] = round($risk['D34']);
	$d['カバー率']['建物'] = round($risk['E33'] * 100, 1);
	$d['カバー率']['家財'] = round($risk['E34'] * 100, 1);
	$d['カバー率']['合計'] = round($risk['L7'] * 100, 1);
	$d['おすすめ保障']['建物'] = round($osusume['D33']);
	$d['おすすめ保障']['家財'] = round($osusume['D34']);
	$d['おすすめカバー率']['建物'] = round($osusume['E33'] * 100, 1);
	$d['おすすめカバー率']['家財'] = round($osusume['E34'] * 100, 1);
	$d['おすすめカバー率']['合計'] = round($osusume['L7'] * 100, 1);

	// リスト・盗難
	$d = &$data['リスト']['盗難'];
	$d['必要額'] = round($risk['L11']);
	$d['保障'] = round($risk['L12']);
	$d['カバー率'] = round($risk['L13'] * 100, 1);
	$d['おすすめ保障'] = round($osusume['L12']);
	$d['おすすめカバー率'] = round($osusume['L13'] * 100, 1);

	// リスト・地震
	$d = &$data['リスト']['地震'];
	$d['必要額']['建物'] = round($risk['C35']);
	$d['必要額']['家財'] = round($risk['C36']);
	$d['保障']['建物'] = round($risk['D35']);
	$d['保障']['家財'] = round($risk['D36']);
	$d['カバー率']['建物'] = round($risk['E35'] * 100, 1);
	$d['カバー率']['家財'] = round($risk['E36'] * 100, 1);
	$d['カバー率']['合計'] = round($risk['L10'] * 100, 1);
	$d['おすすめ保障']['建物'] = round($osusume['D35']);
	$d['おすすめ保障']['家財'] = round($osusume['D36']);
	$d['おすすめカバー率']['建物'] = round($osusume['E35'] * 100, 1);
	$d['おすすめカバー率']['家財'] = round($osusume['E36'] * 100, 1);
	$d['おすすめカバー率']['合計'] = round($osusume['L10'] * 100, 1);

	// リスト・事故
	$d = &$data['リスト']['事故'];
	$d['必要額1'] = round($risk['L14']);
	$d['必要額2'] = round($risk['L15']);
	$d['保障1'] = round($risk['L16']);
	$d['保障2'] = round($risk['L17']);
	$d['カバー率1'] = round($risk['L18'] * 100, 1);
	$d['カバー率2'] = round($risk['L19'] * 100, 1);
	$d['おすすめ保障1'] = round($osusume['L16']);
	$d['おすすめ保障2'] = round($osusume['L17']);
	$d['おすすめカバー率1'] = round($osusume['L18'] * 100, 1);
	$d['おすすめカバー率2'] = round($osusume['L19'] * 100, 1);

	// リスト・損害
	$d = &$data['リスト']['損害'];
	$d['必要額'] = round($risk['L20']);
	$d['保障'] = round($risk['L21']);
	$d['カバー率'] = round($risk['L22'] * 100, 1);
	$d['おすすめ保障'] = round($osusume['L21']);
	$d['おすすめカバー率'] = round($osusume['L22'] * 100, 1);

	// グラフ・今の保障
	$d = &$data['グラフ']['今の保障'];
	$d['火災'] = round($risk['C25'], 1);
	$d['地震'] = round($risk['C26'], 1);
	$d['盗難'] = round($risk['C27'], 1);
	$d['事故'] = round($risk['C28'], 1);
	$d['損害'] = round($risk['C29'], 1);

	return $data;
}
?>