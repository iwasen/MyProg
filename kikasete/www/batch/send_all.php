<?
/******************************************************
' System :きかせて・netバッチ処理
' Content:全配信処理
'******************************************************/

$top = '.';
$inc = "$top/../inc";
include("$inc/database.php");
include("$inc/define.php");
include("$inc/mail.php");
include("$inc/batch_log.php");
include("mail_report.php");

define('FILE_NAME', __FILE__);

// 最大実行時間（３分）
set_time_limit(180);

// 送信元アドレス取得
$fetch = get_system_info('sy_mail_from,sy_mail_name');
$mail_from = $fetch->sy_mail_from;
$mail_name = $fetch->sy_mail_name;

// 全配信処理
for (;;) {
	// 送信可能メール取得
	$sql = "SELECT sa_send_id,sa_target,sa_subject,sa_body,sa_send_date" .
				" FROM t_send_all" .
				" WHERE sa_status=0 AND (sa_send_date IS NULL OR sa_send_date<CURRENT_TIMESTAMP)";
	$result = db_exec($sql);
	if (!$result)
		break;
	$nrow = pg_numrows($result);
	if (!$nrow)
		break;

	$fetch = pg_fetch_object($result, 0);
	$send_id = $fetch->sa_send_id;
	$target = $fetch->sa_target;
	$send_num = 0;
	$select = array();

	db_begin_trans();

	// メール送信テーブルにセット
	$send_mail_id = send_mail_data($fetch->sa_subject, $mail_from, '', $fetch->sa_body, '', $fetch->sa_send_date, $mail_name);

	// モニター
	if ($target{PS_MONITOR} == '1')
		$select[] = "SELECT DISTINCT $send_mail_id,mn_mail_addr FROM t_monitor WHERE mn_status=0 AND mn_type=1";

	// リサーチパートナー
	if ($target{PS_MARKETING_PARTNER} == '1')
		$select[] = "SELECT DISTINCT $send_mail_id,mn_mail_addr FROM t_monitor WHERE mn_status=0 AND mn_type=2";

	// メールニュース購読者
	if ($target{PS_MARKETER_MAILNEWS} == '1')
		$select[] = "SELECT DISTINCT $send_mail_id,mr_mail_addr FROM t_marketer WHERE mr_status=0 AND mr_type=1";

	// マーケター（無料）
	if ($target{PS_MARKETER_FREE} == '1')
		$select[] = "SELECT DISTINCT $send_mail_id,mr_mail_addr FROM t_marketer WHERE mr_status=0 AND mr_type=2";

	// マーケター（有料）
	if ($target{PS_MARKETER_PAY} == '1')
		$select[] = "SELECT DISTINCT $send_mail_id,mr_mail_addr FROM t_marketer WHERE mr_status=0 AND mr_type=3";

	// アシスタント
	if ($target{PS_MARKETER_ASSISTANT} == '1')
		$select[] = "SELECT DISTINCT $send_mail_id,mr_mail_addr FROM t_marketer WHERE mr_status=0 AND mr_type=4";

	// エージェント
	if ($target{PS_AGENT} == '1')
		$select[] = "SELECT DISTINCT $send_mail_id,ag_mail_addr FROM t_agent WHERE ag_status=0 AND ag_type=1";

	// エージェント（サブ）
	if ($target{PS_AGENT_SUB} == '1')
		$select[] = "SELECT DISTINCT $send_mail_id,ag_mail_addr FROM t_agent WHERE ag_status=0 AND ag_type=2";

	// SQL編集
	$sql = '';
	foreach ($select as $sub1) {
		if ($sql != '')
			$sql .= ' UNION ';
		$sql .= $sub1;
	}

	if ($sql != '') {
		$sql = "INSERT INTO t_send_mail_list (sl_send_mail_id,sl_to) ($sql)";
		$result = db_exec($sql);
		$send_num = pg_cmdtuples($result);
	}

	// ステータスを送信可
	send_mail_ok($send_mail_id);

	// 事務局に送信完了通知
	report_admin('全配信メール', $send_num, $send_num, $fetch->sa_subject, $fetch->sa_body);

	// メールを配信済みにセット
	$sql = "UPDATE t_send_all SET sa_status=1,sa_send_date=CURRENT_TIMESTAMP,sa_send_num=$send_num WHERE sa_send_id=$send_id";
	db_exec($sql);

	// エラーチェック
	$err_msg = db_errormessage();
	if ($err_msg) {
		db_rollback();

		// バッチログ書き込み（エラー）
		$desc = "全配信送信処理でエラーが発生しました。($err_msg)";
		batch_log(9, FILE_NAME, $desc);
		break;
	} else {
		// バッチログ書き込み（正常）
		if ($send_num > 0) {
			$desc = "全配信(ID=$send_id)を{$send_num}通送信しました。";
			batch_log(0, FILE_NAME, $desc);
		}
	}

	db_commit_trans();
}
?>