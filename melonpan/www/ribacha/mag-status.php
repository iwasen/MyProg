<?php
include ("../inc/com_path.inc");
include ("inc/pub_passchk.inc");
include ("$compath/err_msg.inc");
include ("$compath/com_func.inc");
include ("$compath/com_db_func.inc");
include ("$compath/sql_mp.inc");
include ("$incpath/pub_mp_dbacces.inc");
include ("$compath/sql_mm.inc");
include ("$compath/sql_mcom.inc");
include ("$compath/com_mm_func.inc");
include ("$incpath/pub_mm_dbacces.inc");
include ("$incpath/pub_mm_chk.inc");


function reader_sexcnt($conn, $mag_id) {
/*  男女数　　sex_cnt1  sex_cnt 2*/
	$sql = "select S_NAME,S_COUNT from s_mailaddr where s_key = 'SEX' and ";
	$sql = $sql . " mag_id = '";
	$sql = $sql . $mag_id . "'";
	$stmt2 = OCIParse($conn, $sql);
	OCIDefinebyname($stmt2, "S_NAME", &$r_reader_sex);
	OCIDefinebyname($stmt2, "S_COUNT", &$sex_cnt);
	$ncols = OCIExecute($stmt2);
	$sex_cnt1 = "0";
	$sex_cnt2 = "0";
	while (OCIFetch($stmt2)) {
		$r_reader_sex = trim ($r_reader_sex);
		if ($r_reader_sex == 1) {
			$sex_cnt1 = sprintf ("%d",$sex_cnt);
		} else {
			$sex_cnt2 = sprintf ("%d",$sex_cnt);
		}
	}
	$sex_cntall = $sex_cnt1 + $sex_cnt2;
	OCIFreeStatement($stmt2);
	if ($sex_cntall != 0) {
		$pa1 = $sex_cnt1 * 100 /$sex_cntall;
		$pa2 = $sex_cnt2 * 100 /$sex_cntall;
		if ($sex_cnt1 != 0 ) {
			print "<TD width=\"$pa1%\" bgcolor=\"#CCFFFF\" class=\"text8ptnoColor\">男性</TD>\n";
		}
		if ($sex_cnt2 != 0 ) {
			print "<TD width=\"$pa2%\" bgcolor=\"#FFCCCC\" class=\"text8ptnoColor\">女性</TD>\n";
		}
	}
}

function reader_mrgcnt($conn, $mag_id) {
/*  既婚数　　mrg_cnt1  mrg_cnt2*/
	$sql = "select S_NAME,S_COUNT from s_mailaddr where s_key = 'MRG' and ";
	$sql = $sql . " mag_id = '";
	$sql = $sql . $mag_id . "'";
	$stmt1 = OCIParse($conn, $sql);
	OCIDefinebyname($stmt1, "S_NAME", &$r_reader_mrg);
	OCIDefinebyname($stmt1, "S_COUNT", &$mrg_cnt);
	$ncols = OCIExecute($stmt1);
	$mrg_cnt1 =  0;
	$mrg_cnt2 = 0;
	while (OCIFetch($stmt1)) {
		$r_reader_mrg = trim ($r_reader_mrg);
		if ($r_reader_mrg == 1) {
			$mrg_cnt1 = $mrg_cnt;
		} else {
			$mrg_cnt2 =  $mrg_cnt;
		}
	}
	OCIFreeStatement($stmt1);
	$sex_mrgall = $mrg_cnt1 + $mrg_cnt2;
	if ($sex_mrgall != 0) {
		$pa1 = $mrg_cnt1 * 100 /$sex_mrgall;
		$pa2 = $mrg_cnt2 * 100 /$sex_mrgall;
		if ($mrg_cnt2 != "0" and $mrg_cnt1 != "0") {
			if ($mrg_cnt1 != 0 ) {
				print "<TD width=\"$pa1%\" bgcolor=\"#CCFFCC\" class=\"text8ptnoColor\">未婚</TD>\n";
			}
			if ($mrg_cnt2 != 0 ) {
				print "<TD width=\"$pa2%\" bgcolor=\"#FFFFCC\" class=\"text8ptnoColor\">既婚</TD>\n";
			}
		}
	}
}

function reader_occupcnt($conn, $mag_id) {
/*  職業数　　syo_cnt */

	$sql = mcom_make_sql ("SELECT_M_MAG_OCCUP");
	$stmt = OCIParse($conn, $sql);
	OCIDefinebyname($stmt, "OCCUP_ID", &$occup_id);
	OCIDefinebyname($stmt, "OCCUP", &$occup);
	$ncols = OCIExecute($stmt, OCI_DEFAULT);
	$w_cnt = 0;
	while (OCIFetch($stmt)) {
		$w_occ_nm[$w_cnt] = trim($occup);
		$w_occ_id[$w_cnt] = $occup_id;
		$w_occ_cnt[$w_cnt] = 0;
		$w_cnt++;
	}
	OCIFreeStatement($stmt);

	$scnt = 0;
	$sql = "select S_NAME,S_COUNT from s_mailaddr where s_key = 'OCC' and ";
	$sql = $sql . " mag_id = '";
	$sql = $sql . $mag_id . "'";
	$stmt1 = OCIParse($conn, $sql);
	OCIDefinebyname($stmt1, "S_NAME", &$r_reader_occup);
	OCIDefinebyname($stmt1, "S_COUNT", &$syo_cnt);
	$ncols = OCIExecute($stmt1);
	while (OCIFetch($stmt1)) {
		$r_reader_occup = trim ($r_reader_occup);
		$sql2 = "select occup from m_mag_occup where occup_id = '";
		$sql2 = $sql2 . $r_reader_occup ."'";
		$stmt2 = OCIParse($conn, $sql2);
		OCIDefinebyname($stmt2, "OCCUP", &$occup);
		$ncols = OCIExecute($stmt2);
		if (OCIFetch($stmt2)) {
			for ($i = 0; $i<$w_cnt; $i++) {
				if ($w_occ_nm[$i] == trim($occup)) {
					$w_occ_cnt[$i] = $syo_cnt;
					$scnt = $scnt + $syo_cnt;
					brake;
				}
			}
		}
		OCIFreeStatement($stmt2);
	}
	OCIFreeStatement($stmt1);

	for ($i = 0; $i<$w_cnt; $i++) {
		if ($scnt == 0) {
			$pa1 = 0;
		} else {
			$pa1= $w_occ_cnt[$i] / $scnt * 100 ;
		}
		print "<TR>\n";
		print "<TD width=\"90\" class=\"text8ptnoColor\">\n";
		if ($w_occ_id[$i] == "06001") {
			$w_occ_nm[$i] = "専門職(*1)";
		} elseif ($w_occ_id[$i] == "07001") {
			$w_occ_nm[$i] = "専門職(*2)";
		} elseif ($w_occ_id[$i] == "09001") {
			$w_occ_nm[$i] = "専業主婦・主夫";
		}
		print "<DIV align=\"right\">$w_occ_nm[$i]</DIV>\n";
		print "</TD>\n";
		print "<TD width=\"300\" class=\"text8ptnoColor\">\n";
		print "<TABLE width=\"$pa1%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\n";
		if ($pa1 != 0) {
			print "<TR bgcolor=\"#CCCCFF\">\n";
		}
		print "<TD width=\"50%\" class=\"text8ptnoColor\">$w_occ_cnt[$i]</TD>\n";
		print "</TR>\n";
		print "</TABLE>\n";
		print "</TD>\n";
		print "</TR>\n";
	}
}

function reader_reason($conn, $mag_id) {
/* 削除理由　del_cnt*/
/************2001/01/11 改造 ******************/
	$reson_1 = "思っていたのと違っていた";
	$num_1 = "0";
	$reson_2 = "メールアドレスがなくなるので";
	$num_2 = "0";
	$reson_3 = "受信メールが多すぎるので";
	$num_3 = "0";
	$reson_4 = "興味が変わった";
	$num_4 = "0";
	$reson_5 = "広告が多すぎる";
	$num_5 = "0";
	$reson_6 = "その他";
	$num_6 = "0";
	$reson_7 = "つまらない";
	$num_7 = "0";
	$reson_8 = "間違えた";
	$num_8 = "0";
/************2001/01/11 改造 ******************/

	$sql = "select S_NAME,S_COUNT from s_mailaddr where s_key = 'ERR' and ";
	$sql = $sql . " mag_id = '";
	$sql = $sql . $mag_id . "'";
	$stmt1 = OCIParse($conn, $sql);
	OCIDefinebyname($stmt1, "S_NAME", &$reason);
	OCIDefinebyname($stmt1, "S_COUNT", &$del_cnt);
	$ncols = OCIExecute($stmt1);
	$reason = trim ($reason);
/************2001/01/11 改造 ******************/
	while (OCIFetch($stmt1)) {
		if ($reson_1 == trim($reason)) { $num_1 = intval($del_cnt); }
		if ($reson_2 == trim($reason)) { $num_2 = intval($del_cnt); }
		if ($reson_3 == trim($reason)) { $num_3 = intval($del_cnt); }
		if ($reson_4 == trim($reason)) { $num_4 = intval($del_cnt); }
		if ($reson_5 == trim($reason)) { $num_5 = intval($del_cnt); }
		if ($reson_6 == trim($reason)) { $num_6 = intval($del_cnt); }
		if ($reson_7 == trim($reason)) { $num_7 = intval($del_cnt); }
		if ($reson_8 == trim($reason)) { $num_8 = intval($del_cnt); }
	}
	OCIFreeStatement($stmt1);
	print "<TR>\n";
	print "  <TD width=\"150\" class=\"text8ptnoColor\">\n";
	print "<DIV align=\"right\">つまらない</DIV>\n";
	print "  </TD>\n";
	print "  <TD width=\"40\" class=\"text8ptnoColor\">\n";
	print "<DIV align=\"right\">$num_7 人</DIV>\n";
	print "  </TD>\n";
	print "</TR>\n";
	print "<TR>\n";
	print "  <TD width=\"150\" class=\"text8ptnoColor\">\n";
	print "<DIV align=\"right\">間違えた</DIV>\n";
	print "  </TD>\n";
	print "  <TD width=\"40\" class=\"text8ptnoColor\">\n";
	print "<DIV align=\"right\">$num_8 人</DIV>\n";
	print "  </TD>\n";
	print "</TR>\n";
	print "<TR>\n";
	print "  <TD width=\"150\" class=\"text8ptnoColor\">\n";
	print "<DIV align=\"right\">思っていたのと違っていた</DIV>\n";
	print "  </TD>\n";
	print "  <TD width=\"40\" class=\"text8ptnoColor\">\n";
	print "<DIV align=\"right\">$num_1 人</DIV>\n";
	print "  </TD>\n";
	print "</TR>\n";
	print "<TR>\n";
	print "  <TD width=\"150\" class=\"text8ptnoColor\">\n";
	print "<DIV align=\"right\">メールアドレスがなくなるので</DIV>\n";
	print "  </TD>\n";
	print "  <TD width=\"40\" class=\"text8ptnoColor\">\n";
	print "<DIV align=\"right\">$num_2 人</DIV>\n";
	print "  </TD>\n";
	print "</TR>\n";
	print "<TR>\n";
	print "  <TD width=\"150\" class=\"text8ptnoColor\">\n";
	print "<DIV align=\"right\">受信メールが多すぎるので</DIV>\n";
	print "  </TD>\n";
	print "  <TD width=\"40\" class=\"text8ptnoColor\">\n";
	print "<DIV align=\"right\">$num_3 人</DIV>\n";
	print "  </TD>\n";
	print "</TR>\n";
	print "<TR>\n";
	print "  <TD width=\"150\" class=\"text8ptnoColor\">\n";
	print "<DIV align=\"right\">興味が変わった</DIV>\n";
	print "  </TD>\n";
	print "  <TD width=\"40\" class=\"text8ptnoColor\">\n";
	print "<DIV align=\"right\">$num_4 人</DIV>\n";
	print "  </TD>\n";
	print "</TR>\n";
	print "<TR>\n";
	print "  <TD width=\"150\" class=\"text8ptnoColor\">\n";
	print "<DIV align=\"right\">広告が多すぎる</DIV>\n";
	print "  </TD>\n";
	print "  <TD width=\"40\" class=\"text8ptnoColor\">\n";
	print "<DIV align=\"right\">$num_5 人</DIV>\n";
	print "  </TD>\n";
	print "</TR>\n";
	print "<TR>\n";
	print "  <TD width=\"150\" class=\"text8ptnoColor\">\n";
	print "<DIV align=\"right\">その他</DIV>\n";
	print "  </TD>\n";
	print "  <TD width=\"40\" class=\"text8ptnoColor\">\n";
	print "<DIV align=\"right\">$num_6 人</DIV>\n";
	print "  </TD>\n";
	print "</TR>\n";

}

/*======================================================================================*/
/*	mmag-status.php									*/
/*											*/
/*======================================================================================*/
$conn = db_conxxxt();
pub_passchk($conn, $publisher_id,$owner_pswd);
/**  総読者数　　scnt */
$sql = "select S_COUNT from s_mailaddr where s_key = 'ALL' and ";
$sql = $sql . " mag_id = '";
$sql = $sql . $mag_id . "'";
$stmt1 = OCIParse($conn, $sql);
OCIDefinebyname($stmt1, "S_COUNT", &$scnt);
$ncols = OCIExecute($stmt1);
OCIFetch($stmt1);
OCIFreeStatement($stmt1);
include ("mag-status.html");
db_logoff();

?>