<?php
//'=== inc/com_mail.inc より ===
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
/* **
print "to=".$to."<br>";
print "subject=".$subject."<br>";
print "body2=".$body2."<br>";
print "header=".$header."<br>";
*/
	mb_language('Japanese');
	if (mb_send_mail($to, $subject, $body, $header) == 1) {
		return 0;
	} else {
		return -1;
	}
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


//'=== jimukyoku/inc/jms_s_mail.inc より ===

function mag_ok_mail($owner_nm_kanj, $mail_add_main, $mag_nm, $mag_id) {
	get_mail_template("mag_ok1", $subject, $from_addr, $bcc_addr, $body);
	$to_addr = trim($mail_add_main);

	$body = str_replace("@user_nm@", trim($owner_nm_kanj), $body);
	$body = str_replace("@mail_add_main@", $mail_add_main, $body);
	$body = str_replace("@mag_nm@", $mag_nm, $body);
	$body = str_replace("@mag_id@", $mag_id, $body);
	return send_mail2($subject, $to_addr, $from_addr, $bcc_addr, $body);
}

function mag_ng_mail($owner_nm_kanj, $mail_add_main, $mag_nm) {
	get_mail_template("mag_ng", $subject, $from_addr, $bcc_addr, $body);
	$to_addr = trim($mail_add_main);

	$body = str_replace("@user_nm@", trim($owner_nm_kanj), $body);
	$body = str_replace("@mail_add_main@", $mail_add_main, $body);
	$body = str_replace("@mag_nm@", $mag_nm, $body);

	return send_mail2($subject, $to_addr, $from_addr, $bcc_addr, $body);
}

function mag_honok_mail($owner_nm_kanj, $mail_add_main, $mag_nm) {
	get_mail_template("hon_ok", $subject, $from_addr, $bcc_addr, $body);
	$to_addr = trim($mail_add_main);

	$body = str_replace("@user_nm@", trim($owner_nm_kanj), $body);
	$body = str_replace("@mail_add_main@", $mail_add_main, $body);
	$body = str_replace("@mag_nm@", $mag_nm, $body);

	return send_mail2($subject, $to_addr, $from_addr, $bcc_addr, $body);
}

function mag_honng_mail($owner_nm_kanj, $mail_add_main, $mag_nm) {
	get_mail_template("hon_ng", $subject, $from_addr, $bcc_addr, $body);
	$to_addr = trim($mail_add_main);

	$body = str_replace("@user_nm@", trim($owner_nm_kanj), $body);
	$body = str_replace("@mail_add_main@", $mail_add_main, $body);
	$body = str_replace("@mag_nm@", $mag_nm, $body);

	return send_mail2($subject, $to_addr, $from_addr, $bcc_addr, $body);
}

function mag_hinng_mail($owner_nm_kanj, $mail_add_main, $mag_nm, $flg) {
	get_mail_template($flg == "01" ? "hin_ng_y" : "hin_ng_r", $subject, $from_addr, $bcc_addr, $body);
	$to_addr = trim($mail_add_main);

	$body = str_replace("@user_nm@", trim($owner_nm_kanj), $body);
	$body = str_replace("@mail_add_main@", $mail_add_main, $body);
	$body = str_replace("@mag_nm@", $mag_nm, $body);

	return send_mail2($subject, $to_addr, $from_addr, $bcc_addr, $body);
}

function new_mag_keisai($owner_nm_kanj, $mail_add_main, $mag_nm, $send_date) {
	get_mail_template("new_mag", $subject, $from_addr, $bcc_addr, $body);
	$to_addr = trim($mail_add_main);

	$body = str_replace("@owner_nm_kanj@", trim($owner_nm_kanj), $body);
	$body = str_replace("@mail_add_main@", $mail_add_main, $body);
	$body = str_replace("@mag_nm@", $mag_nm, $body);
	$body = str_replace("@send_date@", $send_date, $body);

	return send_mail2($subject, $to_addr, $from_addr, $bcc_addr, $body);
}


/*
//'=== inc/com_mail.inc より ===

function iconvErrorHandler($errno, $errmsg, $filename, $linenum, $vars) {
	global $iconv_err;

	$iconv_err = 1;
}

function get_mail_template($template_id, &$subject, &$from, &$bcc, &$body) {
	$sql = "SELECT * FROM M_MAIL_TEMPLATE WHERE MT_id='$template_id'";
	$select = new select_sql($sql);
	if ($select->fetch()) {
		$subject = $select->field("MT_subject");
		$from = $select->field("MT_from");
		$bcc = $select->field("MT_bcc");
		$body = $select->field("MT_body");
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

	if (mail($to, $subject, $body2, $header) == 1) {
		return 0;
	} else {
		return -1;
	}
}

function send_mail($to_addr, $subject, $body, $header) {
	if (substr($header, -1) != "\n")
		$header .= "\n";
	$header .= "Content-Type: text/plain; charset=iso-2022-jp\n";
	$subject = i18n_mime_header_encode($subject);
	$body = iconv_convert($body, "iso-2022-jp", "EUC-JP");

	return mail($to_addr, $subject, $body, $header);
}


//'=== jimukyoku/inc/jms_s_mail.inc より ===

function mag_ok_mail($owner_nm_kanj, $mail_add_main, $mag_nm) {
	get_mail_template("mag_ok1", $subject, $from_addr, $bcc_addr, $body);
	$to_addr = trim($mail_add_main);

	$body = str_replace("@user_nm@", trim($owner_nm_kanj), $body);
	$body = str_replace("@mail_add_main@", $mail_add_main, $body);
	$body = str_replace("@mag_nm@", $mag_nm, $body);

	return send_mail2($subject, $to_addr, $from_addr, $bcc_addr, $body);
}

function mag_ng_mail($owner_nm_kanj, $mail_add_main, $mag_nm) {
	get_mail_template("mag_ng", $subject, $from_addr, $bcc_addr, $body);
	$to_addr = trim($mail_add_main);

	$body = str_replace("@user_nm@", trim($owner_nm_kanj), $body);
	$body = str_replace("@mail_add_main@", $mail_add_main, $body);
	$body = str_replace("@mag_nm@", $mag_nm, $body);

	return send_mail2($subject, $to_addr, $from_addr, $bcc_addr, $body);
}

function mag_honok_mail($owner_nm_kanj, $mail_add_main, $mag_nm) {
	get_mail_template("hon_ok", $subject, $from_addr, $bcc_addr, $body);
	$to_addr = trim($mail_add_main);

	$body = str_replace("@user_nm@", trim($owner_nm_kanj), $body);
	$body = str_replace("@mail_add_main@", $mail_add_main, $body);
	$body = str_replace("@mag_nm@", $mag_nm, $body);

	return send_mail2($subject, $to_addr, $from_addr, $bcc_addr, $body);
}

function mag_honng_mail($owner_nm_kanj, $mail_add_main, $mag_nm) {
	get_mail_template("hon_ng", $subject, $from_addr, $bcc_addr, $body);
	$to_addr = trim($mail_add_main);

	$body = str_replace("@user_nm@", trim($owner_nm_kanj), $body);
	$body = str_replace("@mail_add_main@", $mail_add_main, $body);
	$body = str_replace("@mag_nm@", $mag_nm, $body);

	return send_mail2($subject, $to_addr, $from_addr, $bcc_addr, $body);
}

function mag_hinng_mail($owner_nm_kanj, $mail_add_main, $mag_nm, $flg) {
	get_mail_template($flg == "01" ? "hin_ng_y" : "hin_ng_r", $subject, $from_addr, $bcc_addr, $body);
	$to_addr = trim($mail_add_main);

	$body = str_replace("@user_nm@", trim($owner_nm_kanj), $body);
	$body = str_replace("@mail_add_main@", $mail_add_main, $body);
	$body = str_replace("@mag_nm@", $mag_nm, $body);

	return send_mail2($subject, $to_addr, $from_addr, $bcc_addr, $body);
}

function new_mag_keisai($owner_nm_kanj, $mail_add_main, $mag_nm, $send_date) {
	get_mail_template("new_mag", $subject, $from_addr, $bcc_addr, $body);
	$to_addr = trim($mail_add_main);

	$body = str_replace("@owner_nm_kanj@", trim($owner_nm_kanj), $body);
	$body = str_replace("@mail_add_main@", $mail_add_main, $body);
	$body = str_replace("@mag_nm@", $mag_nm, $body);
	$body = str_replace("@send_date@", $send_date, $body);

	return send_mail2($subject, $to_addr, $from_addr, $bcc_addr, $body);
}
*/
?>