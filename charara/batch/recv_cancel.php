<?
/******************************************************
' System :「きゃららFactory」バッチ処理
' Content:キャンセルメール受信
'******************************************************/

$inc = "../inc";
include("$inc/database.php");
include("$inc/batch.php");
include("$inc/pop3.php");

// 二重起動チェック
if (!dup_check('lock_recv_cancel'))
	exit;

// 最大実行時間（１０分）
set_time_limit(600);

// キャンセルメール受信処理
$count = recv_cancel_mail();

// バッチログ書き込み
if ($count > 0) {
	$desc = "キャンセルメールを{$count}件受信しました。";
	batch_log('0', __FILE__, $desc);
}
exit;

// キャンセルメール受信処理
function recv_cancel_mail() {
	// メールサーバ名取得
	$server = get_system_info('server_pop3');

	// メール受信アカウント取得
	$account = explode('/', get_system_info('account_cancel_mail'), 2);

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
		$random_id = get_id($body, $from);
		if ($random_id != '') {
			delete_account($random_id);

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

// ID取得
function get_id(&$body, &$from) {
	$ary = explode("\n", $body);
	$line = count($ary);
	for ($i = 0; $i < $line; $i++) {
		$ary2 = explode('=', $ary[$i], 2);
		if (count($ary2) == 2) {
			switch (strtolower(trim($ary2[0]))) {
			case 'aid':
				$random_id = trim($ary2[1]);
				break;
			}
		}
	}

	return $random_id;
}

// ID削除処理
function delete_account($random_id) {
	db_delete('t_nigaoe_data', "nd_random_id=" . sql_char($random_id));
}
?>