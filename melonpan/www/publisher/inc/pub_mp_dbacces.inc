<?php
function pub_update ( $conn, $publisher_id, $mail_add_main, $mail_add_sub, $owner_nm_kanj,
	    		$owner_nm_kana, $owner_zip1, $owner_zip2, $owner_prefec, $owner_add,
	    		$owner_tel, $owner_pswd) {

	$l_ret = FALSE;
	//文字列アンクォート// 12/29 追加
//	$owner_nm_kanj = stripcslashes($owner_nm_kanj);
//	$owner_nm_kana = stripcslashes($owner_nm_kana);
//	$owner_add = stripcslashes($owner_add);

	$mail_add_main = trim($mail_add_main);
	$mail_add_sub = trim($mail_add_sub);
	$owner_nm_kanj = trim($owner_nm_kanj);
	$owner_nm_kana = trim($owner_nm_kana);
	$owner_zip = trim($owner_zip);
	$owner_prefec = trim($owner_prefec);
	$owner_add = trim($owner_add);
	$owner_tel = trim($owner_tel);
	$owner_pswd = trim($owner_pswd);

	if ( pub_mp_chkrec ($conn, $publisher_id) ) {
		$sql = "UPDATE  M_PUBLISHER SET
			mail_add_main = '$mail_add_main',
			mail_add_sub = '$mail_add_sub',
			owner_nm_kanj = '$owner_nm_kanj',
			owner_nm_kana = '$owner_nm_kana',
			owner_zip1 = '$owner_zip1',
			owner_zip2 = '$owner_zip2',
			owner_prefec = '$owner_prefec',
			owner_add = '$owner_add',
			owner_tel = '$owner_tel',
			owner_pswd = '$owner_pswd',
			modefy_dt   = 'now'
			WHERE publisher_id = $publisher_id";
	} else {

		$owner_status_flg = "00";
		$pt_melonpan = 0;
		$pt_melmel = 0;

		$sql = "INSERT INTO M_PUBLISHER ( publisher_id, mail_add_main, "
			. "mail_add_sub, "
			. "owner_nm_kanj, owner_nm_kana, owner_zip1, "
			. "owner_zip2, "
			. "owner_prefec, owner_add, owner_tel, "
			. "owner_pswd, owner_status_flg, "
			. "pt_melonpan, pt_melmel, modefy_dt, regist_dt ) "
			. "VALUES ( $publisher_id, '$mail_add_main', '$mail_add_sub', "
			. "'$owner_nm_kanj', '$owner_nm_kana', '$owner_zip1', "
			. "'$owner_zip2', "
			. "'$owner_prefec', '$owner_add', '$owner_tel', '$owner_pswd', "
			. "'$owner_status_flg', $pt_melonpan, $pt_melmel, 'now', 'now')";
	}

	$l_ret = db_exec($sql);

	return $l_ret;
}
function pub_mp_chkrec ($conn, $publisher_id) {
	$sql = "SELECT * FROM M_PUBLISHER WHERE publisher_id = $publisher_id";
	$result = db_exec($sql);
	$retStat = pg_numrows($result);
	return $retStat;
}
function pub_pubdelrea_insert ($conn, $pub_del_id, $publisher_id, $reason) {
	if ( pub_tpds_chkrec ($conn, $pub_del_id) ) {
		$sql = "UPDATE  T_PUB_DELREASON SET publisher_id = $publisher_id, reason = '$reason', modefy_dt = 'now' WHERE pub_del_id = $pub_del_id";
	} else{
		$sql = "INSERT INTO T_PUB_DELREASON (pub_del_id , publisher_id , reason , modefy_dt )
				VALUES ($pub_del_id, $publisher_id, '$reason', 'now')";
	}
	$retStat = db_exec($sql);
	return $retStat;
}
function pub_tpds_chkrec ($conn, $pub_del_id) {
	$sql = "SELECT * FROM T_PUB_DELREASON WHERE pub_del_id = $pub_del_id";
	$result = db_exec($sql);
	$retStat = pg_numrows($result);
	return $retStat;
}

?>
