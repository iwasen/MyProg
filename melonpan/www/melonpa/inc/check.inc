<?
function reg_check($addr) {
	if (strpos($addr, "@") === false ) {
		return false;
	}else{
		list($user,$domain) = split("@", $addr);
	}

	$domain = "*@".strtolower($domain);

	$sql = "select count(*) as COUNT from T_MAILADDR_DISABLED where MAIL_ADDR = '$addr' or MAIL_ADDR = '$domain'";
	$result = db_exec($sql);

	if (pg_numrows($result)) {
		$fetch = pg_fetch_object($result, 0);
		$addr_cnt = $fetch->count;
	}else{
		$addr_cnt = 1;
	}
	
	if ($addr_cnt == 0){
		return true;
	}else{
		return false;
	}
}

//Myめろんぱんreader_idチェック
function reader_check($addr) {
	$sql = "select READER_ID from M_MELONPA where mail_addr='$addr' order by READER_ID desc";
	$result = db_exec($sql);
	if (pg_numrows($result)) {
		$fetch = pg_fetch_object($result, 0);
		return $fetch->reader_id;
	} else {
		return 0;
	}
}
?>