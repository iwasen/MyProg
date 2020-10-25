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

	$sql = "SELECT * FROM M_MAGAZINE WHERE publisher_id = $publisher_id"
		. " and (mag_pub_status_flg = '01' or mag_pub_status_flg = '03') "
		. " and (mag_pub_stop_flg <> '02') ";
	$result = db_exec($sql);
	$nrow = pg_numrows($result);
	$lcount = 0;
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);
		$mag_id = $fetch->mag_id;
		$mail_add_publish = $fetch->mail_add_publish;
		$mag_nm = $fetch->mag_nm;
		$mag_intro_long = $fetch->mag_intro_long;
		$mag_intro_short = $fetch->mag_intro_short;
		$mag_hp = $fetch->mag_hp;
		$mag_type = $fetch->mag_type;
		$mag_cycle = $fetch->mag_cycle;
		$mag_cycle_day = $fetch->mag_cycle_day;
		$mag_catg_1 = $fetch->mag_catg_1;
		$mag_catg_2 = $fetch->mag_catg_2;
		$mag_bg_1 = $fetch->mag_bg_1;
		$mag_bg_2 = $fetch->mag_bg_2;
		$mag_reader_sex = $fetch->mag_reader_sex;
		$mag_reader_age = $fetch->mag_reader_age;
		$mag_reader_area = $fetch->mag_reader_area;
		$mag_reader_occup = $fetch->mag_reader_occup;
		$mag_bn_pub = $fetch->mag_bn_pub;
		$ml_type = $fetch->ml_type;
		$mag_status_flg = $fetch->mag_status_flg;
		$mag_pub_status_flg = $fetch->mag_pub_status_flg;
		$mag_pub_stop_flg = $fetch->mag_pub_stop_flg;

		$lcount++;
		$btn_name =  "edit_mag_data";
		$fom_name =  "formsubmit" . sprintf("%03d", $lcount);
		print "<form name=$fom_name action=\"./mag-henkou.php\" method=\"post\">\n";
		print "<tr>\n";
		print "<td width=\"100\" align=\"center\">$mag_id</td>\n";
		print "<td width=\"300\">".htmlspecialchars($mag_nm)."</td>\n";
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
}
/*======================================================================================*/
/*	edit Magazine									*/
/*	(main)										*/
/*======================================================================================*/
set_global ();
pub_passchk($conn, $publisher_id,$owner_pswd);

if ($edit_menu) {					/* 修正メニュー */
	include ("mag-henkou.html");
} elseif ($mag_edit_sel) {				/* 修正入力選択 */
	include ("mag-syu-sentaku.html");
} elseif ($edit_mag_data) {				/* 修正入力 */
	$save_flg = "edit";
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
    $melmel_id = $g_melmel_id;
	$mag_present_ins = $g_mag_present_ins;
	$mag_original_flg = $g_mag_original_flg;
	$mag_from_addr = htmlspecialchars($g_mag_from_addr);
	$mag_subject_tmpl = htmlspecialchars($g_mag_subject_tmpl);
	$mag_header_tmpl = $g_mag_header_tmpl;
	$mag_footer_tmpl = $g_mag_footer_tmpl;
	$send_notice_flg = $g_send_notice_flg;

	// 初期値設定
	if ($mag_present_ins=="")	{ $mag_present_ins="1"; }	// 挿入する
	if ($mag_original_flg=="")	{ $mag_original_flg="2"; }  // めろんぱん以外で発行
	if ($send_notice_flg=="")	{ $send_notice_flg="2"; }	// 希望しない

//                $mag_status_flg = $g_mag_status_flg;
//                $mag_pub_status_flg = $g_mag_pub_status_flg;
//                $mag_pub_stop_flg = $g_mag_pub_stop_flg;

	$next_job_flg = "Vew_ed";
	include ("mag-info-syusei.html");
}

?>