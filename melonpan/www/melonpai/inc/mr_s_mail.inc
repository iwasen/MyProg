<?php

include ("../inc/com_mail.inc");

function mitu_send_mail($send_mail_addr,$subject,$mail_body,$mail_addr) {
	$to_add = trim($send_mail_addr);
	$faq = "http://www.melonpan.net/faq.html";
	$form_addr = $mail_addr;
	$from_mail_addr = "From:$form_addr"."\n";
	//$from_mail_addr .= "Bcc: ichiro@melonpan.net";
	$header = $from_mail_addr;
	$subject = i18n_mime_header_encode($subject);
	if ( send_mail($to_add,$subject,$mail_body,$header) == 1 ) {
		return 0;
	} else {
		return -1;
	}

}

function recom_send_mail($send_mail_addr,$mag_id,$mag_name,$mail_addr,$point_up,$owner_nm_kanj,$osusume_reason) {
	$to_addr = trim($send_mail_addr);
	get_mail_template("recom", $subject, $from_addr, $bcc_addr, $body);

	$body = str_replace("@mag_id@", $mag_id, $body);
	$body = str_replace("@mag_nm@", $mag_name, $body);
	$body = str_replace("@point_up@", $point_up, $body);
	$body = str_replace("@owner_nm_kanj@", trim($owner_nm_kanj), $body);
	$body = str_replace("@osusume_reason@", $osusume_reason, $body);

	return send_mail2($subject, $to_addr, $from_addr, $bcc_addr, $body);
}

function coution_send_mail($mag_id,$mag_name,$from_mail,$owner_nm_kanj,$melonpai_id,$melonpai_name,$osusume_reason) {
	get_mail_template("caution", $subject, $from_addr, $bcc_addr, $body);
	$to_addr = "dangerous@melonpan.net";
	$from_addr = $from_mail;

	$body = str_replace("@mag_id@", $mag_id, $body);
	$body = str_replace("@mag_nm@", $mag_name, $body);
	$body = str_replace("@owner_nm_kanj@", trim($owner_nm_kanj), $body);
	$body = str_replace("@melonpai_id@", $melonpai_id, $body);
	$body = str_replace("@melonpai_name@", $melonpai_name, $body);
	$body = str_replace("@osusume_reason@", $osusume_reason, $body);

	return send_mail2($subject, $to_addr, $from_addr, $bcc_addr, $body);
}

?>
