<?php
include ("../inc/com_path.inc");
include ("$compath/err_msg.inc");
include ("$compath/com_func.inc");
include ("$compath/com_db_func.inc");
include ("$compath/sql_mcom.inc");
include ("$compath/com_mm_func.inc");
include ("$incpath/mex_passchk.inc");
include ("../sub/head.inc");

function make_list($page_no, $melonpai_id, $melonpai_pswd, $melonpai_name, $keyword) {
	global $gConn;

	$lime_num = 20;
	$sql = "SELECT m_magazine.mag_id, m_magazine.mag_nm, m_magazine.mag_catg_1, "
		. "m_magazine.mag_catg_2, m_magazine.publisher_id, m_publisher.owner_nm_kanj "
		. "FROM M_MAGAZINE, M_PUBLISHER WHERE "
		. "m_magazine.publisher_id = m_publisher.publisher_id AND "
		. "(m_magazine.osusume_flg = 0 or m_magazine.osusume_flg IS NULL) AND "
		. "m_magazine.mag_pub_stop_flg = '00' AND "
		. "(m_magazine.mag_pub_status_flg = '01' or m_magazine.mag_pub_status_flg = '03') ";
	if (strlen($keyword) != 0) {
		$sql .= "and (m_magazine.mag_nm LIKE '%" . $keyword . "%' or "
			. "m_magazine.mag_intro_long LIKE '%" . $keyword . "%' or "
			. "m_magazine.mag_intro_short LIKE '%" . $keyword . "%') ";
	}
	$sql .= "ORDER BY m_magazine.mag_id";
	$result = db_exec($sql);
	$nrow = pg_numrows($result);

	if ($page_no <= 0) {
		$page_no = 1;
	}
	$for_end = ($page_no - 1) * $lime_num;
	$lcount = 0;

		for ($i = $for_end; $i < $nrow; $i++) {
			$fetch = pg_fetch_object($result, $i);
			$mag_id = $fetch->mag_id;
			$mag_nm = $fetch->mag_nm;
			$mag_catg_1 = $fetch->mag_catg_1;
			$mag_catg_2 = $fetch->mag_catg_2;
			$publisher_id = $fetch->publisher_id;
			$owner_nm_kanj = $fetch->owner_nm_kanj;

			$lcount++;
			$fom_name =  "formmag" . sprintf("%03d", $lcount);
			$fom_pub =  "formpub" . sprintf("%03d", $lcount);

			print "<TR BGCOLOR=\"#FFFFFF\">\n";
			print "<TD CLASS=\"text9ptnoColor\" VALIGN=\"MIDDLE\" WIDTH=\"140\">\n";
			print pub_mm_catg($mag_catg_1) . "、" . pub_mm_catg($mag_catg_2) . "</TD>\n";
			print "<form name=\"$fom_name\" action=\"mp-mag-detail.php\" method=\"post\">\n";
			print "<TD CLASS=\"text9ptnoColor\" VALIGN=\"MIDDLE\" WIDTH=\"380\">\n";
			print "<A HREF=\"JavaScript:document.$fom_name.submit();\">\n";
			print $mag_nm . "</A>\n";
			print "<input type=\"hidden\" value=\"$melonpai_id\" name=\"melonpai_id\">\n";
			print "<input type=\"hidden\" value=\"$melonpai_pswd\" name=\"melonpai_pswd\">\n";
			print "<input type=\"hidden\" value=\"$melonpai_name\" name=\"melonpai_name\">\n";
			print "<input type=\"hidden\" value=\"$mag_id\" name=\"mag_id\">\n";
			print "<input type=\"hidden\" value=\"$keyword\" name=\"keyword\">\n";
			print "</TD>\n";
			print "</form>\n";
			//print "<form name=\"$fom_pub\" action=\"mp-hakkousya-syousai.php\" method=\"post\">\n";
			//print "<TD CLASS=\"text9ptnoColor\" VALIGN=\"MIDDLE\" WIDTH=\"55\">\n";
			//print "<A HREF=\"JavaScript:document.$fom_pub.submit();\" target=\"contents\">\n";
			//print $owner_nm_kanj . "</A>\n";
			//print "<input type=\"hidden\" value=\"$melonpai_id\" name=\"melonpai_id\">\n";
			//print "<input type=\"hidden\" value=\"$melonpai_pswd\" name=\"melonpai_pswd\">\n";
			//print "<input type=\"hidden\" value=\"$melonpai_name\" name=\"melonpai_name\">\n";
			//print "<input type=\"hidden\" value=\"$publisher_id\" name=\"publisher_id\">\n";
			//print "</TD>\n";
			//print "</form>\n";
			print "</TR>\n";
		if ($lcount == 20) {
			$next_f = True;
			break;
		}
	}
	return $next_f;
}

function make_nextbtn($page_no, $next_f, $melonpai_id, $melonpai_pswd, $melonpai_name, $keyword) {
	if ($page_no > 1) {
		$p_no = $page_no - 1;
		print "<td WIDTH=\"550\">\n";
		print "<div align=\"right\">\n";
		print "<form name=\"NextOkList\" action=\"mp-mag-list.php\" method=\"post\">\n";
		print "<input type=\"Submit\" name=\"Submit\" value=\"前へ\">\n";
		print "<input type=\"hidden\" value=$p_no name=\"page_no\">\n";
		print "<input type=\"hidden\" value=\"$melonpai_id\" name=\"melonpai_id\">\n";
		print "<input type=\"hidden\" value=\"$melonpai_pswd\" name=\"melonpai_pswd\">\n";
		print "<input type=\"hidden\" value=\"$melonpai_name\" name=\"melonpai_name\">\n";
		print "<input type=\"hidden\" value=\"$keyword\" name=\"keyword\">\n";
		print "</form>\n";
		print "</div>\n";
		print "</td>\n";
	}
	if ($next_f) {
		$p_no = $page_no + 1;
		print "<td>\n";
		print "<div align=\"right\">\n";
		print "<form name=\"NextOkList\" action=\"mp-mag-list.php\" method=\"post\">\n";
		print "<input type=\"Submit\" name=\"Submit2\" value=\"次へ\">\n";
		print "<input type=\"hidden\" value=$p_no name=\"page_no\">\n";
		print "<input type=\"hidden\" value=\"$melonpai_id\" name=\"melonpai_id\">\n";
		print "<input type=\"hidden\" value=\"$melonpai_pswd\" name=\"melonpai_pswd\">\n";
		print "<input type=\"hidden\" value=\"$melonpai_name\" name=\"melonpai_name\">\n";
		print "<input type=\"hidden\" value=\"$keyword\" name=\"keyword\">\n";
		print "</form>\n";
		print "</div>\n";
		print "</td>\n";
	}
}

/*======================================================================================*/
/*	(main)										*/
/*======================================================================================*/
$p_titl = "おすすめ／危ないよ";

mex_passchk($conn, $melonpai_id, $melonpai_pswd);

include("tmpl_html/mp-mag-list.html");
?>
