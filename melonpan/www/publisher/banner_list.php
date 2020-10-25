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

function make_maglist($publisher_id,$owner_pswd) {
	global $gConn;

	$sql = "SELECT * FROM M_MAGAZINE WHERE (mag_pub_status_flg = '01' or "
		. "mag_pub_status_flg = '03') and publisher_id = $publisher_id";
	$result = db_exec($sql);
	$nrow = pg_numrows($result);
	$lcount = 0;
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);
		$mag_id = $fetch->mag_id;
		$mag_nm = $fetch->mag_nm;
		$lcount++;
		$fom_name =  "formsubmit" . sprintf("%03d", $lcount);
		print "<form name=$fom_name action=\"banner_data.php\" method=\"post\">\n";
		print "<tr>\n";
		print "<td width=\"67\" align=\"center\" class=\"text9ptnoColor\">$mag_id</td>\n";
		print "<td width=\"337\" class=\"text9ptnoColor\">".htmlspecialchars($mag_nm)."</td>\n";
		print "<td width=\"82\"> \n";
		print "<input type=\"submit\" value=\"バナー発行\">\n";
		print "</td>\n";
		print "</tr>\n";
		print "<input type=\"hidden\" value=\"$publisher_id\" name=\"publisher_id\">\n";
		print "<input type=\"hidden\" value=\"$owner_pswd\" name=\"owner_pswd\">\n";
		print "<input type=\"hidden\" value=\"$mag_id\" name=\"mag_id\">\n";
		print "<input type=\"hidden\" value=\"".trim($mag_nm)."\" name=\"mag_nm\">\n";
		print "</form>\n";
	}
}
/*======================================================================================*/
/*	(main)										*/
/*======================================================================================*/
pub_passchk($conn, $publisher_id,$owner_pswd);
include ("banner_list.html");
?>
