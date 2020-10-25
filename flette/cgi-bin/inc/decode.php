<?
/******************************************************
' System :fletteスタッフページ
' Content:デコード処理
'******************************************************/

// スタッフ権限
function decode_staff_auth($code, $def='設定なし') {
	switch ($code) {
	case 1:
		return '管理者スタッフ';
	case 2:
		return '店舗スタッフ';
	}
	return $def;
}

// 性別
function decode_sex($code, $def='設定なし') {
	switch ($code) {
	case 1:
		return '男性';
	case 2:
		return '女性';
	case 3:
		return '特に希望しない';
	}
	return $def;
}

// カウンセラー変更希望
function decode_change($code) {
	if ($code)
		return '別のカウンセラーを希望する';
	else
		return '前回と同じカウンセラーを希望する';
}

// 部屋ステータス
function decode_room_status($code, $def='設定なし') {
	switch ($code) {
	case 1:
		return '有効';
	case 9:
		return '無効';
	}
	return $def;
}

// 店舗名
function decode_shop($code, $def='設定なし') {
	$sql = "SELECT sh_shop_name FROM m_shop WHERE sh_shop_id=" . sql_number($code);
	return db_fetch1($sql, $def);
}

// 部屋名
function decode_room($code, $def='設定なし') {
	$sql = "SELECT rm_room_name FROM m_room WHERE rm_room_id=" . sql_number($code);
	return db_fetch1($sql, $def);
}

// コース名
function decode_course($code, $def='設定なし') {
	$sql = "SELECT co_course_name FROM m_course WHERE co_course_id=" . sql_number($code);
	return db_fetch1($sql, $def);
}

// 曜日
function decode_week($code, $def='設定なし') {
	$week = array('日', '月', '火', '水', '木', '金', '土');
	if ($code >= 0 && $code < 7)
		return $week[$code];
	return $def;
}

// 希望連絡時間帯
function decode_contact_time($code, $def='') {
	switch ($code) {
	case 1:
		return '特に希望なし';
	case 2:
		return '午前中（10:00〜12:00）';
	case 3:
		return '午後（12:00〜17:00）';
	case 4:
		return '夜（17:00〜21:00）';
	case 5:
		return 'その他';
	}
	return $def;
}

// 問い合わせ件名
function decode_inquiry_subject($code, $def='設定なし') {
	$sql = "SELECT iq_inquiry_text FROM m_inquiry WHERE iq_inquiry_id=" . sql_number($code);
	return db_fetch1($sql, $def);
}

// スタッフステータス
function decode_staff_status($code, $def='') {
	switch ($code) {
	case 1:
		return '有効';
	case 9:
		return '無効';
	}
	return $def;
}
?>