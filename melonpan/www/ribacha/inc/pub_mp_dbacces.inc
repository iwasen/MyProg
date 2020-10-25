<?php
function pub_update ( $conn, $publisher_id, $mail_add_main, $mail_add_sub, $owner_nm_kanj,
	    		$owner_nm_kana, $owner_zip1, $owner_zip2, $owner_prefec, $owner_add,
	    		$owner_tel, $owner_pswd) {

	$l_ret = FALSE;

	if ( pub_mp_chkrec ($conn, $publisher_id) ) {
		$sql = mp_make_sql ("UPDATE_M_PUBLISHER");
		$stmt = OCIParse($conn, $sql);
	} else {
		$sql = mp_make_sql ("INSERT_M_PUBLISHER");
		$stmt = OCIParse($conn, $sql);
		$owner_status_flg = "00";
		$pt_melonpan = 0;
		$pt_melmel = 0;
	 	OCIbindbyname ($stmt, ":OWNER_STATUS_FLG", &$owner_status_flg, -1);
	 	OCIbindbyname ($stmt, ":PT_MELONPAN", &$pt_melonpan, -1);
		OCIbindbyname ($stmt, ":PT_MELMEL", &$pt_melmel,  -1);
	}

	$mail_add_main = trim($mail_add_main);
	$mail_add_sub = trim($mail_add_sub);
	$owner_nm_kanj = trim($owner_nm_kanj);
	$owner_nm_kana = trim($owner_nm_kana);
	$owner_zip = trim($owner_zip);
	$owner_prefec = trim($owner_prefec);
	$owner_add = trim($owner_add);
	$owner_tel = trim($owner_tel);
	$owner_pswd = trim($owner_pswd);
/*
	OCIbindbyname ($stmt, ":PUBLISHER_ID", &$publisher_id, -1);
	OCIbindbyname ($stmt, ":MAIL_ADD_MAIN", &$mail_add_main, strlen($mail_add_main)+1);
	OCIbindbyname ($stmt, ":MAIL_ADD_SUB", &$mail_add_sub, strlen($mail_add_sub)+1);
	OCIbindbyname ($stmt, ":OWNER_NM_KANJ", &$owner_nm_kanj, strlen($owner_nm_kanj)+1);
	OCIbindbyname ($stmt, ":OWNER_NM_KANA", &$owner_nm_kana, strlen($owner_nm_kana)+1);
	OCIbindbyname ($stmt, ":OWNER_ZIP", &$owner_zip, strlen($owner_zip)+1);
	OCIbindbyname ($stmt, ":OWNER_PREFEC", &$owner_prefec, strlen($owner_prefec)+1);
	OCIbindbyname ($stmt, ":OWNER_ADD", &$owner_add, strlen($owner_add)+1);
	OCIbindbyname ($stmt, ":OWNER_TEL", &$owner_tel, strlen($owner_tel)+1);
	OCIbindbyname ($stmt, ":OWNER_PSWD", &$owner_pswd, strlen($owner_pswd)+1);
*/
	OCIbindbyname ($stmt, ":PUBLISHER_ID", &$publisher_id, -1);
	OCIbindbyname ($stmt, ":MAIL_ADD_MAIN", &$mail_add_main, -1);
	OCIbindbyname ($stmt, ":MAIL_ADD_SUB", &$mail_add_sub, -1);
	OCIbindbyname ($stmt, ":OWNER_NM_KANJ", &$owner_nm_kanj, -1);
	OCIbindbyname ($stmt, ":OWNER_NM_KANA", &$owner_nm_kana, -1);
	OCIbindbyname ($stmt, ":OWNER_ZIP1", &$owner_zip1, -1);
	OCIbindbyname ($stmt, ":OWNER_ZIP2", &$owner_zip2, -1);
	OCIbindbyname ($stmt, ":OWNER_PREFEC", &$owner_prefec, -1);
	OCIbindbyname ($stmt, ":OWNER_ADD", &$owner_add, -1);
	OCIbindbyname ($stmt, ":OWNER_TEL", &$owner_tel, -1);
	OCIbindbyname ($stmt, ":OWNER_PSWD", &$owner_pswd, -1);

	$l_ret = OCIExecute($stmt, OCI_DEFAULT);

	return $l_ret;
}
function pub_mp_chkrec ($conn, $publisher_id) {
	$sql = mp_make_sql ("SELECT_M_PUBLISHER");
	$stmt = OCIParse($conn, $sql);
	$ret01 = OCIbindbyname ($stmt, ":PUBLISHER_ID", &$publisher_id, -1);
	$ncols = OCIExecute($stmt, OCI_DEFAULT);
	$retStat = OCIFetch ($stmt);
	$ret01 = OCIFreeStatement($stmt);
	return $retStat;
}
function pub_pubdelrea_insert ($conn, $pub_del_id, $publisher_id, $reason) {
	if ( pub_tpds_chkrec ($conn, $pub_del_id) ) {
		$sql = mp_make_sql ("UPDATE_T_PUB_DELREASON");
		$stmt = OCIParse($conn, $sql);
	} else{
		$sql = mp_make_sql ("INSERT_T_PUB_DELREASON");
		$stmt = OCIParse($conn, $sql);
	}
	OCIbindbyname ($stmt, ":PUB_DEL_ID", &$pub_del_id, -1);
	OCIbindbyname ($stmt, ":PUBLISHER_ID", &$publisher_id, -1);
	OCIbindbyname ($stmt, ":REASON", &$reason, -1);
	$retStat = OCIExecute($stmt, OCI_DEFAULT);
	$ret01 = OCIFreeStatement($stmt);
	return $retStat;
}
function pub_tpds_chkrec ($conn, $pub_del_id) {
	$sql = mp_make_sql ("SELECT_T_PUB_DELREASON_ID");
	$stmt = OCIParse($conn, $sql);
	$ret01 = OCIbindbyname ($stmt, ":PUB_DEL_ID", &$pub_del_id, -1);
	$ncols = OCIExecute($stmt, OCI_DEFAULT);
	$retStat = OCIFetch ($stmt);
	$ret01 = OCIFreeStatement($stmt);
	return $retStat;
}

?>
