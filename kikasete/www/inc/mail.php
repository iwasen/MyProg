<?
/******************************************************
' System :きかせて・net共通
' Content:メール送信処理
'******************************************************/

// t_send_mail_dataにセット
function send_mail_data($subject, $from, $reply_to, $body, $mail_header, $send_date, $personal, $mail_type='0') {
	// メール送信テーブルにセット
	$sql = sprintf("INSERT INTO t_send_mail_data (sd_subject,sd_from,sd_reply_to,sd_body,sd_status,sd_mail_header,sd_send_date,sd_personal,sd_mail_type) VALUES (%s,%s,%s,%s,0,%s,%s,%s,%s)",
			sql_char($subject),
			sql_char($from),
			sql_char($reply_to),
			sql_char($body),
			sql_char($mail_header),
			sql_date($send_date),
			sql_char($personal),
			sql_char($mail_type));
	if (db_exec($sql)) {
		// 送信メールID取得
		$sql = "select currval('t_send_mail_d_sd_send_mail__seq')";
		$result = db_exec($sql);
		if (pg_numrows($result)) {
			$fetch = pg_fetch_row($result, 0);
			$send_mail_id = $fetch[0];
		}
	}

	return $send_mail_id;
}

// t_send_mail_fileにセット
function send_mail_file($send_mail_id, $file_id, $file_name, $file_content, $content_id = '') {
	db_begin_trans();

	$con = db_open();
	$oid = pg_locreate($con);
	$res = pg_loopen ($con, $oid, "w");
	pg_lowrite($res, $file_content);
//	pg_lowrite($res, "\0");
	pg_loclose($res);

	$sql = sprintf("INSERT INTO t_send_mail_file (sf_send_mail_id,sf_file_id,sf_file_name,sf_content_id,sf_file_content) VALUES (%s,%s,%s,%s,%s)",
			$send_mail_id,
			sql_number($file_id),
			sql_char($file_name),
			sql_char($content_id),
			$oid);
	db_exec($sql);

	db_commit_trans();
}

// t_send_mail_listにセット
function send_mail_list($send_mail_id, $to, $sub_seq = '', $mail_header = '') {
	$sql = sprintf("SELECT sl_send_mail_id FROM t_send_mail_list WHERE sl_send_mail_id=%s AND sl_to=%s",
			$send_mail_id,
			sql_char($to));
	$result = db_exec($sql);
	if (pg_numrows($result) != 0)
		return false;

	$sql = sprintf("INSERT INTO t_send_mail_list (sl_send_mail_id,sl_to,sl_sub_seq,sl_mail_header) VALUES (%s,%s,%s,%s)",
			$send_mail_id,
			sql_char($to),
			sql_number($sub_seq),
			sql_char($mail_header));
	return db_exec($sql);
}

// t_send_mail_embedにセット
function send_mail_embed($send_mail_id, $sub_seq, $variable_name, $embed_text) {
	$sql = sprintf("INSERT INTO t_send_mail_embed (se_send_mail_id,se_sub_seq,se_variable_name,se_embed_text) VALUES (%s,%s,%s,%s)",
			$send_mail_id,
			$sub_seq,
			sql_char($variable_name),
			sql_char($embed_text));
	return db_exec($sql);
}

// t_send_mail_dataステータスを送信可にセット
function send_mail_ok($send_mail_id) {
	$sql = "UPDATE t_send_mail_data SET sd_status=1 WHERE sd_send_mail_id=$send_mail_id";
	return db_exec($sql);
}

// １メール送信
function send_mail_one($subject, $from, $to, $reply_to, $body, $personal, $cc='', $bcc='') {
	$mail_header = '';
	if ($cc != '')
		$mail_header .= "Cc: $cc\n";
	if ($bcc != '')
		$mail_header .= "Bcc: $bcc\n";

	$send_mail_id = send_mail_data($subject, $from, $reply_to, $body, $mail_header, '', $personal);
	send_mail_list($send_mail_id, $to);
	return send_mail_ok($send_mail_id);
}

// メールテンプレート取得
function get_mail_template($template_id, &$subject, &$from, &$cc, &$bcc, &$repty_to, &$body) {
	$sql = "SELECT * FROM m_mail_template WHERE mt_template_id='$template_id'";
	$result = db_exec($sql);
	if (pg_numrows($result)) {
		$fetch = pg_fetch_object($result, 0);
		$subject = $fetch->mt_subject;
		$from = $fetch->mt_from;
		$cc = $fetch->mt_cc;
		$bcc = $fetch->mt_bcc;
		$repty_to = $fetch->mt_reply_to;
		$body = str_replace("\r\n", "\n", $fetch->mt_body);
	}
}

// メール送信
function send_mail($subject, $to, $from, $body, $cc=0, $bcc=0, $reply_to=0, $message_id=0) {
	if ($to == '')
		return;
	$header = "From: " . encode_address($from) . "\n";
	if ($cc)
		$header .= "Cc: $cc\n";
	if ($bcc)
		$header .= "Bcc: $bcc\n";
	if ($reply_to)
		$header .= "Reply-to: $reply_to\n";
	if ($message_id)
		$header .= "Message-ID: $message_id\n";

	mb_language('Japanese');
	return mb_send_mail($to, $subject, str_replace("\r", '', $body), $header);
}

// 名前付きアドレスエンコード
function encode_address($mail_addr) {
	if (ereg('(.+)\<(.+)\>', $mail_addr, $regs)) {
		return mb_encode_mimeheader(trim($regs[1])) . ' <' . trim($regs[2]) . '>';
	} else
		return $mail_addr;
}

// 名前付きアドレス分離
function separate_address($mail_addr, &$addr, &$name) {
	if (ereg('(.+)\<(.+)\>', $mail_addr, $regs)) {
		$addr = trim($regs[2]);
		$name = trim($regs[1]);
	} else {
		$addr = $mail_addr;
		$name = '';
	}
}

// メールアドレスをカンマで結合
function join_mail_addr($addr1, $addr2) {
	$ary = explode("\n", str_replace(',', "\n", $addr2));
	if (is_array($ary)) {
		foreach ($ary as $addr) {
			$addr = trim($addr);
			if ($addr != '') {
				if ($addr1 != '')
					$addr1 .= ',';
				$addr1 .= $addr;
			}
		}
	}
	return $addr1;
}
?>