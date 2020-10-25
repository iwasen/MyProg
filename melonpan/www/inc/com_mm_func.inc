<?php
function make_type_box($mag_type) {
global	$gConn;
	$sql = mcom_make_sql ("SELECT_M_MAG_TYPE");
	$result = db_exec($sql);
	$nrow = pg_numrows($result);
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);
		$type_id = $fetch->type_id;
		$type = $fetch->type;
		if ($type_id == $mag_type) {
			print "<input type=\"radio\" name=\"mag_type\" value=$type_id checked>\n";
		} else {
			print "<input type=\"radio\" name=\"mag_type\" value=$type_id>\n";
		}
		print trim($type)." ";
	}
}

function make_cycle_box($mag_cycle) {
	$w_caption[0] = "���� ";
	$w_caption[1] = "���� ";
	$w_caption[2] = "�ֽ��� ";
	$w_caption[3] = "� ";
	$w_caption[4] = "����� ";
	for ($i = 1; $i < 6; $i++) {
		$c_val = sprintf("%02d", $i);
		if ($c_val == $mag_cycle) {
			print "<input type=\"radio\" name=\"mag_cycle\" value=$c_val checked>\n";
		} else {
			print "<input type=\"radio\" name=\"mag_cycle\" value=$c_val>\n";
		}
		print $w_caption[$i - 1];
	}
}

function make_sex_box($mag_reader_sex) {
	$w_caption[0] = "���� ";
	$w_caption[1] = "���� ";
	for ($i = 1; $i < 3; $i++) {
		$c_val = sprintf("%01d", $i);
		if ($c_val == $mag_reader_sex) {
			print "<input type=\"radio\" name=\"mag_reader_sex\" value=$c_val checked>\n";
		} else {
			print "<input type=\"radio\" name=\"mag_reader_sex\" value=$c_val>\n";
		}
		print $w_caption[$i - 1];
	}
}

function make_age_box($mag_reader_age) {
	for ($i = 1; $i < 7; $i++) {
		$c_val = sprintf("%02d", $i * 10);
		if ($c_val == $mag_reader_age) {
			print "<input type=\"radio\" name=\"mag_reader_age\" value=$c_val checked>\n";
		} else {
			print "<input type=\"radio\" name=\"mag_reader_age\" value=$c_val>\n";
		}
		print $c_val . "�� ";
	}
}

function make_area_box($mag_reader_area) {
global	$gConn;
	$sql = mcom_make_sql ("SELECT_M_MAG_AREA");
	$result = db_exec($sql);
	$nrow = pg_numrows($result);
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);
		$area_id = $fetch->area_id;
		$area = $fetch->area;
		if ($area_id == $mag_reader_area) {
			print "<input type=\"radio\" name=\"mag_reader_area\" value=$area_id checked>\n";
		} else {
			print "<input type=\"radio\" name=\"mag_reader_area\" value=$area_id>\n";
		}
		print $area;
	}
}

function make_occup_box($mag_reader_occup) {
	$sql = mcom_make_sql ("SELECT_M_MAG_OCCUP");
	$result = db_exec($sql);
	$nrow = pg_numrows($result);
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);
		$occup_id = $fetch->occup_id;
		$occup = $fetch->occup;
		if ($occup_id == $mag_reader_occup) {
			print "<option value=$occup_id selected>$occup</option>\n";
		} else {
			print "<option value=$occup_id>$occup</option>\n";
		}
	}
}

function make_bn_pub_box($mag_bn_pub) {
	$w_caption[0] = "���� ";
	$w_caption[1] = "����� ";
	$w_caption[2] = "�ǿ���Τ߸��� ";
	for ($i = 1; $i <= 3; $i++) {
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
	$w_caption[0] = "�̤ͣΤߡ� ";
	$w_caption[1] = "�££�Ϣư��";
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
	$sql = "SELECT * from M_MAG_CATG WHERE catg_id LIKE '" . $id . "%'";
	$result = db_exec($sql);
	$nrow = pg_numrows($result);
	for ($i = 0; $i < $nrow; $i++) {
		$resarray= pg_fetch_array ($result, $i);
		$catg_id[$i] = $resarray[0];
		$catg[$i] = $resarray[1];
		$sel_val = $catg_id[$i];
		$sel_nm = trim($catg[$i]);
		if (substr($sel_val, 2, 2) != "00") {
			if ($sel_val == $mag_catg_1 || $sel_val == $mag_catg_2) {
				print "<input type=\"checkbox\" name=\"mag_catg_chk[]\" value=$sel_val checked>\n";
			} else {
				print "<input type=\"checkbox\" name=\"mag_catg_chk[]\" value=$sel_val>\n";
			}
			print $sel_nm . "�� \n";
		}
	}
}

function make_bg_box($id, $mag_bg_1, $mag_bg_2, $col) {
	$sql = "SELECT * from M_MAG_BG WHERE bg_id LIKE '" . $id . "%' order by bg_id";
	$result = db_exec($sql);
	$nrow = pg_numrows($result);
	$titl_flg = 0;
	$nrowsspan = $nrow - 1;

	for ($i = 0; $i < $nrow; $i++) {
		$resarray= pg_fetch_array ($result, $i);
		$bg_id[$i] = $resarray[0];
		$bg[$i] = $resarray[1];

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
}

function pub_mm_type ($code) {
	$sql = "SELECT * from M_MAG_TYPE WHERE type_id = '$code'";
	$result = db_exec($sql);
	if (!pg_numrows($result)) {
		$ret_code = "No Select";
	} else {
		$fetch = pg_fetch_object($result, 0);
		$ret_code = $fetch->type;
	}
	return $ret_code;
}

function pub_mm_cycle ($code) {
	if ($code == "01") {
		$ret_code = "����";
	} elseif  ($code == "02") {
		$ret_code = "����";
	} elseif  ($code == "03") {
		$ret_code = "�ֽ���";
	} elseif  ($code == "04") {
		$ret_code = "�";
	} else {
		$ret_code = "�����";
	}
	return $ret_code;
}

function pub_mm_sex ($code) {
	if ($code == "01") {
		$ret_code = "����";
	} else {
		$ret_code = "����";
	}
	return $ret_code;
}

function pub_mm_area ($code) {
	$sql = "SELECT * from M_MAG_AREA WHERE area_id = '$code'";
	$result = db_exec($sql);
	if (!pg_numrows($result)) {
		$ret_code = "No Select";
	} else {
		$fetch = pg_fetch_object($result, 0);
		$ret_code = $fetch->area;
	}
	return $ret_code;
}

function pub_mm_occup ($code) {
	$sql = "SELECT * from M_MAG_OCCUP WHERE occup_id = '$code'";
	$result = db_exec($sql);
	if (!pg_numrows($result)) {
		$ret_code = "No Select";
	} else {
		$fetch = pg_fetch_object($result, 0);
		$ret_code = $fetch->occup;
	}
	return $ret_code;
}

function pub_mm_catg ($code) {
	$sql = "SELECT * from M_MAG_CATG WHERE catg_id = '$code'";
	$result = db_exec($sql);
	if (!pg_numrows($result)) {
		$ret_code = "No Select";
	} else {
		$fetch = pg_fetch_object($result, 0);
		$ret_code = $fetch->catg;
	}
	return $ret_code;
}

function pub_mm_bg ($code) {
	$sql = "SELECT * from M_MAG_BG WHERE bg_id = '$code'";
	$result = db_exec($sql);
	if (!pg_numrows($result)) {
		$ret_code = "No Select";
	} else {
		$fetch = pg_fetch_object($result, 0);
		$ret_code = $fetch->bg;
	}
	return $ret_code;
}

function pub_mm_bn_pub ($code) {
	if ($code == "1") {
		$ret_code = "����";
	} elseif ($code == "3") {
		$ret_code = "�ǿ���Τ߸���";
	} else {
		$ret_code = "�����";
	}
	return $ret_code;
}

function pub_mm_ml_type ($code) {
	if ($code == "1") {
		$ret_code = "�̤ͣΤ�";
	} else {
		$ret_code = "�££�Ϣư��";
	}
	return $ret_code;
}

function make_prefec_box($prefec) {
	$w_caption[0] = "�̳�ƻ";
	$w_caption[1] = "�Ŀ���";
	$w_caption[2] = "��긩";
	$w_caption[3] = "�ܾ븩";
	$w_caption[4] = "���ĸ�";
	$w_caption[5] = "������";
	$w_caption[6] = "ʡ�縩";
	$w_caption[7] = "��븩";
	$w_caption[8] = "���ڸ�";
	$w_caption[9] = "���ϸ�";
	$w_caption[10] = "��̸�";
	$w_caption[11] = "���ո�";
	$w_caption[12] = "�����";
	$w_caption[13] = "�����";
	$w_caption[14] = "������";
	$w_caption[15] = "Ĺ�";
	$w_caption[16] = "���㸩";
	$w_caption[17] = "�ٻ���";
	$w_caption[18] = "���";
	$w_caption[19] = "ʡ�温";
	$w_caption[20] = "���츩";
	$w_caption[21] = "�Ų���";
	$w_caption[22] = "���θ�";
	$w_caption[23] = "���Ÿ�";
	$w_caption[24] = "���츩";
	$w_caption[25] = "������";
	$w_caption[26] = "�����";
	$w_caption[27] = "ʼ�˸�";
	$w_caption[28] = "���ɸ�";
	$w_caption[29] = "�²λ���";
	$w_caption[30] = "Ļ�踩";
	$w_caption[31] = "�纬��";
	$w_caption[32] = "������";
	$w_caption[33] = "���縩";
	$w_caption[34] = "������";
	$w_caption[35] = "���縩";
	$w_caption[36] = "���";
	$w_caption[37] = "��ɲ��";
	$w_caption[38] = "���θ�";
	$w_caption[39] = "ʡ����";
	$w_caption[40] = "���츩";
	$w_caption[41] = "Ĺ�긩";
	$w_caption[42] = "���ܸ�";
	$w_caption[43] = "��ʬ��";
	$w_caption[44] = "�ܺ긩";
	$w_caption[45] = "�����縩";
	$w_caption[46] = "���츩";
	$w_caption[47] = "����";

	for ($i = 0; $i < 48; $i++) {
		if ($w_caption[$i] == $prefec) {
			print "<option value=$w_caption[$i] selected>$w_caption[$i]</option>\n";
		} else {
			print "<option value=$w_caption[$i]>$w_caption[$i]</option>\n";
		}
	}
}

function make_cycleday ($code) {
	$w_caption[0] = "������";
	$w_caption[1] = "������";
	$w_caption[2] = "������";
	$w_caption[3] = "������";
	$w_caption[4] = "������";
	$w_caption[5] = "������";
	$w_caption[6] = "������";
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
	if (($reason_sel == "����¾") && (strlen($reason) == 0)) {
		$ret = 0;
	}
	return $ret;
}

function make_present_ins_box($mag_present_ins) {
	$w_caption[0] = "�������� ";
	$w_caption[1] = "�������ʤ� ";
	$tuika = "�������Ѥ�Ǥ����Ū���ɼԥץ쥼��Ȥ�ԤʤäƤ���ޤ�������������פ�����å����줿���ϡ��ɼԥץ쥼��Ȥ�Ԥʤ��ݤˡ����ޥ��Υեå����򤪼ڤꤷ�ޤ��ơ������������������ĺ���ޤ����������˾����ʤ����ϡ����������ʤ��פ�����å����Ƥ���������\n";
	for ($i = 1; $i < 3; $i++) {
		$c_val = sprintf("%01d", $i);
		if ($c_val == $mag_present_ins) {
			print "<input type=\"radio\" name=\"mag_present_ins\" value=$c_val checked>\n";
		} else {
			print "<input type=\"radio\" name=\"mag_present_ins\" value=$c_val>\n";
		}
		print $w_caption[$i - 1];
	}
	print "<br>";
	print "<span class=\"text9pt\">";
	print $tuika;
	print "</span>";
}

function pub_mm_present_ins ($code) {
	if ($code == "1") {
		$ret_code = "��������";
	} else {
		$ret_code = "�������ʤ�";
	}
	return $ret_code;
}

function make_original_box($mag_original_flg) {
	$w_caption[0] = "�����Ѥ�Τߤ�ȯ�� ";
	$w_caption[1] = "�����Ѥ�ʳ��Ǥ�ȯ�� ";
	$tuika2 = "�������Ѥ�����Ǥ���ȯ�Ԥ���Ƥ��ʤ����ޥ��ˤϡ֤����Ѥ󥪥ꥸ�ʥ�ޡ����פ򤪤Ĥ����Ƥ���ޤ��������Ѥ�ʳ��Ǥ�ȯ�Ԥ���Ƥ������Ϥ���θ��������\n";
	for ($i = 1; $i < 3; $i++) {
		$c_val = sprintf("%01d", $i);
		if ($c_val == $mag_original_flg) {
			print "<input type=\"radio\" name=\"mag_original_flg\" value=$c_val checked>\n";
		} else {
			print "<input type=\"radio\" name=\"mag_original_flg\" value=$c_val>\n";
		}
		print $w_caption[$i - 1];
	}
	print "<br>";
	print "<span class=\"text9pt\">";
	print $tuika2;
	print "</span>";
}

function pub_mm_original($code) {
	if ($code == "1") {
		$ret_code = "�����Ѥ�Τߤ�ȯ�� ";
	} else {
		$ret_code = "�����Ѥ�ʳ��Ǥ�ȯ�� ";
	}
	return $ret_code;
}

function make_send_notice_box($send_notice_flg) {
	$w_caption[0] = "��˾���� ";
	$w_caption[1] = "��˾���ʤ� ";
	$send_notice_cmnt = "";
	for ($i = 1; $i < 3; $i++) {
		$c_val = sprintf("%01d", $i);
		if ($c_val == $send_notice_flg) {
			print "<input type=\"radio\" name=\"send_notice_flg\" value=$c_val checked>\n";
		} else {
			print "<input type=\"radio\" name=\"send_notice_flg\" value=$c_val>\n";
		}
		print $w_caption[$i - 1];
	}
	print "<br>";
	print "<span class=\"text9pt\">";
	print $send_notice_cmnt;
	print "</span>";
}

function pub_mm_send_notice($code) {
	if ($code == "1") {
		$ret_code = "��˾���� ";
	} else {
		$ret_code = "��˾���ʤ� ";
	}
	return $ret_code;
}

?>