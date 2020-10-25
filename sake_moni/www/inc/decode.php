<?
/******************************************************
' System :お酒と買物のアンケートモニター共通
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

// モニター種別
function decode_net_kind($code, $def='設定なし') {
	switch ($code) {
	case 1:
		return 'きかせて・net';
	case 2:
		return 'iMiネット';
	}
	return $def;
}

// モニター状態
function decode_monitor_status($code, $def='設定なし') {
	switch ($code) {
	case 0:
		return '有効';
	case 2:
		return '不達状態';
	case 8:
		return 'テスト';
	case 9:
		return '退会';
	}
	return $def;
}

// スタッフ種別
function decode_staff_type($code, $def='設定なし') {
	switch ($code) {
	case 1:
		return 'メールニュース登録';
	case 2:
		return '無料登録';
	case 3:
		return 'Myページ登録';
	case 4:
		return 'アシスタント';
	}
	return $def;
}

// スタッフ状態
function decode_staff_status($code, $def='設定なし') {
	switch ($code) {
	case 0:
		return '有効';
	case 2:
		return '不達状態';
	case 8:
		return 'テスト';
	case 9:
		return '退会';
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

// 子供の有無
function decode_child($code, $def='設定なし') {
	switch ($code) {
	case 1:
		return '有り';
	case 2:
		return '無し';
	}
	return $def;
}

// 関連調査への参加
function decode_research($code, $def='設定なし') {
	switch ($code) {
	case 1:
		return '可能';
	case 2:
		return '不可能';
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

// 地域
function decode_area($code) {
	if ($code != '') {
		$sql = "SELECT ar_area_name FROM m_area WHERE ar_area_cd=$code";
		$name = db_fetch1($sql);
	}
	return $name;
}

// 職業
function decode_shokugyou($code) {
	if ($code != '') {
		$sql = "SELECT sg_shokugyou_name FROM m_shokugyou WHERE sg_shokugyou_cd=$code";
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

// アンケート種別
function decode_enq_type($code, $def='不明') {
	switch ($code) {
	case '0':
		return 'アンケート無し';
	case '1':
		return 'メールアンケート';
	case '2':
		return 'Ｗｅｂアンケート';
	}
	return $def;
}

// アンケート種別（短縮）
function decode_enq_type2($code, $def='不明') {
	switch ($code) {
	case '0':
		return '無し';
	case '1':
		return 'メール';
	case '2':
		return 'Ｗｅｂ';
	}
	return $def;
}

// アンケート状態
function decode_enq_status($code, $def='不明') {
	switch ($code) {
	case 0:
		return '作成中';
	case 1:
		return '作成完了';
	case 2:
		return '申請中';
	case 3:
		return '事務局チェック中';
	case 4:
		return '承認済み';
	case 5:
		return '実施中';
	case 6:
		return '一時停止';
	case 7:
		return '終了';
	case 8:
		return '再申請';
	case 9:
		return '削除済み';
	}
	return $def;
}

// 謝礼支払方法
function decode_payment_type($code, $def='') {
	switch ($code) {
	case 1:
		return 'iMiポイント';
	case 2:
		return '郵便為替';
	case 3:
		return 'ネットバンク';
	}
	return $def;
}

// 業態
function decode_gyoutai($code) {
	if ($code != '') {
		$sql = "SELECT gt_gyoutai_name FROM m_gyoutai WHERE gt_gyoutai_cd=$code";
		$name = db_fetch1($sql);
	}
	return $name;
}

// チェーンエリア
function decode_chain_area($code) {
	if ($code != '') {
		$sql = "SELECT ca_area_name FROM m_chain_area WHERE ca_chain_area_cd=$code";
		$name = db_fetch1($sql);
	}
	return $name;
}

// エリア名（カンマ区切りチェーンコード）
function decode_chain2area($chain_str){
	if ($chain_str){
		$chain_ary = explode(',', $chain_str);

		foreach($chain_ary as $val){
			$sql = "SELECT ch_chain_area_cd FROM m_chain WHERE ch_chain_cd=$val";
			$c_area_cd = db_fetch1($sql);
			$c_area_ary[] = decode_chain_area($c_area_cd);
		}
		$c_area_ary = array_unique($c_area_ary);
		$c_area_str = implode(',', $c_area_ary);
		return $c_area_str;
	}
}

// 業態名（カンマ区切りチェーンコード）
function decode_chain2gyoutai($chain_str){
	if ($chain_str){
		$chain_ary = explode(',', $chain_str);

		foreach($chain_ary as $val){
			$sql = "SELECT ch_gyoutai_cd FROM m_chain WHERE ch_chain_cd=$val";
			$g_cd = db_fetch1($sql);
			$g_ary[] = decode_gyoutai($g_cd);
		}
		$g_ary = array_unique($g_ary);
		$g_str = implode(',', $g_ary);
		return $g_str;
	}
}

// チェーン名（カンマ区切りチェーンコード）
function decode_chain($chain_str){
	if ($chain_str){
		$chain_ary = explode(',', $chain_str);

		foreach($chain_ary as $val){
			$sql = "SELECT ch_name FROM m_chain WHERE ch_chain_cd=$val";
			$ch_ary[] = db_fetch1($sql);
		}
		$ch_ary = array_unique($ch_ary);
		$ch_str = implode(',', $ch_ary);
		return $ch_str;
	}

}
?>