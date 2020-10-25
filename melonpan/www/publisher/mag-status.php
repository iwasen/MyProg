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
/*  ÃË½÷¿ô¡¡¡¡sex_cnt1  sex_cnt 2*/
	$sql = "select S_NAME,S_COUNT from s_mailaddr where s_key = 'SEX' and ";
	$sql = $sql . " mag_id = '";
	$sql = $sql . $mag_id . "'";
	$result = db_exec($sql);
	$nrow = pg_numrows($result);
	$sex_cnt1 = "0";
	$sex_cnt2 = "0";
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);
		$r_reader_sex = $fetch->s_name;
		$sex_cnt = $fetch->s_count;

		$r_reader_sex = trim ($r_reader_sex);
		if ($r_reader_sex == 1) {
			$sex_cnt1 = sprintf ("%d",$sex_cnt);
		} else {
			$sex_cnt2 = sprintf ("%d",$sex_cnt);
		}
	}
	$sex_cntall = $sex_cnt1 + $sex_cnt2;
	if ($sex_cntall != 0) {
		$pa1 = $sex_cnt1 * 100 /$sex_cntall;
		$pa2 = $sex_cnt2 * 100 /$sex_cntall;
		if ($sex_cnt1 != 0 ) {
			print "<TD width=\"$pa1%\" bgcolor=\"#CCFFFF\" class=\"text8ptnoColor\">ÃËÀ­</TD>\n";
		}
		if ($sex_cnt2 != 0 ) {
			print "<TD width=\"$pa2%\" bgcolor=\"#FFCCCC\" class=\"text8ptnoColor\">½÷À­</TD>\n";
		}
	}
}

function reader_mrgcnt($conn, $mag_id) {
/*  ´ûº§¿ô¡¡¡¡mrg_cnt1  mrg_cnt2*/
	$sql = "select S_NAME,S_COUNT from s_mailaddr where s_key = 'MRG' and ";
	$sql = $sql . " mag_id = '";
	$sql = $sql . $mag_id . "'";
	$result = db_exec($sql);
	$nrow = pg_numrows($result);
	$mrg_cnt1 =  0;
	$mrg_cnt2 = 0;
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);
		$r_reader_mrg = $fetch->s_name;
		$mrg_cnt = $fetch->s_count;
		$r_reader_mrg = trim ($r_reader_mrg);
		if ($r_reader_mrg == 1) {
			$mrg_cnt1 = $mrg_cnt;
		} else {
			$mrg_cnt2 =  $mrg_cnt;
		}
	}
	$sex_mrgall = $mrg_cnt1 + $mrg_cnt2;
	if ($sex_mrgall != 0) {
		$pa1 = $mrg_cnt1 * 100 /$sex_mrgall;
		$pa2 = $mrg_cnt2 * 100 /$sex_mrgall;
		if ($mrg_cnt2 != "0" and $mrg_cnt1 != "0") {
			if ($mrg_cnt1 != 0 ) {
				print "<TD width=\"$pa1%\" bgcolor=\"#CCFFCC\" class=\"text8ptnoColor\">Ì¤º§</TD>\n";
			}
			if ($mrg_cnt2 != 0 ) {
				print "<TD width=\"$pa2%\" bgcolor=\"#FFFFCC\" class=\"text8ptnoColor\">´ûº§</TD>\n";
			}
		}
	}
}

function reader_occupcnt($conn, $mag_id) {
/*  ¿¦¶È¿ô¡¡¡¡syo_cnt */

	$sql = mcom_make_sql ("SELECT_M_MAG_OCCUP");
	$result = db_exec($sql);
	$nrow = pg_numrows($result);
	$w_cnt = 0;
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);
		$occup_id = $fetch->occup_id;
		$occup = $fetch->occup;

		$w_occ_nm[$w_cnt] = trim($occup);
		$w_occ_id[$w_cnt] = $occup_id;
		$w_occ_cnt[$w_cnt] = 0;
		$w_cnt++;
	}

	$scnt = 0;
	$sql = "select S_NAME,S_COUNT from s_mailaddr where s_key = 'OCC' and ";
	$sql = $sql . " mag_id = '";
	$sql = $sql . $mag_id . "'";
	$result = db_exec($sql);
	$nrow = pg_numrows($result);
	for ($j = 0; $j < $nrow; $j++) {
		$fetch = pg_fetch_object($result, $j);
		$r_reader_occup = $fetch->s_name;
		$syo_cnt = $fetch->s_count;
		$r_reader_occup = trim ($r_reader_occup);
		$sql2 = "select occup from m_mag_occup where occup_id = '";
		$sql2 = $sql2 . $r_reader_occup ."'";
		$result2 = db_exec($sql2);
		if (pg_numrows($result2)) {
			$fetch = pg_fetch_object($result2, 0);
			$occup = $fetch->occup;
			for ($i = 0; $i<$w_cnt; $i++) {
				if ($w_occ_nm[$i] == trim($occup)) {
					$w_occ_cnt[$i] = $syo_cnt;
					$scnt = $scnt + $syo_cnt;
					brake;
				}
			}
		}
	}

	for ($i = 0; $i<$w_cnt; $i++) {
		if ($scnt == 0) {
			$pa1 = 0;
		} else {
			$pa1= $w_occ_cnt[$i] / $scnt * 100 ;
		}
		print "<TR>\n";
		print "<TD width=\"90\" class=\"text8ptnoColor\">\n";
		if ($w_occ_id[$i] == "06001") {
			$w_occ_nm[$i] = "ÀìÌç¿¦(*1)";
		} elseif ($w_occ_id[$i] == "07001") {
			$w_occ_nm[$i] = "ÀìÌç¿¦(*2)";
		} elseif ($w_occ_id[$i] == "09001") {
			$w_occ_nm[$i] = "Àì¶È¼çÉØ¡¦¼çÉ×";
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

/* ------------------------------------------------ */
/* ºï½üÍýÍ³¡¡										*/
/* ------------------------------------------------ */
function reader_reason($conn, $mag_id) {
#	$sql = "select M.STOP_REASON, coalesce(T.S_COUNT, 0) as STOP_COUNT "
#		 . "  from M_DELREASON M LEFT JOIN (select * from S_MAILADDR where S_KEY = 'ERR' and MAG_ID = '".$mag_id."') T ON trim(M.STOP_REASON) = trim(T.S_NAME)"
#		 . " order by M.STOP_CODE";
	$sql = "select M.STOP_REASON, coalesce(T.S_COUNT, 0) as STOP_COUNT "
		 . "  from M_DELREASON M LEFT JOIN (select * from S_MAILADDR where S_KEY='ERR' and MAG_ID='".$mag_id."') T"
		 . "    ON M.STOP_REASON = T.S_NAME order by M.STOP_CODE";
	$result = db_exec($sql);
	$nrow = pg_numrows($result);
	for ($i = 0; $i < $nrow; $i++) {
		$resarray= pg_fetch_array ($result, $i);
		$reason[$i] = $resarray[0];
		$count[$i] = $resarray[1];
		echo "<TR>\n";
		echo "  <TD width=\"240\" class=\"text8ptnoColor\">\n";
		echo "    <DIV align=\"right\">$reason[$i]</DIV>\n";
		echo "  </TD>\n";
		echo "  <TD width=\"40\" class=\"text8ptnoColor\">\n";
		echo "    <DIV align=\"right\">$count[$i] ¿Í</DIV>\n";
		echo "  </TD>\n";
		echo "</TR>\n";
	}
}

/* ------------------------------------------------ */
/* ÆÉ¼Ô¤ÎÊ¿¶ÑÇ¯Îð
/* ------------------------------------------------ */
function reader_average_age($conn, $mag_id) {
	$sub = "SELECT L.MAG_ID as mag_id, extract (year from age(current_date, to_char(K.READER_BIRTH, 'YYYY/MM/DD'))) as ymd"
		. " FROM T_MAILADDR L , M_MELONPA K WHERE L.READER_ID <> 0 and L.READER_ID = K.READER_ID";
	$sql = "select round(avg(ymd), 1) as age from (" . $sub . ") as SUB where mag_id = '$mag_id' group by mag_id ";
	$result = db_exec($sql);
	$nrow = pg_numrows($result);
	if ($nrow == 0) {
		$reader_avg_age = 0;
	} else {
		$fetch = pg_fetch_object($result, 0);
		$reader_avg_age = $fetch->age;
	}
	return $reader_avg_age;
}
/*======================================================================================*/
/*	mmag-status.php									*/
/*											*/
/*======================================================================================*/
pub_passchk($conn, $publisher_id,$owner_pswd);
/**  ÁíÆÉ¼Ô¿ô¡¡¡¡scnt */
$sql = "select S_COUNT from s_mailaddr where s_key = 'ALL' and ";
$sql = $sql . " mag_id = '";
$sql = $sql . $mag_id . "'";
$result = db_exec($sql);
if (pg_numrows($result)) {
	$fetch = pg_fetch_object($result, 0);
	$scnt = $fetch->s_count;
}
include ("mag-status.html");

?>