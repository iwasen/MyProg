<?
/******************************************************
' System :ICP共通インクルードファイル
' Content:デコード関数
'******************************************************/

// 会議室タイプ
function decode_room_type($code, $default = '未設定') {
	switch ($code) {
	case '1':
		return 'ネットグルイン';
	case '2':
		return '定性メールアンケート';
	case '3':
		return 'バックルーム';
	case '':
		return $default;
	}
	return "不明($code)";
}

// オペレータステータス
function decode_operator_status($code, $default = '未設定') {
	switch ($code) {
	case '1':
		return '有効';
	case '9':
		return '無効';
	}
	return "不明($code)";
}

// プロジェクトステータス
function decode_pjt_status($code, $default = '未設定') {
	switch ($code) {
	case '1':
		return '稼動中';
	case '9':
		return '終了';
	}
	return "不明($code)";
}

// ジョブステータス
function decode_job_status($code, $default = '未設定') {
	switch ($code) {
	case '1':
		return '申請前';
	case '2':
		return '申請中';
	case '3':
		return '承認済み';
	case '4':
		return '待機中';
	case '5':
		return '実施中';
	case '6':
		return '休止中';
	case '7':
		return '再申請猶予期間';
	case '9':
		return '終了';
	}
	return "不明($code)";
}

// 組織ステータス
function decode_org_status($code, $default = '未設定') {
	switch ($code) {
	case '1':
		return '有効';
	case '9':
		return '無効';
	}
	return "不明($code)";
}

// 組織タイプ
function decode_org_type($code, $default = '未設定') {
	switch ($code) {
	case '1':
		return '企業';
	case '2':
		return 'ネットワーク';
	case '3':
		return 'その他';
	case '';
		return $default;
	}
	return "不明($code)";
}

// 案内・開始・終了メールステータス
function decode_mail_status($code, $default = '未設定') {
	switch ($code) {
	case 0:
		return '未設定';
	case 1:
		return '作成中';
	case 2:
		return '作成完了';
	case 3:
		return 'テスト配信中';
	case 9:
		return '配信終了';
	}
	return "不明($code)";
}
?>