<?
include ("../inc/com_path.inc");
include ("$compath/com_db_func.inc");
include ("$compath/com_rm_func.inc");
include ("$compath/sql_mcom.inc");
include ("$compath/sql_rm.inc");
include("inc/pass_check.inc");


/****************************************************************
 Main
****************************************************************/
$goback = 1;

if ($mail_addr != "") {pass_check($gConn,trim($mail_addr),trim($reader_pswd));}

db_exec(sprintf("INSERT INTO t_unsubscribe (mag_id,mail_addr,subscribe_dt,unsubscribe_dt)"
	. " SELECT mag_id,mail_addr,subscribe_dt,current_timestamp FROM t_mailaddr WHERE mag_id = '%s' and mail_addr = '%s'"
	, $rsn_mag_id, trim($email)));

$sql = "delete from T_MAILADDR where mag_id = '$rsn_mag_id' and mail_addr = '".trim($email)."'";
if (!db_exec($sql)) {
	$error_datas .= "<br><FONT color=#990000><B>[ID".$rsn_mag_id."：".trim($rsn_mag_nm)."]</B></FONT>";
	$error_datas .= "<br>".trim($rsn_mag_nm)."削除に失敗しました";
	include("stop_reason.php");
	exit();
}


$reason_sel = trim ($reason_sel);
if ($reason_sel != "") {
	$delete_id = update_squ_key("T_DELREASON");
	if ($mag_del_id != "-1") {
		$sql = "INSERT INTO T_DELREASON (";
		$sql = 	$sql . "delete_id,";
		$sql = 	$sql . "mag_id,";
		if ($reader_id != "") {
			$sql = 	$sql . "reader_id,";
		}
 		$sql = 	$sql . "reason,";
		$sql = 	$sql . "modify_dt";
		$sql = 	$sql . 	" ) VALUES ( ";
		$sql = 	$sql . 	$delete_id . ",";
		$sql = 	$sql . 	"'".$rsn_mag_id . "',";
		if ($reader_id != "") {
			$sql = 	$sql . 	$reader_id . ",";
		}
		$sql = 	$sql ."'" . $reason_sel . "','now' )";
		$result = db_exec($sql);
	}
}

$of_count = count($of_list2);
if (count($of_list2) != 0) {
	while (list($key,$val) = each($of_list2)) {
		$mag_id = substr($val,0,6);
		$mag_nm = substr($val,6);
		$of_list["$mag_id"] = $mag_nm;
	}
}
if ($rmbott50001 == "　　解除する　　") {
/*	if ($mail_addr == "") {
	$mail_addr = $cookie_mail_addr;
	$reader_pswd = $cookie_reader_pswd;
	$reader_id = $cookie_reader_id;
}*/
	$mag_datas .= "<br><FONT color=#990000><B>[ID".$rsn_mag_id."：".trim($rsn_mag_nm)."]</B></FONT>";
	$mag_datas .= "<br>".trim($rsn_mag_nm)."の登録を解除しました";
	include("tmpl_html/mag-regi_thanks.html");
} else {
	include ("tmpl_html/mag-stop-2.html");
}
?>
