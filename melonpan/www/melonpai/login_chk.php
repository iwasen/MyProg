<?php
include ("../inc/com_path.inc");
include ("$compath/com_db_func.inc");
include ("$compath/sql_mcom.inc");
include ("$compath/com_mm_func.inc");
include ("../sub/head.inc");
include ("inc/get_kensuu.inc");

function melx_login_err($login_err) {
	if (strlen($login_err) > 0) {
		print "<div align=\"center\"><font color=\"#006600\">\n";
		print "-" . $login_err . "-</font></div>\n";
	}
}

/****************************************************************
 Main
****************************************************************/
if ($w_melonpai_mail_addr == "" or $w_melonpai_pswd == "" ) {
	$login_err = "すべての項目を入力してください。";
	include("index.php");
	exit();
} else {
	$w_melonpai_mail_addr = strtolower($w_melonpai_mail_addr);
	$Pass_Err = 1;

	$sql = "select * from m_melonpai where MELONPAI_MAIL_ADDR = '$w_melonpai_mail_addr'";
	$result = db_exec($sql);
	if (pg_numrows($result)) {
		$fetch = pg_fetch_object($result, 0);
		$melonpai_id = $fetch->melonpai_id;
		$melonpai_pswd = $fetch->melonpai_pswd;
		$melonpai_name = $fetch->melonpai_name;
		if ($w_melonpai_pswd == trim($melonpai_pswd)) {
			$Pass_Err = 0;
		} else {
			$Pass_Err = 2;
		}
	} else {
		$Pass_Err = 1;
	}
	if ($Pass_Err == 2) {
		$sql = "SELECT * FROM M_SUPERPWD";
		$result = db_exec($sql);
		if (pg_numrows($result)) {
			$fetch = pg_fetch_object($result, 0);
			$super_pwd = $fetch->super_pwd;
			if ($w_melonpai_pswd == trim($super_pwd)) {
				$Pass_Err = 0;
			}
		}
	}
	if ($Pass_Err == 0) {
		get_shori_kensuu($melonpai_id, $mitsukuroi, $suisenbun);
		$melonpai_pswd = $w_melonpai_pswd;
		include("mypage.html");
	}
	if ($Pass_Err == 1) {
		$login_err = "入力したメロンパイIDは登録されていないようです。";
		include("login_err_msg.php");
		exit();
	} elseif ($Pass_Err == 2) {
		$login_err = "パスワードが正しくないようです。";
		include("login_err_msg.php");
		exit();
	}
}

?>