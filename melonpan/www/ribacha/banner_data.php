<?php
include ("inc/pub_passchk.inc");
include ("../inc/com_path.inc");
include ("$compath/err_msg.inc");
include ("$compath/com_db_func.inc");
include ("$compath/sql_mm.inc");
include ("$compath/sql_mp.inc");
include ("$compath/sql_mcom.inc");
include ("$compath/com_mm_func.inc");
include ("$incpath/pub_mm_dbacces.inc");
include ("$incpath/pub_mm_func.inc");
include ("$incpath/pub_s_mail.inc");

/*======================================================================================*/
/*	(main)										*/
/*======================================================================================*/
$conn = db_conxxxt();
pub_passchk($conn, $publisher_id,$owner_pswd);
include ("banner_data.html");
db_logoff();
?>
