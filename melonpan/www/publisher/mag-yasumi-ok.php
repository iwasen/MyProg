<?php

/*======================================================================================*/
/*	mag-yasumi-ok.php								*/
/*											*/
/*======================================================================================*/
include ("inc/error_msg_list.inc");

/****************************************************************
 Main
****************************************************************/
$p_titl ="メルマガ休刊";
if ($reason_sel == "") {
		$print_msg = $em_yasumi_4;
		include ("err-msg.html");
		exit();
}
if ($reason_sel == "その他") {
	if (strlen($reason) > 40) {
		$print_msg = $em_yasumi_3;
		include ("err-msg.html");
		exit();
	}else if (strlen($reason) == 0) {
		$print_msg = $em_yasumi_4;
		include ("err-msg.html");
		exit();
	}
}

pub_passchk($conn, $publisher_id,$owner_pswd);

$sql1 = "SELECT * FROM M_PUBLISHER WHERE publisher_id = $publisher_id";
$result = db_exec($sql1);
if (pg_numrows($result)) {
	$fetch = pg_fetch_object($result, 0);
	$mail_add_main = $fetch->mail_add_main;
	$owner_pswd = $fetch->owner_pswd;
	if ($OWNER_PSWD_I ==  trim($owner_pswd)) {
		$sql2 = "select * from M_MAGAZINE where mag_id = '$mag_id'";
		$result = db_exec($sql2);
		if (pg_numrows($result)) {
			$fetch = pg_fetch_object($result, 0);
			$mag_id = $fetch->mag_id;
			$mag_nm = $fetch->mag_nm;
			$sql = "update M_MAGAZINE set MAG_STATUS_FLG = '01', "
				. "modefy_dt = 'now', mag_status_chg_dt = 'now' "
				. "where mag_id = '$mag_id'";

			db_begin_trans();
			if ($retDel = db_exec($sql)) {
				$w_reason = $reason_sel;
				if ($reason_sel == "その他") {
					if ($reason == "") { $reason = "その他";}
					$w_reason = $reason;
				} elseif ($reason_sel == "") {
					if ($reason == "") { $reason = "その他";}
					$w_reason = $reason;
				}
				$mag_stop_id = update_squ_key("T_MAG_STOPREASON");
				$retRea = FALSE;
				if ($mag_stop_id != "-1") {
					$retRea = pub_magstoprea_insert ($conn,	$mag_stop_id, $mag_id, $w_reason);
				}
			}
			if ($retDel && $retRea) {
				db_commit_trans();
				include ("mag-yasumi-ok.html");
			} else {
				db_rollback();
				$print_msg = $em_yasumi_5;
				include ("err-msg.html");
			}
		} else {
			$print_msg = $em_yasumi_4;
			include ("err-msg.html");
		}
	} else {
		$print_msg = $em_yasumi_7;
		include ("err-msg.html");
	}
} else {
	$print_msg = $em_yasumi_8;
	include ("err-msg.html");
}
?>