<?php

function chkmail_addr($mail_addr) {
	if ( $mail_addr != null ) {
		if(ereg(".+\@.+\..+",$mail_addr) && ereg("\.[a-zA-Z]+$",$mail_addr)) {
			return 0;
		} else {
			return -1;
		}
	}
}

function make_wk_month($wk_month) {
	for ($i = 1; $i < 12; $i++) {
		$c_val = sprintf("%02d", $i);
		if ($c_val == $wk_month) {
			print "<option  selected value=\"$c_val\">$c_val  </option>\n";
		} else {
			print "<option value=\"$c_val\">$c_val </option>\n";
		}
	}
}

function make_wk_day($wk_day) {
	for ($i = 1; $i < 31; $i++) {
		$c_val = sprintf("%02d", $i);
		if ($c_val == $wk_day) {
			print "<option selected  value=\"$c_val\">$c_val  </option>\n";
		} else {
			print "<option value=\"$c_val\">$c_val </option>\n";
		}
	}

}

function check_mail_adr($mail_addr) {
//	$conn = db_conxxxt();
	$sql1 = rm_make_sql ("SELECT_M_MELONPA_MAIL_ADR");
	$sql = $sql1 ."'".  $mail_addr ."'";
	$result = db_exec($sql);
	$nrow = pg_numrows($result);
/* **
	$stmt = OCIParse($conn, $sql);
	OCIDefinebyname($stmt, "READER_ID", &$reader_id1);
	$ncols = OCIExecute($stmt, OCI_DEFAULT);
	$wk_reader_id = "";
	while (OCIFetch($stmt)) {
*/
	$wk_reader_id = "";

	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);
		$reader_id1 = $fetch->reader_id;
		$wk_reader_id = $reader_id1;
	}
//	db_logoff ();
	return ($wk_reader_id);
}

function make_sex_box($mag_reader_sex) {
	$w_caption[0] = "男性 ";
	$w_caption[1] = "女性 ";
	for ($i = 1; $i < 3; $i++) {
		$c_val = sprintf("%01d", $i);
		if ($c_val == $mag_reader_sex) {
			print "<input type=\"radio\" name=\"reader_sex\" value=$c_val checked>\n";
		} else {
			print "<input type=\"radio\" name=\"reader_sex\" value=$c_val>\n";
		}
		print $w_caption[$i - 1];
	}
}

function make_mrg_box($mag_reader_mrg) {
	$w_caption[0] = "独身 ";
	$w_caption[1] = "既婚 ";
	for ($i = 1; $i < 3; $i++) {
		$c_val = sprintf("%01d", $i);
		if ($c_val == $mag_reader_mrg) {
			print "<input type=\"radio\" name=\"reader_mrg\"value=\"$c_val\" checked>";
		} else {
			print "<input type=\"radio\" name=\"reader_mrg\"value=\"$c_val\" >";
		}
		print $w_caption[$i - 1];
	}
}

function make_occup_box($mag_reader_occup) {
	$sql = mcom_make_sql ("SELECT_M_MAG_OCCUP");
print $sql;
	$result = db_exec($sql);
	$nrow = pg_numrows($result);
/* **
	$stmt = OCIParse($gConn, $sql);
	OCIDefinebyname($stmt, "OCCUP_ID", &$occup_id);
	OCIDefinebyname($stmt, "OCCUP", &$occup);
	$ncols = OCIExecute($stmt, OCI_DEFAULT);
*/
//	while (OCIFetch($stmt)) {
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);
		$occup_id = $fetch->occup_id;
		$occup = $fetch->occup;
		if ($occup_id == $mag_reader_occup) {
			print "	<option  selected value=\"$occup_id\" >$occup</option>\n";
		} else {
			print "	<option value=\"$occup_id\">$occup</option>\n";
		}
	}
//	db_logoff ();
}

function check_occup_box($mag_reader_occup) {
	$sql = "SELECT * from M_MAG_OCCUP";
	$result = db_exec($sql);
	$nrow = pg_numrows($result);
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);
		$occup_id = $fetch->occup_id;
		$occup = $fetch->occup;
		if ($occup_id == $mag_reader_occup) {
			echo $occup;
		}
	}
}

function pub_mm_sex ($code) {
	if ($code == "1") {
		$ret_code = "男性";
	} else {
		$ret_code = "女性";
	}
	return $ret_code;
}

function pub_mm_mrg ($code) {
	if ($code == "1") {
		$ret_code = "独身";
	} else {
		$ret_code = "既婚";
	}
	return $ret_code;
}

function check_all(
	$w_mail_addr,
	$mail_addr,
	$reader_pswd,
	$wk_year,
	$wk_month,
	$wk_day,
	$reader_mrg,
	$reader_sex,
	$reader_zip1,
	$reader_zip2,
	$reader_occup
) {
	$errflg = 0;
	$wk_msg = "";
	if (strlen($mail_addr) == 0) {
		$errflg = 1;
		$wk_msg = $wk_msg . "メールアドレスが入力されていないようです。<br>";
	} else {
		if (strlen($mail_addr) < 6) {
			$errflg = 1;
			$wk_msg = $wk_msg . "メールアドレスが短すぎるようです。<br>";
		}
		if (strlen($mail_addr) > 50) {
			$errflg = 1;
			$wk_msg = $wk_msg . "メールアドレスが長すぎるようです。<br>";
		}
		if (chkmail_addr($mail_addr) == -1) {
			$errflg = 1;
			$wk_msg = $wk_msg . "メールアドレスが正しく入力されていないようです。<br>";
		}
	}
	if (strlen($w_mail_addr) == 0) {
		$errflg = 1;
		$wk_msg = $wk_msg .  "確認メールアドレスが入力されていないようです。<br>";
	} else {
		if (strlen($w_mail_addr) < 6) {
			$errflg = 1;
			$wk_msg = $wk_msg . "確認メールアドレスが短すぎるようです。<br>";
		}
		if (strlen($w_mail_addr) > 50) {
			$errflg = 1;
			$wk_msg = $wk_msg . "確認メールアドレスが長すぎるようです。<br>";
		}
		if (chkmail_addr($w_mail_addr) == -1) {
			$errflg = 1;
			$wk_msg = $wk_msg . "確認メールアドレスが正しく入力されていないようです。<br>";
		}
	}

	if($mail_addr != $w_mail_addr) {
		$errflg = 1;
		$wk_msg = $wk_msg . "入力された２つのメールアドレスが一致していないようです。ご確認ください。<br>";
	}

	// 「パスワード」のチェック
	if(strlen($reader_pswd) == 0) {
		$errflg = 1;
		$wk_msg = $wk_msg .  "パスワードが入力されていないようです<br>\n";
	} else {
		// 「パスワード」のチェック
		if(strlen($reader_pswd) < 4 || strlen($reader_pswd) > 8) {
			$wk_msg = $wk_msg .  "パスワードは、半角英数で４文字以上８文字以内で入力してください。<br>\n";
			$errflg = 1;
		} else {
			if( !(ereg ("^[0-9a-zA-Z]{4,}$", $reader_pswd)) ) {
				$wk_msg = $wk_msg .  "パスワードが正しく入力されていないようです。<br>\n";
			$errflg = 1;

			}
		}
	}
	// 「連絡先郵便番号」のチェック
	if(strlen($reader_zip1) == 0 || strlen($reader_zip2) == 0)
	{
		$wk_msg = $wk_msg .  "郵便番号が入力されていないようです。<br>\n";
		$errflg = 1;
	} else {
		// 「郵便番号」のチェック
		if(strlen($reader_zip1) <> 3 || strlen($reader_zip2) <> 4)
		{
			$wk_msg = $wk_msg .  "郵便番号は、それぞれ入力欄に半角数字３桁ー４桁で入力してください。<br>\n";
		} else {
			if( !(ereg ("^[0-9]{3}$", $reader_zip1)) || !(ereg ("^[0-9]{4}$", $reader_zip2)) )
			{
				$wk_msg = $wk_msg .  "郵便番号が正しく入力されていないようです。<br>\n";
				$errflg = 1;
			}
		}
	}

	if (strlen($wk_year) == 0) {
		$errflg = 1;
		$wk_msg = $wk_msg .  "誕生日（年）が入力されていないようです。<br>";
	} elseif (strlen($wk_year) != 4) {
		$errflg = 1;
		$wk_msg = $wk_msg . "誕生日（年）は西暦を半角数字４桁で入力してください。<br>";
	} elseif ( !(ereg ("^[0-9]{4}$", $wk_year))) {
		$errflg = 1;
		$wk_msg = $wk_msg . "誕生日（年）は西暦を半角数字４桁で入力してください。<br>";
	} else {
		$inp_date = date("Y");
		if (($inp_date - 3 ) <= $wk_year) {
			$errflg = 1;
			$wk_msg = $wk_msg . "『Myめろんぱんとうろく』は４さいになるまで、もうすこしまってね。<br>";
		} elseif ($wk_year < 1900) {
			$errflg = 1;
			$wk_msg = $wk_msg . "誕生日（年）は西暦1900年以降を入力してください。<br>";
		}
	}
	if (strlen($wk_month) == 0) {
		$errflg = 1;
		$wk_msg = $wk_msg .  "誕生日（月）が選択されていないようです。<br>";
	} else {
		if (($wk_month < 1) || ($wk_month > 12)) {
			$errflg = 1;
			$wk_msg = $wk_msg .  "誕生日（月）が選択されていないようです。<br>";
		}
	}
	if (strlen($wk_day) == 0) {
		$errflg = 1;
		$wk_msg = $wk_msg .  "誕生日（日）が選択されていないようです。<br>";
	} else {
		if (($wk_day < 1) || ($wk_day > 31)) {
			$errflg = 1;
			$wk_msg = $wk_msg .  "誕生日（日）が選択されていないようです。<br>";
		}
	}

	$ret = checkdate($wk_month,$wk_day,$wk_year);
	if (!$ret) {
		$errflg = 1;
		$wk_msg = $wk_msg .  "誕生日が存在しない日のようです。<br>";
	}

	if (strlen($reader_mrg) == 0) {
		$errflg = 1;
		$wk_msg = $wk_msg .  "ご結婚はされていますか？　未既婚について選択してください。<br>";
	}
	if (strlen($reader_occup) == 0 || strlen($reader_occup) > 5) {
		$errflg = 1;
		$wk_msg = $wk_msg .  "職業が選択されていないようです。\n";
	} else {
		$sql ="SELECT * from M_MAG_OCCUP where OCCUP_ID = '$reader_occup'";
		$result = db_exec($sql);
		if (!pg_numrows($result)) {
			$fetch = pg_fetch_object($result, 0);
			$occup = $fetch->occup;
			$errflg = 1;
			$wk_msg = $wk_msg .  "職業を再度選択していただけますか？<br>　再びエラーが表示されてしまう場合には、<br>　お手数ですが事務局までご連絡ください。<br>　お問合せ先はこちらになります。(staff@melonpan.net)\n";
		}
	}
	if ($mail_adr != $w_mail_adr) {
		$errflg = 1;
		$wk_msg = $wk_msg .  "入力された２つのメールアドレスが一致していないようです。ご確認ください。\n";
	}
	if (strlen($mail_addr) > 50) {
		$errflg = 1;
		$wk_msg = $wk_msg .  "メールアドレスが正しく入力されていないようです。<br>";
	}

	echo $wkmsg;
	if ($errflg == 1) {
		return $wk_msg;
	}
	$ret_reader_id = check_mail_adr ($mail_addr);
	if ($ret_reader_id != "") {
		$errflg = 1;
		$wk_msg = $wk_msg .  "入力したメールアドレスはすでに登録されているようです。ご確認ください。<br>";
		return $wk_msg;
	}
	$wk_msg = "";
	$Chk_flg = TRUE;

	if($pub_chg1) {
		$mail_add_sub = $mail_addr;
	}
	if ($wk_msg != "") {
		$Chk_flg = FALSE;
	}

	return $wk_msg;
}
?>
