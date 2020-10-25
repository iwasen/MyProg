<?
/******************************************************
' System :「おクルマ選び お手伝いサービス」バッチ処理
' Content:継続リレーションメール送信処理
'******************************************************/

$inc = "../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/mail.php");
include("$inc/encrypt.php");
include("$inc/batch.php");
include("$inc/rel_mail.php");

define('LAST_SEND_WEEK', 48);		// 最終メールを送信するまでの週
define('FOLLOW_END_WEEK', 2);		// 最終メールからランクMで引き継ぐまでの週

// 二重起動チェック
if (!dup_check('lock_relation_mail'))
	exit;

// 最大実行時間（１時間）
set_time_limit(3600);

send_relation_mail();
follow_end();

exit;

// 継続リレーションメール送信
function send_relation_mail() {
	// 最終送信日取得
	$sql = "SELECT CAST(rll_last_date AS DATE) FROM t_relation_last";
	$result = db_exec($sql);
	if (pg_num_rows($result)) {
		$last_date = pg_fetch_result($result, 0, 0);
		$update = true;
	}

	// 配信有無判定
	$sql = "SELECT CAST(rld_send_date AS DATE) FROM t_relation_date WHERE rld_send_date<=current_timestamp";
	if ($last_date != '')
		$sql .= " AND CAST(rld_send_date AS DATE)>" . sql_date($last_date);
	$sql .= " ORDER BY rld_send_date DESC LIMIT 1";
	$result = db_exec($sql);
	if (pg_num_rows($result) == 0)
		return;
	$send_date = pg_fetch_result($result, 0, 0);

	// 偶数週か奇数週かを取得
	$sql = sprintf("SELECT COUNT(*) FROM t_relation_date WHERE to_char(rld_send_date, 'YYYYMM')=%s AND rld_send_date<%s",
			sql_char(date('Ym', sql_time($send_date))),
			sql_date($send_date));
	$result = db_exec($sql);
	$rel_week = (pg_fetch_result($result, 0, 0) + 1) % 2;

	// 継続リレーションメール送信履歴シーケンス番号取得
	$sql = "SELECT COALESCE(MAX(rsl_seq_no),0)+1 FROM t_relation_send_log";
	$seq_no = db_fetch1($sql);

	db_begin_trans();

	// 最終配信日時を更新
	$rec['rll_last_date'] = 'current_timestamp';
	if ($update)
		db_update('t_relation_last', $rec, 'true');
	else
		db_insert('t_relation_last', $rec);

	// fromアドレス取得
	$from_addr = get_system_info('addr_rel_mail');

	// メール送信テーブルにセット
	$send_mail_id = send_mail_data('継続リレーションメール', $from_addr, '', '%CONTENTS%', '');

	// 送信数クリア
	$send_count = 0;

	// 配信対象者抽出
	$sql = "SELECT ust_user_id,COALESCE(ust_rel_mail_vol,repeat('0'," . MAX_RELATION_MAIL . ")) AS ust_rel_mail_vol,ust_follow_end_date,ups_mail_addr,cast(urg_follow_date as date) + interval '" . LAST_SEND_WEEK . " week'<=current_date AS end_flag"
			. " FROM t_user_status"
			. " JOIN t_user_personal ON ups_user_id=ust_user_id"
			. " JOIN t_user_manage ON umg_user_id=ust_user_id"
			. " JOIN t_user_regist ON urg_user_id=ust_user_id"
			. " WHERE ust_status=2 AND ust_rel_stop_flag=false AND ust_rel_start_date<=current_date AND ust_rel_week=$rel_week"
			. " AND NOT EXISTS (SELECT * FROM t_relation_jogai WHERE rlj_send_date='$send_date' AND rlj_dealer_cd=umg_dealer_cd)";
	$result = db_exec($sql);
	$nrow = pg_num_rows($result);
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);
		$user_id = $fetch->ust_user_id;
		$rel_mail_vol = $fetch->ust_rel_mail_vol;

		$pos = strpos($rel_mail_vol, '0');
		if ($pos === false)
			continue;
		$vol_no = $pos + 1;

		// メール識別ID取得
		$comm_id = get_next_seq('t_comm_log', 'cml_comm_id');
		$mail_id = '-C' . encrypt_id($comm_id);

		// フォーロー終了メッセージ取得
		if ($fetch->end_flag == DBTRUE) {
			$sql = "SELECT cem_message FROM t_comm_end_msg WHERE cem_msg_id=1";
			$follow_end_msg = db_fetch1($sql);
		} else
			$follow_end_msg = '';

		if (!get_relation_mail($user_id, $vol_no, $mail_id, $subject, $contents, $from_name, $from_addr, $follow_end_msg))
			continue;

		// コミュニケーション内容テーブルに保存
		$rec['cmc_comm_type'] = 3;
		$rec['cmc_subject'] = sql_char($subject);
		$rec['cmc_body'] = sql_char($contents);
		$rec['cmc_vol_no'] = sql_number($vol_no);
		db_insert('t_comm_contents', $rec);
		$contents_id = get_current_seq('t_comm_contents', 'cmc_contents_id');

		// コミュニケーション履歴に追加
		$rec['cml_comm_id'] = $comm_id;
		$rec['cml_thread_comm_id'] = "currval('t_comm_log_cml_comm_id_seq')";
		$rec['cml_contents_id'] = $contents_id;
		$rec['cml_user_id'] = $user_id;
		$rec['cml_send_recv'] = sql_char('S');
		$rec['cml_answer_flag'] = 'false';
		$rec['cml_from_addr'] = sql_char($from_addr);
		$rec['cml_from_name'] = sql_char($from_name);
		$rec['cml_to_addr'] = sql_char($fetch->ups_mail_addr);
		db_insert('t_comm_log', $rec);

		// 送信先リスト書き込み
		$sub_seq = $i + 1;
		$mail_header = "X-Relation-Seq: $seq_no\nX-Relation-Vol: $vol_no";
		send_mail_list($send_mail_id, $fetch->ups_mail_addr, $sub_seq, $mail_header);

		// 差込変数
		send_mail_embed($send_mail_id, $sub_seq, '%%FROM_ADDR%%', "$from_name <$from_addr>");
		send_mail_embed($send_mail_id, $sub_seq, '%%SUBJECT%%', $subject);
		send_mail_embed($send_mail_id, $sub_seq, '%CONTENTS%', $contents);

		// リレーション終了チェック
		if ($fetch->end_flag == DBTRUE) {
			// メール配信停止
			if ($fetch->ust_follow_end_date == null)
				$rec['ust_follow_end_date'] = 'current_date';
			$rec['ust_rel_stop_flag'] = 'true';
		}

		// 継続リレーションメールVolNo.更新
		$rel_mail_vol{$pos} = '1';
		$rec['ust_rel_mail_vol'] = sql_char($rel_mail_vol);
		db_update('t_user_status', $rec, "ust_user_id=$user_id");

		// Vol別配信数カウント
		$send_vol[$vol_no]++;

		$send_count++;
	}

	// ステータスを送信可
	send_mail_ok($send_mail_id);

	// 継続リレーションメール配信ログ書き込み
	relation_send_log($seq_no, $send_count, $send_vol);

	// エラーチェック
	$err_msg = db_errormessage();
	if ($err_msg) {
		db_rollback();

		// バッチログ書き込み（エラー）
		$desc = "継続リレーションメール送信処理でエラーが発生しました。($err_msg)";
		batch_log(9, __FILE__, $desc);
	} else {
		// バッチログ書き込み（正常）
		$desc = "継続リレーションメールを{$send_count}通送信しました。";
		batch_log(0, __FILE__, $desc);

		db_commit_trans();
	}
}

// 継続リレーションメール配信ログ書き込み
function relation_send_log($seq_no, $send_count, &$send_vol) {
	// 継続リレーションメール配信履歴
	$rec['rsl_seq_no'] = sql_number($seq_no);
	$rec['rsl_send_num'] = sql_number($send_count);
	$rec['rsl_err_num'] = 0;
	db_insert('t_relation_send_log', $rec);

	// 継続リレーションメールVol別配信履歴
	if (is_array($send_vol)) {
		foreach ($send_vol as $vol_no => $send_num) {
			$rec['rsv_seq_no'] = sql_number($seq_no);
			$rec['rsv_vol_no'] = sql_number($vol_no);
			$rec['rsv_send_num'] = sql_number($send_num);
			$rec['rsv_err_num'] = 0;
			db_insert('t_relation_send_vol', $rec);
		}
	}
}

// リレーション終了処理
function follow_end() {
	db_begin_trans();

	$sql = "SELECT ust_user_id,urs_user_id,urs_commant1,urs_commant2,urs_commant3,urs_commant4"
			. " FROM t_user_status"
			. " LEFT JOIN t_user_result ON urs_user_id=ust_user_id"
			. " WHERE ust_status=2 AND ust_follow_end_date + interval '" . FOLLOW_END_WEEK . " week'<=current_date"
			. " AND NOT EXISTS (SELECT * FROM t_comm_log WHERE cml_user_id=ust_user_id AND cml_send_recv='R' AND cml_date>=ust_follow_end_date)";
	$result = db_exec($sql);
	$nrow = pg_num_rows($result);
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);
		$user_id = $fetch->ust_user_id;

		// ステータスを引継ぎ済みにセット
		$rec['ust_status'] = 3;
		$rec['ust_handover_date'] = 'current_timestamp';
		db_update('t_user_status', $rec, "ust_user_id=$user_id");

		// コメントセット
		if ($fetch->urs_commant1 == '' && $fetch->urs_commant2 == '' && $fetch->urs_commant3 == '' && $fetch->urs_commant4 == '') {
			$rec['urs_commant1'] = sql_char('一年間フォローメールを送信しましたが、返信を頂いておりません。');
			$rec['urs_commant2'] = sql_char('今後のご連絡の許諾を頂いていないお客様なので、');
			$rec['urs_commant3'] = sql_char('DM・電話などによる営業活動は逆CSになる可能性があります。');
			$rec['urs_commant4'] = sql_char('ご注意ください。');
		}

		// ユーザ対応結果登録
		$rec['urs_kecchaku'] = sql_number(6);
		$rec['urs_rank'] = sql_char('M');
		if ($fetch->urs_user_id == null) {
			$rec['urs_user_id'] = sql_number($user_id);
			db_insert('t_user_result', $rec);
		} else {
			db_update('t_user_result', $rec, "urs_user_id=$user_id");
		}

		// 引継ぎ日時セット
		$rec['urg_handover_date'] = 'current_timestamp';
		db_update('t_user_regist', $rec, "urg_user_id=$user_id");
	}

	db_commit_trans();
}
?>