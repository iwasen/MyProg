<?
/******************************************************
' System :きかせて・netバッチ処理
' Content:アラートメール送信
'******************************************************/

$top = '.';
$inc = "$top/../inc";
include("$inc/database.php");
include("$inc/define.php");
include("$inc/mail.php");
include("$inc/batch_log.php");

define('FILE_NAME', __FILE__);

// 最大実行時間（３分）
set_time_limit(180);

get_mail_template('alt_mail', $subject, $from, $cc, $bcc, $repty_to, $body);
separate_address($from, $from_addr, $from_name);

// アラートメール処理
for (;;) {
	// 送信可能メール取得
	$sql = "SELECT am_seq_no,am_kind,am_agent_id,am_subject,am_body,am_send_date" .
				" FROM t_alert_mail" .
				" WHERE am_status=0 AND (am_send_date IS NULL OR am_send_date<CURRENT_TIMESTAMP)";
	$result = db_exec($sql);
	if (!$result)
		break;
	$nrow = pg_numrows($result);
	if (!$nrow)
		break;

	$fetch = pg_fetch_object($result, 0);
	$seq_no = $fetch->am_seq_no;
	$kind = $fetch->am_kind;

	switch ($kind) {
	case 1:		// 自動送信
		$agent_id = get_alert_agent();
		break;
	case 2:		// 手動送信
		$agent_id = $fetch->am_agent_id;
		break;
	default:
		$agent_id = '';
		break;
	}

	$send_num = 0;

	db_begin_trans();

	if ($agent_id != '') {
		// メール送信テーブルにセット
		$send_mail_id = send_mail_data($fetch->am_subject, $from_addr, '', $fetch->am_body, '', $fetch->am_send_date, $from_name);

		$sql = "SELECT ag_mail_addr,ag_name1 FROM t_agent WHERE ag_agent_id IN ($agent_id)";
		$result = db_exec($sql);
		$nrow = pg_numrows($result);
		for ($i = 0; $i < $nrow; $i++) {
			$fetch = pg_fetch_object($result, $i);

			$sub_seq = $i + 1;
			send_mail_list($send_mail_id, $fetch->ag_mail_addr, $sub_seq);
			send_mail_embed($send_mail_id, $sub_seq, '%MAIL_ADDR%', $fetch->ag_mail_addr);
			send_mail_embed($send_mail_id, $sub_seq, '%AGENT_NAME%', $fetch->ag_name1);

			$send_num++;
		}

		// ステータスを送信可
		send_mail_ok($send_mail_id);
	}

	// メールを配信済みにセット
	$sql = "UPDATE t_alert_mail SET am_status=1,am_agent_id='$agent_id',am_send_date=CURRENT_TIMESTAMP,am_send_num=$send_num WHERE am_seq_no=$seq_no";
	db_exec($sql);

	// 次の自動送信メールセット
	if ($kind == 1) {
		$sql = sprintf("INSERT INTO t_alert_mail (am_kind,am_subject,am_body,am_send_date) VALUES (1,%s,%s,CURRENT_DATE+7)",
				sql_char($subject),
				sql_char($body));
		//db_exec($sql);
	}

	// エラーチェック
	$err_msg = db_errormessage();
	if ($err_msg) {
		db_rollback();

		// バッチログ書き込み（エラー）
		$desc = "アラートメール送信処理でエラーが発生しました。($err_msg)";
		batch_log(9, FILE_NAME, $desc);
		break;
	} else {
		// バッチログ書き込み（正常）
		if ($send_num > 0) {
			$desc = "アラートメール(ID=$seq_no)を{$send_num}通送信しました。";
			batch_log(0, FILE_NAME, $desc);
		}
	}

	db_commit_trans();
}

// １週間以内にメッセージを送信していないエージェントを取得
function get_alert_agent() {
	$sql = "SELECT ag_agent_id FROM t_agent WHERE ag_agent_id NOT IN"
			. " (SELECT co_agent_id FROM t_communication WHERE co_status=0 AND co_regist_date>CURRENT_DATE - 7 AND co_direction=2) AND ag_status=0"
			. " ORDER BY ag_agent_id";
	$result = db_exec($sql);
	$nrow = pg_numrows($result);
	$ary = array();
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_row($result, $i);
		$ary[] = $fetch[0];
	}

	return join(',', $ary);
}
?>