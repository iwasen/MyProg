<?
/******************************************************
' System :ICP共通インクルードファイル
' Content:メール送信関数
'******************************************************/

// t_send_mail_dataにセット
function send_mail_data($subject, $from, $reply_to, $body, $mail_header='', $send_date='', $mail_type='0', $end_sql='') {
	separate_address($from, $from_addr, $from_name);

	// メール送信テーブルにセット
	$sql = sprintf("INSERT INTO t_send_mail_data (sd_subject,sd_from,sd_reply_to,sd_body,sd_status,sd_mail_header,sd_send_date,sd_personal,sd_mail_type,sd_end_sql) VALUES (%s,%s,%s,%s,0,%s,%s,%s,%s,%s)",
			sql_char($subject),
			sql_char($from_addr),
			sql_char($reply_to),
			sql_char($body),
			sql_char($mail_header),
			sql_date($send_date),
			sql_char($from_name),
			sql_char($mail_type),
			sql_char($end_sql));
	if (db_exec($sql)) {
		// 送信メールID取得
		$send_mail_id = get_current_seq('t_send_mail_data', 'sd_send_mail_id');
	}

	return $send_mail_id;
}

// t_send_mail_listにセット
function send_mail_list($send_mail_id, $to, $sub_seq = '', $mail_header = '', $reply_to = '') {
	if ($to == '')
		return;

	$sql = sprintf("SELECT sl_send_mail_id FROM t_send_mail_list WHERE sl_send_mail_id=%s AND sl_to=%s",
			$send_mail_id,
			sql_char($to));
	$result = db_exec($sql);
	if (pg_numrows($result) != 0)
		return false;

	if ($reply_to != '')
		$mail_header = "Reply-To: $reply_to\n$mail_header";

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

// t_send_mail_fileにセット
function send_mail_file($send_mail_id, $file_id, $file_name, $file_content) {
	$conn = db_open();
	$oid = pg_lo_create($conn);
	$res = pg_lo_open($conn, $oid, 'w');
	pg_lo_write($res, $file_content);
	pg_lo_close($res);

	$sql = sprintf("INSERT INTO t_send_mail_file (sf_send_mail_id,sf_file_id,sf_file_name,sf_file_content) VALUES (%s,%s,%s,%s)",
			$send_mail_id,
			$file_id,
			sql_char($file_name),
			$oid);
	return db_exec($sql);
}

// t_send_mail_dataステータスを送信可にセット
function send_mail_ok($send_mail_id) {
	$sql = "UPDATE t_send_mail_data SET sd_status=1 WHERE sd_send_mail_id=$send_mail_id";
	return db_exec($sql);
}

// １メール送信
function send_mail_one($subject, $from, $to, $reply_to, $body, $mail_header='') {
	$send_mail_id = send_mail_data($subject, $from, $reply_to, $body, $mail_header);
	send_mail_list($send_mail_id, $to);
	return send_mail_ok($send_mail_id);
}

// メールテンプレート取得
function get_mail_template($template_id, &$subject, &$from, &$cc, &$bcc, &$repty_to, &$body) {
	$sql = "SELECT * FROM m_mail_template WHERE mt_template_id='$template_id'";
	$result = db_exec($sql);
	if (pg_num_rows($result)) {
		$fetch = pg_fetch_object($result, 0);
		$subject = $fetch->mt_subject;
		$from = $fetch->mt_from;
		$cc = $fetch->mt_cc;
		$bcc = $fetch->mt_bcc;
		$repty_to = $fetch->mt_reply_to;
		$body = str_replace("\r\n", "\n", $fetch->mt_body);
	}
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

// 引用文作成
function make_quote(&$body) {
	$array = split("\n", $body);
	foreach ($array as $line) {
		$line= rtrim($line);
		$len = mb_strlen($line);
		$offset = 0;
		do {
			$str = mb_strimwidth($line, $offset, 76);
			$n = mb_strlen($str);
			$quote .= '> ' . $str . "\n";
			$offset += $n;
			$len -= $n;
		} while ($len > 0);
	}
	return $quote;
}

// FromアドレスとFrom名称を結合
function join_mail_from($addr, $name) {
	if ($name == '')
		return $addr;
	else
		return "$name <$addr>";
}

// Message-ID:生成
function make_message_id($str = 'ICP') {
	return uniqid($str, true) . '@' . get_system_info('mail_domain');
}

// ヘッダ、本文、フッタを結合
function make_contents(&$header, &$body, &$footer) {
	if ($header != '')
		$ary[] = trim($header);

	$ary[] = trim($body);

	if ($footer != '')
		$ary[] = trim($footer);

	return join("\n\n", $ary);
}

// メールヘッダ生成
function make_mail_header($message_id, $ref_message_id) {
	$ary[] = "Message-ID: <$message_id>";
	if ($ref_message_id) {
		$ary[] = "In-Reply-To: <$ref_message_id>";
		$ary[] = "References: <$ref_message_id>";
	}

	return join("\n", $ary);
}
?>