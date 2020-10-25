<?php
/*======================================================================================*/
/*	mag-comeback.php								*/
/*											*/
/*======================================================================================*/
$p_titl ="メルマガ復刊";
$conn = db_conxxxt();
$sql = "select * from M_MAGAZINE where mag_id = '" . $mag_id . "'";
$stmt = OCIParse($conn, $sql);
OCIDefinebyname($stmt, "MAG_NM", &$mag_nm);
$ncols = OCIExecute($stmt, OCI_DEFAULT);
if (OCIFetch($stmt)) {
	$sql2 = "update M_MAGAZINE set MAG_STATUS_FLG = '00', "
				. "modefy_dt = SYSDATE, mag_status_chg_dt = SYSDATE "
					. "where mag_id = '" . $mag_id . "'";
	$stmt2 = OCIParse($conn, $sql2);
	if (OCIExecute($stmt2, OCI_DEFAULT)) {
		OCICommit($conn);
		include ("mag-comeback.html");
	} else {
		OCIRollback($conn);
		err_msg ("PP002", $p_titl);
	}
} else {
//		$print_msg = "指定された発行者ＩＤはありません。";
//		include ("err-msg.html");
	err_msg ("PP002", $p_titl);
}
db_logoff();

?>