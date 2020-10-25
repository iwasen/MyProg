<?php
function db_open() {
	global  $gConn;

	if (!$gConn) {
		if (($host = $_ENV['MELONPAN_DB']) == '')
			$host = 'localhost';
		$gConn = pg_conxxxt("host=$host port=5432 dbname=melonpan user=postgres");
	}

	return $gConn;
}

// SQL文実行
function db_exec($sql) {
	return pg_exec(db_open(), $sql);
}

/****************************************************************
 Main
****************************************************************/
db_open();
if ($id != "") {
	$sql =  "select reader_id,mail_addr,log_key,log_key_flg,mail_addr,reader_pswd from m_melonpa where reader_id = $id";
	$result = db_exec($sql);
	if ($ncols = pg_numrows($result)) {
		$fetch = pg_fetch_object($result, 0);
		$reader_id = $fetch->reader_id;
		$mail_addr = $fetch->mail_addr;
		$log_key_flg = $fetch->log_key_flg;
		$log_key = $fetch->log_key;
		$mail_addr = $fetch->mail_addr;
		$reader_pswd = $fetch->reader_pswd;
	}
} else {
	$ncols = 0;
}
if ($ncols < 1) {
	$error_msg = "該当するメロンパIDは登録されていません";
	include("key_error.html");
	exit();
}

if ($log_key_flg == 1) {
	$error_msg = "既にキー認証が終了しています";
	include("key_error1.html");
	exit();
}
if ($key == $log_key) {
	$sql =  "update m_melonpa set log_key_flg = 1 where reader_id = $id";
	db_exec($sql);

	$sql = "update t_mailaddr set reader_id=$reader_id where mail_addr='$mail_addr'";
	db_exec($sql);

	setcookie("cookie_reader_id", $reader_id, time()+604800);
	setcookie("cookie_mail_addr", trim($mail_addr), time()+604800);
	setcookie("cookie_reader_pswd", trim($reader_pswd), time()+604800);
	include("melonpan/melonpa-keykakunin.html");
	exit();
} else {
	$error_msg = "認証キーが違います";
	include("key_error.html");
	exit();
}
?>