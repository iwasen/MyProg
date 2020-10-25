<?php
include ("../inc/com_path.inc");
include ("$compath/err_msg.inc");
include ("$compath/com_func.inc");
include ("$compath/com_db_func.inc");
include ("$compath/sql_mcom.inc");
include ("$compath/com_mm_func.inc");
include ("$incpath/mex_passchk.inc");
include ("inc/mr_s_mail.inc");
include ("../sub/head.inc");

function count_check ($conn, $melonpai_id) {

	$sql = "select * from m_melonpai where ";
	$sql .= "melonpai_id = $melonpai_id";
	$result = db_exec($sql);
	if (!pg_numrows($result)) {
		err_msg ("PM002", $p_titl);
		exit();
	} else {
		$fetch = pg_fetch_object($result, 0);
		$osusume_num = $fetch->osusume_num;
	}
	$sql = "SELECT COUNT(*) as oss_cnt FROM M_MAGAZINE WHERE osusume_flg = 1 ";
	$sql .= "and osusume_id = $melonpai_id";
	$result = db_exec($sql);
	if (!$result) {
		err_msg ("PM002", $p_titl);
		exit();
	}
	if (pg_numrows($result)) {
		$fetch = pg_fetch_object($result, 0);
		$oss_cnt = $fetch->oss_cnt;
	}
	if ($oss_cnt >= $osusume_num) {
		return $osusume_num;
	} else {
		return 0;
	}
}

/*======================================================================================*/
/*	(main)										*/
/*======================================================================================*/
$p_titl = "おすすめor危ない";
mex_passchk($conn, $melonpai_id, $melonpai_pswd);

if ($OSUSUME) {
	$w_msg1 = "下記マガジンを〈おすすめ〉リストに追加しました。";
	$oss_cnt = count_check($conn, $melonpai_id);
	if ($oss_cnt != 0) {
		$print_msg =  "おすすめ処理できる上限（" . $oss_cnt . "件）を、超えています。";
		include("tmpl_html/err-msg.html");
		exit();
	}
} else {
	$w_msg1 = "下記マガジンを〈危ない〉リストに追加しました。";
}
/* **
if ($osusme_point == "1") {
	if ($point_val > $melonpai_point) {
		$print_msg =  "「あげるポイント」が、お持ちのポイント数を超えているようです。";
		db_logoff();
		include("tmpl_html/err-msg.html");
		exit();
	}
	if ($point_val < 0) {
		$print_msg =  "「あげるポイント」に、マイナスの値が指定されているようです。";
		db_logoff();
		include("tmpl_html/err-msg.html");
		exit();
	}
}
*/
if (strlen($osusume_reason) > 256) {
	$print_msg =  "「理由」は、全角１２６文字以内で入力してください。";
//	db_logoff();
	include("tmpl_html/err-msg.html");
	exit();
}

$sql = "SELECT * FROM M_MAGAZINE WHERE mag_id = '$mag_id' for update";
$result = db_exec($sql);
if (!pg_numrows($result)) {
	$print_msg =  "マガジンマスターの読み込みに失敗しました。";
	include("tmpl_html/err-msg.html");
	exit();
} else {
	$fetch = pg_fetch_object($result, $i);
	$publisher_id = $fetch->publisher_id;
	$mag_nm = $fetch->mag_nm;
}

if ($OSUSUME) {
	$sql = "UPDATE M_MAGAZINE SET osusume_flg = 1, mag_pub_stop_flg = '00', osusume_point_flg = '1',";
	$sql .= " osusume_id = " . $melonpai_id;
} else {
	$sql = "UPDATE M_MAGAZINE SET osusume_flg = 0, mag_pub_stop_flg = '01', "
		. "mag_pub_stop_dt = 'now', modefy_dt = 'now'";
}

if (strlen($osusume_reason) != 0) {
	$sql .=	", osusume_reason = '" . $osusume_reason . "'";
}
$sql .=	" WHERE mag_id = '$mag_id'";
db_exec($sql);

//Mail_Send
#メール送信
$sql = "SELECT P.mail_add_main ,P.owner_nm_kanj FROM M_PUBLISHER P ,M_MAGAZINE M WHERE M.mag_id = '$mag_id' and P.publisher_id = M.publisher_id ";
$result = db_exec($sql);
if (pg_numrows($result)) {
	$fetch = pg_fetch_object($result, 0);
	$mail_add_main = $fetch->mail_add_main;
	$owner_nm_kanj = $fetch->owner_nm_kanj;
}
$sql = "SELECT melonpai_mail_addr FROM M_MELONPAI where melonpai_id = $melonpai_id ";
$result = db_exec($sql);
if (pg_numrows($result)) {
	$fetch = pg_fetch_object($result, 0);
	$melonpai_mail_addr = $fetch->melonpai_mail_addr;
	$melonpai_name = $fetch->melonpai_name;
}
if ($OSUSUME) {
	if ($osusme_point == "1") {
		$point_up = $point_val;
	} else {
		$point_up = 0;
	}
	recom_send_mail($mail_add_main,$mag_id,$mag_nm,$melonpai_mail_addr,$point_up,$owner_nm_kanj,stripcslashes($osusume_reason));
} else {
	coution_send_mail($mag_id,$mag_nm,$melonpai_mail_addr,$owner_nm_kanj,$melonpai_id,$melonpai_name,stripcslashes($osusume_reason));
}

/* **
if ($OSUSUME && $osusme_point == "1") {
	$sql = "SELECT * FROM M_PUBLISHER WHERE publisher_id = :PUBLISHER_ID for update";
	$stmt = OCIParse($conn, $sql);
	OCIbindbyname ($stmt, ":PUBLISHER_ID", &$publisher_id, -1);
	OCIDefinebyname($stmt, "PT_MELONPAN", &$pt_melonpan);
	OCIDefinebyname($stmt, "MAIL_ADD_MAIN", &$mail_add_main);
	$w_ret = exec_sql($conn, $stmt, $p_titl);
	if (!(OCIFetch($stmt))) {
		$print_msg =  "発行者マスターの読み込みに失敗しました。";
		OCIRollback($conn);
		OCICommit($conn);
		db_logoff();
		include("tmpl_html/err-msg.html");
		exit();
	}
	$pt_melonpan = $pt_melonpan + $point_val;
	OCIFreeStatement($stmt);

	$sql = "UPDATE M_PUBLISHER SET pt_melonpan = :PT_MELONPAN, modefy_dt = SYSDATE "
		. "WHERE publisher_id = :PUBLISHER_ID";
	$stmt = OCIParse($conn, $sql);
	OCIbindbyname ($stmt, ":PUBLISHER_ID", &$publisher_id, -1);
	OCIbindbyname ($stmt, ":PT_MELONPAN", &$pt_melonpan, -1);
	$w_ret = exec_sql($conn, $stmt, $p_titl);
	OCIFreeStatement($stmt);

	$sql = "SELECT * FROM M_MELONPAI WHERE melonpai_id = :MELONPAI_ID for update";
	$stmt = OCIParse($conn, $sql);
	OCIbindbyname ($stmt, ":MELONPAI_ID", &$melonpai_id, -1);
	OCIDefinebyname($stmt, "MELONPAI_POINT", &$melonpai_point);
	$w_ret = exec_sql($conn, $stmt, $p_titl);
	if (!(OCIFetch($stmt))) {
		$print_msg =  "Myめろんぱんマスターの読み込みに失敗しました。";
		OCIRollback($conn);
		OCICommit($conn);
		db_logoff();
		include("tmpl_html/err-msg.html");
		exit();
	}
	OCIFreeStatement($stmt);

	$melonpai_point = $melonpai_point - $point_val;
	$sql = "UPDATE M_MELONPAI SET melonpai_point = :MELONPAI_POINT "
		. "WHERE melonpai_id = :MELONPAI_ID";
	$stmt = OCIParse($conn, $sql);
	OCIbindbyname ($stmt, ":MELONPAI_POINT", &$melonpai_point, -1);
	OCIbindbyname ($stmt, ":MELONPAI_ID", &$melonpai_id, -1);
	$w_ret = exec_sql($conn, $stmt, $p_titl);
}
*/

// Log
db_begin_trans();
if (($log_num = update_squ_key ("L_MELONPAI_LOG")) != "-1") {
	db_commit_trans();
} else {
	db_rollback();
	err_msg ("PM002", $p_titl);
	exit();
}

if ($OSUSUME) {
	$log_pro_no = 1;
} else {
	$log_pro_no = 3;
}

$sql  = "INSERT INTO L_MELONPAI_LOG ( ";
$sql .= "log_num, ";
$sql .= "melonpai_id, ";
if ($OSUSUME && $osusme_point == "1") {
	$sql .= "log_point, ";
	$sql .= "log_pub_id, ";
}
$sql .= "mag_id, ";
$sql .= "log_pro_no, ";
$sql .= "modify_dt )  ";
$sql .= "VALUES ( ";
$sql .= "$log_num, ";
$sql .= "$melonpai_id, ";
if ($OSUSUME && $osusme_point == "1") {
	$sql .= "$point_val, ";
	$sql .= "$publisher_id, ";
}
$sql .= "'$mag_id', ";
$sql .= "$log_pro_no, ";
$sql .= "'now'      )";
db_exec($sql);

// 履歴
if ( $OSUSUME && $osusume_reason != "" ) {
	$osusume_reason = trim($osusume_reason);
	$sql  = "INSERT INTO T_OSUSUME_LIST ( mag_id, melonpai_id, sel_date, date_from, reason )  ";
	$sql .= "VALUES ( '$mag_id', $melonpai_id, 'now', 'now', '$osusume_reason'  )";
	db_exec($sql);
}

include("tmpl_html/mp-mag-detail2.html");

?>
