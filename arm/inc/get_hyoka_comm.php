<?
/******************************************************
* system  : xxx リスク診断
* title   : 保険評価コメント取得処理
* content : 保険評価コメントをレスポンス形式で取得する
* version : 1.0
* author  : aizawa
*******************************************************/

// ■保険評価コメント取得
function get_hyoka_comm() {
	$comm['死亡'] = _get_hyoka_comm_sb();
	$comm['医療'] = _get_hyoka_comm_ir();

	return $comm;
}

// ■死亡保険評価コメント取得
function _get_hyoka_comm_sb() {
	global $g_sheets;

	$s = &$g_sheets['死亡保険'];

	// 本人
	$n = 0;
	for ($i = 1; $i <= $g_sheets['生保']['D5']; $i++) {
		// 契約期間種別があるものだけを有効とする
		if ($g_sheets['死亡保険']['D14'][$i] != '' || $g_sheets['死亡保険']['E14'][$i] != '') {
			$n++;

			// 主契約参照データ
			$ref['健康体・非喫煙'] = $s['D32'][$i];		// hs_sb01
			$ref['保険料安定度'] = $s['D34'][$i];			// hs_sb02
			$ref['保険料割安さ'] = $s['D35'][$i];			// hs_sb03
			$ref['継続性'] = $s['D36'][$i];						// hs_sb04
			$ref['契約期間'] = $s['D14'][$i];					// sh_ssb01
			$ref['保険種類'] = $s['D8'][$i];					// sh_sal03
			$ref['逓減定期保険'] = $s['D25'][$i];			// sh_ssb08
			$ref['定期契約期間'] = $s['D15'][$i];			// sh_ssb03
			$ref['定期満了年齢'] = $s['D16'][$i];			// sh_ssb03
			$ref['主契約者'] = $s['D11'][$i];					// C9
			$ref['主契約者死亡時家族契約継続'] = $s['D12'][$i];		// C10
			$ref['契約種別'] = '主契約';

			// 主契約コメント取得
			$comm['本人']["保険$n"]['保険料安定度']['主契約'] = _get_hyoka_comm_sb_anteido($ref);
			$comm['本人']["保険$n"]['保険料の割安さ']['主契約'] = _get_hyoka_comm_sb_wariyasusa($ref);
			$comm['本人']["保険$n"]['継続性']['主契約'] = _get_hyoka_comm_sb_keizokusei($ref);

			// 特約参照データ
			$ref['健康体・非喫煙'] = $s['E32'][$i];		// hs_sb01
			$ref['保険料安定度'] = $s['E34'][$i];			// hs_sb02
			$ref['保険料割安さ'] = $s['E35'][$i];			// hs_sb03
			$ref['継続性'] = $s['E36'][$i];						// hs_sb04
			$ref['契約期間'] = $s['E14'][$i];					// sh_tsb01
			$ref['保険種類'] = $s['E8'][$i];					// sh_tal04
			$ref['逓減定期保険'] = $s['E25'][$i];			// sh_tsb08
			$ref['定期契約期間'] = $s['E15'][$i];			// sh_tsb03
			$ref['定期満了年齢'] = $s['E16'][$i];			// sh_tsb03
			$ref['主契約者'] = $s['D11'][$i];					// C9
			$ref['主契約者死亡時家族契約継続'] = $s['D12'][$i];		// C10
			$ref['契約種別'] = '特約';

			// 特約コメント取得
			$comm['本人']["保険$n"]['保険料安定度']['特約'] = _get_hyoka_comm_sb_anteido($ref);
			$comm['本人']["保険$n"]['保険料の割安さ']['特約'] = _get_hyoka_comm_sb_wariyasusa($ref);
			$comm['本人']["保険$n"]['継続性']['特約'] = _get_hyoka_comm_sb_keizokusei($ref);
		}
	}

	// 配偶者
	$n = 0;
	for ($i = 1; $i <= $g_sheets['生保']['D139']; $i++) {
		// 契約期間種別があるものだけを有効とする
		if ($g_sheets['死亡保険']['D67'][$i] != '' || $g_sheets['死亡保険']['E67'][$i] != '') {
			$n++;

			// 主契約参照データ
			$ref['健康体・非喫煙'] = $s['D85'][$i];		// hs_sb01
			$ref['保険料安定度'] = $s['D87'][$i];			// hs_sb02
			$ref['保険料割安さ'] = $s['D88'][$i];			// hs_sb03
			$ref['継続性'] = $s['D89'][$i];						// hs_sb04
			$ref['契約期間'] = $s['D67'][$i];					// sh_ssb01
			$ref['保険種類'] = $s['D61'][$i];					// sh_sal03
			$ref['逓減定期保険'] = $s['D78'][$i];			// sh_ssb08
			$ref['定期契約期間'] = $s['D68'][$i];			// sh_ssb03
			$ref['定期満了年齢'] = $s['D69'][$i];			// sh_ssb03
			$ref['主契約者'] = $s['D64'][$i];					// C9
			$ref['主契約者死亡時家族契約継続'] = $s['D65'][$i];		// C10
			$ref['契約種別'] = '主契約';

			// 主契約コメント取得
			$comm['配偶者']["保険$n"]['保険料安定度']['主契約'] = _get_hyoka_comm_sb_anteido($ref);
			$comm['配偶者']["保険$n"]['保険料の割安さ']['主契約'] = _get_hyoka_comm_sb_wariyasusa($ref);
			$comm['配偶者']["保険$n"]['継続性']['主契約'] = _get_hyoka_comm_sb_keizokusei($ref);

			// 特約参照データ
			$ref['健康体・非喫煙'] = $s['E85'][$i];		// hs_sb01
			$ref['保険料安定度'] = $s['E87'][$i];			// hs_sb02
			$ref['保険料割安さ'] = $s['E88'][$i];			// hs_sb03
			$ref['継続性'] = $s['E89'][$i];						// hs_sb04
			$ref['契約期間'] = $s['E67'][$i];					// sh_tsb01
			$ref['保険種類'] = $s['E61'][$i];					// sh_tal04
			$ref['逓減定期保険'] = $s['E78'][$i];			// sh_tsb08
			$ref['定期契約期間'] = $s['E68'][$i];			// sh_tsb03
			$ref['定期満了年齢'] = $s['E69'][$i];			// sh_tsb03
			$ref['主契約者'] = $s['D64'][$i];					// C9
			$ref['主契約者死亡時家族契約継続'] = $s['D65'][$i];		// C10
			$ref['契約種別'] = '特約';

			// 特約コメント取得
			$comm['配偶者']["保険$n"]['保険料安定度']['特約'] = _get_hyoka_comm_sb_anteido($ref);
			$comm['配偶者']["保険$n"]['保険料の割安さ']['特約'] = _get_hyoka_comm_sb_wariyasusa($ref);
			$comm['配偶者']["保険$n"]['継続性']['特約'] = _get_hyoka_comm_sb_keizokusei($ref);
		}
	}

	return $comm;
}

// ■死亡保険・保険料安定度コメント取得
function _get_hyoka_comm_sb_anteido($ref) {
	global $g_encode;

	$comment = new comment_class;

	// （１）
	if ($ref['契約期間'] == $g_encode['契約期間種別']['終身']) {
		if ($ref['保険料安定度'] >= 100)
			$comment->set('sbs_a01', 1, 1);
		elseif ($ref['保険料安定度'] > 80)
			$comment->set('sbs_a01', 2, 2);
		else
			$comment->set('sbs_a01', 3, 3);
	}

	if ($ref['契約期間'] == $g_encode['契約期間種別']['定期（更新可）']) {
		if ($ref['保険料安定度'] > 80)
			$comment->set('sbs_a01', 4, 4);
		else
			$comment->set('sbs_a01', 5, 5, null, array('E39' => $ref['定期契約期間']));
	}

	if ($ref['契約期間'] == $g_encode['契約期間種別']['定期（更新不可）']) {
		if ($ref['保険料安定度'] > 80)
			$comment->set('sbs_a01', 6, 6, null, array('E40' => $ref['定期満了年齢']));
		else
			$comment->set('sbs_a01', 7, 7, null, array('E40' => $ref['定期満了年齢']));
	}

	return $comment->get();
}

// ■死亡保険・保険料の割安さコメント取得
function _get_hyoka_comm_sb_wariyasusa($ref) {
	global $g_encode;

	$comment = new comment_class;

	// （１）
	if ($ref['保険種類'] == $g_encode['保険種別']['終身保険'] ||$ref['保険種類'] == $g_encode['保険種別']['養老保険']) {
		if ($ref['保険料割安さ'] >= 88)
			$comment->set('sbs_b01', 1, 1);
		elseif ($ref['保険料割安さ'] >= 56)
			$comment->set('sbs_b01', 2, 2);
		else
			$comment->set('sbs_b01', 3, 3);
	}

	if ($ref['保険種類'] == $g_encode['保険種別']['グループ保険'])
		$comment->set('sbs_b01', 4, 4);

	if ($ref['保険種類'] == $g_encode['保険種別']['変額終身保険'])
		$comment->set('sbs_b01', 5, 5);

	if ($ref['保険種類'] != '' && $ref['保険種類'] != $g_encode['保険種別']['グループ保険'] && $ref['逓減定期保険'] == 0)
		$comment->set('sbs_b01', 6, 6);

	if ($ref['健康体・非喫煙'] == 1)
		$comment->set('sbs_b01', 7, 7);

	return $comment->get();
}

// ■死亡保険・継続性コメント取得
function _get_hyoka_comm_sb_keizokusei($ref) {
	global $g_encode;

	$comment = new comment_class;

	// （１）
	if ($ref['契約期間'] == $g_encode['契約期間種別']['終身'])
		$comment->set('sbs_c01', 1, 1);

	if ($ref['契約期間'] == $g_encode['契約期間種別']['定期（更新可）']) {
		if ($ref['契約種別'] == '主契約')
			$comment->set('sbs_c01', 2, 2);
		if ($ref['契約種別'] == '特約')
			$comment->set('sbs_c01', 3, 3);
	}

	if ($ref['契約期間'] == $g_encode['契約期間種別']['定期（更新不可）']) {
		if ($ref['継続性'] >= 100)
			$comment->set('sbs_c01', 4, 4, null, array('E40' => $ref['定期満了年齢']));
		else
			$comment->set('sbs_c01', 5, 5, null, array('E40' => $ref['定期満了年齢']));
	}

	// （２）
	if ($ref['主契約者'] == $g_encode['主契約者']['配偶者への特約'] && $ref['主契約者死亡時家族契約継続'] == $g_encode['主契約者死亡時家族契約継続']['契約消滅'])
		$comment->set('sbs_c02', '', 1, 'mata');

	return $comment->get();
}

// ■医療保険評価コメント取得
function _get_hyoka_comm_ir() {
	global $g_sheets;

	$s = &$g_sheets['医療保険'];

	// 本人
	$n = 0;
	for ($i = 1; $i <= $g_sheets['生保']['D5']; $i++) {
		// 契約期間種別があるものだけを有効とする
		if ($g_sheets['医療保険']['D14'][$i] != '' || $g_sheets['医療保険']['E14'][$i] != '') {
			$n++;

			// 主契約参照データ
			$ref['継続性'] = $s['D33'][$i];						// hs_ir01
			$ref['保険料支払負担'] = $s['D34'][$i];		// hs_ir02
			$ref['保障の範囲'] = $s['D35'][$i];				// hs_ir03
			$ref['給付の長さ'] = $s['D36'][$i];				// hs_ir04
			$ref['契約期間'] = $s['D14'][$i];					// sh_sir01
			$ref['特定疾病一時金'] = $s['D26'][$i];		// sh_sir10
			$ref['支給開始日'] = $s['D29'][$i];				// sh_sir13
			$ref['開始前期間分'] = $s['D30'][$i];			// sh_sir14
			$ref['定期契約期間'] = $s['D15'][$i];			// sh_ssb03
			$ref['定期満了年齢'] = $s['D16'][$i];			// sh_ssb03
			$ref['主契約者'] = $s['D11'][$i];					// sh_sal06
			$ref['主契約者死亡時家族契約継続'] = $s['D12'][$i];		// sh_sal07
			$ref['給付期間'] = $s['D31'][$i];					// sh_sir16

			// 主契約コメント取得
			$comm['本人']["保険$n"]['継続性']['主契約'] = _get_hyoka_comm_ir_keizokusei($ref);
			$comm['本人']["保険$n"]['保険料支払負担']['主契約'] = _get_hyoka_comm_ir_futan($ref);
			$comm['本人']["保険$n"]['保障の範囲']['主契約'] = _get_hyoka_comm_ir_hani($ref);
			$comm['本人']["保険$n"]['給付の長さ']['主契約'] = _get_hyoka_comm_ir_nagasa($ref);

			// 特約参照データ
			$ref['継続性'] = $s['E33'][$i];						// hs_ir01
			$ref['保険料支払負担'] = $s['E34'][$i];		// hs_ir02
			$ref['保障の範囲'] = $s['E35'][$i];				// hs_ir03
			$ref['給付の長さ'] = $s['E36'][$i];				// hs_ir04
			$ref['契約期間'] = $s['E14'][$i];					// sh_tir01
			$ref['特定疾病一時金'] = $s['E26'][$i];		// sh_tir10
			$ref['支給開始日'] = $s['E29'][$i];				// sh_tir13
			$ref['開始前期間分'] = $s['E30'][$i];			// sh_tir14
			$ref['定期契約期間'] = $s['E15'][$i];			// sh_tsb03
			$ref['定期満了年齢'] = $s['E16'][$i];			// sh_tsb03
			$ref['主契約者'] = $s['D11'][$i];					// sh_sal06
			$ref['主契約者死亡時家族契約継続'] = $s['D12'][$i];		// sh_sal07
			$ref['給付期間'] = $s['E31'][$i];					// sh_tir16

			// 特約コメント取得
			$comm['本人']["保険$n"]['継続性']['特約'] = _get_hyoka_comm_ir_keizokusei($ref);
			$comm['本人']["保険$n"]['保険料支払負担']['特約'] = _get_hyoka_comm_ir_futan($ref);
			$comm['本人']["保険$n"]['保障の範囲']['特約'] = _get_hyoka_comm_ir_hani($ref);
			$comm['本人']["保険$n"]['給付の長さ']['特約'] = _get_hyoka_comm_ir_nagasa($ref);
		}
	}

	// 配偶者
	$n = 0;
	for ($i = 1; $i <= $g_sheets['生保']['D139']; $i++) {
		// 契約期間種別があるものだけを有効とする
		if ($g_sheets['医療保険']['D61'][$i] != '' || $g_sheets['医療保険']['E61'][$i] != '') {
			$n++;

			// 主契約参照データ
			$ref['継続性'] = $s['D80'][$i];						// hs_ir01
			$ref['保険料支払負担'] = $s['D81'][$i];		// hs_ir02
			$ref['保障の範囲'] = $s['D82'][$i];				// hs_ir03
			$ref['給付の長さ'] = $s['D83'][$i];				// hs_ir04
			$ref['契約期間'] = $s['D61'][$i];					// sh_sir01
			$ref['特定疾病一時金'] = $s['D73'][$i];		// sh_sir10
			$ref['支給開始日'] = $s['D76'][$i];				// sh_sir13
			$ref['開始前期間分'] = $s['D77'][$i];			// sh_sir14
			$ref['定期契約期間'] = $s['D62'][$i];			// sh_ssb03
			$ref['定期満了年齢'] = $s['D63'][$i];			// sh_ssb03
			$ref['主契約者'] = $s['D58'][$i];					// sh_sal06
			$ref['主契約者死亡時家族契約継続'] = $s['D59'][$i];		// sh_sal07
			$ref['給付期間'] = $s['D78'][$i];					// sh_sir16

			// 主契約コメント取得
			$comm['配偶者']["保険$n"]['継続性']['主契約'] = _get_hyoka_comm_ir_keizokusei($ref);
			$comm['配偶者']["保険$n"]['保険料支払負担']['主契約'] = _get_hyoka_comm_ir_futan($ref);
			$comm['配偶者']["保険$n"]['保障の範囲']['主契約'] = _get_hyoka_comm_ir_hani($ref);
			$comm['配偶者']["保険$n"]['給付の長さ']['主契約'] = _get_hyoka_comm_ir_nagasa($ref);

			// 特約参照データ
			$ref['継続性'] = $s['E80'][$i];						// hs_ir01
			$ref['保険料支払負担'] = $s['E81'][$i];		// hs_ir02
			$ref['保障の範囲'] = $s['E82'][$i];				// hs_ir03
			$ref['給付の長さ'] = $s['E83'][$i];				// hs_ir04
			$ref['契約期間'] = $s['E61'][$i];					// sh_tir01
			$ref['特定疾病一時金'] = $s['E73'][$i];		// sh_tir10
			$ref['支給開始日'] = $s['E76'][$i];				// sh_tir13
			$ref['開始前期間分'] = $s['E77'][$i];			// sh_tir14
			$ref['定期契約期間'] = $s['E62'][$i];			// sh_tsb03
			$ref['定期満了年齢'] = $s['E63'][$i];			// sh_tsb03
			$ref['主契約者'] = $s['D58'][$i];					// sh_sal06
			$ref['主契約者死亡時家族契約継続'] = $s['D59'][$i];		// sh_sal07
			$ref['給付期間'] = $s['E78'][$i];					// sh_tir16

			// 特約コメント取得
			$comm['配偶者']["保険$n"]['継続性']['特約'] = _get_hyoka_comm_ir_keizokusei($ref);
			$comm['配偶者']["保険$n"]['保険料支払負担']['特約'] = _get_hyoka_comm_ir_futan($ref);
			$comm['配偶者']["保険$n"]['保障の範囲']['特約'] = _get_hyoka_comm_ir_hani($ref);
			$comm['配偶者']["保険$n"]['給付の長さ']['特約'] = _get_hyoka_comm_ir_nagasa($ref);
		}
	}

	return $comm;
}

// ■医療保険・継続性コメント取得
function _get_hyoka_comm_ir_keizokusei($ref) {
	global $g_encode;

	$comment = new comment_class;

	// （１）
	if ($ref['契約期間'] == $g_encode['契約期間種別']['終身'])
		$comment->set('irs_a01', 1, 1);

	if ($ref['契約期間'] == $g_encode['契約期間種別']['定期（更新可）'])
		$comment->set('irs_a01', 2, 2);

	if ($ref['契約期間'] == $g_encode['契約期間種別']['定期（更新不可）']) {
		if ($ref['継続性'] >= 80)
			$comment->set('irs_a01', 3, 3, null, array('C14' => $ref['定期満了年齢']));
		elseif ($ref['継続性'] >= 70)
			$comment->set('irs_a01', 4, 4, null, array('C14' => $ref['定期満了年齢']));
		else
			$comment->set('irs_a01', 5, 5, null, array('C14' => $ref['定期満了年齢']));
	}

	// （２）
	if ($ref['主契約者'] == $g_encode['主契約者']['配偶者への特約'] && $ref['主契約者死亡時家族契約継続'] == $g_encode['主契約者死亡時家族契約継続']['契約消滅'])
		$comment->set('irs_a02', '', 1, 'mata');

	return $comment->get();
}

// ■医療保険・保険料支払負担コメント取得
function _get_hyoka_comm_ir_futan($ref) {
	global $g_encode;

	$comment = new comment_class;

	// （１）
	if ($ref['契約期間'] == $g_encode['契約期間種別']['終身']) {
		if ($ref['保険料支払負担'] >= 100)
			$comment->set('irs_b01', 1, 1);
		elseif ($ref['保険料支払負担'] >= 80)
			$comment->set('irs_b01', 2, 2);
		else
			$comment->set('irs_b01', 3, 3);
	}

	if ($ref['契約期間'] == $g_encode['契約期間種別']['定期（更新可）']) {
		if ($ref['保険料支払負担'] >= 80)
			$comment->set('irs_b01', 4, 4);
		else
			$comment->set('irs_b01', 5, 5, null, array('C13' => $ref['定期契約期間']));
	}

	if ($ref['契約期間'] == $g_encode['契約期間種別']['定期（更新不可）']) {
		if ($ref['保険料支払負担'] >= 80)
			$comment->set('irs_b01', 6, 6, null, array('C14' => $ref['定期満了年齢']));
		elseif ($ref['保険料支払負担'] >= 70)
			$comment->set('irs_b01', 7, 7, null, array('C14' => $ref['定期満了年齢']));
		else
			$comment->set('irs_b01', 8, 8, null, array('C14' => $ref['定期満了年齢']));
	}

	return $comment->get();
}

// ■医療保険・保障の範囲コメント取得
function _get_hyoka_comm_ir_hani($ref) {
	global $g_encode;

	$comment = new comment_class;

	// （１）
	if ($ref['保障の範囲'] >= 100)
		$comment->set('irs_c01', 1, 2);

	if ($ref['保障の範囲'] == 100 && ($ref['支給開始日'] == $g_encode['支給開始日']['4泊5日以上'] || $ref['支給開始日'] == $g_encode['支給開始日']['7泊8日以上']) && $ref['開始前期間分'] == $g_encode['開始前期間分']['支給あり'])
		$comment->set('irs_c01', 2, 1);

	if (80 <= $ref['保障の範囲'] && $ref['保障の範囲'] < 100)
		$comment->set('irs_c01', 3, 3);

	if ($ref['保障の範囲'] < 80)
		$comment->set('irs_c01', 4, 4);

	// （２）
	if ($ref['特定疾病一時金'] > 0)
		$comment->set('irs_c02', '', 1, 'mata');

	return $comment->get();
}

// ■医療保険・給付の長さコメント取得
function _get_hyoka_comm_ir_nagasa($ref) {
	global $g_encode;

	$comment = new comment_class;

	// （１）
	if ($ref['給付の長さ'] >= 100)
		$comment->set('irs_d01', 1, 1);
	elseif ($ref['給付の長さ'] > 80)
		$comment->set('irs_d01', 2, 2);
	elseif ($ref['給付の長さ'] == 80)
		$comment->set('irs_d01', 3, 3);
	else
		$comment->set('irs_d01', 4, 4, null, array('C29' => $ref['給付期間']));

	return $comment->get();
}
?>