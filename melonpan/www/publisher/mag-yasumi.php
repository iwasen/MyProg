<?php

/*======================================================================================*/
/*	mag-yasumi.php									*/
/*											*/
/*======================================================================================*/
include ("inc/error_msg_list.inc");

/****************************************************************
 Main
****************************************************************/
pub_passchk($conn, $publisher_id,$owner_pswd);
$sql1 = mp_make_sql ("SELECT_M_PUBLISHER");
$sql1 =  "SELECT * FROM M_PUBLISHER WHERE publisher_id = $publisher_id";
$result = db_exec($sql1);
$lcount = 0;
if (pg_numrows($result)) {
	$fetch = pg_fetch_object($result, 0);
	$mail_add_main = $fetch->mail_add_main;
	$sql = "select * from M_MAGAZINE where mag_id = '$mag_id'";
	$result = db_exec($sql);
	$lcount = 0;
if (pg_numrows($result)) {
	$fetch = pg_fetch_object($result, 0);
	$mail_add_publish = $fetch->mail_add_publish;
	$mag_nm = $fetch->mag_nm;
	$melmel_id = $fetch->melmel_id;
	$mag_intro_long = $fetch->mag_intro_long;
	$mag_intro_short = $fetch->mag_intro_short;
	$mag_hp = $fetch->mag_hp;
	$mag_type = $fetch->mag_type;
	$mag_cycle_day = $fetch->mag_cycle_day;
	$mag_cycle = $fetch->mag_cycle;
	$mag_catg_1 = $fetch->mag_catg_1;
	$mag_catg_2 = $fetch->mag_catg_2;
	$mag_bg_1 = $fetch->mag_bg_1;
	$mag_bg_2 = $fetch->mag_bg_2;
	$mag_reader_sex = $fetch->mag_reader_sex;
	$mag_reader_age = $fetch->mag_reader_age;
	$mag_reader_area = $fetch->mag_reader_area;
	$mag_reader_occup = $fetch->mag_reader_occup;
	$mag_bn_pub = $fetch->mag_bn_pub;
	$ml_type = $fetch->ml_type;
	$mag_status_flg = $fetch->mag_status_flg;
	$mag_pub_status_flg = $fetch->mag_pub_status_flg;
	$mag_pub_stop_flg = $fetch->mag_pub_stop_flg;
	if (trim($melmel_id) == "00000") {
		$melmel_id = "";
	}
		include ("mag-yasumi.html");
	} else {
		print $em_yasumi_1;
	}
} else {
	print $em_yasumi_2;
}
?>