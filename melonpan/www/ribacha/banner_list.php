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
		. "mag_pub_status_flg = '03') and publisher_id = :PUBLISHER_ID";
	$stmt = OCIParse($gConn, $sql);
	$ret01 = OCIbindbyname ($stmt, ":PUBLISHER_ID", &$publisher_id, -1);
	$ret01 = OCIDefinebyname($stmt, "MAG_ID", &$mag_id);
	$ret01 = OCIDefinebyname($stmt, "MAG_NM", &$mag_nm);
	$ncols = OCIExecute($stmt, OCI_DEFAULT);
	$lcount = 0;
	while (OCIFetch($stmt)) {
		$lcount++;
		$fom_name =  "formsubmit" . sprintf("%03d", $lcount);
		print "<form name=$fom_name action=\"banner_data.php\" method=\"post\">\n";
		print "<tr>\n";
		print "<td width=\"67\" align=\"center\" class=\"text9ptnoColor\">$mag_id</td>\n";
		print "<td width=\"337\" class=\"text9ptnoColor\">$mag_nm</td>\n";
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
$conn = db_conxxxt();
pub_passchk($conn, $publisher_id,$owner_pswd);
include ("banner_list.html");
db_logoff();
?>
