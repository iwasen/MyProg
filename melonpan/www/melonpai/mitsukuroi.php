<?php
include ("../sub/head.inc");

function make_maglist($page_no,$last_cho_id,$melonpai_id,$melonpai_pswd) {
	if ($page_no == "") {$page_no = 1;}
	$lime_num = 20;
	if ($last_cho_id == "") { $last_cho_id = 0; }

	$sql = "SELECT C.CHO_ID,C.COM_TITLE, to_char(C.MODIFY_DT, 'yyyy/mm/dd') as W_MAG_DATE ,M.MELONPAI_NAME ";
	$sql .= " FROM T_CHOICE C ,M_MELONPAI M ";
	$sql .= " WHERE C.MELONPAI_ID = $melonpai_id and C.CHO_FLG = '0' and C.MELONPAI_ID = M.MELONPAI_ID";
	$sql .= " and C.cho_id > $last_cho_id order by C.cho_id";
	$result = db_exec($sql);
	$nrow = pg_numrows($result);
	$lcount = 0;
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);
		$cho_id = $fetch->cho_id;
		$com_title = $fetch->com_title;
		$w_mag_dt = $fetch->w_mag_date;
		$melonpai_name = $fetch->melonpai_name;

		if ($lcount == $lime_num) { break; }
		$fom_name =  "formsubmit" . sprintf("%03d", $lcount);
		print "<tr bgcolor=\"#FFFFFF\"> \n";
		print "<form name=$fom_name action=\"mitsukuroi2.php\" method=\"post\">\n";
		print "<td width=\"70\" class=\"text9ptnoColor\"> \n";
		print "<div align=\"center\">$w_mag_dt</div>\n";
		print "</td>\n";
		print "<td class=\"text9ptnoColor\" width=\"370\">";
		print htmlspecialchars($com_title)."</td>\n";
		print "<td width=\"40\"> \n";
		print "<div align=\"right\">\n";
		print "<input type=\"submit\" name=\"Submit\" value=\"返信\">\n";
		print "</div></td>\n";
		print "<input type=\"hidden\" value=\"$cho_id\" name=\"cho_id\">\n";
		print "<input type=\"hidden\" value=\"$melonpai_id\" name=\"melonpai_id\">\n";
		print "<input type=\"hidden\" value=\"$melonpai_pswd\" name=\"melonpai_pswd\">\n";
		print "<input type=\"hidden\" value=\"$page_no\" name=\"page_no\">\n";
		print "<input type=\"hidden\" value=\"Reply\" name=\"job_flg\"></form></tr>\n";
		$lcount++;
		$last_cho_id = $cho_id;
	}
	if ($count > $lcount) {
		return $last_cho_id;
	} else {
		return 0;
	}
}

function make_nextbtn($page_no, $next_f,$melonpai_id,$melonpai_pswd) {
	if ($page_no > 1) {
		$p_no = $page_no - 1;
		print "<td>\n";
		print "<div align=\"right\">\n";

/*		print "<form name=$fom_name action=\"mitsukuroi.php\" method=\"post\">\n";
		print "<input type=\"hidden\" value=\"$melonpai_id\" name=\"melonpai_id\">\n";
		print "<input type=\"hidden\" value=\"$melonpai_pswd\" name=\"melonpai_pswd\">\n";
		print "<input type=\"hidden\" value=\"last_cho_id\" name=\"$last_cho_id\">\n";
		print "<input type=\"Submit\" name=\"Submit\" value=\"戻る\">\n";
		print "<input type=\"hidden\" value=\"NextMitsukuroi\" name=\"job_flg\">\n";
		print "<input type=\"hidden\" value=$p_no name=\"page_no\">\n";
		print "</form>\n";
*/
		print "<form><input type=\"button\" value=\"戻る\" onClick=\"history.back()\"></form>\n";
		print "</td>\n";
		print "</div>\n";
	}
	if ($next_f) {
		$p_no = $page_no + 1;
		print "<td>\n";
		print "<div align=\"right\">\n";
		print "<form name=$fom_name action=\"mitsukuroi.php\" method=\"post\">\n";
		print "<input type=\"hidden\" value=\"$melonpai_id\" name=\"melonpai_id\">\n";
		print "<input type=\"hidden\" value=\"$melonpai_pswd\" name=\"melonpai_pswd\">\n";
		print "<input type=\"hidden\" value=\"$next_f\" name=\"last_cho_id\">\n";
		print "<input type=\"Submit\" name=\"Submit2\" value=\"次へ\">\n";
		print "<input type=\"hidden\" value=\"NextMitsukuroi\" name=\"job_flg\">\n";
		print "<input type=\"hidden\" value=$p_no name=\"page_no\">\n";
		print "<input type=\"hidden\" value=\"$next_f\" name=\"cho_id_next\">\n";
		print "</form>\n";
		print "</td>\n";
		print "</div>\n";
	}

}
/*======================================================================================*/
/*	jimukyoku									*/
/*	(承認選択画面)										*/
/*======================================================================================*/
include ("../inc/com_path.inc");
include ("$compath/com_db_func.inc");
include ("inc/mex_passchk.inc");

mex_passchk($conn,$melonpai_id,$melonpai_pswd);
include("tmpl_html/mitsukuroi_list.html");
?>
