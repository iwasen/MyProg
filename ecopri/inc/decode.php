<?
/******************************************************
' System :Eco-footprint 共通
' Content:デコード処理
'******************************************************/

// adminステータス
function decode_admin_status($code, $def='設定なし') {
	switch ($code) {
	case 0:
		return '有効';
	case 9:
		return '無効';
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
	}
	return $def;
}

// 未既婚
function decode_mikikon($code, $def='設定なし') {
	switch ($code) {
	case 1:
		return '未婚';
	case 2:
		return '既婚';
	}
	return $def;
}

// AND/OR
function decode_andor($code, $def='設定なし') {
	switch ($code) {
	case 'A':
		return 'AND';
	case 'O':
		return 'OR';
	}
	return $def;
}
// 地域（北海道、東北･･･）
function decode_area2($code) {
	if ($code != '') {
		$sql = "SELECT aa_area2_name FROM m_area2 WHERE aa_area2_cd=$code";
		$name = db_fetch1($sql);
	}
	return $name;
}

// 都道府県
function decode_area($code) {
	if ($code != '') {
		$sql = "SELECT ar_area_name FROM m_area WHERE ar_area_cd=$code";
		$name = db_fetch1($sql);
	}
	return $name;
}

// 都道府県コードから地域（北海道、東北･･･）
function decode_area3($code) {
	if ($code != '') {
		$sql = "SELECT aa_area2_name FROM m_area2 JOIN m_area ON aa_area2_cd=ar_area2_cd WHERE ar_area_cd=$code";
		$name = db_fetch1($sql);
	}
	return $name;
}

// 家族続柄
function decode_family_rel($code) {
	if ($code != '') {
		$sql = "SELECT fr_name FROM m_family_rel WHERE fr_family_rel_cd=$code";
		$name = db_fetch1($sql);
	}
	return $name;
}

// 住居形態
function decode_keitai($code) {
	if ($code != '') {
		$sql = "SELECT kt_keitai_name FROM m_keitai WHERE kt_keitai_cd=$code";
		$name = db_fetch1($sql);
	}
	return $name;
}

// 住居素材
function decode_sozai($code) {
	if ($code != '') {
		$sql = "SELECT sz_sozai_name FROM m_sozai WHERE sz_sozai_cd=$code";
		$name = db_fetch1($sql);
	}
	return $name;
}

// 建設年次
function decode_chikunen($code) {
	if ($code != '') {
		$sql = "SELECT cn_chikunen_text FROM m_chikunen WHERE cn_chikunen_cd=$code";
		$name = db_fetch1($sql);
	} else {
		$name = '未設定';
	}
	return $name;
}

// 居住面積
function decode_space($code) {
	if ($code != '') {
		$sql = "SELECT sp_space_text FROM m_space WHERE sp_space_cd=$code";
		$name = db_fetch1($sql);
	}
	return $name;
}

// 給湯器タイプ
function decode_boiler($code) {
	if ($code != '') {
		$sql = "SELECT bt_boiler_name FROM m_boiler_type WHERE bt_boiler_type_cd=$code";
		$name = db_fetch1($sql);
	}
	return $name;
}

// ガス種
function decode_gas_kind($code) {
	if ($code != '') {
		$sql = "SELECT gk_gas_kind_name FROM m_gas_kind WHERE gk_gas_kind_cd=$code";
		$name = db_fetch1($sql);
	}
	return $name;
}

// ガスタイプ
function decode_gas_type($code) {
	if ($code != '') {
		$sql = "SELECT gt_gas_type_name FROM m_gas_type WHERE gt_gas_type_cd=$code";
		$name = db_fetch1($sql);
	}
	return $name;
}

// 水道料金請求月
function decode_water($code) {
	switch ($code) {
	case 1:
		return '奇数月';
	case 2:
		return '偶数月';
	}
	return $def;
}

// エネルギー分類コード
function decode_energy($code) {
	if ($code != '') {
		$sql = "SELECT en_text FROM m_energy WHERE en_class_cd=$code";
		$name = db_fetch1($sql);
	}
	return $name;
}

// 登録状態
function decode_regist($code, $def='不明') {
	switch ($code) {
	case 1:
		return '未登録';
	case 2:
		return '登録済み';
	}
	return $def;
}

// 家族カテゴリ
function decode_family_ctg($code) {
	if ($code != '') {
		$sql = "SELECT fc_text FROM m_family_ctg WHERE fc_family_ctg_cd=$code";
		$name = db_fetch1($sql);
	}
	return $name;
}

// 間取り
function decode_room($code) {
	if ($code != '') {
		$sql = "SELECT rm_room_name FROM m_room WHERE rm_room_cd=$code";
		$name = db_fetch1($sql);
	} else
		$name = '未設定';
	return $name;
}

// 入力設定
function decode_input($code) {
	if ($code == '')
		$def = '未設定';
	elseif ($code == 1)
		$def = '入力する';
	elseif ($code == '0')
		$def = '入力しない';
	return $def;
}

// エネルギー
function deocde_eng($code) {
	if ($code == 1)
		$def = '電気';
	elseif ($code == 2)
		$def = 'ガス';
	elseif ($code == 3)
		$def = '水道';
	elseif ($code == 4)
		$def = 'ゴミ';
	elseif ($code == 5)
		$def = 'ガソリン';
	elseif ($code == 6)
		$def = '灯油';
	return $def;
}

// 分類
function decode_bunrui($code) {
	if ($code == 1)
		$def = '衛生';
	elseif ($code == 2)
		$def = '娯楽';
	elseif ($code == 3)
		$def = '家事';
	elseif ($code == 4)
		$def = '';
	elseif ($code == 5)
		$def = '快適空間';
	elseif ($code == 6)
		$def = '交通';
	return $def;
}

// 退会理由
function decode_taikai($code) {
	if ($code != '') {
		$sql = "SELECT ta_reason FROM m_taikai WHERE ta_taikai_cd=$code";
		$name = db_fetch1($sql);
	} else
		$name = '不明';
	return $name;
}

?>