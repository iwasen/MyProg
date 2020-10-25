<?php
/*======================================================================================*/
/*	hakkousya-henkou1.php								*/
/*											*/
/*======================================================================================*/
$conn = db_conxxxt();
$sql1 = mp_make_sql ("SELECT_M_PUBLISHER");
$sql = $sql1.$mail_add_mainwk;
$stmt = OCIParse($conn, $sql);
$ret01 = OCIbindbyname ($stmt, ":PUBLISHER_ID", &$publisher_id, -1);
OCIDefinebyname($stmt, "MAIL_ADD_MAIN", &$mail_add_main);
OCIDefinebyname($stmt, "MAIL_ADD_SUB", &$mail_add_sub);
OCIDefinebyname($stmt, "OWNER_NM_KANJ", &$owner_nm_kanj);
OCIDefinebyname($stmt, "OWNER_NM_KANA", &$owner_nm_kana);
OCIDefinebyname($stmt, "OWNER_ZIP1", &$owner_zip1);
OCIDefinebyname($stmt, "OWNER_ZIP2", &$owner_zip2);
OCIDefinebyname($stmt, "OWNER_PREFEC", &$owner_prefec);
OCIDefinebyname($stmt, "OWNER_ADD", &$owner_add);
OCIDefinebyname($stmt, "OWNER_TEL", &$owner_tel);
OCIDefinebyname($stmt, "OWNER_PSWD", &$owner_pswd);
OCIDefinebyname($stmt, "PT_MELONPAN", &$pt_melonpan);
OCIDefinebyname($stmt, "PT_MELMEL", &$pt_melmel);
$ncols = OCIExecute($stmt, OCI_DEFAULT);
if (OCIFetch($stmt)) {
	$mail_add_main = trim($mail_add_main);
	$mail_add_sub = trim($mail_add_sub);
	$owner_nm_kanj = trim($owner_nm_kanj);
	$owner_nm_kana = trim($owner_nm_kana);
	$owner_zip1 = trim($owner_zip1);
	$owner_zip2 = trim($owner_zip2);
	$owner_prefec = trim($owner_prefec);
	$owner_add = trim($owner_add);
	$owner_tel = trim($owner_tel);
	$owner_pswd = trim($owner_pswd);
	include ("hakkousya-henkou1.html");
} else {
	$print_msg =  "指定された発行者ＩＤはありません。";
	include ("err-msg.html");
}
db_logoff();

?>
