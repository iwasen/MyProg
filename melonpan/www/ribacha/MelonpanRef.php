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
	include ("melonpa-index.html");
}
elseif ($rmbott10501) {
	include ("unregist6.php");
}
elseif ($rmbott10601) {
	include ("unregist7.php");
}
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
echo "1";
exit();
	include ("point.php");
}
elseif ($rmbott20402) {
	include ("ikkatsuhenkou-kakunin.php");
}
elseif ($rmbott30001) {
	include ("ikkatsuhenkou-kakunin2.php");
}
elseif ($rmbott30002) {
	include ("melonpa-index.html");
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
	$conn = db_conxxxt();
	$sql1 =  "delete t_mailaddr where mail_addr = '";
	$sql = $sql1 .  $mail_addr . "'" ;
	$sql = $sql . " and mag_id = " . $mag_id;
	$stmt3 = OCIParse($conn, $sql);
	$ncols = OCIExecute($stmt3, OCI_DEFAULT);
	$sql =  "commit ";
	$stmt4 = OCIParse($conn, $sql);
	$ncols = OCIExecute($stmt4, OCI_DEFAULT);

	$delete_id = update_squ_key($conn,
					"T_DELREASON");
	if ($mag_del_id != "-1") {
		if ($w_reason != "") {
			$sql = "INSERT INTO T_DELREASON (";
			$sql = 	$sql . "delete_id,";
			$sql = 	$sql . "mag_id,";
			$sql = 	$sql . "reader_id,";
	 		$sql = 	$sql . "reason,";
			$sql = 	$sql . "modify_dt";
			$sql = 	$sql . 	" ) VALUES ( ";
			$sql = 	$sql . 	$delete_id . ",";
			$sql = 	$sql . 	$mag_id . ",";
			$sql = 	$sql . 	$reader_id . ",";
			$sql = 	$sql ."'" . $w_reason . "',";
			$sql = 	$sql ."SYSDATE )";
			$stmt3 = OCIParse($conn, $sql);
			$ncols = OCIExecute($stmt3, OCI_DEFAULT);
			$sql =  "commit ";
			$stmt4 = OCIParse($conn, $sql);
			$ncols = OCIExecute($stmt4, OCI_DEFAULT);
		} else {
			$sql = "INSERT INTO T_DELREASON (";
			$sql = 	$sql . "delete_id,";
			$sql = 	$sql . "mag_id,";
			$sql = 	$sql . "reader_id,";
			$sql = 	$sql . "modify_dt";
			$sql = 	$sql . 	" ) VALUES ( ";
			$sql = 	$sql . 	$delete_id . ",";
			$sql = 	$sql . 	$mag_id . ",";
			$sql = 	$sql . 	$reader_id . ",";
			$sql = 	$sql ."SYSDATE )";
			$stmt3 = OCIParse($conn, $sql);
			$ncols = OCIExecute($stmt3, OCI_DEFAULT);
			$sql =  "commit ";
			$stmt4 = OCIParse($conn, $sql);
			$ncols = OCIExecute($stmt4, OCI_DEFAULT);
		}
	}
	db_logoff();
	include ("melonpa-index.html");
}
elseif ($rmbott60001) {
	include ("recomend2.php");
}
elseif ($rmbott60002) {
	include ("melonpa-index.html");
}
elseif ($rmbott60101) {
	$conn = db_conxxxt();
	$recom_id = update_squ_key($conn,
					"T_RECOMEND");


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
			$sql1 =  "select * from t_recomend where reader_id = ";
			$sql = $sql1 .  $reader_id;
			$sql = $sql . " and mag_id = ";
			$sql = $sql . $mag_id;
			$stmt0 = OCIParse($conn, $sql);
			$ncols = OCIExecute($stmt0, OCI_DEFAULT);
			if (!OCIFetch($stmt0)) {
				$sql = "INSERT INTO T_RECOMEND (";
				$sql = 	$sql . "recomend_id,";
				$sql = 	$sql . "reader_id,";
				$sql = 	$sql . "mag_id,";
		 		$sql = 	$sql . "recom_titl,";
		 		$sql = 	$sql . "recom_text,";
		 		$sql = 	$sql . "recom_nic,";
		 		$sql = 	$sql . "recom_tip,";
		 		$sql = 	$sql . "open_flg,";
				$sql = 	$sql . "modify_dt";
				$sql = 	$sql . 	" ) VALUES ( ";
				$sql = 	$sql . 	$recom_id . ",";
				$sql = 	$sql . 	$reader_id  . ",'";
				$sql = 	$sql . 	$mag_id . "',";
		 		$sql = 	$sql . "'" . $recom_title . "',";
		 		$sql = 	$sql . "'". $recom_text . "'" . ",";
		 		$sql = 	$sql . "'".  $recom_nic. "'" . ",";
		 		$sql = 	$sql . $sanks_tip . ",";
		 		$sql = 	$sql . "0,";
				$sql = 	$sql ."SYSDATE )";
				$stmt3 = OCIParse($conn, $sql);
				$ncols = OCIExecute($stmt3, OCI_DEFAULT);
				$sql5 = "SELECT * FROM M_MELONPA WHERE mail_addr = :MAIL_ADDR for update";
				$stmt5 = OCIParse($conn, $sql5);
				OCIbindbyname ($stmt5, ":MAIL_ADDR", &$mail_addr, -1);
				OCIDefinebyname($stmt5, "PT_MELONPAN", &$pt_melonpan);
				$ncols = OCIExecute($stmt5, OCI_DEFAULT);
				if (OCIFetch($stmt5)) {
					$pt_melonpan = $pt_melonpan - intval($sanks_tip);
					$sql6 = "UPDATE M_MELONPA SET pt_melonpan = :PT_MELONPAN "
						. "WHERE mail_addr = :MAIL_ADDR";
					$stmt6 = OCIParse($conn, $sql6);
					OCIbindbyname ($stmt6, ":PT_MELONPAN", &$pt_melonpan, -1);
					OCIbindbyname ($stmt6, ":MAIL_ADDR", &$mail_addr, -1);
					$ncols = OCIExecute($stmt6, OCI_DEFAULT);
				}
				$sql =  "commit ";
				$stmt4 = OCIParse($conn, $sql);
				$ncols = OCIExecute($stmt4, OCI_DEFAULT);
			} else {
				$sql = "UPDATE  T_RECOMEND SET ";
		 		$sql = 	$sql . "recom_titl = '" . $recom_title . "',";
		 		$sql = 	$sql . "recom_text = '" . $recom_text . "',";
		 		$sql = 	$sql . "recom_nic = '" . $recom_nic . "',";
		 		$sql = 	$sql . "recom_tip = " . $sanks_tip . ",";
				$sql = 	$sql . "modify_dt = SYSDATE";
				$sql = 	$sql . 	"  where reader_id = " . $reader_id . " and ";
				$sql = 	$sql . 	"mag_id = '" . $mag_id . "'";
				$stmt3 = OCIParse($conn, $sql);
				$ncols = OCIExecute($stmt3, OCI_DEFAULT);
				$sql =  "commit ";
				$stmt4 = OCIParse($conn, $sql);
				$ncols = OCIExecute($stmt4, OCI_DEFAULT);
			}
		} else {
		
			$p_titl = "メロンパ登録";
			include ("tmpl_html/err-msg.html");
		}
	} else {
		$print_msg = $em_login_1;
		$p_titl = "メロンパ登録";
		include ("tmpl_html/err-msg.html");
	}
	db_logoff();

	include ("melonpa-index.html");
}
elseif ($rmbott60102) {
	include ("melonpa-index.html");
}
elseif ($rmbott70101) {
	$conn = db_conxxxt();
	$sql1 = rm_make_sql ("SELECT_M_MELONPA_MAIL_ADR_DATE");
	$sql = $sql1 ."'".  $mail_addr ."'";
	$stmt = OCIParse($conn, $sql);
	OCIDefinebyname($stmt, "READER_PSWD", &$reader_pswd);
	OCIDefinebyname($stmt, "LOG_KEY", &$log_key);
	OCIDefinebyname($stmt, "LOG_KEY_FLG", &$log_key_flg);
	$ncols = OCIExecute($stmt, OCI_DEFAULT);
	if (OCIFetch($stmt)) {
		if ($log_key_flg == 0) {
			$reader_pswd = trim($reader_pswd);
			if (trim($w_log_key) != trim($log_key)) {
				$print_msg = $em_login_2;
				$p_titl = "メロンパ登録";
				include ("tmpl_html/err-msg.html");
			} elseif ($w_reader_pswd != $reader_pswd) {
				$print_msg = $em_login_3;
				$p_titl = "メロンパ登録";
				include ("tmpl_html/err-msg.html");
			} elseif ($w_reader_pswd == $reader_pswd) {
				$sql = "update m_melonpa set log_key_flg = '1' where mail_addr = '";
				$sql = $sql . $mail_addr . "'";
				$stmt3 = OCIParse($conn, $sql);
				$ncols = OCIExecute($stmt3, OCI_DEFAULT);
				$sql = "commit";
				$stmt3 = OCIParse($conn, $sql);
				$ncols = OCIExecute($stmt3, OCI_DEFAULT);			
				include ("melonpa-index.html");
			}
		} else {
			$reader_pswd = trim($reader_pswd);
			if ($w_reader_pswd == $reader_pswd) {
				include ("melonpa-index.html");
			} else {
				$print_msg = $em_login_4;
				$p_titl = "メロンパ登録";
				include ("tmpl_html/err-msg.html");
			}
		}
	} else {
		$print_msg = $em_login_5;
		$p_titl = "メロンパ登録";
		include ("tmpl_html/err-msg.html");
	}
	db_logoff();
}
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
			include ("melonpa-index.html");
		} else {
			$print_msg = $em_login_6;
			$p_titl = "メロンパ登録";
			include ("tmpl_html/err-msg.html");
		}
	} else {
		$print_msg = $em_login_7;
		$p_titl = "メロンパ登録";
		include ("tmpl_html/err-msg.html");
	}
	db_logoff();
}

else {
	if ($PROCESS_FLG == "remainder" ) {
		include ("reminder.php");
	} elseif ($PROCESS_FLG == "indexdisp" ) {
		include ("melonpa-index.html");
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