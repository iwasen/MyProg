<?php
include ("inc/pub_passchk.inc");
include ("../inc/com_path.inc");
include ("$compath/err_msg.inc");
include ("$compath/com_func.inc");
include ("$compath/com_db_func.inc");
include ("$compath/sql_mm.inc");
include ("$incpath/pub_mm_dbacces.inc");
include ("$incpath/pub_mm_chk.inc");
include ("$incpath/pub_s_mail.inc");

function set_global () {
	global $inc_path;

	include ("../inc/com_path.inc");
	$inc_path = $incpath;
}

function make_maglist($publisher_id,$owner_pswd) {
	global $inc_path;

	$conn = db_conxxxt();
	$sql = "SELECT * FROM M_MAGAZINE WHERE (mag_pub_status_flg = '01' or mag_pub_status_flg = '03') ";
	$sql .= " and (mag_pub_stop_flg <> '02') and (publisher_id = :PUBLISHER_ID)";
	$stmt = OCIParse($conn, $sql);
	$ret01 = OCIbindbyname ($stmt, ":PUBLISHER_ID", &$publisher_id, -1);
	include("$inc_path/pub_mm_defmane.def");
	$ncols = OCIExecute($stmt);
	$lcount = 0;
	while (OCIFetch($stmt)) {
		$lcount++;
		$btn_name =  "inp_body";
		$fom_name =  "formsubmit" . sprintf("%03d", $lcount);
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
		print "<input type=\"hidden\" value=\"".trim($mag_nm)."\" name=\"mag_nm\">\n";
		print "</form>\n";

//		if ($lcount > 10) { break; }
	}
	db_logoff();
}

function make_maglist_ed($publisher_id,$owner_pswd) {
	$conn = db_conxxxt();
	$sql = mm_make_sql ("SELECT_MAG_RES");
	$wk_pub_id = "'" . $publisher_id . "'";
	$sql = $sql . " and m_magazine.publisher_id = " . $wk_pub_id;
	$stmt = OCIParse($conn, $sql);
	$ret01 = OCIDefinebyname($stmt, "MAG_ID", &$mag_id);
	$ret01 = OCIDefinebyname($stmt, "MAG_NM", &$mag_nm);
	$ret01 = OCIDefinebyname($stmt, "INDEX_ID", &$index_id);
	$ret01 = OCIDefinebyname($stmt, "RESRV_HOUR_G", &$resrv_hour_g);
	$ncols = OCIExecute($stmt);
	$lcount = 0;
	while (OCIFetch($stmt)) {
		$lcount++;
		$btn_name =  "edit_reserv";
		$fom_name =  "formedit" . sprintf("%03d", $lcount);
		$w_dy = substr($resrv_hour_g, 0, 10);
		$w_ho = substr($resrv_hour_g, 11, 2);
		$w_mi = substr($resrv_hour_g, 13, 3);
		$w_hour = $w_dy . " " . $w_ho . $w_mi;
		print "<form name=$fom_name action=\"./mag-haishin.php\" method=\"post\">\n";
		print "<tr>\n";
		print "<td width=\"300\">$mag_nm</td>\n";
		print "<td>$w_hour</td>\n";
		print "<td width=\"150\" align=\"center\">\n";
	  	print "<input type=\"submit\" name=$btn_name value=\"予約の修正／取り消し\">";
		print "</td>\n";
		print "</tr>\n";
		print "<input type=\"hidden\" value=\"$publisher_id\" name=\"publisher_id\">\n";
		print "<input type=\"hidden\" value=\"$owner_pswd\" name=\"owner_pswd\">\n";
		print "<input type=\"hidden\" value=\"$mag_id\" name=\"mag_id\">\n";
		print "<input type=\"hidden\" value=\"$mag_nm\" name=\"mag_nm\">\n";
		print "<input type=\"hidden\" value=\"$index_id\" name=\"index_id\">\n";
		print "</form>\n";

//		if ($lcount > 10) { break; }
	}
	db_logoff();
}

function make_date_box($type, $inp_date) {
	if ($inp_date < 0) {
		$inp_date = com_get_date($type);
	}
	if ($type == "mon") {
		$imin = $inp_date - 1;
		if ($imin < 1) {$imin = 12;}
		$imax = $inp_date + 1;
		if ($imax > 12) {$imax = 1;}
		if ($inp_date != com_get_date($type)) {
			print "<option value=\"$imin\">$imin</option>\n";
		}
		print "<option value=\"$inp_date\" selected>$inp_date</option>\n";
		if ($inp_date == com_get_date($type)) {
			print "<option value=\"$imax\">$imax</option>\n";
		}
	}else {
		if ($type == "mday") {
			$imin = 1;
			$imax = 32;
		} elseif ($type == "hours_a") {
			$imin = 0;
			$imax = 24;
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

function make_minutes10_box($inp_date) {
	if ($inp_date < 0) {
		$inp_date = com_get_date ("minutes_10");
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
			//print "<input type=\"hidden\" value=\"$owner_pswd\" name=\"admin_pswd\">\n";
			//あくまでも仮
 			print "<input type=\"hidden\" value=\"$admin_pswd\" name=\"admin_pswd\">\n";
			print "<input type=\"hidden\" value=\"RetIndex\" name=\"job_flg\">\n";
			print "</form>\n";
		} else {
			print "<p><a href=\"JavaScript:document.form1.submit();\">発行者専用ページへ戻る
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
//		print "<input type=\"hidden\" value=$pub_dt name=\"pub_dt\">\n";
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

/*======================================================================================*/
/*	submit Magazine									*/
/*	(main)										*/
/*======================================================================================*/
//	global $mag_id, $body_id, $mag_titl, $mag_body;
//	global $mag_id, $body_id, $resrv_month, $resrv_day, $resrv_hour, $resrv_flg;
set_global ();
$conn = db_conxxxt();
pub_passchk($conn, $publisher_id,$owner_pswd);
db_logoff();

if ($btn_snd_menu) {					/* 配信メニュー */
	include ("mag-haishin.html");
} elseif ($inp_body) {					/* 配信入力 */
	$conn = db_conxxxt();
	if (($index_id = update_squ_key ($conn, "T_BODY")) != "-1") {
		OCICommit($conn);
		$body_id = pub_mm_make_bodyid ($conn, $mag_id);
		$access_day = date("Ymd",time());
		$sql = "select admin_msg from t_mag_footer where riba_footer = 1 and access_day = '$access_day'";
		$stmt = OCIParse($conn, $sql);
		$ret01 = OCIDefinebyname($stmt, "ADMIN_MSG", &$admin_msg);
		OCIExecute($stmt);
		$ncols = OCIFetchStatement($stmt,&$resarray);
		if ($ncols == 0) {$admin_msg = "--本日はありません--"; }
		$admin_msg = ereg_replace("\n","<br>",$admin_msg);
		include ("mag-yoyaku.html");
		OCIFreeStatement($stmt);
	} else {
		OCIRollback($conn);
		err_msg ("PM003", "メルマガ配信入力");
	}
	db_logoff();
} elseif ($TestSubmit) {					/* テスト配信 */
		if (strlen($mag_titl) > 80) {
			$p_titl = "メルマガテスト配信";
			$print_msg = "タイトルが長すぎます";
			include ("err-msg.html");
			exit();
		}

		if (strlen($mag_body) > 50000) {
			$p_titl = "メルマガテスト配信";
			$print_msg = "本文が長すぎます";
			include ("err-msg.html");
			exit();
		}

	if (($msg = mm_chk_tbody($mag_titl, $mag_body)) == "OK") {
		$msg = "テスト配信を行ないました。";
		$snd_ok = FALSE;
		$conn = db_conxxxt();
		if (pub_tb_insert ($conn, $index_id, $mag_id, $body_id, $mag_titl,
									$mag_body) ) {
			OCICommit($conn);
			include ("mag-testpublish.html");
			$snd_ok = TRUE;
		} else {
			OCIRollback($conn);
			err_msg ("PM004", "メルマガテスト配信");
		}
		if ($snd_ok) {
			$sql = "SELECT * FROM M_MAGAZINE WHERE mag_id = '" . $mag_id . "'";
			$stmt = OCIParse($conn, $sql);
			$ret01 = OCIDefinebyname($stmt, "MAIL_ADD_PUBLISH", &$mail_add_publish);
			$ret01 = OCIDefinebyname($stmt, "MAG_NM", &$mag_nm);
			$ncols = OCIExecute($stmt);
			if (OCIFetch($stmt)) {
				$mag_titl = stripslashes($mag_titl);
				mag_test_mail($mail_add_publish, $mag_nm, $mag_titl, $mag_body);
			}
		}
		db_logoff();
	} else {
		$p_titl = "メルマガテスト配信";
		$print_msg =  $msg;
		include ("err-msg.html");
	}
} elseif ($OK_reserv) {				/* 本番配信画面表示 */
	$save_flg = "edit";
	$conn = db_conxxxt();
	pub_tb_load ($conn, $index_id);
	pub_tr_load ($conn, $index_id);
	#$mag_id = $g_mag_id; #$body_id = $g_body_id; $mag_titl = $g_mag_titl;
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
		$stmt = OCIParse($conn, $sql);
		$ret01 = OCIDefinebyname($stmt, "ADMIN_MSG", &$admin_msg);
		OCIExecute($stmt);
		$ncols = OCIFetchStatement($stmt,&$resarray);
		if ($ncols == 0) {$admin_msg = "--本日はありません--"; }
		$admin_msg = ereg_replace("\n","<br>",$admin_msg);
		OCIFreeStatement($stmt);
	db_logoff();

	include ("mag-yoyaku.html");
} elseif ($OKSubmit) {					/* 本番配信 */
	$save_flg = "vew";
	$conn = db_conxxxt();
	$nrec = pub_tb_chkrec ($conn, $index_id);
	db_logoff();
	if ( $nrec ) {
		$now_yy = com_chg_year((int)$resrv_month, (int)$resrv_day);
		$day = (int)$resrv_day;
		if (date("U") > date("U",mktime((int)$resrv_hour_h,
			(int)$resrv_hour_m,0,(int)$resrv_month, $day, $now_yy))) {
			$p_titl = "メルマガ配信予約";
			$w_msg = sprintf("%d年%d月%d日 %d時%d分",
				 $now_yy, (int)$resrv_month, $day,
				 (int)$resrv_hour_h, (int)$resrv_hour_m);
			$print_msg =  $w_msg . "は過ぎているようです。";
			include ("err-msg.html");
		} elseif (!checkdate((int)$resrv_month, $day, $now_yy)) {
			$p_titl = "メルマガ配信予約";
			$w_msg = sprintf("%d年%d月%d日", $now_yy, (int)$resrv_month, $day);
			$print_msg =  $w_msg . "は存在しないようです。";
			include ("err-msg.html");
		} else {
			$resrv_day = change_date($resrv_month, $resrv_day);
			include ("mag-yoyaku-kakunin.html");
		}
	} else {
		$msg = "テスト配信を行ってください。";
		include ("mag-testpublish.html");
	}
} elseif ($submit_save) {				/* 配信登録 */
	$save_flg = "save";
	$hour_ora = make_resrv_date($resrv_month, $resrv_day,
							$resrv_hour_h, $resrv_hour_m);
	$conn = db_conxxxt();
	if (pub_tr_insert ($conn, $index_id, $mag_id, $body_id, $resrv_month,
							$resrv_day, $hour_ora, 0)) {
		OCICommit($conn);
		include ("mag-yoyaku-kakunin.html");
	} else {
		OCIRollback($conn);
		err_msg ("PM005", "メルマガ配信登録");
	}
	db_logoff();
} elseif ($edit_reserv) {				/* 配信入力修正 */
	$save_flg = "edit";
	$conn = db_conxxxt();
	pub_tb_load ($conn, $index_id);
	pub_tr_load ($conn, $index_id);
	$mag_id = $g_mag_id; $body_id = $g_body_id; $mag_titl = $g_mag_titl;
	$mag_body = $g_mag_body;
	$resrv_month = $g_resrv_month; $resrv_day = $g_resrv_day;
	$resrv_hour_h = $g_resrv_hour_h; $resrv_hour_m = $g_resrv_hour_m;
	$resrv_flg = $g_resrv_flg;

		$access_day = date("Ymd",time());
		$sql = "select admin_msg from t_mag_footer where riba_footer = 1 and access_day = '$access_day'";
		$stmt = OCIParse($conn, $sql);
		$ret01 = OCIDefinebyname($stmt, "ADMIN_MSG", &$admin_msg);
		OCIExecute($stmt);
		$ncols = OCIFetchStatement($stmt,&$resarray);
		if ($ncols == 0) {$admin_msg = "--本日はありません--"; }
		$admin_msg = ereg_replace("\n","<br>",$admin_msg);
		OCIFreeStatement($stmt);

	db_logoff();
	$mag_body = ereg_replace("<br>","\n",$mag_body);
	//$mag_body = stripslashes($mag_body);
	$mag_titl = stripslashes($mag_titl);
	$mag_titl = htmlspecialchars($mag_titl);
	include ("mag-yoyaku-syuusei.html");
} elseif ($edit_reserv_ok) {				/* 配信修正確認 */
	$save_flg = "edit_ok";
	if (($msg = mm_chk_tbody($mag_titl, $mag_body)) == "OK") {
//			$msg = "以下の内容に修正してもよろしいですか？";
	}
	$now_yy = com_chg_year((int)$resrv_month, (int)$resrv_day);
	$day = (int)$resrv_day;
	if (date("U") > date("U",mktime((int)$resrv_hour_h,
			(int)$resrv_hour_m,0,(int)$resrv_month, $day, $now_yy))) {
		$p_titl = "メルマガ配信予約修正";
			$w_msg = sprintf("%d年%d月%d日 %d時%d分",
				 $now_yy, (int)$resrv_month, $day,
				 (int)$resrv_hour_h, (int)$resrv_hour_m);
		$print_msg =  $w_msg . "は過ぎているようです。";
		include ("err-msg.html");
	} elseif (!checkdate((int)$resrv_month, $day, $now_yy)) {
		$p_titl = "メルマガ配信予約修正";
		$w_msg = sprintf("%d年%d月%d日", $now_yy, (int)$resrv_month, $day);
		$print_msg =  $w_msg . "は存在しないようです。";
		include ("err-msg.html");
	} else {
		$resrv_day = change_date($resrv_month, $resrv_day);
		$mag_titl = htmlspecialchars($mag_titl);
		include ("mag-syuusei-kakunin.html");
	}
} elseif ($edit_reserv_can) {				/* 配信削除確認 */
	$save_flg = "delete_ok";
		$mag_body = stripslashes($mag_body);
		$mag_titl = stripslashes($mag_titl);
		$mag_titl = htmlspecialchars($mag_titl);
		$mag_body = htmlspecialchars($mag_body);
	include ("mag-torikeshi-kakunin.html");
} elseif ($edit_reserv_updt) {				/* 配信修正登録 */
	$save_flg = "reserv_update";
	$hour_ora = make_resrv_date($resrv_month, $resrv_day,
							$resrv_hour_h, $resrv_hour_m);
	$conn = db_conxxxt();
		//$mag_body = stripslashes($mag_body);
		//$mag_titl = stripslashes($mag_titl);
	if (($l_ret = pub_tb_insert ($conn, $index_id, $mag_id, $body_id, $mag_titl,
									 $mag_body))) {
		$l_ret = pub_tr_insert ($conn, $index_id, $mag_id, $body_id, $resrv_month,
								$resrv_day, $hour_ora, 0);
	}
	if ($l_ret) {
		OCICommit($conn);
		include ("mag-syuusei-ok.html");
	} else {
		OCIRollback($conn);
		err_msg ("PM006", "メルマガ配信修正");
	}
	db_logoff();
} elseif ($edit_reserv_del) {				/* 配信削除 */
	$save_flg = "reserv_delete";
	$conn = db_conxxxt();
	if (($l_ret = pub_tb_delete ($conn, $index_id))) {
		$l_ret = pub_tr_delete ($conn, $index_id);
	}
	if ($l_ret) {
		OCICommit($conn);
		$mag_body = stripslashes($mag_body);
		$mag_titl = stripslashes($mag_titl);
		$mag_titl = htmlspecialchars($mag_titl);
		$mag_body = htmlspecialchars($mag_body);
		include ("mag-torikeshi-ok.html");
	} else {
		OCIRollback($conn);
		$p_titl = "メルマガ配信取り消し";
		$print_msg =  "「" . $mag_nm . "」は取り消されているか、配信済みです。";
		include ("err-msg.html");
//			err_msg ("PM007", "メルマガ配信取り消し");
	}
	db_logoff();
}

?>
