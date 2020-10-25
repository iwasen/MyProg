<?php
include ("inc/pub_passchk.inc");
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
		. "mag_pub_status_flg = '03') and publisher_id = :PUBLISHER_ID";
	$stmt = OCIParse($gConn, $sql);
	$ret01 = OCIbindbyname ($stmt, ":PUBLISHER_ID", &$publisher_id, -1);
	include("$inc_path/pub_mm_defmane.def");
	$ncols = OCIExecute($stmt, OCI_DEFAULT);
	$lcount = 0;
	while (OCIFetch($stmt)) {
		$lcount++;
		$fom_name =  "formsubmit" . sprintf("%03d", $lcount);
		print "<tr>\n";
		print "<form name=\"$fom_name\" action=\"magazine4.php\" method=\"post\">\n";
		if ($lcount % 2 == 0) {
			print "<td width=\"100\" bgcolor=\"#CCCCCC\">$mag_id</td>\n";
			print "<td width=\"300\" bgcolor=\"#CCCCCC\">$mag_nm</td>\n";
		} else {
			print "<td width=\"100\">$mag_id</td>\n";
			print "<td width=\"300\">$mag_nm</td>\n";
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
$conn = db_conxxxt();
pub_passchk($conn, $publisher_id,$owner_pswd);
db_logoff();

$p_titl = "メルマガ代理解除";
if ($job_flg == "MagSelect") {
	if (($conn = db_conxxxt()) < 0) {
		err_msg ("PM001", $p_titl);
	} else {
		include ("dairi-sakujyo.html");
		db_logoff();
	}
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
	if (($conn = db_conxxxt()) < 0) {
		err_msg ("PM001", $p_titl);
		exit();
	}
		$sql = "SELECT * FROM T_MAILADDR WHERE mag_id = '" . $mag_id
			. "' and mail_addr = '" . trim($mail_addr) . "'";
		$stmt = OCIParse($conn, $sql);
		if (!(OCIExecute($stmt, OCI_DEFAULT))) {
			err_msg ("PM001", $p_titl);
			db_logoff();
			exit();
		} else {
			if (!(OCIFetch($stmt))) {
				$print_msg =  "メールアドレスが存在しません。";
				include ("err-msg.html");
				db_logoff();
				exit();
			}
		}
	include ("dairi-sakujyo-kakunin.html");
} elseif ($job_flg == "DeleteOk") {
	if (($conn = db_conxxxt()) < 0) {
		err_msg ("PM001", $p_titl);
	} else {
		$del_flg = False;
		$sql = "SELECT * FROM T_MAILADDR WHERE mag_id = '" . $mag_id
			. "' and mail_addr = '" . trim($mail_addr) . "'";
		$stmt = OCIParse($conn, $sql);
		if (!(OCIExecute($stmt, OCI_DEFAULT))) {
			err_msg ("PM001", $p_titl);
		} else {
			if (!(OCIFetch($stmt))) {
				$print_msg =  "ご指定のメールアドレスで登録された\n"
					      . "購読メルマガは無いようです。";
				include ("err-msg.html");
			} else {
				$del_flg = True;
			}
			$ret01 = OCIFreeStatement($stmt);
		}
		if ($del_flg) {
			$sql = "DELETE T_MAILADDR WHERE mag_id = '" . $mag_id
				. "' and mail_addr = '" . trim($mail_addr) . "'";
			$stmt = OCIParse($conn, $sql);
			if (!(OCIExecute($stmt, OCI_DEFAULT))) {
				err_msg ("PM001", $p_titl);
			} else {
				OCICommit($conn);
				$ret01 = OCIFreeStatement($stmt);
				include ("dairi-sakujyo-ok.html");
			}
		}
		db_logoff();
	}
}

?>
