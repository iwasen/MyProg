<?php
/*======================================================================================*/
/*	hakkousya-kaijyo.php								*/
/*											*/
/*======================================================================================*/
$p_titl ="発行者解除";
$sql = mp_make_sql ("SELECT_M_PUBLISHER");
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
$ncols = OCIExecute($stmt);
if (OCIFetch($stmt)) {
	include ("hakkousya-kaijyo.html");
} else {
	$print_msg =  "指定された発行者ＩＤはありません。";
	include ("err-msg.html");
}

?>
