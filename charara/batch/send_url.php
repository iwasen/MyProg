<?
/******************************************************
' System :「きゃららFactory」バッチ処理
' Content:似顔絵URL通知メール送信処理
'******************************************************/

$inc = "../inc";
include("$inc/database.php");
include("$inc/batch.php");
include("$inc/pop3.php");
include("$inc/mail.php");
include("$inc/status.php");

// 二重起動チェック
if (!dup_check('lock_send_url'))
	exit;

// 最大実行時間（１０分）
set_time_limit(600);

// 似顔絵URL通知メール送信処理
$count = send_url_mail();

// バッチログ書き込み
if ($count > 0) {
	$desc = "似顔絵URL通知メールを{$count}件受信しました。";
	batch_log('0', __FILE__, $desc);
}
exit;

// 似顔絵URL通知メール送信処理
function send_url_mail() {
	$sql = "SELECT nd_nigaoe_id,nd_random_id,nd_mail_addr FROM t_nigaoe_data WHERE nd_status=" . STATUS_CHECK_OK;
	$result = db_exec($sql);
	$nrow = pg_num_rows($result);
	if ($nrow) {
		db_begin_trans();

		// 送信メールテンプレートを取得
		get_mail_template('send_url', $subject, $from, $cc, $bcc, $repty_to, $body);

		// 似顔絵ダウンロードURL取得
		$url = get_system_info('url_user') . 'dl.php';

		// 送信内容セット
		$send_mail_id = send_mail_data($subject, $from, $reply_to, $body);

		for ($i = 0; $i < $nrow; $i++) {
			$fetch = pg_fetch_object($result, $i);
			$sub_seq = $i + 1;

			// メール送信先セット
			send_mail_list($send_mail_id, $fetch->nd_mail_addr, $sub_seq);

			// URL差込み
			send_mail_embed($send_mail_id, $sub_seq, '%DOWNLOAD_URL%', "$url?$fetch->nd_random_id");

			// 似顔絵データテーブル更新
			$rec['nd_status'] = STATUS_USER_NOTIFIED;
			db_update('t_nigaoe_data', $rec, "nd_nigaoe_id=$fetch->nd_nigaoe_id");
		}

		// 送信許可
		send_mail_ok($send_mail_id);

		db_commit_trans();
	}
}
?>