<?php
include("inc/pass_check.inc");
include ("inc/error_msg_list.inc");

/****************************************************************
 Main
****************************************************************/
//$conn = db_conxxxt();
if ($mail_addr != "") {pass_check($conn,trim($mail_addr),trim($reader_pswd)); }

$err_flg = 0;
$print_msg = "";
$print_msg1 = "";
$print_msg3 = "";
$print_msg2 = "";

if ($sanks_bot == "¤¢¤²¤Ê¤¤") {
	$sanks_tip_point = 0;
}

if (strlen($recom_title) == 0 || strlen($recom_title) > 80) {
	$print_msg1 = $em_recomend_4;
	$err_flg = 1;
}
if (strlen($recom_text) == 0 || strlen($recom_text) > 800) {
	$print_msg2 =  $em_recomend_5;
	$err_flg = 1;
}
if (strlen($recom_nic) == 0 || strlen($recom_nic) > 30) {
	$print_msg3 =  $em_recomend_6;
	$err_flg = 1;

}
if (intval($sanks_tip_point) < 0) {
	$print_msg3 =  $em_recomend_14;
	$err_flg = 1;
}

if (!(ereg("^[0-9]{1,4}$", $sanks_tip_point))) {
	$print_msg3 =  $em_recomend_15;
	$err_flg = 1;
}

$print_msg = $print_msg1 .  $print_msg2 .  $print_msg3;

if ($err_flg == 0) {
	$sql1 = rm_make_sql ("SELECT_M_MELONPA_MAIL_ADR_DATE");
	$sql = $sql1 ."'".  $mail_addr ."'";
	$result = db_exec($sql);

	if ($pt_melonpan >= intval($sanks_tip_point) ) {
		if (pg_numrows($result)) {
			$fetch = pg_fetch_object($result, 0);
			$reader_id = $fetch->reader_id;
			$mail_addr = $fetch->mail_addr;
			$reader_birth = $fetch->reader_birth;
			$reader_pswd = $fetch->reader_pswd;
			$reader_sex = $fetch->reader_sex;
			$reader_mrg = $fetch->reader_mrg;
			$reader_zip1 = $fetch->reader_zip1;
			$reader_zip2 = $fetch->reader_zip2;
			$reader_occup = $fetch->reader_occup;
			$wk_year = $fetch->wk_year;
			$wk_month = $fetch->wk_month;
			$wk_day = $fetch->wk_day;

			$sql1 =  "select * from t_mailaddr where mail_addr = ";
			$sql = $sql1 .  "'".$mail_addr."'";
			$sql = $sql . " and mag_id = '$mag_id'";
			$result1 = db_exec($sql);
			$lcount = 0;
			if (pg_numrows($result1)) {
				$fetch = pg_fetch_object($result1, 0);
				$mag_id = $fetch->mag_id;

				$sql1 =  "select * from m_magazine where mag_id = '$mag_id'";
				$result2 = db_exec($sql1);
				if (pg_numrows($result2)) {
					$fetch = pg_fetch_object($result, 0);
					$mag_nm = $fetch->mag_nm;

					include ("tmpl_html/recomend2.html");
				} else {
					$print_msg = $em_recomend_7;
					$p_titl = "My¤á¤í¤ó¤Ñ¤óÅÐÏ¿";
					include ("tmpl_html/err-msg.html");
				}
			} else {
				$print_msg = $em_recomend_8;
				$p_titl = "My¤á¤í¤ó¤Ñ¤óÅÐÏ¿";
				include ("tmpl_html/err-msg.html");
			}
		} else {
			$print_msg = $em_recomend_9;
			$p_titl = "My¤á¤í¤ó¤Ñ¤óÅÐÏ¿";
			include ("tmpl_html/err-msg.html");
		}
	} else {
		$print_msg = $em_recomend_10;
		$p_titl = "My¤á¤í¤ó¤Ñ¤óÅÐÏ¿";
		include ("tmpl_html/err-msg.html");
	}
} else {
	$p_titl = "My¤á¤í¤ó¤Ñ¤óÅÐÏ¿";
	include ("tmpl_html/err-msg.html");
}
?>
