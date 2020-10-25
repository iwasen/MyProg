<?php
function pub_passchk($conn, $publisher_id,$owner_pswd_y) {

	$sql = "select owner_pswd from m_publisher where publisher_id = $publisher_id" ;
	$result = db_exec($sql);
	if (pg_numrows($result)) {
		$fetch = pg_fetch_object($result, 0);
		$owner_pswd = $fetch->owner_pswd;
		$owner_riba_flg = $fetch->owner_riba_flg;
		if ($owner_pswd_y == trim($owner_pswd)) {
		} else {
			$sql2 = "SELECT * FROM M_SUPERPWD WHERE pwd_id = 1";
			$result = db_exec($sql2);
			if (pg_numrows($result)) {
				$fetch = pg_fetch_object($result, 0);
				$passwd = $fetch->super_pwd;
				if ($owner_pswd_y == trim($passwd)) {
				} else {
					$error_msg = "発行者パスワードが一致しません";
					include("tmpl_html/pass_err_msg.html");
					exit();
				}
			}
		}
	}
}
/* **
function pub_passchk($conn, $publisher_id,$owner_pswd_y) {
		$sql = "select owner_pswd from m_publisher where publisher_id = $publisher_id" ;
		$stmt = OCIParse($conn, $sql);
		OCIDefinebyname($stmt, "OWNER_PSWD", &$owner_pswd);
		OCIDefinebyname($stmt, "OWNER_RIBA_FLG", &$owner_riba_flg);
		OCIExecute($stmt, OCI_DEFAULT);
		if (OCIFetch($stmt)) {
			if ($owner_pswd_y == trim($owner_pswd)) {
			} else {
				OCIFreeStatement($stmt);
				$sql2 = "SELECT * FROM M_SUPERPWD WHERE pwd_id = 1";
				$stmt = OCIParse($conn, $sql2);
				OCIDefinebyname($stmt, "SUPER_PWD", &$passwd);
				$ncols = OCIExecute($stmt, OCI_DEFAULT);
					if (OCIFetch($stmt)) {
						if ($owner_pswd_y == trim($passwd)) {
						} else {
						ocifreestatement($stmt);
						$error_msg = "発行者パスワードが一致しません";
						include("tmpl_html/pass_err_msg.html");
						db_logoff();
						exit();
						}
					}
			}
		}
}
*/
?>
