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
$conn = db_conxxxt();
$sql1 = rm_make_sql ("SELECT_M_MELONPA_MAIL_ADR");
$sql = $sql1 ."'".  $mail_addr ."'";
$stmt = OCIParse($conn, $sql);
OCIDefinebyname($stmt, "PT_MELONPAN", &$pt_melonpan);
OCIDefinebyname($stmt, "READER_ID", &$reader_id);
$ncols = OCIExecute($stmt, OCI_DEFAULT);

if (OCIFetch($stmt)) {
	include ("point.html");
}
/*	else {
	$print_msg = "Myめろんぱん登録データが存在しません。";
	$p_titl = "Myめろんぱん登録";
	include ("err-msg.html");
}
*/
else {
	include ("mypage-login.php");
}

db_logoff();

?>
