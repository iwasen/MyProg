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
$p_titl = "ブックマークの編集";

mex_passchk($conn, $melonpai_id, $melonpai_pswd);

$chk_num = count($check_box);
$err_cnt = 0;
$sac_cnt = 0;
$wor_cnt = 0;
$job_err_msg = "";
$job_sac_msg = "";
$job_wor_msg = "";
for ($i = 0; $i<$chk_num; $i++) {
	$bm_id = $bmid_list[$check_box[$i]];
	$mag_id = $magid_list[$check_box[$i]];
	$mag_nm = $mag_nm_list[$check_box[$i]];

	$sql = "SELECT * FROM T_BOOKMARK WHERE";
	$sql .= " bm_id = " . $bm_id;

	if (!$result = db_exec($sql)) {
		$job_err_msg .= "　".$mag_id . "：" . $mag_nm . "<br>";
		$err_cnt++;
		$read_flg = FALSE;
	} else {
		if (pg_numrows($result)) {
			$read_flg = TRUE;
		} else {
			$job_wor_msg .= "　".$mag_id . "：" . $mag_nm . "は、削除されています。<br>";
			$wor_cnt++;
			$read_flg = FALSE;
		}
	}
	if ($read_flg) {
		$sql = "DELETE from T_BOOKMARK  WHERE bm_id = $bm_id";
		if (!db_exec($sql)) {
			$job_err_msg .= "　".$mag_id . "：" . $mag_nm . "<br>";
			$err_cnt++;
		} else {
			$job_sac_msg .= "　".$mag_id . "：" . $mag_nm . "<br>";
			$sac_cnt++;
		}
	}
}
$kekka_msg = "<b>&lt;■削除指示： " . $chk_num . "件&gt;</b><br>";
if ($err_cnt > 0) {
	$last_msg .= "■削除失敗： " . $err_cnt . "件<br>" . $job_err_msg;
}
if ($wor_cnt > 0) {
	$last_msg .= "■削除済み： " . $wor_cnt . "件<br>" . $job_wor_msg;
}
if ($sac_cnt > 0) {
	$last_msg .= "■正常終了： " . $sac_cnt . "件<br>" . $job_sac_msg;
}
include("tmpl_html/edit-bookmark2.html");

?>
