<?
/******************************************************
' System :「おクルマ選び お手伝いサービス」バッチ処理
' Content:問い合わせメール受信バッチ処理
'******************************************************/

$top = ".";
$inc = "$top/../inc";
include("$inc/database.php");
include("$inc/encrypt.php");
include("$inc/batch.php");
include("pop3.php");

// 二重起動チェック
if (!dup_check('lock_recv_mail'))
	exit;

// 最大実行時間（１０分）
set_time_limit(600);

// メールサーバ名取得
$server = get_system_info('server_pop3');

// メール受信アカウント取得
$account_ary[] = get_system_info('account_dealer_mail');
$account_ary[] = get_system_info('account_follow_mail');
$account_ary[] = get_system_info('account_rel_mail');
$account_ary[] = get_system_info('account_1to1_mail');
$account_ary[] = get_system_info('account_inquiry_mail');

// 担当者のメールアカウント取得
$sql = "SELECT tts_mail_account FROM t_tantousha";
$result = db_exec($sql);
$nrow = pg_num_rows($result);
for ($i = 0; $i < $nrow; $i++)
	$account_ary[] = pg_fetch_result($result, $i, 0);

// メール受信処理
foreach (array_unique($account_ary) as $account)
	$count += recv_reply($server, $account);

// バッチログ書き込み
if ($count > 0) {
	$desc = "メールを{$count}件受信しました。";
	batch_log('0', __FILE__, $desc);
}
exit;

// 問い合わせ返信メール受信処理
function recv_reply(&$server, &$account) {
	// アカウント名とパスワードに分解
	$ary = explode('/', $account, 2);

	$mail = new mail_pop3();
	$num_msg = $mail->open($server, $ary[0], $ary[1]);
	if (!$num_msg)
		return 0;

	db_begin_trans();

	$count = 0;
	for ($i = 1; $i <= $num_msg; $i++) {
		$header = $mail->get_header($i);

		// toアドレスをチェック
		if (is_array($header->to)) {
			$to = &$header->to[0];
			$pos = strpos($to->mailbox, '-');
			if ($pos)
				$code = substr($to->mailbox, $pos + 1);
			else
				$code = 'N';

			recv_mail_proc($code, $header, $mail->get_text_body($i));
			$count++;

			if (db_errormessage() != '') {
				$mail->close(false);
				db_rollback();
				exit;
			}
		}

		$mail->delete($i);
	}

	$mail->close(true);
	db_commit_trans();

	return $count;
}

// 受信メール処理
function recv_mail_proc($code, &$header, &$body) {
	switch ($code{0}) {
	case 'C':
		recv_mail_comm_id(substr($code, 1), $header, $body);
		break;
	case 'L':
		recv_mail_login_id(substr($code, 1), $header, $body);
		break;
	case 'N':
		recv_mail_no_id($header, $body);
		break;
	}
}

// コミュニケーション履歴IDによる受信メール処理
function recv_mail_comm_id($code, &$header, &$body) {
	// コミュニケーション履歴IDを取得
	$comm_id = decrypt_id($code);
	if (!$comm_id) {
		ecv_mail_no_id($header, $body);
		return;
	}

	// 返信元のコミュニケーション履歴を取得
	$sql = "SELECT cml_parent_comm_id,cml_thread_comm_id,cml_user_id,cmc_comm_type"
			. " FROM t_comm_log"
			. " JOIN t_comm_contents ON cmc_contents_id=cml_contents_id"
			. " WHERE cml_comm_id=$comm_id";
	$result = db_exec($sql);
	$nrow = pg_num_rows($result);
	if ($nrow) {
		$fetch = pg_fetch_object($result, 0);
		$user_id = $fetch->cml_user_id;

		// コミュニケーション内容を保存
		$rec['cmc_comm_type'] = sql_number($fetch->cmc_comm_type);
		$rec['cmc_subject'] = sql_char(mb_decode_mimeheader($header->subject));
		$rec['cmc_body'] = sql_char($body);
		db_insert('t_comm_contents', $rec);
		$contents_id = get_current_seq('t_comm_contents', 'cmc_contents_id');

		// コミュニケーション履歴に保存
		$rec['cml_parent_comm_id'] = sql_number($comm_id);
		$rec['cml_thread_comm_id'] = sql_number($fetch->cml_thread_comm_id);
		$rec['cml_contents_id'] = sql_number($contents_id);
		$rec['cml_user_id'] = sql_number($user_id);
		$rec['cml_send_recv'] = sql_char('R');
		$rec['cml_message_id'] = sql_char($header->message_id);
		$rec['cml_references'] = sql_char($header->references);
		$rec['cml_answer_flag'] = 'true';
		$rec['cml_from_addr'] = sql_char("{$header->from[0]->mailbox}@{$header->from[0]->host}");
		$rec['cml_from_name'] = sql_char(mb_decode_mimeheader($header->from[0]->personal));
		$rec['cml_to_addr'] = sql_char("{$header->to[0]->mailbox}@{$header->to[0]->host}");
		db_insert('t_comm_log', $rec);

		// スレッド管理テーブルに追加
		$thread_comm_id = $fetch->cml_thread_comm_id;
		$sql = "SELECT COUNT(*) FROM t_comm_thread WHERE cmt_comm_id=$thread_comm_id";
		$result = db_exec($sql);
		if (pg_fetch_result($result, 0, 0)) {
			// 既に存在する場合はstatusを1にセット
			$rec['cmt_status'] = 1;
			$rec['cmt_answer_flag'] = 'true';
			db_update('t_comm_thread', $rec, "cmt_comm_id=$thread_comm_id AND (cmt_status<>1 OR cmt_answer_flag=false)");
		} else {
			$rec['cmt_comm_id'] = $thread_comm_id;
			$rec['cmt_status'] = 1;
			$rec['cmt_answer_flag'] = 'true';
			$rec['cmt_thread_type'] = 1;
			db_insert('t_comm_thread', $rec);
		}

		if ($user_id != '') {
			// 返信メール応答管理から削除
			db_delete('t_mail_reply', "mrp_user_id=$user_id");

			// メール配信を一時停止
			$rec['ust_rel_stop_flag'] = 'true';
			db_update('t_user_status', $rec, "ust_user_id=$user_id AND ust_rel_stop_flag=false");
		}
	}
}

// ログインIDによる受信メール処理
function recv_mail_login_id($login_id, &$header, &$body) {
	// ユーザID取得
	$sql = "SELECT ulg_user_id FROM t_user_login WHERE ulg_login_id=" . sql_char($login_id);
	$result = db_exec($sql);
	if (pg_num_rows($result))
		$user_id = pg_fetch_result($result, 0, 0);

	recv_mail_user_id($user_id, &$header, &$body);
}

// IDなしによる受信メール処理
function recv_mail_no_id(&$header, &$body) {
	recv_mail_user_id('', &$header, &$body);
}

// ユーザIDによる受信メール処理
function recv_mail_user_id($user_id, &$header, &$body) {
	// コミュニケーション内容を保存
	$rec['cmc_comm_type'] = sql_number(5);
	$rec['cmc_subject'] = sql_char(mb_decode_mimeheader($header->subject));
	$rec['cmc_body'] = sql_char($body);
	db_insert('t_comm_contents', $rec);
	$contents_id = get_current_seq('t_comm_contents', 'cmc_contents_id');

	// コミュニケーション履歴に保存
	$rec['cml_thread_comm_id'] = "currval('t_comm_log_cml_comm_id_seq')";
	$rec['cml_contents_id'] = sql_number($contents_id);
	$rec['cml_user_id'] = sql_number($user_id);
	$rec['cml_send_recv'] = sql_char('R');
	$rec['cml_message_id'] = sql_char($header->message_id);
	$rec['cml_references'] = sql_char($header->references);
	$rec['cml_answer_flag'] = 'true';
	$rec['cml_from_addr'] = sql_char("{$header->from[0]->mailbox}@{$header->from[0]->host}");
	$rec['cml_from_name'] = sql_char(mb_decode_mimeheader($header->from[0]->personal));
	$rec['cml_to_addr'] = sql_char("{$header->to[0]->mailbox}@{$header->to[0]->host}");
	db_insert('t_comm_log', $rec);
	$comm_id = get_current_seq('t_comm_log', 'cml_comm_id');

	// スレッド管理テーブルに追加
	$rec['cmt_comm_id'] = $comm_id;
	$rec['cmt_status'] = 1;
	$rec['cmt_answer_flag'] = 'true';
	$rec['cmt_thread_type'] = 2;
	db_insert('t_comm_thread', $rec);
}
?>