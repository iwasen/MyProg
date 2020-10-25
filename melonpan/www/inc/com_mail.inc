<?
/*
function iconvErrorHandler($errno, $errmsg, $filename, $linenum, $vars) {
	global $iconv_err;

	$iconv_err = 1;
}
*/
function get_mail_template($template_id, &$subject, &$from, &$bcc, &$body) {
	$sql = "SELECT * FROM M_MAIL_TEMPLATE WHERE MT_id='$template_id'";
	$result = db_exec($sql);
	if (pg_numrows($result)) {
		$fetch = pg_fetch_object($result, 0);
		$subject = $fetch->mt_subject;
		$from = $fetch->mt_from;
		$bcc = $fetch->mt_bcc;
		$body = $fetch->mt_body;
		$body = str_replace("\r\n", "\n",$body);
	}
}

function send_mail2($subject, $to, $from, $bcc, $body, $reply_to=0) {
	global $iconv_err;

	$header = "From: $from"."\n";
	if ($bcc)
		$header .= "Bcc: $bcc"."\n";
	if ($reply_to)
		$header .= "Reply-to: $reply_to"."\n";
/*
	$subject = i18n_mime_header_encode($subject);

	set_error_handler("iconvErrorHandler");
	$iconv_err = 0;
	$body2 = iconv_convert($body . "\n", "iso-2022-jp", "EUC-JP");
	if ($iconv_err == 0) {
		$header .= "Content-Type: text/plain; charset=iso-2022-jp\n";
		$header .= "Content-Transfer-Encoding: 7bit";
	} else {
		$iconv_err = 0;
		$body2 = iconv_convert($body . "\n", "Shift_JIS", "EUC-JP");
		if ($iconv_err == 0) {
			$header .= "Content-Type: text/plain; charset=Shift_JIS\n";
			$header .= "Content-Transfer-Encoding: 8bit";
		} else {
			$body2  = $body;
			$header .= "Content-Type: text/plain; charset=euc-jp\n";
			$header .= "Content-Transfer-Encoding: 8bit";
		}
	}
	restore_error_handler();
*/
	$body = str_replace("\r\n", "\n", $body);		// 2001.10.25’Ç‰Á
	$to = str_replace("\r\n", "\n",$to);			// 2001.10.30’Ç‰Á
	$subject = str_replace("\r\n", "\n",$subject);	// 2001.10.30’Ç‰Á
	$header = str_replace("\r\n", "\n",$header);	// 2001.10.30’Ç‰Á

	mb_language('Japanese');
	if (mb_send_mail($to, $subject, $body, $header) == 1)
		return 0;
	else
		return -1;
}

function send_mail($to_addr, $subject, $body, $header) {
	if (substr($header, -1) != "\n")
		$header .= "\n";
//	$header .= "Content-Type: text/plain; charset=iso-2022-jp\n";
//	$subject = i18n_mime_header_encode($subject);
//	$body = iconv_convert($body, "iso-2022-jp", "EUC-JP");

	mb_language('Japanese');
	return mb_send_mail($to_addr, $subject, $body, $header);
}
?>
