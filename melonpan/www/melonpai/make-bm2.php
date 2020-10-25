<?php
include ("../inc/com_path.inc");
include ("$compath/err_msg.inc");
include ("$compath/com_func.inc");
include ("$compath/com_db_func.inc");
include ("$compath/sql_mcom.inc");
include ("$compath/com_mm_func.inc");
include ("$incpath/mex_passchk.inc");
include ("../sub/head.inc");

function make_categlist($melonpai_id) {
	global $gConn;

	$sql = "SELECT * FROM M_BK_CATEGORY WHERE";
	$sql .= " melonpai_id = " . $melonpai_id;
	$sql .= " ORDER BY category_id";
	$result = db_exec($sql);
	$nrow = pg_numrows($result);

	$select_print = "<select name=\"categ_select[]\">\n";
	$select_print .= "<option value=0 selected>選択して下さい</option>\n";
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);
		$categiry_id = $fetch->category_id;
		$categiry_name = $fetch->categiry_name;

		$select_print .= "<option value=" . $categiry_id . ">";
		$select_print .= $categiry_name . "</option>\n";
	}
	$select_print .= "</select>\n";
	return $select_print;
}

function make_list($page_no, $melonpai_id, $melonpai_pswd, $melonpai_name, $keyword) {
	global $gConn;

	$lime_num = 20;

	$select_print = make_categlist($melonpai_id);

	$sql = "SELECT * FROM M_MAGAZINE WHERE "
		. "mag_status_flg = '00' AND mag_pub_stop_flg = '00' AND "
		. "(mag_pub_status_flg = '01' or mag_pub_status_flg = '03') ";
	if (strlen($keyword) != 0) {
		$sql .= "and (mag_nm LIKE '%" . $keyword . "%' or "
			. "mag_intro_long LIKE '%" . $keyword . "%' or "
			. "mag_intro_short LIKE '%" . $keyword . "%') ";
	}
	$sql .= "ORDER BY mag_id";
	$result = db_exec($sql);
	$nrow = pg_numrows($result);

	if ($page_no <= 0) {
		$page_no == 1;
	}
	$for_end = ($page_no - 1) * $lime_num;

	for ($i = $for_end; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);
		$mag_id = $fetch->mag_id;
		$mag_nm = $fetch->mag_nm;
		$mag_volume = $fetch->mag_volume;
		$publisher_id = $fetch->publisher_id;
		$lcount++;
		$list_cnt = $lcount - 1;
		print "<tr>\n";
		print "<td width=\"80\" align=\"center\">\n";
		print $mag_id . "</td>\n";
		print "<input type=\"hidden\" value=\"$mag_id\" name=\"magid_list[]\">\n";
		print "<input type=\"hidden\" value=\"$publisher_id\" name=\"pub_list[]\">\n";
		print "<input type=\"hidden\" value=\"$mag_nm\" name=\"mag_nm_list[]\">\n";
		print "<td width=\"260\" align=\"left\">\n";
		print "<a href=\"mp-mag-detail3.php?melonpai_id=$melonpai_id&
			melonpai_pswd=$melonpai_pswd&melonpai_name=".urlencode($melonpai_name)."&
			mag_id=$mag_id\">\n";
		print trim($mag_nm) . "</a></td>\n";
		print "<td width=\"60\" align=\"right\">$mag_volume</td>\n";
		print "<td width=\"100\" align=\"center\">\n";
		print $select_print;
		print "</td>\n";
		print "<td width=\"100\" align=\"center\">\n";
		print "<input type=\"checkbox\" name=\"check_box[]\" value=$list_cnt>\n";
		print "</td>\n";
		print "</tr>\n";
		print "<tr><td colspan=5 bgcolor=\"#99CCFF\"></td></tr>\n";
		if ($lcount == 20) {
			$next_f = True;
			break;
		}
	}
	return $next_f;
}
function make_nextbtn($page_no, $next_f) {
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
		$print_msg =  "追加のチェックがされています<br>"
				. "「ブックマークに追加」を行うか、"
				. "追加のチェックを取り消しでください。";
 		include("tmpl_html/err-msg.html");
		exit();
	}
}

/*======================================================================================*/
/*	(main)										*/
/*======================================================================================*/
$p_titl = "ブックマークの新規作成";

if ($sub_addbm) {
	include ("make-bm3.php");
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
/*
if (strlen($keyword) == 0) {
	$print_msg =  "キーワードを入力してください。";
	include("tmpl_html/err-msg.html");
	db_logoff();
	exit();
}
*/
$sql = "SELECT count(*) as R_COUNT FROM M_MAGAZINE WHERE "
	. "mag_status_flg = '00' AND mag_pub_stop_flg = '00' AND "
	. "(mag_pub_status_flg = '01' or mag_pub_status_flg = '03') ";
if (strlen($keyword) != 0) {
	$sql .= "and (mag_nm LIKE '%" . $keyword . "%' or "
		. "mag_intro_long LIKE '%" . $keyword . "%' or "
		. "mag_intro_short LIKE '%" . $keyword . "%') ";
}
//$sql .= "ORDER BY mag_id";
$result = db_exec($sql);
if (pg_numrows($result)) {
	$fetch = pg_fetch_object($result, 0);
	$r_cnt = $fetch->r_count;
	$cnt = $r_cnt;
} else {
	$cnt = 0;
}

include("tmpl_html/make-bm2.html");
?>
