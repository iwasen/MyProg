<?php
include("inc/pass_check.inc");
include ("inc/error_msg_list.inc");

/****************************************************************
 Main
****************************************************************/
if ($mail_addr != "") {pass_check($conn,trim($mail_addr),trim($reader_pswd)); }

$errflg = 0;
$p_titl = "Myめろんぱん情報修正";

// 「パスワード」のチェック

if ($reader_pswd_new == "") {
	$print_msg .= $em_melonpa_4;
	$errflg = 1;
} elseif (strlen($reader_pswd_new) < 4 || strlen($reader_pswd_new) > 8) {
	$print_msg .= $em_melonpa_5;
	$errflg = 1;
} elseif ( !(ereg ("^[0-9a-zA-Z]{4,}$", $reader_pswd)) ) {
	$print_msg .= $em_melonpa_6;
	$errflg = 1;
}

// 「連絡先郵便番号」のチェック
if (strlen($reader_zip1) == 0 || strlen($reader_zip2) == 0) {
	$print_msg .= $em_melonpa_7;
	$errflg = 1;
} else {
	if (strlen($reader_zip1) <> 3 || strlen($reader_zip2) <> 4) {
		$print_msg .= $em_melonpa_8;
		$errflg = 1;
	} else {
		if ( !(ereg ("^[0-9]{3}$", $reader_zip1)) || !(ereg ("^[0-9]{4}$", $reader_zip2)) ) {
			$print_msg .= $em_melonpa_9;
			$errflg = 1;

		}
	}
}
if ($errflg == 1) {
	include ("tmpl_html/err-msg.html");
	db_logoff();
	exit();
}
include ("tmpl_html/unregist6.html");

?>
