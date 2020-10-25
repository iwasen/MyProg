<?php
include ("inc/pub_passchk.inc");
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

function make_sel_maglist($publisher_id,$owner_pswd) {
	global $inc_path;

	$sql = "SELECT * FROM M_MAGAZINE WHERE publisher_id = $publisher_id";
	//$sql .= " and ( mag_status_flg = '00' )";
	$sql .= " and ( mag_pub_status_flg = '01' or mag_pub_status_flg = '03' ) ";
	$sql .= " and ( mag_pub_stop_flg <> '02' ) ";
	$result = db_exec($sql);
	$nrow = pg_numrows($result);
	$lcount = 0;

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

		$lcount++;
		$btn_name =  "recomend_mag_data";
		$fom_name =  "formsubmit" . sprintf("%03d", $lcount);
		print "<form name=\"$fom_name\" action=\"magazine2.php\" method=\"post\">\n";
		print "<tr>\n";
		print "<td width=\"67\" align=\"center\">$mag_id</td>\n";
		print "<td width=\"337\">".htmlspecialchars($mag_nm)."</td>\n";
		print "<td width=\"82\">\n";
	  	print "<input type=\"submit\" name=\"$btn_name\" value=\"¿äÁ¦Ê¸±ÜÍ÷\">";
		print "</td></tr>\n";
		print "<input type=\"hidden\" value=\"$owner_pswd\" name=\"owner_pswd\">\n";
		print "<input type=\"hidden\" value=\"$publisher_id\" name=\"publisher_id\">\n";
		print "<input type=\"hidden\" value=\"$mag_id\" name=\"mag_id\">\n";
		print "<input type=\"hidden\" value=\"$mag_nm\" name=\"mag_nm\">\n";
		print "<input type=\"hidden\" value=\"VIWE\" name=\"job_flg\">\n";
		print "</form>\n";
	}
}

function make_title_list($publisher_id, $mag_id ,$owner_pswd) {
	$sql = "SELECT * FROM T_RECOMEND WHERE open_flg = '1' and mag_id = '$mag_id'";
	$result = db_exec($sql);
	$nrow = pg_numrows($result);
	$lcount = 0;
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);
		$recomend_id = $fetch->recomend_id;
		$recom_titl = $fetch->recom_titl;
		$lcount++;
		$btn_name =  "edit_mag_data";
		$fom_name =  "formsubmit" . sprintf("%03d", $lcount);
		print "<form name=\"$fom_name\" action=\"magazine2.php\" method=\"post\">\n";
		print "<tr>\n";
		print "<td width=\"400\"><a href=\"JavaScript:document.$fom_name.submit();\">".htmlspecialchars($recom_titl)."</a></td>\n";
		print "</tr>\n";
		print "<input type=\"hidden\" value=$publisher_id name=\"publisher_id\">\n";
		print "<input type=\"hidden\" value=$owner_pswd name=\"owner_pswd\">\n";
		print "<input type=\"hidden\" value=$recomend_id name=\"recomend_id\">\n";
		print "<input type=\"hidden\" value=\"COMENT\" name=\"job_flg\">\n";
		print "<input type=\"hidden\" value=$mag_id name=\"mag_id\">\n";
		print "</form>\n";
	}
}

/*======================================================================================*/
/*	Magazine									*/
/*	(main)										*/
/*======================================================================================*/
set_global ();
pub_passchk($conn, $publisher_id,$owner_pswd);

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
	$sql = "SELECT * FROM T_RECOMEND WHERE recomend_id = $recomend_id";
	$result = db_exec($sql);
	if (!pg_numrows($result)) {
		err_msg ("PM001", $p_titl);
	} else {
		if ($job_flg == "OK1") {
			$flgVal = "1";
		} else {
			$flgVal = "2";
		}
		if ($job_flg == "OK1") {
			$sql = "UPDATE T_RECOMEND SET open_flg = '" . $flgVal
					. "' WHERE recomend_id = :RECOMEND_ID";
		} else {
			$sql = "delete from T_RECOMEND WHERE recomend_id = $recomend_id";
		}
		if (!db_exec($sql)) {
			err_msg ("PM001", $p_titl);
		} else {
			if ($job_flg == "OK1") {
				include ("suisen-koukai-ok.html");
			} else {
				include ("suisen-sakujyo-ok.html");
			}
		}
	}
}
?>
