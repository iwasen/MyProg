<?php

function chkmail_addr($mail_addr) {
	if ( $mail_addr != null ) {
		if (ereg(".+\@.+\..+",$mail_addr) && ereg("\.[a-zA-Z]+$",$mail_addr)) {
			return 0;
		} else {
			return -1;
		}
	}
}

include("../inc/com_db_func.inc");
include("inc/mr_s_mail.inc");
include("inc/error_msg_list.inc");
include("inc/check.inc");

/* **
function sel_stop_reason($conn) {
	global $nrow, $reason_list;
	
	$sql = "select STOP_REASON from M_DELREASON where READER_SELECT_FLG = '1'";
	$stmt = OCIParse($conn, $sql);
	OCIDefinebyname($stmt, "STOP_REASON", &$reason);
	OCIExecute($stmt);
	$nrow = OCIFetchStatement($stmt, $reason_list);
	OCIFreeStatement($stmt);
}
*/
/****************************************************************
 Main
****************************************************************/
$email = strtolower($email);
$reader_id = reader_check($email);
$ret = chkmail_addr($email);

include("inc/jimu_mag_num.inc");
if ($mag_id == "") {
	$error_datas = $em_regist_11;
	include("tmpl_html/register_thanks.html");
	exit();
}

if ($ret == -1) {
	$error_datas = $em_regist_2;
	include("tmpl_html/register_thanks.html");
	exit();
} elseif ($email == "") {
	$error_datas = $em_regist_2;
	include("tmpl_html/register_thanks.html");
	exit();
}
	if (ereg(",",$email)) {
		$error_datas = $em_regist_2;
		include("tmpl_html/register_thanks.html");
		exit();
	}


//めろんぱ登録確認

/* **
if ($mail_addr != "") {
	$riba_flg = 0;
	$sql = "select reader_sex,reader_mrg,reader_zip1,reader_occup,to_char(reader_birth,'yyyy') as reader_birth from m_melonpa where mail_addr = '$mail_addr'";
	$stmt = OCIParse($conn, $sql);
	OCIDefinebyname($stmt, "READER_SEX", &$reader_sex);
	OCIDefinebyname($stmt, "READER_MRG", &$reader_mrg);
	OCIDefinebyname($stmt, "READER_ZIP1", &$reader_zip1);
	OCIDefinebyname($stmt, "READER_OCCUP", &$reader_occup);
	OCIDefinebyname($stmt, "READER_BIRTH", &$reader_birth);
	OCIExecute($stmt, OCI_DEFAULT);
	$ct = OCIFetchStatement($stmt,&$resarray);
	if ($ct != 0) { $riba_flg = 1;
	$unix_now_time = strftime("%Y",time());
	$unix_age = ($unix_now_time - $reader_birth);
		if ($unix_age >= 60 ) { $user_birth = "60"; }
		elseif ($unix_age >= 50 && $unix_age < 60) { $user_birth = "50";}
		elseif ($unix_age >= 40 && $unix_age < 50) { $user_birth = "40";}
		elseif ($unix_age >= 30 && $unix_age < 40) { $user_birth = "30";}
		elseif ($unix_age >= 20 && $unix_age < 30) { $user_birth = "20";}
		elseif ($unix_age >= 10 && $unix_age < 20) { $user_birth = "10";}
	OCIFreeStatement($stmt);

		$sql1 = "select area_id from m_zipcode where zipcode = '$reader_zip1'";
		$stmt = OCIParse($conn, $sql1);
		OCIDefinebyname($stmt, "AREA_ID", &$user_area_id);
		OCIExecute($stmt, OCI_DEFAULT);
		$ct2 = OCIFetchStatement($stmt,&$resarray);
		if ($ct2 == 0) {
			$user_area_id = "9999";
		}
		OCIFreeStatement($stmt);
	}
} else {
	$riba_flg = 0;
}
*/

$riba_flg = 0;
$read_ok_flg = 1;

//リバッチャ確認
$sql = "select m.mag_riba_status_flg,r.age,r.sex,r.mrg,r.ocp,r.ara,r.ex ";
$sql .= " from m_magazine m ,m_ribaoptin r ";
$sql .= " where m.mag_id ='$mag_id' and m.mag_id = r.mag_id ";
$result = db_exec($sql);
$ct = pg_numrows($result);
if ($ct != 0) {
	$fetch = pg_fetch_object($result, 0);
	$mag_riba_status_flg = $fetch->mag_riba_status_flg;
	$opt_id = $fetch->opt_id;
	$age = $fetch->age;
	$sex = $fetch->sex;
	$mrg = $fetch->mrg;
	$ocp = $fetch->ocp;
	$ara = $fetch->ara;
	$ex = $fetch->ex;
}
if ($mag_riba_status_flg == 1) {
	if ($riba_flg == 1) {
		if ($age != "") {
			$age_all = split("::",$age);
		}
		if ($sex != "") {
			$sex_all = split("::",$sex);
		}
		if ($mrg != "") {
			$mrg_all = split("::",$mrg);
		}
		if ($ocp != "") {
			$ocp_all = split("::",$ocp);
		}
		if ($ara != "") {
			$ara_all = split("::",$ara);
		}
		$sex_flg = 0;
		if (count($sex_all) != 0) {
			while (list($key,$val) = each($sex_all)) {
				if ($reader_sex == $val) { $sex_flg = 1;}
			}
		} else {
			$sex_flg = 1;
		}
		$mrg_flg = 0;
		if (count($mrg_all) != 0) {
			while (list($key,$val) = each($mrg_all)) {
				if ($reader_mrg == $val) { $mrg_flg = 1;}
			}
		} else {
			$mrg_flg = 1;
		}
		$age_flg = 0;
		if (count($age_all) != 0) {
			while (list($key,$val) = each($age_all)) {
				if ($user_birth == $val) { $age_flg = 1;}
			}
		} else {
			$age_flg = 1;
		}
		$ocp_flg = 0;
		if (count($ocp_all) != 0) {
			while (list($key,$val) = each($ocp_all)) {
				if ($reader_occup == $val) { $ocp_flg = 1;}
			}
		} else {
			$ocp_flg = 1;
		}
		$age_flg = 0;
		if (count($age_all) != 0) {
			while (list($key,$val) = each($age_all)) {
				if ($user_birth == $val) { $age_flg = 1;}
			}
		} else {
			$age_flg = 1;
		}
		//if ($sex_flg == 0 && $mrg_flg == 0 && $ocp_flg == 0 && $ara_flg == 0) {
		if ($sex_flg == 0 || $mrg_flg == 0 || $ocp_flg == 0 || $ara_flg == 0 || $age_flg == 0) {
			$read_ok_flg = 0;
		}
	} else {
		$read_ok_flg = 2;
	}
} else {
	$read_ok_flg = 1;
}


//登録
if ($regist != "" && $unregist == "") {
	$regist_flg = 0;
	$flg = 0;
	/* 購読禁止アドレス確認 --------------------- */
/*
	$sql = "select count(*) as COUNT from T_MAILADDR_DISABLED where MAIL_ADDR = '$email'";
	$result = db_exec($sql);
	if (pg_numrows($result)) {
		$fetch = pg_fetch_object($result, 0);
		$addr_cnt = $fetch->count;
	}else{
		$addr_cnt = 1; // 取得エラーの場合は購読不可
	}
	if ($addr_cnt != 0) {
*/
	if (!reg_check($email)){	//購読禁止アドレス確認(2002.03.19 機能追加)
		$error_datas .= "<br><FONT color=#990000><B>[EMAIL ".trim($email)."]</B></FONT>";
		$error_datas .= "<br>登録できないようです。";
		$flg = 1;
		include ("tmpl_html/mag-regi_thanks.html");
		exit();
	}
	/* ------------------------------------------ */
	$sql = "select M.mag_nm from T_MAILADDR T,M_MAGAZINE M where T.mail_addr = '$email' and T.mag_id = '$mag_id' and T.mag_id = M.mag_id";
	$result = db_exec($sql);
	$ct = pg_numrows($result);
	if ($ct > 0) {
		$fetch = pg_fetch_object($result, 0);
		$mag_nm = $fetch->mag_nm;
		$mag_data = "[ID".$mag_id."：".$mag_nm."]";

		$error_datas .= "<br><FONT color=#990000><B>[ID".$mag_id."：".$mag_nm."]</B></FONT>";
		$error_datas .= "<br>".$mag_nm.$em_regist_3;
		include("tmpl_html/register_thanks.html");
		exit();
	}

	if ($read_ok_flg == 0) {
		$sql = "select mag_nm from M_MAGAZINE where mag_id = '$mag_id'";
		$result = db_exec($sql);
		if (pg_numrows($result)) {
			$fetch = pg_fetch_object($result, 0);
			$mag_nm = $fetch->mag_nm;
		}
		$error_datas .= "<br><FONT color=#990000><B>[ID".$mag_id."：".trim($mag_nm)."]</B></FONT>";
		$error_datas .= "<br>".trim($mag_nm).$em_regist_15;
		$flg = 1;
		include ("tmpl_html/register_thanks.html");
		exit();
	} elseif ($read_ok_flg == 2) {
		$mag_nm = "";
		$sql = "select mag_nm from M_MAGAZINE where mag_id = '$mag_id'";
		if (pg_numrows($result)) {
			$fetch = pg_fetch_object($result, 0);
			$mag_nm = $fetch->mag_nm;
		}
		$error_datas .= "<br><FONT color=#990000><B>[ID".$mag_id."：".trim($mag_nm)."]</B></FONT>";
		$error_datas .= "<br>".trim($mag_nm).$em_regist_16;
		$flg = 1;
		include ("tmpl_html/mag-regi_thanks.html");
		exit();
	}

	$sql = "select mag_nm,mag_status_flg,mag_pub_status_flg,mag_pub_stop_flg from M_MAGAZINE where mag_id = '$mag_id'";
	$result = db_exec($sql);
	$ct = pg_numrows($result);
	if ($ct != 0) {
		$fetch = pg_fetch_object($result, 0);
		$mag_nm = $fetch->mag_nm;
		$mag_status_flg = $fetch->mag_status_flg;
		$mag_pub_status_flg = $fetch->mag_pub_status_flg;
		$mag_pub_stop_flg = $fetch->mag_pub_stop_flg;
		if ($mag_status_flg == '02' || $mag_pub_status_flg == '02' || $mag_pub_status_flg == '99' || $mag_pub_stop_flg == '02' || $mag_pub_stop_flg == '99') {
			$error_datas .= $em_regist_13;
			include("tmpl_html/register_thanks.html");
			exit();
		}
	} else {
		$error_datas .= $em_regist_12;
		include("tmpl_html/register_thanks.html");
		exit();
	}

	db_begin_trans();
	if (($mail_id = update_squ_key ("T_MAILADDR")) != "-1") {
		$modify_dt = date("y-m-d H:i:s");
		if ($reader_id == "") { $reader_id = 0;}
		$sql = "insert into T_MAILADDR  (mail_id,mag_id,reader_id,mail_addr,modify_dt,reg_flg,subscribe_dt) values";
		$sql .= "($mail_id,'$mag_id',$reader_id,'$email',";
		$sql .= "to_timestamp('$modify_dt','YY-MM-DD HH24:MI:SS'),1,to_timestamp('$modify_dt','YY-MM-DD HH24:MI:SS'))";
		if (!db_exec($sql)) {
			db_rollback();
			$error_datas .= "<br><FONT color=#990000><B>[ID".$mag_id."：".$mag_nm."]</B></FONT>";
			$error_datas .= "<br>".$mag_nm.$em_regist_4;
			include("tmpl_html/register_thanks.html");
			exit();
		}
		db_commit_trans();
	}else {
		$error_datas .= "<br><FONT color=#990000><B>[ID".$mag_id."：".$mag_nm."]</B></FONT>";
		$error_datas .= "<br>".$mag_nm.$em_regist_5;
		db_rollback();
		include("tmpl_html/register_thanks.html");
		exit();
	}
	$sql = "select mag_nm from M_MAGAZINE where mag_id = '$mag_id'";
	$result = db_exec($sql);
	if (pg_numrows($result)) {
		$fetch = pg_fetch_object($result, 0);
		$mag_nm = $fetch->mag_nm;
	}

	/* -- 解除用キー追加 -- */
	$modify_time = substr($modify_dt,9,2) . substr($modify_dt,12,2) . substr($modify_dt,15,2);
	mag_reg_mail($email,$mag_id,trim($mag_nm), $mail_id, $modify_time);
	/* -------------------- */
	//mag_reg_mail($email,$mag_id,$mag_nm);
	$mag_datas .= "<br><FONT color=#990000><B>[ID".$mag_id."：".$mag_nm."]</B></FONT>";
	$mag_datas .= "<br>".$mag_nm.$em_regist_6;
	$regist_flg = 1;
}

//解除
else if ($regist == "" && $unregist != "") {
	$sql = "select M.mag_nm from T_MAILADDR T,M_MAGAZINE M where T.mail_addr = '$email' and T.mag_id = '$mag_id' and T.mag_id = M.mag_id";
	$result = db_exec($sql);
	$ct = pg_numrows($result);
	$mag_data = "[ID".$mag_id."：".$mag_nm."]";
	if ($ct < 1) {
		$sql = "select mag_nm from M_MAGAZINE where mag_id = '$mag_id'";
		$result = db_exec($sql);
		if (pg_numrows($result)) {
			$fetch = pg_fetch_object($result, 0);
			$mag_nm = $fetch->mag_nm;
		}
		$mag_data = "[ID".$mag_id."：".$mag_nm."]";
		$error_datas .= "<br><FONT color=#990000><B>[ID".$mag_id."：".$mag_nm."]</B></FONT>";
		$error_datas .= "<br>".$mag_nm.$em_regist_7;
		include("tmpl_html/register_thanks.html");
		exit();
	}
	$fetch = pg_fetch_object($result, 0);
	$mag_nm = $fetch->mag_nm;
	$sql = "select mag_nm,mag_status_flg,mag_pub_status_flg,mag_pub_stop_flg from M_MAGAZINE where mag_id = '$mag_id'";
	$result = db_exec($sql);
	$ct = pg_numrows($result);
	if ($ct == 0) {
		$error_datas .= $em_regist_12;
		include("tmpl_html/register_thanks.html");
		exit();
	} else {
		$fetch = pg_fetch_object($result, 0);
		$mag_nm = $fetch->mag_nm;
		$mag_status_flg = $fetch->mag_status_flg;
		$mag_pub_status_flg = $fetch->mag_pub_status_flg;
		$mag_pub_stop_flg = $fetch->mag_pub_stop_flg;
		if ($mag_status_flg == '02' || $mag_pub_status_flg == '02' || $mag_pub_status_flg == '99' || $mag_pub_stop_flg == '02' || $mag_pub_stop_flg == '99') {
			$error_datas .= $em_regist_13;
			include("tmpl_html/register_thanks.html");
			exit();
		}
	}

/*		$sql = "delete from T_MAILADDR where mag_id = '$mag_id' and mail_addr = '$email'";
		$stmt = OCIParse($conn, $sql);
		OCIExecute($stmt, OCI_DEFAULT);
		if (!$stmt) {
			$error_datas .= "<br><FONT color=#990000><B>[ID".$mag_id."：".$mag_nm."]</B></FONT>";
			$error_datas .= "<br>".$mag_nm.$em_regist_8;
			OCIFreeStatement($stmt);
			OCIRollback($conn);
			include("tmpl_html/register_thanks.html");
			db_logoff();
			exit();
		}
	$sql = "select mag_nm from M_MAGAZINE where mag_id = '$mag_id'";
	$stmt = OCIParse($conn, $sql);
	OCIDefinebyname($stmt, "MAG_NM", &$mag_nm);
	OCIExecute($stmt, OCI_DEFAULT);
	OCIFetch($stmt);
	$mag_datas .= "<br><FONT color=#990000><B>[ID".$mag_id."：".$mag_nm."]</B></FONT>";
	$mag_datas .= "<br>".$mag_nm.$em_regist_14;
*/
//追加緊急
			$sql = "select mag_nm from M_MAGAZINE where mag_id = '$mag_id'";
			$result = db_exec($sql);
			if (pg_numrows($result)) {
				$fetch = pg_fetch_object($result, 0);
				$mag_nm = $fetch->mag_nm;
			}
			$mag_datas .= "<br><FONT color=#990000><B>[ID".$mag_id."：".trim($mag_nm)."]</B></FONT>";
			$mag_datas .= "<br>".trim($mag_nm).$em_regist_9;
			include ("tmpl_html/mag-stop-3.html");
			exit();
//ここまで
} else{
	$error_datas .= $em_regist_1;
	include("tmpl_html/register_thanks.html");
	exit();
}

// 既登録チェック,携帯アドレスチェック 2001.08.22 //
$sql = "select count(*) as COUNT from T_MAILADDR where MAIL_ADDR = '$email'";
$result = db_exec($sql);
if (pg_numrows($result)) {
	$fetch = pg_fetch_object($result, 0);
	$mag_reg_cnt = $fetch->count;
	$mag_reg_cnt--; // すでに登録処理済みのため
}else{
	$mag_reg_cnt = 0;
}
if ( check_imode($email) ){ 
	$mag_reg_cnt = 1; 
} 
// ----------------------------------------------- //

if ($regist_flg == 1 && $mag_reg_cnt == 0) {
//バ・キューン
	$sql = "select M.mag_nm from T_MAILADDR T,M_MAGAZINE M where T.mail_addr = '$email' and T.mag_id = '$bakyun_id' and T.mag_id = M.mag_id";
	$result = db_exec($sql);
	$ct = pg_numrows($result);
	if ($ct > 0) { ;
	} else {
		if (($mail_id = update_squ_key ("T_MAILADDR")) != "-1") {
		$modify_dt = date("y-m-d H:i:s");
		if ($reader_id == "") { $reader_id = 0;}
		$sql = "insert into T_MAILADDR  (mail_id,mag_id,reader_id,mail_addr,modify_dt,reg_flg,subscribe_dt) values";
		$sql .= "($mail_id,'$bakyun_id',$reader_id,'$email',";
		$sql .= "to_timestamp('$modify_dt','YY-MM-DD HH24:MI:SS'),1,to_timestamp('$modify_dt','YY-MM-DD HH24:MI:SS'))";
		db_exec($sql);
		//mag_baq_mail($email,$mag_id,trim($mag_nm));
		}
	}

//めろんぱんレター
	$sql = "select M.mag_nm from T_MAILADDR T,M_MAGAZINE M where T.mail_addr = '$email' and T.mag_id = '$letter_id' and T.mag_id = M.mag_id";
	$result = db_exec($sql);
	$ct = pg_numrows($result);
	if ($ct > 0) { ;
	} else {
		if (($mail_id = update_squ_key ("T_MAILADDR")) != "-1") {
			$modify_dt = date("y-m-d H:i:s");
			if ($reader_id == "") { $reader_id = 0;}
			$sql = "insert into T_MAILADDR  (mail_id,mag_id,reader_id,mail_addr,modify_dt,reg_flg,subscribe_dt) values";
			$sql .= "($mail_id,'$letter_id',$reader_id,'$email',";
			$sql .= "to_timestamp('$modify_dt','YY-MM-DD HH24:MI:SS'),1,to_timestamp('$modify_dt','YY-MM-DD HH24:MI:SS'))";
			db_exec($sql);
			//mag_let_mail($email,$mag_id,trim($mag_nm));
		}
	}

//めろんぱんHTMLレター
	$sql = "select M.mag_nm from T_MAILADDR T,M_MAGAZINE M where T.mail_addr = '$email' and T.mag_id = '$html_letter_id' and T.mag_id = M.mag_id";
	$result = db_exec($sql);
	$ct = pg_numrows($result);
	if ($ct > 0) { ;
	} else {
		if (($mail_id = update_squ_key ("T_MAILADDR")) != "-1") {
			$modify_dt = date("y-m-d H:i:s");
			if ($reader_id == "") { $reader_id = 0;}
			$sql = "insert into T_MAILADDR  (mail_id,mag_id,reader_id,mail_addr,modify_dt,reg_flg,subscribe_dt) values";
			$sql .= "($mail_id,'$html_letter_id',$reader_id,'$email',";
			$sql .= "to_timestamp('$modify_dt','YY-MM-DD HH24:MI:SS'),1,to_timestamp('$modify_dt','YY-MM-DD HH24:MI:SS'))";
			db_exec($sql);
			//mag_let_mail($email,$mag_id,trim($mag_nm));
		}
	}
}
include ("tmpl_html/register_thanks.html");
exit();

?>
