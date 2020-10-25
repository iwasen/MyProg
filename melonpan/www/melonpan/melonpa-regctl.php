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

/****************************************************************
 [ all code from ../melonpa/regist3.php ]
****************************************************************/
function in_check(){

	global $w_mail_addr;
	global $mail_addr;
	global $wk_year;
	global $wk_month;
	global $wk_day;
	global $reader_mrg;
	global $reader_sex;
	global $reader_zip1;
	global $reader_occup;
	global $reader_zip2;
	global $reader_pswd;
	global $wk_year;
	global $wk_month;
	global $wk_day;

	$w_mail_addr = trim ($w_mail_addr);
	$mail_addr = trim ($mail_addr);
	$wk_year = trim($wk_year);
	$wk_month = trim($wk_month);
	$wk_day = trim($wk_day);
	$reader_mrg = trim($reader_mrg);
	$reader_sex = trim($reader_sex);
	$reader_zip1 = trim($reader_zip1);
	$reader_occup = trim($reader_occup);
	$reader_zip2 = trim($reader_zip2);
	$reader_pswd = trim($reader_pswd);
	$reader_birth = $wk_year . "/". $wk_month . "/".  $wk_day;

	/* ╬╝й╦╩З╓гепо©(2001.10.09) -------------- */
	$w_mail_addr = strtolower($w_mail_addr);
	$mail_addr = strtolower($mail_addr);
	/* --------------------------------------- */

	$print_msg = check_all(
		$w_mail_addr,
		$mail_addr,
		$reader_pswd,
		$wk_year,
		$wk_month,
		$wk_day,
		$reader_mrg,
		$reader_sex,
		$reader_zip1,
		$reader_zip2,
		$reader_occup
	);
	if ($print_msg != "") {
		$p_titl = "ёмёЫ╓А╓М╓С╓я╓С епо©";
//		include ("tmpl_html/err-msg.html");
		include ("melonpa-err-msg.php");
	} else {
//		include ("tmpl_html/regist3.html");
		include ("melonpa-regist2.php");
	}

}
/*==================================================================*/
/*	submit Magazine	(main)											*/
/*	[ from melonpa/MelonpanDef.php ]								*/
/*==================================================================*/
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
	include ("melonpa-regist.php"); // modifyed
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
	include ("melonpa-regist.php"); // modifyed
} elseif ($rmbott0201) {
//	include ("../melonpa/regist3.php");
	in_check();
} elseif ($rmbott0301) {
//	include ("../melonpa/regist4.php");
	include ("melonpa-regist3.php"); // modifyed
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
	include ("melonpa-regist.php"); // modifyed
} elseif ($rmbott0401) {
//	include ("../melonpa/regist4.php");
	include ("melonpa-regist3.php"); // modifyed
}
?>