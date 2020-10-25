<?php
include ("../inc/com_path.inc");
include ("$compath/err_msg.inc");
include ("$compath/com_func.inc");
include ("$compath/com_db_func.inc");
include ("$compath/sql_mcom.inc");
include ("$compath/com_mm_func.inc");
include ("$incpath/mex_passchk.inc");
include ("../sub/head.inc");

function make_head($categiry_name) {
	$head = "<p><b><font color=\"#003399\"><span class=\"text9ptnoColor\">\n";
	$head .= "【" . $categiry_name . "】\n";
	$head .= "</span></font></b></p>\n";
	$head .= "<table width=\"100%\" border=\"0\" cellspacing=\"1\" cellpadding=\"0\">\n";
	$head .= "<tr class=\"text9ptnoColor\"> \n";
	$head .= "<td bgcolor=\"#99CCFF\" width=\"17%\"> \n";
	$head .= "<div align=\"center\"><b><font color=\"#003399\">ID</font></b></div>\n";
	$head .= "</td>\n";
	$head .= "<td bgcolor=\"#99CCFF\" width=\"64%\"> \n";
	$head .= "<div align=\"center\"><b><font color=\"#003399\">マガジン名</font></b></div>\n";
	$head .= "</td>\n";
	$head .= "<td bgcolor=\"#99CCFF\" width=\"19%\"> \n";
	$head .= "<div align=\"center\"><b><font color=\"#003399\">削除チェック</font></b></div>\n";
	$head .= "</td>\n";
	$head .= "</tr>\n";
	return $head;
}

function make_body($bm_id, $mag_id, $mag_nm, $list_cnt,$melonpai_id,$melonpai_pswd,$melonpai_name) {
	$mag_nm = trim($mag_nm);
	$body = "<tr>\n";
	$body .= "<td class=\"text9ptnoColor\" width=\"17%\">" . $mag_id . "</td>\n";
	$body .= "<td class=\"text9ptnoColor\" width=\"64%\">";
	$body .= "<a href=\"mp-mag-detail3.php?melonpai_id=$melonpai_id&melonpai_pswd=$melonpai_pswd&melonpai_name=".urlencode($melonpai_name)."&mag_id=$mag_id\">\n";
	$body .= $mag_nm . "</a></td>\n";
	$body .= "<input type=\"hidden\" value=\"$bm_id\" name=\"bmid_list[]\">\n";
	$body .= "<input type=\"hidden\" value=\"$mag_id\" name=\"magid_list[]\">\n";
	$body .= "<input type=\"hidden\" value=\"$mag_nm\" name=\"mag_nm_list[]\">\n";
	$body .= "<td width=\"19%\">\n";
	$body .= "<div align=\"center\">\n";
	$body .= "<input type=\"checkbox\" name=\"check_box[]\" value=$list_cnt>\n";
	$body .= "</div>\n";
	$body .= "</td>\n";
	$body .= "</tr>\n";
	$body .= "<tr><td colspan=\"3\" height=1 bgcolor=\"#99CCFF\"></td><tr>\n";
	return $body;
}
function make_footer() {
	$footer = "</table>\n";
	return $footer;
}

function exec_sql($conn, $stmt, $p_titl) {
	if (!(OCIExecute($stmt, OCI_DEFAULT))) {
		db_logoff();
		err_msg ("PM002", $p_titl);
		exit();
	}
}

function make_list($melonpai_id,$melonpai_pswd,$melonpai_name) {
global $gConn;

	$p_titl = "ブックマークの編集";
	$list_print = "";
	$list_cnt = 0;

	$sql = "SELECT * FROM M_BK_CATEGORY WHERE";
	$sql .= " melonpai_id = " . $melonpai_id;
	$sql .= " ORDER BY category_id";
	$result = db_exec($sql);
	$nrow = pg_numrows($result);
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);
		$category_id = $fetch->category_id;
		$categiry_name = $fetch->categiry_name;

		$list_print .= make_head($categiry_name);
		$sql2 = "select t_bookmark.bm_id, t_bookmark.mag_id, m_magazine.mag_nm";
 		$sql2 .= " from t_bookmark, m_magazine where";
		$sql2 .= " t_bookmark.melonpai_id = " . $melonpai_id;
		$sql2 .= " and t_bookmark.category_id = " . $category_id;
		$sql2 .= " and m_magazine.mag_id = t_bookmark.mag_id";
		$sql2 .= " order by t_bookmark.mag_id";
		$result2 = db_exec($sql2);
		$nrow2 = pg_numrows($result2);
		$list_cnt2 = 0;
		for ($j = 0; $j < $nrow2; $j++) {
			$fetch = pg_fetch_object($result2, $j);
			$bm_id = $fetch->bm_id;
			$mag_nm = $fetch->mag_nm;
			$mag_id = $fetch->mag_id;
			$list_print .= make_body($bm_id, $mag_id, $mag_nm, $list_cnt,$melonpai_id,$melonpai_pswd,$melonpai_name);
			$list_cnt++;
			$list_cnt2++;
		}
		if ($list_cnt2 == 0) {
			$list_print .= "<tr><td colspan=\"3\" align=\"center\"><br>登録はありません</td></tr>\n";
		}
		$list_print .= make_footer();
	}
	return $list_print;
}

/*======================================================================================*/
/*	(main)										*/
/*======================================================================================*/
$p_titl = "ブックマークの編集";

mex_passchk($conn, $melonpai_id, $melonpai_pswd);

include("tmpl_html/edit-bookmark.html");
?>
