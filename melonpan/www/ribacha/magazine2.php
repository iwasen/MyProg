<?php
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

function make_sel_maglist($publisher_id) {
	global $inc_path;

	$conn = db_conxxxt();
//	$sql = "SELECT * FROM M_MAGAZINE WHERE mag_id = " . $publisher_id;
	$sql = "SELECT * FROM M_MAGAZINE WHERE publisher_id = :PUBLISHER_ID";
	//$sql .= " and ( mag_status_flg = '00' )";
	$sql .= " and ( mag_pub_status_flg = '01' or mag_pub_status_flg = '03' ) ";
	$sql .= " and ( mag_pub_stop_flg <> '02' ) ";
	$stmt = OCIParse($conn, $sql);
	$ret01 = OCIbindbyname ($stmt, ":PUBLISHER_ID", &$publisher_id, -1);
	include("$inc_path/pub_mm_defmane.def");
	$ncols = OCIExecute($stmt);
	$lcount = 0;
	while (OCIFetch($stmt)) {
		$lcount++;
		$btn_name =  "recomend_mag_data";
		$fom_name =  "formsubmit" . sprintf("%03d", $lcount);
		print "<form name=\"$fom_name\" action=\"magazine2.php\" method=\"post\">\n";
		print "<tr>\n";
		print "<td width=\"67\" align=\"center\">$mag_id</td>\n";
		print "<td width=\"337\">$mag_nm</td>\n";
		print "<td width=\"82\">\n";
	  	print "<input type=\"submit\" name=\"$btn_name\" value=\"¿äÁ¦Ê¸±ÜÍ÷\">";
		print "</td></tr>\n";
		print "<input type=\"hidden\" value=$publisher_id name=\"publisher_id\">\n";
		print "<input type=\"hidden\" value=$mag_id name=\"mag_id\">\n";
		print "<input type=\"hidden\" value=$mag_nm name=\"mag_nm\">\n";
		print "<input type=\"hidden\" value=\"VIWE\" name=\"job_flg\">\n";
		print "</form>\n";
	}
	db_logoff();
}

function make_title_list($publisher_id, $mag_id) {
	$conn = db_conxxxt();
	$sql = "SELECT * FROM T_RECOMEND WHERE open_flg = '1' and mag_id = :MAG_ID";
	$stmt = OCIParse($conn, $sql);
	$ret01 = OCIbindbyname ($stmt, ":MAG_ID", &$mag_id, -1);
	OCIDefinebyname($stmt, "RECOMEND_ID", &$recomend_id);	// ÄÌ¤·ÈÖ¹æ
	OCIDefinebyname($stmt, "RECOM_TITL", &$recom_titl);
	$ncols = OCIExecute($stmt);
	$lcount = 0;
	while (OCIFetch($stmt)) {
		$lcount++;
		$btn_name =  "edit_mag_data";
		$fom_name =  "formsubmit" . sprintf("%03d", $lcount);
		print "<form name=\"$fom_name\" action=\"magazine2.php\" method=\"post\">\n";
		print "<tr>\n";
		print "<td width=\"400\"><a href=\"JavaScript:document.$fom_name.submit();\">$recom_titl</a></td>\n";
//		print "<td width=\"400\">$recom_titl</td>\n";
		print "</tr>\n";
		print "<input type=\"hidden\" value=$publisher_id name=\"publisher_id\">\n";
		print "<input type=\"hidden\" value=$recomend_id name=\"recomend_id\">\n";
		print "<input type=\"hidden\" value=\"COMENT\" name=\"job_flg\">\n";
		print "</form>\n";
	}
	db_logoff();
}

/*======================================================================================*/
/*	Magazine									*/
/*	(main)										*/
/*======================================================================================*/
set_global ();
$p_titl = "¥á¥ë¥Þ¥¬¿äÁ¦Ê¸";
if ($job_flg == "RECOMEND") {
	include ("suisen-ichiran.html");
} elseif ($job_flg == "VIWE") {
	include ("suisen-title.html");
} elseif ($job_flg == "COMENT") {
	include ("suisenbun.php");
} elseif ($job_flg == "KAKUNIN") {
	if ($Koukai) {
		include ("suisen-koukai.html");
	} else {
		include ("suisen-sakujyo.html");
	}
} elseif ($job_flg == "OK1" || $job_flg == "NG1") {
	$conn = db_conxxxt();
	$sql = "SELECT * FROM T_RECOMEND WHERE recomend_id = :RECOMEND_ID";
	$stmt = OCIParse($conn, $sql);
	$ret01 = OCIbindbyname ($stmt, ":RECOMEND_ID", &$recomend_id, -1);
	$ncols = OCIExecute($stmt);
	if (!(OCIFetch($stmt))) {
		err_msg ("PM001", $p_titl);
	} else {
		$ret01 = OCIFreeStatement($stmt);
		if ($job_flg == "OK1") {
			$flgVal = "1";
		} else {
			$flgVal = "2";
		}
		if ($job_flg == "OK1") {
			$sql = "UPDATE T_RECOMEND SET open_flg = '" . $flgVal
					. "' WHERE recomend_id = :RECOMEND_ID";
		} else {
			$sql = "delete from T_RECOMEND WHERE recomend_id = :RECOMEND_ID";
		}
		$stmt = OCIParse($conn, $sql);
		$ret01 = OCIbindbyname ($stmt, ":RECOMEND_ID", &$recomend_id, -1);
		if (!(OCIExecute($stmt))) {
			err_msg ("PM001", $p_titl);
		} else {
			OCICommit ($conn);
			$ret01 = OCIFreeStatement($stmt);
			if ($job_flg == "OK1") {
				include ("suisen-koukai-ok.html");
			} else {
				include ("suisen-sakujyo-ok.html");
			}
		}
	}
	db_logoff();
}
?>
