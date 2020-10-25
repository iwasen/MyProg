<?php
include("inc/pass_check.inc");
include ("inc/error_msg_list.inc");

/****************************************************************
 Main
****************************************************************/

pass_check($conn,trim($mail_addr),trim($reader_pswd));

$sql1 = rm_make_sql ("SELECT_M_MELONPA_MELO");
$sql = $sql1 .  $reader_id;
$result = db_exec($sql);
if (pg_numrows($result)) {
	$sql = "UPDATE M_MELONPA SET "
			. "mail_addr = '$mail_addr', "
			. "reader_pswd = '$reader_pswd_new',"
			. "reader_birth = '$reader_birth', "
      . "reader_sex = '$reader_sex', "
			. "reader_mrg = '$reader_mrg', "
			. "reader_zip1 = '$reader_zip1', "
			. "reader_zip2 = '$reader_zip2', "
			. "reader_occup = '$reader_occup', "
			. "modify_dt    = 'now'"
		. " WHERE reader_id = $reader_id ";

	$result = db_exec($sql);
	setcookie("cookie_reader_id", $reader_id, time()+604800);
	setcookie("cookie_mail_addr", trim($mail_addr), time()+604800);
	setcookie("cookie_reader_pswd", trim($reader_pswd_new), time()+604800);
	include ("tmpl_html/unregist7.html");
} else {
	include ("tmpl_html/unregist5.html");
	echo $em_melonpa_10;
}

?>
