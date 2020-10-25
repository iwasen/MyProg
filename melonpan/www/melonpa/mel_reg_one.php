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
include("inc/pass_check.inc");
include("inc/jimu_mag_num.inc");
include("inc/error_msg_list.inc");
include("inc/check.inc");

function sel_stop_reason($conn) {
	global $nrow, $reason_list;

	$sql = "select STOP_REASON from M_DELREASON where READER_SELECT_FLG = '1'";
	$result = db_exec($sql);
	$nrow = pg_numrows($result);
}

/****************************************************************
 Main
****************************************************************/

$mail_let_flg = 0;
$mail_baq_flg = 0;
$mail_addr = strtolower($mail_addr);
$email = strtolower($email);

if ($mail_addr != "") {
	if ($mail_addr != "") {pass_check($gConn,trim($mail_addr),trim($reader_pswd));}
}

if ( $sub_d == "購読する" ) {
	$action = "on";
} elseif ( $sub_d == "購読解除する" ) {
	$action = "of";
}

if ($read_chk == "") {

	$mag_datas = "";
	$error_datas = $em_regist_1;
	include("tmpl_html/mag-regi_thanks.html");
	exit();

} else {

$ret = chkmail_addr($email);

if ($ret == -1) {
	$error_datas = $em_regist_2;
	include("tmpl_html/mag-regi_thanks.html");
	exit();
} elseif ($email == "") {
	$error_datas = $em_regist_2;
	include("tmpl_html/mag-regi_thanks.html");
	exit();
}
	if (ereg(",",$email)) {
		$error_datas = $em_regist_2;
		include("tmpl_html/mag-regi_thanks.html");
		exit();
	}

if ($action == 'on') {
	if ($agreement1 == "") {
		$mag_datas = "";
		$error_datas = '<br>「規約に同意します」のチェックボックスがチェックされていないようです。';
		include("tmpl_html/mag-regi_thanks.html");
		exit();
	}

	if ($agreement2 == "") {
		$mag_datas = "";
		$error_datas = '<br>「広告・宣伝受取に同意します」のチェックボックスがチェックされていないようです。';
		include("tmpl_html/mag-regi_thanks.html");
		exit();
	}
}

//めろんぱ登録確認
if ($mail_addr != "") {
	$riba_flg = 0;
	$sql = "select reader_sex,reader_mrg,reader_zip1,reader_occup,to_char(reader_birth,'yyyy') as reader_birth from m_melonpa where mail_addr = '$mail_addr'";
	$result = db_exec($sql);
	$ct = pg_numrows($result);
	if ($ct != 0) {
		$fetch = pg_fetch_object($result, $i);
		$reader_sex = $fetch->reader_sex;
		$reader_mrg = $fetch->reader_mrg;
		$reader_zip1 = $fetch->reader_zip1;
		$reader_occup = $fetch->reader_occup;
		$reader_birth = $fetch->reader_birth;
		$riba_flg = 1;
		$unix_now_time = strftime("%Y",time());
		$unix_age = ($unix_now_time - $reader_birth);

		if ($unix_age >= 60 ) { $user_birth = "60"; }
		elseif ($unix_age >= 50 && $unix_age < 60) { $user_birth = "50";}
		elseif ($unix_age >= 40 && $unix_age < 50) { $user_birth = "40";}
		elseif ($unix_age >= 30 && $unix_age < 40) { $user_birth = "30";}
		elseif ($unix_age >= 20 && $unix_age < 30) { $user_birth = "20";}
		elseif ($unix_age >= 10 && $unix_age < 20) { $user_birth = "10";}

		$sql1 = "select area_id from m_zipcode where zipcode = '$reader_zip1'";
		$result = db_exec($sql1);
		$ct = pg_numrows($result);
		if ($ct == 0) {
			$user_area_id = "9999";
		} else {
			$fetch = pg_fetch_object($result, $i);
			$user_area_id = $fetch->area_id;
		}
	}
} else {
	$riba_flg = 0;
}

$mag_id = $read_chk;
$read_ok_flg = 1;

//リバッチャ確認
$sql = "select m.mag_riba_status_flg,r.age,r.sex,r.mrg,r.ocp,r.ara,r.ex ";
$sql .= " from m_magazine m ,m_ribaoptin r ";
$sql .= " where m.mag_id ='$mag_id' and m.mag_id = r.mag_id ";
$result = db_exec($sql);
if ($ct = pg_numrows($result)) {
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
		$ara_flg = 0;
		if (count($ara_all) != 0) {
			while (list($key,$val) = each($ara_all)) {
				if ($user_area_id == $val) { $ara_flg = 1;}
			}
		} else {
			$ara_flg = 1;
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
		//if ($sex_flg == 0 && $mrg_flg == 0 && $ocp_flg == 0 && $ara_flg == 0 && $age_flg == 0) {
		if ($sex_flg == 0 || $mrg_flg == 0 || $ocp_flg == 0 || $ara_flg == 0 || $age_flg == 0) {
			$read_ok_flg = 0;
		}
	} else {
		$read_ok_flg = 2;
	}
} else {
	$read_ok_flg = 1;
}
//****************************************
	if ($action == "on") {
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
			$fetch = pg_fetch_object($result, $i);
			$mag_nm = $fetch->mag_nm;

			$error_datas .= "<br><FONT color=#990000><B>[ID".$mag_id."：".trim(htmlspecialchars($mag_nm))."]</B></FONT>";
			$error_datas .= "<br>".trim(htmlspecialchars($mag_nm)).$em_regist_3;
			$flg = 1;
			include ("tmpl_html/mag-regi_thanks.html");
			exit();
		}

	if ($read_ok_flg == 0) {
		$sql = "select mag_nm from M_MAGAZINE where mag_id = '$mag_id'";
		$result = db_exec($sql);
		if (pg_numrows($result)) {
			$fetch = pg_fetch_object($result, 0);
			$mag_nm = $fetch->mag_nm;
		}
		$error_datas .= "<br><FONT color=#990000><B>[ID".$mag_id."：".trim(htmlspecialchars($mag_nm))."]</B></FONT>";
		$error_datas .= "<br>".trim(htmlspecialchars($mag_nm)).$em_regist_15;
		$flg = 1;
		include ("tmpl_html/mag-regi_thanks.html");
		exit();
	} elseif ($read_ok_flg == 2) {
		$mag_nm = "";
		$sql = "select mag_nm from M_MAGAZINE where mag_id = '$mag_id'";
		$result = db_exec($sql);
		if (pg_numrows($result)) {
			$fetch = pg_fetch_object($result, 0);
			$mag_nm = $fetch->mag_nm;
		}
		$error_datas .= "<br><FONT color=#990000><B>[ID".$mag_id."：".trim(htmlspecialchars($mag_nm))."]</B></FONT>";
		$error_datas .= "<br>".trim(htmlspecialchars($mag_nm)).$em_regist_16;
		$flg = 1;
		include ("tmpl_html/mag-regi_thanks.html");
		exit();
	}

if ($flg == 0) {

//reader_iidセット
	$reader_id = reader_check($email);
//****************************************強制購読
	// 既登録チェック  要件NO 1 追加 //
	$sql = "select count(*) as COUNT from T_MAILADDR where MAIL_ADDR = '$email' and REG_FLG = '0'";
	if (pg_numrows($result)) {
		$fetch = pg_fetch_object($result, 0);
		$mag_reg_cnt = $fetch->count;
	}else{
		$mag_reg_cnt = 0;
	}
	if ( IsPortableAddr($email) ){ $mag_reg_cnt = 1; } // 2001.08.22 携帯読者には送らない。
	// --------------------------------//
	if ($mag_reg_cnt == 0) {   // 要件No1 既存読者の場合はそのまま //
		//バ・キューン
		$sql = "select M.mag_nm from T_MAILADDR T,M_MAGAZINE M where T.mail_addr = '$email' and T.mag_id = '$bakyun_id' and T.mag_id = M.mag_id";
		$result = db_exec($sql);
		$ct = pg_numrows($result);
		if ($ct > 0) { ;
			$fetch = pg_fetch_object($result, 0);
			$mag_nm = $fetch->mag_nm;
		} else {
			if (($mail_id = update_squ_key ("T_MAILADDR")) != "-1") {
			$modify_dt = date("y-m-d H:i:s");
//			if ($reader_id == "") { $reader_id = 0;}
			$sql = "insert into T_MAILADDR (mail_id,mag_id,reader_id,mail_addr,modify_dt,reg_flg,subscribe_dt) values";
			$sql .= "($mail_id,'$bakyun_id',$reader_id,'$email',";
			$sql .= "to_timestamp('$modify_dt','YY-MM-DD HH24:MI:SS'),0,to_timestamp('$modify_dt','YY-MM-DD HH24:MI:SS'))";
			db_exec($sql);
			$mail_baq_flg = 1;
			}
		}

	//めろんぱんレター
		$sql = "select M.mag_nm from T_MAILADDR T,M_MAGAZINE M where T.mail_addr = '$email' and T.mag_id = '$letter_id' and T.mag_id = M.mag_id";
		$result = db_exec($sql);
		$ct = pg_numrows($result);
		if ($ct > 0) { ;
			$fetch = pg_fetch_object($result, 0);
			$mag_nm = $fetch->mag_nm;
		} else {
			if (($mail_id = update_squ_key ("T_MAILADDR")) != "-1") {
			$modify_dt = date("y-m-d H:i:s");
//			if ($reader_id == "") { $reader_id = 0;}
			$sql = "insert into T_MAILADDR (mail_id,mag_id,reader_id,mail_addr,modify_dt,reg_flg,subscribe_dt) values";
			$sql .= "($mail_id,'$letter_id',$reader_id,'$email',";
			$sql .= "to_timestamp('$modify_dt','YY-MM-DD HH24:MI:SS'),0,to_timestamp('$modify_dt','YY-MM-DD HH24:MI:SS'))";
			db_exec($sql);
			$mail_let_flg = 1;
			}
		}


	//めろんぱんHTMLレター
		$sql = "select M.mag_nm from T_MAILADDR T,M_MAGAZINE M where T.mail_addr = '$email' and T.mag_id = '$html_letter_id' and T.mag_id = M.mag_id";
		$result = db_exec($sql);
		$ct = pg_numrows($result);
		if ($ct > 0) { ;
			$fetch = pg_fetch_object($result, 0);
			$mag_nm = $fetch->mag_nm;
		} else {
			if (($mail_id = update_squ_key ("T_MAILADDR")) != "-1") {
			$modify_dt = date("y-m-d H:i:s");
//			if ($reader_id == "") { $reader_id = 0;}
			$sql = "insert into T_MAILADDR (mail_id,mag_id,reader_id,mail_addr,modify_dt,reg_flg,subscribe_dt) values";
			$sql .= "($mail_id,'$html_letter_id',$reader_id,'$email',";
			$sql .= "to_timestamp('$modify_dt','YY-MM-DD HH24:MI:SS'),0,to_timestamp('$modify_dt','YY-MM-DD HH24:MI:SS'))";
			db_exec($sql);
			$mail_let_flg = 1;
			}
		}
	}
//**********************************************************

		db_begin_trans();
		if (($mail_id = update_squ_key ("T_MAILADDR")) != "-1") {
			$modify_dt = date("y-m-d H:i:s");
//			if ($reader_id == "") { $reader_id = 0;}
			$sql = "insert into T_MAILADDR (mail_id,mag_id,reader_id,mail_addr,modify_dt,reg_flg,subscribe_dt) values";
			$sql .= "($mail_id,'$mag_id',$reader_id,'$email',";
			$sql .= "to_timestamp('$modify_dt','YY-MM-DD HH24:MI:SS'),0,to_timestamp('$modify_dt','YY-MM-DD HH24:MI:SS'))";
			db_exec($sql);
			db_commit_trans();
		}else {
			$error_datas .= "<br><FONT color=#990000><B>[ID".$mag_id."：".trim(htmlspecialchars($mag_nm))."]</B></FONT>";
			$error_datas .= "<br>".trim(htmlspecialchars($mag_nm)).$em_regist_5;
			include ("tmpl_html/mag-regi_thanks.html");
			$flg = 1;
			db_rollback();
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
			mag_reg_mail($email,$mag_id,trim(htmlspecialchars($mag_nm)), $mail_id, $modify_time);
			/* -------------------- */
			//mag_reg_mail($email,$mag_id,trim($mag_nm));
//	if ($mail_let_flg == 1) {
//		mag_let_mail($email,$letter_id,"めろんぱんレター");
//	}
//	if ($mail_baq_flg == 1) {
//		mag_baq_mail($email,$bakyun_id,"バ・キューン");
//	}
			$mag_datas .= "<br><FONT color=#990000><B>[ID".$mag_id."：".trim(htmlspecialchars($mag_nm))."]</B></FONT>";
			$mag_datas .= "<br>".trim(htmlspecialchars($mag_nm)).$em_regist_6;
			include ("tmpl_html/mag-regi_thanks.html");
		}
	} elseif ($action == "of") {
		$flg = 0;
		$sql = "select M.mag_nm from T_MAILADDR T,M_MAGAZINE M where T.mail_addr = '$email' and T.mag_id = '$mag_id' and T.mag_id = M.mag_id";
		$result = db_exec($sql);
		$ct = pg_numrows($result);
		if ($ct < 1) {
			$sql = "select mag_nm from M_MAGAZINE where mag_id = '$mag_id'";
			$result = db_exec($sql);
			if (pg_numrows($result)) {
				$fetch = pg_fetch_object($result, 0);
				$mag_nm = $fetch->mag_nm;
			}
			$mag_data = "[ID".$mag_id."：".trim($mag_nm)."]";
			$error_datas .= "<br><FONT color=#990000><B>[ID".$mag_id."：".trim(htmlspecialchars($mag_nm))."]</B></FONT>";
			$error_datas .= "<br>".trim(htmlspecialchars($mag_nm)).$em_regist_7;
			$flg = 1;
			include("tmpl_html/mag-regi_thanks.html");
			exit();
		}
			$sql = "select mag_nm from M_MAGAZINE where mag_id = '$mag_id'";
			$result = db_exec($sql);
			$fetch = pg_fetch_object($result, 0);
			$mag_nm = $fetch->mag_nm;
			$mag_datas .= "<br><FONT color=#990000><B>[ID".$mag_id."：".trim(htmlspecialchars($mag_nm))."]</B></FONT>";
			$mag_datas .= "<br>".trim(htmlspecialchars($mag_nm)).$em_regist_9;
			include ("tmpl_html/mag-stop-3.html");
	}
}
exit();

?>
