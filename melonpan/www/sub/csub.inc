<?php
/*==========================================================*/
/*	パスワードチェック（マガジン詳細）						*/
/*　　	from melonpa/inc/pass_chek.inc						*/
/*==========================================================*/
function pass_check($conn,$mail_addr,$reader_pswd) {
	$sql = "select reader_pswd from M_MELONPA where mail_addr = '$mail_addr'";
	$result = db_exec($sql);
	if (pg_numrows($result)) {
		$fetch = pg_fetch_object($result, 0);
		$pswd = $fetch->reader_pswd;
		if ($reader_pswd == trim($pswd)) {
		} else {
			$sql2 = "SELECT * FROM M_SUPERPWD WHERE pwd_id = 1";
			$result = db_exec($sql2);
			if (pg_numrows($result)) {
				$fetch = pg_fetch_object($result, 0);
				$passwd = $fetch->super_pwd;
				if ($reader_pswd == trim($passwd)) {
				} else {
					$error_msg = "Myめろんぱんパスワードが一致しません";
					include("../sub/pass_err_msg.html");
					exit();
				}
			}
		}
	}
}
?>
