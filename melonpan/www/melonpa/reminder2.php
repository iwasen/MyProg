<?php
include("inc/mr_s_mail.inc");
include ("inc/error_msg_list.inc");

/****************************************************************
 Main
****************************************************************/
if (strlen($input_mail_addr) == 0) {
	$error_msg = $em_pass_1;
	include ("reminder2.html");
	exit();
}
$sql1 = rm_make_sql ("SELECT_M_MELONPA_MAIL_ADR_DATE");
$sql = $sql1 ."'".  $input_mail_addr ."'";
$result = db_exec($sql);
if (pg_numrows($result)) {
	$fetch = pg_fetch_object($result, 0);
	$reader_pswd = $fetch->reader_pswd;
	pass_send_mail($input_mail_addr,$reader_pswd);
	$error_msg = $em_pass_2;
	include ("reminder2.html");
	exit();
} else {
	$error_msg = $em_pass_3;
	include ("reminder2.html");
	exit();
}
?>
