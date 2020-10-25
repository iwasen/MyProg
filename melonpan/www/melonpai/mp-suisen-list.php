<?php
include ("../inc/com_path.inc");
include ("$compath/err_msg.inc");
include ("$compath/com_func.inc");
include ("$compath/com_db_func.inc");
include ("$compath/sql_mcom.inc");
include ("$compath/com_mm_func.inc");
include ("$incpath/mex_passchk.inc");
include ("../sub/head.inc");

function make_suisen_list($page_no, $melonpai_id, $melonpai_pswd, $melonpai_name) {
	global $gConn;

	$lime_num = 20;
	$sql = "SELECT m_magazine.mag_id, m_magazine.mag_nm, m_magazine.mag_catg_1, "
		. "m_magazine.mag_catg_2, t_recomend.recomend_id, t_recomend.recom_titl "
		. "FROM M_MAGAZINE, T_RECOMEND WHERE "
		. "m_magazine.mag_id = t_recomend.mag_id AND t_recomend.open_flg = '0' "
		. "ORDER BY t_recomend.mag_id";
	$result = db_exec($sql);
	$nrow = pg_numrows($result);

	if ($page_no <= 0) {
		$page_no == 1;
	}
	$for_end = ($page_no - 1) * $lime_num;
	$lcount = 0;
	


	for ($i = $for_end; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);
		$mag_id = $fetch->mag_id;
		$mag_catg_1 = $fetch->mag_catg_1;
		$mag_catg_2 = $fetch->mag_catg_2;
		$mag_nm = $fetch->mag_nm;
		$recomend_id = $fetch->recomend_id;
		$recom_titl = $fetch->recom_titl;
		$loopf = 1;
		$lcount++;
		$fom_name =  "formsubmit" . sprintf("%03d", $lcount);
		print "<form name=\"$fom_name\" action=\"mp-suisen-detail.php\" method=\"post\">\n";
		print "<TR BGCOLOR=\"#FFFFFF\">\n";
		print "<TD CLASS=\"text9ptnoColor\" VALIGN=\"MIDDLE\" WIDTH=\"100\"><font size=-2>\n";
		print "・" . pub_mm_catg($mag_catg_1) . "<br>"
			 . "・" . pub_mm_catg($mag_catg_2) . "</font></TD>\n";
		print "<TD CLASS=\"text9ptnoColor\" VALIGN=\"MIDDLE\" WIDTH=\"186\">\n";
		print "<a href=\"mp-mag-detail3.php?melonpai_id=$melonpai_id&melonpai_pswd=$melonpai_pswd&melonpai_name=".urlencode($melonpai_name)."&mag_id=$mag_id\" target=\"contents\">\n";
		print trim(htmlspecialchars($mag_nm)) . "</a></TD>\n";
		print "<TD CLASS=\"text9ptnoColor\" VALIGN=\"MIDDLE\" WIDTH=\"230\">\n";
		print htmlspecialchars($recom_titl) . "</TD>\n";
		print "<TD WIDTH=\"51\">\n";
		print "<INPUT TYPE=\"submit\" NAME=\"Submit\" VALUE=\"処理\">\n";
		print "</TD>\n";
		print "</TR>\n";
		print "<input type=\"hidden\" value=\"$melonpai_id\" name=\"melonpai_id\">\n";
		print "<input type=\"hidden\" value=\"$melonpai_pswd\" name=\"melonpai_pswd\">\n";
		print "<input type=\"hidden\" value=\"$melonpai_name\" name=\"melonpai_name\">\n";
		print "<input type=\"hidden\" value=\"$recomend_id\" name=\"recomend_id\">\n";
		print "<input type=\"hidden\" value=\"$page_no\" name=\"page_no\">\n";
		print "</form>\n";
		if ($lcount == 20) {
			$next_f = True;
			break;
		}
	}
	return $next_f;
}

function make_nextbtn($page_no, $next_f, $melonpai_id, $melonpai_pswd, $melonpai_name) {
	if ($page_no > 1) {
		$p_no = $page_no - 1;
		print "<td WIDTH=\"550\">\n";
		print "<div align=\"right\">\n";
		print "<form name=\"NextOkList\" action=\"mp-suisen-list.php\" method=\"post\">\n";
		print "<input type=\"Submit\" name=\"Submit\" value=\"前へ\">\n";
		print "<input type=\"hidden\" value=$p_no name=\"page_no\">\n";
		print "<input type=\"hidden\" value=\"$melonpai_id\" name=\"melonpai_id\">\n";
		print "<input type=\"hidden\" value=\"$melonpai_pswd\" name=\"melonpai_pswd\">\n";
		print "<input type=\"hidden\" value=\"$melonpai_name\" name=\"melonpai_name\">\n";
		print "</form>\n";
		print "</div>\n";
		print "</td>\n";
	}
	if ($next_f) {
		$p_no = $page_no + 1;
		print "<td>\n";
		print "<div align=\"right\">\n";
		print "<form name=\"NextOkList\" action=\"mp-suisen-list.php\" method=\"post\">\n";
		print "<input type=\"Submit\" name=\"Submit2\" value=\"次へ\">\n";
		print "<input type=\"hidden\" value=$p_no name=\"page_no\">\n";
		print "<input type=\"hidden\" value=\"$melonpai_id\" name=\"melonpai_id\">\n";
		print "<input type=\"hidden\" value=\"$melonpai_pswd\" name=\"melonpai_pswd\">\n";
		print "<input type=\"hidden\" value=\"$melonpai_name\" name=\"melonpai_name\">\n";
		print "</form>\n";
		print "</div>\n";
		print "</td>\n";
	}
}

/*======================================================================================*/
/*	(main)										*/
/*======================================================================================*/
$p_titl = "メルマガ推薦文";

mex_passchk($conn, $melonpai_id, $melonpai_pswd);
include("tmpl_html/mp-suisen-list.html");
?>
