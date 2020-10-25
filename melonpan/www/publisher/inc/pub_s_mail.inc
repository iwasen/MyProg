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

function mag_test_mail($mail_addr, $mag_nm, $mag_titl, $mag_body, $mag_from_addr) {
	get_mail_template("mag_test", $subject, $from_addr, $bcc_addr, $body);
	$subject = $mag_titl . $subject;
	$to_addr = trim($mail_addr);
	/* from ƒAƒhƒŒƒX‘Î‰ž */
	if ( $mag_from_addr != "" ){
		if ( strpos( $mag_from_addr, "<") === false and strpos( $mag_from_addr, ">") === false ){
			if ( substr($mag_from_addr, 0, 1) == "\"" and  substr($mag_from_addr, -1) == "\"" ){
				$mag_from_addr = substr($mag_from_addr, 1, strlen($mag_from_addr)-2 );
				$mag_from_addr = i18n_mime_header_encode($mag_from_addr);
				$mag_from_addr = "\"" . $mag_from_addr."\"";
			}else{
				$mag_from_addr = str_replace("\"", "", $mag_from_addr);
				$mag_from_addr = i18n_mime_header_encode($mag_from_addr);
			}
			$from_addr = $mag_from_addr."<".$from_addr.">";
		}else{
//			if ( substr($mag_from_addr, 0, 1) == "\"" and  substr($mag_from_addr, -1) == "\"" ){
//				$mag_from_addr = substr($mag_from_addr, 1, strlen($mag_from_addr)-2 );
//			}
			$mag_from_addr = str_replace("\"", "", $mag_from_addr);
			$mag_from_addr = i18n_mime_header_encode($mag_from_addr);
			$mag_from_addr = "\"" . $mag_from_addr."\"";
			$from_addr = $mag_from_addr."<".$from_addr.">";
		}
	}
	/* ------------------- */

	$body = str_replace("@mag_nm@", trim($mag_nm), $body);
	$body = str_replace("@mag_titl@", $mag_titl, $body);
	$body = str_replace("@mag_body@", stripslashes($mag_body), $body);
	$body = str_replace("@mail_addr@", $to_addr, $body);

	return send_mail2($subject, $to_addr, $from_addr, $bcc_addr, $body, $to_addr);
}

?>
