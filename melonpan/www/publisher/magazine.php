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

function set_global () {
	global $inc_path;

	include ("../inc/com_path.inc");
	$inc_path = $incpath;
}

function make_maglist($publisher_id,$owner_pswd) {
	global $inc_path;

	$sql = "SELECT * FROM M_MAGAZINE"
		. " WHERE publisher_id = $publisher_id AND mag_status_flg = '00' and (mag_pub_status_flg = '01' or mag_pub_status_flg = '03')"
		. " and (mag_pub_stop_flg <> '02')";
	$result = db_exec($sql);
	$nrow = pg_numrows($result);
	$lcount = 0;
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);
		$mag_id = $fetch->mag_id;
		$mag_nm = $fetch->mag_nm;
		$publisher_id = $fetch->publisher_id;

		$lcount++;
		$fom_name =  "formsubmit" . sprintf("%03d", $lcount);
		print "<tr>\n";
		print "<td width=\"98\"align=\"center\">$mag_id</td>\n";
		print "<td width=\"289\">$mag_nm</td>\n";
		print "<form name=$fom_name action=\"magazine.php\" method=\"post\">\n";
		print "<td width=\"155\">\n";
		print "<div align=\"center\">\n";
	  	print "<input type=\"submit\" name=\"inp_body\" value=\"休刊\">\n";
	  	print "<input type=\"submit\" name=\"inp_kyu1\" value=\"廃刊\">\n";
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
}

function make_maglist_f($publisher_id,$owner_pswd) {
global $inc_path;
	$sql = mm_make_sql ("SELECT_M_MAGAZINE_PUB_F");
	$sql = "SELECT * FROM M_MAGAZINE"
		. " WHERE publisher_id = $publisher_id AND mag_status_flg = '01' and (mag_pub_status_flg = '01' or mag_pub_status_flg = '03')"
		. " and (mag_pub_stop_flg <> '02')";
	$result = db_exec($sql);
	$nrow = pg_numrows($result);
	$lcount = 0;
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);
		$mag_id = $fetch->mag_id;
		$mag_nm = $fetch->mag_nm;
		$publisher_id = $fetch->publisher_id;

		$lcount++;
		$btn_name =  "repl_mag";
		$fom_name =  "formsubmit" . sprintf("%03d", 100 + $lcount);
		print "<tr>\n";
		print "<td width=\"98\"align=\"center\">$mag_id</td>\n";
		print "<td width=\"289\">$mag_nm</td>\n";
		print "<form name=$fom_name action=\"magazine.php\" method=\"post\">\n";
		print "<td width=\"155\">\n";
		print "<div align=\"center\">\n";
	  	print "<input type=\"submit\" name=$btn_name value=\"復刊\">\n";
	  	print "<input type=\"submit\" name=\"inp_kyu1\" value=\"廃刊\">\n";
		print "<input type=\"hidden\" value=\"$publisher_id\" name=\"publisher_id\">\n";
		print "<input type=\"hidden\" value=\"$owner_pswd\" name=\"owner_pswd\">\n";
		print "<input type=\"hidden\" value=\"$mag_id\" name=\"mag_id\">\n";
		print "<input type=\"hidden\" value=\"RepMag\" name=\"job_flg\">\n";
		print "</div>\n";
		print "</td>\n";
		print "</form>\n";
		print "</tr>\n";
	}
}

function chk_mag_nm($mag_id, $mag_nm) {
	global $gConn;

	$name_msg = "";
//	$mag_nm = ereg_replace("'","''",$mag_nm);
	$sql = "SELECT * FROM M_MAGAZINE WHERE mag_nm = '" . trim($mag_nm) . "'";
	$result = db_exec($sql);
	$nrow = pg_numrows($result);
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);
		$chk_mag_id = $fetch->mag_id;
		if ($chk_mag_id != $mag_id) {
			//$name_msg = "・同じタイトルのメルマガが有りますが、よろしいですか？。<br><br>\n";
			//break;
			$print_msg = "・同じタイトルのメルマガが有りますので登録できません。戻って修正してください。<br><br>\n";
			$p_titl = "メルマガ情報";
			include ("err-msg.html");
			exit();
		}
	}
	return $name_msg;
}

function chk_mag_rec($mag_nm, $publisher_id) {
	global $gConn;

	$l_ret = FALSE;
//	$mag_nm = ereg_replace("'","''",$mag_nm);
	$sql = "SELECT * FROM M_MAGAZINE WHERE mag_nm = '" . trim($mag_nm) . "'"
			. " and publisher_id = " . $publisher_id;
	$result = db_exec($sql);
	if (pg_numrows($result)) {
		$l_ret = TRUE;
	}
	return $l_ret;
}

/*======================================================================================*/
/*	Magazine									*/
/*	(main)										*/
/*======================================================================================*/
set_global ();

pub_passchk($conn, $publisher_id,$owner_pswd);
$mail_add_publish = strtolower($mail_add_publish);

if ($job_flg == "New") {
	$next_job_flg = "Vew";
	include ("mag-information.html");
} elseif ($job_flg == "New2") {
	$next_job_flg = "Vew2";
	include ("mag-newinfo.html");
} elseif ($job_flg == "Vew" || $job_flg == "Vew2" || $job_flg == "Vew_ed") {
	$mag_nm2 = ereg_replace("\n","",$mag_nm);
	$mag_nm = htmlspecialchars($mag_nm2);
	$mag_intro_long = htmlspecialchars($mag_intro_long);
	$mag_intro_short = htmlspecialchars($mag_intro_short);
	$mag_from_addr = htmlspecialchars($mag_from_addr);			// 2001.07.24 追加
	$mag_subject_tmpl = htmlspecialchars($mag_subject_tmpl);	// 2001.07.24 追加
	$mag_header_tmpl = htmlspecialchars($mag_header_tmpl);		// 2001.07.24 追加
	$mag_footer_tmpl = htmlspecialchars($mag_footer_tmpl);		// 2001.07.24 追加
	include ("$incpath/mag_inp_chk.inc");		//入力チェック
	if ($Chk_flg) {
		$name_msg = chk_mag_nm($mag_id, $mag_nm);				// 200107.24 追加
		$mag_nm = stripslashes($mag_nm);						// 200107.24 追加
		$mag_intro_long = stripslashes($mag_intro_long);		// 200107.24 追加
		$mag_intro_short = stripslashes($mag_intro_short);		// 200107.24 追加
		$mag_hp = stripslashes($mag_hp);
		$mag_cycle_day = stripslashes($mag_cycle_day);
		$mag_from_addr = stripslashes($mag_from_addr);			// 200107.24 追加
		$mag_subject_tmpl = stripslashes($mag_subject_tmpl);	// 200107.24 追加
		$mag_header_tmpl = stripslashes($mag_header_tmpl);		// 200107.24 追加
		$mag_footer_tmpl = stripslashes($mag_footer_tmpl);		// 200107.24 追加
		if ($job_flg == "Vew_ed") {
			$next_job_flg = "Ins_ed";
//			$name_msg = chk_mag_nm($mag_id, $mag_nm);
//			$mag_nm = stripslashes($mag_nm);
//			$mag_intro_long = stripslashes($mag_intro_long);
//			$mag_intro_short = stripslashes($mag_intro_short);
			include ("mag-info-syusei-kakunin.html");
		} else {
			if ($job_flg == "Vew2") {
				$next_job_flg = "Ins2";
			} else {
				$next_job_flg = "Ins";
			}
//			$name_msg = chk_mag_nm($mag_id, $mag_nm);
//			$mag_nm = stripslashes($mag_nm);
//			$mag_intro_long = stripslashes($mag_intro_long);
//			$mag_intro_short = stripslashes($mag_intro_short);
			include ("mag-kakunin.html");
		}
	} else {
		$p_titl = "メルマガ情報";
		include ("err-msg.html");
	}
} elseif ($job_flg == "Ins" || $job_flg == "Ins2" || $job_flg == "Ins_ed") {
	$p_titl ="メルマガ情報";

	$ins_f = FALSE;
	if ($job_flg == "Ins" || $job_flg == "Ins2") {
		if (chk_mag_rec($mag_nm, $publisher_id)) {
			$print_msg =  "「" . trim($mag_nm) . "」登録済みです。";
			include ("err-msg.html");
		} else {
			if (($mag_id = update_squ_key ("M_MAGAZINE")) != "-1") {
				$ins_f = TRUE;
			} else {
				err_msg ("PP002", "メルマガ情報");
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
						$mag_reader_occup, $mag_bn_pub, $ml_type, $melmel_id, $mag_present_ins,
						$mag_original_flg, $mag_from_addr, $mag_subject_tmpl, $mag_header_tmpl,
						$mag_footer_tmpl, $send_notice_flg) ) {
			db_commit_trans();
			$next_job_flg = "Menu";

			if ($job_flg == "Ins_ed") {
				include ("mag-info-syusei-ok.html");
			} else {
				if ($job_flg == "Ins") {
					include ("mag-kanryou.html");
				} else {
					include ("mag-newkanryou.html");
				}
				$sql = "SELECT * FROM M_PUBLISHER WHERE publisher_id = " . $publisher_id;
				$result = db_exec($sql);
				if (pg_numrows($result)) {
					$fetch = pg_fetch_object($result, 0);
					$owner_nm_kanj = $fetch->owner_nm_kanj;
					mag_new_mail($owner_nm_kanj, $mail_add_main, stripslashes($mag_nm));
				}
			}
		} else {
			db_rollback();
			err_msg ("PP002", "メルマガ情報");
		}
	}
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
