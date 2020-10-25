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

	$sql = "SELECT mag_id, mag_nm, mag_catg_1, mag_catg_2, "
		. "to_char(mag_pub_status_dt,'YYYY.MM.DD') as mag_date FROM M_MAGAZINE WHERE "
		. "mag_pub_stop_flg = '01' and "
		. "(mag_pub_status_flg = '01' or mag_pub_status_flg = '03') "
		. "ORDER BY mag_pub_status_dt";
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
		$mag_nm = $fetch->mag_nm;
		$mag_catg_1 = $fetch->mag_catg_1;
		$mag_catg_2 = $fetch->mag_catg_2;
		$mag_date = $fetch->mag_date;
		$lcount++;
		print "<TR BGCOLOR=\"#FFFFFF\">\n";
		print "<TD CLASS=\"text9ptnoColor\" VALIGN=\"MIDDLE\" WIDTH=\"39\">\n";
		print "<div align=\"center\">危</div>\n";
		print "</TD>\n";
		print "<TD CLASS=\"text9ptnoColor\" VALIGN=\"MIDDLE\" WIDTH=\"66\">\n";
		print $mag_date . "</TD>\n";
		print "<TD CLASS=\"text9ptnoColor\" VALIGN=\"MIDDLE\" WIDTH=\"96\">\n";
		print pub_mm_catg($mag_catg_1) . "、" . pub_mm_catg($mag_catg_2);
		print "</TD>\n";
		print "<td class=\"text9ptnoColor\" valign=\"MIDDLE\" width=\"326\">\n";
		print "<A HREF=\"mp-mag-detail3.php?melonpai_id=$melonpai_id&
			melonpai_pswd=$melonpai_pswd&melonpai_name=".urlencode($melonpai_name)."&
			mag_id=$mag_id\">\n";
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
				. "「危ないよリストから削除」を行うか、"
				. "解除のチェックを取り消しでください。";
 		include("tmpl_html/err-msg.html");
		exit();
	}
}

/*======================================================================================*/
/*	(main)										*/
/*======================================================================================*/
$p_titl = "危ないよ一覧";

if ($Delete) {
	include ("mp-abunai2.php");
	exit();
}
if ($Back) {
	box_check($check_box, $p_titl);
	$page_no = $page_no - 1;
} elseif ($Next) {
	box_check($check_box, $p_titl);
	$page_no = $page_no + 1;
}

//$conn = db_conxxxt();
mex_passchk($conn, $melonpai_id, $melonpai_pswd);
include("tmpl_html/mp-abunai-list.html");
//db_logoff();
?>
