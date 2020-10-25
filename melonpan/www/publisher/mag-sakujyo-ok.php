<?php
/*======================================================================================*/
/*	mag-sakujyo-ok.php								*/
/*											*/
/*======================================================================================*/
include ("inc/error_msg_list.inc");

/****************************************************************
 Main
****************************************************************/
$p_titl ="メルマガ廃刊";
if ($reason_sel == "その他") {
	if (strlen($reason) > 40) {
		$print_msg = $em_mag_3;
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
		if (inp_chk_reason($reason, $reason_sel)) {
 			$sql2 = "select * from M_MAGAZINE where mag_id = '$mag_id'";
			$result = db_exec($sql2);
			if (pg_numrows($result)) {
				$fetch = pg_fetch_object($result, 0);
				$mag_nm = $fetch->mag_nm;
				/* ----------------------------------------------------- */
				/* $sql = "delete from M_MAGAZINE where mag_id = :MAG_ID";
				/* 削除からフラグ管理へ変更 ---------------------------- */
				$sql = "update M_MAGAZINE "
						." set mag_status_flg = '99' ,mag_pub_status_flg= '99' "
						.",modefy_dt = 'now' ,mag_status_chg_dt = 'now' "
						." where mag_id = '$mag_id'";
				/* ----------------------------------------------------- */
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
					$mag_del_id = update_squ_key("T_MAG_DELREASON");
					$retRea = FALSE;
					if ($mag_del_id != "-1") {
						if ($retRea = pub_magdelrea_insert ($conn,
							$mag_del_id, $mag_id, $w_reason)) {
							$sql3 = "delete from t_mailaddr  "
								. "WHERE  mag_id = '$mag_id'";
							$retmailDel = db_exec($sql3);
						}
					}
				}
				if ($retDel && $retRea && $retmailDel) {
					db_commit_trans();
					include ("mag-sakujyo-ok.html");
				} else {
					db_rollback();
					$print_msg = $em_mag_4;
					include ("err-msg.html");
				}
			} else {
				$print_msg = $em_mag_5;
				include ("err-msg.html");
			}
		} else {
			$print_msg = $em_mag_7;
			include ("err-msg.html");
		}
	} else {
		$print_msg = $em_mag_8;
		include ("err-msg.html");
	}
} else {
	$print_msg = $em_mag_5;
	include ("err-msg.html");
}
?>