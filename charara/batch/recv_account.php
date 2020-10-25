<?
/******************************************************
' System :「きゃららFactory」バッチ処理
' Content:課金サーバからのID通知メール受信
'******************************************************/

$inc = "../inc";
include("$inc/database.php");
include("$inc/batch.php");
include("$inc/pop3.php");
include("$inc/mail.php");
include("$inc/status.php");

define('RESULT_NORMAL', 0);
define('RESULT_NO_ID', 1);
define('RESULT_INVALID_ID ', 2);
define('RESULT_ALREADY_REG', 3);
define('RESULT_INVALID_FROM', 4);

// 二重起動チェック
if (!dup_check('lock_recv_accounting'))
	exit;

// 最大実行時間（１０分）
set_time_limit(600);

// 課金サーバからのID通知メール受信処理
$count = recv_accounting_mail();

// バッチログ書き込み
if ($count > 0) {
	$desc = "課金サーバからのID通知メールを{$count}件受信しました。";
	batch_log('0', __FILE__, $desc);
}
exit;

// 課金サーバからのID通知メール受信処理
function recv_accounting_mail() {
	// メールサーバ名取得
	$server = get_system_info('server_pop3');

	// メール受信アカウント取得
	$account = explode('/', get_system_info('account_app_mail'), 2);

	$mail = new mail_pop3();
	$num_msg = $mail->open($server, $account[0], $account[1]);
	if (!$num_msg)
		return 0;

	db_begin_trans();

	$count = 0;
	for ($i = 1; $i <= $num_msg; $i++) {
		// メールヘッダ取得
		$header = $mail->get_header($i);

		// Fromアドレス取得
		$from = "{$header->from[0]->mailbox}@{$header->from[0]->host}";

		// 本文を取得
		$body = $mail->get_body($i);

		// 本文からID等を取得
		$result = get_id($body, $from, $server_id, $random_id, $product_cd, $campaign_cd);
		if ($result == 0) {
			save_account($server_id, $random_id, $product_cd, $campaign_cd);

			$count++;

			if (db_errormessage() != '') {
				$mail->close(false);
				db_rollback();
				exit;
			}
		}

		// 結果返信
		reply_result(mb_decode_mimeheader($header->subject), $body, $from, $result);

		$mail->delete($i);
	}

	$mail->close(true);
	db_commit_trans();

	return $count;
}

// ID取得
function get_id(&$body, &$from, &$server_id, &$random_id, &$product_cd, &$campaign_cd) {
	$ary = explode("\n", $body);
	$line = count($ary);
	for ($i = 0; $i < $line; $i++) {
		$ary2 = explode('=', $ary[$i], 2);
		if (count($ary2) == 2) {
			switch (strtolower(trim($ary2[0]))) {
			case 'aid':
				$random_id = trim($ary2[1]);
				$server_id = substr($random_id, 0, 3);
				break;
			case 'pid':
				$product_cd = trim($ary2[1]);
				break;
			case 'cid':
				$campaign_cd = trim($ary2[1]);
				break;
			}
		}
	}

	if ($random_id == '')
		$result = RESULT_NO_ID;
	else {
		$sql = "SELECT as_mail_addr FROM m_account_server WHERE as_server_id=" . sql_char($server_id);
		$mail_addr = db_fetch1($sql);
		if ($mail_addr == '')
			$result = RESULT_INVALID_ID;
		elseif ($mail_addr != $from && $server_id != 'TST')
			$result = RESULT_INVALID_FROM;
		else {
			$sql = "SELECT COUNT(*) FROM t_nigaoe_data WHERE nd_random_id=" . sql_char($random_id);
			if (db_fetch1($sql) != 0)
				$result = RESULT_ALREADY_REG;
			else
				$result = RESULT_NORMAL;
		}
	}

	return $result;
}

// ID通知メール保存処理
function save_account($server_id, $random_id, $product_cd, $campaign_cd) {
	$rec['nd_status'] = STATUS_SERVER_RECEIVED;
	$rec['nd_server_id'] = sql_char($server_id);
	$rec['nd_random_id'] = sql_char($random_id);
	$rec['nd_product_cd'] = sql_char($product_cd);
	$rec['nd_campaign_cd'] = sql_char($campaign_cd);
	$rec['nd_accept_date'] = 'current_timestamp';
	db_insert('t_nigaoe_data', $rec);
}

// 返信メール送信
function reply_result($org_subject, $org_body, $to, $result) {
	// 送信メールテンプレート取得
	get_mail_template('app_res', $subject, $from, $cc, $bcc, $repty_to, $body);

	// 差込み変数処理
	$subject = str_replace('%SUBJECT%', $org_subject, $subject);
	$body = str_replace('%ORIGINAL%', rtrim($org_body), $body);
	$body = str_replace('%RESULT%', $result, $body);

	// 受付完了メール送信
	send_mail($subject, $to, $from, $body, $cc, $bcc, $reply_to);
}
?>