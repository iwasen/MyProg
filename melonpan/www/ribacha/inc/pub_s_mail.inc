<?php

include ("../inc/com_mail.inc");

function mag_new_mail($owner_nm_kanj, $mail_add_main, $mag_nm) {
	get_mail_template("mag_new", $subject, $from_addr, $bcc_addr, $body);
	$to_addr = trim($mail_add_main);

	$body = str_replace("@user_nm@", trim($owner_nm_kanj), $body);
	$body = str_replace("@mail_add_main@", $mail_add_main, $body);
	$body = str_replace("@mag_nm@", $mag_nm, $body);

	return send_mail2($subject, $to_addr, $from_addr, $bcc_addr, $body);
}

function mag_test_mail($mail_addr, $mag_nm, $mag_titl, $mag_body) {
	get_mail_template("mag_test", $subject, $from_addr, $bcc_addr, $body);
	$subject = $mag_titl . $subject;
	$to_addr = trim($mail_addr);

	$body = str_replace("@mag_nm@", trim($mag_nm), $body);
	$body = str_replace("@mag_titl@", $mag_titl, $body);
	$body = str_replace("@mag_body@", stripslashes($mag_body), $body);
	$body = str_replace("@mail_addr@", $to_addr, $body);

	return send_mail2($subject, $to_addr, $from_addr, $bcc_addr, $body, $to_addr);
}

?>
