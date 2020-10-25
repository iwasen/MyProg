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
	$w_caption[0] = "���� ";
	$w_caption[1] = "���� ";
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
	$w_caption[0] = "�ȿ� ";
	$w_caption[1] = "���� ";
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
		$ret_code = "����";
	} else {
		$ret_code = "����";
	}
	return $ret_code;
}

function pub_mm_mrg ($code) {
	if ($code == "1") {
		$ret_code = "�ȿ�";
	} else {
		$ret_code = "����";
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
		$wk_msg = $wk_msg . "�᡼�륢�ɥ쥹�����Ϥ���Ƥ��ʤ��褦�Ǥ���<br>";
	} else {
		if (strlen($mail_addr) < 6) {
			$errflg = 1;
			$wk_msg = $wk_msg . "�᡼�륢�ɥ쥹��û������褦�Ǥ���<br>";
		}
		if (strlen($mail_addr) > 50) {
			$errflg = 1;
			$wk_msg = $wk_msg . "�᡼�륢�ɥ쥹��Ĺ������褦�Ǥ���<br>";
		}
		if (chkmail_addr($mail_addr) == -1) {
			$errflg = 1;
			$wk_msg = $wk_msg . "�᡼�륢�ɥ쥹�����������Ϥ���Ƥ��ʤ��褦�Ǥ���<br>";
		}
	}
	if (strlen($w_mail_addr) == 0) {
		$errflg = 1;
		$wk_msg = $wk_msg .  "��ǧ�᡼�륢�ɥ쥹�����Ϥ���Ƥ��ʤ��褦�Ǥ���<br>";
	} else {
		if (strlen($w_mail_addr) < 6) {
			$errflg = 1;
			$wk_msg = $wk_msg . "��ǧ�᡼�륢�ɥ쥹��û������褦�Ǥ���<br>";
		}
		if (strlen($w_mail_addr) > 50) {
			$errflg = 1;
			$wk_msg = $wk_msg . "��ǧ�᡼�륢�ɥ쥹��Ĺ������褦�Ǥ���<br>";
		}
		if (chkmail_addr($w_mail_addr) == -1) {
			$errflg = 1;
			$wk_msg = $wk_msg . "��ǧ�᡼�륢�ɥ쥹�����������Ϥ���Ƥ��ʤ��褦�Ǥ���<br>";
		}
	}

	if($mail_addr != $w_mail_addr) {
		$errflg = 1;
		$wk_msg = $wk_msg . "���Ϥ��줿���ĤΥ᡼�륢�ɥ쥹�����פ��Ƥ��ʤ��褦�Ǥ�������ǧ����������<br>";
	}

	// �֥ѥ���ɡפΥ����å�
	if(strlen($reader_pswd) == 0) {
		$errflg = 1;
		$wk_msg = $wk_msg .  "�ѥ���ɤ����Ϥ���Ƥ��ʤ��褦�Ǥ�<br>\n";
	} else {
		// �֥ѥ���ɡפΥ����å�
		if(strlen($reader_pswd) < 4 || strlen($reader_pswd) > 8) {
			$wk_msg = $wk_msg .  "�ѥ���ɤϡ�Ⱦ�ѱѿ��ǣ�ʸ���ʾ壸ʸ����������Ϥ��Ƥ���������<br>\n";
			$errflg = 1;
		} else {
			if( !(ereg ("^[0-9a-zA-Z]{4,}$", $reader_pswd)) ) {
				$wk_msg = $wk_msg .  "�ѥ���ɤ����������Ϥ���Ƥ��ʤ��褦�Ǥ���<br>\n";
			$errflg = 1;

			}
		}
	}
	// ��Ϣ����͹���ֹ�פΥ����å�
	if(strlen($reader_zip1) == 0 || strlen($reader_zip2) == 0)
	{
		$wk_msg = $wk_msg .  "͹���ֹ椬���Ϥ���Ƥ��ʤ��褦�Ǥ���<br>\n";
		$errflg = 1;
	} else {
		// ��͹���ֹ�פΥ����å�
		if(strlen($reader_zip1) <> 3 || strlen($reader_zip2) <> 4)
		{
			$wk_msg = $wk_msg .  "͹���ֹ�ϡ����줾���������Ⱦ�ѿ������塼��������Ϥ��Ƥ���������<br>\n";
		} else {
			if( !(ereg ("^[0-9]{3}$", $reader_zip1)) || !(ereg ("^[0-9]{4}$", $reader_zip2)) )
			{
				$wk_msg = $wk_msg .  "͹���ֹ椬���������Ϥ���Ƥ��ʤ��褦�Ǥ���<br>\n";
				$errflg = 1;
			}
		}
	}

	if (strlen($wk_year) == 0) {
		$errflg = 1;
		$wk_msg = $wk_msg .  "��������ǯ�ˤ����Ϥ���Ƥ��ʤ��褦�Ǥ���<br>";
	} elseif (strlen($wk_year) != 4) {
		$errflg = 1;
		$wk_msg = $wk_msg . "��������ǯ�ˤ������Ⱦ�ѿ�����������Ϥ��Ƥ���������<br>";
	} elseif ( !(ereg ("^[0-9]{4}$", $wk_year))) {
		$errflg = 1;
		$wk_msg = $wk_msg . "��������ǯ�ˤ������Ⱦ�ѿ�����������Ϥ��Ƥ���������<br>";
	} else {
		$inp_date = date("Y");
		if (($inp_date - 3 ) <= $wk_year) {
			$errflg = 1;
			$wk_msg = $wk_msg . "��My�����Ѥ�Ȥ������٤ϣ������ˤʤ�ޤǡ��⤦�������ޤäƤ͡�<br>";
		} elseif ($wk_year < 1900) {
			$errflg = 1;
			$wk_msg = $wk_msg . "��������ǯ�ˤ�����1900ǯ�ʹߤ����Ϥ��Ƥ���������<br>";
		}
	}
	if (strlen($wk_month) == 0) {
		$errflg = 1;
		$wk_msg = $wk_msg .  "�������ʷ�ˤ����򤵤�Ƥ��ʤ��褦�Ǥ���<br>";
	} else {
		if (($wk_month < 1) || ($wk_month > 12)) {
			$errflg = 1;
			$wk_msg = $wk_msg .  "�������ʷ�ˤ����򤵤�Ƥ��ʤ��褦�Ǥ���<br>";
		}
	}
	if (strlen($wk_day) == 0) {
		$errflg = 1;
		$wk_msg = $wk_msg .  "�����������ˤ����򤵤�Ƥ��ʤ��褦�Ǥ���<br>";
	} else {
		if (($wk_day < 1) || ($wk_day > 31)) {
			$errflg = 1;
			$wk_msg = $wk_msg .  "�����������ˤ����򤵤�Ƥ��ʤ��褦�Ǥ���<br>";
		}
	}

	$ret = checkdate($wk_month,$wk_day,$wk_year);
	if (!$ret) {
		$errflg = 1;
		$wk_msg = $wk_msg .  "��������¸�ߤ��ʤ����Τ褦�Ǥ���<br>";
	}

	if (strlen($reader_mrg) == 0) {
		$errflg = 1;
		$wk_msg = $wk_msg .  "���뺧�Ϥ���Ƥ��ޤ�������̤�����ˤĤ������򤷤Ƥ���������<br>";
	}
	if (strlen($reader_occup) == 0 || strlen($reader_occup) > 5) {
		$errflg = 1;
		$wk_msg = $wk_msg .  "���Ȥ����򤵤�Ƥ��ʤ��褦�Ǥ���\n";
	} else {
		$sql ="SELECT * from M_MAG_OCCUP where OCCUP_ID = '$reader_occup'";
		$result = db_exec($sql);
		if (!pg_numrows($result)) {
			$fetch = pg_fetch_object($result, 0);
			$occup = $fetch->occup;
			$errflg = 1;
			$wk_msg = $wk_msg .  "���Ȥ�������򤷤Ƥ��������ޤ�����<br>���Ƥӥ��顼��ɽ������Ƥ��ޤ����ˤϡ�<br>��������Ǥ�����̳�ɤޤǤ�Ϣ������������<br>������礻��Ϥ�����ˤʤ�ޤ���(staff@melonpan.net)\n";
		}
	}
	if ($mail_adr != $w_mail_adr) {
		$errflg = 1;
		$wk_msg = $wk_msg .  "���Ϥ��줿���ĤΥ᡼�륢�ɥ쥹�����פ��Ƥ��ʤ��褦�Ǥ�������ǧ����������\n";
	}
	if (strlen($mail_addr) > 50) {
		$errflg = 1;
		$wk_msg = $wk_msg .  "�᡼�륢�ɥ쥹�����������Ϥ���Ƥ��ʤ��褦�Ǥ���<br>";
	}

	echo $wkmsg;
	if ($errflg == 1) {
		return $wk_msg;
	}
	$ret_reader_id = check_mail_adr ($mail_addr);
	if ($ret_reader_id != "") {
		$errflg = 1;
		$wk_msg = $wk_msg .  "���Ϥ����᡼�륢�ɥ쥹�Ϥ��Ǥ���Ͽ����Ƥ���褦�Ǥ�������ǧ����������<br>";
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