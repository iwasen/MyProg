<?php

function MenberS() {
	$conn = db_conxxxt();
	$sql1 = rm_make_sql ("SELECT_M_MELONPA_MAIL_ADR_DATE");
	$sql = $sql1 ."'".  $mail_addr ."'";
	$stmt = OCIParse($conn, $sql);
	$ncols = OCIExecute($stmt, OCI_DEFAULT);
	if (!OCIFetch($stmt)) {
		print "<form name=\"rmform710\" action=\"MelonpanRef.php\" method=\"post\">\n";
		print "<p>まだMyめろんぱん登録がおすみでないかたは<a href=\"JavaScript:document.rmform710.submit();\">こちら！</a></p>\n";
		print "<input type=\"hidden\" value=\"$reader_id\" name=\"reader_id\">\n";
		print "<input type=\"hidden\" value=\"$mail_addr\" name=\"mail_addr\">\n";
		print "<input type=\"hidden\" value=\"regist" name=\"PROCESS_FLG\">\n";
		print "</form>\n";
	}
	db_logoff();
}

/****************************************************************
 Main
****************************************************************/
$conn = db_conxxxt();
$sql1 = rm_make_sql ("SELECT_M_MELONPA_MAIL_ADR_DATE");
$sql = $sql1 ."'".  $mail_addr ."'";
$stmt = OCIParse($conn, $sql);
$ncols = OCIExecute($stmt, OCI_DEFAULT);
if (OCIFetch($stmt)) {
	include ("mypage-login2.html");
} else {
	include ("regist.html");

}

db_logoff();

?>
