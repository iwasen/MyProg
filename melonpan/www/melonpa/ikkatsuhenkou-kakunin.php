<?php
function henkou_fnc($mail_addr,$chg_mail_addr) {
	print "<p>¥á¡¼¥ë¥¢¥É¥ì¥¹[". $mail_addr ."]¤ò</p>";
	print "<p>[". $chg_mail_addr ."]¤ËÊÑ¹¹¤·¤Æ¤è¤í¤·¤¤¤Ç¤¹¤«¡©</p>";
}

function chkmail($chkmail) {
	if ( $chkmail != null ) {
		if (ereg(".+\@.+\..+",$chkmail) && ereg("\.[a-zA-Z]+$",$chkmail)) {
			return 0;
		} else {
			return -1;
		}
	}
}

/***********************************************************
                Main
***********************************************************/
include("inc/pass_check.inc");
include ("inc/error_msg_list.inc");

if ($mail_addr != "") {pass_check($conn,trim($mail_addr),trim($reader_pswd));}

$chg_mail_addr = strtolower($chg_mail_addr);
$chg_mail_addr_kaku = strtolower($chg_mail_addr_kaku);
$flg = chkmail($chg_mail_addr);
$flg2 = chkmail($chg_mail_addr_kaku);

if(trim($mail_addr) == $chg_mail_addr || trim($mail_addr) == $chg_mail_addr_kaku ) {
	$print_msg = $em_ikkatsu_12;
	$p_titl = "£Í£ù¤á¤í¤ó¤Ñ¤óÅÐÏ¿";
	include ("tmpl_html/err-msg.html");
	exit();
}

if ($flg == -1) {
	$print_msg = $em_ikkatsu_9;
	$p_titl = "£Í£ù¤á¤í¤ó¤Ñ¤óÅÐÏ¿";
	include ("tmpl_html/err-msg.html");
	exit();
}
if ($flg2 == -1) {
	$print_msg = $em_ikkatsu_10;
	$p_titl = "£Í£ù¤á¤í¤ó¤Ñ¤óÅÐÏ¿";
	include ("tmpl_html/err-msg.html");
	exit();
}
if (strlen(trim($chg_mail_addr_kaku)) == 0) {
	$print_msg = $em_ikkatsu_3;
	$p_titl = "£Í£ù¤á¤í¤ó¤Ñ¤óÅÐÏ¿";
	include ("tmpl_html/err-msg.html");
	exit();
}
else if (strlen(trim($chg_mail_addr)) == 0) {
	$print_msg = $em_ikkatsu_4;
	$p_titl = "£Í£ù¤á¤í¤ó¤Ñ¤óÅÐÏ¿";
	include ("tmpl_html/err-msg.html");
	exit();
}
else if (strlen(trim($chg_mail_addr)) > 51) {
	$print_msg = $em_ikkatsu_11;
	$p_titl = "£Í£ù¤á¤í¤ó¤Ñ¤óÅÐÏ¿";
	include ("tmpl_html/err-msg.html");
	exit();
}
else if (strlen(trim($chg_mail_addr_kaku)) > 51) {
	$print_msg = $em_ikkatsu_11;
	$p_titl = "£Í£ù¤á¤í¤ó¤Ñ¤óÅÐÏ¿";
	include ("tmpl_html/err-msg.html");
	exit();
}
else if ($flg == -1 || $flg2 == -1) {
	$print_msg = $em_ikkatsu_5;
	$p_titl = "£Í£ù¤á¤í¤ó¤Ñ¤óÅÐÏ¿";
	include ("tmpl_html/err-msg.html");
	exit();
}
else if ($chg_mail_addr_kaku == $chg_mail_addr) {
	$sql = "SELECT * FROM M_MELONPA where mail_addr = '".  trim($chg_mail_addr) ."'";
	$result = db_exec($sql);
	$ncols = pg_numrows($result);
	$wk_reader_id = "";
	if ($ncols > 0) {
		$fetch = pg_fetch_object($result, 0);
		$reader_id1 = $fetch->reader_id;
		$print_msg = $em_ikkatsu_6;
		$p_titl = "£Í£ù¤á¤í¤ó¤Ñ¤óÅÐÏ¿";
		include ("tmpl_html/err-msg.html");
		exit();
	}

	$sql = "SELECT * FROM T_MAILADDR WHERE MAIL_ADDR = '$chg_mail_addr'";
	$result = db_exec($sql);
	$ncols = pg_numrows($result);
	$wk_reader_id = "";
	if ($ncols > 0) {
		$fetch = pg_fetch_object($result, 0);
		$mag_ids = $fetch->mag_id;
		$print_msg = $em_ikkatsu_7;
		$p_titl = "£Í£ù¤á¤í¤ó¤Ñ¤óÅÐÏ¿";
		include ("tmpl_html/err-msg.html");
		exit();
	}

	include ("tmpl_html/ikkatsuhenkou-kakunin.html");
} else {
	$print_msg = $em_ikkatsu_8;
	$p_titl = "£Í£ù¤á¤í¤ó¤Ñ¤óÅÐÏ¿";
	include ("tmpl_html/err-msg.html");
}

?>
