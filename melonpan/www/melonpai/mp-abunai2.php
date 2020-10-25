<?php
//include ("../sub/head.inc");

function melx_log ($conn, $melonpai_id, $mag_id) {
	db_begin_trans();
	if (($log_num = update_squ_key ("L_MELONPAI_LOG")) != "-1") {
		db_commit_trans();
	} else {
		db_rollback();
		err_msg ("PM002", $p_titl);
		exit();
	}

	$log_pro_no = 4;

	$sql  = "INSERT INTO L_MELONPAI_LOG (log_num, melonpai_id, mag_id, log_pro_no, modify_dt )  "
		. "VALUES ( $log_num, $melonpai_id, '$mag_id', $log_pro_no, 'now')";

	db_exec($sql);
}

/*======================================================================================*/
/*	(main)										*/
/*======================================================================================*/
$p_titl = "危ないよメルマガ解除";
mex_passchk($conn, $melonpai_id, $melonpai_pswd);

$chk_num = count($check_box);
$err_cnt = 0;
$sac_cnt = 0;
$job_err_msg = "";
$job_sac_msg = "";
for ($i = 0; $i<$chk_num; $i++) {
	$read_flg = TRUE;
	$w_mag_id = $check_box[$i];
	$sql = "SELECT * FROM M_MAGAZINE WHERE mag_id = '$w_mag_id' for update";
	$result = db_exec($sql);
	if (!pg_numrows($result)) {
		$job_err_msg .= $w_mag_id . "<br>";
		$read_flg = FALSE;
		$err_cnt++;
	} else {
		$fetch = pg_fetch_object($result, 0);
		$mag_nm = $fetch->mag_nm;
	}
	if ($read_flg) {
		$sql = "UPDATE M_MAGAZINE SET mag_pub_stop_flg = '00', osusume_reason = '' WHERE mag_id = '$w_mag_id'";
		if (!db_exec($sql)) {
			$job_err_msg .= $w_mag_id . "<br>";
			$read_flg = FALSE;
			$err_cnt++;
		} else {
			$job_sac_msg .= "　".$w_mag_id . "：" . $mag_nm . "<br>";
			$sac_cnt++;
			//Melonpai Log
			melx_log ($conn, $melonpai_id, $w_mag_id);
		}
	}
}
$kekka_msg = "<b>&lt;■解除指示：" . $chk_num . "件&gt;</b><br>";
if ($err_cnt > 0) {
$last_msg .= "■解除失敗：" . $err_cnt . "件<br>" . $job_err_msg;
}
if ($sac_cnt > 0) {
$last_msg .= "■正常終了：" . $sac_cnt . "件<br>" . $job_sac_msg;
}
include("tmpl_html/mp-abunai2.html");

?>
