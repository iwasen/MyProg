<?php
/*======================================================================================*/
/*	kaijyo-kakunin.php								*/
/*											*/
/*======================================================================================*/
include ("inc/error_msg_list.inc");

/****************************************************************
 Main
****************************************************************/
$p_titl ="発行者解除";
$sql = "SELECT * FROM M_MAGAZINE WHERE publisher_id = $publisher_id"
			. " and (mag_pub_status_flg = '01' or mag_pub_status_flg = '03') "
			. " and (mag_pub_stop_flg <> '02') ";
$result = db_exec($sql);
if (!pg_numrows($result)) {
	$sql = mp_make_sql ("SELECT_M_PUBLISHER");
	$sql = "SELECT * FROM M_PUBLISHER WHERE publisher_id = $publisher_id";
	$result = db_exec($sql);
	if (pg_numrows($result)) {
		include ("kaijyo-kakunin.html");
	} else {
		$print_msg =  $em_kaijyo_1;
		include ("err-msg.html");
	}
} else {
	$sql = "SELECT * FROM M_PUBLISHER WHERE publisher_id = $publisher_id";
	$result = db_exec($sql);
	if (pg_numrows($result)) {
		$print_msg =  $em_kaijyo_2;
		include ("err-msg.html");
	}
}
?>
