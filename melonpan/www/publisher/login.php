<?php
include ("../inc/com_path.inc");
include ("$compath/com_db_func.inc");
include ("$compath/sql_mm.inc");
include ("$compath/sql_mp.inc");
include ("$incpath/pub_mp_dbacces.inc");
include ("$compath/sql_mcom.inc");
include ("$compath/com_mm_func.inc");

function set_global () {
	global $inc_path;

	include ("../inc/com_path.inc");
	$inc_path = $incpath;
}
function pub_login_err($login_err) {
	if (strlen($login_err) > 0) {
		print "<p align=\"center\">　</p>\n";
		print "<div align=\"center\"><font color=\"#006600\">\n";
		print "-" . $login_err . "-</font></div><BR>\n";
	}
}

/****************************************************************
 Main
****************************************************************/
set_global ();
$mail_add_main = strtolower($mail_add_main);

if ($mail_add_main == "" or $owner_pswd_r == "" ) {
	if ($nyusitu)
		$login_err = "すべての項目を入力してください。";
	include ("hakkou-login2.html");
	exit();
} else {
	$Pass_Err = 1;

	$sql = "select publisher_id,owner_pswd,owner_riba_flg from m_publisher where mail_add_main = '$mail_add_main'";
	$result = db_exec($sql);
	if (pg_numrows($result)) {
		$fetch = pg_fetch_object($result, 0);
		$publisher_id = $fetch->publisher_id;
		$owner_pswd = $fetch->owner_pswd;
		$owner_riba_flg = $fetch->owner_riba_flg;
		if ($owner_pswd_r == trim($owner_pswd)) {
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
			if ($owner_pswd_r == trim($super_pwd)) {
				$Pass_Err = 0;
			}
		}
	}
	if ($Pass_Err == 0) {
		if ($owner_riba_flg == 1) {
			$action = "../ribacha/pub_index.php";
		} else {
			$action = "pub_index.php";
		}
		print"<HTML>\n";
		print"<BODY onLoad=\"document.form1.submit();\">\n";
		print"<form action=\"$action\" method=\"post\" name=\"form1\">\n";
		print"<INPUT type=\"hidden\" name=\"mail_add_main\" value=\"$mail_add_main\">\n";
		print"<INPUT type=\"hidden\" name=\"publisher_id\" value=\"$publisher_id\">\n";
		print"<INPUT type=\"hidden\" name=\"owner_pswd_r\" value=\"$owner_pswd_r\">\n";
		print"<INPUT type=\"hidden\" value=\"入室\" name=\"nyusitu\">\n";
		print"</form> \n";
		print"</body>\n";
		print"</html>\n";
	}
	if ($Pass_Err == 1) {
		$login_err = "入力した基本メールアドレスは登録されていないようです。";
		include ("hakkou-login2.html");
		exit();
	} elseif ($Pass_Err == 2) {
		$login_err = "パスワードが正しくないようです。";
		include ("hakkou-login2.html");
		exit();
	}
}

?>