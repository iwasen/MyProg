<?php
include ("inc/pub_passchk.inc");
include ("../inc/com_path.inc");
include ("$compath/err_msg.inc");
include ("$compath/com_func.inc");
include ("$compath/com_db_func.inc");
include ("$compath/sql_mm.inc");
include ("$compath/sql_mcom.inc");
include ("$compath/com_mm_func.inc");
include ("$incpath/pub_mm_dbacces.inc");
include ("$incpath/pub_mm_chk.inc");

function set_global () {
	global $inc_path;

	include ("../inc/com_path.inc");
	$inc_path = $incpath;
}

function make_sel_maglist($publisher_id, $mail_add_main, $owner_pswd) {
	global $inc_path;

	$conn = db_conxxxt();
	$sql = mm_make_sql ("SELECT_M_MAGAZINE_PUB");
	$stmt = OCIParse($conn, $sql);
	$ret01 = OCIbindbyname ($stmt, ":PUBLISHER_ID", &$publisher_id, -1);
	include("$inc_path/pub_mm_defmane.def");
	$ncols = OCIExecute($stmt);
	$lcount = 0;
	while (OCIFetch($stmt)) {
		$lcount++;
		$btn_name =  "edit_mag_data";
		$fom_name =  "formsubmit" . sprintf("%03d", $lcount);
		print "<form name=$fom_name action=\"./mag-henkou.php\" method=\"post\">\n";
		print "<tr>\n";
		print "<td width=\"100\" align=\"center\">$mag_id</td>\n";
		print "<td width=\"300\">$mag_nm</td>\n";
		print "<td width=\"100\"><div align=\"center\">\n";
	  	print "<input type=\"submit\" name=$btn_name value=\"修　正\">";
		print "</div></td></tr>\n";
		print "<input type=\"hidden\" value=$owner_pswd name=\"owner_pswd\">\n";
		print "<input type=\"hidden\" value=$publisher_id name=\"publisher_id\">\n";
		print "<input type=\"hidden\" value=$mail_add_main name=\"mail_add_main\">\n";
		print "<input type=\"hidden\" value=$mag_id name=\"mag_id\">\n";
		print "</form>\n";

//		if ($lcount > 10) { break; }
	}
	db_logoff();
}

/*======================================================================================*/
/*	edit Magazine									*/
/*	(main)										*/
/*======================================================================================*/
set_global ();
$conn = db_conxxxt();
pub_passchk($conn, $publisher_id,$owner_pswd);
db_logoff();

if ($edit_menu) {					/* 修正メニュー */
	include ("mag-henkou.html");
} elseif ($mag_edit_sel) {				/* 修正入力選択 */
	include ("mag-syu-sentaku.html");
} elseif ($edit_mag_data) {				/* 修正入力 */
	$save_flg = "edit";
	$conn = db_conxxxt();
	pub_mm_load ($conn, $mag_id);

            $mail_add_publish = $g_mail_add_publish;
            $mag_nm = $g_mag_nm;
            $mag_intro_long = $g_mag_intro_long;
            $mag_intro_short = $g_mag_intro_short;
            $mag_hp = $g_mag_hp;
            $mag_type = $g_mag_type;
            $mag_cycle = $g_mag_cycle;
            $mag_cycle_day = $g_mag_cycle_day;
            $mag_catg_1 = $g_mag_catg_1;
            $mag_catg_2 = $g_mag_catg_2;
            $mag_bg_1 = $g_mag_bg_1;
            $mag_bg_2 = $g_mag_bg_2;
            $mag_reader_sex = $g_mag_reader_sex;
            $mag_reader_age = $g_mag_reader_age;
            $mag_reader_area = $g_mag_reader_area;
            $mag_reader_occup = $g_mag_reader_occup;
            $mag_bn_pub = $g_mag_bn_pub;
            $ml_type = $g_ml_type;
            $company_nm = $g_company_nm;
            $add_point = $g_add_point;
//                $mag_status_flg = $g_mag_status_flg;
//                $mag_pub_status_flg = $g_mag_pub_status_flg;
//                $mag_pub_stop_flg = $g_mag_pub_stop_flg;

	$next_job_flg = "Vew_ed";
	include ("mag-info-syusei.html");
	db_logoff();
}

?>