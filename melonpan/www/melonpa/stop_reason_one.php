<?
include ("../inc/com_path.inc");
include ("$compath/com_db_func.inc");
include ("$compath/com_rm_func.inc");
include ("$compath/sql_mcom.inc");
include ("$compath/sql_rm.inc");

/****************************************************************
 Main
****************************************************************/
//$conn = db_conxxxt();
$goback = 1;

db_exec(sprintf("INSERT INTO t_unsubscribe (mag_id,mail_addr,subscribe_dt,unsubscribe_dt)"
	. " SELECT mag_id,mail_addr,subscribe_dt,current_timestamp FROM t_mailaddr WHERE mag_id = '%s' and mail_addr = '%s'"
	, $rsn_mag_id, trim($email)));

$sql = "delete from T_MAILADDR where mag_id = '$rsn_mag_id' and mail_addr = '".trim($email)."'";

if (!db_exec($sql)) {
	$error_datas .= "<br><FONT color=#990000><B>[ID".$rsn_mag_id."：".trim($rsn_mag_nm)."]</B></FONT>";
	$error_datas .= "<br>".trim($mag_nm)."削除に失敗しました";
	include("tmpl_html/mag-regi_thanks.html");
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
		$sql = 	$sql ."'" . $reason_sel . "',";
		$sql = 	$sql ."'now' )";
		db_exec($sql);
	}
}
if ($rmbott50001) {
	$mag_datas .= "<br><FONT color=#990000><B>[ID".$rsn_mag_id."：".trim(htmlspecialchars($rsn_mag_nm))."]</B></FONT>";
	$mag_datas .= "<br>".trim(htmlspecialchars($rsn_mag_nm))."の登録を解除しました";
	include("tmpl_html/mag-regi_thanks.html");
} else {
	$mag_datas .= "<br><FONT color=#990000><B>[ID".$rsn_mag_id."：".trim(htmlspecialchars($rsn_mag_nm))."]</B></FONT>";
	$mag_datas .= "<br>".trim(htmlspecialchars($rsn_mag_nm))."の登録を解除しました";
	include("tmpl_html/mag-regi_thanks.html");
}
?>
