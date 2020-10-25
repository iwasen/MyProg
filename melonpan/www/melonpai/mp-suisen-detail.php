<?php
include ("../inc/com_path.inc");
include ("$compath/err_msg.inc");
include ("$compath/com_func.inc");
include ("$compath/com_db_func.inc");
include ("$compath/sql_mcom.inc");
include ("$compath/com_mm_func.inc");
include ("$incpath/mex_passchk.inc");
include ("../sub/head.inc");

/*======================================================================================*/
/*	(main)										*/
/*======================================================================================*/
$p_titl = "¥á¥ë¥Þ¥¬¿äÁ¦Ê¸";
mex_passchk($conn, $melonpai_id, $melonpai_pswd);
$sql = "SELECT m_magazine.mag_id, m_magazine.mag_nm, m_magazine.mag_catg_1, "
	. "m_magazine.mag_catg_2, t_recomend.recomend_id, t_recomend.recom_titl, "
	. "t_recomend.recom_text, t_recomend.recom_nic, "
	. "to_char(t_recomend.modify_dt, 'yyyy/mm/dd') as recomend_day "
	. "FROM M_MAGAZINE, T_RECOMEND WHERE "
	. "m_magazine.mag_id = t_recomend.mag_id AND "
	. "t_recomend.recomend_id = $recomend_id";
if (!$result = db_exec($sql)) {
	err_msg ("PM002", $p_titl);
	exit();
} else {
	if (pg_numrows($result)) {
		$fetch = pg_fetch_object($result, 0);
		$mag_catg_1 = $fetch->mag_catg_1;
		$mag_catg_2 = $fetch->mag_catg_2;
		$mag_nm = $fetch->mag_nm;
		$mag_id = $fetch->mag_id;
		$recomend_id = $fetch->recomend_id;
		$recom_titl = $fetch->recom_titl;
		$recom_text = $fetch->recom_text;
		$recom_nic = $fetch->recom_nic;
		$recomend_day = $fetch->recomend_day;
	}
}
$sql = "SELECT * FROM M_MELONPAI WHERE melonpai_id = " . $melonpai_id;
$result = db_exec($sql);
if (!pg_numrows($result)) {
	err_msg ("PM002", $p_titl);
	exit();
} else {
	$fetch = pg_fetch_object($result, 0);
	$melonpai_point = $fetch->melonpai_point;
}
include("tmpl_html/mp-suisen-detail.html");

?>
