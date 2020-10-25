<?php
/*======================================================================================*/
/*	mag-comeback.php								*/
/*											*/
/*======================================================================================*/
$p_titl ="メルマガ復刊";

$sql = "select * from M_MAGAZINE where mag_id = '" . $mag_id . "'";
$result = db_exec($sql);
if (pg_numrows($result)) {
	$fetch = pg_fetch_object($result, 0);
	$mag_nm = $fetch->mag_nm;

	db_begin_trans();
	$sql2 = "update M_MAGAZINE set MAG_STATUS_FLG = '00', "
				. "modefy_dt = 'now', mag_status_chg_dt = 'now' "
					. "where mag_id = '" . $mag_id . "'";
	if (db_exec($sql2)) {
		db_commit_trans();
		include ("mag-comeback.html");
	} else {
		db_rollback();
		err_msg ("PP002", $p_titl);
	}
} else {
//		$print_msg = "指定された発行者ＩＤはありません。";
//		include ("err-msg.html");
	err_msg ("PP002", $p_titl);
}
?>