<?php
/*======================================================================================*/
/*	kaijhyo-kanryou.php								*/
/*											*/
/*======================================================================================*/
include ("inc/error_msg_list.inc");

/****************************************************************
 Main
****************************************************************/
$p_titl ="発行者解除";
$sql = "SELECT * FROM M_MAGAZINE WHERE publisher_id = $publisher_id and (mag_pub_status_flg = '01' or mag_pub_status_flg = '03') and (mag_pub_stop_flg <> '02')";
$result = db_exec($sql);
if (!pg_numrows($result)) {
	$sql2 = "select * from M_PUBLISHER where publisher_id = $publisher_id";
	$result = db_exec($sql2);
	if (pg_numrows($result)) {
		$fetch = pg_fetch_object($result, 0);
		$owner_pswd = $fetch->owner_pswd;
		if ($OWNER_PSWD_I == trim($owner_pswd)) {
			db_begin_trans();
			if (inp_chk_reason($reason, $reason_sel)) {
				$sql1 = "delete from M_PUBLISHER where publisher_id = $publisher_id";
				if ($retDel = db_exec($sql1)) {
					$w_reason = $reason_sel;
					if ($reason_sel == "その他") {
						$w_reason = $reason;
					}
					$pub_del_id = update_squ_key("T_PUB_DELREASON");
					$retRea = FALSE;
					if ($mag_del_id != "-1") {
						$retRea = pub_pubdelrea_insert ($conn, $pub_del_id, $publisher_id, $w_reason);
					}
				}
				if ($retDel && $retRea) {
					db_commit_trans();
					include ("kaijyo-kanryou.html");
				} else {
					db_rollback();
					err_msg ("PP001", "発行者情報");
				}
			} else {
				$print_msg =  $em_kaijyo_3;
				include ("err-msg.html");
			}
		} else {
			$print_msg =  $em_kaijyo_4;
			include ("err-msg.html");
		}
	} else {
		$print_msg =  $em_kaijyo_5;
		include ("err-msg.html");
	}
} else {
	$fetch = pg_fetch_object($result, 0);
	$mag_id = $fetch->mag_id;
	$mail_add_publish = $fetch->mail_add_publish;
	$mag_nm = $fetch->mag_nm;
	$mag_intro_long = $fetch->mag_intro_long;
	$mag_intro_short = $fetch->mag_intro_short;
	$mag_hp = $fetch->mag_hp;
	$mag_type = $fetch->mag_type;
	$mag_type = $fetch->mag_type;
	$mag_cycle_day = $fetch->mag_cycle_day;
	$mag_catg_1 = $fetch->mag_catg_1;
	$mag_catg_2 = $fetch->mag_catg_2;
	$mag_bg_1 = $fetch->mag_bg_1;
	$mag_bg_2 = $fetch->mag_bg_2;
	$mag_reader_sex = $fetch->mag_reader_sex;
	$mag_reader_age = $fetch->mag_reader_age;
	$mag_reader_area = $fetch->mag_reader_area;
	$mag_reader_occup = $fetch->mag_reader_occup;
	$mag_bn_pub = $fetch->mag_bn_pub;
	$ml_type = $fetch->ml_type;
	$mag_status_flg = $fetch->mag_status_flg;
	$mag_pub_status_flg = $fetch->mag_pub_status_flg;
	$mag_pub_stop_flg = $fetch->mag_pub_stop_flg;
	$print_msg =  $em_kaijyo_6;
	include ("err-msg.html");
}
?>
