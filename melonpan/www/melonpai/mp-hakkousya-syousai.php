<?php
include ("../inc/com_path.inc");
include ("$compath/err_msg.inc");
include ("$compath/com_func.inc");
include ("$compath/com_db_func.inc");
include ("$compath/sql_mcom.inc");
include ("$compath/com_mm_func.inc");
include ("$incpath/mex_passchk.inc");
include ("../sub/head.inc");

function make_maglist1($publisher_id, $melonpai_id, $melonpai_pswd, $melonpai_name, $keyword) {
	global $gConn;

	$sql = "SELECT * FROM M_MAGAZINE WHERE";
	$sql .= " (mag_pub_status_flg = '01' or mag_pub_status_flg = '03')";
	$sql .= " and publisher_id = " . $publisher_id;
	$stmt = OCIParse($gConn, $sql);
	OCIDefinebyname($stmt, "MAG_ID", &$mag_id);
	OCIDefinebyname($stmt, "MAG_NM", &$mag_nm);
	$ncols = OCIExecute($stmt, OCI_DEFAULT);
	$lcount = 0;
	while (OCIFetch($stmt)) {
		$lcount = $lcount + 1;
		$fom_name =  "form" . sprintf("%03d", $lcount);
		print "<form name=$fom_name action=\"mp-mag-syousai.php\" method=\"post\">\n";
		print "<tr>";
		print "<td width=\"100\" align=\"center\">$mag_id</td>";
		print "<td> <a href= \"JavaScript:document.$fom_name.submit();\">$mag_nm</a></td>";
		print "<input type=\"hidden\" value=\"$melonpai_id\" name=\"melonpai_id\">\n";
		print "<input type=\"hidden\" value=\"$melonpai_pswd\" name=\"melonpai_pswd\">\n";
		print "<input type=\"hidden\" value=\"$melonpai_name\" name=\"melonpai_name\">\n";
		print "<input type=\"hidden\" value=\"$mag_id\" name=\"mag_id\">\n";
		print "<input type=\"hidden\" value=\"$keyword\" name=\"keyword\">\n";
		print "<input type=\"hidden\" value=\"$publisher_id\" name=\"publisher_id\">\n";
		print "</tr>\n";
		print "</form>\n";
	}
	OCIFreeStatement($stmt);
}

/****************************************************************
 Main
****************************************************************/
$conn = db_conxxxt();
mex_passchk($conn, $melonpai_id, $melonpai_pswd);
$sql = "SELECT * FROM M_PUBLISHER WHERE publisher_id = " . $publisher_id;
$stmt = OCIParse($conn, $sql);
OCIDefinebyname($stmt, "MAIL_ADD_MAIN", &$mail_add_main);
OCIDefinebyname($stmt, "MAIL_ADD_SUB", &$mail_add_sub);
OCIDefinebyname($stmt, "OWNER_NM_KANJ", &$owner_nm_kanj);
OCIDefinebyname($stmt, "OWNER_NM_KANA", &$owner_nm_kana);
OCIDefinebyname($stmt, "OWNER_ZIP1", &$owner_zip1);
OCIDefinebyname($stmt, "OWNER_ZIP2", &$owner_zip2);
OCIDefinebyname($stmt, "OWNER_PREFEC", &$owner_prefec);
OCIDefinebyname($stmt, "OWNER_ADD", &$owner_add);
OCIDefinebyname($stmt, "OWNER_TEL", &$owner_tel);
OCIDefinebyname($stmt, "OWNER_PSWD", &$owner_pswd);
OCIDefinebyname($stmt, "PT_MELONPAN", &$pt_melonpan);
OCIDefinebyname($stmt, "PT_MELMEL", &$pt_melmel);
$ncols = OCIExecute($stmt, OCI_DEFAULT);
if (!(OCIFetch($stmt))) {
	OCIFreeStatement($stmt);
	db_logoff();
	err_msg ("PM002", $p_titl);
	exit();
}
OCIFreeStatement($stmt);
include("tmpl_html/mp-hakkousya-syousai.html");
db_logoff();
?>
