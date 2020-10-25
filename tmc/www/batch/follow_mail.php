<?
/******************************************************
' System :「おクルマ選び お手伝いサービス」バッチ処理
' Content:初期フォローメール送信処理
'******************************************************/

$inc = "../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/mail.php");
include("$inc/format.php");
include("$inc/encrypt.php");
include("$inc/batch.php");
include("$inc/follow_mail.php");

// 二重起動チェック
if (!dup_check('lock_follow_mail'))
	exit;

// 最大実行時間（１時間）
set_time_limit(3600);

send_follow_mail();
exit;

function send_follow_mail() {
	// 最終送信日取得
	$sql = "SELECT CAST(fll_last_date AS DATE) FROM t_follow_last";
	$result = db_exec($sql);
	if (pg_num_rows($result)) {
		$last_date = pg_fetch_result($result, 0, 0);
		$update = true;
	}

	// 配信有無判定
	$sql = "SELECT CAST(fld_send_date AS DATE) FROM t_follow_date WHERE fld_send_date<=current_timestamp";
	if ($last_date != '')
		$sql .= " AND CAST(fld_send_date AS DATE)>" . sql_date($last_date);
	$sql .= " ORDER BY fld_send_date DESC LIMIT 1";
	$result = db_exec($sql);
	if (pg_num_rows($result) == 0)
		return;
	$send_date = pg_fetch_result($result, 0, 0);

	// 初期フォローメール送信履歴シーケンス番号取得
	$sql = "SELECT COALESCE(MAX(fsl_seq_no),0)+1 FROM t_follow_send_log";
	$seq_no = db_fetch1($sql);

	db_begin_trans();

	// 最終配信日時を更新
	$rec['fll_last_date'] = 'current_timestamp';
	if ($update)
		db_update('t_follow_last', $rec, 'true');
	else
		db_insert('t_follow_last', $rec);

	// fromアドレス取得
	$from_addr = get_system_info('addr_follow_mail');

	// メール送信テーブルにセット
	$send_mail_id = send_mail_data('初期フォローメール', $from_addr, '', '%CONTENTS%', '');

	// 送信数クリア
	$send_count = 0;

	// 配信対象者抽出
	$sql = "SELECT ust_user_id,ust_follow_mail_vol,ups_mail_addr"
			. " FROM t_user_status"
			. " JOIN t_user_personal ON ups_user_id=ust_user_id"
			. " JOIN t_user_manage ON umg_user_id=ust_user_id"
			. " WHERE ust_status=1 AND ust_rel_stop_flag=false"
			. " AND NOT EXISTS (SELECT * FROM t_follow_jogai WHERE flj_send_date='$send_date' AND flj_dealer_cd=umg_dealer_cd)";
	$result = db_exec($sql);
	$nrow = pg_num_rows($result);
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);
		$user_id = $fetch->ust_user_id;
		$vol_no = $fetch->ust_follow_mail_vol + 1;

		// メール識別ID取得
		$comm_id = get_next_seq('t_comm_log', 'cml_comm_id');
		$mail_id = '-C' . encrypt_id($comm_id);

		if (!get_follow_mail($user_id, $vol_no, $mail_id, $subject, $contents, $from_name, $from_addr))
			continue;

		// コミュニケーション内容テーブルに保存
		$rec['cmc_comm_type'] = 2;
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
		$mail_header = "X-Follow-Seq: $seq_no\nX-Follow-Vol: $vol_no";
		send_mail_list($send_mail_id, $fetch->ups_mail_addr, $sub_seq, $mail_header);

		// 差込変数
		send_mail_embed($send_mail_id, $sub_seq, '%%FROM_ADDR%%', "$from_name <$from_addr>");
		send_mail_embed($send_mail_id, $sub_seq, '%%SUBJECT%%', $subject);
		send_mail_embed($send_mail_id, $sub_seq, '%CONTENTS%', $contents);

		// 初期フォローメールVolNo.更新
		$rec['ust_follow_mail_vol'] = sql_number($vol_no);
		if ($vol_no >= 4) {
			if (!$rel_start_date)
				get_start_relation($rel_week, $rel_start_date);
			$rec['ust_status'] = 2;
			$rec['ust_rel_week'] = sql_number($rel_week);
			$rec['ust_rel_start_date'] = sql_date($rel_start_date);
		}
		db_update('t_user_status', $rec, "ust_user_id=$user_id");

		// 初回メール配信日設定
		$rec['urg_follow_date'] = 'current_timestamp';
		db_update('t_user_regist', $rec, "urg_user_id=$user_id AND urg_follow_date IS NULL");

		// Vol別配信数カウント
		$send_vol[$vol_no]++;

		$send_count++;
	}

	// ステータスを送信可
	send_mail_ok($send_mail_id);

	// 初期フォローメール配信ログ書き込み
	follow_send_log($seq_no, $send_count, $send_vol);

	// エラーチェック
	$err_msg = db_errormessage();
	if ($err_msg) {
		db_rollback();

		// バッチログ書き込み（エラー）
		$desc = "初期フォローメール送信処理でエラーが発生しました。($err_msg)";
		batch_log(9, __FILE__, $desc);
	} else {
		// バッチログ書き込み（正常）
		$desc = "初期フォローメールを{$send_count}通送信しました。";
		batch_log(0, __FILE__, $desc);

		db_commit_trans();
	}
}

// 継続リレーションメール開始情報取得
function get_start_relation(&$rel_week, &$rel_start_date) {
	$sql = sprintf("SELECT rld_send_date FROM t_relation_date WHERE rld_send_date>%s ORDER BY rld_send_date LIMIT 1",
			sql_date(date('Y/m/d', time() + 60 * 60 * 24 * 10)));
	$result = db_exec($sql);
	if (pg_num_rows($result)) {
		$send_date = pg_fetch_result($result, 0, 0);
		$rel_start_date = format_date($send_date);

		$sql = sprintf("SELECT COUNT(*) FROM t_relation_date WHERE to_char(rld_send_date, 'YYYYMM')=%s AND rld_send_date<%s",
				sql_char(date('Ym', sql_time($send_date))),
				sql_date($rel_start_date));
		$result = db_exec($sql);
		$rel_week = (pg_fetch_result($result, 0, 0) + 1) % 2;
	}
}

// 初期フォローメール配信ログ書き込み
function follow_send_log($seq_no, $send_count, &$send_vol) {
	// 初期フォローメール配信履歴
	$rec['fsl_seq_no'] = sql_number($seq_no);
	$rec['fsl_send_num'] = sql_number($send_count);
	$rec['fsl_err_num'] = 0;
	db_insert('t_follow_send_log', $rec);

	// 初期フォローメールVol別配信履歴
	if (is_array($send_vol)) {
		foreach ($send_vol as $vol_no => $send_num) {
			$rec['fsv_seq_no'] = sql_number($seq_no);
			$rec['fsv_vol_no'] = sql_number($vol_no);
			$rec['fsv_send_num'] = sql_number($send_num);
			$rec['fsv_err_num'] = 0;
			db_insert('t_follow_send_vol', $rec);
		}
	}
}
?>