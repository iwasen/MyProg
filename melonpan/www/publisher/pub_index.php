<?php
include ("inc/pub_passchk.inc");
include ("../inc/com_path.inc");
include ("$compath/com_db_func.inc");
include ("$compath/sql_mm.inc");
include ("$compath/sql_mp.inc");
include ("$incpath/pub_mp_dbacces.inc");
include ("$compath/sql_mcom.inc");
include ("$compath/com_mm_func.inc");

function set_global () {
	global $inc_path;

	include ("../inc/com_path.inc");
	$inc_path = $incpath;
}

function make_maglist($publisher_id, $owner_pswd_r) {
	global $inc_path;
	global $gConn;

	$sql1 = "SELECT * FROM M_MAGAZINE WHERE (mag_status_flg <> '01' and publisher_id = $publisher_id) ";
	$sql1 .= " and ( mag_pub_status_flg = '01' or mag_pub_status_flg = '03' ) ";
	$sql1 .= " and ( mag_pub_stop_flg <> '02' ) ";
	$sql1 .= " order by mag_id";
	//$sql1 .= " and ( publisher_id <> 0 ) ";
	$result1 = db_exec($sql1);
	$nrow = pg_numrows($result1);
	$w_cnt = 0;
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result1, $i);
		$mag_id = $fetch->mag_id;
		$mag_nm = $fetch->mag_nm;

//		$sql2 = "SELECT count(*) as T_COUNT FROM t_mailaddr  WHERE  mag_id = '$mag_id'";
		$sql2 = "select S_COUNT from S_MAILADDR where MAG_ID='$mag_id' AND S_KEY='ALL'";
		$result = db_exec($sql2);
		if (pg_numrows($result)) {
			$fetch = pg_fetch_row($result, 0);
			$t_count = $fetch[0];
		} else {
			$t_count = 0;
		}
		$w_cnt++;
		$fom_nm = "status" . sprintf("%02d", $w_cnt);
		print "<form method=\"post\" action=\"mag-status.php\" name=\"$fom_nm\">\n";
		print "<tr>\n";
		print "<td width=\"40\">$mag_id</td>\n";
		print "<td width=\"240\"> \n";
		print "<div align=\"left\">".htmlspecialchars($mag_nm)."</div>\n";
		print "</td>\n";
		print "<td width=\"80\"> \n";
		print "<div align=\"right\">\n";
		print "<a href=\"JavaScript:document.$fom_nm.submit();\">".number_format($t_count)." 人</a></div>\n";
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
		print "<p align=\"center\">　</p>\n";
		print "<div align=\"center\"><font color=\"#006600\">\n";
		print "-" . $login_err . "-</font></div><BR>\n";
	}
}

function make_maglistkyu($publisher_id) {
	global $inc_path;
	global $gConn;

	$sql1 = "SELECT * FROM M_MAGAZINE WHERE mag_status_flg = '01' and publisher_id = $publisher_id";
	$sql1 .= " and ( mag_pub_status_flg = '01' or mag_pub_status_flg = '03' ) ";
	$sql1 .= " and ( mag_pub_stop_flg <> '02' ) ";
	$result = db_exec($sql1);
	$nrow = pg_numrows($result);
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);
		$mag_id = $fetch->mag_id;
		$mag_nm = $fetch->mag_nm;

		print "<tr>\n";
		print "<td width=\"53\">$mag_id</td>\n";
		print "<td width=\"182\"> \n";
		print "<div align=\"left\">$mag_nm</div>\n";
		print "</td>\n";
		print "<td width=\"110\"> \n";
		print "<div align=\"right\">休刊中</div>";
		print "</td>";
		print "</tr>";
	}
}

function adm_msg() {
	global $gConn;

	$w_msg = "";
	$sql1 = "SELECT * FROM T_ADMIN_MSG WHERE msg_id = 1";
	$result = db_exec($sql1);
	if (pg_numrows($result)) {
		$fetch = pg_fetch_object($result, 0);
		$admin_msg = $fetch->admin_msg;
		$w_msg = trim($admin_msg);
	}
	return $w_msg;
}

/* ------------------------------------------------ */
/*	過去1週間の公開推薦文選択						*/
/* ------------------------------------------------ */
function check_new_recommendation($publisher_id) {
	global $gConn, $new_reco;

	$base_date = strftime("%Y/%m/%d ",time() - (8*24*60*60));

	$sql = "select to_char(R.MODIFY_DT, 'MM/DD'), M.MAG_NM from T_RECOMEND R, M_MAGAZINE M "
 		 . " where R.OPEN_FLG = '1' and to_char(R.MODIFY_DT,  'YYYY/MM/DD') > '$base_date' "
		 . "   and M.PUBLISHER_ID = $publisher_id "
		 . "   and M.MAG_ID = R.MAG_ID";
	$result = db_exec($sql);
	$nrows = pg_numrows($result);
	return $rows;
}

/* ------------------------------------------------ */
/*	ポイントの内訳表示								*/
/* ------------------------------------------------ */
function pub_point_detail ($publisher_id, &$p_detail) {
	global $gConn;

	if ( pub_point_detail_start() == 0 ){ // 繰越レコードの数
		return 0;
	}
	
	$sql = "select M.ATTRIBUTION, coalesce(P.SUM, 0) as POINT "
		 . "  from M_POINT_ATTRIBUTION M,"
		 . "      (select ATTRIBUTION_CD, sum(AVAILABLE_POINT) as SUM from T_POINT_DETAIL "
		 . "        where OWNER_KIND = '2' and OWNER_ID = $publisher_id group by ATTRIBUTION_CD ) P "
		 . " where M.ATTRIBUTION_CD = P.ATTRIBUTION_CD and P.SUM <> 0"
		 . " order by P.ATTRIBUTION_CD";
	$result = db_exec($sql);
	$nrows = pg_numrows($result);
	return $rows;
}

/* ------------------------------------------------ */
/*	ポイントの内訳表示チェック(移行時の考慮)		*/
/* ------------------------------------------------ */
function pub_point_detail_start () {

	$cnt = 0;
	$sql = "select count(*) as CNT from T_POINT_DETAIL where ATTRIBUTION_CD = '00'";
	$result = db_exec($sql);
	if (pg_numrows($result)) {
		$fetch = pg_fetch_object($result, 0);
		$cnt = $fetch->cnt;
	}
	return $cnt;
}
/*======================================================================================*/
/*	submit login								*/
/*	(main)										*/
/*======================================================================================*/
set_global ();
$login_err = "";
if ($nyusitu ) {
	//pub_passchk($conn, $publisher_id,$owner_pswd_r);

	$sql1 = "SELECT * FROM M_PUBLISHER WHERE publisher_id = $publisher_id";
	$result = db_exec($sql1);
	$nrow = pg_numrows($result);
	if ($nrow == 0) {
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
	$fetch = pg_fetch_object($result, 0);
	$p_mail_add_main = $fetch->mail_add_main;
	$p_mail_add_sub = $fetch->mail_add_sub;
	$p_owner_nm_kanj = $fetch->owner_nm_kanj;
	$p_owner_nm_kana = $fetch->owner_nm_kana;
	$p_owner_zip1 = $fetch->owner_zip1;
	$p_owner_zip2 = $fetch->owner_zip2;
	$p_owner_prefec = $fetch->owner_prefec;
	$p_owner_add = $fetch->owner_add;
	$p_owner_tel = $fetch->owner_tel;
	$p_owner_pswd = $fetch->owner_pswd;
	$p_pt_melonpan = $fetch->pt_melonpan;
	$p_pt_melmel = $fetch->pt_melmel;
//		$ret01 = OCIFreeStatement($stmt1);
	$mail_add_main = trim($p_mail_add_main);	/* add */
	$mdm_msg = adm_msg();
	include ("pub_index.html");
} elseif ($publisher_id != "") {
	pub_passchk($conn, $publisher_id,$owner_pswd_r);

	$lret = pub_mp_chkrec ($conn, $publisher_id);

	if ($lret) {
		$sql = "SELECT * FROM M_PUBLISHER WHERE publisher_id = $publisher_id";
		$result = db_exec($sql);
		$nrow = pg_numrows($result);
		if ($nrow == 0) {
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
		$fetch = pg_fetch_object($result, 0);
		$p_mail_add_main = $fetch->mail_add_main;
		$p_mail_add_sub = $fetch->mail_add_sub;
		$p_owner_nm_kanj = $fetch->owner_nm_kanj;
		$p_owner_nm_kana = $fetch->owner_nm_kana;
		$p_owner_zip1 = $fetch->owner_zip1;
		$p_owner_zip2 = $fetch->owner_zip2;
		$p_owner_prefec = $fetch->owner_prefec;
		$p_owner_add = $fetch->owner_add;
		$p_owner_tel = $fetch->owner_tel;
		$p_owner_pswd = $fetch->owner_pswd;
		$p_pt_melonpan = $fetch->pt_melonpan;
		$p_pt_melmel = $fetch->pt_melmel;
		$p_pt_melonpan = trim($p_pt_melonpan);

		$mail_add_main = trim($p_mail_add_main);	/* add */
		$mdm_msg = adm_msg();
		include ("pub_index.html");
	} else {
		pub_passchk($conn, $publisher_id,$owner_pswd_r);
		include ("hakkou-login.html");
	}
} else {
	include ("hakkou-login.html");
}
?>

