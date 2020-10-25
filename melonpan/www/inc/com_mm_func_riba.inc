<?php
function make_type_box($mag_type) {
global	$gConn;
	$sql = mcom_make_sql ("SELECT_M_MAG_TYPE");
	$stmt = OCIParse($gConn, $sql);
	OCIDefinebyname($stmt, "TYPE_ID", &$type_id);
	OCIDefinebyname($stmt, "TYPE", &$type);
	$ncols = OCIExecute($stmt, OCI_DEFAULT);
	while (OCIFetch($stmt)) {
//½é´ü¸ø³«ÍÑ
print "<input type=\"checkbox\" name=\"mag_type\" value=$type_id checked>\n";
/*
		if ($type_id == $mag_type) {
			print "<input type=\"radio\" name=\"mag_type\" value=$type_id checked>\n";
		} else {
			print "<input type=\"radio\" name=\"mag_type\" value=$type_id>\n";
		}
*/
		print $type;
	}
}

function make_cycle_box($mag_cycle) {
	$w_caption[0] = "Æü´© ";
	$w_caption[1] = "½µ´© ";
	$w_caption[2] = "³Ö½µ´© ";
	$w_caption[3] = "·î´© ";
	$w_caption[4] = "ÉÔÄê´ü ";
	for ($i = 1; $i < 6; $i++) {
		$c_val = sprintf("%02d", $i);
		if ($c_val == $mag_cycle) {
			print "<input type=\"checkbox\" name=\"mag_cycle\" value=$c_val checked>\n";
		} else {
			print "<input type=\"checkbox\" name=\"mag_cycle\" value=$c_val>\n";
		}
		print trim($w_caption[$i - 1]);
	}
}

function make_sex_box($mag_reader_sex) {
	$w_caption[0] = "ÃËÀ­ ";
	$w_caption[1] = "½÷À­ ";
	for ($i = 1; $i < 3; $i++) {
		$c_val = sprintf("%01d", $i);
		if ($c_val == $mag_reader_sex) {
			print "<input type=\"checkbox\" name=\"mag_reader_sex[]\" value=$c_val checked>\n";
		} else {
			print "<input type=\"checkbox\" name=\"mag_reader_sex[]\" value=$c_val>\n";
		}
		print trim($w_caption[$i - 1]);
	}
}

function make_age_box($mag_reader_age) {
	for ($i = 1; $i < 7; $i++) {
		$c_val = sprintf("%02d", $i * 10);
		if ($c_val == $mag_reader_age) {
			print "<input type=\"checkbox\" name=\"mag_reader_age[]\" value=$c_val checked>\n";
		} else {
			print "<input type=\"checkbox\" name=\"mag_reader_age[]\" value=$c_val>\n";
		}
		print trim($c_val) . "Âå ";
	}
}

function make_area_box($mag_reader_area) {
//	$conn = db_conxxxt();
global	$gConn;
	$sql = mcom_make_sql ("SELECT_M_MAG_AREA");
	$stmt = OCIParse($gConn, $sql);
	OCIDefinebyname($stmt, "AREA_ID", &$area_id);
	OCIDefinebyname($stmt, "AREA", &$area);
	$ncols = OCIExecute($stmt, OCI_DEFAULT);
	while (OCIFetch($stmt)) {
		if ($area_id == $mag_reader_area) {
			print "<input type=\"checkbox\" name=\"mag_reader_area[]\" value=$area_id checked>\n";
		} else {
			print "<input type=\"checkbox\" name=\"mag_reader_area[]\" value=$area_id>\n";
		}
		print trim($area);
	}
//	db_logoff ();
}

function make_occup_box($mag_reader_occup) {
//	$conn = db_conxxxt();
global	$gConn;
	$sql = mcom_make_sql ("SELECT_M_MAG_OCCUP");
	$stmt = OCIParse($gConn, $sql);
	OCIDefinebyname($stmt, "OCCUP_ID", &$occup_id);
	OCIDefinebyname($stmt, "OCCUP", &$occup);
	$ncols = OCIExecute($stmt, OCI_DEFAULT);
	while (OCIFetch($stmt)) {
		if ($occup_id == $mag_reader_occup) {
			print "<input type=\"checkbox\" name=\"mag_reader_occup[]\" value=\"$occup_id\" checked>\n";
		} else {
			print "<input type=\"checkbox\" name=\"mag_reader_occup[]\" value=\"$occup_id\">\n";
		}
		print trim($occup);
	}
//	db_logoff ();
}

function make_bn_pub_box($mag_bn_pub) {
	$w_caption[0] = "¸ø³« ";
	$w_caption[1] = "Èó¸ø³« ";
	for ($i = 1; $i < 3; $i++) {
		$c_val = sprintf("%01d", $i);
		if ($c_val == $mag_bn_pub) {
			print "<input type=\"radio\" name=\"mag_bn_pub\" value=$c_val checked>\n";
		} else {
			print "<input type=\"radio\" name=\"mag_bn_pub\" value=$c_val>\n";
		}
		print $w_caption[$i - 1];
	}
}

function make_ml_type_box($ml_type) {
	$w_caption[0] = "£Í£Ì¤Î¤ß¡¡ ";
	$w_caption[1] = "£Â£Â£ÓÏ¢Æ°·¿";
	for ($i = 1; $i < 3; $i++) {
		$c_val = sprintf("%01d", $i);
		if ($c_val == $ml_type) {
			print "<input type=\"radio\" name=\"ml_type\" value=$c_val checked>\n";
		} else {
			print "<input type=\"radio\" name=\"ml_type\" value=$c_val>\n";
		}
		print $w_caption[$i - 1];
	}
}

function make_catg_box($id, $mag_catg_1, $mag_catg_2) {
//	$conn = db_conxxxt();
global	$gConn;
	$sql = "SELECT * from M_MAG_CATG WHERE catg_id LIKE '" . $id . "%'";
	$stmt = OCIParse($gConn, $sql);
	$ncols = OCIExecute($stmt, OCI_DEFAULT);
	$nrows = OCIFetchStatement($stmt, &$results);
	$column = each($results);
	$catg_id = $column['value'];
	$column = each($results);
	$catg = $column['value'];
	for ($i = 0; $i < $nrows; $i++) {
		$sel_val = $catg_id[$i];
		$sel_nm = trim($catg[$i]);
		if (substr($sel_val, 2, 2) != "00") {
			if ($sel_val == $mag_catg_1 || $sel_val == $mag_catg_2) {
				print "<input type=\"checkbox\" name=\"mag_catg_chk[]\" value=$sel_val checked>\n";
			} else {
				print "<input type=\"checkbox\" name=\"mag_catg_chk[]\" value=$sel_val>\n";
			}
			print $sel_nm . "¡¡ \n";
		}
	}
//	db_logoff ();
}

function make_bg_box($id, $mag_bg_1, $mag_bg_2, $col) {
//	$conn = db_conxxxt();
global	$gConn;
	$sql = "SELECT * from M_MAG_BG WHERE bg_id LIKE '" . $id . "%' order by bg_id";
	$stmt = OCIParse($gConn, $sql);
	$ncols = OCIExecute($stmt, OCI_DEFAULT);
	$nrows = OCIFetchStatement($stmt, &$results);
	$column = each($results);
	$bg_id = $column['value'];
	$column = each($results);
	$bg = $column['value'];
	$titl_flg = 0;
	$nrowsspan = $nrows - 1;
	for ($i = 0; $i < $nrows; $i++) {
		$sel_val = $bg_id[$i];
		$sel_nm = trim($bg[$i]);
		if (substr($sel_val, 2, 3) == "000") {
			$titl = $sel_nm;
		} else {
			print "<tr>\n";
			if ($titl_flg == 0) {
				$titl_flg = 1;
				print "<td rowspan=\"$nrowsspan\" class=\"text9pt\" width=\"20\" bgcolor=\"$col\">\n";
				print "<div align=\"center\">$titl</div>\n";
				print "</td>\n";
			}
			print "<td width=\"24\">\n";
			if ($sel_val == $mag_bg_1 || $sel_val == $mag_bg_2) {
				print "<input type=\"checkbox\" name=\"mag_bg_chk[]\" value=$sel_val checked>\n";
			} else {
				print "<input type=\"checkbox\" name=\"mag_bg_chk[]\" value=$sel_val>\n";
			}
			print "</td>\n";
			print "<td class=\"catcont\">$sel_nm</td>\n";
			print "</tr>\n";
		}
	}
//	db_logoff ();
}

function pub_mm_type ($code) {
//	$conn = db_conxxxt();
global	$gConn;
	$sql = mcom_make_sql ("SELECT_M_MAG_TYPE_ID");
	$stmt = OCIParse($gConn, $sql);
	OCIbindbyname ($stmt, ":TYPE_ID", &$code, -1);
	OCIDefinebyname($stmt, "TYPE", &$ret_code);
	$ncols = OCIExecute($stmt, OCI_DEFAULT);
	if (!OCIFetch($stmt)) {
		$ret_code = "No Select";
	}
//	db_logoff ();
	return $ret_code;
}

function pub_mm_cycle ($code) {
	if ($code == "01") {
		$ret_code = "Æü´©";
	} elseif  ($code == "02") {
		$ret_code = "½µ´©";
	} elseif  ($code == "03") {
		$ret_code = "³Ö½µ´©";
	} elseif  ($code == "04") {
		$ret_code = "·î´©";
	} else {
		$ret_code = "ÉÔÄê´ü";
	}
	return $ret_code;
}

function pub_mm_sex ($code) {
	if ($code == "01") {
		$ret_code = "ÃËÀ­";
	} else {
		$ret_code = "½÷À­";
	}
	return $ret_code;
}

function pub_mm_area ($code) {
//	$conn = db_conxxxt();
global	$gConn;
	$sql = mcom_make_sql ("SELECT_M_MAG_AREA_ID");
	$stmt = OCIParse($gConn, $sql);
	OCIbindbyname ($stmt, ":AREA_ID", &$code, -1);
	OCIDefinebyname($stmt, "AREA", &$ret_code);
	$ncols = OCIExecute($stmt, OCI_DEFAULT);
	if (!OCIFetch($stmt)) {
		$ret_code = "No Select";
	}
//	db_logoff ();
	return $ret_code;
}

function pub_mm_occup ($code) {
//	$conn = db_conxxxt();
global	$gConn;
	$sql = mcom_make_sql ("SELECT_M_MAG_OCCUP_ID");
	$stmt = OCIParse($gConn, $sql);
	OCIbindbyname ($stmt, ":OCCUP_ID", &$code, -1);
	OCIDefinebyname($stmt, "OCCUP", &$ret_code);
	$ncols = OCIExecute($stmt, OCI_DEFAULT);
	if (!OCIFetch($stmt)) {
		$ret_code = "No Select";
	}
//	db_logoff ();
	return $ret_code;
}

function pub_mm_catg ($code) {
//	$conn = db_conxxxt();
global	$gConn;
	$sql = mcom_make_sql ("SELECT_M_MAG_CATG_ID");
	$stmt = OCIParse($gConn, $sql);
	OCIbindbyname ($stmt, ":CATG_ID", &$code, -1);
	OCIDefinebyname($stmt, "CATG", &$ret_code);
	$ncols = OCIExecute($stmt, OCI_DEFAULT);
	if (!OCIFetch($stmt)) {
		$ret_code = "No Select";
	}
//	db_logoff ();
	return $ret_code;
}

function pub_mm_bg ($code) {
//	$conn = db_conxxxt();
global	$gConn;
	$sql = mcom_make_sql ("SELECT_M_MAG_BG_ID");
	$stmt = OCIParse($gConn, $sql);
	OCIbindbyname ($stmt, ":BG_ID", &$code, -1);
	OCIDefinebyname($stmt, "BG", &$ret_code);
	$ncols = OCIExecute($stmt, OCI_DEFAULT);
	if (!OCIFetch($stmt)) {
		$ret_code = "No Select";
	}
//	db_logoff ();
	return $ret_code;
}

function pub_mm_bn_pub ($code) {
	if ($code == "1") {
		$ret_code = "¸ø³«";
	} else {
		$ret_code = "Èó¸ø³«";
	}
	return $ret_code;
}

function pub_mm_ml_type ($code) {
	if ($code == "1") {
		$ret_code = "£Í£Ì¤Î¤ß";
	} else {
		$ret_code = "£Â£Â£ÓÏ¢Æ°·¿";
	}
	return $ret_code;
}

function make_prefec_box($prefec) {
	$w_caption[0] = "ËÌ³¤Æ»";
	$w_caption[1] = "ÀÄ¿¹¸©";
	$w_caption[2] = "´ä¼ê¸©";
	$w_caption[3] = "µÜ¾ë¸©";
	$w_caption[4] = "½©ÅÄ¸©";
	$w_caption[5] = "»³·Á¸©";
	$w_caption[6] = "Ê¡Åç¸©";
	$w_caption[7] = "°ñ¾ë¸©";
	$w_caption[8] = "ÆÊÌÚ¸©";
	$w_caption[9] = "·²ÇÏ¸©";
	$w_caption[10] = "ºë¶Ì¸©";
	$w_caption[11] = "ÀéÍÕ¸©";
	$w_caption[12] = "ÅìµþÅÔ";
	$w_caption[13] = "¿ÀÆàÀî¸©";
	$w_caption[14] = "»³Íü¸©";
	$w_caption[15] = "Ä¹Ìî¸©";
	$w_caption[16] = "¿·³ã¸©";
	$w_caption[17] = "ÉÙ»³¸©";
	$w_caption[18] = "ÀÐÀî¸©";
	$w_caption[19] = "Ê¡°æ¸©";
	$w_caption[20] = "´ôÉì¸©";
	$w_caption[21] = "ÀÅ²¬¸©";
	$w_caption[22] = "°¦ÃÎ¸©";
	$w_caption[23] = "»°½Å¸©";
	$w_caption[24] = "¼¢²ì¸©";
	$w_caption[25] = "µþÅÔÉÜ";
	$w_caption[26] = "ÂçºåÉÜ";
	$w_caption[27] = "Ê¼¸Ë¸©";
	$w_caption[28] = "ÆàÎÉ¸©";
	$w_caption[29] = "ÏÂ²Î»³¸©";
	$w_caption[30] = "Ä»¼è¸©";
	$w_caption[31] = "Åçº¬¸©";
	$w_caption[32] = "²¬»³¸©";
	$w_caption[33] = "¹­Åç¸©";
	$w_caption[34] = "»³¸ý¸©";
	$w_caption[35] = "ÆÁÅç¸©";
	$w_caption[36] = "¹áÀî¸©";
	$w_caption[37] = "°¦É²¸©";
	$w_caption[38] = "¹âÃÎ¸©";
	$w_caption[39] = "Ê¡²¬¸©";
	$w_caption[40] = "º´²ì¸©";
	$w_caption[41] = "Ä¹ºê¸©";
	$w_caption[42] = "·§ËÜ¸©";
	$w_caption[43] = "ÂçÊ¬¸©";
	$w_caption[44] = "µÜºê¸©";
	$w_caption[45] = "¼¯»ùÅç¸©";
	$w_caption[46] = "²­Æì¸©";
	$w_caption[47] = "³¤³°";

	for ($i=0; $i<48; $i++) {
		if ($w_caption[$i] == $prefec) {
			print "<option value=$w_caption[$i] selected>$w_caption[$i]</option>\n";
		} else {
			print "<option value=$w_caption[$i]>$w_caption[$i]</option>\n";
		}
	}
}

function make_cycleday ($code) {
	$w_caption[0] = "·îÍËÆü";
	$w_caption[1] = "²ÐÍËÆü";
	$w_caption[2] = "¿åÍËÆü";
	$w_caption[3] = "ÌÚÍËÆü";
	$w_caption[4] = "¶âÍËÆü";
	$w_caption[5] = "ÅÚÍËÆü";
	$w_caption[6] = "ÆüÍËÆü";
	for ($i = 0; $i < 7; $i++) {
		if ($w_caption[$i] == $code) {
			print "<option value=$w_caption[$i] selected>$w_caption[$i]</option>\n";
		} else {
			print "<option value=$w_caption[$i]>$w_caption[$i]</option>\n";
		}
	}
}

function inp_chk_reason ($reason, $reason_sel) {
	$ret = 1;
	if (strlen($reason_sel) == 0) {
		$ret = 0;
	}
	if (($reason_sel == "¤½¤ÎÂ¾") && (strlen($reason) == 0)) {
		$ret = 0;
	}
	return $ret;
}

?>
