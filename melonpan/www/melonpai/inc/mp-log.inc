<?php
function melx_log($conn, $melonpai_id, $log_id, $log_point, $log_pro_no) {
	$p_titl = "メロンパイログ";

	if (($log_num = update_squ_key ($conn, "L_MELONPAI_LOG")) != "-1") {
		OCICommit($conn);
		$ins_f = TRUE;
	} else {
		OCIRollback($conn);
		OCICommit($conn);
		db_logoff();
		err_msg ("PM002", $p_titl);
		exit();
	}

	$sql  = "INSERT INTO L_MELONPAI_LOG ( ";
	$sql .= "log_num, ";
	$sql .= "melonpai_id, ";
	$sql .= "cho_id, ";
	$sql .= "log_point, ";
	$sql .= "log_pub_id, ";
	$sql .= "log_mel_id, ";
	$sql .= "log_pro_no, ";
	$sql .= "modify_dt )  ";
	$sql .= "VALUES ( ";
	$sql .= ":LOG_NUM, ";
	$sql .= ":MELONPAI_ID, ";
	$sql .= ":CHO_ID, ";
	$sql .= ":LOG_POINT, ";
	$sql .= ":LOG_PUB_ID, ";
	$sql .= ":LOG_MEL_ID, ";
	$sql .= ":LOG_PRO_NO, ";
	$sql .= "SYSDATE      )";

		$stmt = OCIParse($conn, $sql);
		OCIbindbyname ($stmt, ":LOG_NUM", &$log_num, -1);
		OCIbindbyname ($stmt, "MELONPAI_ID", &$melonpai_id, -1);
		OCIbindbyname ($stmt, "CHO_ID", &$cho_id, -1);
		OCIbindbyname ($stmt, "LOG_POINT", &$point_val, -1);
		OCIbindbyname ($stmt, "LOG_PUB_ID", &$publisher_id, -1);
		OCIbindbyname ($stmt, "LOG_MEL_ID", &$LOG_MEL_ID, -1);
		OCIbindbyname ($stmt, "LOG_PRO_NO", &$log_pro_no, -1);
		OCIExecute($stmt, OCI_DEFAULT);
}

/*======================================================================================*/
/*	(main)										*/
/*======================================================================================*/
	$p_titl = "おすすめor危ない";
	if ($OSUSUME) {
		$w_msg1 = "下記マガジンを〈おすすめ〉リストに追加しました。";
	} else {
		$w_msg1 = "下記マガジンを〈危ない〉リストに追加しました。";
	}
	if ($osusme_point == "1") {
		if ($point_val > $melonpai_point) {
			$print_msg =  "「あげるポイント」が、お持ちのポイント数を超えているようです。";
			include ("err-msg.html");
			exit();
		}
		if ($point_val < 0) {
			$print_msg =  "「あげるポイント」に、マイナスの値が指定されているようです。";
			include ("err-msg.html");
			exit();
		}
	}
	if (strlen($osusume_reason) > 256) {
		$print_msg =  "「理由」は、全角１２６文字以内で入力してください。";
		include ("err-msg.html");
		exit();
	}
	if (($conn = db_conxxxt()) == "-1") {
		err_msg ("PM001", $p_titl);
		exit();
	}
	mex_passchk($conn, $melonpai_id, $melonpai_pswd);

	$sql = "SELECT * FROM M_MAGAZINE WHERE mag_id = :MAG_ID for update";
	$stmt = OCIParse($conn, $sql);
	OCIbindbyname ($stmt, ":MAG_ID", &$mag_id, -1);
	OCIDefinebyname($stmt, "PUBLISHER_ID", &$publisher_id);
	OCIDefinebyname($stmt, "MAG_NM", &$mag_nm);
	if (!(OCIExecute($stmt, OCI_DEFAULT))) {
		err_msg ("PM002", $p_titl);
		db_logoff();
		exit();
	}
	if (!(OCIFetch($stmt))) {
		$print_msg =  "マガジンマスターの読み込みに失敗しました。";
		db_logoff();
		include ("err-msg.html");
		exit();
	}
	OCIFreeStatement($stmt);

	if ($OSUSUME) {
		$sql = "UPDATE M_MAGAZINE SET osusume_flg = 1, mag_pub_stop_flg = '00'";
	} else {
		$sql = "UPDATE M_MAGAZINE SET osusume_flg = 0, mag_pub_stop_flg = '01', "
			. "mag_pub_stop_dt = SYSDATE, modefy_dt = SYSDATE";
	}
	if (strlen($osusume_reason) != 0) {
		$sql .=	", osusume_reason = '" . $osusume_reason . "'";
	}
	$sql .=	" WHERE mag_id = :MAG_ID";
	$stmt = OCIParse($conn, $sql);
	OCIbindbyname ($stmt, ":MAG_ID", &$mag_id, -1);
	$w_ret = exec_sql($conn, $stmt, $p_titl);
	OCIFreeStatement($stmt);

	if ($OSUSUME && $osusme_point == "1") {
		$sql = "SELECT * FROM M_PUBLISHER WHERE publisher_id = :PUBLISHER_ID for update";
		$stmt = OCIParse($conn, $sql);
		OCIbindbyname ($stmt, ":PUBLISHER_ID", &$publisher_id, -1);
		OCIDefinebyname($stmt, "PT_MELONPAN", &$pt_melonpan);
		$w_ret = exec_sql($conn, $stmt, $p_titl);
		if (!(OCIFetch($stmt))) {
			$print_msg =  "発行者マスターの読み込みに失敗しました。";
			OCIRollback($conn);
			OCICommit($conn);
			db_logoff();
			include ("err-msg.html");
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
			include ("err-msg.html");
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
		OCICommit($conn);



	}
	db_logoff();

	include ("mp-mag-detail2.html");

?>
