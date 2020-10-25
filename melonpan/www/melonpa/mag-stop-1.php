<?php
/****************************************************************
 Main
****************************************************************/

if (trim ($mail_addr)  == "") {

	$sql1 =  "select * from t_mailaddr where reader_id = ";
	$sql = $sql1 .  $reader_id;
	$sql = $sql . " and mag_id = '$mag_id'";
	$result = db_exec($sql);
	$nrow = pg_numrows($result);
	$lcount = 0;
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);
		$mag_id = $fetch->mag_id;
		$sql1 =  "select * from m_magazine where mag_id = '$mag_id'";
		$result = db_exec($sql);
		if (pg_numrows($result)) {
			$fetch = pg_fetch_object($result, 0);
			$mag_nm = $fetch->mag_nm;
			include("tmpl_html/mag-stop-1.html");
		}
	}
} else {
	$sql1 =  "select * from t_mailaddr where mail_addr = ";
	$sql = $sql1 . "'" . $mail_addr ."'";
	$sql = $sql . " and mag_id = '$mag_id'";
	$result = db_exec($sql);
	$nrow = pg_numrows($result);
	$lcount = 0;
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);
		$mag_id = $fetch->mag_id;
		$sql1 =  "select * from m_magazine where mag_id = ";
		$sql = $sql1 .  "'$mag_id'";
		$result2 = db_exec($sql);
		if (pg_numrows($result2)) {
			$fetch = pg_fetch_object($result2, 0);
			$mag_nm = $fetch->mag_nm;
			include("tmpl_html/mag-stop-1.html");
		}
	}
}
/*======================================================================================*/
/*	submit login								*/
/*	(main)										*/
/*======================================================================================*/

?>
