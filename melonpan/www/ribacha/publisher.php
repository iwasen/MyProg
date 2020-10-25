<?php
include ("../inc/com_path.inc");
include ("$compath/err_msg.inc");
include ("$compath/com_db_func.inc");
include ("$compath/com_mm_func.inc");
//include ("$compath/sql_mm.inc");
//include ("$compath/sql_mp.inc");
include ("$compath/sql_mm_riba.inc");
include ("$compath/sql_mp_riba.inc");
include ("$incpath/pub_mp_dbacces.inc");
include ("inc/pub_passchk.inc");

function set_global () {
	global $inc_path;

	include ("../inc/com_path.inc");
	$inc_path = $incpath;
}

function make_maglist1($publisher_id) {
	global $inc_path;
	global $gConn;

	$sql = mm_make_sql ("SELECT_M_MAGAZINE_PUB");
	$stmt = OCIParse($gConn, $sql);
	$ret01 = OCIbindbyname ($stmt, ":PUBLISHER_ID", &$publisher_id, -1);
	include("$inc_path/pub_mm_defmane.def");
	$ncols = OCIExecute($stmt, OCI_DEFAULT);
	$cnt=0;
	while (OCIFetch($stmt)) {
		$sql2 = "SELECT count(*) T_COUNT FROM t_mailaddr  WHERE  mag_id = :MAG_ID";
		$stmt2 = OCIParse($gConn, $sql2);
		$ret01 = OCIbindbyname ($stmt2, ":MAG_ID", &$mag_id, -1);
		OCIDefinebyname($stmt2, "T_COUNT", &$t_count);
		$ncols = OCIExecute($stmt2);	
		if (OCIFetch($stmt2)) {
		} else {
			$t_count = 0;
		}
		print "<tr>\n";
		print "<td width=\"14%\">$mag_id</td>\n";
		print "<td width=\"67%\">$mag_nm</td>\n";
		print "<td width=\"19%\"> \n";
		if ($mag_status_flg == "01") {
			print "<div align=\"right\">休刊中</div>\n";
		} else {
			print "<div align=\"right\">$t_count 人</div>\n";
		}
		print "</td>\n";
		print "</tr>\n";
	$cnt++;
	}
	if ($cnt == 0) { print "<tr><td colspan=\"3\">ありません</td></tr>\n"; }
}

function make_maglist_red($publisher_id) {
	global $inc_path;
	global $gConn;

	$sql = mm_make_sql ("SELECT_M_MAGAZINE_PUB_RED");
	$stmt = OCIParse($gConn, $sql);
	$ret01 = OCIbindbyname ($stmt, ":PUBLISHER_ID", &$publisher_id, -1);
	include("$inc_path/pub_mm_defmane.def");
	$ncols = OCIExecute($stmt, OCI_DEFAULT);
	$cnt=0;
	while (OCIFetch($stmt)) {
		$sql2 = "SELECT count(*) T_COUNT FROM t_mailaddr  WHERE  mag_id = :MAG_ID";
		$stmt2 = OCIParse($gConn, $sql2);
		$ret01 = OCIbindbyname ($stmt2, ":MAG_ID", &$mag_id, -1);
		OCIDefinebyname($stmt2, "T_COUNT", &$t_count);
		$ncols = OCIExecute($stmt2);	
		if (OCIFetch($stmt2)) {
		} else {
			$t_count = 0;
		}
		print "<tr>\n";
		print "<td width=\"14%\">$mag_id</td>\n";
		print "<td width=\"67%\">$mag_nm</td>\n";
		print "<td width=\"19%\"> \n";
		if ($mag_pub_status_flg == "00") {
			print "<div align=\"right\">未承認</div>\n";
		} elseif ($mag_pub_status_flg == "02") {
			print "<div align=\"right\">承認拒否</div>\n";
		} elseif ($mag_pub_stop_flg == "02") {
			print "<div align=\"right\">レッドカード</div>\n";
		} else {
			print "<div align=\"right\">$t_count 人</div>\n";
		}
		print "</td>\n";
		print "</tr>\n";
		$cnt++;
	}
	if ($cnt == 0) { print "<tr><td colspan=\"3\">ありません</td></tr>\n"; }
}

/*======================================================================================*/
/*	Publisher									*/
/*	(main)										*/
/*======================================================================================*/
set_global ();
$conn = db_conxxxt();
pub_passchk($conn, $publisher_id,$owner_pswd);
if ($job_flg == "New") {
	if (($publisher_id = update_squ_key ($conn, "M_PUBLISHER")) != "-1") {
		OCICommit($conn);
		$next_job_flg = "Vew";
		include ("hakkousya-info.html");
	} else {
		OCIRollback($conn);
		err_msg ("PP001", "発行者情報");
	}
	db_logoff();
} elseif ($job_flg == "Vew" || $job_flg == "Vew_ed") {
	if ($job_flg == "Vew") {
		include ("$incpath/pub_inp_chk.inc");		//入力チェック
	} elseif ($job_flg == "Vew_ed") {
		include ("$incpath/pub_inp_chk_syusei.inc");		//入力チェック
	}
	if ($Chk_flg) {
		$next_job_flg = "Ins";
		if ($job_flg == "Vew_ed") {
			$next_job_flg = "Ins_ed";
			include ("henkou-kakunin.html");
		} else {
			include ("hakkousya-info-kakunin.html");
		}
	} else {
		$p_titl = "発行者情報";
		include ("err-msg.html");
	}
} elseif ($job_flg == "Ins" || $job_flg == "Ins_ed") {
	$conn = db_conxxxt();
	//if ($job_flg == "Ins") { $owner_pswd_new = $owner_pswd; }
		$owner_nm_kanj = ereg_replace("'","''",$owner_nm_kanj);
		$owner_nm_kana = ereg_replace("'","''",$owner_nm_kana);
		$owner_prefec = ereg_replace("'","''",$owner_prefec);
		$owner_pswd_new = ereg_replace("'","''",$owner_pswd_new);
	if (pub_update ($conn, $publisher_id, $mail_add_main, $mail_add_sub,
			$owner_nm_kanj, $owner_nm_kana, $owner_zip1, $owner_zip2,
			$owner_prefec, $owner_add, $owner_tel, $owner_pswd_new) ) {
		OCICommit($conn);
		if ($job_flg == "Ins") {
			$next_job_flg = "New";
			include ("hakkousya-info-kanryou.html");
		} else {
			$next_job_flg = "Menu";
			include ("henkou-kanryou.html");
		}
	} else {
		OCIRollback($conn);
		err_msg ("PP002", "発行者情報");
	}
	db_logoff();
} elseif ($job_flg == "Edi") {
	$next_job_flg1 = "Edi1";
	$next_job_flg2 = "Del1";
	$next_job_flg3 = "Stop1";
	include ("hakkousya-henkou.html");
} elseif ($job_flg == "Edi1") {
	$next_job_flg = "Vew_ed";
	include ("hakkousya-henkou1.php");
} elseif ($job_flg == "Del1") {
	$next_job_flg = "Del2";
	$conn = db_conxxxt();
	include ("hakkousya-kaijyo.php");
	db_logoff();
} elseif ($job_flg == "Del2") {
	$next_job_flg = "Del3";
	include ("kaijyo-kakunin.php");
} elseif ($job_flg == "Del3") {
	include ("kaijyo-kanryou.php");
}
db_logoff();

?>
