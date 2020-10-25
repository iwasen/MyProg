<?php
include ("inc/pub_passchk.inc");
include ("../inc/com_path.inc");
include ("$compath/err_msg.inc");
include ("$compath/com_db_func.inc");

function make_loglist($publisher_id) {
	$sql = "SELECT mag_nm, send_num, to_char(send_dt, 'YYYY/MM/DD') as send_day, "
		. "to_char(send_dt, 'hh24:mi') as send_hour ,send_volume FROM L_SEND "
		. "WHERE publisher_id = $publisher_id AND send_dt>current_date - interval '1 year' ORDER BY SEND_DT DESC";
	$result = db_exec($sql);
	$nrow = pg_numrows($result);
	$lcount = 0;
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);
		$mag_nm = $fetch->mag_nm;
		$send_volume = $fetch->send_volume;
		$send_day = $fetch->send_day;
		$send_hour = $fetch->send_hour;
		$lcount++;
		if ($lcount % 2 == 0) {
			print "<tr bgcolor=\"#CCCCCC\">\n";
		} else {
			print "<tr>\n";
		}
		print "<td width=\"14%\">$send_day</td>\n";
		print "<td width=\"14%\" align=\"center\">$send_hour</td>\n";
		print "<td width=\"48%\">$mag_nm</td>\n";
		print "<td width=\"24%\" align=\"right\">".number_format($send_volume). "</td>\n";
		print "</tr>";
	}
}

function make_loglist2($publisher_id) {

	$sql = "SELECT mag_id, mag_nm, send_num, to_char(send_dt, 'YYYY/MM/DD') as send_day, "
		. "to_char(send_dt, 'hh24:mi') as send_hour ,send_volume FROM L_SEND "
		. "WHERE publisher_id = $publisher_id AND send_dt>current_date - interval '1 year' ORDER BY MAG_NM ASC";
$result = db_exec($sql);
$nrow = pg_numrows($result);
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);
	$mag_id = $fetch->mag_id;
	$mag_nm = $fetch->mag_nm;
	$send_num = $fetch->send_num;
	$send_day = $fetch->send_day;
	$send_hour = $fetch->send_hour;
	$send_volume = $fetch->send_volume;
		$lcount++;
		if ($lcount % 2 == 0) {
			print "<tr bgcolor=\"#CCCCCC\">\n";
		} else {
			print "<tr>\n";
		}
		print "<td width=\"11%\">$send_day</td>\n";
		print "<td width=\"15%\" align=\"center\">$send_hour</td>\n";
		print "<td width=\"11%\">$mag_id</td>\n";
		print "<td width=\"48%\">$mag_nm</td>\n";
		print "<td width=\"15%\" align=\"right\">".number_format($send_volume)."</td>\n";
		print "</tr>";
	}
}

/*======================================================================================*/
/*	submit Magazine	Log								*/
/*	(main)										*/
/*======================================================================================*/
if ($job_flg == "Log1") {
	pub_passchk($conn, $publisher_id,$owner_pswd);
	include ("mag-log.html");
} else {
	pub_passchk($conn, $publisher_id,$owner_pswd);
	include ("mag-log2.html");
}

?>
