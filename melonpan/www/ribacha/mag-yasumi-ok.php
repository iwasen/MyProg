<?php

/*======================================================================================*/
/*	mag-yasumi-ok.php								*/
/*											*/
/*======================================================================================*/
$p_titl ="���ޥ��ٴ�";
if ($reason_sel == "") {
		$print_msg = "�ֵٴ���ͳ�פ����򡢤⤷�������Ϥ��Ƥ�������";
		include ("err-msg.html");
		exit();
}
if ($reason_sel == "����¾") {
	if (strlen($reason) > 40) {
		$print_msg = "�ֵٴ���ͳ�פ����ѣ���ʸ����������Ϥ��Ƥ�������";
		include ("err-msg.html");
		exit();
	}else if (strlen($reason) == 0) {
		$print_msg = "�ֵٴ���ͳ�פ������Ƥ�������";
		include ("err-msg.html");
		exit();
	}
}

$conn = db_conxxxt();
pub_passchk($conn, $publisher_id,$owner_pswd);

$sql1 = mp_make_sql ("SELECT_M_PUBLISHER");
$stmt1 = OCIParse($conn, $sql1);
$ret01 = OCIbindbyname ($stmt1, ":PUBLISHER_ID", &$publisher_id, -1);
OCIDefinebyname($stmt1, "MAIL_ADD_MAIN", &$mail_add_main);
OCIDefinebyname($stmt1, "OWNER_PSWD", &$owner_pswd);
$ncols = OCIExecute($stmt1, OCI_DEFAULT);
if (OCIFetch($stmt1)) {
	if ($OWNER_PSWD_I ==  trim($owner_pswd)) {
		$sql2 = "select * from M_MAGAZINE where mag_id = :MAG_ID";
		$stmt2 = OCIParse($conn, $sql2);
		OCIbindbyname ($stmt2, ":MAG_ID", &$mag_id, -1);
		OCIDefinebyname($stmt2, "MAG_NM", &$mag_nm);
		$ncols = OCIExecute($stmt2, OCI_DEFAULT);
		if (OCIFetch($stmt2)) {
			$sql = "update M_MAGAZINE set MAG_STATUS_FLG = '01', "
				. "modefy_dt = SYSDATE, mag_status_chg_dt = SYSDATE "
				. "where mag_id = :MAG_ID";
			$stmt = OCIParse($conn, $sql);
			OCIbindbyname ($stmt, ":MAG_ID", &$mag_id, -1);
			if ( $retDel = OCIExecute($stmt, OCI_DEFAULT) ) {
				$w_reason = $reason_sel;
				if ($reason_sel == "����¾") {
					if ($reason == "") { $reason = "����¾";}
					$w_reason = $reason;
				} elseif ($reason_sel == "") {
					$w_reason = "����¾";
				}
				$mag_stop_id = update_squ_key($conn,
								"T_MAG_STOPREASON");
				$retRea = FALSE;
				if ($mag_stop_id != "-1") {
					$retRea = pub_magstoprea_insert ($conn,
						$mag_stop_id, $mag_id, $w_reason);
				}
			}
			if ($retDel && $retRea) {
				OCICommit ($conn);
				include ("mag-yasumi-ok.html");
			} else {
				$ret01 = OCIRollback ($conn);
				$print_msg = "�ٴ��������Ǥ��ޤ���Ǥ�����";
				include ("err-msg.html");
			}
		} else {
			$print_msg = "���Ǥ˺���ѤߤǤ���";
			include ("err-msg.html");
		}
	} else {
		$print_msg = "���ꤷ���ѥ���ɤ��㤤�ޤ���";
		include ("err-msg.html");
	}
} else {
	$print_msg = "���ꤵ�줿ȯ�ԼԣɣĤϤ���ޤ���";
	include ("err-msg.html");
}
db_logoff();

?>