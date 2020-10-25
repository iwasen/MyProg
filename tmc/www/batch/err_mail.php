<?
/******************************************************
' System :「おクルマ選び お手伝いサービス」バッチ処理
' Content:不達メール処理
'******************************************************/

$inc = "../inc";
include("$inc/database.php");
include("$inc/encrypt.php");
include("$inc/batch.php");
include("pop3.php");

define('MAX_ERR_COUNT', 3);

// 二重起動チェック
if (!dup_check('lock_err_mail'))
	exit;

// 最大実行時間（３分）
set_time_limit(180);

// 不達メール受信処理
$count = recv_err_mail();

// バッチログ書き込み
if ($count > 0) {
	$desc = "不達メールを{$count}件受信しました。";
	batch_log('0', __FILE__, $desc);
}
exit;

// 不達メール受信処理
function recv_err_mail() {
	// システム情報取得
	$server = get_system_info('server_pop3');
	$account = explode('/', get_system_info('account_err_mail'), 2);

	// エラーメール受信
	$mail = new mail_pop3();
	$num_msg = $mail->open($server, $account[0], $account[1]);
	if (!$num_msg)
		return 0;

	$count = 0;
	$num_msg = min($num_msg, 500);
	for ($i = 1; $i <= $num_msg; $i++) {
		// メール本文取得
		$body = $mail->get_body($i);

		$warning = false;

		// 不達以外を除く
		if (strpos($body, '(warning-timeout)') !== false)
			$warning = true;

		if (!$warning) {
			// 返送されたメールヘッダを解析
			$header = imap_rfc822_parse_headers($body);

			// fromアドレスをチェック
			if (is_array($header->from)) {
				foreach ($header->from as $from) {
					$pos = strpos($from->mailbox, '-');
					if ($pos) {
						// 識別コードがあったら不達メール処理を行う
						err_mail_proc(substr($from->mailbox, $pos + 1), $body);
						$count++;
						break;
					}
				}
			}
		}

		// メールサーバ上のメールを削除
		$mail->delete($i);
	}
	$mail->close(true);

	return $count;
}

// エラーメール処理
function err_mail_proc($code, &$body) {
	$code2 = substr($code, 1);

	switch ($code{0}) {
	case 'C':
		err_mail_comm_id($code2, $body);
		break;
	case 'L':
		err_mail_login_id($code2);
		break;
	}
}

// コミュニケーション履歴IDによるエラーメール処理
function err_mail_comm_id($code, &$body) {
	// コミュニケーション履歴IDを取得
	$comm_id = decrypt_id($code);
	if (!$comm_id)
		return;

	// コミュニケーション履歴に不達フラグをセット
	$rec['cml_err_mail_flag'] = 'true';
	db_update('t_comm_log', $rec, "cml_comm_id=$comm_id");

	// ユーザIDを取得
	$sql = "SELECT cml_user_id FROM t_comm_log WHERE cml_comm_id=$comm_id AND cml_user_id IS NOT NULL";
	$user_id = db_fetch1($sql);
	if ($user_id) {
		// 不達回数を取得
		$sql = "SELECT uem_cont_count,uem_comm_id FROM t_user_errmail WHERE uem_user_id=$user_id";
		$result = db_exec($sql);
		$nrow = pg_num_rows($result);
		if ($nrow) {
			$fetch = pg_fetch_object($result, 0);

			if ($comm_id == $fetch->uem_comm_id)
				return;

			if ($comm_id == get_next_comm_id($user_id, $fetch->uem_comm_id))
				$cont_count = $fetch->uem_cont_count + 1;
			else
				$cont_count = 1;

			// レコードが存在すれば更新
			$rec['uem_total_count'] = 'uem_total_count+1';
			$rec['uem_cont_count'] = $cont_count;
			$rec['uem_comm_id'] = $comm_id;
			$rec['uem_last_date'] = 'current_timestamp';
			db_update('t_user_errmail', $rec, "uem_user_id=$user_id");
		} else {
			// レコードが存在しなければ挿入
			$cont_count = 1;
			$rec['uem_user_id'] = $user_id;
			$rec['uem_total_count'] = 1;
			$rec['uem_cont_count'] = 1;
			$rec['uem_comm_id'] = $comm_id;
			$rec['uem_last_date'] = 'current_timestamp';
			db_insert('t_user_errmail', $rec);
		}

		// 最大不達回数に達したら状態コードを不達にセット
		if ($cont_count >= MAX_ERR_COUNT)
			set_error_status($user_id);

		// 初期フォーロー、継続リレーションメール送信Vol番号戻し処理
		reset_send_mail($user_id, $body);
	}
}

// 次のコミュニケーション履歴ID取得
function get_next_comm_id($user_id, $comm_id) {
	$sql = "SELECT cml_comm_id FROM t_comm_log WHERE cml_user_id=$user_id AND cml_comm_id>$comm_id ORDER BY cml_comm_id LIMIT 1";
	return db_fetch1($sql);
}

// ログインIDによるエラーメール処理
function err_mail_login_id($login_id) {
	// ユーザIDを取得
	$sql = "SELECT ulg_user_id FROM t_user_login WHERE ulg_login_id='$login_id'";
	$result = db_exec($sql);
	$nrow = pg_num_rows($result);
	if ($nrow) {
		$user_id = pg_fetch_result($result, 0, 0);

		// 不達回数をカウント
		$sql = "SELECT uem_user_id FROM t_user_errmail WHERE uem_user_id=$user_id";
		$result = db_exec($sql);
		$nrow = pg_num_rows($result);
		if ($nrow) {
			// レコードが存在すれば更新
			$rec['uem_total_count'] = 'uem_total_count+1';
			$rec['uem_comm_id'] = $comm_id;
			$rec['uem_last_date'] = 'current_timestamp';
			db_update('t_user_errmail', $rec, "uem_user_id=$user_id");
		} else {
			// レコードが存在しなければ挿入
			$rec['uem_user_id'] = $user_id;
			$rec['uem_total_count'] = 1;
			$rec['uem_cont_count'] = 0;
			$rec['uem_comm_id'] = 0;
			$rec['uem_last_date'] = 'current_timestamp';
			db_insert('t_user_errmail', $rec);
		}
	}
}

// 不達エラー設定処理
function set_error_status($user_id) {
	$sql = "SELECT COUNT(*) FROM t_user_status WHERE ust_user_id=$user_id AND (ust_status=1 OR ust_status=2)";
	$result = db_exec($sql);
	if (pg_fetch_result($result, 0, 0) != 0) {
		// ステータスを不達エラーにセット
		$rec['ust_status'] = 7;
		$rec['ust_handover_date'] = 'current_timestamp';
		db_update('t_user_status', $rec, "ust_user_id=$user_id");

		// メール送受信回数により決着区分とランクを決定取得
		$sb1 = "SELECT cml_err_mail_flag FROM t_comm_log WHERE cml_user_id=$user_id AND cml_send_recv='S' ORDER BY cml_comm_id LIMIT " . MAX_ERR_COUNT;
		$sql = "SELECT COUNT(*) FROM ($sb1) sb1 WHERE cml_err_mail_flag=false";
		if (db_fetch1($sql, 0) > 0) {
			// １回以上正常があり
			$kecchaku = 6;
			$rank = 'E';
		} else {
			// 初回から全て不達
			$kecchaku = 3;
			$rank = 'L';
		}

		// ユーザ対応結果登録
		$rec['urs_kecchaku'] = sql_number($kecchaku);
		$rec['urs_rank'] = sql_char($rank);
		$sql = "SELECT COUNT(*) FROM t_user_result WHERE urs_user_id=$user_id";
		$result = db_exec($sql);
		if (pg_fetch_result($result, 0, 0) == 0) {
			$rec['urs_user_id'] = sql_number($user_id);
			db_insert('t_user_result', $rec);
		} else {
			db_update('t_user_result', $rec, "urs_user_id=$user_id");
		}

		$rec['urg_handover_date'] = 'current_timestamp';
		db_update('t_user_regist', $rec, "urg_user_id=$user_id");
	}
}

// 初期フォーロー、継続リレーションメール送信Vol番号戻し処理
function reset_send_mail($user_id, &$body) {
	// 付加ヘッダ検索
	$ary = explode("\n", $body);
	if (is_array($ary)) {
		foreach ($ary as $str) {
			// 初期フォーローメールシーケンス番号
			$val = header_search('X-Follow-Seq', $str);
			if ($val != '')
				$follow_seq_no = $val;

			// 初期フォーローメールVol番号
			$val = header_search('X-Follow-Vol', $str);
			if ($val != '')
				$follow_vol = $val;

			// 継続リレーションメールシーケンス番号
			$val = header_search('X-Relation-Seq', $str);
			if ($val != '')
				$relation_seq_no = $val;

			// 継続リレーションメールVol番号
			$val = header_search('X-Relation-Vol', $str);
			if ($val != '')
				$relation_vol = $val;
		}

		if ($follow_vol != '')
			reset_follow_mail($user_id, $follow_seq_no, $follow_vol);
		elseif ($relation_vol != '')
			reset_relation_mail($user_id, $relation_seq_no, $relation_vol);
	}
}

// ヘッダ検索
function header_search($header, &$str) {
	$header .= ': ';
	$pos = strpos($str, $header);
	if ($pos !== false)
		return trim(substr($str, $pos + strlen($header)));
}

// 初期フォーローメール送信Vol番号戻し処理
function reset_follow_mail($user_id, $seq_no, $vol_no) {
	if (is_numeric($vol_no)) {
		$sql = "SELECT ust_follow_mail_vol,ust_rel_mail_vol,ust_status FROM t_user_status WHERE ust_user_id=$user_id";
		$result = db_exec($sql);
		if (pg_num_rows($result)) {
			$fetch = pg_fetch_object($result, 0);

			if ($fetch->ust_follow_mail_vol == $vol_no) {
				// ステータスが2で継続リレーションメールを未送信なら状態を初期フォロー中に戻す
				if ($fetch->ust_status == 2 && strpos($fetch->ust_rel_mail_vol, '1') === false) {
					$rec['ust_status'] = 1;
					$rec['ust_rel_week'] = 'null';
					$rec['ust_rel_start_date'] = 'null';
				}

				// Volを戻す
				$rec['ust_follow_mail_vol'] = $vol_no - 1;
				db_update('t_user_status', $rec, "ust_user_id=$user_id");

				// エラーカウント
				if (is_numeric($seq_no)) {
					$rec['fsl_err_num'] = 'fsl_err_num+1';
					db_update('t_follow_send_log', $rec, "fsl_seq_no=$seq_no");

					$rec['fsv_err_num'] = 'fsv_err_num+1';
					db_update('t_follow_send_vol', $rec, "fsv_seq_no=$seq_no AND fsv_vol_no=$vol_no");
				}
			}
		}
	}
}

// 継続リレーションメール送信Vol番号戻し処理
function reset_relation_mail($user_id, $seq_no, $vol_no) {
	if (is_numeric($vol_no)) {
		$sql = "SELECT ust_rel_mail_vol FROM t_user_status WHERE ust_user_id=$user_id";
		$rel_mail_vol = db_fetch1($sql);
		$pos = $vol_no - 1;
		if ($rel_mail_vol != '' && $rel_mail_vol{$pos} == '1') {
			// Volを未送信にする
			$rel_mail_vol{$pos} = '0';
			$rec['ust_rel_mail_vol'] = sql_char($rel_mail_vol);
			db_update('t_user_status', $rec, "ust_user_id=$user_id");

			// エラーカウント
			if (is_numeric($seq_no)) {
				$rec['rsl_err_num'] = 'rsl_err_num+1';
				db_update('t_relation_send_log', $rec, "rsl_seq_no=$seq_no");

				$rec['rsv_err_num'] = 'rsv_err_num+1';
				db_update('t_relation_send_vol', $rec, "rsv_seq_no=$seq_no AND rsv_vol_no=$vol_no");
			}
		}
	}
}
?>