<?php
include ("../inc/com_path.inc");
include ("$compath/err_msg.inc");
include ("$compath/com_func.inc");
include ("$compath/com_db_func.inc");
include ("$compath/sql_mcom.inc");
include ("$compath/com_mm_func.inc");
include ("$incpath/mex_passchk.inc");
include ("../sub/head.inc");

function exec_sql($conn, $stmt, $p_titl) {
	$l_ret = TRUE;
	if (!(OCIExecute($stmt, OCI_DEFAULT))) {
		OCIRollback($conn);
		db_logoff();
		err_msg ("PM002", $p_titl);
	}
	return 	$l_ret;
}

function recom_log ($conn, $log_pro_no, $melonpai_id, $recomend_id, $log_point, $log_mel_id ,$recom_titl) {
	db_begin_trans();
	if (($log_num = update_squ_key ("L_MELONPAI_LOG")) != "-1") {
		db_commit_trans();
	} else {
		db_rollback();
		err_msg ("PM002", $p_titl);
		exit();
	}

	$point_flg = FALSE;
	if ($log_pro_no == 5 and $log_point > 0) {
		$point_flg = TRUE;
	}

	$sql  = "INSERT INTO L_MELONPAI_LOG ( ";
	$sql .= "log_num, ";
	$sql .= "melonpai_id, ";
	$sql .= "recomend_id, ";
	if ($point_flg) {
		$sql .= "log_point, ";
		$sql .= "log_mel_id, ";
	}
	$sql .= "log_pro_no, ";
	$sql .= "recom_titl, ";
	$sql .= "modify_dt )  ";
	$sql .= "VALUES ( ";
	$sql .= "$log_num, ";
	$sql .= "$melonpai_id, ";
	$sql .= "$recomend_id, ";
	if ($point_flg) {
		$sql .= "$log_point, ";
		$sql .= "$log_mel_id, ";
	}
	$sql .= "$log_pro_no, ";
	$sql .= "'$recom_titl', ";
	$sql .= "'now'      )";
	db_exec($sql);
}

/*======================================================================================*/
/*	(main)										*/
/*======================================================================================*/
$p_titl = "メルマガ推薦文処理";
mex_passchk($conn, $melonpai_id, $melonpai_pswd);
$sql = "select * from m_melonpai where ";
$sql .= "melonpai_id = $melonpai_id";
$result = db_exec($sql);
if (!pg_numrows($result)) {
	err_msg ("PM002", $p_titl);
	exit();
} else {
	$fetch = pg_fetch_object($result, 0);
	$recom_num = $fetch->recom_num;
}

$sql = "select count(*) as rec_cnt from l_melonpai_log where ";
$sql .= "to_char(modify_dt, 'yyyy/mm/dd') = to_char(now(), 'yyyy/mm/dd') ";
$sql .= "and (log_pro_no = 5 or log_pro_no = 6) ";
$sql .= "and melonpai_id = $melonpai_id";
$result = db_exec($sql);
if (!pg_numrows($result)) {
	err_msg ("PM002", $p_titl);
	exit();
} else {
	$fetch = pg_fetch_object($result, 0);
	$rec_cnt = $fetch->rec_cnt;

	if ($rec_cnt >= $recom_num) {
		$print_msg =  "本日の推薦文を処理できる上限（" . $recom_num . "件）を、超えています。";
		include("tmpl_html/err-msg.html");
		exit();
	}
}

if ($radi_point == "1") {
	if ($recom_point > $melonpai_point) {
		$print_msg =  "「あげるポイント」が、お持ちのポイント数を超えているようです。";
		db_logoff();
		include("tmpl_html/err-msg.html");
		exit();
	}
	if ($recom_point < 0) {
		$print_msg =  "「あげるポイント」に、マイナスの値が指定されているようです。";
		db_logoff();
		include("tmpl_html/err-msg.html");
		exit();
	}
} else {
	$recom_point = 0;
}

$sql = "SELECT * FROM T_RECOMEND WHERE recomend_id = $recomend_id for update";
$result = db_exec($sql);
if (!pg_numrows($result)) {
	$print_msg =  "推薦文は、すでに削除されているようです。";
	include("tmpl_html/err-msg.html");
	exit();
} else {
	$fetch = pg_fetch_object($result, 0);
	$reader_id = $fetch->reader_id;
	$mag_id = $fetch->mag_id;
	$recom_tip = $fetch->recom_tip;
	$open_flg = $fetch->open_flg;
	if ($open_flg != "0") {
		$print_msg =  "推薦文は、すでに処理されているようです。";
		include("tmpl_html/err-msg.html");
		exit();
	}
}

if ($OPEN) {
	$sql2 = "UPDATE T_RECOMEND SET open_flg = '1' WHERE recomend_id = $recomend_id";
	$w_ret = db_exec($sql2);
	if ($radi_point == "1") {
		$sql3 = "SELECT * FROM M_MELONPA WHERE reader_id = $reader_id for update";
		$result = db_exec($sql3);
		if (pg_numrows($result)) {
			$fetch = pg_fetch_object($result, 0);
			$pt_melonpan = $fetch->pt_melonpan;
			$pt_melonpan = $pt_melonpan + $recom_point;
			$sql4 = "UPDATE M_MELONPA SET pt_melonpan = $pt_melonpan "
				. "WHERE reader_id = $reader_id";
			$w_ret = db_exec($sql4);
		}
		$sql31 = "SELECT * FROM M_MELONPAI WHERE melonpai_id = $melonpai_id for update";
		$result = db_exec($sql31);
		if (pg_numrows($result)) {
			$melonpai_point = $melonpai_point - $recom_point;
			$sql41 = "UPDATE M_MELONPAI SET melonpai_point = $melonpai_point "
				. "WHERE melonpai_id = $melonpai_id";
			$w_ret = db_exec($sql41);
		}
	}
	if ($recom_tip > 0) {
		$sql5 = "SELECT * FROM M_MAGAZINE WHERE mag_id = '$mag_id'";
		$result = db_exec($sql5);
		if (pg_numrows($result)) {
			$fetch = pg_fetch_object($result, 0);
			$publisher_id = $fetch->publisher_id;
			$sql6 = "SELECT * FROM M_PUBLISHER WHERE publisher_id = $publisher_id for update";
			$result = db_exec($sql);
			if (pg_numrows($result)) {
				$fetch = pg_fetch_object($result, 0);
				$pub_pt_melonpan = $fetch->pt_melonpan;
			}
			$pub_pt_melonpan = $pub_pt_melonpan + $recom_tip;
			$sql7 = "UPDATE M_PUBLISHER SET pt_melonpan = $pub_pt_melonpan "
				. "WHERE publisher_id = $publisher_id";
			$w_ret = db_exec($sql7);
		}
	}

// Recomend Open Log
	recom_log ($conn, 5, $melonpai_id, $recomend_id, $recom_point, $reader_id ,$recom_titl);
	include("tmpl_html/mp-suisen-detail2.html");
} else {
	$sql2 = "delete from T_RECOMEND WHERE recomend_id = $recomend_id";
	db_exec($sql2);

// Recomend Close Log
	recom_log ($conn, 6, $melonpai_id, $recomend_id, 0, 0 ,$recom_titl);
	include("tmpl_html/mp-suisen-detail3.html");
}

?>
