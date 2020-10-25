<?
/******************************************************
' System :ICPバッチ処理
' Content:不達メール受信処理
'******************************************************/

$top = ".";
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/batch.php");
include("$inc/auth.php");
include("$inc/mail.php");
include("$inc/pop3.php");
include("$inc/variable.php");
include("$inc/room.php");
include("$inc/format.php");
include("$inc/decode.php");

// 二重起動チェック
if (!dup_check('lock_mail_err'))
	exit;

// 最大実行時間（１０分）
set_time_limit(600);

// メールサーバ名取得
$server = get_system_info('server_pop3');

// メール受信アカウント／パスワード取得
$account = explode('/', get_system_info('err_mail_account'), 2);

mail_recv($server, $account[0], $account[1]);
exit;

// メール受信処理
function mail_recv($server, $account, $password) {
	$mail = new mail_pop3();
	$num_msg = $mail->open($server, $account, $password);
	if (!$num_msg)
		return 0;

	db_begin_trans();

	$count = 0;

	for ($i = 1; $i <= $num_msg; $i++) {
		// メール本文取得
		$body = $mail->get_text_body($i);

		$warning = false;

		// 不達以外を除く
		if (strpos($body, '(warning-timeout)') !== false)
			$warning = true;

		if (!$warning) {
			// 返送されたメールヘッダを解析
			$header = imap_rfc822_parse_headers($body);

			// Reply-ToアドレスからプロジェクトID,ジョブIDを取得
			get_id($header->reply_to[0]->mailbox, $pjt_id, $job_id);
			if ($job_id) {
				// ToアドレスからメンバIDを取得
				$member_id = get_member_id($pjt_id, $header->toaddress);
				if ($member_id) {
					send_err_notify($pjt_id, $job_id, $member_id, $body);
					$count++;
				}
			}
		}

		// メールサーバ上のメールを削除
		$mail->delete($i);
	}

	// エラーチェック
	$err_msg = db_errormessage();
	if ($err_msg) {
		db_rollback();
		$mail->close(false);

		// バッチログ書き込み（エラー）
		$desc = "不達メール受信処理でエラーが発生しました。($err_msg)";
		batch_log(9, __FILE__, $desc);
	} else {
		// バッチログ書き込み（正常）
		$desc = "不達メールを{$count}件受信しました。";
		batch_log(0, __FILE__, $desc);

		db_commit_trans();
		$mail->close(true);
	}
}

// メールアドレスからプロジェクトID,ジョブIDを取得
function get_id($mail_addr, &$pjt_id, &$job_id) {
	$ary = explode('-', $mail_addr);

	// プロジェクトID取得
	if ($ary[1] != '') {
		$sql = "SELECT pj_pjt_id FROM t_pjt WHERE pj_pjt_cd=" . sql_char($ary[1]);
		$pjt_id = db_fetch1($sql);

		// ジョブID取得
		if ($pjt_id != '' && $ary[2] != '') {
			$sql = "SELECT jb_job_id FROM t_job WHERE jb_pjt_id=$pjt_id AND jb_job_cd=" . sql_char($ary[2]);
			$job_id = db_fetch1($sql);
		}
	}
}

// メールアドレスからメンバIDを取得
function get_member_id($pjt_id, $mail_addr) {
	$sql = "SELECT pm_member_id FROM t_pjt_member WHERE pm_pjt_id=$pjt_id AND pm_mail_addr=" . sql_char($mail_addr);
	return db_fetch1($sql);
}

// 関係者に通知
function send_err_notify($pjt_id, $job_id, $member_id, &$body_org) {
	$to_ary = array();

	// プロジェクト所有オペレーターのメールアドレス取得
	$sql = "SELECT op_mail_addr"
			. " FROM t_pjt"
			. " JOIN t_operator ON op_operator_id=pj_operator_id"
			. " WHERE pj_pjt_id=$pjt_id";
	$to_ary[] = db_fetch1($sql);

	// ジョブ管理者権限所有メンバのメールアドレス取得
	$sql = "SELECT pm_mail_addr"
			. " FROM t_pjt_member"
			. " WHERE pm_pjt_id=$pjt_id AND pm_status=1 AND " . cond_staff_auth('ジョブ管理');
	$result = db_exec($sql);
	$nrow = pg_num_rows($result);
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);
		$to_ary[]  = $fetch->pm_mail_addr;
	}

	// 司会者権限所有者メンバのメールアドレス取得
	$sql = "SELECT pm_mail_addr"
			. " FROM t_job_member"
			. " JOIN t_pjt_member ON pm_member_id=jm_member_id"
			. " WHERE jm_job_id=$job_id AND jm_status=1 AND " . cond_participant_auth('司会者');
	$result = db_exec($sql);
	$nrow = pg_num_rows($result);
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);
		$to_ary[]  = $fetch->pm_mail_addr;
	}

	// 送信メールテンプレート取得
	get_mail_template('err_notify', $subject, $from, $cc, $bcc, $repty_to, $body);

	// 本文引用
	$body = str_replace('%QUATE%', make_quote($body_org), $body);

	// 差し込み変数処理
	$body = replace_all_var($job_id, $member_id, $body);

	// 送信メールデータセット
	$send_mail_id = send_mail_data($subject, $from, $repty_to, $body);

	// 配信先リスト設定
	foreach (array_unique($to_ary) as $to)
		send_mail_list($send_mail_id, $to);

	send_mail_ok($send_mail_id);
}
?>