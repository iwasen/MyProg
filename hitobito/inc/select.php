<?php
/**
 * ひとびと･net 共通ファイル
 *
 * 選択肢表示処理
 *
 *
 * @package
 * @author
 * @version
 */

// <option>タグ出力
function option_tag($value, $selected, $text) {
	$attr = value_selected($value, $selected);
	return "<option $attr>$text</option>\n";
}

// 表示行数
function select_displine($selected) {
	echo '<option ', value_selected(0, $selected), ">- 全て -</option>\n";
	for ($i = 10; $i <= 100; $i += 10)
		echo '<option ', value_selected($i, $selected), ">$i</option>\n";
}

// 年
function select_year($start_year, $default, $selected) {
	if ($default)
		$tag = option_tag('', $selected, $default);

	$end_year = date('Y') + 8;
	for ($i = $start_year; $i < $end_year; $i++)
		$tag .= option_tag($i, $selected, $i . '年');

	return $tag;
}

// 月
function select_month($default, $selected) {
	if ($default)
		$tag = option_tag('', $selected, $default);

	for ($i = 1; $i <= 12; $i++)
		$tag .= option_tag($i, $selected, $i . '月');

	return $tag;
}

// 日
function select_day($default, $selected) {
	if ($default)
		$tag = option_tag('', $selected, $default);

	for ($i = 1; $i <= 31; $i++)
		$tag .= option_tag($i, $selected, $i . '日');

	return $tag;
}

// 年月(YYYYMM)
function select_year_month($default, $selected) {
	$start_ym = '200507';
	$end_ym = date('Ym');

	if ($default)
		$tag = option_tag('', $selected, $default);

	for ($ym = $start_ym; $ym <= $end_ym; $ym = sprintf('%04d%02d', $y, $m)) {
		sscanf($ym, '%4d%2d', &$y, &$m);
		$tag .= option_tag($ym, $selected, sprintf('%d/%d', $y, $m));

		if (++$m > 12) {
			$y++;
			$m = 1;
		}
	}

	return $tag;
}

// 時間
function select_hour($default, $selected) {
	if ($default)
		$tag = option_tag('', $selected, $default);

	for ($i = 0; $i < 24; $i++) {
		if ($i < 12)
			$tag .= option_tag($i, $selected, 'AM ' . $i);
		else
			$tag .= option_tag($i, $selected, 'PM ' . ($i - 12));
	}

	return $tag;
}

// 都道府県
function select_todofuken($default, $selected) {
	if ($default)
		$tag = option_tag('', $selected, $default);

	$sql = "SELECT tdf_area_id,tdf_name FROM m_todofuken ORDER BY tdf_area_id";
	$result = db_exec($sql);
	$nrow = pg_numrows($result);
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);
		$tag .= option_tag($fetch->tdf_area_id, $selected, htmlspecialchars($fetch->tdf_name));
	}

	return $tag;
}

// 職業
function select_shokugyo($default, $selected) {
	if ($default)
		$tag = option_tag('', $selected, $default);

	$sql = "SELECT skg_shokugyo_id,skg_shokugyo_name FROM m_shokugyo ORDER BY skg_shokugyo_id";
	$result = db_exec($sql);
	$nrow = pg_numrows($result);
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);
		$tag .= option_tag($fetch->skg_shokugyo_id, $selected, htmlspecialchars($fetch->skg_shokugyo_name));
	}

	return $tag;
}

// メンバーステータス
function select_member_status($default, $selected) {
	if ($default)
		$tag = option_tag('', $selected, $default);

	$tag .= option_tag('0', $selected, '仮登録')
				. option_tag('1', $selected, '有効')
				. option_tag('2', $selected, '不達状態')
				. option_tag('3', $selected, '一時停止')
				. option_tag('9', $selected, '退会');

	return $tag;
}

// MLメンバーステータス
function select_member_ml_status($default, $selected) {
	if ($default)
		$tag = option_tag('', $selected, $default);

	$tag .= option_tag('2', $selected, 'ゲスト登録')
				. option_tag('3', $selected, '本登録')
				. option_tag('9', $selected, '退会');

	return $tag;
}

// ナビステータス
function select_navi_status($selected) {
	$tag  = option_tag('0', $selected, '未承認')
				. option_tag('1', $selected, '承認')
				. option_tag('2', $selected, '修正')
				. option_tag('9', $selected, '退会');

	return $tag;
}

// チャネル
function select_channel($default, $selected) {
	if ($default)
		$tag = option_tag('', $selected, $default);

	$sql = "SELECT chn_channel_id, chn_channel_name FROM m_channel WHERE chn_status = 1 ORDER BY chn_order";
	$result = db_exec($sql);
	$nrow = pg_numrows($result);
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);
		$tag .= option_tag($fetch->chn_channel_id, $selected, htmlspecialchars($fetch->chn_channel_name));
	}

	return $tag;
}

// サブチャネル
function select_sub_channel($default, $selected) {
	if ($default)
		$tag = option_tag('', $selected, $default);

	$sql = "SELECT sch_subchannel_id, sch_subchannel_name FROM m_sub_channel WHERE sch_status = 1 ORDER BY sch_order";
	$result = db_exec($sql);
	$nrow = pg_numrows($result);
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);
		$tag .= option_tag($fetch->sch_subchannel_id, $selected, htmlspecialchars($fetch->sch_subchannel_name));
	}

	return $tag;
}

// ナビ名
function select_navi_name($default, $selected) {
	if ($default)
		$tag = option_tag('', $selected, $default);

	$sql = "SELECT nav_navi_id, nav_name1, nav_name2 FROM t_navi ORDER BY nav_navi_id";
	$result = db_exec($sql);
	$nrow = pg_numrows($result);
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);
echo $fetch->nav_name;
		$tag .= option_tag($fetch->nav_navi_id, $selected, htmlspecialchars(trim("$fetch->nav_name1 $fetch->nav_name2")));
	}

	return $tag;
}

// ひとびと便り配信状態
function select_hitobito_letter($default, $selected) {
	if ($default)
		$tag = option_tag('', $selected, $default);

	$tag .= option_tag('0', $selected, '配信停止')
				. option_tag('1', $selected, '配信中');

	return $tag;
}

// ナビページ公開フラグ
function select_navi_open_flag($default, $selected) {
	if ($default)
		$tag = option_tag('', $selected, $default);

	$tag .= option_tag('0', $selected, '未公開')
				. option_tag('1', $selected, '公開中')
				. option_tag('2', $selected, '申請中');

	return $tag;
}

// Blog/ML/メルマガ公開フラグ
function select_open_status($default, $selected) {
	if ($default)
		$tag = option_tag('', $selected, $default);

	$tag .= option_tag('0', $selected, '非公開')
				. option_tag('1', $selected, '公開中');

	return $tag;
}

// ワイワイ会議
function select_room($default, $selected) {
	if ($default)
		$tag = option_tag('', $selected, $default);

	$sql = "SELECT rom_room_id, rom_name FROM t_room WHERE rom_status IN (1,2) ORDER BY rom_room_id";
	$result = db_exec($sql);
	$nrow = pg_numrows($result);
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);
		$tag .= option_tag($fetch->rom_room_id, $selected, htmlspecialchars($fetch->rom_name));
	}

	return $tag;
}

// pickup
function select_pickup($default, $selected) {
	if ($default)
		$tag = option_tag('', $selected, $default);

	$tag .= option_tag('1', $selected, 'pickup');

	return $tag;
}

// ナビセレクト
function select_naviselect($default, $selected) {
	if ($default)
		$tag = option_tag('', $selected, $default);

	$tag .= option_tag('1', $selected, 'ナビセレクト');

	return $tag;
}

// 事務局チェック状態
function select_admin_check($default, $selected) {
	if ($default)
		$tag = option_tag('', $selected, $default);

	$tag .= option_tag('1', $selected, '未チェック')
				. option_tag('2', $selected, '問題なし')
				. option_tag('3', $selected, '問題あり');

	return $tag;
}

// パトロール隊チェック状態
function select_patrol_check($default, $selected) {
	return select_admin_check($default, $selected);
}

// 指定数以上
function select_over_count($default, $selected) {
	if ($default)
		$tag = option_tag('', $selected, $default);

	$tag .= option_tag('0', $selected, '0');
	$tag .= option_tag('1-', $selected, '1-');
	$tag .= option_tag('5-', $selected, '5-');
	$tag .= option_tag('10-', $selected, '10-');
	$tag .= option_tag('20-', $selected, '20-');
	$tag .= option_tag('50-', $selected, '50-');
	$tag .= option_tag('100-', $selected, '100-');
	$tag .= option_tag('200-', $selected, '200-');
	$tag .= option_tag('500-', $selected, '500-');
	$tag .= option_tag('1000-', $selected, '1000-');

	return $tag;
}

// メルマガ配信ステータス
function select_melmaga_send_status($default, $selected) {
	if ($default)
		$tag = option_tag('', $selected, $default);

	$tag .= option_tag('1', $selected, '未配信')
				. option_tag('2', $selected, '配信済');

	return $tag;
}

// 投稿種別（本文/コメント/トラックバック）
function select_remark_kind($default, $selected) {
	if ($default)
		$tag = option_tag('', $selected, $default);

	$tag .= option_tag('1', $selected, '本文')
				. option_tag('3', $selected, 'トラックバック')
				. option_tag('2', $selected, 'コメント');

	return $tag;
}

// NGワード
function select_ngword($default, $selected) {
	if ($default)
		$tag = option_tag('', $selected, $default);

	$tag .= option_tag('1', $selected, 'NG無し')
				. option_tag('2', $selected, 'NG有り');

	return $tag;
}

// ナビページ名
function select_navi_page_title($default, $selected, $channel_id = '') {
	if ($default)
		$tag = option_tag('', $selected, $default);

	$sql = "SELECT nvp_navi_page_id, nvp_title"
			. " FROM t_navi_page"
			. " WHERE nvp_status = 1 AND nvp_open_flag=1" . ($channel_id ? " AND nvp_channel_id=$channel_id" : '')
			. " ORDER BY nvp_navi_page_id";
	$result = db_exec($sql);
	$nrow = pg_numrows($result);
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);
		$tag .= option_tag($fetch->nvp_navi_page_id, $selected, htmlspecialchars($fetch->nvp_title));
	}

	return $tag;
}

// ワイワイ会議ステータス
function select_room_status($default, $selected) {
	if ($default)
		$tag = option_tag('', $selected, $default);

	$tag .= option_tag('1', $selected, '公開')
				. option_tag('2', $selected, '非公開')
				. option_tag('9', $selected, '閉鎖');

	return $tag;
}

?>