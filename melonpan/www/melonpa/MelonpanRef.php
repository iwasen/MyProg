<?php
include ("../inc/com_path.inc");
include ("inc/error_msg_list.inc");
include ("$compath/com_db_func.inc");
include ("$compath/com_rm_func.inc");
include ("$compath/sql_mcom.inc");
include ("$compath/sql_rm.inc");

function set_global () {
global $inc_path;
	include ("../inc/com_path.inc");
	$inc_path = $incpath;

}
/* -------------------------------- */
/* ¥Ý¥¤¥ó¥È¹¹¿·(¿·µ¬Åê¹Æ)			*/
/* -------------------------------- */
function update_point($conn, $mail_addr, $reader_id, $mag_id, $trans_point) {
	$sql = "SELECT * FROM M_MELONPA WHERE mail_addr = '$mail_addr' for update";
	$result = db_exec($sql);
	if ( !pg_numrows($result)) { return false; }
	$fetch = pg_fetch_object($result, 0);
	$pt_melonpan = $fetch->pt_melonpan;

	$pt_melonpan = $pt_melonpan - $trans_point;
	$sql = "UPDATE M_MELONPA SET pt_melonpan = $pt_melonpan WHERE mail_addr = '$mail_addr'";

	if (!db_exec($sql)) { return false; }
	if ( !ins_point_detail($conn, $reader_id, $mag_id, - $trans_point) ) {
		return false;
	}
	return true;
}

/* -------------------------------- */
/* ¥Ý¥¤¥ó¥ÈÆâÌõÅÐÏ¿					*/
/* -------------------------------- */
function ins_point_detail($conn, $reader_id, $mag_id, $trans_point) {

	$trans_id = update_squ_key("T_POINT_DETAIL");
	if ($trans_id == "-1") { 
		return false; 
	}

	$sql = "select publisher_id from M_MAGAZINE where mag_id = '$mag_id'";
	$result = db_exec($sql);
	if (pg_numrows($result)) {
		$fetch = pg_fetch_object($result, 0);
		$publisher_id = $fetch->publisher_id;

	}else{
		$publisher_id = 0;
	}

	$sql = "insert into T_POINT_DETAIL "
		 . "(TRANS_ID, OWNER_KIND, OWNER_ID, ATTRIBUTION_CD, TRANS_DATE, TRANS_POINT, AVAILABLE_POINT, COUNTER_ID,"
		 . " MAG_ID ) "
		 . "values($trans_id, '1', $reader_id, '21', 'now', $trans_point, $trans_point, $publisher_id, '$mag_id')";
	if (!db_exec($sql)) {
		return false;
	}
	return true;
}

/* -------------------------------- */
/* ¥Ý¥¤¥ó¥È¹¹¿·(ÆâÍÆÊÑ¹¹)			*/
/* -------------------------------- */
function update_point_mod($conn, $mail_addr, $reader_id, $mag_id, $trans_point) {
	# ½¤ÀµÁ°¥µ¥ó¥­¥å¡¼¥Á¥Ã¥×¼èÆÀ
	$sql = "select RECOM_TIP from T_RECOMEND where READER_ID = $reader_id and MAG_ID = '$mag_id'";
	$result = db_exec($sql);
	if (pg_numrows($result)) {
		$fetch = pg_fetch_object($result, 0);
		$old_recom_tip = $fetch->recom_tip;
	}else{
		return false;
	}

	# ¸½ºß¥Ý¥¤¥ó¥È»Ä¹â¼èÆÀ
	$sql = "select PT_MELONPAN from M_MELONPA where MAIL_ADDR = '$mail_addr' for update";
	$result = db_exec($sql);
	if (pg_numrows($result)) {
		$fetch = pg_fetch_object($result, 0);
		$pt_melonpan = $fetch->pt_melonpan;
	}else{
		return false;
	}

	# ¸½ºß¥Ý¥¤¥ó¥È»Ä¹âÊÑ¹¹
	$new_pt_melonpan = $pt_melonpan - ( $trans_point - $old_recom_tip );
	$sql = "UPDATE M_MELONPA SET pt_melonpan = $new_pt_melonpan WHERE mail_addr = '$mail_addr'";
	if (!db_exec($sql)) { return false; }
	# ¥Ý¥¤¥ó¥ÈÌÀºÙÊÑ¹¹
	$sql = "select AVAILABLE_POINT from T_POINT_DETAIL "
		 . " where OWNER_KIND = '1' and OWNER_ID = $reader_id and ATTRIBUTION_CD = '21' and MAG_ID = '$mag_id'";
	$result = db_exec($sql);
	if (pg_numrows($result)) {
		$fetch = pg_fetch_object($result, 0);
		$available_point = $fetch->available_point;
	}else{
		return ( ins_point_detail($conn, $reader_id, $mag_id, - $trans_point) );
	}

	$available_point = $available_point - ( $trans_point - $old_recom_tip );
	$trans_point = -$trans_point ;
	$sql = "update T_POINT_DETAIL set "
		 . " TRANS_POINT = $trans_point,"
		 . " AVAILABLE_POINT = $available_point,"
		 . " REMARK = '¥µ¥ó¥­¥å¡¼¥Á¥Ã¥×ÊÑ¹¹' "
		 . " where OWNER_KIND = '1' and OWNER_ID = $reader_id and ATTRIBUTION_CD = '21' and MAG_ID = '$mag_id'";
	if (!db_exec($sql)) {
		return false;
	}
	return true;
}
/*======================================================================================*/
/*	submit Magazine									*/
/*	(main)										*/
/*======================================================================================*/
set_global ();
if ($rmbott10101) {
	include ("unregist2.php");
}
elseif ($rmbott10201) {
	include ("unregist5.php");
}
elseif ($rmbott10202) {
	include ("unregist3.php");
}
elseif ($rmbott10301) {
	include ("unregist4.php");
}
elseif ($rmbott10302) {
//	include ("melonpa-index.html");
	include ("../mypage/mypage-index.html");
}
elseif ($rmbott10501) {
	include ("unregist6.php");
}
elseif ($rmbott10601) {
	include ("unregist7.php");
}
/* -----------------------------*/
/* ¥í¥°¥ª¥Õ³ÎÇ§					*/
/* -----------------------------*/
elseif ($rmbott10801) {
	include ("tmpl_html/logoff1.html");
}
/* -----------------------------*/
/* ¥í¥°¥ª¥Õ						*/
/* -----------------------------*/
elseif ($rmbott10802) {
	setcookie("cookie_mail_addr"  , "", time()+604800);
	setcookie("cookie_reader_pswd", "", time()+604800);
	setcookie("cookie_reader_id"  , "", time()+604800);

	setcookie("cookie_mail_addr"  , "", time()+604800, "/~melonpa/");
	setcookie("cookie_reader_pswd", "", time()+604800, "/~melonpa/");
	setcookie("cookie_reader_id"  , "", time()+604800, "/~melonpa/");

	setcookie("cookie_mail_addr"  , "", time()-604800);
	setcookie("cookie_reader_pswd", "", time()-604800);
	setcookie("cookie_reader_id"  , "", time()-604800);

	include ("tmpl_html/logoff2.html");
}
/* -----------------------------*/
elseif ($rmbott20001) {
	include ("recomend1.php");
}
elseif ($rmbott20002) {
	include ("mag-stop-1.php");
}
elseif ($rmbott20301) {
	include ("../search/list1.html");
}
elseif ($rmbott20401) {
	include ("point.php");
//	include ("../mypage/mypage-point.php");
}
elseif ($rmbott20402) {
	include ("ikkatsuhenkou-kakunin.php");
}
elseif ($rmbott30001) {
	include ("ikkatsuhenkou-kakunin2.php");
}
elseif ($rmbott30002) {
//	include ("melonpa-index.html");
	include ("../mypage/mypage-index.html");
}
elseif ($rmbott40001) {
	include ("reminder2.php");
}
elseif ($rmbott50001) {
	$w_reason = $reason_sel;
	if ($reason != "") {
		$w_reason = $reason;
	}
	$w_reason = trim ($w_reason);

	db_exec(sprintf("INSERT INTO t_unsubscribe (mag_id,mail_addr,subscribe_dt,unsubscribe_dt)"
		. " SELECT mag_id,mail_addr,subscribe_dt,current_timestamp FROM t_mailaddr WHERE mag_id = '%s' and mail_addr = '%s'"
		, $mag_id, $mail_addr));

	$sql1 =  "delete from t_mailaddr where mail_addr = '";
	$sql = $sql1 .  $mail_addr . "'" ;
	$sql = $sql . " and mag_id = '$mag_id'";
	db_exec($sql);
	$delete_id = update_squ_key("T_DELREASON");
	if ($delete_id != "-1") {
//		if ($w_reason != "") {
			/* 2001.08.07 SQLÊÑ¹¹ (mag_id 6·å¤Ø) */
			$sql = "INSERT INTO T_DELREASON "
				 . "  (delete_id, mag_id, reader_id, reason, modify_dt) "
				 . "VALUES"
				 . "  (". $delete_id.",'" . $mag_id . "'," . $reader_id . ",'" . trim($w_reason) . "', 'now' )";
			db_exec($sql);
//		} else {
//			$sql = "INSERT INTO T_DELREASON (";
//			$sql = 	$sql . "delete_id,";
//			$sql = 	$sql . "mag_id,";
//			$sql = 	$sql . "reader_id,";
//			$sql = 	$sql . "modify_dt";
//			$sql = 	$sql . 	" ) VALUES ( ";
//			$sql = 	$sql . 	$delete_id . ",";
//			$sql = 	$sql . 	$mag_id . ",";
//			$sql = 	$sql . 	$reader_id . ",";
//			$sql = 	$sql ."SYSDATE )";
//			$stmt3 = OCIParse($conn, $sql);
//			$ncols = OCIExecute($stmt3, OCI_DEFAULT);
//			$sql =  "commit ";
//			$stmt4 = OCIParse($conn, $sql);
//			$ncols = OCIExecute($stmt4, OCI_DEFAULT);
//		}
	}

	//include ("melonpa-index.html");
	$mag_datas .= "<br><FONT color=#990000><B>[ID".$mag_id."¡§".trim(stripcslashes(htmlspecialchars($mag_nm)))."]</B></FONT>";
	$mag_datas .= "<br>".trim(htmlspecialchars(stripcslashes($mag_nm))).$em_regist_14;
	$goback = 1;
	include ("tmpl_html/mag-regi_thanks.html");

}
elseif ($rmbott60001) {
	include ("recomend2.php");
}
elseif ($rmbott60002) {
//	include ("melonpa-index.html");
	include ("../mypage/mypage-index.html");
}
elseif ($rmbott60101) {
	$recom_title = stripcslashes($recom_title);
	$recom_text = stripcslashes($recom_text);
	$recom_nic = stripcslashes($recom_nic);

	$recom_id = update_squ_key("T_RECOMEND");

	$sanks_tip = strval($sanks_tip_point);
	if ($sanks_tip == "" ) {
		$sanks_tip = 0;
	}	
	if ($recom_id != "-1") {
		$print_msg = "";
		$err_flg = 0;
		if (strlen($recom_title) == 0) {
			$print_msg = $print_msg . $em_recomend_1;
			$err_flg = 1;
		}
		if (strlen($recom_text) == 0) {
			$print_msg = $print_msg . $em_recomend_2;
			$err_flg = 1;
		}
		if (strlen($recom_nic) == 0) {
			$print_msg = $print_msg . $em_recomend_3;
			$err_flg = 1;
		
		}

		if ($err_flg == 0) {
			$sql = "select * from t_recomend where reader_id = " . $reader_id 
				 . " and mag_id = '" . $mag_id . "'";
			$result = db_exec($sql);
			if (!pg_numrows($result)) {

				// ¿äÁ¦Ê¸ÅÐÏ¿ ------------------ //
				$sql = "INSERT INTO T_RECOMEND "
					 . "( recomend_id,reader_id,mag_id,recom_titl,recom_text,recom_nic,recom_tip,open_flg,modify_dt)"
					 . "  VALUES ( $recom_id, $reader_id, '$mag_id', '$recom_title',"
		 			 . " '$recom_text', '$recom_nic', $sanks_tip, 0, 'now' )";
				db_exec($sql);

				// ÆÉ¼Ô¥Ý¥¤¥ó¥È¹¹¿· ------------ //
				$trans_point = intval($sanks_tip);
				if ( $trans_point > 0 ) {
					if ( !update_point($conn, $mail_addr, $reader_id, $mag_id, $trans_point) ) {
						$print_msg = $print_msg . $em_db_1;
						$err_flg = 1;
					}
				}
			} else {
				// ¿äÁ¦Ê¸ÊÑ¹¹ ------------------ //
				$trans_point = intval($sanks_tip);
				if ( update_point_mod($conn, $mail_addr, $reader_id, $mag_id, $trans_point) ) {
					$sql = "UPDATE  T_RECOMEND SET ";
			 		$sql = 	$sql . "recom_titl = '" . $recom_title . "',";
		 			$sql = 	$sql . "recom_text = '" . $recom_text . "',";
		 			$sql = 	$sql . "recom_nic = '" . $recom_nic . "',";
			 		$sql = 	$sql . "recom_tip = " . $sanks_tip . ",";
					$sql = 	$sql . "modify_dt = 'now'";
					$sql = 	$sql . 	"  where reader_id = " . $reader_id . " and ";
					$sql = 	$sql . 	"mag_id = '" . $mag_id . "'";
				db_exec($sql);
				}else{
					$print_msg = $print_msg . $em_db_1;
					$err_flg = 1;
				}
			}
			// ÅÐÏ¿´°Î» ------------------ //
			if ($err_flg == 0) {
			}else{
				$p_titl = "¿äÁ¦Ê¸ÅÐÏ¿";
				include ("tmpl_html/err-msg.html");
			}
		} else {
			db_rollback();
			$p_titl = "£Í£ù¤á¤í¤ó¤Ñ¤óÅÐÏ¿";
			include ("tmpl_html/err-msg.html");
		}
	} else {
		db_rollback();
		$print_msg = $em_login_1;
		$p_titl = "£Í£ù¤á¤í¤ó¤Ñ¤óÅÐÏ¿";
		include ("tmpl_html/err-msg.html");
	}

	include ("../mypage/mypage-index.html");
}
elseif ($rmbott60102) {
//	include ("melonpa-index.html");
	include ("../mypage/mypage-index.html");
}
elseif ($rmbott70101) {
	$w_reader_pswd = trim(stripcslashes($w_reader_pswd));

	$sql1 = rm_make_sql ("SELECT_M_MELONPA_MAIL_ADR_DATE");
	$sql = $sql1 ."'".  $mail_addr ."'";
	$result = db_exec($sql);
	if (pg_numrows($result)) {
		$fetch = pg_fetch_object($result, 0);
		$reader_pswd = $fetch->reader_pswd;
		$log_key = $fetch->log_key;
		$log_key_flg = $fetch->log_key_flg;

		if ($log_key_flg == 0) {
			$reader_pswd = trim($reader_pswd);
			if (trim($w_log_key) != trim($log_key)) {
				$print_msg = $em_login_2;
				$p_titl = "£Í£ù¤á¤í¤ó¤Ñ¤óÅÐÏ¿";
				include ("tmpl_html/err-msg.html");
			} elseif ($w_reader_pswd != $reader_pswd) {
				$print_msg = $em_login_3;
				$p_titl = "£Í£ù¤á¤í¤ó¤Ñ¤óÅÐÏ¿";
				include ("tmpl_html/err-msg.html");
			} elseif ($w_reader_pswd == $reader_pswd) {
				$sql = "update m_melonpa set log_key_flg = '1' where mail_addr = '";
				$sql = $sql . $mail_addr . "'";
				$ncols = db_exec($sql);
//				include ("melonpa-index.html");
				include ("../mypage/mypage-index.html");
			}
		} else {
			$reader_pswd = trim($reader_pswd);
			if ($w_reader_pswd == $reader_pswd) {
//				include ("melonpa-index.html");
				include ("../mypage/mypage-index.html");
			} else {
				$print_msg = $em_login_4;
				$p_titl = "£Í£ù¤á¤í¤ó¤Ñ¤óÅÐÏ¿";
				include ("tmpl_html/err-msg.html");
			}
		}
	} else {
		$print_msg = $em_login_5;
		$p_titl = "£Í£ù¤á¤í¤ó¤Ñ¤óÅÐÏ¿";
		include ("tmpl_html/err-msg.html");
	}
}
/* **
elseif ($rmbott70201) {
	$conn = db_conxxxt();
	$sql1 = rm_make_sql ("SELECT_M_MELONPA_MAIL_ADR_DATE");
	$sql = $sql1 ."'".  $mail_addr ."'";
	$stmt = OCIParse($conn, $sql);
	OCIDefinebyname($stmt, "READER_PSWD", &$reader_pswd);
	$ncols = OCIExecute($stmt, OCI_DEFAULT);
	if (OCIFetch($stmt)) {
		$reader_pswd = trim($reader_pswd);
		if ($w_reader_pswd == $reader_pswd) {
//			include ("melonpa-index.html");
			include ("../mypage/mypage-index.html");
		} else {
			$print_msg = $em_login_6;
			$p_titl = "£Í£ù¤á¤í¤ó¤Ñ¤óÅÐÏ¿";
			include ("tmpl_html/err-msg.html");
		}
	} else {
		$print_msg = $em_login_7;
		$p_titl = "£Í£ù¤á¤í¤ó¤Ñ¤óÅÐÏ¿";
		include ("tmpl_html/err-msg.html");
	}
	db_logoff();
}
*/
else {
	if ($PROCESS_FLG == "remainder" ) {
		include ("reminder.php");
	} elseif ($PROCESS_FLG == "indexdisp" ) {
//		include ("melonpa-index.html");
		include ("../mypage/mypage-index.html");
	} elseif ($PROCESS_FLG == "login1" ) {
	//	if (strlen($mail_addr) > 0) {
			//setcookie("cookie_mail_addr", $mail_addr, time()+3600);
			//setcookie("cookie_reader_pswd", $reader_pswd, time()+3600);
			//setcookie("cookie_reader_id", $reader_id, time()+604800);
			//setcookie("cookie_mail_addr", trim($mail_addr), time()+604800);
			//setcookie("cookie_reader_pswd", trim($reader_pswd), time()+604800);
		//}
		include ("mypage-login.php");
	} elseif ($PROCESS_FLG == "login2" ) {
		include ("mypage-login2.php");
	} elseif ($PROCESS_FLG == "regist" ) {
		include ("regist.php");
	} else {
		include ("mypage-login.php");
	}
}
?>