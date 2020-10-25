<?php
function pub_mm_insert (
		$conn,
		$mag_id ,
                $publisher_id ,
                $mail_add_publish,
                $mag_nm,
                $mag_intro_long,
                $mag_intro_short,
                $mag_hp,
                $mag_type,
                $mag_cycle,
                $mag_cycle_day,
                $mag_catg_1,
                $mag_catg_2,
                $mag_bg_1,
                $mag_bg_2,
                $mag_reader_sex,
                $mag_reader_age,
                $mag_reader_area,
                $mag_reader_occup,
                $mag_bn_pub,
                $ml_type,
                $company_nm,
                $add_point) {

	$l_ret = FALSE;
	$sql = mm_make_sql ("SELECT_M_MAGAZINE_MAG");
	$stmt = OCIParse($conn, $sql);
	$ret01 = OCIbindbyname ($stmt, ":MAG_ID", &$mag_id, -1);
	if (OCIExecute($stmt, OCI_DEFAULT)) {
		$nrec = OCIFetchstatement ($stmt, &$results);
		if ( $nrec >  0 ) {
			if (strlen($mag_cycle_day) > 0) {
				$sql = mm_make_sql ("UPDATE_M_MAGAZINE");
			} else {
				$sql = mm_make_sql ("UPDATE_M_MAGAZINE_NOT_CYCLEDAY");
			}
			$stmt = OCIParse($conn, $sql);
		} else {
			if (strlen($mag_cycle_day) > 0) {
				$sql = mm_make_sql ("INSERT_M_MAGAZINE");
			} else {
				$sql = mm_make_sql ("INSERT_M_MAGAZINE_NOT_CYCLEDAY");
			}
			$stmt = OCIParse($conn, $sql);

			$mag_status_flg = "00";
			$mag_pub_status_flg = "01";
			$mag_pub_stop_flg = "00";
			$ml_type = "0";
			$last_send_dt = "2000/01/01";
			OCIbindbyname ($stmt, ":PUBLISHER_ID", &$publisher_id, -1);
			OCIbindbyname ($stmt, ":MAG_STATUS_FLG", &$mag_status_flg, -1);
			OCIbindbyname ($stmt, ":MAG_PUB_STATUS_FLG", &$mag_pub_status_flg, -1);
			OCIbindbyname ($stmt, ":MAG_PUB_STOP_FLG", &$mag_pub_stop_flg, -1);
			OCIbindbyname ($stmt, ":ML_TYPE", &$ml_type, -1);
			OCIbindbyname ($stmt, ":LAST_SEND_DT", &$last_send_dt, -1);
		}

		$mag_nm = trim($mag_nm);
		$mag_intro_long = trim($mag_intro_long);
		$mag_intro_short = trim($mag_intro_short);
		$mag_hp = trim($mag_hp);
		$mag_cycle_day = trim($mag_cycle_day);

		OCIbindbyname ($stmt, ":MAG_ID", &$mag_id, -1);
		OCIbindbyname ($stmt, ":MAIL_ADD_PUBLISH", &$mail_add_publish, -1);
		OCIbindbyname ($stmt, ":MAG_NM", &$mag_nm, -1);
		OCIbindbyname ($stmt, ":MAG_INTRO_LONG", &$mag_intro_long, -1);
		OCIbindbyname ($stmt, ":MAG_INTRO_SHORT", &$mag_intro_short, -1);
		OCIbindbyname ($stmt, ":MAG_HP", &$mag_hp, -1);
		OCIbindbyname ($stmt, ":MAG_TYPE", &$mag_type, -1);
		OCIbindbyname ($stmt, ":MAG_CYCLE", &$mag_cycle, -1);
		if (strlen($mag_cycle_day) > 0) {
			OCIbindbyname ($stmt, ":MAG_CYCLE_DAY", &$mag_cycle_day, -1);
		}
		OCIbindbyname ($stmt, ":MAG_CATG_1", &$mag_catg_1, -1);
		OCIbindbyname ($stmt, ":MAG_CATG_2", &$mag_catg_2, -1);
		OCIbindbyname ($stmt, ":MAG_BG_1", &$mag_bg_1, -1);
		OCIbindbyname ($stmt, ":MAG_BG_2", &$mag_bg_2, -1);
		OCIbindbyname ($stmt, ":MAG_READER_SEX", &$mag_reader_sex, -1);
		OCIbindbyname ($stmt, ":MAG_READER_AGE", &$mag_reader_age, -1);
		OCIbindbyname ($stmt, ":MAG_READER_AREA", &$mag_reader_area, -1);
		OCIbindbyname ($stmt, ":MAG_READER_OCCUP", &$mag_reader_occup, -1);
		OCIbindbyname ($stmt, ":MAG_BN_PUB", &$mag_bn_pub, -1);
		OCIbindbyname ($stmt, ":COMPANY_NM", &$company_nm, -1);
		OCIbindbyname ($stmt, ":ADD_POINT", &$add_point, -1);
		$l_ret = OCIExecute($stmt, OCI_DEFAULT);
	}
	return $l_ret;
}

function pub_mm_load ($conn, $mag_id) {
	global	$g_publisher_id ,
                $g_mail_add_publish,
                $g_mag_nm,
                $g_mag_intro_long,
                $g_mag_intro_short,
                $g_mag_hp,
                $g_mag_type,
                $g_mag_cycle,
                $g_mag_cycle_day,
                $g_mag_catg_1,
                $g_mag_catg_2,
                $g_mag_bg_1,
                $g_mag_bg_2,
                $g_mag_reader_sex,
                $g_mag_reader_age,
                $g_mag_reader_area,
                $g_mag_reader_occup,
                $g_mag_bn_pub,
                $g_ml_type,
                $g_mag_status_flg,
                $g_mag_pub_status_flg,
                $g_mag_pub_stop_flg,
                $g_company_nm,
                $g_add_point;

	$sql = mm_make_sql ("SELECT_M_MAGAZINE");
	$stmt = OCIParse($conn, $sql);
	OCIbindbyname ($stmt, ":MAG_ID", &$mag_id, -1);
	OCIDefinebyname($stmt, "PUBLISHER_ID", &$g_publisher_id);
	OCIDefinebyname($stmt, "MAIL_ADD_PUBLISH", &$g_mail_add_publish);
	OCIDefinebyname($stmt, "MAG_NM", &$g_mag_nm);
	OCIDefinebyname($stmt, "MAG_INTRO_LONG", &$g_mag_intro_long);
	OCIDefinebyname($stmt, "MAG_INTRO_SHORT", &$g_mag_intro_short);
	OCIDefinebyname($stmt, "MAG_HP", &$g_mag_hp);
	OCIDefinebyname($stmt, "MAG_TYPE", &$g_mag_type);
	OCIDefinebyname($stmt, "MAG_CYCLE", &$g_mag_cycle);
	OCIDefinebyname($stmt, "MAG_CYCLE_DAY", &$g_mag_cycle_day);
	OCIDefinebyname($stmt, "MAG_CATG_1", &$g_mag_catg_1);
	OCIDefinebyname($stmt, "MAG_CATG_2", &$g_mag_catg_2);
	OCIDefinebyname($stmt, "MAG_BG_1", &$g_mag_bg_1);
	OCIDefinebyname($stmt, "MAG_BG_2", &$g_mag_bg_2);
	OCIDefinebyname($stmt, "MAG_READER_SEX", &$g_mag_reader_sex);
	OCIDefinebyname($stmt, "MAG_READER_AGE", &$g_mag_reader_age);
	OCIDefinebyname($stmt, "MAG_READER_AREA", &$g_mag_reader_area);
	OCIDefinebyname($stmt, "MAG_READER_OCCUP", &$g_mag_reader_occup);
	OCIDefinebyname($stmt, "MAG_BN_PUB", &$g_mag_bn_pub);
	OCIDefinebyname($stmt, "ML_TYPE", &$g_ml_type);
	OCIDefinebyname($stmt, "MAG_STATUS_FLG", &$g_mag_status_flg);
	OCIDefinebyname($stmt, "MAG_PUB_STATUS_FLG", &$g_mag_pub_status_flg);
	OCIDefinebyname($stmt, "MAG_PUB_STOP_FLG", &$g_mag_pub_stop_flg);
	OCIDefinebyname($stmt, "COMPANY_NM", &$g_company_nm);
	OCIDefinebyname($stmt, "ADD_POINT", &$g_add_point);
	$ncols = OCIExecute($stmt, OCI_DEFAULT);
	OCIFetch($stmt);
	$g_mail_add_publish = trim($g_mail_add_publish);
	$g_mag_nm = trim($g_mag_nm);
	$g_mag_intro_long = trim($g_mag_intro_long);
	$g_mag_intro_short = trim($g_mag_intro_short);
	$g_mag_hp = trim($g_mag_hp);
	$g_mag_type = trim($g_mag_type);
	$g_mag_cycle = trim($g_mag_cycle);
	$g_mag_cycle_day = trim($g_mag_cycle_day);
	$g_mag_catg_1 = trim($g_mag_catg_1);
	$g_mag_catg_2 = trim($g_mag_catg_2);
	$g_mag_bg_1 = trim($g_mag_bg_1);
	$g_mag_bg_2 = trim($g_mag_bg_2);
	$g_mag_reader_sex = trim($g_mag_reader_sex);
	$g_mag_reader_age = trim($g_mag_reader_age);
	$g_mag_reader_area = trim($g_mag_reader_area);
	$g_mag_reader_occup = trim($g_mag_reader_occup);
	$g_mag_bn_pub = trim($g_mag_bn_pub);
	$g_ml_type = trim($g_ml_type);
	$g_company_nm = trim($g_company_nm);
	$g_add_point = trim($g_add_point);
}

function pub_mm_make_bodyid ($conn, $mag_id) {
	$sql = mm_make_sql ("SELECT_T_BODY_BID_MAX");
	$stmt = OCIParse($conn, $sql);
	$ret01 = OCIDefinebyname($stmt, "BODY_ID", &$body_id);
	$ret01 = OCIbindbyname ($stmt, ":MAG_ID", &$mag_id, -1);
	$ncols = OCIExecute($stmt, OCI_DEFAULT);
	$ret01 = OCIFetch ($stmt);
	if ($body_id > 0) {
		$body_id++;
		if ($body_id > 9999) {
			$body_id = 1;
		}
	} else {
		$body_id = 1;
	}
	$ret = $body_id;
	return $ret;
}

function pub_tb_insert ($conn, $index_id, $mag_id, $body_id, $mag_titl, $mag_body) {
	if ( pub_tb_chkrec ($conn, $index_id) ) {
		$sql = mm_make_sql ("UPDATE_T_BODY");
		$stmt = OCIParse($conn, $sql);
	} else{
		update_squ_key ($conn, "T_BODY", $index_id);

		$sql = mm_make_sql ("INSERT_T_BODY");
		$stmt = OCIParse($conn, $sql);
	}
	//$mag_titl = trim($mag_titl);
	//$mag_body = trim($mag_body);
	$mag_body = stripslashes($mag_body);

	OCIbindbyname ($stmt, ":INDEX_ID", &$index_id, -1);
	OCIbindbyname ($stmt, ":MAG_ID", &$mag_id, -1);
	OCIbindbyname ($stmt, ":BODY_ID", &$body_id, -1);
	OCIbindbyname ($stmt, ":MAG_TITL", &$mag_titl, -1);
	OCIbindbyname ($stmt, ":MAG_BODY", &$mag_body, -1);
	return OCIExecute($stmt, OCI_DEFAULT);
}

function pub_tb_load ($conn, $index_id) {
	global $g_mag_id, $g_body_id, $g_mag_titl, $g_mag_body;

	if ( pub_tb_chkrec ($conn, $index_id) ) {
		$sql = mm_make_sql ("SELECT_T_BODY");
		$stmt = OCIParse($conn, $sql);

		OCIbindbyname ($stmt, ":INDEX_ID", &$index_id, -1);
		OCIDefinebyname($stmt, "MAG_ID", &$g_mag_id);
		OCIDefinebyname($stmt, "BODY_ID", &$g_body_id);
		OCIDefinebyname($stmt, "MAG_TITL", &$g_mag_titl);
		OCIDefinebyname($stmt, "MAG_BODY", &$g_mag_body);
		$ncols = OCIExecute($stmt, OCI_DEFAULT);
		OCIFetch($stmt);
	} else{
		//レコード無し
	}
}

function pub_tb_delete ($conn, $index_id) {
	$l_ret = FALSE;
	if ( pub_tb_chkrec ($conn, $index_id) ) {
		$sql = mm_make_sql ("DELETE_T_BODY");
		$stmt = OCIParse($conn, $sql);
		OCIbindbyname ($stmt, ":INDEX_ID", &$index_id, -1);
		$l_ret = OCIExecute($stmt, OCI_DEFAULT);
	}
	return $l_ret;
}

function pub_tr_insert ($conn, $index_id, $mag_id, $body_id, $resrv_month, $resrv_day,
			$resrv_hour, $resrv_flg) {
	if ( pub_tr_chkrec ($conn, $index_id) ) {
		$sql = mm_make_sql ("UPDATE_T_PUB_RESERVE");
		$stmt = OCIParse($conn, $sql);
	} else{
		$sql = mm_make_sql ("INSERT_T_PUB_RESERVE");
		$stmt = OCIParse($conn, $sql);
	}
	OCIbindbyname ($stmt, ":INDEX_ID", &$index_id, -1);
	OCIbindbyname ($stmt, ":MAG_ID", &$mag_id, -1);
	OCIbindbyname ($stmt, ":BODY_ID", &$body_id, -1);
	OCIbindbyname ($stmt, ":RESRV_MONTH", &$resrv_month, -1);
	OCIbindbyname ($stmt, ":RESRV_DAY", &$resrv_day, -1);
	OCIbindbyname ($stmt, ":RESRV_HOUR", &$resrv_hour, -1);
	OCIbindbyname ($stmt, ":RESRV_FLG", &$resrv_flg, -1);
	return OCIExecute($stmt, OCI_DEFAULT);
}

function pub_tr_load ($conn, $index_id) {
	global $g_mag_id, $g_body_id, $g_resrv_month, $g_resrv_day, $g_resrv_hour, $g_resrv_flg,
		$g_resrv_year, $g_resrv_hour_ampm, $g_resrv_hour_h, $g_resrv_hour_m;

	if ( pub_tr_chkrec ($conn, $index_id) ) {
		$sql = mm_make_sql ("SELECT_T_RESERVE_LOAD");
		$stmt = OCIParse($conn, $sql);
		OCIbindbyname ($stmt, ":INDEX_ID", &$index_id, -1);
		OCIDefinebyname($stmt, "MAG_ID", &$g_mag_id);
		OCIDefinebyname($stmt, "BODY_ID", &$g_body_id);
		OCIDefinebyname($stmt, "RESRV_MONTH", &$g_resrv_month);
		OCIDefinebyname($stmt, "RESRV_DAY", &$g_resrv_day);
		OCIDefinebyname($stmt, "RESRV_HOUR_A", &$w_resrv_hour_a);
		OCIDefinebyname($stmt, "RESRV_HOUR_H", &$g_resrv_hour_h);
		OCIDefinebyname($stmt, "RESRV_HOUR_M", &$g_resrv_hour_m);
		OCIDefinebyname($stmt, "RESRV_FLG", &$g_resrv_flg);
		$ncols = OCIExecute($stmt, OCI_DEFAULT);
		OCIFetch($stmt);

		$g_resrv_hour_h = $w_resrv_hour_a;
		$g_resrv_hour_ampm = "AM";
		if ($w_resrv_hour_a > 12) {
			$g_resrv_hour_ampm = "PM";
		}
	} else{
		//レコード無し
	}
}

function pub_tr_delete ($conn, $index_id) {
	$l_ret = FALSE;
	if ( pub_tr_chkrec ($conn, $index_id) ) {
		$sql = mm_make_sql ("DELETE_T_RESERVE");
		$stmt = OCIParse($conn, $sql);
		OCIbindbyname ($stmt, ":INDEX_ID", &$index_id, -1);
		$l_ret = OCIExecute($stmt, OCI_DEFAULT);
	}
	return $l_ret;
}

function pub_tb_chkrec ($conn, $index_id) {
	$sql = mm_make_sql ("SELECT_T_BODY");
	$stmt = OCIParse($conn, $sql);
	$ret01 = OCIbindbyname ($stmt, ":INDEX_ID", &$index_id, -1);
	$ncols = OCIExecute($stmt, OCI_DEFAULT);
	$nrec = OCIFetch ($stmt);
	$ret01 = OCIFreeStatement($stmt);
	return $nrec;
}

function pub_tr_chkrec ($conn, $index_id) {
	$sql = mm_make_sql ("SELECT_T_RESERVE_INDEX");
	$stmt = OCIParse($conn, $sql);
	$ret01 = OCIbindbyname ($stmt, ":INDEX_ID", &$index_id, -1);
	$ncols = OCIExecute($stmt, OCI_DEFAULT);
	$nrec = OCIFetch ($stmt);
	$ret01 = OCIFreeStatement($stmt);
	return $nrec;
}

function pub_magdelrea_insert ($conn, $mag_del_id, $mag_id, $reason) {
	if ( pub_tmds_chkrec ($conn, $mag_del_id) ) {
		$sql = mm_make_sql ("UPDATE_T_MAG_DELREASON");
		$stmt = OCIParse($conn, $sql);
	} else{
		$sql = mm_make_sql ("INSERT_T_MAG_DELREASON");
		$stmt = OCIParse($conn, $sql);
	}
	OCIbindbyname ($stmt, ":MAG_DEL_ID", &$mag_del_id, -1);
	OCIbindbyname ($stmt, ":MAG_ID", &$mag_id, -1);
	OCIbindbyname ($stmt, ":REASON", &$reason, -1);
	$retStat = OCIExecute($stmt, OCI_DEFAULT);
	$ret01 = OCIFreeStatement($stmt);
	return $retStat;
}

function pub_tmds_chkrec ($conn, $mag_del_id) {
	$sql = mm_make_sql ("SELECT_T_MAG_DELREASON_ID");
	$stmt = OCIParse($conn, $sql);
	$ret01 = OCIbindbyname ($stmt, ":MAG_DEL_ID", &$mag_del_id, -1);
	$ncols = OCIExecute($stmt, OCI_DEFAULT);
	$retStat = OCIFetch ($stmt);
	$ret01 = OCIFreeStatement($stmt);
	return $retStat;
}

function pub_magstoprea_insert ($conn, $mag_stop_id, $mag_id, $reason) {
	if ( pub_tmss_chkrec ($conn, $mag_stop_id) ) {
		$sql = mm_make_sql ("UPDATE_T_MAG_STOPREASON");
		$stmt = OCIParse($conn, $sql);
	} else{
		$sql = mm_make_sql ("INSERT_T_MAG_STOPREASON");
		$stmt = OCIParse($conn, $sql);
	}
	OCIbindbyname ($stmt, ":MAG_STOP_ID", &$mag_stop_id, -1);
	OCIbindbyname ($stmt, ":MAG_ID", &$mag_id, -1);
	OCIbindbyname ($stmt, ":REASON", &$reason, -1);
	$retStat = OCIExecute($stmt, OCI_DEFAULT);
	$ret01 = OCIFreeStatement($stmt);
	return $retStat;
}

function pub_tmss_chkrec ($conn, $mag_stop_id) {
	$sql = mm_make_sql ("SELECT_T_MAG_STOPREASON_ID");
	$stmt = OCIParse($conn, $sql);
	$ret01 = OCIbindbyname ($stmt, ":MAG_STOP_ID", &$mag_stop_id, -1);
	$ncols = OCIExecute($stmt, OCI_DEFAULT);
	$retStat = OCIFetch ($stmt);
	$ret01 = OCIFreeStatement($stmt);
	return $retStat;
}

?>
