<?
//'=== エラーメールアドレスチェック ===
function addr_check($mail_adr) {
	$mail_adr = trim($mail_adr);
	if (strrpos($mail_adr, " ")) {
		$err = true;
	} else {
		$mail_parts = explode("@", $mail_adr);
		if (count ($mail_parts) > 2) {
			$err = true;
		} elseif ($mail_parts[0] == "" || $mail_parts[1] == "") {
			$err = true;
		} elseif (strpos($mail_parts[1], ".") == 0) {
			$err = true;
		}
	}
	if ($err) {
		return false;
	} else {
		return true;
	}
}

//=== メールアドレスリスト挿入 ===
function insert_addr_list($seq_no, $data_file) {
	$fd = fopen ($data_file, "r");
	$sub_seq = 1;

	while (!feof ($fd)) {
		$mail_adr = trim(fgets($fd, 100));
		if ($mail_adr <> "") {
			if (addr_check($mail_adr)) {
				$cl_status = "0";
			} else {
				$cl_status = "1";
			}
			$sql = "insert into T_CLEANING_LIST (CL_seq_no, CL_sub_seq, CL_mail_adr, CL_status) ".
					"values ($seq_no, $sub_seq, '$mail_adr', '$cl_status') ";
			db_exec($sql);
			$sub_seq++;
		}
	}
	fclose ($fd);
/*
	$conn = db_conxxxt();
	$sql = "insert into T_CLEANING_LIST (CL_seq_no, CL_sub_seq, CL_mail_adr, CL_status) ".
			"values ($seq_no, :sub_seq, :mail_adr, :CL_status) ";
	$parse = OCIParse($conn, $sql);
	OCIBindByName($parse, ":SUB_SEQ", &$sub_seq, 9);
	OCIBindByName($parse, ":MAIL_ADR", &$mail_adr, 100);
	OCIBindByName($parse, ":CL_STATUS", &$cl_status, 1);

	$fd = fopen ($data_file, "r");
	$sub_seq = 1;

	while (!feof ($fd)) {
		$mail_adr = trim(fgets($fd, 100));
		if ($mail_adr <> "") {
			if (addr_check($mail_adr)) {
				$cl_status = "0";
			} else {
				$cl_status = "1";
			}
			OCIExecute($parse, OCI_DEFAULT);
			$sub_seq++;
		}
	}
	fclose ($fd);

	OCICommit($conn);
	OCIFreeStatement($parse);
*/
}
?>