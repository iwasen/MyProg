<?php
/**
 * ひとびと･net 共通ファイル
 *
 * デコード処理
 *
 *
 * @package
 * @author
 * @version
 */

// ひとびと便り承認
function decode_letter_status1($code, $def='') {
	switch ($code) {
	case '1':
	case '2':
		return '未配信';
	case '3':
		return '配信中';
	case '4':
		return '配信済';
	}
	return $def;
}

// ひとびと便り発信状態
function decode_letter_status2($code) {
	if ($code == '1')
		return '未承認';
	else
		return '承認';
}

// ひとびと便りバックナンバー
function decode_backnumber($code, $def='') {
	switch($code) {
	case '0':
		return '非掲載';
	case '1':
		return '掲載';
	}
	return $def;
}

// ナビ応募ステータス
function decode_navi_page_status($code, $def='') {
	switch($code) {
	case '0':
		return '未承認';
	case '1':
		return '承認済';
	case '2':
		return 'お断り';
	}
	return $def;
}

// ナビコンテンツチェックフラグ
function decode_navi_contents_check($code, $def='') {
	switch($code) {
	case '0':
		return '未申請';
	case '1':
		return '申請中';
	case '2':
		return '差戻し';
	case '3':
		return '承認済';
	}
	return $def;
}

// ナビページ公開フラグ
function decode_navi_open_flag($code, $def='') {
	switch($code) {
	case '0':
		return '未申請';
	case '1':
		return '公開中';
	case '2':
		return '申請中';
	case '9':
		return '閉鎖';
	}
	return $def;
}

// ナビページ種別
function decode_navi_page_type($code, $def='') {
	switch($code) {
	case '1':
		return 'Blog+ML';
	case '2':
		return 'Blogのみ';
	}
	return $def;
}

// チャネル
function decode_channel($code, $def='') {
	if ($code != '') {
		$sql = "SELECT chn_cnannel_name FROM m_channel WHERE chn_channel_id=$code";
		$name = db_fetch1($sql);
	}
	return $name;
}

// サブチャネル
function decode_sub_channel($code, $def='') {
	if ($code != '') {
		$sql = "SELECT sch_subchannel_name FROM m_sub_channel WHERE sch_subchannel_id=$code";
		$name = db_fetch1($sql);
	}
	return $name;
}

// 有無（汎用）
function decode_umu($code, $def='') {
	switch ($code) {
	case '0':
		return 'なし';
	case '1':
		return 'あり';
	}
	return $def;
}

// 公開ステータス
function decode_open_status($code, $def='') {
	switch($code) {
	case '0':
		return '非公開';
	case '1':
		return '公開中';
	}
	return $def;
}

// メンバーステータス
function decode_member_status($code, $def = '') {
	switch ($code) {
	case '0':
		return '仮登録';
	case '1':
		return '有効';
	case '2':
		return '不達状態';
	case '3':
		return '一時停止';
	case '9':
		return '退会';
	}
	return $def;
}

// メンバーMLステータス
function decode_member_ml_status($code, $def = '') {
	switch ($code) {
	case '1':
	case '2':
		return 'ゲスト登録';
	case '3':
		return '本登録';
	case '9':
		return '退会';
	}
	return $def;
}

// ひとびと便り配信状態
function decode_hitobito_letter($code, $def = '') {
	switch ($code) {
	case '0':
		return '配信停止';
	case '1':
		return '配信中';
	}
	return $def;
}

// 管理者名
function decode_admin_name($code, $def = '') {
	if ($code != '') {
		$sql = "SELECT adm_name1 ||' '|| adm_name2 FROM m_admin WHERE adm_admin_id=$code";
		$name = db_fetch1($sql);
	}
	return $name;
}

// ナビ名
function decode_navi_name($code, $def = '') {
	if ($code != '') {
		$sql = "SELECT trim(nav_name1||' '||COALESCE(nav_name2,'')) FROM t_navi WHERE nav_navi_id=$code";
		$name = db_fetch1($sql);
	}
	return $name;
}

// パトロール隊名
function decode_patrol_name($code, $def = '') {
	if ($code != '') {
		$sql = "SELECT ptl_name1 ||' '|| ptl_name2 FROM m_patrol WHERE ptl_patrol_id=$code";
		$name = db_fetch1($sql);
	}
	return $name;
}

// パトロール隊チェック選択肢
function decode_patrol_check($code, $def = '') {
	switch ($code) {
	case '1':
		return '未チェック';
	case '2':
		return '問題なし';
	case '3':
		return '問題あり';
	case '4':
		return '要チェック';
	}
	return $def;
}

// 事務局チェック選択肢
function decode_admin_check($code, $def = '') {
	switch ($code) {
	case '1':
		return '未チェック';
	case '2':
		return '問題なし';
	case '3':
		return '問題あり';
	}
	return $def;
}

// ナビおすすめリンクカテゴリ
function decode_navi_osusume_link($code, $def = '') {
	if ($code != '') {
		$sql = "SELECT lct_category_name FROM m_link_category WHERE lct_link_category_id=$code";
		$name = db_fetch1($sql);
	}
	return $name;
}

// Pickup
function decode_pickup($code, $def = '') {
	if ($code == 1)
		return 'pickup';
	else
		return $def;
}

// ナビセレクト
function decode_navi_select($code, $def = '') {
	if ($code == 1)
		return 'ナビセレクト';
	else
		return $def;
}

// 項目種別
function decode_contents_kind($code, $def = '') {
	switch($code) {
	case 1:
		return '本文';
	case 2:
		return 'トラックバック';
	case 3:
		return 'コメント';
	default:
		return $def;
	}
}

// ナビページ名
function decode_navi_page_name($code, $def = '') {
	if ($code != '') {
		$sql = "SELECT nvp_title FROM t_navi_page WHERE nvp_navi_page_id=$code";
		$name = db_fetch1($sql);
	}
	return $name;
}

// 問い合わせ内容デコード
function decode_inquiry_type($code, $def = '') {
	switch ($code) {
	case '1':
		return '記事について';
	case '2':
		return 'ワイワイ会議について';
	case '3':
		return '仕事の依頼';
	case '4':
		return '取材の依頼';
	case '9':
		return 'その他';
	}
	return $def;
}

// ナビページステータス
function decode_navi_page_status2($status, $open_flag, $def = '') {
	switch ($status) {
	case '0':
		return '未承認';
	case '1':
		switch ($open_flag) {
		case '0':
			return '未公開';
		case '1':
			return '公開中';
		case '2':
			return '公開申請中';
		}
		break;
	case '2':
		return 'お断り';
	default:
		return '閉鎖';
	}
	return $def;
}

// ワイワイ会議ステータス
function decode_room_status($code, $def='') {
	switch($code) {
	case '1':
		return '公開';
	case '2':
		return '非公開';
	case '9':
		return '閉鎖';
	}
	return $def;
}

// 性別デコード
function decode_sex_cd($code, $def='') {
	if ($code != '') {
		if ($code == 1)
			$name = '男性';
		else
			$name = '女性';
	}
	return $name;
}

// 郵便番号デコード
function decode_zip_cd($code, $def='') {
	if ($code != '') {
		$name = substr($code, 0, 3) . '-' . substr($code, 3, 4);
	}
	return $name;
}

// 都道府県デコード
function decode_address1($code, $def='') {
	if ($code != '') {
		$sql = "SELECT tdf_name FROM m_todofuken WHERE tdf_area_id=$code";
		$name = db_fetch1($sql);
	}
	return $name;
}
?>