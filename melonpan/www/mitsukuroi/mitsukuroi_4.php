<?php
include ("../inc/com_path.inc");
include ("$compath/com_db_func.inc");
include ("$compath/com_rm_func.inc");
include ("$compath/sql_mcom.inc");
include ("$compath/sql_rm.inc");
include ("$compath/sql_mpai.inc");
include ("../sub/head.inc");

/****************************************************************
 Main
****************************************************************/
//$conn = db_conxxxt();
$com_title = stripcslashes($com_title);
$com_text = stripcslashes($com_text);

$sql = "SELECT *  from m_melonpai where  melonpai_id = ";
$sql = $sql . $melonpai_id;
$result = db_exec($sql);
/* **
$stmt = OCIParse($conn, $sql);
OCIDefinebyname($stmt, "MELONPAI_NIC", &$melonpai_nic);
OCIDefinebyname($stmt, "MELONPAI_PRO", &$melonpai_pro);

$ncols = OCIExecute($stmt, OCI_DEFAULT);
if (OCIFetch($stmt)) {
*/
if (pg_numrows($result)) {
	$fetch = pg_fetch_object($result, 0);
	$melonpai_nic = $fetch->melonpai_nic;
	$melonpai_pro = $fetch->melonpai_pro;

	$sql1 = rm_make_sql ("SELECT_M_MELONPA_MAIL_ADR_DATE");
	$sql = $sql1 ."'".  trim($send_mail_addr) ."'";
	$result = db_exec($sql);
/* **
	$stmt = OCIParse($conn, $sql);
	OCIDefinebyname($stmt, "READER_ID", &$reader_id);
	$ncols = OCIExecute($stmt, OCI_DEFAULT);
	if (OCIFetch($stmt)) {
*/
	if (pg_numrows($result)) {
		$fetch = pg_fetch_object($result, 0);
		$reader_id = $fetch->reader_id;

		$sql1 = "select * from T_CHOICE where reader_id = ";
		$sql = $sql1 .  $reader_id ." and send_mail_addr = ";
		$sql .= "'".  $send_mail_addr ."' and COM_TITLE = '";
		$sql .= $com_title ."' and cho_flg = '0'";
		$result = db_exec($sql);
/* **
		$stmt = OCIParse($conn, $sql);
		$ncols = OCIExecute($stmt, OCI_DEFAULT);
		if (!OCIFetch($stmt)) {
*/
		if (!pg_numrows($result)) {
			$cho_id = update_squ_key ("T_CHOICE");
			if ($cho_id != "-1") {
				$cho_flg = "0";
//				$sql = pai_make_sql ("INSERT_T_CHOICE");
				$sql = "INSERT INTO t_choice ( cho_id, reader_id, com_title, com_text, send_mail_addr, melonpai_id, modify_dt, cho_flg, cho_dt ) VALUES ( $cho_id, $reader_id, '$com_title', '$com_text', '$send_mail_addr', $melonpai_id, 'now', '$cho_flg', 'now' )";
				db_exec($sql);
/* **
				$stmt = OCIParse($conn, $sql);
				$ret01 = OCIbindbyname ($stmt, ":CHO_ID", &$cho_id, -1);
				$ret01 = OCIbindbyname ($stmt, ":READER_ID", &$reader_id, -1);
				$ret01 = OCIbindbyname ($stmt, ":COM_TITLE", &$com_title, -1);
				$ret01 = OCIbindbyname ($stmt, ":COM_TEXT", &$com_text, -1);
				$ret01 = OCIbindbyname ($stmt, ":SEND_MAIL_ADDR", &$send_mail_addr, -1);
				$ret01 = OCIbindbyname ($stmt, ":MELONPAI_ID", &$melonpai_id, -1);
				$ret01 = OCIbindbyname ($stmt, ":CHO_FLG", &$cho_flg, -1);
				$ncols = OCIExecute($stmt, OCI_DEFAULT);
				$sql = "commit";
				$stmt = OCIParse($conn, $sql);
				$ncols = OCIExecute($stmt, OCI_DEFAULT);
*/
				include("mitsukuroi_4.html");

			} else {
				$print_msg = "指定されたキーの取得ができませんでした。";
				$p_titl = "Myめろんぱん登録";
				include("../sub/err_msg.html");

			}
		} else {
			$print_msg = "同一の依頼が登録されているようです";
			$p_titl = "Myめろんぱん登録";
			include("../sub/err_msg.html");
		}

	} else {
		$reader_id = " ";
		$sql1 = "select * from T_CHOICE where ";
		$sql = $sql1 .  "  send_mail_addr = ";
		$sql = $sql ."'".  $send_mail_addr ."' and COM_TITLE = '";
		$sql = $sql .  $com_title ."' and cho_flg = '0'";
		$result = db_exec($sql);
/* **
		$stmt = OCIParse($conn, $sql);
		$ncols = OCIExecute($stmt, OCI_DEFAULT);
		if (!OCIFetch($stmt)) {
*/
		if (!pg_numrows($result)) {
			$cho_id = update_squ_key ("T_CHOICE");
			if ($cho_id != "-1") {
				$cho_flg = "0";

//				$sql = pai_make_sql ("INSERT_T_CHOICE_2");
				$sql = "INSERT INTO t_choice ( cho_id, com_title, com_text, send_mail_addr, melonpai_id, modify_dt, cho_flg, cho_dt ) VALUES ( $cho_id, '$com_title', '$com_text', '$send_mail_addr', $melonpai_id, 'now', '$cho_flg', 'now' )";
				db_exec($sql);
/* **
				$stmt = OCIParse($conn, $sql);
				$ret01 = OCIbindbyname ($stmt, ":CHO_ID", &$cho_id, -1);
				$ret01 = OCIbindbyname ($stmt, ":COM_TITLE", &$com_title, -1);
				$ret01 = OCIbindbyname ($stmt, ":COM_TEXT", &$com_text, -1);
				$ret01 = OCIbindbyname ($stmt, ":SEND_MAIL_ADDR", &$send_mail_addr, -1);
				$ret01 = OCIbindbyname ($stmt, ":MELONPAI_ID", &$melonpai_id, -1);
				$ret01 = OCIbindbyname ($stmt, ":CHO_FLG", &$cho_flg, -1);
				$ncols = OCIExecute($stmt, OCI_DEFAULT);
				$sql = "commit";
				$stmt = OCIParse($conn, $sql);
				$ncols = OCIExecute($stmt, OCI_DEFAULT);
*/
				include("mitsukuroi_4.html");

			} else {
				$print_msg = "指定されたキーの取得ができませんでした。";
				$p_titl = "Myめろんぱん登録";
				include("../sub/err_msg.html");

			}
		} else {
			$print_msg = "同一の依頼が登録されているようです";
			$p_titl = "Myめろんぱん登録";
			include("../sub/err_msg.html");
		}
	}
} else {
	$print_msg = "指定された隊員は登録されておりません。";
	$p_titl = "Myめろんぱん登録";
	include("../sub/err_msg.html");

}
//db_logoff();
?>
