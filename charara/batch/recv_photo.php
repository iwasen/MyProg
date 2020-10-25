<?
/******************************************************
' System :「きゃららFactory」バッチ処理
' Content:顔写真メール受信処理
'******************************************************/

$inc = "../inc";
include("$inc/database.php");
include("$inc/batch.php");
include("$inc/pop3.php");
include("$inc/mail.php");
include("$inc/status.php");
include("$inc/file.php");

// 二重起動チェック
if (!dup_check('lock_recv_photo'))
	exit;

// 最大実行時間（１０分）
set_time_limit(600);

// 顔写真メール受信処理
$count = recv_photo_mail();

// バッチログ書き込み
if ($count > 0) {
	$desc = "顔写真メールを{$count}件受信しました。";
	batch_log('0', __FILE__, $desc);
}
exit;

// 顔写真メール受信処理
function recv_photo_mail() {
	// メールサーバ名取得
	$server = get_system_info('server_pop3');

	// メール受信アカウント取得
	$account = explode('/', get_system_info('account_photo_mail'), 2);

	$mail = new mail_pop3();
	$num_msg = $mail->open($server, $account[0], $account[1]);
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
			if ($pos) {
				$id = substr($to->mailbox, $pos + 1);

				$body = $mail->get_text_body($i);

				$attachment = $mail->get_attachment($i, 5);
//				if (!$attachment)
//					$attachment = $mail->get_attachment($i);

				recv_photo($id, $header, $body, $attachment);

				$count++;

				if (db_errormessage() != '') {
					$mail->close(false);
					db_rollback();
					exit;
				}
			}
		}

		$mail->delete($i);
	}

	$mail->close(true);
	db_commit_trans();

	return $count;
}

// 写真ファイル受付処理
function recv_photo($id, &$header, &$body, &$attachment) {
	// ステータス取得
	$sql = "SELECT nd_nigaoe_id,nd_status,nd_mail_addr FROM t_nigaoe_data WHERE nd_random_id=" . sql_char($id);
	$result = db_exec($sql);
	$nrow = pg_num_rows($result);
	if ($nrow == 0)
		return;

	$fetch = pg_fetch_object($result, 0);
	$status = $fetch->nd_status;

	if ($status < 2)
		return;

	if ($attachment) {
		if ($status == 2 || $status == 3) {
			save_photo($id, $attachment[0]);
			send_reply($id, 'photo_ok');
		}
	} else {
		if ($status == 2) {
			send_reply($id, 'photo_no');
		}
	}

	// メール送受信履歴に書き込み
	$rec['lm_nigaoe_id'] = sql_number($fetch->nd_nigaoe_id);
	$rec['lm_send_recv'] = sql_char('R');
	$rec['lm_from_addr'] = sql_char("{$header->from[0]->mailbox}@{$header->from[0]->host}");
	$rec['lm_to_addr'] = sql_char($fetch->nd_mail_addr);
	$rec['lm_subject'] = sql_char(mb_decode_mimeheader($header->subject));
	$rec['lm_body'] = sql_char(trim($body));
	db_insert('l_mail', $rec);
}

// 写真ファイル保存処理
function save_photo($id, &$attachment) {
	// 拡張子取得
	switch ($attachment['subtype']) {
	case 'JPEG':
		$ext = 'jpg';
		break;
	case 'PNG':
		$ext = 'png';
		break;
	case 'GIF':
		$ext = 'gif';
		break;
	default:
		$pos = strrpos($attachment['name'], '.');
		if ($pos)
			$ext = strtolower(substr($attachment['name'], $pos + 1));
		break;
	}

	// ファイル名生成
	$filename = "$id.$ext";

	// 画像ファイル保存
	if (save_photo_file($attachment['body'], $filename)) {
		// 似顔絵作成依頼データ更新
		$rec['nd_status'] = STATUS_PHOTO_RECEIVED;
		$rec['nd_photo_date'] = 'current_timestamp';
		$rec['nd_photo_file'] = sql_char($filename);
		db_update('t_nigaoe_data', $rec, "nd_random_id=" . sql_char($id));
	}
}

// 返信メール送信
function send_reply($id, $mail_id) {
	// 送信メールテンプレート取得
	get_mail_template($mail_id, $subject, $from, $cc, $bcc, $repty_to, $body);

	// FromアドレスにIDを付加
	$from = str_replace('@', "-$id@", $from);

	// ユーザのメールアドレス取得
	$sql = "SELECT nd_mail_addr FROM t_nigaoe_data WHERE nd_random_id=" . sql_char($id);
	$mail_addr = db_fetch1($sql);

	// 受付完了メール送信
	send_mail_one($subject, $from, $mail_addr, $reply_to, $body);
}
?>