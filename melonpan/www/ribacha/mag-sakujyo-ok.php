<?php
/*======================================================================================*/
/*	mag-sakujyo-ok.php								*/
/*											*/
/*======================================================================================*/
$p_titl ="���ޥ��Ѵ�";
if ($reason_sel == "����¾") {
	if (strlen($reason) > 40) {
		$print_msg = "���Ѵ���ͳ�פ����ѣ���ʸ����������Ϥ��Ƥ�������";
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
		if (inp_chk_reason($reason, $reason_sel)) {
 			$sql2 = "select * from M_MAGAZINE where mag_id = :MAG_ID";
			$stmt2 = OCIParse($conn, $sql2);
			OCIbindbyname ($stmt2, ":MAG_ID", &$mag_id, -1);
			OCIDefinebyname($stmt2, "MAG_NM", &$mag_nm);
			$ncols = OCIExecute($stmt2, OCI_DEFAULT);
			if (OCIFetch($stmt2)) {
				$sql = "delete from M_MAGAZINE where mag_id = :MAG_ID";
				$stmt = OCIParse($conn, $sql);
				OCIbindbyname ($stmt, ":MAG_ID", &$mag_id, -1);
				if ( $retDel = OCIExecute($stmt, OCI_DEFAULT) ) {
					$w_reason = $reason_sel;
					if ($reason_sel == "����¾") {
						if ($reason == "") { $reason = "����¾";}
						$w_reason = $reason;
					} elseif ($reason_sel == "") {
						if ($reason == "") { $reason = "����¾";}
						$w_reason = $reason;
					}
					$mag_del_id = update_squ_key($conn,
									"T_MAG_DELREASON");
					$retRea = FALSE;
					if ($mag_del_id != "-1") {
						if ($retRea = pub_magdelrea_insert ($conn,
							$mag_del_id, $mag_id, $w_reason)) {
							$sql3 = "delete from t_mailaddr  "
								. "WHERE  mag_id = :MAG_ID";
							$stmt3 = OCIParse($conn, $sql3);
							OCIbindbyname ($stmt3, ":MAG_ID", &$mag_id, -1);
							$retmailDel = OCIExecute($stmt3, OCI_DEFAULT);
						}
					}
				}
				if ($retDel && $retRea && $retmailDel) {
					$ret01 = OCICommit ($conn);
					include ("mag-sakujyo-ok.html");
				} else {
					$ret01 = OCIRollback ($conn);
					$print_msg = "�ǡ����κ�����Ǥ��ޤ���Ǥ�����";
					include ("err-msg.html");
				}
			} else {
				$print_msg = "���Ǥ˺���ѤߤǤ���";
				include ("err-msg.html");
			}
		} else {
			$print_msg = "�Ѵ���ͳ�����򤷤Ƥ�������";
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