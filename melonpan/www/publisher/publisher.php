<?php
include ("../inc/com_path.inc");
include ("$compath/err_msg.inc");
include ("$compath/com_db_func.inc");
include ("$compath/com_mm_func.inc");
include ("$compath/sql_mm.inc");
include ("$compath/sql_mp.inc");
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

	$sql = "SELECT * FROM M_MAGAZINE WHERE publisher_id = $publisher_id"
				. " and (mag_pub_status_flg = '01' or mag_pub_status_flg = '03') "
				. " and (mag_pub_stop_flg <> '02') ";
	$result = db_exec($sql);
	$nrow = pg_numrows($result);
	$cnt=0;

	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);
		$mag_id = $fetch->mag_id;
		$mail_add_publish = $fetch->mail_add_publish;
		$mag_nm = $fetch->mag_nm;
		$mag_intro_long = $fetch->mag_intro_long;
		$mag_intro_short = $fetch->mag_intro_short;
		$mag_hp = $fetch->mag_hp;
		$mag_type = $fetch->mag_type;
		$mag_type = $fetch->mag_type;
		$mag_cycle_day = $fetch->mag_cycle_day;
		$mag_catg_1 = $fetch->mag_catg_1;
		$mag_catg_2 = $fetch->mag_catg_2;
		$mag_bg_1 = $fetch->mag_bg_1;
		$mag_bg_2 = $fetch->mag_bg_2;
		$mag_reader_sex = $fetch->mag_reader_sex;
		$mag_reader_age = $fetch->mag_reader_age;
		$mag_reader_area = $fetch->mag_reader_area;
		$mag_reader_occup = $fetch->mag_reader_occup;
		$mag_bn_pub = $fetch->mag_bn_pub;
		$ml_type = $fetch->ml_type;
		$mag_status_flg = $fetch->mag_status_flg;
		$mag_pub_status_flg = $fetch->mag_pub_status_flg;
		$mag_pub_stop_flg = $fetch->mag_pub_stop_flg;
		$sql2 = "SELECT count(*) as T_COUNT FROM t_mailaddr  WHERE  mag_id = '$mag_id'";
		$result2 = db_exec($sql2);
		if (pg_numrows($result2)) {
			$fetch = pg_fetch_object($result2, 0);
			$t_count = $fetch->t_count;
		} else {
			$t_count = 0;
		}
		print "<tr>\n";
		print "<td width=\"14%\">$mag_id</td>\n";
		print "<td width=\"67%\">".htmlspecialchars($mag_nm)."</td>\n";
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
/* **
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
*/
/*======================================================================================*/
/*	Publisher									*/
/*	(main)										*/
/*======================================================================================*/
set_global ();
$mail_add_main = strtolower($mail_add_main);
$mail_add_sub = strtolower($mail_add_sub);

if ($job_flg != "New" && $job_flg != "Vew" && $job_flg != "Ins") {
	pub_passchk($conn, $publisher_id,$owner_pswd);
}
if ($job_flg == "New") {
	if (($publisher_id = update_squ_key ("M_PUBLISHER")) != "-1") {
		$next_job_flg = "Vew";
		include ("hakkousya-info.html");
	} else {
		err_msg ("PP001", "発行者情報");
	}
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
	if ($job_flg == "Ins") {$owner_pswd_new = $owner_pswd;}
/* **
		$owner_nm_kanj = ereg_replace("'","''",$owner_nm_kanj);
		$owner_nm_kana = ereg_replace("'","''",$owner_nm_kana);
		$owner_prefec = ereg_replace("'","''",$owner_prefec);
		$owner_pswd_new = ereg_replace("'","''",$owner_pswd_new);
*/
		$owner_nm_kanj = stripcslashes($owner_nm_kanj);
		$owner_nm_kana = stripcslashes($owner_nm_kana);
		$owner_add = stripcslashes($owner_add);
		$owner_pswd_new = stripcslashes($owner_pswd_new);

	db_begin_trans();
	if (pub_update ($conn, $publisher_id, $mail_add_main, $mail_add_sub,
			$owner_nm_kanj, $owner_nm_kana, $owner_zip1, $owner_zip2,
			$owner_prefec, $owner_add, $owner_tel, $owner_pswd_new) ) {
			db_commit_trans();
		if ($job_flg == "Ins") {
			$next_job_flg = "New";
			include ("hakkousya-info-kanryou.html");
		} else {
			$next_job_flg = "Menu";
			include ("henkou-kanryou.html");
		}
	} else {
		db_rollback();
		err_msg ("PP002", "発行者情報");
	}
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
	include ("hakkousya-kaijyo.php");
} elseif ($job_flg == "Del2") {
	$next_job_flg = "Del3";
	include ("kaijyo-kakunin.php");
} elseif ($job_flg == "Del3") {
	include ("kaijyo-kanryou.php");
}

?>
