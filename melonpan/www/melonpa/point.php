<?php
/****************************************************************
 Main
****************************************************************/
$mail_addr = $cookie_mail_addr;
$reader_pswd = $cookie_reader_pswd;
$reader_id = $cookie_reader_id;
if ($mail_addr == "") {
	include("login.html");
	exit();
}

$sql1 = rm_make_sql ("SELECT_M_MELONPA_MAIL_ADR");
$sql = $sql1 ."'".  $mail_addr ."'";
$result = db_exec($sql);

if (pg_numrows($result)) {
	$fetch = pg_fetch_object($result, 0);
	$pt_melonpan = $fetch->pt_melonpan;
	$reader_id = $fetch->reader_id;
	include ("../mypage/mypage-point.html");
}
/*	else {
	$print_msg = "メロンパ登録データが存在しません。";
	$p_titl = "メロンパ登録";
	include ("err-msg.html");
}
*/
else {
	include ("mypage-login.php");
}

?>
