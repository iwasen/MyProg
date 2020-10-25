<?php
include ("inc/pub_passchk.inc");
include ("inc/error_msg_list.inc");
include ("../inc/com_path.inc");
include ("$compath/err_msg.inc");
include ("$compath/com_db_func.inc");

function set_global () {
	global $inc_path;

	include ("../inc/com_path.inc");
	$inc_path = $incpath;
}

function make_maglist($publisher_id,$owner_pswd) {
	global $inc_path;
	global $gConn;

	$sql = "SELECT * FROM M_MAGAZINE WHERE (mag_pub_status_flg = '01' or "
		. "mag_pub_status_flg = '03') and publisher_id = $publisher_id";
	$result = db_exec($sql);
	$nrow = pg_numrows($result);
	$lcount = 0;
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);
		$mag_id = $fetch->mag_id;
		$mail_add_publish = $fetch->mail_add_publish;
		$mag_nm = $fetch->mag_nm;
		$mag_intro_long = $fetch->mag_intro_long;
		$mag_intro_short = $fetch->mag_intro_short;
		$mag_hp = $fetch->mag_hp;
		$mag_type = $fetch->mag_type;
		$mag_type = $fetch->mag_type;
		$mag_cycle_day = $fetch->mag_cycle_day;
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
		$lcount++;
		$fom_name =  "formsubmit" . sprintf("%03d", $lcount);
		print "<tr>\n";
		print "<form name=\"$fom_name\" action=\"magazine4.php\" method=\"post\">\n";
		if ($lcount % 2 == 0) {
			print "<td width=\"100\" bgcolor=\"#CCCCCC\">$mag_id</td>\n";
			print "<td width=\"300\" bgcolor=\"#CCCCCC\">".stripcslashes(htmlspecialchars($mag_nm))."</td>\n";
		} else {
			print "<td width=\"100\">$mag_id</td>\n";
			print "<td width=\"300\">".stripcslashes(htmlspecialchars($mag_nm))."</td>\n";
		}
		print "<td width=\"100\">\n";
		print "<input type=\"submit\" name=\"Submit\" value=\"代理解除\">\n";
		print "</td>\n";
		print "<input type=\"hidden\" value=\"$owner_pswd\" name=\"owner_pswd\">\n";
		print "<input type=\"hidden\" value=\"$publisher_id\" name=\"publisher_id\">\n";
		print "<input type=\"hidden\" value=\"$mag_id\" name=\"mag_id\">\n";
		print "<input type=\"hidden\" value=\"$mag_nm\" name=\"mag_nm\">\n";
		print "<input type=\"hidden\" value=\"Delete\" name=\"job_flg\">\n";
		print "</form>\n";
		print "</tr>\n";
	}
}

/*======================================================================================*/
/*	Magazine Dairi sakujyo								*/
/*	(main)										*/
/*======================================================================================*/
set_global ();
pub_passchk($conn, $publisher_id,$owner_pswd);
$mail_addr = strtolower($mail_addr);

$p_titl = "メルマガ代理解除";
if ($job_flg == "MagSelect") {
	include ("dairi-sakujyo.html");
} elseif ($job_flg == "Delete") {
	include ("dairi-sakujyo2.html");
} elseif ($job_flg == "Kakunin") {
	if (strlen($mail_addr) == 0) {
	err_msg ("メールアドレスが記入されていません", $p_titl);
	exit();
	}
	if ( !(ereg ("^([0-9a-zA-Z._-]+)(@[0-9a-zA-Z._-]+)$", $mail_addr)) )
	{
		err_msg ("メールアドレスが正しく入力されていません", $p_titl);
		exit();
	}
	$sql = "SELECT * FROM T_MAILADDR WHERE mag_id = '" . $mag_id
		. "' and mail_addr = '" . trim($mail_addr) . "'";
	$result = db_exec($sql);
	if (!pg_numrows($result)) {
		$print_msg =  $em_magazine_1;
		include ("err-msg.html");
		exit();
	}
	include ("dairi-sakujyo-kakunin.html");

} elseif ($job_flg == "DeleteOk") {
	$del_flg = False;
	$sql = "SELECT * FROM T_MAILADDR WHERE mag_id = '" . $mag_id
		. "' and mail_addr = '" . trim($mail_addr) . "'";
	$result = db_exec($sql);
		if (!pg_numrows($result)) {
			$print_msg =  $em_magazine_1;
			include ("err-msg.html");
		} else {
			$del_flg = True;
		}
	if ($del_flg) {
		$sql = "DELETE FROM T_MAILADDR WHERE mag_id = '" . $mag_id
			. "' and mail_addr = '" . trim($mail_addr) . "'";
		if (!db_exec($sql)) {
			err_msg ("PM001", $p_titl);
		} else {
			include ("dairi-sakujyo-ok.html");
		}
	}
}

?>
