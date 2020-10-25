<?php
/*======================================================================================*/
/*	hakkousya-henkou1.php								*/
/*											*/
/*======================================================================================*/
include ("inc/error_msg_list.inc");

/****************************************************************
 Main
****************************************************************/
$sql = "SELECT * FROM M_PUBLISHER WHERE publisher_id = $publisher_id";
$result = db_exec($sql);
if (pg_numrows($result)) {
	$fetch = pg_fetch_object($result, 0);
	$mail_add_main = $fetch->mail_add_main;
	$mail_add_sub = $fetch->mail_add_sub;
	$owner_nm_kanj = $fetch->owner_nm_kanj;
	$owner_nm_kana = $fetch->owner_nm_kana;
	$owner_zip1 = $fetch->owner_zip1;
	$owner_zip2 = $fetch->owner_zip2;
	$owner_prefec = $fetch->owner_prefec;
	$owner_add = $fetch->owner_add;
	$owner_tel = $fetch->owner_tel;
	$owner_pswd = $fetch->owner_pswd;
	$pt_melonpan = $fetch->pt_melonpan;
	$pt_melmel = $fetch->pt_melmel;

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
	$print_msg =  $em_henkou_1;
	include ("err-msg.html");
}

?>
