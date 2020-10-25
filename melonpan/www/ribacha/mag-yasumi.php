<?php

/*======================================================================================*/
/*	mag-yasumi.php									*/
/*											*/
/*======================================================================================*/
$conn = db_conxxxt();
pub_passchk($conn, $publisher_id,$owner_pswd);
$sql1 = mp_make_sql ("SELECT_M_PUBLISHER");
$stmt1 = OCIParse($conn, $sql1);
$ret01 = OCIbindbyname ($stmt1, ":PUBLISHER_ID", &$publisher_id, -1);
OCIDefinebyname($stmt1, "MAIL_ADD_MAIN", &$mail_add_main);
$ncols = OCIExecute($stmt1);
$lcount = 0;
if (OCIFetch($stmt1)) {
	$sql = "select * from M_MAGAZINE where mag_id = :MAG_ID";
	$stmt = OCIParse($conn, $sql);
	OCIbindbyname ($stmt, ":MAG_ID", &$mag_id, -1);
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
	$ncols = OCIExecute($stmt);
	$lcount = 0;
	if (OCIFetch($stmt)) {
		include ("mag-yasumi.html");
	} else {
		print "指定されたメルマガＩＤはありません。";
	}
} else {
	print "指定された発行者ＩＤはありません。";
}
db_logoff();

?>