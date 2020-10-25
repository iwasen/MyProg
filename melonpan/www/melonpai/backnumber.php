<?php
include ("../inc/com_db_func.inc");
include ("../melonpa/inc/error_msg_list.inc");
include ("../sub/head.inc");

/****************************************************************
 Main
****************************************************************/
$sql = "select L.MAG_ID,L.MAG_TITL,L.MAG_BODY,to_char(L.MODEFY_DT, 'YYYY/MM/DD') as modefy_dt,L.MAG_VOLUME,";
$sql .= "M.MAG_NM,L.BODY_ID ";
$sql .= " from L_BACKNUMBER L,M_MAGAZINE M,M_PUBLISHER P ";
$sql .= " where L.INDEX_ID = $index_id and ";
//$sql .= " where (L.rowid = '$back_rid') and ";
$sql .= " L.MAG_ID = M.MAG_ID and ";
$sql .= " M.PUBLISHER_ID = P.PUBLISHER_ID ";
$result = db_exec($sql);
$ncols = pg_numrows($result);
if ($ncols >= 1) {
	$fetch = pg_fetch_object($result, 0);
	$mag_id = $fetch->mag_id;
	$mag_title = $fetch->mag_titl;
	$mag_body = $fetch->mag_body;
	$modefy_dt = $fetch->modefy_dt;
	$mag_volume = $fetch->mag_volume;
	$mag_nm = $fetch->mag_nm;
}
$cnt = 0;

if ($cols < 1 ) { $table = $em_back_1; }
$mag_body = ereg_replace("\n","<br>",$mag_body);
include("tmpl_html/backnumber.html");
exit();

?>
