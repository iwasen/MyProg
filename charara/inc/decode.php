<?
/******************************************************
' System :「きゃららFactory」インクルードファイル
' Content:デコード処理
'******************************************************/

// 性別
function decode_sex($code) {
	if ($code != '') {
		if ($code == 1)
			return '男性';
		else
			return '女性';
	}
}

// スタッフ所属種別
function decode_staff_kind($code) {
	if ($code != '') {
		$sql = "SELECT sk_name FROM m_staff_kind WHERE sk_kind_cd=$code";
		$name = db_fetch1($sql);
	}
	return $name;
}

// スタッフ所属
function decode_staff_org($code) {
	if ($code != '') {
		$sql = "SELECT so_name FROM m_staff_org WHERE so_org_cd=$code";
		$name = db_fetch1($sql);
	}
	return $name;
}

// スタッフ名
function decode_staff_name($code) {
	if ($code != '') {
		$sql = "SELECT (st_name1 || st_name2) AS name FROM m_staff WHERE st_staff_id=$code";
		$name = db_fetch1($sql);
	}
	return $name;
}

// 課金サーバ
function decode_ac_server($id) {
	if ($id != '') {
		$sql = "SELECT as_name FROM m_account_server WHERE as_server_id='$id'";
		$name = db_fetch1($sql);
	}
	return $name;
}

// 商品名
function decode_product($code) {
	if ($code != '') {
		$sql = "SELECT pd_name AS name FROM m_product WHERE pd_product_cd='$code'";
		$name = db_fetch1($sql);
	}
	return $name;
}

// スタッフメールアドレス
function decode_staff_mail($code) {
	if ($code != '') {
		$sql = "SELECT st_mail_addr FROM m_staff WHERE st_staff_id=$code";
		$name = db_fetch1($sql);
	}
	return $name;
}
?>