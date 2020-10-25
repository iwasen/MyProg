<?
/******************************************************
' System :ICP共通インクルードファイル
' Content:ジョブ通知メール処理
'******************************************************/

function send_job_notify_mail($job_id, $template_id, $reorder = false) {
	// 送信メールテンプレート取得
	get_mail_template($template_id, $subject, $from, $cc, $bcc, $repty_to, $body);

	// 差し込み処理
	$body = replace_room_var($job_id, $body, $reorder);

	// ICP管理者のメールアドレス取得
	$sql = "SELECT op_mail_addr FROM t_operator WHERE op_status=1 AND op_admin_auth=1";
	$result = db_exec($sql);
	$nrow = pg_num_rows($result);
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);
		$to_ary[]  = $fetch->op_mail_addr;
	}

	// ジョブ管理者権限所有メンバのメールアドレス取得
	$sql = "SELECT pm_mail_addr"
			. " FROM t_job"
			. " JOIN t_pjt_member ON pm_pjt_id=jb_pjt_id"
			. " WHERE jb_job_id=$job_id AND pm_status=1 AND " . cond_staff_auth('ジョブ管理');
	$result = db_exec($sql);
	$nrow = pg_num_rows($result);
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);
		$to_ary[]  = $fetch->pm_mail_addr;
	}

	// 送信メールデータセット
	$send_mail_id = send_mail_data($subject, $from, $repty_to, $body);

	// 配信先リスト設定
	foreach (array_unique($to_ary) as $to)
		send_mail_list($send_mail_id, $to);

	send_mail_ok($send_mail_id);
}
?>