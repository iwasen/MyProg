<?php
include ("inc/pub_passchk.inc");
include ("../inc/com_path.inc");
include ("$compath/err_msg.inc");
include ("$compath/com_db_func.inc");
//include ("$compath/sql_mm.inc");
include ("$compath/sql_mm_riba.inc");
//include ("$compath/sql_mp.inc");
include ("$compath/sql_mp_riba.inc");
include ("$compath/sql_mcom.inc");
include ("$compath/com_mm_func.inc");
include ("$incpath/pub_mm_dbacces.inc");
include ("$incpath/pub_mm_func.inc");
include ("$incpath/pub_s_mail.inc");

function set_global () {
	global $inc_path;

	include ("../inc/com_path.inc");
	$inc_path = $incpath;

}

function make_maglist($publisher_id,$owner_pswd) {
	global $inc_path;

	$conn = db_conxxxt();
	$sql = mm_make_sql ("SELECT_M_MAGAZINE_PUB_K");
	$stmt = OCIParse($conn, $sql);
	$ret01 = OCIbindbyname ($stmt, ":PUBLISHER_ID", &$publisher_id, -1);
	include("$inc_path/pub_mm_defmane.def");
	$ncols = OCIExecute($stmt, OCI_DEFAULT);
	$lcount = 0;
	while (OCIFetch($stmt)) {
		$lcount++;
		$fom_name =  "formsubmit" . sprintf("%03d", $lcount);
		print "<tr>\n";
		print "<td width=\"98\"align=\"center\">$mag_id</td>\n";
		print "<td width=\"289\">$mag_nm</td>\n";
		print "<form name=$fom_name action=\"magazine.php\" method=\"post\">\n";
		print "<td width=\"155\">\n";
		print "<div align=\"center\">\n";
	  	print "<input type=\"submit\" name=\"inp_body\" value=\"�ٴ�\">\n";
	  	print "<input type=\"submit\" name=\"inp_kyu1\" value=\"�Ѵ�\">\n";
		print "<input type=\"hidden\" value=\"$owner_pswd\" name=\"owner_pswd\">\n";
		print "<input type=\"hidden\" value=\"$publisher_id\" name=\"publisher_id\">\n";
		print "<input type=\"hidden\" value=\"$mag_id\" name=\"mag_id\">\n";
		print "<input type=\"hidden\" value=\"StopDel2\" name=\"job_flg\">\n";
		print "</div>\n";
		print "</td>\n";
		print "</form>\n";
		print "</tr>\n";
//		if ($lcount > 10) { break; }
	}
	db_logoff();
}

function make_maglist_f($publisher_id,$owner_pswd) {
global $inc_path;
	$conn = db_conxxxt();
	$sql = mm_make_sql ("SELECT_M_MAGAZINE_PUB_F");
	$stmt = OCIParse($conn, $sql);
	$ret01 = OCIbindbyname ($stmt, ":PUBLISHER_ID", &$publisher_id, -1);
	include("$inc_path/pub_mm_defmane.def");
	$ncols = OCIExecute($stmt, OCI_DEFAULT);
	$lcount = 0;
	while (OCIFetch($stmt)) {
		$lcount++;
		$btn_name =  "repl_mag";
		$fom_name =  "formsubmit" . sprintf("%03d", 100 + $lcount);
		print "<tr>\n";
		print "<td width=\"98\"align=\"center\">$mag_id</td>\n";
		print "<td width=\"289\">$mag_nm</td>\n";
		print "<form name=$fom_name action=\"magazine.php\" method=\"post\">\n";
		print "<td width=\"155\">\n";
		print "<div align=\"center\">\n";
	  	print "<input type=\"submit\" name=$btn_name value=\"����\">\n";
	  	print "<input type=\"submit\" name=\"inp_kyu1\" value=\"�Ѵ�\">\n";
		print "<input type=\"hidden\" value=\"$publisher_id\" name=\"publisher_id\">\n";
		print "<input type=\"hidden\" value=\"$owner_pswd\" name=\"owner_pswd\">\n";
		print "<input type=\"hidden\" value=\"$mag_id\" name=\"mag_id\">\n";
		print "<input type=\"hidden\" value=\"RepMag\" name=\"job_flg\">\n";
		print "</div>\n";
		print "</td>\n";
		print "</form>\n";
		print "</tr>\n";
//		if ($lcount > 10) { break; }
	}
	db_logoff();
}

function chk_mag_nm($mag_id, $mag_nm) {
	global $gConn;

	$name_msg = "";
	$mag_nm = ereg_replace("'","��",$mag_nm);
	$sql = "SELECT * FROM M_MAGAZINE WHERE mag_nm = '" . trim($mag_nm) . "'";
	$stmt = OCIParse($gConn, $sql);
	OCIDefinebyname($stmt, "MAG_ID", &$chk_mag_id);
	$ncols = OCIExecute($stmt, OCI_DEFAULT);
	while (OCIFetch($stmt))
	{
		if ($chk_mag_id != $mag_id)
		{
			//$name_msg = "��Ʊ�������ȥ�Υ��ޥ���ͭ��ޤ�����������Ǥ�������<br><br>\n";
			//break;
			$print_msg = "��Ʊ�������ȥ�Υ��ޥ���ͭ��ޤ��Τ���Ͽ�Ǥ��ޤ�����äƽ������Ƥ���������<br><br>\n";
			$p_titl = "���ޥ�����";
			include ("err-msg.html");
			exit();
		}
	}
	return $name_msg;
}

function chk_mag_rec($mag_nm, $publisher_id) {
	global $gConn;

	$l_ret = FALSE;
	$mag_nm = ereg_replace("'","��",$mag_nm);
	$sql = "SELECT * FROM M_MAGAZINE WHERE mag_nm = '" . trim($mag_nm) . "'"
			. "and publisher_id = " . $publisher_id;
	$stmt = OCIParse($gConn, $sql);
	$ncols = OCIExecute($stmt, OCI_DEFAULT);
	if (OCIFetch($stmt))
	{
		$l_ret = TRUE;
	}
	return $l_ret;
}

/*======================================================================================*/
/*	Magazine									*/
/*	(main)										*/
/*======================================================================================*/
set_global ();
$conn = db_conxxxt();
pub_passchk($conn, $publisher_id,$owner_pswd);
db_logoff();

if ($job_flg == "New") {
	$conn = db_conxxxt();
	$next_job_flg = "Vew";
	include ("mag-information.html");
	db_logoff();
} elseif ($job_flg == "New2") {
	$conn = db_conxxxt();
	$next_job_flg = "Vew2";
	include ("mag-newinfo.html");
	db_logoff();
} elseif ($job_flg == "Vew" || $job_flg == "Vew2" || $job_flg == "Vew_ed") {
	$mag_nm2 = ereg_replace("\n","",$mag_nm);
	$mag_nm = htmlspecialchars($mag_nm2);
	$mag_intro_long = htmlspecialchars($mag_intro_long);
	$mag_intro_short = htmlspecialchars($mag_intro_short);
	include ("$incpath/mag_inp_chk.inc");		//���ϥ����å�
	if ($Chk_flg) {
		$conn = db_conxxxt();
		if ($job_flg == "Vew_ed") {
			$next_job_flg = "Ins_ed";
			$name_msg = chk_mag_nm($mag_id, $mag_nm);
			include ("mag-info-syusei-kakunin.html");
		} else {
			if ($job_flg == "Vew2") {
				$next_job_flg = "Ins2";
			} else {
				$next_job_flg = "Ins";
			}
			$name_msg = chk_mag_nm($mag_id, $mag_nm);
			include ("mag-kakunin.html");
		}
		db_logoff();
	} else {
		$p_titl = "���ޥ�����";
		include ("err-msg.html");
	}
} elseif ($job_flg == "Ins" || $job_flg == "Ins2" || $job_flg == "Ins_ed") {
		$mag_nm = ereg_replace("'","''",$mag_nm);
		$mag_intro_long = ereg_replace("'","''",$mag_intro_long);
		$mag_intro_short = ereg_replace("'","''",$mag_intro_short);
		$mag_type = ereg_replace("'","''",$mag_type);
	$p_titl ="���ޥ�����";
//	$conn = db_conxxxt();
	$ins_f = FALSE;
	if ($job_flg == "Ins" || $job_flg == "Ins2") {
		if (chk_mag_rec($mag_nm, $publisher_id)) {
			$print_msg =  "��" . trim($mag_nm) . "����Ͽ�ѤߤǤ���";
			include ("err-msg.html");
		} else {
			if (($mag_id = update_squ_key ($conn, "M_MAGAZINE")) != "-1") {
				OCICommit($conn);
				$ins_f = TRUE;
			} else {
				err_msg ("PP002", "���ޥ�����");
			}
		}
	} else {
		$ins_f = TRUE;
	}
	if ($ins_f) {
		db_begin_trans();
		if (pub_mm_insert ($conn, $mag_id, $publisher_id, $mail_add_publish, $mag_nm,
                		$mag_intro_long, $mag_intro_short, $mag_hp, $mag_type, $mag_cycle,
                		$mag_cycle_day, $mag_catg_1, $mag_catg_2, $mag_bg_1, $mag_bg_2,
                		$mag_reader_sex, $mag_reader_age, $mag_reader_area,
						$mag_reader_occup, $mag_bn_pub, $ml_type,$company_nm,$add_point)) {
//			OCICommit($conn);
			db_commit_trans();
			$next_job_flg = "Menu";
			if ($job_flg == "Ins_ed") {
			//	include ("mag-info-syusei-ok.html");
				include("riba-atri-syusei.php");
			} else {
				if ($job_flg == "Ins") {
					//include ("mag-kanryou.html");
					include("riba-atri.php");
				} else {
					//include ("mag-newkanryou.html");
					include("riba-newatri.php");
				}
				$sql = "SELECT * FROM M_PUBLISHER WHERE publisher_id = " . $publisher_id;
				$result = db_exec($sql);
				if (pg_numrows($result)) {
					$fetch = pg_fetch_object($result, 0);
					$mail_add_main = $fetch->mail_add_main;
					mag_new_mail($owner_nm_kanj, $mail_add_main, $mag_nm);
				}
/* **
				$stmt = OCIParse($conn, $sql);
				$ret01 = OCIDefinebyname($stmt, "OWNER_NM_KANJ", &$owner_nm_kanj);
				OCIExecute($stmt, OCI_DEFAULT);
				OCIFetch($stmt);
*/
			}
		} else {
//			OCIRollback($conn);
			db_rollback();
			err_msg ("PP002", "���ޥ�����");
		}
	}
//	db_logoff();
} elseif ($job_flg == "Stop1") {
	include ("mag-saku-sentaku.html");
} elseif ($job_flg == "StopDel2") {
	if ($inp_body) {
		$next_job_flg = "Stop3";
		include ("mag-yasumi.php");
	} elseif ($inp_kyu1) {
		$next_job_flg = "Del3";
		include ("mag-sakujyo.php");
	}
} elseif ($job_flg == "Stop3") {
	$next_job_flg = "Stop4";
	include ("mag-yasumi-kakunin.html");
} elseif ($job_flg == "Stop4") {
	$next_job_flg = "Menu";
	include ("mag-yasumi-ok.php");
} elseif ($job_flg == "Del3") {
	$next_job_flg = "Del4";
	include ("mag-sakujyo-kakunin.html");
} elseif ($job_flg == "Del4") {
	$next_job_flg = "Menu";
	include ("mag-sakujyo-ok.php");
} elseif ($job_flg == "RepMag") {
	if ($inp_kyu1) {
		$next_job_flg = "Del3";
		include ("mag-sakujyo.php");
	} else {
		$next_job_flg = "Menu";
		include ("mag-comeback.php");
	}
}
?>
