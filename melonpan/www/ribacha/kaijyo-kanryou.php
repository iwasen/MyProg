<?php
/*======================================================================================*/
/*	kaijhyo-kanryou.php								*/
/*											*/
/*======================================================================================*/
$p_titl ="発行者解除";
$conn = db_conxxxt();
$sql = mm_make_sql ("SELECT_M_MAGAZINE_PUB");
$stmt = OCIParse($conn, $sql);
$ret01 = OCIbindbyname ($stmt, ":PUBLISHER_ID", &$publisher_id, -1);
include("$inc_path/pub_mm_defmane.def");
$ncols = OCIExecute($stmt, OCI_DEFAULT);
if (!OCIFetch($stmt)) {
	$sql2 = "select * from M_PUBLISHER where publisher_id = :PUBLISHER_ID";
	$stmt2 = OCIParse($conn, $sql2);
	$ret02 = OCIbindbyname ($stmt2, ":PUBLISHER_ID", &$publisher_id, -1);
	OCIDefinebyname($stmt2, "OWNER_PSWD", &$owner_pswd);
	$ncols = OCIExecute($stmt2, OCI_DEFAULT);
	if (OCIFetch($stmt2)) {
		if ($OWNER_PSWD_I == trim($owner_pswd)) {
			if (inp_chk_reason($reason, $reason_sel)) {
				$sql1 = "delete from M_PUBLISHER where publisher_id =
									:PUBLISHER_ID";
				$stmt1 = OCIParse($conn, $sql1);
				OCIbindbyname ($stmt1, ":PUBLISHER_ID", &$publisher_id, -1);
				if ( $retDel = OCIExecute($stmt1, OCI_DEFAULT) ) {
					$w_reason = $reason_sel;
					if ($reason_sel == "その他") {
						$w_reason = $reason;
					}
					$pub_del_id = update_squ_key($conn,
									"T_PUB_DELREASON");
					$retRea = FALSE;
					if ($mag_del_id != "-1") {
						$retRea = pub_pubdelrea_insert ($conn,
							$pub_del_id, $publisher_id,
										$w_reason);
					}
				}
				if ($retDel && $retRea) {
					OCICommit ($conn);
					include ("kaijyo-kanryou.html");
				} else {
					OCIRollback($conn);
					err_msg ("PP001", "発行者情報");
				}
			} else {
				$print_msg =  "解除理由を選択してください。";
				include ("err-msg.html");
			}
		} else {
			$print_msg =  "指定されたパスワードが違います。";
			include ("err-msg.html");
		}
	} else {
		$print_msg =  "すでに削除済みです。";
		include ("err-msg.html");
	}
} else {
		$print_msg =  "指定されたデータには、メルマガが登録されております。";
		include ("err-msg.html");
}
db_logoff();

?>
