<?
/******************************************************
* system  : xxx リスク診断
* title   : 保険評価結果取得処理
* content : 保険評価結果をレスポンス形式で取得する
* version : 1.0
* author  : aizawa
*******************************************************/

// ■保険評価計算
function get_hyoka_data($basic_info, $company_info, $hoken_info, $osusume_info, $type) {
	// 保険評価 or おすすめ判定
	switch ($type) {
	case $hoken_info->m_type:
		// 保険情報管理クラス
		$hyoka_info = &$hoken_info;

		// 年間保険料（算出無し）
		$amount0 = null;
		$amount1 = null;
		break;
	case $osusume_info->m_type:
		// おすすめ保険情報管理クラス
		$hyoka_info = &$osusume_info;

		// 年間保険料取得
		list($amount0, $amount1) = $hoken_info->get_yearly_insurance();
		break;
	}

	// 保険評価計算処理
	calc_basic_info1($basic_info->m_basic_info);
	calc_company_info($company_info->m_guarantee, $company_info->m_retire_rate);
	calc_basic_info2();
	calc_seiho_info($hyoka_info->m_seiho_data);
	calc_sonpo_info($hyoka_info->m_sonpo_data);
	calc_basic_info3();
	calc_sb_hoken();
	calc_ir_hoken();
	calc_shoken_shindan();

	// ステータス
	$data['ステータス'] = !$hoken_info->m_exist_flag ? 1 : (!$osusume_info->m_exist_flag ? 2 : 3);

	// 更新日
	$data['更新日'] = format_date($hyoka_info->m_update_date);

	// 結婚種別
	$data['結婚'] = $basic_info->m_basic_info['あなた']['本人']['結婚'];

	// 各評価データ
	$data['一覧'] = _get_hyoka_data_list($hyoka_info->m_seiho_data, $hyoka_info->m_sonpo_data, $amount0, $amount1);
	$data['死亡'] = _get_hyoka_data_sb($hyoka_info->m_seiho_data);
	$data['医療'] = _get_hyoka_data_ir($hyoka_info->m_seiho_data);
	$data['損害'] = _get_hyoka_data_sn($hyoka_info->m_sonpo_data);

	return $data;
}

// ■保険の一覧データ取り出し処理
function _get_hyoka_data_list($seiho_data, $sonpo_data, $amount0, $amount1) {
	global $g_sheets, $g_encode;

	$c = &$g_sheets['証券診断'];

	// 本人
	$n = 0;
	$d = &$data['本人'];
	$d['保険料支払額'] = round($c['B22']);
	if ($amount0 != null)
		$d['保険料差額'] = round(($c['B22'] - $amount0) / 10000);
	$d['保険個数'] = count($c['A6']) + count($c['A16']);

	// 本人・生保系
	for ($i = 1; $i <= count($c['A6']); $i++) {
		$n++;
		$seiho_id = $g_sheets['生保']['D6'][$i];

		$d = &$data['本人']["保険$n"];
		$d['保険名称'] = $c['A6'][$i];
		$d['保険会社'] = $c['A7'][$i];
		$d['保険種別'] = $c['A8'][$i];
		if (isset($seiho_data[$seiho_id]['基本']->recommend_change_kind))
			$d['変更種別'] = $seiho_data[$seiho_id]['基本']->recommend_change_kind;
		$d['生保ID'] = $seiho_id;
		$d['年間保険料'] = round($c['B6'][$i]);

		$d = &$data['本人']["保険$n"]['保障範囲別'];
		$d['死亡'] = round($c['D7'][$i]);
		$d['医療'] = round($c['F7'][$i]);
		$d['就業不能']['給付形態'] = $g_sheets['生保']['D99'][$i];
		$d['就業不能']['給付'] = ($g_sheets['生保']['D99'][$i] == $g_encode['給付形態']['定額']) ? round($g_sheets['生保']['D100'][$i]) : round($g_sheets['生保']['D101'][$i] * 100, 1);
		$d['老後']['年額'] = round($c['J6'][$i]);
		$d['老後']['満期金'] = round($c['J7'][$i]);
		$d['損害'] = '−';
	}

	// 本人・損保系
	for ($i = 1; $i <= count($c['A16']); $i++) {
		$n++;
		$sonpo_id = $g_sheets['損保']['C3'][$i];

		$d = &$data['本人']["保険$n"];
		$d['保険名称'] = $c['A16'][$i];
		$d['保険会社'] = $c['A17'][$i];
		$d['保険種別'] = $c['A18'][$i];
		if (isset($sonpo_data[$sonpo_id]->recom_update_flag))
			$d['変更種別'] = $sonpo_data[$sonpo_id]->recom_update_flag;
		$d['損保ID'] = $sonpo_id;
		$d['年間保険料'] = round($c['B16'][$i]);

		$d = &$data['本人']["保険$n"]['保障範囲別'];
		$d['死亡'] = '−';
		$d['医療'] = '−';
		$d['就業不能']['給付形態'] = '−';
		$d['就業不能']['給付'] = '−';
		$d['老後']['年額'] = round($c['J16'][$i]);
		$d['老後']['満期金'] = round($c['J17'][$i]);
		$d['損害'] = $c['L16'][$i];
	}

	// 配偶者
	$n = 0;
	$d = &$data['配偶者'];
	$d['保険料支払額'] = round($c['B46']);
	if ($amount1 != null)
		$d['保険料差額'] = round(($c['B46'] - $amount1) / 10000);
	$d['保険個数'] = count($c['A30']) + count($c['A40']);

	// 配偶者・生保系
	for ($i = 1; $i <= count($c['A30']); $i++) {
		$n++;
		$seiho_id = $g_sheets['生保']['D140'][$i];

		$d = &$data['配偶者']["保険$n"];
		$d['保険名称'] = $c['A30'][$i];
		$d['保険会社'] = $c['A31'][$i];
		$d['保険種別'] = $c['A32'][$i];
		if (isset($seiho_data[$seiho_id]['基本']->recommend_change_kind))
			$d['変更種別'] = $seiho_data[$seiho_id]['基本']->recommend_change_kind;
		$d['生保ID'] = $seiho_id;
		$d['年間保険料'] = round($c['B30'][$i]);

		$d = &$data['配偶者']["保険$n"]['保障範囲別'];
		$d['死亡'] = round($c['D31'][$i]);
		$d['医療'] = round($c['F31'][$i]);
		$d['就業不能']['給付形態'] = $g_sheets['生保']['D224'][$i];
		$d['就業不能']['給付'] = ($g_sheets['生保']['D224'][$i] == $g_encode['給付形態']['定額']) ? round($g_sheets['生保']['D225'][$i]) : round($g_sheets['生保']['D226'][$i] * 100, 1);
		$d['老後']['年額'] = round($c['J30'][$i]);
		$d['老後']['満期金'] = round($c['J31'][$i]);
		$d['損害'] = '−';
	}

	// 配偶者・損保系
	for ($i = 1; $i <= count($c['A40']); $i++) {
		$n++;
		$sonpo_id = $g_sheets['損保']['C3'][$i];

		$d = &$data['配偶者']["保険$n"];
		$d['保険名称'] = $c['A40'][$i];
		$d['保険会社'] = $c['A41'][$i];
		$d['保険種別'] = $c['A42'][$i];
		if (isset($sonpo_data[$sonpo_id]->recom_update_flag))
			$d['変更種別'] = $sonpo_data[$sonpo_id]->recom_update_flag;
		$d['損保ID'] = $sonpo_id;
		$d['年間保険料'] = '−';

		$d = &$data['配偶者']["保険$n"]['保障範囲別'];
		$d['死亡'] = '−';
		$d['医療'] = '−';
		$d['就業不能']['給付形態'] = '−';
		$d['就業不能']['給付'] = '−';
		$d['老後']['年額'] = round($c['J40'][$i]);
		$d['老後']['満期金'] = round($c['J41'][$i]);
		$d['損害'] = $c['L40'][$i];
	}

	return $data;
}

// ■死亡保険評価データ取り出し処理
function _get_hyoka_data_sb($seiho_data) {
	global $g_sheets;

	$c = &$g_sheets['証券診断'];

	// 本人
	$n = 0;
	$data['本人']['保険個数'] = 0;
	for ($i = 1; $i <= count($c['AG6']); $i++) {
		// 契約期間種別があるものだけを有効とする
		if ($g_sheets['死亡保険']['D14'][$i] != '' || $g_sheets['死亡保険']['E14'][$i] != '') {
			$n++;
			$seiho_id = $g_sheets['生保']['D6'][$i];

			$d = &$data['本人']["保険$n"];
			$d['保険名称'] = $c['AG6'][$i];
			$d['保険会社'] = $c['AG7'][$i];
			$d['保険種別'] = $c['AG8'][$i];
			if (isset($seiho_data[$seiho_id]['死亡']->recom_update_flag)) {
				$d['変更種別']['主契約'] = $seiho_data[$seiho_id]['死亡']->recom_update_flag;
				$d['変更種別']['特約'] = $seiho_data[$seiho_id]['特約']->death_recom_update_flag;
			}
			$d['生保ID'] = $seiho_id;
			$d['保険金額']['病気死亡']['主契約'] = round($c['AH6'][$i]);
			$d['保険金額']['病気死亡']['特約'] = round($c['AH7'][$i]);
			$d['保険金額']['災害死亡']['主契約'] = round($c['AJ6'][$i]);
			$d['保険金額']['災害死亡']['特約'] = round($c['AJ7'][$i]);
			$d['総合評価']['主契約'] = $c['AM6'][$i];
			$d['総合評価']['特約'] = $c['AM7'][$i];
			$d['継続性']['主契約'] = $c['AN6'][$i];
			$d['継続性']['特約'] = $c['AN7'][$i];
			$d['保険料安定度']['主契約'] = $c['AO6'][$i];
			$d['保険料安定度']['特約'] = $c['AO7'][$i];
			$d['保険料の割安さ']['主契約'] = $c['AP6'][$i];
			$d['保険料の割安さ']['特約'] = $c['AP7'][$i];
		}
	}
	$data['本人']['保険個数'] = $n;

	// 配偶者
	$n = 0;
	$data['配偶者']['保険個数'] = 0;
	for ($i = 1; $i <= count($c['AG30']); $i++) {
		// 契約期間種別があるものだけを有効とする
		if ($g_sheets['死亡保険']['D67'][$i] != '' || $g_sheets['死亡保険']['E67'][$i] != '') {
			$n++;
			$seiho_id = $g_sheets['生保']['D140'][$i];

			$d = &$data['配偶者']["保険$n"];
			$d['保険名称'] = $c['AG30'][$i];
			$d['保険会社'] = $c['AG31'][$i];
			$d['保険種別'] = $c['AG32'][$i];
			if (isset($seiho_data[$seiho_id]['死亡']->recom_update_flag)) {
				$d['変更種別']['主契約'] = $seiho_data[$seiho_id]['死亡']->recom_update_flag;
				$d['変更種別']['特約'] = $seiho_data[$seiho_id]['特約']->death_recom_update_flag;
			}
			$d['生保ID'] = $seiho_id;
			$d['保険金額']['病気死亡']['主契約'] = round($c['AH30'][$i]);
			$d['保険金額']['病気死亡']['特約'] = round($c['AH31'][$i]);
			$d['保険金額']['災害死亡']['主契約'] = round($c['AJ30'][$i]);
			$d['保険金額']['災害死亡']['特約'] = round($c['AJ31'][$i]);
			$d['総合評価']['主契約'] = $c['AM30'][$i];
			$d['総合評価']['特約'] = $c['AM31'][$i];
			$d['継続性']['主契約'] = $c['AN30'][$i];
			$d['継続性']['特約'] = $c['AN31'][$i];
			$d['保険料安定度']['主契約'] = $c['AO30'][$i];
			$d['保険料安定度']['特約'] = $c['AO31'][$i];
			$d['保険料の割安さ']['主契約'] = $c['AP30'][$i];
			$d['保険料の割安さ']['特約'] = $c['AP31'][$i];
		}
	}
	$data['配偶者']['保険個数'] = $n;

	return $data;
}

// ■医療保険評価データ取り出し処理
function _get_hyoka_data_ir($seiho_data) {
	global $g_sheets;

	$c = &$g_sheets['証券診断'];

	// 本人
	$n = 0;
	$data['本人']['保険個数'] = 0;
	for ($i = 1; $i <= count($c['Z6']); $i++) {
		// 契約期間種別があるものだけを有効とする
		if ($g_sheets['医療保険']['D14'][$i] != '' || $g_sheets['医療保険']['E14'][$i] != '') {
			$n++;
			$seiho_id = $g_sheets['生保']['D6'][$i];

			$d = &$data['本人']["保険$n"];
			$d['保険名称'] = $c['Z6'][$i];
			$d['保険会社'] = $c['Z7'][$i];
			$d['保険種別'] = $c['Z8'][$i];
			if (isset($seiho_data[$seiho_id]['医療']->recom_update_flag)) {
				$d['変更種別']['主契約'] = $seiho_data[$seiho_id]['医療']->recom_update_flag;
				$d['変更種別']['特約'] = $seiho_data[$seiho_id]['特約']->medi_death_recom_update_flag;
			}
			$d['生保ID'] = $seiho_id;

			$d = &$data['本人']["保険$n"]['病気別の保障'];
			$d['一般医療']['主契約'] = round($c['N6'][$i]);
			$d['一般医療']['特約'] = round($c['N8'][$i]);
			$d['けが']['主契約'] = round($c['P6'][$i]);
			$d['けが']['特約'] = round($c['P8'][$i]);
			$d['脳卒中']['主契約']['日額'] = round($c['R6'][$i]);
			$d['脳卒中']['主契約']['診断時'] = round($c['R7'][$i]);
			$d['脳卒中']['特約']['日額'] = round($c['R8'][$i]);
			$d['脳卒中']['特約']['診断時'] = round($c['R9'][$i]);
			$d['がん']['主契約']['日額'] = round($c['T6'][$i]);
			$d['がん']['主契約']['診断時'] = round($c['T7'][$i]);
			$d['がん']['特約']['日額'] = round($c['T8'][$i]);
			$d['がん']['特約']['診断時'] = round($c['T9'][$i]);
			$d['心筋梗塞']['主契約']['日額'] = round($c['V6'][$i]);
			$d['心筋梗塞']['主契約']['診断時'] = round($c['V7'][$i]);
			$d['心筋梗塞']['特約']['日額'] = round($c['V8'][$i]);
			$d['心筋梗塞']['特約']['診断時'] = round($c['V9'][$i]);
			$d['糖尿病']['主契約']['日額'] = round($c['X6'][$i]);
			$d['糖尿病']['主契約']['診断時'] = round($c['X7'][$i]);
			$d['糖尿病']['特約']['日額'] = round($c['X8'][$i]);
			$d['糖尿病']['特約']['診断時'] = round($c['X9'][$i]);

			$d = &$data['本人']["保険$n"]['評価'];
			$d['総合評価']['主契約'] = $c['AB6'][$i];
			$d['総合評価']['特約'] = $c['AB7'][$i];
			$d['継続性']['主契約'] = $c['AC6'][$i];
			$d['継続性']['特約'] = $c['AC7'][$i];
			$d['保険料安定度']['主契約'] = $c['AD6'][$i];
			$d['保険料安定度']['特約'] = $c['AD7'][$i];
			$d['保障開始条件']['主契約'] = $c['AE6'][$i];
			$d['保障開始条件']['特約'] = $c['AE7'][$i];
			$d['給付の長さ']['主契約'] = $c['AF6'][$i];
			$d['給付の長さ']['特約'] = $c['AF7'][$i];
		}
	}
	$data['本人']['保険個数'] = $n;

	// 配偶者
	$n = 0;
	$data['配偶者']['保険個数'] = 0;
	for ($i = 1; $i <= count($c['Z30']); $i++) {
		// 契約期間種別があるものだけを有効とする
		if ($g_sheets['医療保険']['D61'][$i] != '' || $g_sheets['医療保険']['E61'][$i] != '') {
			$n++;
			$seiho_id = $g_sheets['生保']['D140'][$i];

			$d = &$data['配偶者']["保険$n"];
			$d['保険名称'] = $c['Z30'][$i];
			$d['保険会社'] = $c['Z31'][$i];
			$d['保険種別'] = $c['Z32'][$i];
			if (isset($seiho_data[$seiho_id]['医療']->recom_update_flag)) {
				$d['変更種別']['主契約'] = $seiho_data[$seiho_id]['医療']->recom_update_flag;
				$d['変更種別']['特約'] = $seiho_data[$seiho_id]['特約']->medi_death_recom_update_flag;
			}
			$d['生保ID'] = $seiho_id;

			$d = &$data['配偶者']["保険$n"]['病気別の保障'];
			$d['一般医療']['主契約'] = round($c['N30'][$i]);
			$d['一般医療']['特約'] = round($c['N32'][$i]);
			$d['けが']['主契約'] = round($c['P30'][$i]);
			$d['けが']['特約'] = round($c['P32'][$i]);
			$d['脳卒中']['主契約']['日額'] = round($c['R30'][$i]);
			$d['脳卒中']['主契約']['診断時'] = round($c['R31'][$i]);
			$d['脳卒中']['特約']['日額'] = round($c['R32'][$i]);
			$d['脳卒中']['特約']['診断時'] = round($c['R33'][$i]);
			$d['がん']['主契約']['日額'] = round($c['T30'][$i]);
			$d['がん']['主契約']['診断時'] = round($c['T31'][$i]);
			$d['がん']['特約']['日額'] = round($c['T32'][$i]);
			$d['がん']['特約']['診断時'] = round($c['T33'][$i]);
			$d['心筋梗塞']['主契約']['日額'] = round($c['V30'][$i]);
			$d['心筋梗塞']['主契約']['診断時'] = round($c['V31'][$i]);
			$d['心筋梗塞']['特約']['日額'] = round($c['V32'][$i]);
			$d['心筋梗塞']['特約']['診断時'] = round($c['V33'][$i]);
			$d['糖尿病']['主契約']['日額'] = round($c['X30'][$i]);
			$d['糖尿病']['主契約']['診断時'] = round($c['X31'][$i]);
			$d['糖尿病']['特約']['日額'] = round($c['X32'][$i]);
			$d['糖尿病']['特約']['診断時'] = round($c['X33'][$i]);

			$d = &$data['配偶者']["保険$n"]['評価'];
			$d['総合評価']['主契約'] = $c['AB30'][$i];
			$d['総合評価']['特約'] = $c['AB31'][$i];
			$d['継続性']['主契約'] = $c['AC30'][$i];
			$d['継続性']['特約'] = $c['AC31'][$i];
			$d['保険料安定度']['主契約'] = $c['AD30'][$i];
			$d['保険料安定度']['特約'] = $c['AD31'][$i];
			$d['保障開始条件']['主契約'] = $c['AE30'][$i];
			$d['保障開始条件']['特約'] = $c['AE31'][$i];
			$d['給付の長さ']['主契約'] = $c['AF30'][$i];
			$d['給付の長さ']['特約'] = $c['AF31'][$i];
		}
	}
	$data['配偶者']['保険個数'] = $n;

	return $data;
}

// ■損害保険評価データ取り出し処理
function _get_hyoka_data_sn($sonpo_data) {
	global $g_sheets;

	$c = &$g_sheets['証券診断'];

	$n = 0;
	$d = &$data;
	$d['保険個数'] = count($c['AQ6']);
	for ($i = 1; $i <= count($c['AQ6']); $i++) {
		$n++;
		$sonpo_id = $g_sheets['損保']['C3'][$i];

		$d = &$data["保険$n"];
		$d['保険名称'] = $c['AQ6'][$i];
		$d['保険会社'] = $c['AQ7'][$i];
		$d['保険種別'] = $c['AQ8'][$i];
		if (isset($sonpo_data[$sonpo_id]->recom_update_flag))
			$d['変更種別'] = $sonpo_data[$sonpo_id]->recom_update_flag;
		$d['損保ID'] = $sonpo_id;

		$d['火災保険']['建物'] = round($c['AR6'][$i]);
		$d['火災保険']['家財'] = round($c['AR7'][$i]);
		$d['地震保険']['建物'] = round($c['AT6'][$i]);
		$d['地震保険']['家財'] = round($c['AT7'][$i]);
		$d['自動車保険']['対人'] = round($c['AV6'][$i]);
		$d['自動車保険']['対物'] = round($c['AV7'][$i]);
		$d['個人賠償責任保険'] = round($c['AX6'][$i]);
	}

	return $data;
}
?>