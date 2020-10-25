<?
/******************************************************
* system  : xxx リスク診断
* title   : 基本計算処理
* content : リスク診断の基本計算を行う
* version : 1.0
* author  : aizawa
*******************************************************/

// ■基本計算処理（第１段階）
function calc_basic_info1($basic_info) {
	global $g_encode, $g_const, $g_sheets;

	$n = '基本';
	$g_sheets[$n] = array();
	$s = &$g_sheets;
	$c = &$g_sheets[$n];

	// あなた・本人
	$t = &$basic_info['あなた']['本人'];
	$c['D6'] = $t['性別'];					// 性別
	$c['F7'] = $t['結婚'];					// 未既婚
	$c['D7'] = $t['生年月日'];			// 生年月日
	$c['D9'] = $t['年収'];					// 年収
	$c['D11'] = $t['勤続年数'];			// 勤続年数（今の会社）
	$c['M45'] = $t['喫煙'];					// 喫煙 [1:吸わない　2:吸う]
	$c['M46'] = $t['健康'];					// 健康 [1:不安がある　2:不安はない]
	$c['M48'] = $t['運転'];					// 運転 [1:自分の車を運転する　2:自分以外の車を運転する　3:運転しない]
	$c['K3'] = $t['子供'];					// 子供人数
	$children_num = $c['K3'];

	// あなた・配偶者
	$t = &$basic_info['あなた']['配偶者'];
	$c['E6'] = $c['D6'] == $g_encode['性別']['男'] ? $g_encode['性別']['女'] : $g_encode['性別']['男'];					// 性別
	$c['E7'] = $t['生年月日'];			// 生年月日
	$c['E9'] = $t['年収'];					// 年収
	$c['N45'] = $t['喫煙'];					// 喫煙 [1:吸わない　2:吸う]
	$c['N46'] = $t['健康'];					// 健康 [1:不安がある　2:不安はない]
	$c['N48'] = $t['運転'];					// 運転 [1:自分の車を運転する　2:自分以外の車を運転する　3:運転しない]

	// 貯蓄
	$t = &$basic_info['あなた']['貯蓄'];
	$c['C47'] = $t['年間'];					// 年間貯蓄額
	$c['C49'] = $t['現在'];					// 現在の貯蓄額
	$c['C50'] = $t['想定'];					// 60歳時予想額貯蓄

	// 住宅
	$t = &$basic_info['住宅'];
	$c['C28'] = $t['住まい'];				// 住宅 [1:持ち家　2:賃貸]
	$c['C29'] = $t['住まい'] == $g_encode['住宅']['賃貸'] ? $t['賃貸']['形態'] : $t['持家']['形態'];					// 形態 [1:共同住宅（鉄筋）　2:共同住宅（木造）　3:戸建(鉄筋)　4:戸建(木造)]
	$c['C31'] = $t['賃貸']['家賃'];					// 家賃
	$c['C34'] = $t['持家']['購入価格'];			// 購入価格
	$c['C35'] = $t['持家']['年間維持費'];		// 年間維持費
	$c['C40'] = $t['持家']['建物面積'];			// 建物面積

	// 住宅ローン
	$t = &$basic_info['住宅']['ローン'];
	$c['J27'] = $t['住宅ローン数'];
	$housing_loan_num = $c['J27'];
	for ($i = 1; $i <= $housing_loan_num; $i++) {
		$k = "住宅$i";
		$c['F29'][$i] = $t[$k]['年間返済額'];		// 年間返済額
		$c['F42'][$i] = $t[$k]['返済終了年度'];	// 返済終了年度
		$c['F31'][$i] = $t[$k]['金利'] / 100;		// 金利
		$c['F32'][$i] = $t[$k]['名義'];					// ローン名義 [1:自分　2:配偶者　3:夫婦共有　4:その他親族]
		$c['F33'][$i] = $t[$k]['団信保険'];			// 団信加入 [1:加入　2:未加入]
	}

	// その他ローン
	$t = &$basic_info['住宅']['ローン'];
	$c['N27'] = $t['その他ローン数'];
	$other_loan_num = $c['N27'];
	for ($i = 1; $i <= $other_loan_num; $i++) {
		$k = "その他$i";
		$c['K29'][$i] = $t[$k]['年間返済額'];		// 年間返済額
		$c['K42'][$i] = $t[$k]['返済終了年度'];	// 他ローン・返済終了年度・返済終了年度
		$c['K31'][$i] = $t[$k]['金利'] / 100;		// 金利
		$c['K32'][$i] = $t[$k]['名義'];					// ローン名義 [1:自分　2:配偶者]
	}

	// 教育
	$t = &$basic_info['教育'];
	$j = 1;
	for ($i = 1; $i <= $c['K3']; $i++) {
		$k = "子供$i";
		if ($basic_info['子供'][$k]['扶養'] == 0) {
			$c['I7'][$j] = $basic_info['子供'][$k]['生年月日'];		// 生年月日
			$c['I9'][$j] = $t[$k]['幼稚園'];	// 教育計画・幼稚園 [1:公立　2:私立]
			$c['I10'][$j] = $t[$k]['小学'];		// 教育計画・小学校 [1:公立　2:私立]
			$c['I11'][$j] = $t[$k]['中学'];		// 教育計画・中学校 [1:公立　2:私立]
			$c['I12'][$j] = $t[$k]['高校'];		// 教育計画・高校 [1:公立　2:私立]
			$c['I13'][$j] = $t[$k]['大学'];		// 教育計画・大学 [1:公立　2:私立]
			$c['I15'][$j] = $t[$k]['仕送り'];	// 教育計画・仕送り（下宿）
			$j++;
		}
	}
	$children_num = $j - 1;

	// 老後・本人
	$t = &$basic_info['老後']['本人'];
	$c['D12'] = $t['退職予定年齢'];			// 退職予定年齢
	$c['D10'] = $t['想定退職金'];				// 想定退職金
	$c['D27'] = $t['退職時想定年収'];		// 60歳（最高）時想定年収
	$c['D13'] = $t['退職後年間収入'];		// 60歳以降の就労手取り収入（年間）
	$c['I47'] = $t['老後生活費'];				// 老後生活費

	// 老後・配偶者
	$t = &$basic_info['老後']['配偶者'];
	$c['E12'] = $t['退職予定年齢'];			// 退職予定年齢
	$c['E10'] = $t['想定退職金'];				// 想定退職金
	$c['E27'] = $t['退職時想定年収'];		// 60歳（最高）時想定年収
	$c['E13'] = $t['退職後年間収入'];		// 60歳以降の就労手取り収入（年間）

	// 万一・本人
	$t = &$basic_info['万一']['本人'];
	$c['D2'] = $t['死亡退職金'];								// 死亡退職金
	$c['C56'] = $t['死亡時賃貸住宅方針'];				// 死亡時・賃貸住宅方針 [1:現状維持　2:家賃減額　3:実家　4:その他家賃不要]
	$c['C58'] = $t['葬儀費用'];									// 死亡時・葬儀費用
	$c['C60'] = $t['死亡時生活費'];							// 死亡時・万一の時の生活費
	$c['C66'] = $t['入院スタイル'];							// 入院時・入院時ベット代 [1:大部屋　2:個室　3:個室(電化製品付)]
	$c['F56'] = $t['就業不能時賃貸住宅方針'];		// 長期就業不能時・賃貸住宅方針 [1:現状維持　2:家賃減額　3:実家　4:その他家賃不要]
	$c['F60'] = $t['就業不能時生活費'];					// 長期就業不能時・万一の時の生活費
	$c['F67'] = $t['家事自立年齢'];							// 家事自立年齢
	$c['F64'] = $t['家事代替費用'];							// 家事労働代替費用
	$c['F65'] = $t['家事代替費用金額'];					// 自分で入力
	$c['I64'] = $t['保育園費'];									// ベビーシッター・保育園等費用 [1:金額を入力　2:親族が世話をする　3:保育費が必要　4:これまでどおり]

	// 万一・配偶者
	$t = &$basic_info['万一']['配偶者'];
	$c['E2'] = $t['死亡退職金'];								// 死亡退職金
	$c['D56'] = $t['死亡時賃貸住宅方針'];				// 死亡時・賃貸住宅方針 [1:現状維持　2:家賃減額　3:実家　4:その他家賃不要]
	$c['D58'] = $t['葬儀費用'];									// 死亡時・葬儀費用
	$c['D60'] = $t['死亡時生活費'];							// 死亡時・万一の時の生活費
	$c['D66'] = $t['入院スタイル'];							// 入院時・入院時ベット代 [1:大部屋　2:個室　3:個室(電化製品付)]
	$c['G56'] = $t['就業不能時賃貸住宅方針'];		// 長期就業不能時・賃貸住宅方針 [1:現状維持　2:家賃減額　3:実家　4:その他家賃不要]
	$c['G60'] = $t['就業不能時生活費'];					// 長期就業不能時・万一の時の生活費
	$c['G67'] = $t['家事自立年齢'];							// 家事自立年齢
	$c['G64'] = $t['家事代替費用'];							// 家事労働代替費用
	$c['G65'] = $t['家事代替費用金額'];					// 自分で入力
	$c['J64'] = $t['保育園費'];									// ベビーシッター・保育園等費用 [1:金額を入力　2:親族が世話をする　3:保育費が必要　4:これまでどおり]

	// 以下定数
	$c['O8'] = 0;
	$c['O9'] = $g_const['年間教育費']['幼稚園']['入学年齢'];
	$c['O10'] = $g_const['年間教育費']['小学校']['入学年齢'];
	$c['O11'] = $g_const['年間教育費']['中学校']['入学年齢'];
	$c['O12'] = $g_const['年間教育費']['高校']['入学年齢'];
	$c['O13'] = $g_const['年間教育費']['大学前2年']['入学年齢'];
	$c['O14'] = $g_const['年間教育費']['大学後2年']['入学年齢'];
	$c['O15'] = 22;

	// 仕送り
	$c['M16'] = $g_const['仕送り'][1];

	// 課税所得
	$r = 105;
	foreach ($g_const['課税所得'] as $key => $val) {
		$c["B$r"] = $key;
		$c["C$r"] = $val['税率'];
		$c["D$r"] = $val['控除額'];
		$r++;
	}

	// 給与収入
	$r = 105;
	foreach ($g_const['給与収入'] as $key => $val) {
		$c["G$r"] = $key;
		$c["H$r"] = $val['掛率'];
		$c["I$r"] = $val['金額'];
		$r++;
	}

	// 賃貸方針
	$r = 54;
	foreach ($g_const['賃貸方針'] as $key => $val) {
		$c["I$r"] = $key;
		$c["J$r"] = $val;
		$r++;
	}

	// ベビーシッター・保育園等費用
	$r = 87;
	foreach ($g_const['保育園費用'] as $key => $val) {
		$c["C$r"] = $key;
		$c["D$r"] = $val;
		$c["E$r"] = $val;
		$r++;
	}

	// 葬儀費用
	$r = 123;
	foreach ($g_const['葬儀費用'] as $key => $val) {
		$c["F$r"] = $key;
		$c["H$r"] = $val;
		$r++;
	}

	// 社会保険料掛け率
	$c['D99'] = $g_const['社会保険料掛け率'];
	$c['I99'] = $g_const['社会保険料掛け率'];

	// 初任給
	$c['O23'] = $g_const['初任給'];

	// 就職年齢
	$c['O24'] = $g_const['就職年齢'];

	// 公的年金
	$c['D73'] = $g_const['遺族国民年金支給額']['遺族厚生年金掛け率'];
	$c['D75'] = $g_const['遺族国民年金支給額']['老齢国民年金掛け率'];
	$c['D76'] = $g_const['遺族国民年金支給額']['老齢厚生年金掛け率'];
	$c['D77'] = $g_const['遺族国民年金支給額']['加給年金基本金額'];

	// 社会保険期間
	$c['FL3'] = 18;
	$c['FL8'] = 18;

	// 住宅形態
	$r = 80;
	foreach ($g_const['住宅形態'] as $key => $val) {
		$c["N$r"] = $key;
		$c["O$r"] = $val;
		$r++;
	}

	// 子供生活費割合
	$c['C62'] = $g_const['子供生活費割合'];

	// 以下、計算処理
	$c['D8'] = VB_IF(VB_OR(VB_MONTH($c['D7']) < VB_MONTH(VB_TODAY()), VB_AND(VB_MONTH($c['D7']) == VB_MONTH(VB_TODAY()), VB_DAY($c['D7']) <= VB_DAY(VB_TODAY()))), 1, 0) + VB_YEAR(VB_TODAY()) - VB_YEAR($c['D7']) - 1;
	$c['D14'] = (($c['O23'] + MIN($g_const['標準報酬上限額'] * 12, $c['D9'])) / 2) / 12;
	$c['D16'] = VB_IF($c['D8'] < $c['O24'], 1, $c['D8'] - $c['O24']);
	$c['D17'] = MAX(0, $c['D12'] - $c['D8'] + $c['D16']);
	$c['D15'] =(($c['O23'] + MIN($g_const['標準報酬上限額'] * 12, $c['D9'])) * $c['D16'] / 2 + (MIN($g_const['標準報酬上限額'] * 12, $c['D9']) + MIN($g_const['標準報酬上限額'] * 12, $c['D27'])) * (MIN($g_const['定年'], $c['D12']) - $c['D8']) / 2 + MAX(0, ($c['D12'] - MIN($g_const['定年'], $c['D12']))) * MIN($g_const['標準報酬上限額'] * 12, $c['D13'])) / $c['D17'] / 12;
	$c['D19'] = $g_const['年金開始'] - MIN(5, MAX(0, (int)((1962 + VB_IF($c['D6'] == $g_encode['性別']['女'], 5, 0) - VB_YEAR(add_date($c['D7'], -1)) + VB_IF(VB_MONTH(add_date($c['D7'], -1)) < 4, 1, 0)) / 2)));
	$c['D20'] = $g_const['年金開始'] - MIN(5, MAX(0, (int)((1950 + VB_IF($c['D6'] == $g_encode['性別']['女'], 5, 0) - VB_YEAR(add_date($c['D7'], -1)) + VB_IF(VB_MONTH(add_date($c['D7'], -1)) < 4, 1, 0)) / 2)));
	$c['D21'] = VB_IF($c['D8'] != "", MAX(0, $g_const['定年'] - $c['D8']), 0);
	$c['D22'] = VB_IF($c['D8'] != "", MAX(0, $g_const['年金開始'] - $c['D8']), 0);
	$c['D23'] = VB_IF($c['D8'] != "", MAX(0, $g_const['寿命'] - $c['D8']), 0);
	$c['D24'] = $c['D21'] * 12;
	$c['D25'] = $c['D22'] * 12;
	$c['D26'] = $c['D23'] * 12;
	$c['E8'] = VB_IF($c['E7'] != "", VB_IF(VB_OR(VB_MONTH($c['E7']) < VB_MONTH(VB_TODAY()), VB_AND(VB_MONTH($c['E7']) == VB_MONTH(VB_TODAY()),VB_DAY($c['E7']) <= VB_DAY(VB_TODAY()))), 1, 0) + VB_YEAR(VB_TODAY()) - VB_YEAR($c['E7']) - 1, "");
	$c['E14'] = VB_IF($c['E9'] >= $g_const['年金加入年収'], (($c['O23'] + MIN($g_const['標準報酬上限額'] * 12, $c['E9'])) / 2) / 12,0);
	$c['E16'] = VB_IF($c['E8'] < $c['O24'], 1, VB_IF($c['E8'] == "", 0, VB_IF($c['E9'] >= $g_const['年金加入年収'],$c['E8'] - $c['O24'], 0)));
$c['E17'] = VB_IF($c['E7'] != "", MAX(0, $c['E12'] - $c['E8'] + $c['E16']), 0);
	$c['E15'] = VB_IF($c['E17'] > 0,(($c['O23'] + MIN($g_const['標準報酬上限額'] * 12, $c['E9'])) * $c['E16'] / 2 + (MIN($g_const['標準報酬上限額'] * 12, $c['E9']) + MIN($g_const['標準報酬上限額'] * 12, $c['E27'])) * (MIN($g_const['定年'], $c['E12']) - $c['E8']) / 2 + MAX(0, ($c['E12'] - MIN($g_const['定年'], $c['E12']))) * MIN($g_const['標準報酬上限額'] * 12, $c['E13'])) / $c['E17'] / 12,0);
	$c['E19'] = VB_IF($c['E8'] != "", $g_const['年金開始'] - MIN(5, MAX(0, (int)((1962 + VB_IF($c['E6'] == $g_encode['性別']['女'], 5, 0) - VB_YEAR(add_date($c['E7'], -1)) + VB_IF(VB_MONTH(add_date($c['E7'], -1)) < 4, 1, 0)) / 2))), "");
	$c['E20'] = VB_IF($c['E8'] != "", $g_const['年金開始'] - MIN(5, MAX(0, (int)((1950 + VB_IF($c['E6'] == $g_encode['性別']['女'], 5, 0) - VB_YEAR(add_date($c['E7'], -1)) + VB_IF(VB_MONTH(add_date($c['E7'], -1)) < 4, 1, 0)) / 2))), "");
	$c['E21'] = VB_IF($c['E8'] != "", MAX(0, $g_const['定年'] - $c['E8']), 0);
	$c['E22'] = VB_IF($c['E8'] != "", MAX(0, $g_const['年金開始'] - $c['E8']), 0);
	$c['E23'] = VB_IF($c['E8'] != "", MAX(0, $g_const['寿命'] - $c['E8']), 0);
	$c['E24'] = $c['E21'] * 12;
	$c['E25'] = $c['E22'] * 12;
	$c['E26'] = $c['E23'] * 12;

	for ($i = 1; $i <= $children_num; $i++) {
		$c['I8'][$i] = VB_IF($c['I7'][$i] == "", "", VB_IF(VB_OR(VB_MONTH($c['I7'][$i]) < VB_MONTH(VB_TODAY()), VB_AND(VB_MONTH($c['I7'][$i]) == VB_MONTH(VB_TODAY()), VB_DAY($c['I7'][$i]) <= VB_DAY(VB_TODAY()))), 1, 0) + VB_YEAR(VB_TODAY()) - VB_YEAR($c['I7'][$i])-1);
		$c['I14'][$i] = _get_daigaku_nensu($basic_info['教育']["子供$i"]['大学年数']);
		$c['P9'][$i] = _get_kyoikuhi('幼稚園', $c['I9'][$i]);
		$c['P10'][$i] = _get_kyoikuhi('小学校', $c['I10'][$i]);
		$c['P11'][$i] = _get_kyoikuhi('中学校', $c['I11'][$i]);
		$c['P12'][$i] = _get_kyoikuhi('高校', $c['I12'][$i]);
		$c['P16'][$i] = VB_IF($c['I13'][$i] == $g_encode['学校']['無'], 0, VB_IF($c['I15'][$i] == $g_encode['仕送り']['有'], $c['M16'], 0));
		$c['P13'][$i] = _get_kyoikuhi('大学前2年', $c['I13'][$i]) + $c['P16'][$i];
		$c['P14'][$i] = VB_IF($c['I14'][$i] == 4, $c['P13'][$i] , 0);
		$c['P15'][$i] = 0;
		$c['P17'][$i] = VB_IF($c['I8'][$i] != "", VB_VLOOKUP($n, $c['I8'][$i], 'O8:S15', $i + 1, TRUE), 0);
		$c['I22'][$i] = VB_IF($c['I8'][$i] != "", 18 - $c['I8'][$i] + VB_IF($c['I13'][$i] != 3, $c['I14'][$i], 0), "");
		$c['I23'][$i] = VB_IF($c['I7'][$i] == "", "", 15 - $c['I8'][$i]);
	}

	for ($i = 1; $i <= $housing_loan_num; $i++) {
		$c['F30'][$i] = VB_IF($c['F42'][$i] >= VB_YEAR(VB_TODAY()), $c['F42'][$i] - VB_YEAR(VB_TODAY()), 0);
		$c['F34'][$i] = $c['F29'][$i] * $c['F30'][$i];
		$c['F35'][$i] = -VB_PV($c['F31'][$i], $c['F30'][$i] ,$c['F29'][$i] , 0, 1);
//#		$c['F36'][$i] = MAX(0, $c['F30'][$i] - ($g_const['定年'] - $c['D8']));
//#		$c['F37'][$i] = MIN(5 ,$c['F36'][$i]) * $c['F29'][$i];
//#		$c['F38'][$i] = MAX(0, $c['F36'][$i] - 5) * $c['F29'][$i];

		$c['F40'][$i] = VB_IF($c['F33'][$i] == $g_encode['団信加入']['無'], 0, VB_IF(VB_AND($c['F33'][$i] == $g_encode['団信加入']['有'], $c['F32'][$i] == $g_encode['ローン名義']['本人']), $c['F35'][$i], VB_IF($c['F32'][$i] == $g_encode['ローン名義']['共有'], $c['F35'][$i] / 2, 0)));
		$c['F41'][$i] = VB_IF($c['F33'][$i] == $g_encode['団信加入']['無'], 0, VB_IF(VB_AND($c['F33'][$i] == $g_encode['団信加入']['有'], $c['F32'][$i] == $g_encode['ローン名義']['配偶者']), $c['F35'][$i] ,VB_IF($c['F32'][$i] == $g_encode['ローン名義']['共有'], $c['F35'][$i] / 2, 0)));
	}

	$c['J29'] = VB_SUM($n, 'F29');
	$c['J34'] = VB_SUM($n, 'F34');
	$c['J35'] = VB_SUM($n, 'F35');
//#	$c['J36'] = VB_SUM($n, 'F36');
//#	$c['J37'] = VB_SUM($n, 'F37');
//#	$c['J38'] = VB_SUM($n, 'F38');
	$c['J40'] = VB_SUM($n, 'F40');
	$c['J41'] = VB_SUM($n, 'F41');

	$c['C38'] = VB_IF($c['C28'] == $g_encode['住宅']['賃貸'], $c['C31'], ($c['J29'] + $c['C35']) / 12);

	for ($i = 1; $i <= $other_loan_num; $i++) {
		$c['K30'][$i] = VB_IF($c['K42'][$i] >= VB_YEAR(VB_TODAY()), $c['K42'][$i] - VB_YEAR(VB_TODAY()), 0);
		$c['K34'][$i] = $c['K29'][$i] * $c['K30'][$i];
		$c['K35'][$i] = -VB_PV($c['K31'][$i], $c['K30'][$i], $c['K29'][$i], 0, 1);
//#		$c['K36'][$i] = MAX(0, $c['K30'][$i] - ($g_const['定年'] - $c['D8']));
//#		$c['K37'][$i] = MIN(5, $c['K36'][$i]) * $c['K29'][$i];
//#		$c['K38'][$i] = MAX(0, $c['K36'][$i] - 5) * $c['K29'][$i];
	}
			
	$c['N29'] = VB_SUM($n, 'K29');
	$c['N34'] = VB_SUM($n, 'K34');
	$c['N35'] = VB_SUM($n, 'K35');
//#	$c['N36'] = VB_SUM($n, 'K36');
//#	$c['N37'] = VB_SUM($n, 'K37');
//#	$c['N38'] = VB_SUM($n, 'K38');
//#	$c['N40'] = 0;
//#	$c['N41'] = 0;

	$c['C51'] = $c['C49'] + $c['C47'] * $c['D21'];
	$c['C96'] = $c['D9'];
	$c['C97'] = VB_IF($c['C96'] > 0, $c['C96'] * VB_VLOOKUP($n, $c['C96'], 'G105:I110', 2, TRUE) + VB_VLOOKUP($n, $c['C96'], 'G105:I110', 3, TRUE), 0);
	$c['D98'] = VB_COUNTIF($c['I23'], ">0") + VB_IF(VB_AND($c['E9'] < 103, $c['E8'] != ""), 1, 0) + 1;
	$c['C98'] = $c['D98'] * $g_const['所得税控除額掛け率'] + $c['C97'];
	$c['C99'] = MIN(VB_IF($c['C96'] > $g_const['年金加入年収'], $c['C96'] * $c['D99'], 0 ), $g_const['社会保険料上限額']);
	$c['C100'] = $c['C96'] - $c['C98'] - $c['C99'];
	$c['C101'] = VB_IF($c['C100'] > 0, $c['C100'] * VB_VLOOKUP($n, $c['C100'], 'B105:D110', 2, TRUE) - VB_VLOOKUP($n, $c['C100'], 'B105:D110', 3, TRUE), 0);
	$c['C102'] = $c['C99'] + $c['C101'];

	$c['H96'] = $c['E9'];
	$c['H97'] = VB_IF($c['H96'] > 0, $c['H96'] * VB_VLOOKUP($n, $c['H96'], 'G105:I110', 2, TRUE) + VB_VLOOKUP($n, $c['H96'], 'G105:I110', 3, TRUE), 0);
	$c['I98'] = 1;
	$c['H98'] = $c['I98'] * $g_const['所得税控除額掛け率'] + $c['H97'];
	$c['H99'] = MIN(VB_IF($c['H96'] > $g_const['年金加入年収'], $c['H96'] * $c['I99'], 0), $g_const['社会保険料上限額']);
	$c['H100'] = MAX(0, $c['H96'] - $c['H98'] - $c['H99']);
	$c['H101'] = VB_IF($c['H100'] > 0, $c['H100'] * VB_VLOOKUP($n, $c['H100'], 'B105:D110', 2, TRUE) - VB_VLOOKUP($n, $c['H100'], 'B105:D110', 3, TRUE), 0);
	$c['H102'] = $c['H99'] + $c['H101'];

	$c['F45'] = $c['D9'] + $c['E9'];
	$c['F46'] = $c['C102'] + $c['H102'];
	$c['F47'] = $c['F45'] - $c['F46'];
	$c['F48'] = $c['C38'] * 12;
	$c['F49'] = VB_SUM($n, 'P17');
	$c['F50'] = $c['N29'];
	$c['F51'] = $c['F47'] - VB_SUM($n, 'F48:F50') - $c['C47'];
			
//#	$c['I45'] = 平均;
//#	$c['I46'] = VB_IF($c['E8'] == "", VLOOKUP(I45,G81:I84,3,FALSE), VLOOKUP(I45,G81:I84,2,FALSE));
	$c['C48'] = $c['F51'] / 12;
	$c['C57'] = VB_VLOOKUP($n, $c['C56'], 'I54:J57', 2, FALSE);
	$c['C59'] = VB_VLOOKUP($n, $c['C58'], 'F123:H125', 3, FALSE);
	$c['C61'] = $c['C60'] * (1 - $c['K3'] * $c['C62']);


	$c['D57'] = VB_VLOOKUP($n, $c['D56'], 'I54:J57', 2, FALSE);
	$c['D59'] = VB_VLOOKUP($n, $c['D58'], 'F123:H125', 3, FALSE);
	$c['D61'] = $c['D60'] * (1 - $c['K3'] * $c['C62']);
	$c['F57'] = VB_VLOOKUP($n, $c['F56'], 'I54:J57', 2, FALSE);
	$c['F61'] = $c['F60'] * (1 - $c['K3'] * $c['C62']);
	$c['G57'] = VB_VLOOKUP($n, $c['G56'], 'I54:J57', 2, FALSE);
	$c['G61'] = $c['G60'] * (1 - $c['K3'] * $c['C62']);

	$c['F66'] = $c['F65'];

	$c['G66'] = $c['G65'];
	$c['I66'] = VB_VLOOKUP($n, $c['I64'], 'C87:D89', 2, FALSE);

	$c['J66'] = VB_VLOOKUP($n, $c['J64'], 'C87:E89', 3, FALSE);

	$c['E72'] = VB_IF($c['D8'] != "", 79.7, 0);
	$c['E73'] = $c['E14'] * $c['D73'] / 1000 * MAX(300, $c['E16'] * 12) * 3 / 4;
	$c['E74'] = 59.8;
	$c['E75'] = ($c['D75'] * ($g_const['定年'] - $c['D8'] + $c['D16'])) / 40;
	$c['E76'] = VB_IF($c['D8'] != "", $c['D15'] * $c['D76'] / 1000 * (($c['D16'] + MAX(0, $c['D12'] - $c['D8'])) * 12), 0);

	$c['F72'] = VB_IF($c['E8'] != "", 79.7, 0);
	$c['F73'] = $c['D14'] * $c['D73'] / 1000 * MAX(300, $c['D16'] * 12) * 3 / 4;
	$c['F74'] = 59.8;
	$c['F75'] = $c['E75'];
	$c['F76'] = VB_IF($c['E8'] != "", $c['E15'] * $c['D76'] / 1000 * (($c['E16'] + MAX(0, $c['E12'] - $c['E8'])) * 12), 0);

	$c['G72'] = VB_IF($c['E8'] == "", $g_const['遺族国民年金支給額']['基本支給額'], $g_const['遺族国民年金支給額']['第一子']);
	$c['H72'] = $c['G72'] + $g_const['遺族国民年金支給額']['第二子'];
	$c['I72'] = $c['H72'] + $g_const['遺族国民年金支給額']['第三子以降'];
	$c['J72'] = $c['I72'] + $g_const['遺族国民年金支給額']['第三子以降'];
}

// ■基本計算処理（第２段階）
function calc_basic_info2() {
	global $g_encode, $g_const, $g_sheets;

	$n = '基本';
	$s = &$g_sheets;
	$c = &$g_sheets[$n];

	$children_num = $c['K3'];
	$housing_loan_num = $c['J27'];
	$other_loan_num = $c['N27'];

	$p['給付形態'] = &$s['会社']['D22'];

	// 年間データ
	for ($y = 0; $y <= 70; $y++) {
		$r = 11 + $y;

		$p['年'] = &$c["AT$r"];
		$p['本人年齢'] = &$c["AU$r"];
		$p['配偶者年齢'] = &$c["AV$r"];

		$c["AT$r"] = $y;
		$c["AU$r"] = VB_IF(VB_OR(VB_MONTH($c['D7']) < VB_MONTH(VB_TODAY()), VB_AND(VB_MONTH($c['D7']) == VB_MONTH(VB_TODAY()), VB_DAY($c['D7']) <= VB_DAY(VB_TODAY()))), 1, 0) + VB_YEAR(VB_TODAY()) - VB_YEAR($c['D7']) - 1 + $y;
		$c["AV$r"] = VB_IF($c['E7'] != "", VB_IF(VB_OR(VB_MONTH($c['E7']) < VB_MONTH(VB_TODAY()), VB_AND(VB_MONTH($c['E7']) == VB_MONTH(VB_TODAY()), VB_DAY($c['E7']) <= VB_DAY(VB_TODAY()))), 1, 0) + VB_YEAR(VB_TODAY()) - VB_YEAR($c['E7']) - 1 + $y, "");

		for ($i = 1; $i <= $children_num; $i++) {
			$c["AW$r"][$i] = VB_IF($c['I7'][$i] == "", "", VB_IF(VB_OR(VB_MONTH($c['I7'][$i]) < VB_MONTH(VB_TODAY()), VB_AND(VB_MONTH($c['I7'][$i]) == VB_MONTH(VB_TODAY()), VB_DAY($c['I7'][$i]) <= VB_DAY(VB_TODAY()))), 1, 0) + VB_YEAR(VB_TODAY()) - VB_YEAR($c['I7'][$i]) - 1 + $y);
		}

		$c["BA$r"] = VB_IF($c["AU$r"] >= $g_const['定年'], $c['I47'] * 12, 0);

		for ($i = 1; $i <= $children_num; $i++) {
			$c["BB$r"][$i] = VB_IF($c["AW$r"][$i] != "", VB_VLOOKUP($n, $c["AW$r"][$i], 'O8:R15', 1 + $i, TRUE), 0);
		}

		$c["BF$r"] = VB_IF($c['C28'] == $g_encode['住宅']['賃貸'], $c['C31'] * 12, $c['C35']);

		for ($i = 1; $i <= $housing_loan_num; $i++) {
			$c["BG$r"][$i] = VB_IF($p['年'] <= $c['F30'][$i], $c['F29'][$i], 0);
		}

		for ($i = 1; $i <= $other_loan_num; $i++) {
			$c["BK$r"][$i] = VB_IF($p['年'] <= $c['K30'][$i], $c['K29'][$i], 0);
		}

		$c["BN$r"] = VB_IF($p['本人年齢'] < $c['D12'], VB_IF($p['本人年齢'] >= $g_const['定年'], $c['D13'], $c['D9'] - $c['C102']), 0);
		$c["BO$r"] = VB_IF($p['配偶者年齢'] < $c['E12'], VB_IF($p['配偶者年齢'] >= $g_const['定年'], $c['E13'], $c['E9'] - $c['H102']), 0);
		$c["BP$r"] = VB_IF($p['本人年齢'] == $c['D12'], $c['D10'], 0);
		$c["BQ$r"] = VB_IF($p['本人年齢'] == $g_const['定年'], $c['C50'], 0) + VB_IF($p['配偶者年齢'] == $c['E12'], $c['E10'], 0);
		$c["BR$r"] = VB_IF($p['本人年齢'] >= $c['D20'], $c['E75'], 0);
		$c["BS$r"] = VB_IF($p['本人年齢'] >= $c['D19'], $c['E76'], 0);
		$c["BT$r"] = VB_IF(VB_AND($c['D6'] == $g_encode['性別']['男'], $p['本人年齢'] >= $c['D19'], $p['配偶者年齢'] < $c['E19'], $c['E9'] < $g_const['被扶養者年収上限']), $c['D77'], 0);
		$c["BU$r"] = VB_IF($p['本人年齢'] >= $s['会社']['D8'], $s['会社']['D9'], 0);
		$c["BV$r"] = VB_IF($c['E8'] == "", 0, VB_IF($p['配偶者年齢'] >= $c['E20'], $c['F75'], 0));
		$c["BW$r"] = VB_IF($c['E8'] == "", 0, VB_IF($p['配偶者年齢'] >= $c['E19'], $c['F76'], 0));
		$c["BX$r"] = VB_IF($c['E8'] == "", 0, VB_IF(VB_AND($c['E6'] == $g_encode['性別']['男'], $p['配偶者年齢'] >= $c['E19'], $p['本人年齢'] < $c['D19'], $c['D9'] < $g_const['被扶養者年収上限']), $c['D77'], 0));

		$c["CV$r"] = $c['C61'] * 12;

		for ($i = 1; $i <= $children_num; $i++) {
			$c["CW$r"][$i] = VB_IF($c["AW$r"][$i] < 18 + VB_IF($c['I13'][$i] != $g_encode['学校']['無'], $c['I14'][$i], 0), $c['C60'] * $c['C62'], 0) * 12;
		}

		$c["DA$r"] = $c['D61'] * 12;

		for ($i = 1; $i <= $children_num; $i++) {
			$c["DB$r"][$i] = VB_IF($c["AW$r"][$i] < 18 + VB_IF($c['I13'][$i] != $g_encode['学校']['無'], $c['I14'][$i], 0), $c['D60'] * $c['C62'], 0) * 12;
		}

		$c["DF$r"] = VB_SUM($n, "BB$r");
		$c["DI$r"] = VB_COUNTIF($c["AW$r"], "<4") * $g_const['保育年額'] * $c['I66'];
		$c["DJ$r"] = VB_COUNTIF($c["AW$r"], "<4") * $g_const['保育年額'] * $c['J66'];
		$c["DK$r"] = VB_IF($c['C28'] == $g_encode['住宅']['賃貸'], $c['C31'] * $c['C57'] * 12, $c['C35']);
		$c["DG$r"] = VB_IF(VB_MIN($n, "AW$r") < $c['F67'], $c['F66'] * 12 + $c["DI$r"], 0);
		$c["DH$r"] = VB_IF(VB_MIN($n, "AW$r") < $c['G67'], $c['G66'] * 12 + $c["DJ$r"], 0);

		for ($i = 1; $i <= $housing_loan_num; $i++) {
			$c["DL$r"][$i] = VB_IF($p['年'] <= $c['F30'][$i], VB_IF(VB_AND($c['F32'][$i] == $g_encode['ローン名義']['本人'], $c['F33'][$i] == $g_encode['団信加入']['有']), $y == 0 ? $c['F35'][$i] : 0, $c["BG$r"][$i]), 0);
		}

		$c["DP$r"] = VB_IF($c['C28'] == $g_encode['住宅']['賃貸'], $c['C31'] * $c['D57'] * 12, $c['C35']);

		for ($i = 1; $i <= $housing_loan_num; $i++) {
			$c["DQ$r"][$i] = VB_IF($p['年'] <= $c['F30'][$i], VB_IF(VB_AND($c['F32'][$i] == $g_encode['ローン名義']['配偶者'], $c['F33'][$i] == $g_encode['団信加入']['有']), $y == 0 ? $c['F35'][$i] : 0, $c["BG$r"][$i]), 0);
		}

		$c["DU$r"] = $c["BO$r"];
		$c["DV$r"] = VB_IF(VB_AND(VB_OR($c['E8'] == "", $c['D6'] == $g_encode['性別']['男']), $c['E9'] < $g_const['被扶養者年収上限']), VB_CHOOSE(VB_COUNTIF($c["AW$r"], "<18") + 1, 0, $c['F72'] + $c['G72'], $c['F72'] + $c['H72'], $c['F72'] + $c['I72'], $c['F72'] + $c['J72']), 0);
		$c["DW$r"] = VB_IF(VB_AND(VB_OR($c['E8'] == "", $c['D6'] == $g_encode['性別']['男']), $c['E9'] < $g_const['被扶養者年収上限']), $c['F73'], 0);
		$c["DX$r"] = VB_IF(VB_AND($c['D6'] == $g_encode['性別']['男'], VB_OR(VB_MIN($n, 'I8') < $g_const['扶養上限'], $c['E8'] > 35), $c['E9'] < $g_const['被扶養者年収上限'], VB_MIN($n, "AW$r") >= $g_const['扶養上限'], $p['配偶者年齢'] >= 40, $p['配偶者年齢'] < $g_const['年金開始']), $c['F74'], 0);
		$c["DY$r"] = VB_IF(VB_AND(VB_OR($c['E8'] == "", $c['D6'] == $g_encode['性別']['男']), $c['E9'] < $g_const['被扶養者年収上限']), $s['会社']['D10'], 0);
		$c["DZ$r"] = $c["BN$r"];
		$c["EA$r"] = VB_IF(VB_AND(VB_OR($c['D8'] == "", $c['E6'] == $g_encode['性別']['男']), $c['D9'] < $g_const['被扶養者年収上限']), VB_CHOOSE(VB_COUNTIF($c["AW$r"], "<18") + 1, 0, $c['E72'] + $c['G72'], $c['E72'] + $c['H72'], $c['E72'] + $c['I72'], $c['E72'] + $c['J72']), 0);
		$c["EB$r"] = VB_IF(VB_AND(VB_OR($c['D8'] == "", $c['E6'] == $g_encode['性別']['男']), $c['D9'] < $g_const['被扶養者年収上限']), $c['E73'], 0);
		$c["EC$r"] = VB_IF(VB_AND($c['E6'] == $g_encode['性別']['男'], VB_OR(VB_MIN($n, 'I8') < $g_const['扶養上限'], $c['D8'] > 35), $c['D9'] < $g_const['被扶養者年収上限'], VB_MIN($n, "AW$r") >= $g_const['扶養上限'], $p['本人年齢'] >= 40, $p['本人年齢'] < $g_const['年金開始']), $c['E74'], 0);
		$c["ED$r"] = VB_IF($c["AU$r"] >= 60,0, $c['F61'] * 12);

		for ($i = 1; $i <= $children_num; $i++) {
			$c["EE$r"][$i] = VB_IF($c["AU$r"] >= 60,0, VB_IF($c["AW$r"][$i] < 18 + VB_IF($c['I13'][$i] != $g_encode['学校']['無'], $c['I14'][$i], 0), $c['F60'] * $c['C62'], 0) * 12);
		}

		$c["EI$r"] = VB_IF($c["AV$r"] >= 60, 0, $c['G61'] * 12);

		for ($i = 1; $i <= $children_num; $i++) {
			$c["EJ$r"][$i] = VB_IF($c["AW$r"][$i] < 18 + VB_IF($c['I13'][$i] != $g_encode['学校']['無'], $c['I14'][$i], 0), $c['G60'] * $c['C62'], 0) * 12;
		}

		$c["EN$r"] = VB_IF(VB_MIN($n, "AW$r") < $c['F67'], $c['F66'] * 12, 0);
		$c["EO$r"] = VB_IF(VB_MIN($n, "AW$r") < $c['F67'], $c['G66'] * 12, 0);
		$c["EP$r"] = VB_IF($c["AU$r"] >= 60, 0, VB_IF($c['C28'] == $g_encode['住宅']['賃貸'], $c['C31'] * $c['F57'] * 12, $c['C35']));
		$c["EQ$r"] = VB_IF($c["AV$r"] >= 60, 0, VB_IF($c['C28'] == $g_encode['住宅']['賃貸'], $c['C31'] * $c['G57'] * 12, $c['C35']));
		$c["ER$r"] = VB_IF($p['本人年齢'] < $g_const['定年'], VB_SUM($n, "EE$r", "ED$r"), 0);
		$c["ES$r"] = VB_IF($p['本人年齢'] < $g_const['定年'], VB_SUM($n, "EN$r", "DI$r"), 0);
		$c["ET$r"] = VB_IF($p['本人年齢'] < $g_const['定年'], VB_SUM($n, "BB$r"), 0);
		$c["EU$r"] = VB_IF($p['本人年齢'] < $g_const['定年'], VB_SUM($n, "EP$r", "BG$r"), 0);
		$c["EV$r"] = VB_IF($p['本人年齢'] < $g_const['定年'], VB_SUM($n, "BK$r"), 0);
		$c["EW$r"] = VB_SUM($n, "ER$r:EV$r");
		$c["EX$r"] = VB_IF($p['配偶者年齢'] < $g_const['定年'], VB_SUM($n, "EJ$r", "EI$r"), 0);
		$c["EY$r"] = VB_IF($p['配偶者年齢'] < $g_const['定年'], VB_SUM($n, "EO$r", "DJ$r"), 0);
		$c["EZ$r"] = VB_IF($p['配偶者年齢'] < $g_const['定年'], VB_SUM($n, "BB$r"), 0);
		$c["FA$r"] = VB_IF($p['配偶者年齢'] < $g_const['定年'], VB_SUM($n, "EQ$r", "BG$r"), 0);
		$c["FB$r"] = VB_IF($p['配偶者年齢'] < $g_const['定年'], VB_SUM($n, "BK$r"), 0);
		$c["FC$r"] = VB_SUM($n, "EX$r:FB$r");
		$c["FD$r"] = VB_IF($p['本人年齢'] < $g_const['定年'], $c["BO$r"], 0);

		switch ($y) {
		case 0:
			$c["FE$r"] = 0;
			$c["FF$r"] = 0;
			$c["FH$r"] = 0;
			$c["FI$r"] = 0;
			break;
		case 1:
			$c["FE$r"] = VB_IF(VB_AND($p['本人年齢'] < $g_const['定年'], $c['E9'] < $g_const['被扶養者年収上限']), VB_CHOOSE(VB_COUNTIF($c["AW$r"], "<18") + 1, $c['F72'], $c['F72'] + $c['G72'], $c['F72'] + $c['H72'], $c['F72'] + $c['I72'], $c['F72'] + $c['J72']), 0) / 2;
			$c["FF$r"] = VB_IF(VB_AND($p['本人年齢'] < $g_const['定年'], $c['E9'] < $g_const['被扶養者年収上限']), $c['F73'], 0) / 2;
			$c["FH$r"] = VB_IF(VB_AND($p['配偶者年齢'] < $g_const['定年'], $c['D9'] < $g_const['被扶養者年収上限']), VB_CHOOSE(VB_COUNTIF($c["AW$r"], "<18") + 1, $c['F72'], $c['F72'] + $c['G72'], $c['F72'] + $c['H72'], $c['F72'] + $c['I72'], $c['F72'] + $c['J72']), 0) / 2;
			$c["FI$r"] = VB_IF(VB_AND($p['配偶者年齢'] < $g_const['定年'], $c['D9'] < $g_const['被扶養者年収上限']),$c['E73'], 0) / 2;
			break;
		default:
			$c["FE$r"] = VB_IF(VB_AND($p['本人年齢'] < $g_const['定年'], $c['E9'] < $g_const['被扶養者年収上限']), VB_CHOOSE(VB_COUNTIF($c["AW$r"], "<18") + 1, $c['F72'], $c['F72'] + $c['G72'], $c['F72'] + $c['H72'], $c['F72'] + $c['I72'], $c['F72'] + $c['J72']), 0);
			$c["FF$r"] = VB_IF(VB_AND($p['本人年齢'] < $g_const['定年'], $c['E9'] < $g_const['被扶養者年収上限']), $c['F73'], 0);
			$c["FH$r"] = VB_IF(VB_AND($p['配偶者年齢'] < $g_const['定年'], $c['D9'] < $g_const['被扶養者年収上限']), VB_CHOOSE(VB_COUNTIF($c["AW$r"], "<18") + 1, $c['F72'], $c['F72'] + $c['G72'], $c['F72'] + $c['H72'], $c['F72'] + $c['I72'], $c['F72'] + $c['J72']), 0);
			$c["FI$r"] = VB_IF(VB_AND($p['配偶者年齢'] < $g_const['定年'], $c['D9'] < $g_const['被扶養者年収上限']),$c['E73'], 0);
			break;
		}

		$c["FG$r"] = VB_IF($p['配偶者年齢'] < $g_const['定年'], $c["BN$r"], 0);

		if ($p['本人年齢'] >= $g_const['寿命'] && $p['配偶者年齢'] >= $g_const['寿命'])
			break;
	}

	$p['傷病付加給付率'] = &$c['EN3'];
	$p['傷病付加給付期間'] = &$c['EN4'];
	$p['傷病上乗せ給付率'] = &$c['EN5'];
	$p['病気有給'] = &$c['FY2'];
	$p['填補期間'] = &$c['FY3'];
	$p['給付月数'] = &$s['会社']['D13'];
	$p['定額'] = &$s['会社']['D23'];
	$p['定率'] = &$s['会社']['D24'];
	$p['免責期間'] = &$s['会社']['D29'];

	$c['AY2'] = MAX(0, $g_const['定年'] - $c['D8']);
	$c['AY3'] = MAX(0, $g_const['年金開始'] - $c['D8']);
	$c['AZ2'] = MAX(0, $g_const['年金開始'] - $c['D8'] - $c['AY2']);
	$c['AZ3'] = $g_const['寿命'] - $c['D8'] - $c['AY3'];
	$c['BA2'] = VB_IF($c['AZ2'] > 0, VB_SUM($n, VB_OFFSET('BA11', $c['AY2'], 0, $c['AZ2'], 1)), 0);
	$c['BA3'] = VB_SUM($n, VB_OFFSET('BA11', $c['AY3'], 0, $c['AZ3'], 1));

	for ($i = 1; $i <= $children_num; $i++) {
		$c['BB2'][$i] = VB_IF($c['AZ2'] > 0, VB_SUM2($n, $i, VB_OFFSET('BB11', $c['AY2'], 0, $c['AZ2'], 1)), 0);
		$c['BB3'][$i] = VB_SUM2($n, $i, VB_OFFSET('BB11', $c['AY3'], 0, $c['AZ3'], 1));
	}

	$c['BF2'] = VB_IF($c['AZ2'] > 0, VB_SUM($n, VB_OFFSET('BF11', $c['AY2'], 0, $c['AZ2'], 1)), 0);
	$c['BF3'] = VB_SUM($n, VB_OFFSET('BF11', $c['AY3'], 0, $c['AZ3'], 1));

	for ($i = 1; $i <= $housing_loan_num; $i++) {
		$c['BG2'][$i] = VB_IF($c['AZ2'] > 0, VB_SUM2($n, $i, VB_OFFSET('BG11', $c['AY2'], 0, $c['AZ2'], 1)), 0);
		$c['BG3'][$i] = VB_SUM2($n, $i, VB_OFFSET('BG11', $c['AY3'], 0, $c['AZ3'], 1));
	}

	for ($i = 1; $i <= $other_loan_num; $i++) {
		$c['BK2'][$i] = VB_IF($c['AZ2'] > 0, VB_SUM2($n, $i, VB_OFFSET('BK11', $c['AY2'], 0, $c['AZ2'], 1)), 0);
		$c['BK3'][$i] = VB_SUM2($n, $i, VB_OFFSET('BK11', $c['AY3'], 0, $c['AZ3'], 1));
	}

	$c['BN2'] = VB_IF($c['AZ2'] > 0, VB_SUM($n, VB_OFFSET('BN11', $c['AY2'], 0, $c['AZ2'], 1)), 0);
	$c['BN3'] = VB_SUM($n, VB_OFFSET('BN11', $c['AY3'], 0, $c['AZ3'], 1));
	$c['BO2'] = VB_IF($c['AZ2'] > 0, VB_SUM($n, VB_OFFSET('BO11', $c['AY2'], 0, $c['AZ2'], 1)), 0);
	$c['BO3'] = VB_SUM($n, VB_OFFSET('BO11', $c['AY3'], 0, $c['AZ3'], 1));
	$c['BP2'] = VB_IF($c['AZ2'] > 0, VB_SUM($n, VB_OFFSET('BP11', $c['AY2'], 0, $c['AZ2'], 1)), 0);
	$c['BP3'] = VB_SUM($n, VB_OFFSET('BP11', $c['AY3'], 0, $c['AZ3'], 1));
	$c['BQ2'] = VB_IF($c['AZ2'] > 0, VB_SUM($n, VB_OFFSET('BQ11', $c['AY2'], 0, $c['AZ2'], 1)), 0);
	$c['BQ3'] = VB_SUM($n, VB_OFFSET('BQ11', $c['AY3'], 0, $c['AZ3'], 1));
	$c['BR2'] = VB_IF($c['AZ2'] > 0, VB_SUM($n, VB_OFFSET('BR11', $c['AY2'], 0, $c['AZ2'], 1)), 0);
	$c['BR3'] = VB_SUM($n, VB_OFFSET('BR11', $c['AY3'], 0, $c['AZ3'], 1));
	$c['BS2'] = VB_IF($c['AZ2'] > 0, VB_SUM($n, VB_OFFSET('BS11', $c['AY2'], 0, $c['AZ2'], 1)), 0);
	$c['BS3'] = VB_SUM($n, VB_OFFSET('BS11', $c['AY3'], 0, $c['AZ3'], 1));
	$c['BT2'] = VB_IF($c['AZ2'] > 0, VB_SUM($n, VB_OFFSET('BT11', $c['AY2'], 0, $c['AZ2'], 1)), 0);
	$c['BT3'] = VB_SUM($n, VB_OFFSET('BT11', $c['AY3'], 0, $c['AZ3'], 1));
	$c['BU2'] = VB_IF($c['AZ2'] > 0, VB_SUM($n, VB_OFFSET('BU11', $c['AY2'], 0, $c['AZ2'], 1)), 0);
	$c['BU3'] = VB_SUM($n, VB_OFFSET('BU11', $c['AY3'], 0, $c['AZ3'], 1));
	$c['BV2'] = VB_IF($c['AZ2'] > 0, VB_SUM($n, VB_OFFSET('BV11', $c['AY2'], 0, $c['AZ2'], 1)), 0);
	$c['BV3'] = VB_SUM($n, VB_OFFSET('BV11', $c['AY3'], 0, $c['AZ3'], 1));
	$c['BW2'] = VB_IF($c['AZ2'] > 0, VB_SUM($n, VB_OFFSET('BW11', $c['AY2'], 0, $c['AZ2'], 1)), 0);
	$c['BW3'] = VB_SUM($n, VB_OFFSET('BW11', $c['AY3'], 0, $c['AZ3'], 1));
	$c['BX2'] = VB_IF($c['AZ2'] > 0, VB_SUM($n, VB_OFFSET('BX11', $c['AY2'], 0, $c['AZ2'], 1)), 0);
	$c['BX3'] = VB_SUM($n, VB_OFFSET('BX11', $c['AY3'], 0, $c['AZ3'], 1));
	$c['CU3'] = VB_IF($c['E8'] == "", VB_MAX($n, 'I22'), MAX(0, $g_const['年金開始'] - $c['E8']));
	$c['CU5'] = MAX(0, $g_const['年金開始'] - $c['D8']);
	$c['CV3'] = VB_IF($c['CU3'] > 0, VB_SUM($n, VB_OFFSET('CV11', 0, 0, $c['CU3'], 1)), 0);

	for ($i = 1; $i <= $children_num; $i++) {
		$c['CW3'][$i] = VB_IF($c['CU3'] > 0, VB_SUM2($n, $i, VB_OFFSET('CW11', 0, 0, $c['CU3'], 1)), 0);
	}

	$c['DA5'] = VB_IF($c['CU5'] > 0, VB_SUM($n, VB_OFFSET('DA11', 0, 0, $c['CU5'], 1)), 0);

	for ($i = 1; $i <= $children_num; $i++) {
		$c['DB5'][$i] = VB_IF($c['CU5'] > 0, VB_SUM2($n, $i, VB_OFFSET('DB11', 0, 0, $c['CU5'], 1)), 0);
	}

	$c['DF3'] = VB_IF($c['CU3'] > 0, VB_SUM($n, VB_OFFSET('DF11', 0, 0, $c['CU3'], 1)), 0);
	$c['DF5'] = VB_IF($c['CU5'] > 0, VB_SUM($n, VB_OFFSET('DF11', 0, 0, $c['CU5'], 1)), 0);
	$c['DG3'] = VB_IF($c['CU3'] > 0, VB_SUM($n, VB_OFFSET('DG11', 0, 0, $c['CU3'], 1)), 0);
	$c['DH5'] = VB_IF($c['CU5'] > 0, VB_SUM($n, VB_OFFSET('DH11', 0, 0, $c['CU5'], 1)), 0);
	$c['DI3'] = VB_IF($c['CU3'] > 0, VB_SUM($n, VB_OFFSET('DI11', 0, 0, $c['CU3'], 1)), 0);
	$c['DI5'] = VB_IF($c['CU5'] > 0, VB_SUM($n, VB_OFFSET('DI11', 0, 0, $c['CU5'], 1)), 0);
	$c['DJ3'] = VB_IF($c['CU3'] > 0, VB_SUM($n, VB_OFFSET('DJ11', 0, 0, $c['CU3'], 1)), 0);
	$c['DJ5'] = VB_IF($c['CU5'] > 0, VB_SUM($n, VB_OFFSET('DJ11', 0, 0, $c['CU5'], 1)), 0);
	$c['DK3'] = VB_IF($c['CU3'] > 0, VB_SUM($n, VB_OFFSET('DK11', 0, 0, $c['CU3'], 1)), 0);

	for ($i = 1; $i <= $housing_loan_num; $i++) {
		$c['DL3'][$i] = VB_IF($c['CU3'] > 0, VB_SUM2($n, $i, VB_OFFSET('DL11', 0, 0, $c['CU3'], 1)), 0);
	}

	$c['DP5'] = VB_IF($c['CU5'] > 0, VB_SUM($n, VB_OFFSET('DP11', 0, 0, $c['CU5'], 1)), 0);

	for ($i = 1; $i <= $housing_loan_num; $i++) {
		$c['DQ5'][$i] = VB_IF($c['CU5'] > 0, VB_SUM2($n, $i, VB_OFFSET('DQ11', 0, 0, $c['CU5'], 1)), 0);
	}

	$c['DU3'] = VB_IF($c['CU3'] > 0, VB_SUM($n, VB_OFFSET('DU11', 0, 0, $c['CU3'], 1)), 0);
	$c['DV3'] = VB_IF($c['CU3'] > 0, VB_SUM($n, VB_OFFSET('DV11', 0, 0, $c['CU3'], 1)), 0);
	$c['DW3'] = VB_IF($c['CU3'] > 0, VB_SUM($n, VB_OFFSET('DW11', 0, 0, $c['CU3'], 1)), 0);
	$c['DX3'] = VB_IF($c['CU3'] > 0, VB_SUM($n, VB_OFFSET('DX11', 0, 0, $c['CU3'], 1)), 0);
	$c['DY3'] = VB_IF($c['CU3'] > 0, VB_SUM($n, VB_OFFSET('DY11', 0, 0, $c['CU3'], 1)), 0);
	$c['DZ5'] = VB_IF($c['CU5'] > 0, VB_SUM($n, VB_OFFSET('DZ11', 0, 0, $c['CU5'], 1)), 0);
	$c['EA5'] = VB_IF($c['CU5'] > 0, VB_SUM($n, VB_OFFSET('EA11', 0, 0, $c['CU5'], 1)), 0);
	$c['EB5'] = VB_IF($c['CU5'] > 0, VB_SUM($n, VB_OFFSET('EB11', 0, 0, $c['CU5'], 1)), 0);
	$c['EC5'] = VB_IF($c['CU5'] > 0, VB_SUM($n, VB_OFFSET('EC11', 0, 0, $c['CU5'], 1)), 0);
	$c['EN3'] = $s['会社']['D12'];
	$c['EN4'] = $s['会社']['D13'];
	$c['EN5'] = $s['会社']['D14'];
	$c['EQ3'] = MIN(18, $c['D21'] * 12);
	$c['EQ4'] = MIN($s['会社']['D13'], $c['D21'] * 12 - $c['EQ3']);
	$c['EQ5'] = $c['D21'] * 12 - $c['EQ3'] - $c['EQ4'];
	$c['ER3'] = $c['ER11'] + $c['ER12'] / 2;
	$c['ER4'] = MIN($c['EQ4'], 6) / 12 * $c['ER12'] + MAX(0, $c['EQ4'] - 6) / 12 * $c['ER13'];
	$c['ER5'] = VB_SUM($n, "ER11:ER$r") - $c['ER3'] - $c['ER4'];
	$c['ES3'] = $c['ES11'] + $c['ES12'] / 2;
	$c['ES4'] = MIN($c['EQ4'], 6) / 12 * $c['ES12'] + MAX(0, $c['EQ4'] - 6) / 12 * $c['ES13'];
	$c['ES5'] = VB_SUM($n, "ES11:ES$r") - $c['ES3'] - $c['ES4'];
	$c['ET3'] = $c['ET11'] + $c['ET12'] / 2;
	$c['ET4'] = MIN($c['EQ4'], 6) / 12 * $c['ET12'] + MAX(0, $c['EQ4'] - 6) / 12 * $c['ET13'];
	$c['ET5'] = VB_SUM($n, "ET11:ET$r") - $c['ET3'] - $c['ET4'];
	$c['EU3'] = $c['EU11'] + $c['EU12'] / 2;
	$c['EU4'] = MIN($c['EQ4'], 6) / 12 * $c['EU12'] + MAX(0, $c['EQ4'] - 6) / 12 * $c['EU13'];
	$c['EU5'] = VB_SUM($n, "EU11:EU$r") - $c['EU3'] - $c['EU4'];
	$c['EV3'] = $c['EV11'] + $c['EV12'] / 2;
	$c['EV4'] = MIN($c['EQ4'], 6) / 12 * $c['EV12'] + MAX(0, $c['EQ4'] - 6) / 12 * $c['EV13'];
	$c['EV5'] = VB_SUM($n, "EV11:EV$r") - $c['EV3'] - $c['EV4'];
	$c['EW3'] = MIN(18, $c['E21'] * 12);
	$c['EW4'] = 0;
	$c['EW5'] = $c['E21'] * 12 - $c['EW3'] - $c['EW4'];
	$c['EX3'] = $c['EX11'] + $c['EX12'] / 2;
	$c['EX4'] = MIN($c['EW4'], 6) / 12 * $c['EX12'] + MAX(0, $c['EW4'] - 6) / 12 * $c['EX13'];
	$c['EX5'] = VB_SUM($n, "EX11:EX$r") - $c['EX3'] - $c['EX4'];
	$c['EY3'] = $c['EY11'] + $c['EY12'] / 2;
	$c['EY4'] = MIN($c['EW4'], 6) / 12 * $c['EY12'] + MAX(0, $c['EW4'] - 6) / 12 * $c['EY13'];
	$c['EY5'] = VB_SUM($n, "EY11:EY$r") - $c['EY3'] - $c['EY4'];
	$c['EZ3'] = $c['EZ11'] + $c['EZ12'] / 2;
	$c['EZ4'] = MIN($c['EW4'], 6) / 12 * $c['EZ12'] + MAX(0, $c['EW4'] - 6) / 12 * $c['EZ13'];
	$c['EZ5'] = VB_SUM($n, "EZ11:EZ$r") - $c['EZ3'] - $c['EZ4'];
	$c['FA3'] = $c['FA11'] + $c['FA12'] / 2;
	$c['FA4'] = MIN($c['EW4'], 6) / 12 * $c['FA12'] + MAX(0, $c['EW4'] - 6) / 12 * $c['FA13'];
	$c['FA5'] = VB_SUM($n, "FA11:FA$r") - $c['FA3'] - $c['FA4'];
	$c['FB3'] = $c['FB11'] + $c['FB12'] / 2;
	$c['FB4'] = MIN($c['EW4'], 6) / 12 * $c['FB12'] + MAX(0, $c['EW4'] - 6) / 12 * $c['FB13'];
	$c['FB5'] = VB_SUM($n, "FB11:FB$r") - $c['FB3'] - $c['FB4'];
	$c['FL4'] = $s['会社']['D13'];
	$c['FL5'] = $c['EQ5'];
	$c['FL10'] = $c['EW5'];
	$c['FM3'] = $c['FD11'] + ($c['FD12'] / 2);
	$c['FM4'] = ($c['FD12'] / 12) * $c['FL4'];
	$c['FM5'] = VB_SUM($n, "FD11:FD$r") - $c['FM3'] - $c['FM4'];
	$c['FM8'] = $c['FG11'] + ($c['FG12'] / 2);
	$c['FM9'] = 0;
	$c['FM10'] = VB_SUM($n, "FG11:FG$r") - $c['FM8'] - $c['FM9'];
	$c['FN3'] = 0;
	$c['FN4'] = MIN($c['EQ4'], 6) / 6 * $c['FE12'] + MAX(0, $c['EQ4'] - 6) / 12 * $c['FE13'];
	$c['FN5'] = VB_SUM($n, "FE11:FE$r") - $c['FN3'] - $c['FN4'];
	$c['FN8'] = 0;
	$c['FN9'] = 0;
	$c['FN10'] = VB_SUM($n, "FH11:FH$r") - $c['FN8'] - $c['FN9'];
	$c['FO3'] = 0;
	$c['FO4'] = MIN($c['EQ4'], 6) / 6 * $c['FF12'] + MAX(0, $c['EQ4'] - 6) / 12 * $c['FF13'];
	$c['FO5'] = VB_SUM($n, "FF11:FF$r") - $c['FO3'] - $c['FO4'];
	$c['FO8'] = 0;
	$c['FO9'] = 0;
	$c['FO10'] = VB_SUM($n, "FI11:FI$r") - $c['FO8'] - $c['FO9'];
	$c['FP3'] = $c['D14'] * 0.6 * $c['FL3'];
	$c['FP8'] = $c['E14'] * 0.6 * $c['FL8'];
	$c['FQ3'] = $c['D14'] * $p['傷病上乗せ給付率'] * $c['FL3'];
	$c['FR4'] = $c['D14'] * $p['傷病付加給付率'] * $p['傷病付加給付期間'];
	$c['FY2'] = $s['会社']['D11'];
	$c['FY3'] = $s['会社']['D28'];
	$c['FT1'] = MAX(0, $p['免責期間'] / 30 - $p['病気有給']);
	$c['FT3'] = MAX(0, 18 - $c['FT1']);
	$c['FT4'] = MAX(0, $c['FL4'] - MAX(0, $c['FT1'] - 18));
	$c['FT5'] = MIN(MAX(0, $c['FL5'] - MAX(0, $c['FT1'] - $p['給付月数'] - 18)), MAX(0, $p['填補期間'] - $c['FT3'] - $c['FT4']));
	$c['FU3'] = VB_IF($p['給付形態'] == $g_encode['給付形態']['定額'], $p['定額'], $s['会社']['D25'] * 0.4 * $p['定率']);
	$c['FU4'] = VB_IF($p['給付形態'] == $g_encode['給付形態']['定額'], $p['定額'], ($s['会社']['D25'] - ($c['FE13'] + $c['FF13']) / 12) * $p['定率']);
	$c['FU5'] = VB_IF($p['給付形態'] == $g_encode['給付形態']['定額'], $p['定額'], ($s['会社']['D25'] - ($c['FE13'] + $c['FF13']) / 12) * $p['定率']);
	$c['FV3'] = $c['FT3'] * $c['FU3'];
	$c['FV4'] = $c['FT4'] * $c['FU4'];
	$c['FV5'] = $c['FT5'] * $c['FU5'];
}

// ■基本計算処理（第３段階）
function calc_basic_info3() {
	global $g_encode, $g_const, $g_sheets;

	$n = '基本';
	$s = &$g_sheets;
	$c = &$g_sheets[$n];

	for ($y = 0; $y <= 70; $y++) {
		$r = 11 + $y;

		$p['本人年齢'] = &$c["AU$r"];
		$p['配偶者年齢'] = &$c["AV$r"];

		// 本人生保系情報
		for ($i = 1; $i <= $s['生保']['D5']; $i++) {
			$c["BY$r"][$i] = VB_IF(VB_AND($s['生保']['D91'][$i] > 0, $p['本人年齢'] >= $s['生保']['D92'][$i], VB_OR($s['生保']['D94'][$i] == $g_encode['終身／定期']['終身'], $p['本人年齢'] < $s['生保']['D92'][$i] + $s['生保']['D93'][$i])), $s['生保']['D91'][$i], 0) + VB_IF($p['本人年齢'] == $s['生保']['D96'][$i], $s['生保']['D97'][$i], 0);
		}

		// 配偶者生保系情報
		for ($i = 1; $i <= $s['生保']['D139']; $i++) {
			$c["CD$r"][$i] = VB_IF(VB_AND($s['生保']['D215'][$i] > 0, $p['配偶者年齢'] >= $s['生保']['D216'][$i], VB_OR($s['生保']['D218'][$i] == $g_encode['終身／定期']['終身'], $p['配偶者年齢'] < $s['生保']['D216'][$i] + $s['生保']['D217'][$i])), $s['生保']['D215'][$i], 0) + VB_IF($p['配偶者年齢'] == $s['生保']['D220'][$i], $s['生保']['D221'][$i], 0);
		}

		// 損保
		for ($i = 1; $i <= $s['損保']['C2']; $i++) {
			$c["CI$r"][$i] = VB_IF(VB_AND($s['損保']['C31'][$i] > 0, $p['本人年齢'] >= $s['損保']['C32'][$i], VB_OR($s['損保']['C34'][$i] == $g_encode['終身／定期']['終身'], $p['本人年齢'] < $s['損保']['C32'][$i] + $s['損保']['C33'][$i])), $s['損保']['C31'][$i], 0) + VB_IF($p['本人年齢'] == $s['損保']['C36'][$i], $s['損保']['C37'][$i], 0);
			$c["CM$r"][$i] = VB_IF(VB_AND($s['損保']['C39'][$i] > 0, $p['配偶者年齢'] >= $s['損保']['C40'][$i], VB_OR($s['損保']['C42'][$i] == $g_encode['終身／定期']['終身'], $p['配偶者年齢'] < $s['損保']['C40'][$i] + $s['損保']['C41'][$i])), $s['損保']['C39'][$i], 0) + VB_IF($p['配偶者年齢'] == $s['損保']['C44'][$i], $s['損保']['C45'][$i], 0);
		}

		$c["CQ$r"] = VB_SUM($n, "BA$r:BM$r");
		$c["CR$r"] = VB_SUM($n, "BN$r:CP$r");

		if ($p['本人年齢'] >= $g_const['寿命'] && $p['配偶者年齢'] >= $g_const['寿命'])
			break;
	}

	$c['BY2'] = VB_IF($c['AZ2'] > 0, VB_SUM($n, VB_OFFSET('BY11', $c['AY2'], 0, $c['AZ2'], 1)), 0);
	$c['BY3'] = VB_SUM($n, VB_OFFSET('BY11', $c['AY3'], 0, $c['AZ3'], 1));
	$c['BZ2'] = VB_IF($c['AZ2'] > 0, VB_SUM($n, VB_OFFSET('BZ11', $c['AY2'], 0, $c['AZ2'], 1)), 0);
	$c['BZ3'] = VB_SUM($n, VB_OFFSET('BZ11', $c['AY3'], 0, $c['AZ3'], 1));
	$c['CA2'] = VB_IF($c['AZ2'] > 0, VB_SUM($n, VB_OFFSET('CA11', $c['AY2'], 0, $c['AZ2'], 1)), 0);
	$c['CA3'] = VB_SUM($n, VB_OFFSET('CA11', $c['AY3'], 0, $c['AZ3'], 1));
	$c['CB2'] = VB_IF($c['AZ2'] > 0, VB_SUM($n, VB_OFFSET('CB11', $c['AY2'], 0, $c['AZ2'], 1)), 0);
	$c['CB3'] = VB_SUM($n, VB_OFFSET('CB11', $c['AY3'], 0, $c['AZ3'], 1));
	$c['CC2'] = VB_IF($c['AZ2'] > 0, VB_SUM($n, VB_OFFSET('CC11', $c['AY2'], 0, $c['AZ2'], 1)), 0);
	$c['CC3'] = VB_SUM($n, VB_OFFSET('CC11', $c['AY3'], 0, $c['AZ3'], 1));
	$c['CD2'] = VB_IF($c['AZ2'] > 0, VB_SUM($n, VB_OFFSET('CD11', $c['AY2'], 0, $c['AZ2'], 1)), 0);
	$c['CD3'] = VB_SUM($n, VB_OFFSET('CD11', $c['AY3'], 0, $c['AZ3'], 1));
	$c['CE2'] = VB_IF($c['AZ2'] > 0, VB_SUM($n, VB_OFFSET('CE11', $c['AY2'], 0, $c['AZ2'], 1)), 0);
	$c['CE3'] = VB_SUM($n, VB_OFFSET('CE11', $c['AY3'], 0, $c['AZ3'], 1));
	$c['CF2'] = VB_IF($c['AZ2'] > 0, VB_SUM($n, VB_OFFSET('CF11', $c['AY2'], 0, $c['AZ2'], 1)), 0);
	$c['CF3'] = VB_SUM($n, VB_OFFSET('CF11', $c['AY3'], 0, $c['AZ3'], 1));
	$c['CG2'] = VB_IF($c['AZ2'] > 0, VB_SUM($n, VB_OFFSET('CG11', $c['AY2'], 0, $c['AZ2'], 1)), 0);
	$c['CG3'] = VB_SUM($n, VB_OFFSET('CG11', $c['AY3'], 0, $c['AZ3'], 1));
	$c['CH2'] = VB_IF($c['AZ2'] > 0, VB_SUM($n, VB_OFFSET('CH11', $c['AY2'], 0, $c['AZ2'], 1)), 0);
	$c['CH3'] = VB_SUM($n, VB_OFFSET('CH11', $c['AY3'], 0, $c['AZ3'], 1));
	$c['CI2'] = VB_IF($c['AZ2'] > 0, VB_SUM($n, VB_OFFSET('CI11', $c['AY2'], 0, $c['AZ2'], 1)), 0);
	$c['CI3'] = VB_SUM($n, VB_OFFSET('CI11', $c['AY3'], 0, $c['AZ3'], 1));
	$c['CJ2'] = VB_IF($c['AZ2'] > 0, VB_SUM($n, VB_OFFSET('CJ11', $c['AY2'], 0, $c['AZ2'], 1)), 0);
	$c['CJ3'] = VB_SUM($n, VB_OFFSET('CJ11', $c['AY3'], 0, $c['AZ3'], 1));
	$c['CK2'] = VB_IF($c['AZ2'] > 0, VB_SUM($n, VB_OFFSET('CK11', $c['AY2'], 0, $c['AZ2'], 1)), 0);
	$c['CK3'] = VB_SUM($n, VB_OFFSET('CK11', $c['AY3'], 0, $c['AZ3'], 1));
	$c['CL2'] = VB_IF($c['AZ2'] > 0, VB_SUM($n, VB_OFFSET('CL11', $c['AY2'], 0, $c['AZ2'], 1)), 0);
	$c['CL3'] = VB_SUM($n, VB_OFFSET('CL11', $c['AY3'], 0, $c['AZ3'], 1));
	$c['CM2'] = VB_IF($c['AZ2'] > 0, VB_SUM($n, VB_OFFSET('CM11', $c['AY2'], 0, $c['AZ2'], 1)), 0);
	$c['CM3'] = VB_SUM($n, VB_OFFSET('CM11', $c['AY3'], 0, $c['AZ3'], 1));
	$c['CN2'] = VB_IF($c['AZ2'] > 0, VB_SUM($n, VB_OFFSET('CN11', $c['AY2'], 0, $c['AZ2'], 1)), 0);
	$c['CN3'] = VB_SUM($n, VB_OFFSET('CN11', $c['AY3'], 0, $c['AZ3'], 1));
	$c['CO2'] = VB_IF($c['AZ2'] > 0, VB_SUM($n, VB_OFFSET('CO11', $c['AY2'], 0, $c['AZ2'], 1)), 0);
	$c['CO3'] = VB_SUM($n, VB_OFFSET('CO11', $c['AY3'], 0, $c['AZ3'], 1));
	$c['CP2'] = VB_IF($c['AZ2'] > 0, VB_SUM($n, VB_OFFSET('CP11', $c['AY2'], 0, $c['AZ2'], 1)), 0);
	$c['CP3'] = VB_SUM($n, VB_OFFSET('CP11', $c['AY3'], 0, $c['AZ3'], 1));
	$c['CQ2'] = VB_IF($c['AZ2'] > 0, VB_SUM($n, VB_OFFSET('CQ11', $c['AY2'], 0, $c['AZ2'], 1)), 0);
	$c['CQ3'] = VB_SUM($n, VB_OFFSET('CQ11', $c['AY3'], 0, $c['AZ3'], 1));
	$c['CR2'] = VB_IF($c['AZ2'] > 0, VB_SUM($n, VB_OFFSET('CR11', $c['AY2'], 0, $c['AZ2'], 1)), 0);
	$c['CR3'] = VB_SUM($n, VB_OFFSET('CR11', $c['AY3'], 0, $c['AZ3'], 1));
}

// ■大学年数取得
function _get_daigaku_nensu($daigaku_kubun_cd) {
	switch ($daigaku_kubun_cd) {
	case 1:
		return 4;
	case 2:
		return 2;
	}
	return 0;
}

// ■子供年間費用取得
function _get_kyoikuhi($school, $ko_shi_cd) {
	global $g_const;

	return $g_const['年間教育費'][$school]['費用'][$ko_shi_cd];
}
?>