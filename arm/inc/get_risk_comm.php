<?
/******************************************************
* system  : xxx リスク診断
* title   : リスク診断コメント取得処理
* content : リスク診断コメントをレスポンス形式で取得する
* version : 1.0
* author  : aizawa
*******************************************************/

// ■リスク診断コメント取得
function get_risk_comm() {
	global $g_sheets;

	if ($g_sheets['生保']['D5'] == 0 && $g_sheets['生保']['D139'] == 0 && $g_sheets['損保']['C2'] == 0)
		return _get_risk_comm1();		// 保険未入力
	else
		return _get_risk_comm2();		// 保険入力済み
}

// ■保険未入力時コメント取得
function _get_risk_comm1() {
	global $g_sheets;

	// 本人及び共通参照データ
	$ref['死亡カバーポイント'] = $g_sheets['死亡']['J16'] * 100;		// DEATH_21
	$ref['結婚'] = $g_sheets['基本']['F7'];		// A02i
	$ref['子供人数'] = $g_sheets['基本']['K3'];		// A03i
	$ref['貯蓄額'] = $g_sheets['基本']['C49'];		// A12i/DEATH_17
	$ref['死亡時必要資金合計'] = $g_sheets['死亡']['J9'];		// DEATH_6
	$ref['万一の生活費'] = $g_sheets['基本']['C60'];		// F02i
	$ref['現在の生活費'] = $g_sheets['基本']['C48'];		// [基本計算]J25
	$ref['本人収入'] = $g_sheets['基本']['D9'];		// A07i
	$ref['配偶者収入'] = $g_sheets['基本']['E9'];		// A07y
	$ref['現在年齢'] = $g_sheets['基本']['D8'];		// [基本計算]A21
	$ref['性別'] = $g_sheets['基本']['D6'];		// [入力規制]A01
	$ref['就業不能ポイント'] = $g_sheets['総合']['J17'];		// IMP_74
	$ref['就業不能社会保障'] = $g_sheets['就業不能']['C39'];		// IMP_70
	$ref['住宅ローン'] = $g_sheets['基本']['J27'];		// G07i
	$ref['住宅ローン最長残年数'] = VB_MAX('基本', 'F30');		// G20i
	$ref['就業不能時生活費'] = $g_sheets['基本']['F60'];		// F06
	$ref['社会保障依存度'] = $g_sheets['老後']['C26'];		// OLD_42
	$ref['60歳後労働依存度'] = $g_sheets['老後']['C27'];		// OLD_43
	$ref['老後生活費削減金額'] = $g_sheets['老後']['J26'];		// OLD_44
	$ref['年間必要貯蓄金額'] = $g_sheets['老後']['J27'];		// OLD_45
	$ref['住まい'] = $g_sheets['基本']['C28'];		// G01i

//echo '<pre>';var_dump($ref);echo '</pre>';

	// 本人リスク診断コメント取得
	$comm['総合'] = _get_risk_comm1_sg($ref);
	$comm['死亡']['本人'] = _get_risk_comm1_sb($ref);
	$comm['医療']['本人'] = _get_risk_comm1_ir($ref);
	$comm['就業不能']['本人'] = _get_risk_comm1_sf($ref);
	$comm['老後'] = _get_risk_comm1_rg($ref);
	$comm['損害'] = _get_risk_comm1_sn($ref);

	// 配偶者参照データ
	$ref['死亡カバーポイント'] = $g_sheets['死亡']['X16'] * 100;		// DEATH_21
	$ref['死亡時必要資金合計'] = $g_sheets['死亡']['X9'];		// DEATH_6
	$ref['万一の生活費'] = $g_sheets['基本']['D60'];		// F02i
	$ref['本人収入'] = $g_sheets['基本']['E9'];		// A07i
	$ref['配偶者収入'] = $g_sheets['基本']['D9'];		// A07y
	$ref['現在年齢'] = $g_sheets['基本']['E8'];		// [基本計算]A21
	$ref['性別'] = $g_sheets['基本']['E6'];		// [入力規制]A01
	$ref['就業不能ポイント'] = $g_sheets['総合']['W17'];		// IMP_74
	$ref['就業不能社会保障'] = $g_sheets['就業不能']['R38'];		// IMP_70
	$ref['就業不能時生活費'] = $g_sheets['基本']['G60'];		// F06

	// 配偶者リスク診断コメント取得
	$comm['死亡']['配偶者'] = _get_risk_comm1_sb($ref);
	$comm['医療']['配偶者'] = _get_risk_comm1_ir($ref);
	$comm['就業不能']['配偶者'] = _get_risk_comm1_sf($ref);

	return $comm;
}

// ■保険未入力時総合コメント取得
function _get_risk_comm1_sg($ref) {
	$comment = new comment_class;

	$comment->set('rfb_tp01');

	return $comment->get();
}

// ■保険未入力時死亡コメント取得
function _get_risk_comm1_sb($ref) {
	global $g_encode;

	$comment = new comment_class;

	// （１）
	if ($ref['死亡カバーポイント'] >= 90) {
		if ($ref['結婚'] == $g_encode['結婚']['既婚'] || $ref['子供人数'] > 0)
			$comment->set('rfb_sb01', 1);
		else
			$comment->set('rfb_sb01', 2);
	} else
		$comment->set('rfb_sb01', 3);

	// （２）
	if ($ref['貯蓄額'] > 500 && $ref['貯蓄額'] / $ref['死亡時必要資金合計'] > 0.1)
		$comment->set('rfb_sb02', 1, 1, 'mata');
	if (($ref['結婚'] == $g_encode['結婚']['既婚'] || $ref['子供人数'] > 0) && $ref['万一の生活費'] / $ref['現在の生活費'] < 0.6)
		$comment->set('rfb_sb02', 2, 2, 'mata');
	if ($ref['配偶者収入'] >= 130 && $ref['結婚'] == $g_encode['結婚']['既婚'])
		$comment->set('rfb_sb02', 3, 3, 'mata');

	// 定型句
	$comment->set('rfb_sb03');

	return $comment->get();
}

// ■保険未入力時医療コメント取得
function _get_risk_comm1_ir($ref) {
	global $g_encode;

	$comment = new comment_class;

	// （１）定型句
	$comment->set('rfb_ir01');

	// （２）
	if ($ref['現在年齢'] < 40)
		$comment->set('rfb_ir02', 1, 1, 'mata');
	else
		$comment->set('rfb_ir02', 2, 1, 'mata');

	// （３）
	if ($ref['性別'] == $g_encode['性別']['男'] && $ref['現在年齢'] <= 29)
		$comment->set('rfb_ir03', 1, 2, 'doyo');
	if ($ref['性別'] == $g_encode['性別']['女'])
		$comment->set('rfb_ir03', 2, 1, 'doyo');

	// 定型句
	$comment->set('rfb_ir04');

	return $comment->get();
}

// ■保険未入力時就業不能コメント取得
function _get_risk_comm1_sf($ref) {
	global $g_encode;

	$comment = new comment_class;

	// （１）
	if ($ref['現在年齢'] >= 57)
		$comment->set('rfb_sf01', 1);
	else {
		if ($ref['就業不能ポイント'] >= 90) {
			if ($ref['配偶者収入'] < 130 || $ref['結婚'] == $g_encode['結婚']['独身'])
				$comment->set('rfb_sf01', 2);
			elseif ($ref['配偶者収入'] >= 130)
				$comment->set('rfb_sf01', 3);
		} else {
			if ($ref['結婚'] == $g_encode['結婚']['既婚'] || $ref['子供人数'] > 0)
				$comment->set('rfb_sf01', 4);
			elseif ($ref['結婚'] == $g_encode['結婚']['独身'] && $ref['子供人数'] == 0)
				$comment->set('rfb_sf01', 5);
		}
	}

	// （２）
	if ($ref['現在年齢'] < 57 && $ref['住宅ローン'] > 0)
		$comment->set('rfb_sf02', 1, 2, 'mata');
	if ($ref['就業不能時生活費'] / $ref['現在の生活費'] < 0.8)
		$comment->set('rfb_sf02', 2, 1, 'mata');
	if ($ref['就業不能社会保障'] > 50)
		$comment->set('rfb_sf02', 3, 3, 'mata');

	// 定型句
	$comment->set('rfb_sf03');

	return $comment->get();
}

// ■保険未入力時老後コメント取得
function _get_risk_comm1_rg($ref) {
	global $g_encode;

	$comment = new comment_class;

	// （１）
	if ($ref['老後生活費削減金額'] <= 0) {
		$comment->set('rfb_rg01', 1);
	} elseif (0 < $ref['老後生活費削減金額'] && $ref['老後生活費削減金額'] <= 5) {
		$tmp = $ref['年間必要貯蓄金額'] / ($ref['結婚'] == $g_encode['結婚']['既婚'] ? $ref['本人収入'] + $ref['配偶者収入'] : $ref['本人収入']);
		if (0 <= $tmp && $tmp < 0.2)
			$comment->set('rfb_rg01', 2);
		elseif ($tmp >= 0.2)
			$comment->set('rfb_rg01', 3);
	} else {
		$tmp = $ref['年間必要貯蓄金額'] / ($ref['結婚'] == $g_encode['結婚']['既婚'] ? $ref['本人収入'] + $ref['配偶者収入'] : $ref['本人収入']);
		if (0 <= $tmp && $tmp < 0.2)
			$comment->set('rfb_rg01', 4);
		elseif ($tmp >= 0.2)
			$comment->set('rfb_rg01', 5);
	}

	// （２）
	if ($ref['60歳後労働依存度'] > 0.25)
		$comment->set('rfb_rg02', 1, 2, 'mata');
	if ($ref['社会保障依存度'] > 0.7)
		$comment->set('rfb_rg02', 2, 1, 'mata');
	if ((60 - $ref['現在年齢']) < $ref['住宅ローン最長残年数'] && $ref['現在年齢'] < 60)
		$comment->set('rfb_rg02', 3, 3, 'mata');
	if ($ref['住まい'] == $g_encode['住宅']['賃貸'])
		$comment->set('rfb_rg02', 5, 4, 'mata');
	if ($ref['住まい'] == $g_encode['住宅']['持家'])
		$comment->set('rfb_rg02', 4, 4, 'mata');

	// 定型句
	$comment->set('rfb_rg03');

	return $comment->get();
}

// ■保険未入力時損害コメント取得
function _get_risk_comm1_sn($ref) {
	global $g_encode;

	$comment = new comment_class;

	// 定型句
	$comment->set('rfb_sg01');

	// 定型句
	$comment->set('rfb_sg02');

	return $comment->get();
}

// ■保険入力済みコメント取得
function _get_risk_comm2() {
	global $g_sheets;

	// 本人及び共通参照データ
	$ref['死亡カバーポイント'] = $g_sheets['死亡']['J16'] * 100;		// DEATH_21
	$ref['災害死亡時割増保険金額合計'] = $g_sheets['生保']['I28'] + $g_sheets['生保']['I35'];
	$ref['死亡保険金額合計'] = $g_sheets['生保']['I37'];		// rs_sb31/DEATH_19
	$ref['生保主契約保険種別'] = $g_sheets['生保']['D10'];		// sh_sal03
	$ref['死亡保険金額'] = $g_sheets['生保']['D37'];		// rs_sb21
	$ref['主契約者'] = $g_sheets['生保']['D13'];		// sh_sal06
	$ref['主契約者死亡時家族契約継続'] = $g_sheets['生保']['D14'];		// sh_sal07
	$ref['特約保険種別'] = $g_sheets['生保']['D30'];
	$ref['脳卒中ポイント'] = $g_sheets['医療']['K12'] * 100;		// MEDI_41
	$ref['がんポイント'] = $g_sheets['医療']['K15'] * 100;		// MEDI_42
	$ref['心筋梗塞ポイント'] = $g_sheets['医療']['K18'] * 100;		// MEDI_43
	$ref['医療評価ポイント'] = $g_sheets['総合']['J12'];		// MEDI_45
	$ref['現在年齢'] = $g_sheets['基本']['D8'];		// [基本計算]A21
	$ref['就業不能ポイント'] = $g_sheets['総合']['J17'];		// IMP_74
	$ref['60歳満了Ｐ'] = $g_sheets['生保']['D103'];		// sh_ssf05
	$ref['老後生活費削減金額'] = $g_sheets['老後']['J26'];		// OLD_44
	$ref['年間必要貯蓄金額'] = $g_sheets['老後']['J27'];		// OLD_45
	$ref['個人年金'] = VB_SUM('生保', 'D91');		// sh_srg01
	$ref['結婚'] = $g_sheets['基本']['F7'];		// A02i
	$ref['本人収入'] = $g_sheets['基本']['D9'];		// A07i
	$ref['配偶者収入'] = $g_sheets['基本']['E9'];		// A07y
	$ref['損害評価ポイント'] = $g_sheets['総合']['J26'];		// DAM_24
	$ref['住宅形態'] = $g_sheets['基本']['C29'];		// G02
	$ref['損保主契約保険種別'] = $g_sheets['損保']['C13'];		// sp_al08
	$ref['住宅ローン'] = $g_sheets['基本']['J27'];		// G07i
	$ref['火災建物評価金額'] = $g_sheets['損保']['G49'];		// sp_ks01
	$ref['火災家財評価金額'] = $g_sheets['損保']['G51'];		// sa_ks03
	$ref['賠償責任保険'] = $g_sheets['損保']['G27'];		// sp_sn12
	$ref['住まい'] = $g_sheets['基本']['C28'];		// G01i

	// 本人リスク診断コメント取得
	$comm['総合'] = _get_risk_comm2_sg($ref);
	$comm['死亡']['本人'] = _get_risk_comm2_sb($ref);
	$comm['医療']['本人'] = _get_risk_comm2_ir($ref);
	$comm['就業不能']['本人'] = _get_risk_comm2_sf($ref);
	$comm['老後'] = _get_risk_comm2_rg($ref);
	$comm['損害'] = _get_risk_comm2_sn($ref);

	// 配偶者参照データ
	$ref['死亡カバーポイント'] = $g_sheets['死亡']['X16'] * 100;		// DEATH_21
	$ref['災害死亡時割増保険金額合計'] = $g_sheets['生保']['I162'] + $g_sheets['生保']['I169'];
	$ref['死亡保険金額合計'] = $g_sheets['生保']['I171'];		// rs_sb31/DEATH_19
	$ref['生保主契約保険種別'] = $g_sheets['生保']['D144'];		// sh_sal03
	$ref['死亡保険金額'] = $g_sheets['生保']['D171'];		// rs_sb21
	$ref['主契約者'] = $g_sheets['生保']['D147'];		// sh_sal06
	$ref['主契約者死亡時家族契約継続'] = $g_sheets['生保']['D148'];		// sh_sal07
	$ref['特約保険種別'] = $g_sheets['生保']['D164'];
	$ref['脳卒中ポイント'] = $g_sheets['医療']['X12'] * 100;		// MEDI_41
	$ref['がんポイント'] = $g_sheets['医療']['X15'] * 100;		// MEDI_42
	$ref['心筋梗塞ポイント'] = $g_sheets['医療']['X18'] * 100;		// MEDI_43
	$ref['医療評価ポイント'] = $g_sheets['総合']['W12'];		// MEDI_45
	$ref['現在年齢'] = $g_sheets['基本']['E8'];		// [基本計算]A21
	$ref['就業不能ポイント'] = $g_sheets['総合']['W17'];		// IMP_74
	$ref['60歳満了Ｐ'] = $g_sheets['生保']['D228'];		// sh_ssf05
	$ref['個人年金'] = VB_SUM('生保', 'D215');		// sh_srg01
	$ref['本人収入'] = $g_sheets['基本']['E9'];		// A07i
	$ref['配偶者収入'] = $g_sheets['基本']['D9'];		// A07y

	// 配偶者診断コメント取得
	$comm['死亡']['配偶者'] = _get_risk_comm2_sb($ref);
	$comm['医療']['配偶者'] = _get_risk_comm2_ir($ref);
	$comm['就業不能']['配偶者'] = _get_risk_comm2_sf($ref);

	return $comm;
}

// ■保険入力済み総合コメント取得
function _get_risk_comm2_sg($ref) {
	$comment = new comment_class;

	$comment->set('rfb_tp02');

	return $comment->get();
}
// ■保険入力済み死亡コメント取得
function _get_risk_comm2_sb($ref) {
	global $g_encode;

	$comment = new comment_class;

	// （１）
	if ($ref['死亡カバーポイント'] >= 110)
		$comment->set('sfb_sb01', 1);
	elseif ($ref['死亡カバーポイント'] >= 90)
		$comment->set('sfb_sb01', 2);
	else
		$comment->set('sfb_sb01', 3);

	// （２）
	if ($ref['災害死亡時割増保険金額合計'] > $ref['死亡保険金額合計'] * 1.2)
		$comment->set('sfb_sb02', 1, 2, 'mata');
	if (is_array($ref['生保主契約保険種別'])) {
		foreach ($ref['生保主契約保険種別'] as $key => $val) {
			if ($val == $g_encode['保険種別']['グループ保険'] && $ref['死亡保険金額'][$key] / $ref['死亡保険金額合計'] >= 0.6) {
				$comment->set('sfb_sb02', 2, 1, 'mata');
				break;
			}
		}
	}

	// 定型句
	$comment->set('sfb_sb03');

	return $comment->get();
}


// ■保険入力済み医療コメント取得
function _get_risk_comm2_ir($ref) {
	global $g_encode;

	$comment = new comment_class;

	// （１）
	if ($ref['医療評価ポイント'] >= 110)
		$comment->set('sfb_ir01', 1);
	elseif ($ref['医療評価ポイント'] >= 90)
		$comment->set('sfb_ir01', 2);
	else
		$comment->set('sfb_ir01', 3);

	// （２）
	if (($ref['脳卒中ポイント'] < 80 || $ref['心筋梗塞ポイント'] < 80) && $ref['現在年齢'] > 45)
		$comment->set('sfb_ir02', 1, 2, 'mata');
	if (is_array($ref['主契約者'])) {
		foreach ($ref['主契約者'] as $key => $val) {
			if ($val == $g_encode['主契約者']['配偶者への特約'] && $ref['主契約者死亡時家族契約継続'][$key] == $g_encode['主契約者死亡時家族契約継続']['契約消滅'] && $ref['特約保険種別'][$key] == $g_encode['保険種別']['医療保険']) {
				$comment->set('sfb_ir02', 2, 1, 'mata');
				break;
			}
		}
	}
	if ($ref['がんポイント'] >= 110)
		$comment->set('sfb_ir02', 3, 3, 'mata');

	// 定型句
	$comment->set('sfb_ir03');

	return $comment->get();
}

// ■保険入力済み就業不能コメント取得
function _get_risk_comm2_sf($ref) {
	global $g_encode;

	$comment = new comment_class;

	// （１）
	if ($ref['現在年齢'] >= 57)
		$comment->set('sfb_sf01', 1);
	else {
		if ($ref['就業不能ポイント'] >= 110)
			$comment->set('sfb_sf01', 2);
		elseif ($ref['就業不能ポイント'] >= 90)
			$comment->set('sfb_sf01', 3);
		else
			$comment->set('sfb_sf01', 4);
	}

	// （２）
	if (is_array($ref['60歳満了Ｐ'])) {
		foreach ($ref['60歳満了Ｐ'] as $key => $val) {
			if ($val == $g_encode['60歳満了']['定期']) {
				$comment->set('sfb_sf02', '', 1, 'mata');
				break;
			}
		}
	}

	// 定型句
	$comment->set('sfb_sf03');

	return $comment->get();
}

// ■保険入力済み老後コメント取得
function _get_risk_comm2_rg($ref) {
	global $g_encode;

	$comment = new comment_class;

	// （１）
	if ($ref['老後生活費削減金額'] <= 0) {
		$comment->set('sfb_rg01', 1);
	} elseif ($ref['老後生活費削減金額'] <= 5) {
		$tmp = $ref['年間必要貯蓄金額'] / ($ref['結婚'] == $g_encode['結婚']['既婚'] ? $ref['本人収入'] + $ref['配偶者収入'] : $ref['本人収入']);
		if (0 <= $tmp && $tmp < 0.2)
			$comment->set('sfb_rg01', 2);
		elseif ($tmp >= 0.2)
			$comment->set('sfb_rg01', 3);
	} else {
		$tmp = $ref['年間必要貯蓄金額'] / ($ref['結婚'] == $g_encode['結婚']['既婚'] ? $ref['本人収入'] + $ref['配偶者収入'] : $ref['本人収入']);
		if (0 <= $tmp && $tmp < 0.2)
			$comment->set('sfb_rg01', 4);
		elseif ($tmp >= 0.2)
			$comment->set('sfb_rg01', 5);
	}

	// （２）
	if ($ref['個人年金'] > 0)
		$comment->set('sfb_rg02', 1, 1, 'mata');
	elseif ($ref['個人年金'] == 0)
		$comment->set('sfb_rg02', 2, 1, 'mata');

	// 定型句
	$comment->set('sfb_rg03');

	return $comment->get();
}

// ■保険入力済み損害コメント取得
function _get_risk_comm2_sn($ref) {
	global $g_encode;

	$comment = new comment_class;

	// （１）
	if ($ref['損害評価ポイント'] >= 110)
		$comment->set('sfb_sn01', 1);
	elseif ($ref['損害評価ポイント'] >= 90)
		$comment->set('sfb_sn01', 2);
	else
		$comment->set('sfb_sn01', 3);

	// （２）
	if (is_array($ref['損保主契約保険種別'])) {
		foreach ($ref['損保主契約保険種別'] as $val) {
			if ($ref['住宅形態'] == $g_encode['住宅形態']['共同住宅（鉄筋）'] && ($val == $g_encode['損保保険種別']['住宅火災保険'] || $val == $g_encode['損保保険種別']['住宅総合保険'])) {
				$comment->set('sfb_sn02', 1, 2, 'mata');
				break;
			}
		}
	}
	if ($ref['住宅ローン'] > 0)
		$comment->set('sfb_sn02', 2, 1, 'mata');
	if ($ref['火災建物評価金額'] == 0 || $ref['火災家財評価金額'] == 0)
		$comment->set('sfb_sn02', 3, 4, 'mata');
	if ($ref['賠償責任保険'] >= 10000)
		$comment->set('sfb_sn02', 4, 5, 'mata');
	if ($ref['住まい'] == $g_encode['住宅']['持家'] && $ref['火災家財評価金額'] == 0)
		$comment->set('sfb_sn02', 5, 3, 'mata');

	// 定型句
	$comment->set('sfb_sn03');

	return $comment->get();
}
?>