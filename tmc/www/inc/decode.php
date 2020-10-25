<?
/******************************************************
' System :「おクルマ選び お手伝いサービス」事務局用ページ
' Content:デコード関数
'******************************************************/

// ユーザステータス
function decode_user_status($code) {
	switch ($code) {
	case 1:
		return '初期フォロー中';
	case 2:
		return '継続リレーション中';
	case 3:
		return '引継ぎ済み';
	case 7:
		return 'メール不達';
	case 8:
		return 'メール購読解除';
	case 9:
		return '終了済み';
	case '':
		return '未設定';
	}
	return "不明($code)";
}

// 応募種別
function decode_oubo_shubetsu($code) {
	switch ($code) {
	case 'C':
		return 'クローズ';
	case 'P':
		return 'はがき';
	case 'W':
		return 'インターネット';
	case 'K':
		return 'ケータイ';
	case '':
		return '未設定';
	}
	return "不明($code)";
}

// 性別
function decode_sex_cd($code) {
	switch ($code) {
	case '1':
		return '男性';
	case '2':
		return '女性';
	case '3':
		return '法人';
	case '':
		return '未設定';
	}
	return "不明($code)";
}

// 自社他社区分
function decode_jisha_tasha_kubun($code) {
	switch ($code) {
	case '1':
		return '自社';
	case '':
		return '他社客・不明';
	}
	return "不明($code)";
}

// フォロー区分
function decode_follow_kubun($code) {
	switch ($code) {
	case '1':
		return 'スタッフ';
	case '2':
		return 'TEL';
	case '3':
		return 'TEL+Mail';
	case '4':
		return 'Mail';
	case '':
		return '不明';
	}
	return "不明($code)";
}

// 車両メーカ
function decode_car_maker($code) {
	switch ($code) {
	case '01':
		return 'トヨタ';
	case '02':
		return '日産';
	case '03':
		return 'ホンダ';
	case '04':
		return '三菱';
	case '05':
		return 'マツダ';
	case '06':
		return 'スバル';
	case '07':
		return 'イスズ';
	case '08':
		return 'ダイハツ';
	case '09':
		return 'スズキ';
	case '10':
		return '輸入車';
	case '99':
		return 'その他';
	case '':
		return '未設定';
	}
	return "不明($code)";
}

// 購入販売店種別
function decode_dealer_type($code) {
	switch ($code) {
	case '1':
		return 'トヨタ販売店';
	case '2':
		return 'トヨタ以外販売店';
	case '3':
		return '不明';
	case '':
		return '未設定';
	}
	return "不明($code)";
}

// 来店目的
function decode_raiten_mokuteki($code) {
	switch ($code) {
	case '1':
		return '車を見に来た';
	case '2':
		return '点検・整備';
	case '3':
		return 'その他';
	case '':
		return '未設定';
	}
	return "不明($code)";
}

// キャンペーン認知
function decode_campaign_ninchi($code) {
	$len = strlen($code);
	for ($i = 0; $i < $len; $i++) {
		if ($code{$i} == '1') {
			if ($str)
				$str .= '，';

			switch ($i + 1) {
			case 1:
				$str .= 'ＴＶ';
				break;
			case 2:
				$str .= '新聞';
				break;
			case 3:
				$str .= '雑誌';
				break;
			case 4:
				$str .= 'チラシ';
				break;
			case 5:
				$str .= 'ＤＭ';
				break;
			case 6:
				$str .= 'インターネット';
				break;
			case 7:
				$str .= 'ケータイ';
				break;
			case 8:
				$str .= 'お店で';
				break;
			case 9:
				$str .= 'その他';
				break;
			case 10:
				$str .= '予備';
				break;
			}
		}
	}
	return $str;
}

// 情報提供許諾
function decode_jouhou_teikyou_kyodaku($code) {
	switch ($code) {
	case '1':
		return '希望しない';
	case '':
		return '未設定';
	}
	return "不明($code)";
}

// 権限
function decode_privilege($code) {
	switch ($code) {
	case PRIV_DHSTAFF:
		return 'DHスタッフ';
	case PRIV_OPERATOR:
		return 'オペレータ';
	case '':
		return '未設定';
	}
	return '不明';
}

// スタッフステータス
function decode_staff_status($code) {
	switch ($code) {
	case 0:
		return '有効';
	case 9:
		return '無効';
	case '':
		return '未設定';
	}
	return "不明($code)";
}

// コミュニケーション種別
function decode_comm_type($code) {
	switch ($code) {
	case 1:
		return '販売店メール';
	case 2:
		return '初期フォローメール';
	case 3:
		return '継続リレーションメール';
	case 4:
		return 'OneToOneメール';
	case 5:
		return '問い合わせメール';
	case '':
		return '未設定';
	}
	return "不明($code)";
}

// コミュニケーション種別（短縮形）
function decode_comm_type2($code) {
	switch ($code) {
	case 1:
		return '販売店';
	case 2:
		return '初期';
	case 3:
		return '継続';
	case 4:
		return '1to1';
	case 5:
		return '問合せ';
	case '':
		return '未設定';
	}
	return "不明($code)";
}

// 送受信
function decode_send_recv($code) {
	switch ($code) {
	case 'R':
		return '受信';
	case 'S':
		return '送信';
	case '':
		return '未設定';
	}
	return "不明($code)";
}

// 販売店
function decode_dealer($code) {
	$sql = "SELECT dlr_dealer_name FROM t_dealer WHERE dlr_dealer_cd='$code'";
	$result = db_exec($sql);
	return pg_fetch_result($result, 0, 0);
}

// 車種
function decode_car_model($code) {
	$sql = "SELECT crm_car_model_name FROM t_car_model WHERE crm_car_model_cd=$code";
	$result = db_exec($sql);
	return pg_fetch_result($result, 0, 0);
}

// 車種カテゴリ
function decode_car_category($code) {
	$sql = "SELECT crc_car_category_name FROM t_car_category WHERE crc_car_category_cd=$code";
	$result = db_exec($sql);
	return pg_fetch_result($result, 0, 0);
}
?>