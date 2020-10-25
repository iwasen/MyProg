<?php
function hakkou_check($log_key_flg) {
	if ($log_key_flg != 1) {
		print "<tr> \n";
		print "<td bgcolor=\"#FFCC33\" class=\"text9pt\" width=\"120\"> \n";
		print "<div align=\"left\"><b><font color=\"#336600\">専用キー</font></b></div>\n";
		print "</td>\n";
		print "<td bgcolor=\"#FFFFCC\" width=\"230\">\n";
		print "<div align=\"left\"><input type=\"text\" name=\"w_log_key\"></div>\n";
		print "</td>\n";
		print "</tr>\n";
	}
}

function MenberS() {
	$conn = db_conxxxt ($mail_addr);
	$sql1 = rm_make_sql ("SELECT_M_MELONPA_MAIL_ADR_DATE");
	$sql = $sql1 ."'".  $mail_addr ."'";
//	$result = db_exec($sql);
	$stmt = OCIParse($conn, $sql);
	$ncols = OCIExecute($stmt, OCI_DEFAULT);
	if (!OCIFetch($stmt)) {
		/*print "<form name=\"rmform702\" action=\"MelonpanRef.php\" method=\"post\">\n";
		print "<p>まだメロンパ登録がおすみでないかたは<a href=\"JavaScript:document.rmform702.submit();\">こちら！</a></p>\n";
		print "<input type=\"hidden\" value=\"$reader_id\" name=\"reader_id\">\n";
		print "<input type=\"hidden\" value=\"$mail_addr\" name=\"mail_addr\">\n";
		print "<input type=\"hidden\" value=\"regist\" name=\"PROCESS_FLG\">\n";
		print "</form>\n";*/
	}
	db_logoff();
}

/****************************************************************
 Main
****************************************************************/
//メールアドレス小文字変換
$mail_addr = strtolower($mail_addr);

if ($no_riba_flg == 1) {
	$no_riba_flg = 1;
//	include ("login.html");
	include ("../melonpan/melonpa-login.html");
	exit();
}

$no_pass_flg = 0;
$pass_error_flg = 0;
$no_mail_flg = 0;

if ($mail_addr == "") {
	$mail_addr = $cookie_mail_addr;
	$reader_pswd = $cookie_reader_pswd;
	$reader_id = $cookie_reader_id;
}
if ($mail_addr == "") {
	$no_mail_flg = 1;
//	include ("login.html");
	include ("../melonpan/melonpa-login.html");
	exit();
}

//$conn = db_conxxxt();
$sql1 = rm_make_sql ("SELECT_M_MELONPA_MAIL_ADR_DATE");
$sql = $sql1 ."'".  $mail_addr ."'";
$result = db_exec($sql);
if (pg_numrows($result)) {
	$fetch = pg_fetch_object($result, 0);
	$reader_id = $fetch->reader_id;
	$mail_addr = $fetch->mail_addr;
	$reader_birth = $fetch->reader_birth;
	$reader_pswdr = $fetch->reader_pswd;
	$reader_sex = $fetch->reader_sex;
	$reader_mrg = $fetch->reader_mrg;
	$reader_zip1 = $fetch->reader_zip1;
	$reader_zip2 = $fetch->reader_zip2;
	$reader_occup = $fetch->reader_occup;
	$wk_year = $fetch->wk_year;
	$wk_month = $fetch->wk_month;
	$wk_day = $fetch->wk_day;
	$log_key_flg = $fetch->log_key_flg;

	$reader_pswdr= trim($reader_pswdr);
	$reader_pswd= trim($reader_pswd);
	if (strlen($reader_pswd) == 0 ) {
		$no_pass_flg = 1;
//		include ("mypage-login.html");
			include ("../melonpan/melonpa-login2.html");
		exit();
	} else {
		if ($reader_pswdr == $reader_pswd && $log_key_flg == 1) {
			if (strlen($mail_addr) > 0) {
				setcookie("cookie_reader_id", $reader_id, time()+604800);
				setcookie("cookie_mail_addr", trim($mail_addr), time()+604800);
				setcookie("cookie_reader_pswd", trim($reader_pswd), time()+604800);
			}
//			include ("melonpa-index.html");
			include ("../mypage/mypage-index.html");
		exit();
		} elseif ($log_key_flg != 1) {
//			include ("mypage-login.html");
			include ("../melonpan/melonpa-login2.html");
		exit();
		} elseif ($reader_pswdr != $reader_pswd && $log_key_flg == 1) {
			$pass_error_flg = 1;
//			include ("mypage-login.html");
			include ("../melonpan/melonpa-login2.html");
		exit();
		} else {
//			include ("login.html");
			include ("../melonpan/melonpa-login.html");
			exit();
		}
	}
} else {
	$no_mail_flg = 1;
//	include ("login.html");
	include ("../melonpan/melonpa-login.html");
	exit();
}

?>
