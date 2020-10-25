<?php
include ("../inc/com_path.inc");
include ("$compath/com_db_func.inc");
include ("inc/mex_passchk.inc");
include ("inc/mr_s_mail.inc");
include ("../sub/head.inc");

/****************************************************************
 Main
****************************************************************/
//$conn = db_conxxxt();
mex_passchk($conn,$melonpai_id,$melonpai_pswd);
$subject = stripcslashes($subject);
$header = stripcslashes($header);
$body = stripcslashes($body);
$footer = stripcslashes($footer);

$sql = "select melonpai_mail_addr from m_melonpai where melonpai_id = $melonpai_id";
$result = db_exec($sql);
if (pg_numrows($result)) {
	$fetch = pg_fetch_object($result, 0);
	$melonpai_mail_addr = $fetch->melonpai_mail_addr;
}
$mail_body = $header."\n\n".$body."\n\n".$footer."\n";

$ret = mitu_send_mail($send_mail_addr,$subject,$mail_body,$melonpai_mail_addr);
if ($ret == -1) {
	$p_titl = "みつくろい依頼送信";
	$print_msg = "みつくろい文送信に失敗しました";
	include("tmpl_html/err-msg.html");
	exit();
} else {
	
	$sql = "update t_choice set cho_flg = 1 ,modify_dt = 'now' ,cho_dt = 'now' where cho_id = $cho_id";

if (!db_exec($sql)) {
		db_rollback();
		$p_titl = "みつくろい依頼送信";
		$print_msg = "みつくろい文アップデートに失敗しました";
		include("tmpl_html/err-msg.html");
		exit();
	}

	db_begin_trans();
	if (($log_num = update_squ_key ("L_MELONPAI_LOG")) != "-1") {
	$sql = "insert into l_melonpai_log(log_num,melonpai_id,cho_id,log_pro_no,modify_dt) ";
	$sql .= " values($log_num ,$melonpai_id ,$cho_id ,7,'now')";
		if (!db_exec($sql)) {
			db_rollback();
			$p_titl = "みつくろい依頼送信";
			$print_msg = "ログ記録に失敗しました";
			include("tmpl_html/err-msg.html");
			exit();
		}
		db_commit_trans();
		include("tmpl_html/mitu-return3.html");
	} else {
		db_rollback();
		$p_titl = "みつくろい依頼送信";
		$print_msg = "ログ記録に失敗しました";
		include("tmpl_html/err-msg.html");
		exit();
	}
}
?>