<?php
	include("inc/pass_check.inc");
	include ("inc/error_msg_list.inc");

/****************************************************************
 Main
****************************************************************/
//$conn = db_conxxxt();
if ($mail_addr != "") { pass_check($conn,trim($mail_addr),trim($reader_pswd)); }

$sql1 = rm_make_sql ("SELECT_M_MELONPA_MAIL_ADR_DATE");
$sql = $sql1 ."'".  $mail_addr ."'";
$result = db_exec($sql);
if (pg_numrows($result)) {
	$fetch = pg_fetch_object($result, 0);
	$reader_id = $fetch->reader_id;
	$mail_addr = $fetch->mail_addr;
	$reader_birth = $fetch->reader_birth;
	$reader_pswd = $fetch->reader_pswd;
	$reader_sex = $fetch->reader_sex;
	$reader_mrg = $fetch->reader_mrg;
	$reader_zip1 = $fetch->reader_zip1;
	$reader_zip2 = $fetch->reader_zip2;
	$reader_occup = $fetch->reader_occup;
	$wk_year = $fetch->wk_year;
	$wk_month = $fetch->wk_month;
	$wk_day = $fetch->wk_day;
	include ("tmpl_html/unregist5.html");
} else {
	//$print_msg = $em_melonpa_3;
	$p_titl = "Ž¥â§?Ž§ŽÑŽÅŽÐŽÏŽ¿";
	include ("tmpl_html/err-msg.html");
}

?>
