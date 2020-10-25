<?php
include ("inc/pub_passchk.inc");
include ("../inc/com_path.inc");
include ("$compath/com_db_func.inc");
//include ("$compath/sql_mm.inc");
//include ("$compath/sql_mp.inc");
include ("$compath/sql_mm_riba.inc");
include ("$compath/sql_mp_riba.inc");
include ("$incpath/pub_mp_dbacces.inc");
include ("$compath/sql_mcom.inc");
include ("$compath/com_mm_func.inc");

function set_global () {
	global $inc_path;

	include ("../inc/com_path.inc");
	$inc_path = $incpath;
}

function make_maglist($publisher_id,$owner_pswd_r) {
	global $inc_path;
	global $gConn;

	$sql1 = "SELECT * FROM M_MAGAZINE WHERE mag_status_flg <> '01' and publisher_id = :PUBLISHER_ID";
	$sql1 .= " and ( mag_pub_status_flg = '01' or mag_pub_status_flg = '03' ) ";
	$sql1 .= " and ( mag_pub_stop_flg <> '02' ) ";
	$stmt1 = OCIParse($gConn, $sql1);
	$ret01 = OCIbindbyname ($stmt1, ":PUBLISHER_ID", &$publisher_id, -1);
	OCIDefinebyname($stmt1, "MAG_ID", &$mag_id);
	OCIDefinebyname($stmt1, "MAG_NM", &$mag_nm);
	$w_cnt = 0;
	$ncols = OCIExecute($stmt1);
	while (OCIFetch($stmt1)) {
		$sql2 = "SELECT count(*) T_COUNT FROM t_mailaddr  WHERE  mag_id = :MAG_ID";
		$stmt2 = OCIParse($gConn, $sql2);
		$ret01 = OCIbindbyname ($stmt2, ":MAG_ID", &$mag_id, -1);
		OCIDefinebyname($stmt2, "T_COUNT", &$t_count);
		$ncols = OCIExecute($stmt2);	
		if (OCIFetch($stmt2)) {
		} else {
			$t_count = 0;
		}
		$w_cnt++;
		$fom_nm = "status" . sprintf("%02d", $w_cnt);
		print "<form method=\"post\" action=\"mag-status.php\" name=\"$fom_nm\">\n";
		print "<tr>\n";
		print "<td width=\"53\">$mag_id</td>\n";
		print "<td width=\"182\"> \n";
		print "<div align=\"left\">$mag_nm</div>\n";
		print "</td>\n";
		print "<td width=\"110\"> \n";
		print "<div align=\"right\">\n";
		print "<a href=\"JavaScript:document.$fom_nm.submit();\">$t_count ¿Í</a></div>\n";
		print "</td>\n";
		print "</tr>\n";
		print "<input type=\"hidden\" value=\"$publisher_id\" name=\"publisher_id\">\n";
		print "<input type=\"hidden\" value=\"$owner_pswd_r\" name=\"owner_pswd\">\n";
		print "<input type=\"hidden\" value=\"$mag_id\" name=\"mag_id\">\n";
		print "<input type=\"hidden\" value=\"$mag_nm\" name=\"mag_nm\">\n";
		print "</form>\n";
	}
}

function pub_login_err($login_err) {
	if (strlen($login_err) > 0) {
		print "<p align=\"center\">¡¡</p>\n";
		print "<div align=\"center\"><font color=\"#006600\">\n";
		print "-" . $login_err . "-</font></div><BR>\n";
	}
}

function make_maglistkyu($publisher_id) {
	global $inc_path;
	global $gConn;

	$sql1 = "SELECT * FROM M_MAGAZINE WHERE mag_status_flg = '01' and publisher_id = :PUBLISHER_ID";
	$sql1 .= " and ( mag_pub_status_flg = '01' or mag_pub_status_flg = '03' ) ";
	$sql1 .= " and ( mag_pub_stop_flg <> '02' ) ";
	$stmt1 = OCIParse($gConn, $sql1);
	$ret01 = OCIbindbyname ($stmt1, ":PUBLISHER_ID", &$publisher_id, -1);
	OCIDefinebyname($stmt1, "MAG_ID", &$mag_id);
	OCIDefinebyname($stmt1, "MAG_NM", &$mag_nm);
	$ncols = OCIExecute($stmt1);
	while (OCIFetch($stmt1)) {
		print "<tr>\n";
		print "<td width=\"53\">$mag_id</td>\n";
		print "<td width=\"182\"> \n";
		print "<div align=\"left\">$mag_nm</div>\n";
		print "</td>\n";
		print "<td width=\"110\"> \n";
		print "<div align=\"right\">µÙ´©Ãæ</div>";
		print "</td>";
		print "</tr>";
	}
}

function adm_msg() {
	global $gConn;

	$w_msg = "";
	$sql1 = "SELECT * FROM T_ADMIN_MSG WHERE msg_id = 1";
	$stmt1 = OCIParse($gConn, $sql1);
	OCIDefinebyname($stmt1, "ADMIN_MSG", &$admin_msg);
	$ncols = OCIExecute($stmt1);
	if (OCIFetch($stmt1)) {
		$w_msg = trim($admin_msg);
	}
	return $w_msg;
}

/*======================================================================================*/
/*	submit login								*/
/*	(main)										*/
/*======================================================================================*/
set_global ();
$conn = db_conxxxt();
$login_err = "";
if ($nyusitu ) {
	//pub_passchk($conn, $publisher_id,$owner_pswd_r);

	$sql1 = mp_make_sql ("SELECT_M_PUBLISHER");
	$stmt1 = OCIParse($conn, $sql1);
	$ret01 = OCIbindbyname ($stmt1, ":PUBLISHER_ID", &$publisher_id, -1);
	OCIDefinebyname($stmt1, "MAIL_ADD_MAIN", &$p_mail_add_main);
	OCIDefinebyname($stmt1, "MAIL_ADD_SUB", &$p_mail_add_sub);
	OCIDefinebyname($stmt1, "OWNER_NM_KANJ", &$p_owner_nm_kanj);
	OCIDefinebyname($stmt1, "OWNER_NM_KANA", &$p_owner_nm_kana);
	OCIDefinebyname($stmt1, "OWNER_ZIP1", &$p_owner_zip1);
	OCIDefinebyname($stmt1, "OWNER_ZIP2", &$p_owner_zip2);
	OCIDefinebyname($stmt1, "OWNER_PREFEC", &$p_owner_prefec);
	OCIDefinebyname($stmt1, "OWNER_ADD", &$p_owner_add);
	OCIDefinebyname($stmt1, "OWNER_TEL", &$p_owner_tel);
	OCIDefinebyname($stmt1, "OWNER_PSWD", &$p_owner_pswd);
	OCIDefinebyname($stmt1, "PT_MELONPAN", &$p_pt_melonpan);
	OCIDefinebyname($stmt1, "PT_MELMEL", &$p_pt_melmel);
	$ncols = OCIExecute($stmt1);
	if (!OCIFetch($stmt1)) {
		$p_mail_add_main = "";
		$p_mail_add_sub= "";
		$p_owner_nm_kanj= "";
		$p_owner_nm_kana= "";
		$p_owner_zip1 = "";
		$p_owner_zip2 = "";
		$p_owner_prefec = "";
		$p_owner_add = "";
		$p_owner_tel = "";
		$p_owner_pswd = "";
		$p_pt_melonpan = "";
		$p_pt_melmel = "";
	}
	$mail_add_main = trim($p_mail_add_main);
	$ret01 = OCIFreeStatement($stmt1);
	$mdm_msg = adm_msg();
	include ("pub_index.html");
	db_logoff();
} else if ($publisher_id != "") {
	$conn = db_conxxxt();
	pub_passchk($conn, $publisher_id,$owner_pswd_r);

	$lret = pub_mp_chkrec ($conn, $publisher_id);
	if ($lret) {
		$sql1 = mp_make_sql ("SELECT_M_PUBLISHER");
		$stmt1 = OCIParse($conn, $sql1);
		$ret01 = OCIbindbyname ($stmt1, ":PUBLISHER_ID", &$publisher_id, -1);
		OCIDefinebyname($stmt1, "MAIL_ADD_MAIN", &$p_mail_add_main);
		OCIDefinebyname($stmt1, "MAIL_ADD_SUB", &$p_mail_add_sub);
		OCIDefinebyname($stmt1, "OWNER_NM_KANJ", &$p_owner_nm_kanj);
		OCIDefinebyname($stmt1, "OWNER_NM_KANA", &$p_owner_nm_kana);
		OCIDefinebyname($stmt1, "OWNER_ZIP1", &$p_owner_zip1);
		OCIDefinebyname($stmt1, "OWNER_ZIP2", &$p_owner_zip2);
		OCIDefinebyname($stmt1, "OWNER_PREFEC", &$p_owner_prefec);
		OCIDefinebyname($stmt1, "OWNER_ADD", &$p_owner_add);
		OCIDefinebyname($stmt1, "OWNER_TEL", &$p_owner_tel);
		OCIDefinebyname($stmt1, "OWNER_PSWD", &$p_owner_pswd);
		OCIDefinebyname($stmt1, "PT_MELONPAN", &$p_pt_melonpan);
		OCIDefinebyname($stmt1, "PT_MELMEL", &$p_pt_melmel);
		$ncols = OCIExecute($stmt1);
		if (!OCIFetch($stmt1)) {
			$p_mail_add_main = "";
			$p_mail_add_sub= "";
			$p_owner_nm_kanj= "";
			$p_owner_nm_kana= "";
			$p_owner_zip1 = "";
			$p_owner_zip2 = "";
			$p_owner_prefec = "";
			$p_owner_add = "";
			$p_owner_tel = "";
			$p_owner_pswd = "";
			$p_pt_melonpan = "";
			$p_pt_melmel = "";
		}
		$mail_add_main = trim($p_mail_add_main);
		$ret01 = OCIFreeStatement($stmt1);
		$mdm_msg = adm_msg();
		include ("pub_index.html");
	} else {
		$conn = db_conxxxt();
		pub_passchk($conn, $publisher_id,$owner_pswd_r);
		include ("hakkou-login.html");
	}
	db_logoff();
} else {
	include ("hakkou-login.html");
}
?>
