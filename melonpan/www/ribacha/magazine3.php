<?php
include ("inc/pub_passchk.inc");
include ("../inc/com_path.inc");
include ("$compath/err_msg.inc");
include ("$compath/com_db_func.inc");

function make_loglist($publisher_id) {
	$conn = db_conxxxt();
	$sql = "SELECT mag_nm, send_num, to_char(send_dt, 'YYYY/MM/DD') send_day, "
		. "to_char(send_dt, 'hh24:mi') send_hour ,send_volume FROM L_SEND "
		. "WHERE publisher_id = :PUBLISHER_ID ORDER BY SEND_DT DESC";
	$stmt = OCIParse($conn, $sql);
	$ret01 = OCIbindbyname ($stmt, ":PUBLISHER_ID", &$publisher_id, -1);
	$ret01 = OCIDefinebyname($stmt, "MAG_NM", &$mag_nm);
	$ret01 = OCIDefinebyname($stmt, "SEND_VOLUME", &$send_volume);
	$ret01 = OCIDefinebyname($stmt, "SEND_DAY", &$send_day);
	$ret01 = OCIDefinebyname($stmt, "SEND_HOUR", &$send_hour);
	$ncols = OCIExecute($stmt);
	$lcount = 0;
	while (OCIFetch($stmt)) {
		$lcount++;
		if ($lcount % 2 == 0) {
			print "<tr bgcolor=\"#CCCCCC\">\n";
		} else {
			print "<tr>\n";
		}
		print "<td width=\"14%\">$send_day</td>\n";
		print "<td width=\"14%\">$send_hour</td>\n";
		print "<td width=\"48%\">$mag_nm</td>\n";
		print "<td width=\"24%\">$send_volume</td>\n";
		print "</tr>";
	}
	db_logoff();
}

function make_loglist2($publisher_id) {
	$conn = db_conxxxt();

	$sql = "SELECT mag_id, mag_nm, send_num, to_char(send_dt, 'YYYY/MM/DD') send_day, "
		. "to_char(send_dt, 'hh24:mi') send_hour ,send_volume FROM L_SEND "
		. "WHERE publisher_id = :PUBLISHER_ID ORDER BY MAG_NM ASC";
	$stmt = OCIParse($conn, $sql);
	$ret01 = OCIbindbyname ($stmt, ":PUBLISHER_ID", &$publisher_id, -1);
	$ret01 = OCIDefinebyname($stmt, "MAG_ID", &$mag_id);
	$ret01 = OCIDefinebyname($stmt, "MAG_NM", &$mag_nm);
	$ret01 = OCIDefinebyname($stmt, "SEND_NUM", &$send_num);
	$ret01 = OCIDefinebyname($stmt, "SEND_DAY", &$send_day);
	$ret01 = OCIDefinebyname($stmt, "SEND_HOUR", &$send_hour);
	$ret01 = OCIDefinebyname($stmt, "SEND_VOLUME", &$send_volume);
	$ncols = OCIExecute($stmt);
	$lcount = 0;
	while (OCIFetch($stmt)) {
		$lcount++;
		if ($lcount % 2 == 0) {
			print "<tr bgcolor=\"#CCCCCC\">\n";
		} else {
			print "<tr>\n";
		}
		print "<td width=\"11%\">$send_day</td>\n";
		print "<td width=\"15%\">$send_hour</td>\n";
		print "<td width=\"11%\">$mag_id</td>\n";
		print "<td width=\"48%\">$mag_nm</td>\n";
		print "<td width=\"15%\">$send_volume</td>\n";
		print "</tr>";
	}
	db_logoff();
}

/*======================================================================================*/
/*	submit Magazine	Log								*/
/*	(main)										*/
/*======================================================================================*/
if ($job_flg == "Log1") {
	$conn = db_conxxxt();
	pub_passchk($conn, $publisher_id,$owner_pswd);
	include ("mag-log.html");
} else {
	$conn = db_conxxxt();
	pub_passchk($conn, $publisher_id,$owner_pswd);
	include ("mag-log2.html");
}

?>
