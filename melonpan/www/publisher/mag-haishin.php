<?php
include ("inc/pub_passchk.inc");
include ("inc/error_msg_list.inc");
include ("../inc/com_path.inc");
include ("$compath/err_msg.inc");
include ("$compath/com_func.inc");
include ("$compath/com_db_func.inc");
include ("$compath/sql_mm.inc");
include ("$incpath/pub_mm_dbacces.inc");
include ("$incpath/pub_mm_chk.inc");
include ("$incpath/pub_s_mail.inc");
include ("inc/jimu_mag_num.inc");

function set_global () {
	global $inc_path;

	include ("../inc/com_path.inc");
	$inc_path = $incpath;

}

function make_maglist($publisher_id,$owner_pswd) {
	global $inc_path;

	$sql = "SELECT * FROM M_MAGAZINE WHERE (mag_pub_status_flg = '01' or mag_pub_status_flg = '03') and ";
	$sql .= "mag_pub_stop_flg <> '02' and publisher_id = $publisher_id";
	$result = db_exec($sql);
	$nrow = pg_numrows($result);
	$lcount = 0;
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);
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
		$lcount++;
		$btn_name =  "inp_body";
		$fom_name =  "formsubmit" . sprintf("%03d", $lcount);
		$mag_nm = trim($mag_nm);				//2001.10.29
		$mag_nm = htmlspecialchars($mag_nm);	//2001.10.29
		$mag_nm = stripslashes($mag_nm);		//2001.10.29
		print "<form name=$fom_name action=\"./mag-haishin.php\" method=\"post\">\n";
		print "<tr>\n";
		print "<td width=\"294\">$mag_nm</td>\n";
		print "<td width=\"75\">\n";
		print "<div align=\"center\">\n";
		if ($mag_status_flg == '01') {
			print "休刊中";
		} else {
	  		print "<input type=\"submit\" name=$btn_name value=\"配信予約\">";
		}
	  	print "</div>\n";
		print "</td>\n";
		print "</tr>\n";
		print "<input type=\"hidden\" value=\"$publisher_id\" name=\"publisher_id\">\n";
		print "<input type=\"hidden\" value=\"$owner_pswd\" name=\"owner_pswd\">\n";
		print "<input type=\"hidden\" value=\"$mag_id\" name=\"mag_id\">\n";
//		print "<input type=\"hidden\" value=\"".trim($mag_nm)."\" name=\"mag_nm\">\n";
		print "<input type=\"hidden\" value=\"$mag_nm\" name=\"mag_nm\">\n";	//2001.10.29修正
		print "</form>\n";

//		if ($lcount > 10) { break; }
	}
}

function make_maglist_ed($publisher_id, $owner_pswd ,$s_mag, $s_date, $s_culm) {
	$sql = mm_make_sql ("SELECT_MAG_RES");
	$wk_pub_id = "'" . $publisher_id . "'";
	$sql = $sql . " and m_magazine.publisher_id = " . $wk_pub_id ;
	$result = db_exec($sql);
	$nrow = pg_numrows($result);

	if ($s_culm == "1"){ //メルマガ順-日付順
		if     ($s_mag=="A" && $s_date=="A"):
			$sql .= " order by t_pub_reserve.mag_id     , t_pub_reserve.resrv_hour     ";
		elseif ($s_mag=="A" && $s_date=="D"):
			$sql .= " order by t_pub_reserve.mag_id     , t_pub_reserve.resrv_hour desc";
		elseif ($s_mag=="D" && $s_date=="A"):
			$sql .= " order by t_pub_reserve.mag_id desc, t_pub_reserve.resrv_hour     ";
		elseif ($s_mag=="D" && $s_date=="D"):
			$sql .= " order by t_pub_reserve.mag_id desc, t_pub_reserve.resrv_hour desc";
		else:
			$sql .= " order by t_pub_reserve.mag_id     , t_pub_reserve.resrv_hour     ";
		endif;
	}else{      		 //日付順
		if ($s_date=="A")
			$sql .= " order by t_pub_reserve.resrv_hour ";
		else
			$sql .= " order by t_pub_reserve.resrv_hour desc";
	}
	/* echo $s_culm.$s_mag.$s_date; */
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);
		$mag_id = $fetch->mag_id;
		$mag_nm = $fetch->mag_nm;
		$index_id = $fetch->index_id;
		$resrv_hour_g = $fetch->resrv_hour_g;
		$mag_type = $fetch->mag_type;
		$mag_titl = $fetch->mag_titl;

		$lcount++;
		$btn_name =  "edit_reserv";
		$fom_name =  "formedit" . sprintf("%03d", $lcount);
		$w_dy = substr($resrv_hour_g, 0, 10);
		$w_ho = substr($resrv_hour_g, 11, 2);
		$w_mi = substr($resrv_hour_g, 13, 3);
		$w_hour = $w_dy . " " . $w_ho . $w_mi;

		$mag_nm = trim($mag_nm);				//2001.10.29
		$mag_nm = htmlspecialchars($mag_nm);	//2001.10.29
		$mag_nm = stripslashes($mag_nm);		//2001.10.29

		$bgcolor = ($i % 2) ? '#dddddd' : '#ffffff';

		print "<form name=$fom_name action=\"./mag-haishin.php\" method=\"post\">\n";
		print "<tr bgcolor='$bgcolor'>\n";
		print "<td width=\"300\">$mag_nm<br>／$mag_titl</td>\n";
		print "<td>$w_hour</td>\n";
		print "<td width=\"150\" align=\"center\">\n";
	  	print "<input type=\"submit\" name=$btn_name value=\"予約の修正／取り消し\">";
		print "</td>\n";
		print "</tr>\n";
		print "<input type=\"hidden\" value=\"$publisher_id\" name=\"publisher_id\">\n";
		print "<input type=\"hidden\" value=\"$owner_pswd\" name=\"owner_pswd\">\n";
		print "<input type=\"hidden\" value=\"$mag_id\" name=\"mag_id\">\n";
//		print "<input type=\"hidden\" value=\"".trim($mag_nm)."\" name=\"mag_nm\">\n";
		print "<input type=\"hidden\" value=\"$mag_nm\" name=\"mag_nm\">\n";	//2001.10.29修正
		print "<input type=\"hidden\" value=\"$index_id\" name=\"index_id\">\n";
		print "<input type=\"hidden\" value=\"$mag_type\" name=\"mag_type\">\n";
		print "</form>\n";

//		if ($lcount > 10) { break; }
	}
}

function make_date_box($type, $inp_date, $mag_type) {
	/* i_mode対応 */
	$timestamp = mktime(date("H"), date("i")+10, date("s"), date("m"), date("d"), date("Y")); 
	if ($mag_type == "02"){
		if ( date("H") > 22 ) $timestamp = mktime(7, 0, 0, date("m"), date("d")+1, date("Y"));
		if ( date("H") <  7 ) $timestamp = mktime(7, 0, 0, date("m"), date("d")  , date("Y"));
	}
	/* ---------- */
	if ($inp_date < 0) {
//		$inp_date = com_get_date($type);
		$inp_date = com_get_specify_date($type, $timestamp);
	}
	if ($type == "mon") {
		$imin = $inp_date - 1;
		if ($imin < 1) {$imin = 12;}
		$imax = $inp_date + 1;
		if ($imax > 12) {$imax = 1;}

		if ($inp_date != com_get_specify_date($type, $timestamp)) {
			print "<option value=\"$imin\">$imin</option>\n";
		}
		print "<option value=\"$inp_date\" selected>$inp_date</option>\n";
		if ($inp_date == com_get_specify_date($type, $timestamp)) {
			print "<option value=\"$imax\">$imax</option>\n";
		}
	}else {
		if ($type == "mday") {
			$imin = 1;
			$imax = 32;
		} elseif ($type == "hours_a") {
			if ($mag_type != "02"){ /* i_mode対応 */
				$imin = 0;	$imax = 24;
			}else{
				$imin = 7;	$imax = 23;
			}
		}
		for ($i=$imin; $i<$imax; $i++) {
			if ($i == $inp_date) {
				print "<option value=\"$i\" selected>$i</option>\n";
			} else {
				print "<option value=\"$i\">$i</option>\n";
			}
		}
	}
}

function make_minutes10_box($inp_date, $mag_type) {
	/* i_mode対応 */
	$timestamp = mktime(date("H"), date("i")+10, date("s"), date("m"), date("d"), date("Y")); 
	if ($mag_type == "02"){
		if ( date("H", $timestamp) > 22 ) $timestamp = mktime(7, 0, 0, date("m"), date("d")+1, date("Y"));
		if ( date("H", $timestamp) <  7 ) $timestamp = mktime(7, 0, 0, date("m"), date("d")  , date("Y"));
	}
	/* ---------- */
	if ($inp_date < 0) {
//		$inp_date = com_get_date ("minutes_10");
		$inp_date = com_get_specify_date("minutes_10", $timestamp);
	}
	for ($i = 0; $i<6; $i++) {
		$sval = sprintf("%02d", $i * 10);
		if ($i * 10 == $inp_date) {
			print "<option value=\"$sval\" selected>$sval</option>\n";
		} else {
			print "<option value=\"$sval\">$sval</option>\n";
		}
	}
}

function make_ampm_box($am) {
	if ($am == "0") {
		$am = date("A");
	}
	if ($am == "AM") {
		print "<option value=\"AM\" selected>AM</option>\n";
		print "<option value=\"PM\">PM</option>\n";
	} else {
		print "<option value=\"AM\">AM</option>\n";
		print "<option value=\"PM\" selected>PM</option>\n";
	}
}

function yoyak_header($save_flg) {
	if ($save_flg == "save") {
		print "<form name=\"form1\" action=\"./pub_index.php\" method=\"post\">\n";
		print "配信予約を受け付けました";
	} else {
		print "<form name=\"form1\" action=\"./mag-haishin.php\" method=\"post\">\n";
		print "下記の内容で予約しますか？";
	}
}

function yoyak_footer($save_flg, $publisher_id, $index_id, $mag_id, $mag_nm, $body_id,
			$resrv_month, $resrv_day, $resrv_hour_ampm, $resrv_hour_h, $resrv_hour_m ,$owner_pswd) {

	if ($save_flg == "save") {
		if ($publisher_id == 0) {
			print "</form>\n";
			print "<form name=\"form2\" action=\"../jimukyoku/jm001.php\" method=\"post\">\n";
			print "<p><a href=\"JavaScript:document.form2.submit();\">事務局メニューへ戻る
				</a></p>\n";
			print "<input type=\"hidden\" value=1 name=\"admin_id\">\n";
			print "<input type=\"hidden\" value=\"$owner_pswd\" name=\"admin_pswd\">\n";
			//あくまでも仮
 			//print "<input type=\"hidden\" value=\"sugoizo\" name=\"admin_pswd\">\n";
			print "<input type=\"hidden\" value=\"RetIndex\" name=\"job_flg\">\n";
			print "</form>\n";
		} else {
			print "<p><a href=\"JavaScript:document.form1.submit();\">発行者用管理ページへ戻る
				</a></p>\n";
			print "<input type=\"hidden\" value=\"$owner_pswd\" name=\"owner_pswd_r\">\n";
			print "<input type=\"hidden\" value=\"$publisher_id\" name=\"publisher_id\">\n";
			print "<input type=\"hidden\" value=\"$owner_pswd\" name=\"owner_pswd\">\n";
			print "</form>\n";
		}
	} else {
		print "<p><input type=\"submit\" name=\"submit_save\" value=\"配信予約する\">\n";
		print "<input type=\"button\" name=\"Submitbtn\" value=\"戻る\"
			onClick=\"history.back()\"></p>\n";
		print "<input type=\"hidden\" value=\"$index_id\" name=\"index_id\">\n";
		print "<input type=\"hidden\" value=\"$publisher_id\" name=\"publisher_id\">\n";
		print "<input type=\"hidden\" value=\"$owner_pswd\" name=\"owner_pswd\">\n";
		print "<input type=\"hidden\" value=\"$mag_id\" name=\"mag_id\">\n";
		print "<input type=\"hidden\" value=\"$mag_nm\" name=\"mag_nm\">\n";
		print "<input type=\"hidden\" value=\"$body_id\" name=\"body_id\">\n";
		print "<input type=\"hidden\" value=\"$resrv_month\" name=\"resrv_month\">\n";
		print "<input type=\"hidden\" value=\"$resrv_day\" name=\"resrv_day\">\n";
		print "<input type=\"hidden\" value=\"$resrv_hour_ampm\" name=\"resrv_hour_ampm\">\n";
		print "<input type=\"hidden\" value=\"$resrv_hour_h\" name=\"resrv_hour_h\">\n";
		print "<input type=\"hidden\" value=\"$resrv_hour_m\" name=\"resrv_hour_m\">\n";
		print "<input type=\"hidden\" value=\"0\" name=\"resrv_flg\">\n";
//		print "<input type=\"hidden\" value=\"$pub_dt\" name=\"pub_dt\">\n";
		print "</form>\n";
	}
}

function msg_print($msg) {
	if ($msg == OK) {
//		$msg = "テスト配信を行ないました。";
	}
	print $msg;
}

function change_date($resrv_month, $resrv_day) {
	$now_yy = com_chg_year((int)$resrv_month, (int)$resrv_day);
	$day = (int)$resrv_day;
	while (!checkdate((int)$resrv_month, $day, $now_yy)) {
		$day--;
	}
	return (string)$day;
}

function make_resrv_date($resrv_month, $resrv_day, $resrv_hour_h, $resrv_hour_m) {
	$now_yy = com_chg_year((int)$resrv_month, (int)$resrv_day);
	$wk_hour = (int)$resrv_hour_h;
	$hour_ora = sprintf("%04d-%02d-%02d %02d:%02d", $now_yy, (int)$resrv_month,
				    (int)$resrv_day, $wk_hour, (int)$resrv_hour_m);
	return $hour_ora;
}

function sel_mag_template($conn, $mag_id) {
	global $mag_titl, $mag_body, $ins_volno, $ins_date, $mag_type;

	$mag_titl = $mag_body = "";
	$sql = "SELECT mag_from_addr, mag_subject_tmpl, mag_header_tmpl, mag_footer_tmpl, "
		 . "       coalesce(mag_volnum_ins_flg, '0') as ins_volno, coalesce(mag_date_ins_flg,'0') as ins_date, mag_type "
		 . "  FROM m_magazine WHERE mag_id = '$mag_id' ";
	$result = db_exec($sql);

	if( db_exec($sql) == 0 ) return;
	if( !pg_numrows($result) ) return;
	$fetch = pg_fetch_object($result, 0);
	$mag_from_addr = $fetch->mag_from_addr;
	$mag_subject_tmpl = $fetch->mag_subject_tmpl;
	$mag_header_tmpl = $fetch->mag_header_tmpl;
	$mag_footer_tmpl = $fetch->mag_footer_tmpl;
	$ins_volno = $fetch->ins_volno;
	$ins_date = $fetch->ins_date;
	$mag_type = $fetch->mag_type;

	$mag_titl = stripslashes($mag_subject_tmpl);
	$mag_titl = htmlspecialchars($mag_subject_tmpl);
	$mag_body = $mag_header_tmpl . "\n\n\n\n\n\n\n\n\n\n" . $mag_footer_tmpl;
	$mag_body = trim($mag_body);  // テンプレートがない場合の対応
	/* サブジェクト挿入オプション設定 */
	if ( $ins_volno == "1" ) {
		if ( strpos( $mag_titl, "@volno@" ) === false ){
			$mag_titl = $mag_titl . " @volno@";
		}
	}else{
		if ( strpos( $mag_titl, "@volno@" ) === false ){
		}else{
			$ins_volno == "1";
		}
	}
	
	if ( $ins_date == "1" ) {
		if ( strpos( $mag_titl, "@date@" ) === false ){
			$mag_titl = $mag_titl . " @date@";
		}
	}else{
		if ( strpos( $mag_titl, "@date@" ) === false ){
		}else{
			$ins_date = 1;
		}
	}
	/* ----------------------------- */
//	echo "ins_volno=" . $ins_volno ."<br>" . "ins_date=" . $ins_date ."<br>";
}

function upd_option_ins($conn, $mag_id, $mag_titl ) {

	if ( strpos( $mag_titl, "@volno@" ) === false )
		$ins_volno = "0";
	else
		$ins_volno = "1";

	if ( strpos( $mag_titl, "@date@" ) === false )
		$ins_date = "0";
	else
		$ins_date = "1";
//	echo "mag_id=" . $mag_id ."<br>"."ins_volno=". $ins_volno ."<br>"."ins_date=". $ins_date ."<br>";

	$sql = "update M_MAGAZINE set MAG_VOLNUM_INS_FLG = '$ins_volno', MAG_DATE_INS_FLG = '$ins_date' "
		 . " where  mag_id = '$mag_id' ";
	db_exec($sql);
}
/*======================================================================================*/
/*	submit Magazine																		*/
/*	(main)																				*/
/*======================================================================================*/
//	global $mag_id, $body_id, $mag_titl, $mag_body;
//	global $mag_id, $body_id, $resrv_month, $resrv_day, $resrv_hour, $resrv_flg;
set_global ();

pub_passchk($conn, $publisher_id,$owner_pswd);

if ($btn_snd_menu) {					/* 配信メニュー */
	include ("mag-haishin.html");
/* ------------------------------------------------------------ */
/*	メルマガ入力画面作成
/* ------------------------------------------------------------ */
} elseif ($inp_body) {
	db_begin_trans();
	if (($index_id = update_squ_key ("T_BODY")) != "-1") {
		db_commit_trans();
		$body_id = pub_mm_make_bodyid ($conn, $mag_id);
		$access_day = date("Ymd",time());
		if ($mag_id == $bakyun_id) {
			$sql = "select admin_msg from t_mag_footer where bakyun_footer = 1 and access_day = '$access_day'";
		} elseif ($mag_id == $letter_id) {
			$sql = "select admin_msg from t_mag_footer where letter_footer = 1 and access_day = '$access_day'";
		} else {
			$sql = "select admin_msg from t_mag_footer where user_footer = 1 and access_day = '$access_day'";
		}
		$result = db_exec($sql);
		$nrow = pg_numrows($result);
		if ($nrow == 0) {
			$admin_msg = "--本日はありません--";
		} else {
			$fetch = pg_fetch_object($result, $i);
			$admin_msg = $fetch->admin_msg;
			$admin_msg = ereg_replace("\n","<br>",$admin_msg);
		}
		sel_mag_template($conn, $mag_id);
		$mag_nm = htmlspecialchars($mag_nm);	//2001.10.29
		$mag_nm = stripslashes($mag_nm);		//2001.10.29
		include ("mag-yoyaku.html");
	} else {
		db_rollback();
		err_msg ("PM003", "メルマガ配信入力");
	}
/* ------------------------------------------------------------ */
/*	テスト配信 (from mag_yoyaku.html)							*/
/* ------------------------------------------------------------ */
} elseif ($TestSubmit) {					/* テスト配信 */
	if (($msg = mm_chk_tbody($mag_titl, $mag_body, $mag_type)) == "OK") {
		$msg = "テスト配信を行ないました。";
//		$snd_ok = FALSE; 						2001.10.26削除
//		$conn = db_conxxxt();
//		$mag_titl = stripslashes($mag_titl); 	2001.10.26削除
/* 												2001.10.23削除
		if (pub_tb_insert ($conn, $index_id, $mag_id, $body_id, $mag_titl,
									$mag_body) ) {
			OCICommit($conn);
//			include ("mag-testpublish.html");	位置変更 
			$snd_ok = TRUE;
		} else {
			OCIRollback($conn);
			err_msg ("PM004", "メルマガテスト配信");
		}
*/
//		if ($snd_ok) {							2001.10.26削除

/*			$sql = "SELECT * FROM M_MAGAZINE WHERE mag_id = '" . $mag_id . "'";
			$stmt = OCIParse($conn, $sql);
			$ret01 = OCIDefinebyname($stmt, "MAIL_ADD_PUBLISH", &$mail_add_publish);
			$ret01 = OCIDefinebyname($stmt, "MAG_NM", &$mag_nm); */
			/* 2001.06.14(修正) テスト配信は基本メールアドレスへ送信 */
			/* 2001.07.24(修正) フロムアドレスのユーザ指定対応 */
			/* 2001.07.26(修正) 日付・発行号挿入対応 */
			$sql = "SELECT M.mag_nm as MAG_NM, M.mag_from_addr, coalesce(M.mag_volume, 0) as mag_volno, "
				 . "       P.mail_add_main as MAIL_ADD_MAIN "
				 . "  FROM M_MAGAZINE M, M_PUBLISHER P "
				 . " WHERE mag_id = '" . $mag_id . "' and M.publisher_id = P.publisher_id" ;
			$result = db_exec($sql);

			if (pg_numrows($result)) {
				$fetch = pg_fetch_object($result, 0);
				$mag_nm = $fetch->mag_nm;
				$mag_from_addr = $fetch->mag_from_addr;
				$mag_volno = $fetch->mag_volno;
				$mail_add_publish = $fetch->mail_add_main;
				$titl_wk = $mag_titl;
				$titl_wk = str_replace("@volno@", $mag_volno, $titl_wk);
				$titl_wk = str_replace("@date@", strftime("%Y.%m.%d ",time()), $titl_wk);
				$titl_wk = stripslashes($titl_wk);
				mag_test_mail($mail_add_publish, $mag_nm, $titl_wk, $mag_body, $mag_from_addr);
			}
			$mag_nm = htmlspecialchars($mag_nm);		//2001.10.29
			$mag_nm = stripslashes($mag_nm);			//2001.10.29
			$mag_body = stripslashes($mag_body);		//2001.10.26
			$mag_body = htmlspecialchars($mag_body);	//2001.10.26
			$mag_titl = stripslashes($mag_titl);		//2001.10.26
			$mag_titl = htmlspecialchars($mag_titl);	//2001.10.26
			include ("mag-testpublish.html");
//		}										2001.10.26削除
	} else {
		$p_titl = "メルマガテスト配信";
		$print_msg =  $msg;
		include ("err-msg.html");
	}
/* ------------------------------------------------------------ */
/*	テスト配信からの戻り										*/
/* ------------------------------------------------------------ */
} elseif ($OK_reserv) {				/* 本番配信画面表示 */
//	$conn = db_conxxxt();
/* -------- 2001.10.23削除
	$sql = "select * from t_body where index_id = $index_id";
	$stmt = OCIParse($conn, $sql);
	OCIDefinebyname($stmt, "MAG_ID", &$mag_id);
	OCIDefinebyname($stmt, "BODY_ID", &$body_id);
	OCIDefinebyname($stmt, "MAG_TITL", &$mag_titl);
	OCIDefinebyname($stmt, "MAG_BODY", &$mag_body);
	$ncols = OCIExecute($stmt, OCI_DEFAULT);
	OCIFetch($stmt);
------------------------- */
/*		$sql = "select * from t_pub_reserve where index_id = $index_id";
	$stmt = OCIParse($conn, $sql);
	OCIDefinebyname($stmt, "RESRV_MONTH", &$resrv_month);
	OCIDefinebyname($stmt, "RESRV_DAY", &$resrv_day);
	OCIDefinebyname($stmt, "RESRV_HOUR_A", &$resrv_hour_a);
	OCIDefinebyname($stmt, "RESRV_HOUR_H", &$resrv_hour_h);
	OCIDefinebyname($stmt, "RESRV_HOUR_M", &$resrv_hour_m);
	OCIDefinebyname($stmt, "RESRV_FLG", &$g_resrv_flg);
	$ncols = OCIExecute($stmt, OCI_DEFAULT);
	OCIFetch($stmt);

	$resrv_hour_h = $resrv_hour_a;
	$resrv_hour_ampm = "AM";
	if ($resrv_hour_a > 12) {
		$resrv_hour_ampm = "PM";
	}
*/
	$mag_nm = htmlspecialchars($mag_nm);	//2001.10.29
	$mag_nm = stripslashes($mag_nm);		//2001.10.29
	$mag_body = stripslashes($mag_body);	//2001.10.26
	$mag_body = htmlspecialchars($mag_body);//2001.10.26
	$mag_titl = stripslashes($mag_titl);	//2001.10.26
	$mag_titl = htmlspecialchars($mag_titl);
	if ( strpos( $mag_titl, "@volno@" ) !== false ) $ins_volno = "1";
	if ( strpos( $mag_titl, "@date@" ) !== false ) $ins_date = "1";

	$access_day = date("Ymd",time());
	if ($mag_id == $bakyun_id) {
		$sql = "select admin_msg from t_mag_footer where bakyun_footer = 1 and access_day = '$access_day'";
	} elseif ($mag_id == $letter_id) {
		$sql = "select admin_msg from t_mag_footer where letter_footer = 1 and access_day = '$access_day'";
	} else {
		$sql = "select admin_msg from t_mag_footer where user_footer = 1 and access_day = '$access_day'";
	}
	$result = db_exec($sql);
	if ($ncols = pg_numrows($result)) {
		$fetch = pg_fetch_object($result, 0);
		$admin_msg = $fetch->admin_msg;
	}
	if ($ncols == 0) {$admin_msg = "--本日はありません--"; }
	$admin_msg = ereg_replace("\n","<br>",$admin_msg);

	include ("mag-yoyaku.html");
/* ------------------------------------------------------------ */
/*	本番配信予約 (from mag_yoyaku.html)							*/
/* ------------------------------------------------------------ */
} elseif ($OKSubmit) {

	$mag_nm = htmlspecialchars($mag_nm);	//2001.10.29
	$mag_nm = stripslashes($mag_nm);		//2001.10.29
	
	$save_flg = "vew";
	if (($msg = mm_chk_tbody( $mag_titl, $mag_body, $mag_type )) != "OK") {
		$p_titl = "メルマガ配信予約"; $print_msg = $msg;
		include ("err-msg.html");
//		db_logoff();
		exit();
	}
	$nrec = pub_tb_chkrec ($conn, $index_id);
	if ( !$nrec ) {
		/* テスト配信なしの場合 --> T_BODYへレコード追加 */
//		$mag_titl = stripslashes($mag_titl);  // 2002.02.19 削除
		db_begin_trans();
		if ( pub_tb_insert($conn, $index_id, $mag_id, $body_id, $mag_titl, $mag_body) ) {
			db_commit_trans();
		} else {
			db_rollback();
			err_msg ("PM004", "メルマガ配信予約");
			exit();
		}
	}

	$now_yy = com_chg_year((int)$resrv_month, (int)$resrv_day);
	$day = (int)$resrv_day;
	if (date("U") > date("U",mktime((int)$resrv_hour_h,
		(int)$resrv_hour_m,0,(int)$resrv_month, $day, $now_yy))) {
		$p_titl = "メルマガ配信予約";
		$w_msg = sprintf("%d年%d月%d日 %d時%d分",
			 $now_yy, (int)$resrv_month, $day,
			 (int)$resrv_hour_h, (int)$resrv_hour_m);
		$print_msg =  $w_msg . $em_haishin_1;
		include ("err-msg.html");
	} elseif (!checkdate((int)$resrv_month, $day, $now_yy)) {
		$p_titl = "メルマガ配信予約";
		$w_msg = sprintf("%d年%d月%d日", $now_yy, (int)$resrv_month, $day);
		$print_msg =  $w_msg . $em_haishin_2;
		include ("err-msg.html");
	} else {
		upd_option_ins($conn, $mag_id, $mag_titl ) ;
		$resrv_day = change_date($resrv_month, $resrv_day);
		include ("mag-yoyaku-kakunin.html");
	}
/* ------------------------------------------------------------ */
/*																*/
/* ------------------------------------------------------------ */
} elseif ($submit_save) {				/* 配信登録 */

	$mag_nm = htmlspecialchars($mag_nm);	//2001.10.29
	$mag_nm = stripslashes($mag_nm);		//2001.10.29
	
	$save_flg = "save";
	$hour_ora = make_resrv_date($resrv_month, $resrv_day,
							$resrv_hour_h, $resrv_hour_m);

	db_begin_trans();
	if (pub_tr_insert ($conn, $index_id, $mag_id, $body_id, $resrv_month,
							$resrv_day, $hour_ora, 0)) {
		db_commit_trans();
		include ("mag-yoyaku-kakunin.html");
	} else {
		db_rollback();
		err_msg ("PM005", "メルマガ配信登録");
	}
/* ------------------------------------------------------------ */
/*	配信修正の最初の画面（現状データ表示）						*/
/* ------------------------------------------------------------ */
} elseif ($edit_reserv) {				/* 配信修正 */

	$mag_nm = htmlspecialchars($mag_nm);	//2001.10.29
	$mag_nm = stripslashes($mag_nm);		//2001.10.29
	
	$save_flg = "edit";

	pub_tb_load ($conn, $index_id);
	pub_tr_load ($conn, $index_id);
	$mag_id = $g_mag_id; $body_id = $g_body_id; $mag_titl = $g_mag_titl;
	$mag_body = $g_mag_body;
	$resrv_month = $g_resrv_month; $resrv_day = $g_resrv_day;
	$resrv_hour_h = $g_resrv_hour_h; $resrv_hour_m = $g_resrv_hour_m;
	$resrv_flg = $g_resrv_flg;

		$access_day = date("Ymd",time());
		if ($mag_id == $bakyun_id) {
			$sql = "select admin_msg from t_mag_footer where bakyun_footer = 1 and access_day = '$access_day'";
		} elseif ($mag_id == $letter_id) {
			$sql = "select admin_msg from t_mag_footer where letter_footer = 1 and access_day = '$access_day'";
		} else {
			$sql = "select admin_msg from t_mag_footer where user_footer = 1 and access_day = '$access_day'";
		}
		$result = db_exec($sql);
		if ($ncols = pg_numrows($result)) {
			$fetch = pg_fetch_object($result, 0);
			$admin_msg = $fetch->admin_msg;
		}
		if ($ncols == 0) {$admin_msg = "--本日はありません--"; }
		$admin_msg = ereg_replace("\n","<br>",$admin_msg);

	$mag_body = ereg_replace("<br>","\n",$mag_body);
	//$mag_body = stripslashes($mag_body);
//	$mag_titl = stripslashes($mag_titl);		// 2002.02.19 削除
	$mag_titl = htmlspecialchars($mag_titl);
	include ("mag-yoyaku-syuusei.html");
/* ------------------------------------------------------------ */
/*	修正確認画面表示 （from mag-yoyaku-syuusei.html）			*/
/* ------------------------------------------------------------ */
} elseif ($edit_reserv_ok) {

	$mag_nm = htmlspecialchars($mag_nm);	//2001.10.29
	$mag_nm = stripslashes($mag_nm);		//2001.10.29
	
	$save_flg = "edit_ok";
//	echo "type=".$mag_type;
	if (($msg = mm_chk_tbody($mag_titl, $mag_body, $mag_type)) != "OK") {
		$p_titl = "メルマガ配信予約修正";
		$print_msg =  $msg;
		include ("err-msg.html");
		exit();
	}
	$now_yy = com_chg_year((int)$resrv_month, (int)$resrv_day);
	$day = (int)$resrv_day;
	if (date("U") > date("U",mktime((int)$resrv_hour_h,
			(int)$resrv_hour_m,0,(int)$resrv_month, $day, $now_yy))) {
		$p_titl = "メルマガ配信予約修正";
			$w_msg = sprintf("%d年%d月%d日 %d時%d分",
				 $now_yy, (int)$resrv_month, $day,
				 (int)$resrv_hour_h, (int)$resrv_hour_m);
		$print_msg =  $w_msg . $em_haishin_1;
		include ("err-msg.html");
	} elseif (!checkdate((int)$resrv_month, $day, $now_yy)) {
		$p_titl = "メルマガ配信予約修正";
		$w_msg = sprintf("%d年%d月%d日", $now_yy, (int)$resrv_month, $day);
		$print_msg =  $w_msg . $em_haishin_2;
		include ("err-msg.html");
	} else {
		$resrv_day = change_date($resrv_month, $resrv_day);
		$mag_titl = htmlspecialchars($mag_titl);
		//$mag_body = stripslashes($mag_body);
		//$mag_titl = stripslashes($mag_titl);
		//$mag_body = ereg_replace("\n","<br>",$mag_body);
		include ("mag-syuusei-kakunin.html");
	}
/* ------------------------------------------------------------ */
/*																*/
/* ------------------------------------------------------------ */
} elseif ($edit_reserv_can) {				/* 配信削除確認 */

	$mag_nm = htmlspecialchars($mag_nm);	//2001.10.29
	$mag_nm = stripslashes($mag_nm);		//2001.10.29
	
	$save_flg = "delete_ok";
	$mag_body = stripslashes($mag_body);
	$mag_titl = stripslashes($mag_titl);
	$mag_titl = htmlspecialchars($mag_titl);
	$mag_body = htmlspecialchars($mag_body);

	include ("mag-torikeshi-kakunin.html");
/* ------------------------------------------------------------ */
/*																*/
/* ------------------------------------------------------------ */
} elseif ($edit_reserv_updt) {				/* 配信修正登録 */

	$mag_nm = htmlspecialchars($mag_nm);	//2001.10.29
	$mag_nm = stripslashes($mag_nm);		//2001.10.29
	
	$save_flg = "reserv_update";
	$hour_ora = make_resrv_date($resrv_month, $resrv_day,
							$resrv_hour_h, $resrv_hour_m);

	//$mag_body = stripslashes($mag_body);
//	$mag_titl = stripslashes($mag_titl);	//2002.02.19 削除

	db_begin_trans();
	if (($l_ret = pub_tb_insert ($conn, $index_id, $mag_id, $body_id, $mag_titl,
									 $mag_body))) {
		$l_ret = pub_tr_insert ($conn, $index_id, $mag_id, $body_id, $resrv_month,
								$resrv_day, $hour_ora, 0);
	}
	if ($l_ret) {
		db_commit_trans();
		include ("mag-syuusei-ok.html");
	} else {
		db_rollback();
		err_msg ("PM006", "メルマガ配信修正");
	}

/* ------------------------------------------------------------ */
/*																*/
/* ------------------------------------------------------------ */
} elseif ($edit_reserv_del) {				/* 配信削除 */

	$mag_nm = htmlspecialchars($mag_nm);	//2001.10.29
	$mag_nm = stripslashes($mag_nm);		//2001.10.29
	
	$save_flg = "reserv_delete";

	db_begin_trans();
	if (($l_ret = pub_tb_delete ($conn, $index_id))) {
		$l_ret = pub_tr_delete ($conn, $index_id);
	}
	if ($l_ret) {
		db_commit_trans();
		$mag_body = stripslashes($mag_body);
		$mag_titl = stripslashes($mag_titl);
		$mag_titl = htmlspecialchars($mag_titl);
		$mag_body = htmlspecialchars($mag_body);
		include ("mag-torikeshi-ok.html");
	} else {
		db_rollback();
		$p_titl = "メルマガ配信取り消し";
		$print_msg =  "「" . $mag_nm . "」".$em_haishin_4;
		include ("err-msg.html");
//			err_msg ("PM007", "メルマガ配信取り消し");
	}

/* ------------------------------------------------------------ */
/*	すぐに配信 -- 現在時間で予約(from mag_yoyaku.html)			*/
/* ------------------------------------------------------------ */
} elseif ($OKSubmitNow) {
	
	$mag_nm = htmlspecialchars($mag_nm);	//2001.10.29
	$mag_nm = stripslashes($mag_nm);		//2001.10.29

	/* 配信データ登録（テスト配信部） */
	if (($msg = mm_chk_tbody($mag_titl, $mag_body, $mag_type)) != "OK") {
		$p_titl = "メルマガ配信"; $print_msg =  $msg;
		include ("err-msg.html");
		exit();
	}
//	$mag_titl = stripslashes($mag_titl); // 2002.02.19 削除
	db_begin_trans();
	if (pub_tb_insert ($conn, $index_id, $mag_id, $body_id, $mag_titl, $mag_body) ) {
		db_commit_trans();
		$sql = "SELECT * FROM M_MAGAZINE WHERE mag_id = '" . $mag_id . "'";
		$result = db_exec($sql);
		if (pg_numrows($result)) {
			$fetch = pg_fetch_object($result, 0);
			$mail_add_publish = $fetch->mail_add_publish;
			$mag_nm = $fetch->mag_nm;
		}
	} else {
		db_rollback();
		err_msg ("PM004", "メルマガ配信準備");
		exit();
	}

	/* 配信確認画面 （配信予約部）*/
	$save_flg = "vew";
	$nrec = pub_tb_chkrec ($conn, $index_id);
	if ( $nrec ) {
		$resrv_month = (int)strftime("%m", time());
		$resrv_day = (int)strftime("%d", time());
		$resrv_hour_h = (int)strftime("%H", time());
		$resrv_hour_m = (int)strftime("%M", time());
		upd_option_ins($conn, $mag_id, $mag_titl ) ;
		include ("mag-yoyaku-kakunin.html");
	} else {
		$msg = $em_haishin_3;
		include ("mag-testpublish.html");
	}
}
?>
