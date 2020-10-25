<?php
include ("../inc/com_path.inc");
include ("$compath/com_db_func.inc");
include ("$compath/com_mm_func.inc");
include ("$compath/sql_mcom.inc");
include ("$incpath/mex_passchk.inc");
include ("../sub/head.inc");

/****************************************************************
 Main
****************************************************************/
$conn = db_conxxxt();
mex_passchk($conn, $melonpai_id, $melonpai_pswd);
$sql2 = "SELECT * FROM M_PUBLISHER WHERE publisher_id = ";
$sql = $sql2 . $publisher_id;
$stmt = OCIParse($conn, $sql);
OCIDefinebyname($stmt, "MAIL_ADD_MAIN", &$mail_add_main);
OCIDefinebyname($stmt, "MAIL_ADD_SUB", &$mail_add_sub);
OCIDefinebyname($stmt, "OWNER_NM_KANJ", &$owner_nm_kanj);
OCIDefinebyname($stmt, "OWNER_NM_KANA", &$owner_nm_kana);
OCIDefinebyname($stmt, "OWNER_ZIP1", &$owner_zip);
OCIDefinebyname($stmt, "OWNER_ZIP2", &$owner_zip);
OCIDefinebyname($stmt, "OWNER_PREFEC", &$owner_prefec);
OCIDefinebyname($stmt, "OWNER_ADD", &$owner_add);
OCIDefinebyname($stmt, "OWNER_TEL", &$owner_tel);
OCIDefinebyname($stmt, "OWNER_PSWD", &$owner_pswd);
OCIDefinebyname($stmt, "PT_MELONPAN", &$pt_melonpan);
OCIDefinebyname($stmt, "PT_MELMEL", &$pt_melmel);
$ncols = OCIExecute($stmt, OCI_DEFAULT);
$lcount = 0;
if (OCIFetch($stmt)) {
	$sql2 = "SELECT * FROM M_MAGAZINE WHERE MAG_ID = ";
	$sql = $sql2 . $mag_id;
	$stmt = OCIParse($conn, $sql);
	OCIDefinebyname($stmt, "MAIL_ADD_PUBLISH", &$mail_add_publish);
	OCIDefinebyname($stmt, "MAG_NM", &$mag_nm);
	OCIDefinebyname($stmt, "MAG_INTRO_LONG", &$mag_intro_long);
	OCIDefinebyname($stmt, "MAG_INTRO_SHORT", &$mag_intro_short);
	OCIDefinebyname($stmt, "MAG_HP", &$mag_hp);
	OCIDefinebyname($stmt, "MAG_TYPE", &$mag_type);
	OCIDefinebyname($stmt, "MAG_CYCLE", &$mag_cycle);
	OCIDefinebyname($stmt, "MAG_CYCLE_DAY", &$mag_cycle_day);
	OCIDefinebyname($stmt, "MAG_CATG_1", &$mag_catg_1);
	OCIDefinebyname($stmt, "MAG_CATG_2", &$mag_catg_2);
	OCIDefinebyname($stmt, "MAG_BG_1", &$mag_bg_1);
	OCIDefinebyname($stmt, "MAG_BG_2", &$mag_bg_2);
	OCIDefinebyname($stmt, "MAG_READER_SEX", &$mag_reader_sex);
	OCIDefinebyname($stmt, "MAG_READER_AGE", &$mag_reader_age);
	OCIDefinebyname($stmt, "MAG_READER_AREA", &$mag_reader_area);
	OCIDefinebyname($stmt, "MAG_READER_OCCUP", &$mag_reader_occup);
	OCIDefinebyname($stmt, "MAG_BN_PUB", &$mag_bn_pub);
	OCIDefinebyname($stmt, "ML_TYPE", &$ml_type);
	OCIDefinebyname($stmt, "MAG_STATUS_FLG", &$mag_status_flg);
	OCIDefinebyname($stmt, "MAG_PUB_STATUS_FLG", &$mag_pub_status_flg);
	OCIDefinebyname($stmt, "MAG_PUB_STOP_FLG", &$mag_pub_stop_flg);
	OCIDefinebyname($stmt, "MELMEL_ID", &$melmel_id);
	$ncols = OCIExecute($stmt, OCI_DEFAULT);
	$lcount = 0;
	if (OCIFetch($stmt)) {
		include("tmpl_html/mp-mag-syousai.html");
	}
}
db_logoff();
?>
