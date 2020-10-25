<?
/******************************************************
' System :「きゃららFactory」インクルードファイル
' Content:メール送信関数
'******************************************************/

// t_send_mail_dataにセット
function send_mail_data($subject, $from, $reply_to, $body, $mail_header='', $send_date='', $mail_type='0', $end_sql='') {

	separate_address($from, $from_addr, $from_name);

	// メール送信テーブルにセット
	$sql = sprintf("INSERT INTO t_send_mail_data (sd_subject,sd_from,sd_reply_to,sd_body,sd_status,sd_mail_header,sd_send_date,sd_personal,sd_mail_type) VALUES (%s,%s,%s,%s,0,%s,%s,%s,%s)",
			sql_char($subject),
			sql_char($from_addr),
			sql_char($reply_to),
			sql_char($body),
			sql_char($mail_header),
			sql_date($send_date),
			sql_char($from_name),
			sql_char($mail_type));
	if (db_exec($sql)) {
		// 送信メールID取得
		$send_mail_id = get_current_seq('t_send_mail_data', 'sd_send_mail_id');
	}

	return $send_mail_id;
}

// t_send_mail_listにセット
function send_mail_list($send_mail_id, $to, $sub_seq = '', $mail_header = '') {
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
function send_mail_file($send_mail_id, $file_id, $file_name, $file_content, $content_id = '') {
	db_begin_trans();

	$con = db_open();
	$oid = pg_locreate($con);
	$res = pg_loopen ($con, $oid, "w");
	pg_lowrite($res, $file_content);
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

// t_send_mail_dataステータスを送信可にセット
function send_mail_ok($send_mail_id) {
	$sql = "UPDATE t_send_mail_data SET sd_status=1 WHERE sd_send_mail_id=$send_mail_id";
	return db_exec($sql);
}

// １メール送信
function send_mail_one($subject, $from, $to, $reply_to, $body) {
	$send_mail_id = send_mail_data($subject, $from, $reply_to, $body);
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

// メール送信
function send_mail($subject, $to, $from, $body, $cc=0, $bcc=0, $reply_to=0, $message_id=0, $headers=0, $attachment=0) {
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
	if ($headers)
		$header .= $headers;

//	$ary = explode('/', get_system_info('account_err_mail'));
//	$mail_from = str_replace('%', '@', $ary[0]);

	if ($attachment)	{
		$boundary = '-*-*-*-*-*-*-*-*-Boundary_' . uniqid("b");
		$header	.= "MIME-Version: 1.0\n";
		$header	.= "Content-Type: multipart/mixed; boundary=\"$boundary\"\n";

		$part	= "--$boundary\n";
		$part	.= "Content-Type: text/plain;	charset=\"ISO-2022-JP\"\n";
		$part	.= "Content-Transfer-Encoding: 7bit\n";
		$part	.= "\n";

		$body = $part . mb_convert_encoding($body, "JIS") . "\n";

		for ($i = 0; $i < count($attachment); $i++)	{
			$filename = mb_encode_mimeheader($attachment[$i]['file']);
			$body	.= "--$boundary\n";
			$body	.= "Content-Type: {$attachment[$i]['type']}; name=\"$filename\"\n";
			$body	.= "Content-Transfer-Encoding: base64\n";
			$body	.= "Content-Disposition: attachment; filename=\"$filename\"\n";
			$body	.= "\n";
			$body	.= chunk_split($attachment[$i]['data']);
			$body	.= "\n";
		}

		$body	.= "--$boundary--\n";
		return mail($to, mb_encode_mimeheader($subject), str_replace("\r", '', $body), $header, "-f $mail_from");
	} else {
		mb_language('Japanese');
		return mb_send_mail($to, $subject, str_replace("\r", '', $body), $header/*, "-f $mail_from"*/);
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

// Message-ID:生成
function make_message_id($str = '') {
	return "<$str" . time() . "@{$_SERVER['SERVER_NAME']}>";
}

// ヘッダ、本文、フッタを結合
function make_contents(&$header, &$body, &$footer) {
	return "{$header}\r\n{$body}\r\n{$footer}";
}
?>