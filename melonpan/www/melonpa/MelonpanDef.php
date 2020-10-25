<?php
include ("../inc/com_path.inc");
include ("$compath/com_db_func.inc");
include ("$compath/com_rm_func.inc");
include ("$compath/sql_mcom.inc");
include ("$compath/sql_rm.inc");
include ("../melonpa/inc/mr_s_mail.inc");

function set_global () {
global $inc_path;
	include ("../inc/com_path.inc");
	$inc_path = $incpath;

}
/*======================================================================================*/
/*	submit Magazine									*/
/*	(main)										*/
/*======================================================================================*/
set_global ();
if ($rmbott0101) {
	$w_mail_addr = "";
	$mail_addr = "";
	$wk_year = "";
	$wk_month = "1";
	$wk_day = "1";
	$reader_sex = "1";
	$reader_mrg = "1";
	$reader_zip1 = "";
	$reader_zip2 = "";
	$reader_occup = "00000000";
	include ("regist2.php");
} elseif ($rmbott0102) {
	$w_mail_addr = "";
	$mail_addr = "";
	$wk_year = "";
	$wk_month = "1";
	$wk_day = "1";
	$reader_sex = "1";
	$reader_mrg = "1";
	$reader_zip1 = "";
	$reader_zip2 = "";
	$reader_occup = "00000000";
	include ("regist2.php");
} elseif ($rmbott0201) {
	include ("../melonpa/regist3.php");
} elseif ($rmbott0301) {
	include ("../melonpa/regist4.php");
} elseif ($rmbott0302) {
	$w_mail_addr = "";
	$mail_addr = "";
	$wk_year = "";
	$wk_month = "1";
	$wk_day = "1";
	$reader_sex = "1";
	$reader_mrg = "1";
	$reader_zip1 = "";
	$reader_zip2 = "";
	$reader_occup = "00000000";

	include ("../melonpa/regist2.php");
} elseif ($rmbott0401) {
	include ("../melonpa/regist4.php");
}
?>