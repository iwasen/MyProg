<?php
include("inc/pass_check.inc");
include ("inc/error_msg_list.inc");

/****************************************************************
 Main
****************************************************************/
//$conn = db_conxxxt();
if ($mail_addr != "") {pass_check($conn,trim($mail_addr),trim($reader_pswd)); }
$sql0 =  "select pt_melonpan from m_melonpa where mail_addr = ";
$sql0 = $sql0 .  "'".$mail_addr."'";
$result = db_exec($sql0);
if (pg_numrows($result)) {
	$fetch = pg_fetch_object($result, 0);
	$pt_melonpan = $fetch->pt_melonpan;
}

if ($pt_melonpan == "") { $pt_melonpan = "0";}

$sql1 =  "select * from t_recomend where reader_id = ";
$sql = $sql1 .  $reader_id;
$sql = $sql . " and mag_id = '$mag_id'";

$result = db_exec($sql);
if (pg_numrows($result)) {
	$fetch = pg_fetch_object($result, 0);
	$recomend_id = $fetch->recomend_id;
	$recom_title = $fetch->recom_titl;
	$recom_text = $fetch->recom_text;
	$recom_nic = $fetch->recom_nic;
	$sanks_tip_point = $fetch->recom_tip;
	$open_flg = $fetch->open_flg;
} else {
	$recomend_id = "";
 	$recom_title = "";
 	$recom_text = "";
 	$recom_nic = "";
 	$sanks_tip_point = "0";
 	$open_flg = "0";
}
if ($open_flg == 0 ) {
	$sql1 =  "select * from t_mailaddr where mail_addr = ";
	$sql = $sql1 .  "'".$mail_addr."'";
	$sql = $sql . " and mag_id = '$mag_id'";
	$result1 = db_exec($sql);
	$lcount = 0;
	if (pg_numrows($result1)) {
		$sql1 =  "select * from m_magazine where mag_id = '$mag_id'";
		$result2 = db_exec($sql1);

		if (pg_numrows($result2)) {
			$fetch = pg_fetch_object($result2, 0);
			$mag_nm = $fetch->mag_nm;
			include ("tmpl_html/recomend1.html");
		} else {
			$print_msg = $em_recomend_11;
			$p_titl = "¥á¥í¥ó¥ÑÅÐÏ¿";
			include ("tmpl_html/err-msg.html");
		}
	} else {
		$print_msg = $em_recomend_12;
		$p_titl = "¥á¥í¥ó¥ÑÅÐÏ¿";
		include ("tmpl_html/err-msg.html");
	}
} else {
	$print_msg = $em_recomend_13;
	$p_titl = "¥á¥í¥ó¥ÑÅÐÏ¿";
	include ("tmpl_html/err-msg.html");
}
?>
