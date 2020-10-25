<?
/******************************************************
' System :「おクルマ選び お手伝いサービス」販売店様用ページ
' Content:メール表示関係includeファイル
'******************************************************/

// メール種別表示
function mail_type($comm_type, $send_recv) {
	if ($send_recv == 'R')
		return '1to1';
	else {
		switch ($comm_type) {
		case 1:
			return '販';
		case 2:
			return '初';
		case 3:
			return '継';
		case 4:
			return '1to1';
		case 5:
			return '問';
		}
	}
}

// メール種別表示
function mail_type2($comm_type, $send_recv) {
	if ($send_recv == 'R')
		return '1to1';
	else {
		switch ($comm_type) {
		case 1:
			return '販売';
		case 2:
			return '初期';
		case 3:
			return '継続';
		case 4:
			return '1to1';
		case 5:
			return '問い';
		}
	}
}

// 送信者表示
function send_name($comm_type, $send_recv, $user_name, $operator_name) {
	if ($send_recv == 'R')
		return $user_name . '　様';
	else {
		if ($comm_type == 4)
			return 'オペレータ　' . $operator_name;
		else
			return '事務局';
	}
}

// シーケンス番号取得
function get_seq_no($user_id) {
	$sql = "SELECT cml_comm_id FROM t_comm_log WHERE cml_user_id=$user_id ORDER BY cml_comm_id";
	$result = db_exec($sql);
	$nrow = pg_num_rows($result);
	for ($i = 0; $i < $nrow; $i++)
		$seq_ary[pg_fetch_result($result, $i, 0)] = sprintf('%03d', $i + 1);

	return $seq_ary;
}
?>