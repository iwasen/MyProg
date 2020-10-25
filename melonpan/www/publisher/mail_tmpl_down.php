<?php
include ("inc/pub_passchk.inc");
include ("../inc/com_path.inc");
include ("$compath/err_msg.inc");
include ("$compath/com_db_func.inc");
include ("$compath/sql_mm.inc");
include ("$compath/sql_mp.inc");
include ("$compath/sql_mcom.inc");
include ("$compath/com_mm_func.inc");
include ("$incpath/pub_mm_dbacces.inc");
include ("$incpath/pub_mm_func.inc");
include ("$incpath/pub_s_mail.inc");

$err_dsp_titl = "メール入稿テンプレート";
$reply_to = "send@melonpan.net";

// パスワード生成(copy from melonpa-regist3.php)
Function chkpwd($text_data) {
	if ( $text_data != null ) {
		$text_data = ereg_replace("[^a-zA-Z0-9]","",$text_data);
		if ( ereg("([a-zA-Z0-9]+)",$text_data,$regs) ) {
			if ( strlen($text_data) == strlen($regs[0]) ) {
				return $text_data;
			} else {
				return -1;
			}
		} else {
			return -1;
		}
	} else {
		return -1;
	}
}

// メルマガテンプレート取得
function sel_mag_template($conn, $mag_id) {
	global $mag_titl, $mag_body;

	$mag_titl = $mag_body = "";
	$sql = "SELECT mag_subject_tmpl, mag_header_tmpl, mag_footer_tmpl, "
		 . "       coalesce(mag_volnum_ins_flg, '0') as ins_volno, coalesce(mag_date_ins_flg,'0') as ins_date "
		 . "  FROM m_magazine WHERE mag_id = '$mag_id' ";
	$result = db_exec($sql);
	if (!pg_numrows($result)) {
		return false;
	} else {
		$fetch = pg_fetch_object($result, 0);
		$mag_subject_tmpl = $fetch->mag_subject_tmpl;
		$mag_header_tmpl = $fetch->mag_header_tmpl;
		$mag_footer_tmpl = $fetch->mag_footer_tmpl;
		$ins_volno = $fetch->ins_volno;
		$ins_date = $fetch->ins_date;
	}
	$mag_header_tmpl = str_replace("\r\n", "\n",$mag_header_tmpl);
	$mag_footer_tmpl = str_replace("\r\n", "\n",$mag_footer_tmpl);

	$mag_titl = $mag_subject_tmpl;
	if ( $ins_volno == "1" && strpos( $mag_titl, "@volno@" ) === false ) {
		$mag_titl = $mag_titl . " @volno@";
	}	
	if ( $ins_date == "1" && strpos( $mag_titl, "@date@" ) === false ) {
		$mag_titl = $mag_titl . " @date@";
	}
	$mag_body = $mag_header_tmpl . "\n\n\n\n\n\n\n\n\n\n\n" . $mag_footer_tmpl;

	return true;
}

// 発行者情報取得
function sel_publisher($conn, $pub_id, &$mail_add_main) {

	$sql = "select mail_add_main from m_publisher WHERE publisher_id = $pub_id ";
	$result = db_exec($sql);
	if (!pg_numrows($result)) {
		return false;
	} else {
		$fetch = pg_fetch_object($result, 0);
		$mail_add_main = $fetch->mail_add_main;
		return true;
	}
}

// パスワード保存
function set_pub_pass($conn, $mag_id, $pub_pass) {

	if( strlen($mag_id) != 6 ) 
		return false;

	$sql = "update M_MAGAZINE set MAIL_ISSUE_PSWD = '$pub_pass' where MAG_ID = '$mag_id'";

	if (!db_exec($sql)) {
		return false;
	} else {
		return true;
	}
}
/*======================================================================================*/
/*	メール入稿テンプレート送信(main)													*/
/*======================================================================================*/
//$conn = db_conxxxt();
pub_passchk($conn, $publisher_id,$owner_pswd);
$mag_nm = stripcslashes($mag_nm);

// マガジン情報取得
if (!sel_mag_template($gConn, $mag_id)){
	$p_titl = $err_dsp_titl; $print_msg = "ＤＢエラー(1)";
	include ("err-msg.html");
	exit();
}
if ($mag_titl == ""){ $mag_titl = $mag_nm; }

// 入稿パスワード生成
Do {
	$log_key = crypt($owner_pswd,substr(time(),-2));
	$log_key = chkpwd($log_key);
}While ($log_key == -1 || $log_key == "");
$log_key = substr($log_key,0,12);

if (!set_pub_pass($conn, $mag_id, $log_key)) {
	$p_titl = $err_dsp_titl; $print_msg = "ＤＢエラー(2)";
	include ("err-msg.html");
	exit();
}

// 基本メールアドレス取得
if (!sel_publisher($gConn, $publisher_id, $mail_add_main)){
	$p_titl = $err_dsp_titl; $print_msg = "ＤＢエラー(3)";
	include ("err-msg.html");
	exit();
}

// メール送信
get_mail_template("pub_tmpl", $subject, $from_addr, $bcc_addr, $body);
$to_addr = trim($mail_add_main);
$body = str_replace("@password@", $log_key, $body);
$body = str_replace("@mag_id@", $mag_id, $body);
$body = str_replace("@mag_nm@", $mag_nm, $body);
$body = str_replace("@mag_title@", $mag_titl, $body);
$body = str_replace("@mag_body@", $mag_body, $body);

if ( send_mail2($subject, $to_addr, $from_addr, $bcc_addr, $body, $reply_to) == 0 ){
	include ("mail_tmpl_down.html");
}else{
	$p_titl = $err_dsp_titl; $print_msg = "メール送信エラー";
	include ("err-msg.html");
	exit();
}
?>
