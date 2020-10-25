<?php
function pub_passchk($conn, $publisher_id,$owner_pswd_y) {
	$sql = "select super_pwd from m_superpwd where pwd_id = 1";
	$stmt = OCIParse($conn, $sql);
	OCIDefinebyname($stmt, "SUPER_PWD", &$super_pwd);
	OCIExecute($stmt);
	OCIFetch($stmt);
	if (trim($super_pwd) == $owner_pswd_y) {;
	} else {
		$sql = "select publisher_id from m_publisher where publisher_id = $publisher_id and owner_pswd='$owner_pswd_y'" ;
		$stmt = OCIParse($conn, $sql);
		OCIDefinebyname($stmt, "PUBLISHER_ID", &$publisher_id);
		OCIDefinebyname($stmt, "OWNER_PSWD", &$owner_pswd);
		OCIDefinebyname($stmt, "OWNER_RIBA_FLG", &$owner_riba_flg);
		OCIExecute($stmt);
		$ncols = OCIFetchStatement($stmt,&$resarray);
		if ($ncols < 1) {
			ocifreestatement($stmt);
			$error_msg = "発行者パスワードが一致しません";
			include("tmpl_html/pass_err_msg.html");
			db_logoff();
			exit();
		}
	}
}

?>
