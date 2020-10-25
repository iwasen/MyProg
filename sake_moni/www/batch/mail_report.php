<?
/******************************************************
' System :お酒と買物のアンケートモニターバッチ処理
' Content:レポートメール送信 (include)
'******************************************************/

function report_pjt_mail($job_id) {
	$sql = "SELECT jb_send_num,jb_real_send,en_enq_type,en_mail_subject,en_mail_contents"
			. " FROM t_job JOIN t_enquete ON en_enquete_id=jb_enquete_id"
			. " WHERE jb_job_id=$job_id AND jb_notice_flag=FALSE";
	$result = db_exec($sql);
	if (pg_num_rows($result)) {
		$fetch = pg_fetch_object($result, 0);

		get_mail_template('pjt_enq', $subject, $from, $cc, $bcc, $repty_to, $body);
		if (strpos($from, '***') > 0)
			return;

		$enq_type = $fetch->en_enq_type;
		$mail_subject = $fetch->en_mail_subject;
		$mail_contents = $fetch->en_mail_contents;
		$send_num = $fetch->jb_send_num;
		$real_send = $fetch->jb_real_send;

		$body = str_replace('%END_TIME%', date('Y/m/d H:i'), $body);
		$body = str_replace('%SEND_NUM%', number_format($real_send), $body);
		$body = str_replace('%SUBJECT%', $mail_subject, $body);
		$body = str_replace('%CONTENTS%', $mail_contents, $body);

		$sql = "SELECT sn_mail_adr FROM t_send_notice WHERE sn_job_id=$job_id";
		$result = db_exec($sql);
		$nrow = pg_num_rows($result);
		if ($nrow) {
			separate_address($from, $from_addr, $from_name);
			$send_mail_id = send_mail_data($subject, $from_addr, '', $body, '', '', $from_name);
			for ($i = 0; $i < $nrow; $i++) {
				$fetch = pg_fetch_object($result, $i);
				send_mail_list($send_mail_id, $fetch->sn_mail_adr);
			}
			send_mail_ok($send_mail_id);
		}

		$sql = "UPDATE t_job SET jb_notice_flag=TRUE WHERE jb_job_id=$job_id";
		db_exec($sql);

		// 事務局に送信完了通知
		switch ($enq_type) {
		case 1:
			$mail_kind = 'メールアンケート';
			break;
		case 2:
			$mail_kind = 'Webアンケート';
			break;
		default:
			$mail_kind = 'ポイント付与メール';
			break;
		}
		report_admin($mail_kind, $send_num, $real_send, $mail_subject, $mail_contents);
	}
}

function report_admin($mail_kind, $max_send_num, $send_num, &$mail_subject, &$mail_contents) {
	get_mail_template('send_rep', $subject, $from, $cc, $bcc, $repty_to, $body);
	if (strpos($from, '***') > 0)
		return;

	$fetch = get_system_info('sy_mail_notify_to');
	$to_addr = $fetch->sy_mail_notify_to;
	if (!$to_addr)
		return;

	$subject = str_replace('%MAIL_KIND%', $mail_kind, $subject);

	$body = str_replace('%MAIL_KIND%', $mail_kind, $body);
	$body = str_replace('%END_TIME%', date('Y/m/d H:i'), $body);
	$body = str_replace('%MAX_SEND_NUM%', number_format($max_send_num), $body);
	$body = str_replace('%SEND_NUM%', number_format($send_num), $body);
	$body = str_replace('%SUBJECT%', $mail_subject, $body);
	$body = str_replace('%CONTENTS%', $mail_contents, $body);

	separate_address($from, $from_addr, $from_name);
	$send_mail_id = send_mail_data($subject, $from_addr, $repty_to, $body, '', '', $from_name);
	send_mail_list($send_mail_id, $to_addr);

	send_mail_ok($send_mail_id);
}
