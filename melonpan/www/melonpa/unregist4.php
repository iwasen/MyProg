<?php
include("inc/pass_check.inc");

/****************************************************************
 Main
****************************************************************/
//$conn = db_conxxxt();
pass_check($conn,trim($mail_addr),trim($reader_pswd));

$sql1 = rm_make_sql ("SELECT_M_MELONPA_MAIL_ADR");
$sql = $sql1 ."'".  $mail_addr ."'";
$result = db_exec($sql);
if (pg_numrows($result)) {

	$sql1 = "delete from m_melonpa where mail_addr = ";
	$sql = $sql1 ."'".  $mail_addr ."'";
	db_exec($sql);
	
	//t_mailaddr‚Ìreader_id‰Šú‰»
	$sql = "update t_mailaddr set reader_id=0 where mail_addr='$mail_addr'";
	db_exec($sql);

	include ("tmpl_html/unregist4.html");
}
//db_logoff();
?>
