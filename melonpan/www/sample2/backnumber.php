<?php
include ("../inc/com_db_func.inc");

	$sql = "select L.MAG_ID,L.MAG_TITL,L.MAG_BODY,to_char(L.MODEFY_DT, 'YYYY/MM/DD HH24:MI:SS') as modefy_dt,L.MAG_VOLUME,";
	$sql .= "M.MAG_NM,L.BODY_ID ";
	$sql .= " from L_BACKNUMBER L,M_MAGAZINE M,M_PUBLISHER P ";
	$sql .= " where L.INDEX_ID = $index_id and ";
//	$sql .= " where (L.rowid = '$back_rid') and ";
	$sql .= " L.MAG_ID = M.MAG_ID and ";
	$sql .= " M.PUBLISHER_ID = P.PUBLISHER_ID ";
	$result = db_exec($sql);
	if ($cols = pg_numrows($result)) {
		$fetch = pg_fetch_object($result, 0);
		$mag_id = $fetch->mag_id;
		$mag_title = $fetch->mag_titl;
		$mag_body = $fetch->mag_body;
		$modefy_dt = $fetch->modefy_dt;
		$mag_volume = $fetch->mag_volume;
		$mag_nm = $fetch->mag_nm;
		$body_id = $fetch->body_id;
	}
	$cnt = 0;

	if ($cols < 1 ) { $table = "ファイルが存在しません"; }
	$mag_body = ereg_replace("\n","<br>",$mag_body);
	include("tmpl_html/backnumber.html");
	exit();

?>
