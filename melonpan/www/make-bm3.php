<?php

/*======================================================================================*/
/*	(main)										*/
/*======================================================================================*/
$p_titl = "�֥å��ޡ������ɲ�";

if (($conn = db_conxxxt()) == "-1") {
	err_msg ("PM001", $p_titl);
	exit();
}
mex_passchk($conn, $melonpai_id, $melonpai_pswd);

$chk_num = count($check_box);
$err_cnt = 0;
$sac_cnt = 0;
$wor_cnt = 0;
$job_err_msg = "";
$job_sac_msg = "";
$job_wor_msg = "";
for ($i = 0; $i<$chk_num; $i++) {
	$mag_id = $magid_list[$check_box[$i]];
	$category_id = $categ_select[$check_box[$i]];
	$publisher_id = $pub_list[$check_box[$i]];
	$mag_nm = $mag_nm_list[$check_box[$i]];

	if ($category_id == 0) {
		$job_err_msg .= $mag_id . "��" . $mag_nm . "�ʥ��ƥ��꡼�����򤷤Ƥ���������<br>";
		$err_cnt++;
		$read_flg = FALSE;
	} else {
		$sql = "SELECT * FROM T_BOOKMARK WHERE";
		$sql .= " melonpai_id = " . $melonpai_id;
		$sql .= " and category_id = " . $category_id;
		$sql .= " and mag_id = '" . $mag_id . "'";
		$stmt = OCIParse($conn, $sql);
		if (!(OCIExecute($stmt, OCI_DEFAULT))) {
			$job_err_msg .= $mag_id . "��" . $mag_nm . "<br>";
			$err_cnt++;
			$read_flg = FALSE;
		} else {
			if (OCIFetch($stmt)) {
				$job_wor_msg .= $mag_id . "��" . $mag_nm . "�ϡ���Ͽ����Ƥ��ޤ���<br>";
				$wor_cnt++;
				$read_flg = FALSE;
			} else {
				$read_flg = TRUE;
			}
		}
		OCIFreeStatement($stmt);
	}
	if ($read_flg) {
		if (($bm_id = update_squ_key ($conn, "T_BOOKMARK")) != "-1") {
			OCICommit($conn);
			$read_flg = TRUE;
		} else {
			OCIRollback($conn);
			OCICommit($conn);
			$job_err_msg .= $w_mag_id . "<br>";
			$err_cnt++;
			$read_flg = FALSE;
		}
	}
	if ($read_flg) {
		$sql = "INSERT INTO T_BOOKMARK ( ";
		$sql .= " bm_id, melonpai_id, category_id, mag_id, publisher_id, modify_dt)";
		$sql .= " VALUES ( ";
		$sql .= " :BM_ID, :MELONPAI_ID, :CATEGORY_ID, :MAG_ID, :PUBLISHER_ID, SYSDATE)";
		$stmt = OCIParse($conn, $sql);
		OCIbindbyname ($stmt, ":BM_ID", &$bm_id, -1);
		OCIbindbyname ($stmt, ":MELONPAI_ID", &$melonpai_id, -1);
		OCIbindbyname ($stmt, ":CATEGORY_ID", &$category_id, -1);
		OCIbindbyname ($stmt, ":MAG_ID", &$mag_id, -1);
		OCIbindbyname ($stmt, ":PUBLISHER_ID", &$publisher_id, -1);
		if (!(OCIExecute($stmt, OCI_DEFAULT))) {
			$job_err_msg .= $mag_id . "��" . $mag_nm . "<br>";
			$err_cnt++;
		} else {
			OCICommit($conn);
			$job_sac_msg .= $mag_id . "��" . $mag_nm . "<br>";
			$sac_cnt++;
		}
		OCIFreeStatement($stmt);
	}
}
db_logoff();
$last_msg = "���ɲûؼ��� " . $chk_num . "��<br><br>";
if ($err_cnt > 0) {
	$last_msg .= "���ɲü���: " . $err_cnt . "��<br><br>" . $job_err_msg;
}
if ($wor_cnt > 0) {
	$last_msg .= "����ʣ��Ͽ: " . $wor_cnt . "��<br><br>" . $job_wor_msg;
}
if ($sac_cnt > 0) {
	$last_msg .= "�����ｪλ: " . $sac_cnt . "��<br>" . $job_sac_msg;
}
include ("make-bm3.html");

?>
