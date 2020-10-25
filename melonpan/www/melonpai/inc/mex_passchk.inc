<?php
function mex_passchk($conn, $melonpai_id, $melonpai_pswd_r) {
	$sql = "select melonpai_pswd from m_melonpai where melonpai_id = $melonpai_id" ;
	$result = db_exec($sql);
	if (pg_numrows($result)) {
		$fetch = pg_fetch_object($result, 0);
		$melonpai_pswd = $fetch->melonpai_pswd;
		if ($melonpai_pswd_r == trim($melonpai_pswd)) {
		} else {
			$sql2 = "SELECT * FROM M_SUPERPWD WHERE pwd_id = 1";
			$result = db_exec($sql2);
			if (pg_numrows($result)) {
				$fetch = pg_fetch_object($result, 0);
				$passwd = $fetch->super_pwd;
				if ($melonpai_pswd_r == trim($passwd)) {
				} else {
				$error_msg = "メルマガナビ パスワードが一致しません";
					include("tmpl_html/pass_err_msg.html");
					exit();
				}
			}
		}
	}
}

?>
