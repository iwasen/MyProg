<?php
include ("../inc/com_path.inc");
include ("$compath/err_msg.inc");
include ("$compath/com_func.inc");
include ("$compath/com_db_func.inc");
include ("$compath/sql_mcom.inc");
include ("$compath/com_mm_func.inc");
include ("$incpath/mex_passchk.inc");
include ("../sub/head.inc");

function make_list($page_no, $melonpai_id, $melonpai_pswd, $melonpai_name) {
	global $gConn;

	$lime_num = 20;
	$sql = "SELECT MM.mag_id, MM.mag_nm, MM.mag_catg_1, MM.mag_catg_2, ";
	$sql .= "to_char(OL.sel_date,'YYYY.MM.DD') as mag_date FROM M_MAGAZINE MM, T_OSUSUME_LIST OL WHERE ";
	$sql .= "MM.osusume_flg = '1' and ";
	$sql .= "(MM.mag_pub_status_flg = '01' or MM.mag_pub_status_flg = '03') and ";
	$sql .= "MM.osusume_id = " . $melonpai_id . " and MM.mag_id = OL.mag_id and ";
	$sql .= "OL.date_to is NULL ";		//2002.02.13 ２重表示回避
	$sql .= "ORDER BY mag_date";		//2001.11.26 登録日順から選定日順に変更
	$result = db_exec($sql);
	$nrow = pg_numrows($result);
	if ($page_no <= 0) {
		$page_no == 1;
	}
	$for_end = ($page_no - 1) * $lime_num;

	$lcount = 0;
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);
		$mag_id = $fetch->mag_id;
		$mag_nm = $fetch->mag_nm;
		$mag_catg_1 = $fetch->mag_catg_1;
		$mag_catg_2 = $fetch->mag_catg_2;
		$mag_date = $fetch->mag_date;
		print "<TR BGCOLOR=\"#FFFFFF\">\n";
		print "<TD CLASS=\"text9ptnoColor\" VALIGN=\"MIDDLE\" WIDTH=\"39\">\n";
		print "<div align=\"center\">薦</div>\n";
		print "</TD>\n";
		print "<TD CLASS=\"text9ptnoColor\" VALIGN=\"MIDDLE\" WIDTH=\"66\">\n";
		print $mag_date . "</TD>\n";
		print "<TD CLASS=\"text9ptnoColor\" VALIGN=\"MIDDLE\" WIDTH=\"96\">\n";
		print pub_mm_catg($mag_catg_1) . "、" . pub_mm_catg($mag_catg_2);
		print "</TD>\n";
		print "<td class=\"text9ptnoColor\" valign=\"MIDDLE\" width=\"326\">\n";
		print "<A HREF=\"mp-mag-detail3.php?melonpai_id=$melonpai_id&melonpai_pswd=$melonpai_pswd&melonpai_name=$melonpai_name&mag_id=$mag_id\">\n";
		print trim($mag_nm) . "</a>\n";
		print "</td>\n";
		print "<TD CLASS=\"text9ptnoColor\" VALIGN=\"MIDDLE\" WIDTH=\"32\"> \n";
		print "<div align=\"center\"> \n";
		print "<input type=\"checkbox\" name=\"check_box[]\" value=$mag_id>\n";
		print "</div>\n";
		print "</TD>\n";
		print "</TR>\n";
		if ($lcount == 20) {
			$next_f = True;
			break;
		}
	}
	return $next_f;
}

function make_nextbtn($page_no, $next_f, $melonpai_id, $melonpai_pswd, $melonpai_name) {
	if ($page_no > 1) {
		print "<td WIDTH=\"550\">\n";
		print "<div align=\"right\">\n";
		print "<input type=\"Submit\" name=\"Back\" value=\"前へ\">\n";
		print "</div>\n";
		print "</td>\n";
	}
	if ($next_f) {
		print "<td>\n";
		print "<div align=\"right\">\n";
		print "<input type=\"Submit\" name=\"Next\" value=\"次へ\">\n";
		print "</div>\n";
		print "</td>\n";
	}
}

function box_check($check_box, $p_titl) {
	if (count($check_box)) {
		$print_msg =  "解除のチェックがされています<br>"
				. "「おすすめリストから削除」を行うか、"
				. "解除のチェックを取り消しでください。";
 		include("tmpl_html/err-msg.html");
		exit();
	}
}

/*======================================================================================*/
/*	(main)										*/
/*======================================================================================*/
$p_titl = "おすすめ一覧";

if ($Delete) {
	include ("mp-osusume2.php");
	exit();
}
if ($Back) {
	box_check($check_box, $p_titl);
	$page_no = $page_no - 1;
} elseif ($Next) {
	box_check($check_box, $p_titl);
	$page_no = $page_no + 1;
}

mex_passchk($conn, $melonpai_id, $melonpai_pswd);
include("tmpl_html/mp-osusume-list.html");
?>
