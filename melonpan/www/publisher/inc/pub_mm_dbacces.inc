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
			$melmel_id,
			$mag_present_ins_flg,
			$mag_original_flg,
			$mag_from_addr,
			$mag_subject_tmpl,
			$mag_header_tmpl,
			$mag_footer_tmpl,
			$send_notice_flg ) {
	$l_ret = FALSE;

	$sql = "SELECT mag_id FROM M_MAGAZINE WHERE mag_id = '$mag_id'";
	if ($result = db_exec($sql)) {

		$nrec = pg_numrows($result);
		if (strlen($melmel_id) == 0) {
			$melmel_id = "00000";
		}

		$mag_nm = trim($mag_nm);
		$mag_intro_long = trim($mag_intro_long);
		$mag_intro_short = trim($mag_intro_short);
		$mag_hp = trim($mag_hp);
		$mag_cycle_day = trim($mag_cycle_day);

		if ( $nrec >  0 ) {
			/* -------------- */
			/* レコード書換え */
			/* -------------- */
			if (strlen($mag_cycle_day) > 0) {
//				$sql = mm_make_sql ("UPDATE_M_MAGAZINE");
				$sql = "UPDATE M_MAGAZINE SET "
					. "mail_add_publish = '$mail_add_publish', "
					. "mag_nm = '$mag_nm', "
					. "mag_intro_long = '$mag_intro_long', "
	                . "mag_intro_short = '$mag_intro_short', "
	                . "mag_hp = '$mag_hp', "
	                . "mag_type = '$mag_type', "
	                . "mag_cycle = '$mag_cycle', "
	                . "mag_cycle_day = '$mag_cycle_day', "
	                . "mag_catg_1 = '$mag_catg_1', "
	                . "mag_catg_2 = '$mag_catg_2', "
	                . "mag_bg_1 = '$mag_bg_1', "
	                . "mag_bg_2 = '$mag_bg_2', "
	                . "mag_reader_sex = '$mag_reader_sex', "
	                . "mag_reader_age = '$mag_reader_age', "
	                . "mag_reader_area = '$mag_reader_area', "
					. "mag_reader_occup = '$mag_reader_occup', "
					. "mag_bn_pub = '$mag_bn_pub', "
					. "modefy_dt = 'now', "
					. "melmel_id = '$melmel_id', "
					. "mag_present_ins_flg = '$mag_present_ins_flg', "
					. "mag_original_flg = '$mag_original_flg', "
					. "mag_from_addr = '$mag_from_addr', "
					. "mag_subject_tmpl = '$mag_subject_tmpl', "
					. "mag_header_tmpl = '$mag_header_tmpl', "
		 			. "mag_footer_tmpl = '$mag_footer_tmpl', "
		 			. "send_notice_flg = '$send_notice_flg' "
					. "WHERE mag_id = '$mag_id' ";
			} else {
				return FALSE;
				$sql = mm_make_sql ("UPDATE_M_MAGAZINE_NOT_CYCLEDAY");
			}
			
		} else{
			/* -------------- */
			/* レコード挿入   */
			/* -------------- */
			if (strlen($mag_cycle_day) > 0) {
//				$sql = mm_make_sql ("INSERT_M_MAGAZINE");
				$mag_status_flg = "00";
				$mag_pub_status_flg = "00";
				$mag_pub_stop_flg = "00";
				$ml_type = "0";
				$last_send_dt = "2000/01/01";
				$sql = "INSERT INTO M_MAGAZINE ( mag_id , publisher_id , mail_add_publish, mag_nm, mag_intro_long,"
					. " mag_intro_short, mag_hp, mag_type, mag_cycle, mag_cycle_day, mag_catg_1, "
					. " mag_catg_2, mag_bg_1, mag_bg_2, mag_reader_sex, mag_reader_age, "
					. " mag_reader_area, "
					. " mag_reader_occup, mag_bn_pub, ml_type, mag_status_flg, mag_pub_status_flg, "
					. " mag_pub_stop_flg, mag_pub_status_dt, modefy_dt, melmel_id, mag_present_ins_flg, "
					. " mag_original_flg,"
					. " mag_from_addr, mag_subject_tmpl, mag_header_tmpl, mag_footer_tmpl, "
					. " send_notice_flg, last_send_dt )"
					. " VALUES ( '$mag_id' , $publisher_id , '$mail_add_publish', '$mag_nm', '$mag_intro_long',"
					. " '$mag_intro_short', '$mag_hp', '$mag_type', '$mag_cycle', '$mag_cycle_day', '$mag_catg_1', "
					. " '$mag_catg_2', '$mag_bg_1', '$mag_bg_2', '$mag_reader_sex', '$mag_reader_age', "
					. " '$mag_reader_area',"
					. " '$mag_reader_occup', '$mag_bn_pub', '$ml_type', '$mag_status_flg', '$mag_pub_status_flg',"
					. " '$mag_pub_stop_flg', 'now', 'now', '$melmel_id', '$mag_present_ins_flg', "
					. " '$mag_original_flg',"
					. " '$mag_from_addr', '$mag_subject_tmpl', '$mag_header_tmpl', '$mag_footer_tmpl', "
					. " '$send_notice_flg', '$last_send_dt' )";
			} else {
				return FALSE;
				$sql = mm_make_sql ("INSERT_M_MAGAZINE_NOT_CYCLEDAY");
			}
		}
		$l_ret = db_exec($sql);
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
                $g_melmel_id,
                $g_mag_present_ins,
				$g_mag_original_flg,
				$g_mag_from_addr,
				$g_mag_subject_tmpl,
				$g_mag_header_tmpl,
				$g_mag_footer_tmpl,
				$g_send_notice_flg;

//	$sql = mm_make_sql ("SELECT_M_MAGAZINE");
	$sql = "SELECT * FROM M_MAGAZINE WHERE mag_id = '$mag_id'";
	$result = db_exec($sql);
/* **
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
	OCIDefinebyname($stmt, "MELMEL_ID", &$g_melmel_id);
	OCIDefinebyname($stmt, "MAG_PRESENT_INS_FLG", &$g_mag_present_ins);
	OCIDefinebyname($stmt, "MAG_ORIGINAL_FLG", &$g_mag_original_flg, -1);
	OCIDefinebyname($stmt, "MAG_FROM_ADDR", &$g_mag_from_addr, -1);
	OCIDefinebyname($stmt, "MAG_SUBJECT_TMPL", &$g_mag_subject_tmpl, -1);
	OCIDefinebyname($stmt, "MAG_HEADER_TMPL", &$g_mag_header_tmpl, -1);
	OCIDefinebyname($stmt, "MAG_FOOTER_TMPL", &$g_mag_footer_tmpl, -1);
	OCIDefinebyname($stmt, "SEND_NOTICE_FLG", &$g_send_notice_flg, -1);
	
	$ncols = OCIExecute($stmt, OCI_DEFAULT);
	OCIFetch($stmt);
*/
	$fetch = pg_fetch_object($result, 0);
	$g_publisher_id = $fetch->publisher_id;
	$g_mail_add_publish = $fetch->mail_add_publish;
	$g_mag_nm = $fetch->mag_nm;
	$g_mag_intro_long = $fetch->mag_intro_long;
	$g_mag_intro_short = $fetch->mag_intro_short;
	$g_mag_hp = $fetch->mag_hp;
	$g_mag_type = $fetch->mag_type;
	$g_mag_cycle = $fetch->mag_cycle;
	$g_mag_cycle_day = $fetch->mag_cycle_day;
	$g_mag_catg_1 = $fetch->mag_catg_1;
	$g_mag_catg_2 = $fetch->mag_catg_2;
	$g_mag_bg_1 = $fetch->mag_bg_1;
	$g_mag_bg_2 = $fetch->mag_bg_2;
	$g_mag_reader_sex = $fetch->mag_reader_sex;
	$g_mag_reader_age = $fetch->mag_reader_age;
	$g_mag_reader_area = $fetch->mag_reader_area;
	$g_mag_reader_occup = $fetch->mag_reader_occup;
	$g_mag_bn_pub = $fetch->mag_bn_pub;
	$g_ml_type = $fetch->ml_type;
	$g_mag_status_flg = $fetch->mag_status_flg;
	$g_mag_pub_status_flg = $fetch->mag_pub_status_flg;
	$g_mag_pub_stop_flg = $fetch->mag_pub_stop_flg;
	$g_mag_pub_stop_flg = $fetch->mag_pub_stop_flg;
	$g_melmel_id = $fetch->melmel_id;
	$g_mag_present_ins = $fetch->mag_present_ins;
	$g_mag_original_flg = $fetch->mag_original_flg;
	$g_mag_from_addr = $fetch->mag_from_addr;
	$g_mag_subject_tmpl = $fetch->mag_subject_tmpl;
	$g_mag_header_tmpl = $fetch->mag_header_tmpl;
	$g_mag_footer_tmpl = $fetch->mag_footer_tmpl;
	$g_send_notice_flg = $fetch->send_notice_flg;

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
	$g_melmel_id = trim($g_melmel_id);
	$g_mag_present_ins = trim($g_mag_present_ins);
	$g_mag_original_flg = trim($g_mag_original_flg);
	$g_mag_from_addr = trim($g_mag_from_addr);
	$g_mag_subject_tmpl = trim($g_mag_subject_tmpl);
	$g_mag_header_tmpl = trim($g_mag_header_tmpl);
	$g_mag_footer_tmpl = trim($g_mag_footer_tmpl);
	$g_send_notice_flg = trim($g_send_notice_flg);
}

function pub_mm_make_bodyid ($conn, $mag_id) {
//	$sql = mm_make_sql ("SELECT_T_BODY_BID_MAX");
	$sql = "SELECT body_id FROM T_BODY WHERE mag_id = '$mag_id' ORDER BY BODY_ID DESC";
	$result = db_exec($sql);
	if (pg_numrows($result)) {
		$fetch = pg_fetch_object($result, 0);
		$body_id = $fetch->body_id;
	}
/* **
	$stmt = OCIParse($conn, $sql);
	$ret01 = OCIDefinebyname($stmt, "BODY_ID", &$body_id);
	$ret01 = OCIbindbyname ($stmt, ":MAG_ID", &$mag_id, -1);
	$ncols = OCIExecute($stmt, OCI_DEFAULT);
	$ret01 = OCIFetch ($stmt);
*/
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
//		$sql = mm_make_sql ("UPDATE_T_BODY");
		$sql = "UPDATE T_BODY SET mag_id = '$mag_id', body_id = $body_id, mag_titl = '$mag_titl' , mag_body = '$mag_body', modefy_dt = 'now' WHERE index_id = $index_id";
//		$stmt = OCIParse($conn, $sql);
	} else{
		update_squ_key ("T_BODY", $index_id);

//		$sql = mm_make_sql ("INSERT_T_BODY");
		$sql = "INSERT INTO T_BODY ( index_id , mag_id , body_id, mag_titl , mag_body , modefy_dt ) VALUES ( $index_id, '$mag_id', $body_id, '$mag_titl', '$mag_body', 'now' )";
//		$stmt = OCIParse($conn, $sql);
	}
	//$mag_titl = trim($mag_titl);
	//$mag_body = trim($mag_body);
	$mag_body = stripslashes($mag_body);
	$mag_titl = stripslashes($mag_titl);	// 2002.02.19
/*
	OCIbindbyname ($stmt, ":INDEX_ID", &$index_id, -1);
	OCIbindbyname ($stmt, ":MAG_ID", &$mag_id, -1);
	OCIbindbyname ($stmt, ":BODY_ID", &$body_id, -1);
	OCIbindbyname ($stmt, ":MAG_TITL", &$mag_titl, -1);
	OCIbindbyname ($stmt, ":MAG_BODY", &$mag_body, -1);
	return OCIExecute($stmt, OCI_DEFAULT);
*/
	return db_exec($sql);
}
function pub_tb_load ($conn, $index_id) {
	global $g_mag_id, $g_body_id, $g_mag_titl, $g_mag_body;

	if ( pub_tb_chkrec ($conn, $index_id) ) {
//		$sql = mm_make_sql ("SELECT_T_BODY");
		$sql = "SELECT * FROM T_BODY WHERE index_id = $index_id";
		$result = db_exec($sql);
		if (pg_numrows($result)) {
			$fetch = pg_fetch_object($result, 0);
			$g_mag_id = $fetch->mag_id;
			$g_body_id = $fetch->body_id;
			$g_mag_titl = $fetch->mag_titl;
			$g_mag_body = $fetch->mag_body;
		}
/* **
		$stmt = OCIParse($conn, $sql);

		OCIbindbyname ($stmt, ":INDEX_ID", &$index_id, -1);
		OCIDefinebyname($stmt, "MAG_ID", &$g_mag_id);
		OCIDefinebyname($stmt, "BODY_ID", &$g_body_id);
		OCIDefinebyname($stmt, "MAG_TITL", &$g_mag_titl);
		OCIDefinebyname($stmt, "MAG_BODY", &$g_mag_body);
		$ncols = OCIExecute($stmt, OCI_DEFAULT);
		OCIFetch($stmt);
*/
	} else{
		//レコード無し
	}
}
function pub_tb_delete ($conn, $index_id) {
	$l_ret = FALSE;
	if ( pub_tb_chkrec ($conn, $index_id) ) {
//		$sql = mm_make_sql ("DELETE_T_BODY");
		$sql = "DELETE FROM T_BODY WHERE index_id = $index_id";
		$l_ret = db_exec($sql);
/* **
		$stmt = OCIParse($conn, $sql);
		OCIbindbyname ($stmt, ":INDEX_ID", &$index_id, -1);
		$l_ret = OCIExecute($stmt, OCI_DEFAULT);
*/
	}
	return $l_ret;
}
function pub_tr_insert ($conn, $index_id, $mag_id, $body_id, $resrv_month, $resrv_day,
			$resrv_hour, $resrv_flg) {
	if ( pub_tr_chkrec ($conn, $index_id) ) {
//		$sql = mm_make_sql ("UPDATE_T_PUB_RESERVE");
		$sql = "UPDATE T_PUB_RESERVE SET mag_id = '$mag_id', body_id = $body_id, resrv_month = '$resrv_month', resrv_day = '$resrv_day', resrv_hour = to_timestamp('$resrv_hour', 'YYYY-MM-DD HH24:MI'), resrv_flg = '$resrv_flg', modefy_dt = 'now' WHERE index_id = $index_id";
//		$stmt = OCIParse($conn, $sql);
	} else{
//		$sql = mm_make_sql ("INSERT_T_PUB_RESERVE");
		$sql = "INSERT INTO T_PUB_RESERVE ( index_id , mag_id , body_id, resrv_month, resrv_day, resrv_hour, resrv_flg, modefy_dt )"
		. " VALUES ( $index_id, '$mag_id', $body_id, '$resrv_month', '$resrv_day', to_timestamp('$resrv_hour', 'YYYY/MM/DD HH24:MI'), '$resrv_flg', 'now' )";
//		$stmt = OCIParse($conn, $sql);
	}
/* **
	OCIbindbyname ($stmt, ":INDEX_ID", &$index_id, -1);
	OCIbindbyname ($stmt, ":MAG_ID", &$mag_id, -1);
	OCIbindbyname ($stmt, ":BODY_ID", &$body_id, -1);
	OCIbindbyname ($stmt, ":RESRV_MONTH", &$resrv_month, -1);
	OCIbindbyname ($stmt, ":RESRV_DAY", &$resrv_day, -1);
	OCIbindbyname ($stmt, ":RESRV_HOUR", &$resrv_hour, -1);
	OCIbindbyname ($stmt, ":RESRV_FLG", &$resrv_flg, -1);
	return OCIExecute($stmt, OCI_DEFAULT);
*/
	return db_exec($sql);
}
function pub_tr_load ($conn, $index_id) {
	global $g_mag_id, $g_body_id, $g_resrv_month, $g_resrv_day, $g_resrv_hour, $g_resrv_flg,
		$g_resrv_year, $g_resrv_hour_ampm, $g_resrv_hour_h, $g_resrv_hour_m;

	if ( pub_tr_chkrec ($conn, $index_id) ) {
//		$sql = mm_make_sql ("SELECT_T_RESERVE_LOAD");
		$sql = "SELECT mag_id, body_id, resrv_month, resrv_day, to_char(resrv_hour, 'YYYY') as resrv_year, to_char(resrv_hour, 'hh24') as resrv_hour_a, to_char(resrv_hour, 'hh12') as resrv_hour_h, to_char(resrv_hour, 'mi') as resrv_hour_m, resrv_flg FROM T_PUB_RESERVE WHERE index_id = $index_id";
		$result = db_exec($sql);
		if (pg_numrows($result)) {
			$fetch = pg_fetch_object($result, 0);
			$g_mag_id = $fetch->mag_id;
			$g_body_id = $fetch->body_id;
			$g_resrv_month = $fetch->resrv_month;
			$g_resrv_day = $fetch->resrv_day;
			$w_resrv_hour_a = $fetch->resrv_hour_a;
			$g_resrv_hour_h = $fetch->resrv_hour_h;
			$g_resrv_hour_m = $fetch->resrv_hour_m;
			$g_resrv_flg = $fetch->resrv_flg;
		}
/* **
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
*/
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
//		$sql = mm_make_sql ("DELETE_T_RESERVE");
		$sql = "DELETE FROM T_PUB_RESERVE WHERE index_id = $index_id";
		$l_ret = db_exec($sql);
/* **
		$stmt = OCIParse($conn, $sql);
		OCIbindbyname ($stmt, ":INDEX_ID", &$index_id, -1);
		$l_ret = OCIExecute($stmt, OCI_DEFAULT);
*/
	}
	return $l_ret;
}
function pub_tb_chkrec ($conn, $index_id) {
//	$sql = mm_make_sql ("SELECT_T_BODY");
	$sql = "SELECT * FROM T_BODY WHERE index_id = $index_id";
	$result = db_exec($sql);
/* **
	$stmt = OCIParse($conn, $sql);
	$ret01 = OCIbindbyname ($stmt, ":INDEX_ID", &$index_id, -1);
	$ncols = OCIExecute($stmt, OCI_DEFAULT);
	$nrec = OCIFetch ($stmt);
	$ret01 = OCIFreeStatement($stmt);
*/
	$nrec = pg_numrows($result);
	return $nrec;
}
function pub_tr_chkrec ($conn, $index_id) {
//	$sql = mm_make_sql ("SELECT_T_RESERVE_INDEX");
	$sql = "SELECT * FROM T_PUB_RESERVE WHERE index_id = $index_id";
	$result = db_exec($sql);
	$nrec = pg_numrows($result);
/* **
	$stmt = OCIParse($conn, $sql);
	$ret01 = OCIbindbyname ($stmt, ":INDEX_ID", &$index_id, -1);
	$ncols = OCIExecute($stmt, OCI_DEFAULT);
	$nrec = OCIFetch ($stmt);
	$ret01 = OCIFreeStatement($stmt);
*/
	return $nrec;
}
function pub_magdelrea_insert ($conn, $mag_del_id, $mag_id, $reason) {
	if ( pub_tmds_chkrec ($conn, $mag_del_id) ) {
		$sql = mm_make_sql ("UPDATE_T_MAG_DELREASON");
		$stmt = OCIParse($conn, $sql);
	} else{
		$sql = mm_make_sql ("INSERT_T_MAG_DELREASON");
		$sql = "INSERT INTO T_MAG_DELREASON ( mag_del_id , mag_id , reason , modefy_dt ) VALUES ( $mag_del_id, '$mag_id', '$reason', 'now' )";
/* **
		$stmt = OCIParse($conn, $sql);
*/
	}
	$retStat = db_exec($sql);
/* **
	OCIbindbyname ($stmt, ":MAG_DEL_ID", &$mag_del_id, -1);
	OCIbindbyname ($stmt, ":MAG_ID", &$mag_id, -1);
	OCIbindbyname ($stmt, ":REASON", &$reason, -1);
	$retStat = OCIExecute($stmt, OCI_DEFAULT);
	$ret01 = OCIFreeStatement($stmt);
*/
	return $retStat;
}
function pub_tmds_chkrec ($conn, $mag_del_id) {
//	$sql = mm_make_sql ("SELECT_T_MAG_DELREASON_ID");
	$sql = "SELECT * FROM T_MAG_DELREASON WHERE mag_del_id = $mag_del_id";
	$result = db_exec($sql);
	$retStat = pg_numrows($result);
/* **
	$stmt = OCIParse($conn, $sql);
	$ret01 = OCIbindbyname ($stmt, ":MAG_DEL_ID", &$mag_del_id, -1);
	$ncols = OCIExecute($stmt, OCI_DEFAULT);
	$retStat = OCIFetch ($stmt);
	$ret01 = OCIFreeStatement($stmt);
*/
	return $retStat;
}
function pub_magstoprea_insert ($conn, $mag_stop_id, $mag_id, $reason) {
	if ( pub_tmss_chkrec ($conn, $mag_stop_id) ) {
//		$sql = mm_make_sql ("UPDATE_T_MAG_STOPREASON");
		$sql = "UPDATE  T_MAG_STOPREASON SET mag_id = '$mag_id', reason = '$reason', modefy_dt = 'now' WHERE mag_stop_id = $mag_stop_id";
//		$stmt = OCIParse($conn, $sql);
	} else{
//		$sql = mm_make_sql ("INSERT_T_MAG_STOPREASON");
		$sql = "INSERT INTO T_MAG_STOPREASON ( mag_stop_id , mag_id , reason , modefy_dt ) VALUES ( $mag_stop_id, '$mag_id', '$reason', 'now' )";
//		$stmt = OCIParse($conn, $sql);
	}
/* **
	OCIbindbyname ($stmt, ":MAG_STOP_ID", &$mag_stop_id, -1);
	OCIbindbyname ($stmt, ":MAG_ID", &$mag_id, -1);
	OCIbindbyname ($stmt, ":REASON", &$reason, -1);
	$retStat = OCIExecute($stmt, OCI_DEFAULT);
	$ret01 = OCIFreeStatement($stmt);
*/
	$retStat = db_exec($sql);
	return $retStat;
}
function pub_tmss_chkrec ($conn, $mag_stop_id) {
//	$sql = mm_make_sql ("SELECT_T_MAG_STOPREASON_ID");
	$sql = "SELECT * FROM T_MAG_STOPREASON WHERE mag_stop_id = $mag_stop_id";
	$result = db_exec($sql);
	$retStat = pg_numrows($result);
/* **
	$stmt = OCIParse($conn, $sql);
	$ret01 = OCIbindbyname ($stmt, ":MAG_STOP_ID", &$mag_stop_id, -1);
	$ncols = OCIExecute($stmt, OCI_DEFAULT);
	$retStat = OCIFetch ($stmt);
	$ret01 = OCIFreeStatement($stmt);
*/
	return $retStat;
}

?>
