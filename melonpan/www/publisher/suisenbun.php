<?php
/****************************************************************
 Main
****************************************************************/
pub_passchk($conn, $publisher_id,$owner_pswd);
$sql = "SELECT * FROM T_RECOMEND WHERE recomend_id = $recomend_id";
$result = db_exec($sql);
if (pg_numrows($result)) {
	$fetch = pg_fetch_object($result, 0);
	$recom_titl = $fetch->recom_titl;
	$recom_text = $fetch->recom_text;
	$recom_nic = $fetch->recom_nic;
	$mag_id = $fetch->mag_id;
}
include ("suisenbun.html");
?>
