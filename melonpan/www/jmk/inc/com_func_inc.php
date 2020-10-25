<?php

//'=== 	ǯ����������(2000������+10ǯ) ===
function SelectYear($y) {
	$i = 2000;
	if (!$y) {
		print "<option value=\"\" selected></option>\n";
		while ($i <= date("Y")+10){
			print "<option value=\"$i\">". $i ."</option>\n";
			$i++;
		}
	} else {
		while ($i <= $y+10){
			if ($i == $y) {
				print "<option value=\"$i\" selected>". $i ."</option>\n";
			} else {
				print "<option value=\"$i\">". $i ."</option>\n";
			}
			$i++;
		}
	}
}
//'=== 	ǯ����������(1900��) ===
function SelectYear2($y) {
	$i = 1900;
	while ($i <= date(Y)){
		if ($i == $y) {
			print "<option value=\"$i\" selected>". $i ."</option>\n";
		} else {
			print "<option value=\"$i\">". $i ."</option>\n";
		}
		$i++;
	}
}
//'=== 	ǯ����������(systemǯ��10ǯ) ===
function SelectYear3($y) {
	$i = date(Y);
	if (!$y) {
		print "<option value=\"\" selected></option>\n";
		while ($i <= date("Y")+10){
			print "<option value=\"$i\">". $i ."</option>\n";
			$i++;
		}
	} else {
		while ($i <= $y+10){
			if ($i == $y) {
				print "<option value=\"$i\" selected>". $i ."</option>\n";
			} else {
				print "<option value=\"$i\">". $i ."</option>\n";
			}
			$i++;
		}
	}
}
//'=== ����������� ===
function SelectMonth($m) {
	$i = 1;
	if (!$m) {
		print "<option value=\"\" selected></option>\n";
		while ($i <= 12){
			print "<option value=\"$i\">". $i ."</option>\n";
			$i++;
		}
	} else {
		while ($i <= 12){
			if ($i == $m) {
				print "<option value=\"$i\" selected>". $i ."</option>\n";
			} else {
				print "<option value=\"$i\">". $i ."</option>\n";
			}
			$i++;
		}
	}
}

//'=== ������������ ===
function SelectDay($d) {
	$i = 1;
	if (!$d) {
		print "<option value=\"\" selected></option>\n";
			while ($i <= 31){
			print "<option value=\"$i\">". $i ."</option>\n";
			$i++;
		}
	} else {
		while ($i <= 31){
			if ($i == $d) {
				print "<option value=\"$i\" selected>". $i ."</option>\n";
			} else {
				print "<option value=\"$i\">". $i ."</option>\n";
			}
			$i++;
		}
	}
}

//'=== ������������ ===
function SelectHour($h) {
	$i = 0;
	if (!$h) {
		print "<option value=\"\" selected></option>\n";
		while ($i <= 23) {
			if ($i < 12 ) {
				$hour = "AM " . $i .  "��";
			} elseif ($i >= 12) {
				$hour = "PM " . ($i-12) . "��";
			}
			print "<option value=\"$i\">" . $hour . "</option>\n";
			$i++;
		}
	} else {
		while ($i <= 23) {
			if ($i < 12 ) {
				$hour = "AM " . $i .  "��";
			} elseif ($i >= 12) {
				$hour = "PM " . ($i-12) . "��";
			}
			if ($i == $h) {
				print "<option value=\"$i\" selected>". $hour ."</option>\n";
			} else {
				print "<option value=\"$i\">" . $hour . "</option>\n";
			}
			$i++;
		}
	}
}
//'=== ʬ�������� ===
function SelectMinute($mi) {
	$i = 0;
	if (!$mi) {
		print "<option value=\"\" selected></option>\n";
		while ($i < 60) {
			$minute =  $i .  "ʬ";
			print "<option value=\"$i\">" . $minute	 . "</option>\n";
			$i=$i+10;
		}
	} else {
		while ($i < 60) {
			$minute =  $i .  "ʬ";
			if ($i == $mi) {
				print "<option value=\"$i\" selected>". $minute ."</option>\n";
			} else {
				print "<option value=\"$i\">" . $minute . "</option>\n";
			}
			$i=$i+10;
		}
	}
}
//'=== ���󥰥륯�������ִ� ===
function replace_single($data) {
	return str_replace("'", "''", stripslashes($data));
}
//=== ���ԥ����ɤ��� ===
function replace_return($data) {
	$data=str_replace("\n", "", $data);
	return str_replace("\r", "", $data);
}
//=== NULL��<br>���֤����� ===
function replace_br($str) {
	if ($str == "") {
		return $str = "<br>";
	} else {
		return $str;
	}
}
//'=== 	��ƻ�ܸ����������� ===
function comp($data1, $data2) {
	if ($data1 == $data2) {
		return " selected";
	}
}

function SelectKen($ken) {
	print "<option value=\"�̳�ƻ\"" . comp("�̳�ƻ", $ken) . ">�̳�ƻ</option>\n";
	print "<option value=\"�Ŀ���\"" . comp("�Ŀ���", $ken) . ">�Ŀ���</option>\n";
	print "<option value=\"��긩\"" . comp("��긩", $ken) . ">��긩</option>\n";
	print "<option value=\"�ܾ븩\"" . comp("�ܾ븩", $ken) . ">�ܾ븩</option>\n";
	print "<option value=\"���ĸ�\"" . comp("���ĸ�", $ken) . ">���ĸ�</option>\n";
	print "<option value=\"������\"" . comp("������", $ken) . ">������</option>\n";
	print "<option value=\"ʡ�縩\"" . comp("ʡ�縩", $ken) . ">ʡ�縩</option>\n";
	print "<option value=\"��븩\"" . comp("��븩", $ken) . ">��븩</option>\n";
	print "<option value=\"���ڸ�\"" . comp("���ڸ�", $ken) . ">���ڸ�</option>\n";
	print "<option value=\"���ϸ�\"" . comp("���ϸ�", $ken) . ">���ϸ�</option>\n";
	print "<option value=\"��̸�\"" . comp("��̸�", $ken) . ">��̸�</option>\n";
	print "<option value=\"���ո�\"" . comp("���ո�", $ken) . ">���ո�</option>\n";
	print "<option value=\"�����\"" . comp("�����", $ken) . ">�����</option>\n";
	print "<option value=\"�����\"" . comp("�����", $ken) . ">�����</option>\n";
	print "<option value=\"���㸩\"" . comp("���㸩", $ken) . ">���㸩</option>\n";
	print "<option value=\"�ٻ���\"" . comp("�ٻ���", $ken) . ">�ٻ���</option>\n";
	print "<option value=\"���\"" . comp("���", $ken) . ">���</option>\n";
	print "<option value=\"ʡ�温\"" . comp("ʡ�温", $ken) . ">ʡ�温</option>\n";
	print "<option value=\"������\"" . comp("������", $ken) . ">������</option>\n";
	print "<option value=\"Ĺ�\"" . comp("Ĺ�", $ken) . ">Ĺ�</option>\n";
	print "<option value=\"���츩\"" . comp("���츩", $ken) . ">���츩</option>\n";
	print "<option value=\"�Ų���\"" . comp("�Ų���", $ken) . ">�Ų���</option>\n";
	print "<option value=\"���θ�\"" . comp("���θ�", $ken) . ">���θ�</option>\n";
	print "<option value=\"���Ÿ�\"" . comp("���Ÿ�", $ken) . ">���Ÿ�</option>\n";
	print "<option value=\"���츩\"" . comp("���츩", $ken) . ">���츩</option>\n";
	print "<option value=\"������\"" . comp("������", $ken) . ">������</option>\n";
	print "<option value=\"�����\"" . comp("�����", $ken) . ">�����</option>\n";
	print "<option value=\"ʼ�˸�\"" . comp("ʼ�˸�", $ken) . ">ʼ�˸�</option>\n";
	print "<option value=\"���ɸ�\"" . comp("���ɸ�", $ken) . ">���ɸ�</option>\n";
	print "<option value=\"�²λ���\"" . comp("�²λ���", $ken) . ">�²λ���</option>\n";
	print "<option value=\"Ļ�踩\"" . comp("Ļ�踩", $ken) . ">Ļ�踩</option>\n";
	print "<option value=\"�纬��\"" . comp("�纬��", $ken) . ">�纬��</option>\n";
	print "<option value=\"������\"" . comp("������", $ken) . ">������</option>\n";
	print "<option value=\"���縩\"" . comp("���縩", $ken) . ">���縩</option>\n";
	print "<option value=\"������\"" . comp("������", $ken) . ">������</option>\n";
	print "<option value=\"���縩\"" . comp("���縩", $ken) . ">���縩</option>\n";
	print "<option value=\"���\"" . comp("���", $ken) . ">���</option>\n";
	print "<option value=\"��ɲ��\"" . comp("��ɲ��", $ken) . ">��ɲ��</option>\n";
	print "<option value=\"���θ�\"" . comp("���θ�", $ken) . ">���θ�</option>\n";
	print "<option value=\"ʡ����\"" . comp("ʡ����", $ken) . ">ʡ����</option>\n";
	print "<option value=\"���츩\"" . comp("���츩", $ken) . ">���츩</option>\n";
	print "<option value=\"Ĺ�긩\"" . comp("Ĺ�긩", $ken) . ">Ĺ�긩</option>\n";
	print "<option value=\"���ܸ�\"" . comp("���ܸ�", $ken) . ">���ܸ�</option>\n";
	print "<option value=\"��ʬ��\"" . comp("��ʬ��", $ken) . ">��ʬ��</option>\n";
	print "<option value=\"�ܺ긩\"" . comp("�ܺ긩", $ken) . ">�ܺ긩</option>\n";
	print "<option value=\"�����縩\"" . comp("�����縩", $ken) . ">�����縩</option>\n";
	print "<option value=\"���츩\"" . comp("���츩", $ken) . ">���츩</option>\n";
	print "<option value=\"����\"" . comp("����", $ken) . ">����</option>\n";
}
//'=== ���顼�᡼�륢�ɥ쥹�����å� ===
function addr_check2($mail_adr) {
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

// AND�����
function and_join(&$where, $cond) {
	if ($where != '')
		$where .= ' AND ';

	$where .= $cond;
}

// ORDER BY������
function order_by($def_col, $def_dir) {
	global $sort_col, $sort_dir;

	if ($sort_col == '')
		$sort_col = $def_col;

	if ($sort_dir == '')
		$sort_dir = $def_dir;

	$order = func_get_arg($sort_col + 1);
	if ($sort_dir == 1)
		$order .= ' desc';

	return "ORDER BY $order";
}

// �����ȥꥹ�ȥإå�
function sort_header($column, $text) {
	global $sort_col, $sort_dir;

	if ($column) {
		if ($column == $sort_col) {
			if ($sort_dir == 0) {
				$bgcolor = ' bgcolor="skyblue"';
				$dir = 1;
				$title = "����å������{$text}�ǹ߽�˥����Ȥ��ޤ�";
			} else {
				$bgcolor = ' bgcolor="pink"';
				$dir = 0;
				$title = "����å������{$text}�Ǿ���˥����Ȥ��ޤ�";
			}
		} else {
			$dir = 0;
			$title = "����å������{$text}�Ǿ���˥����Ȥ��ޤ�";
		}
		echo "<th$bgcolor onclick='sort_list($column,$dir)' style='cursor:hand' title='$title'>$text</th>\n";
	} else
		echo "<th>$text</th>\n";
}

// ɽ���Կ�
function disp_limit($def = 50) {
	global $displine, $page;

	if ($displine == '')
		$displine = $def;

	if ($displine != 0) {
		$limit = "LIMIT $displine";
		if ($page)
			$limit .= ',' . $displine * $page;
	}

	return $limit;
}

// �����ե����ޥå�
function format_number($number) {
	if ($number < 0)
		return '<font color="red">'. number_format($number). '</font>';
	else
		return number_format($number);
}

// �����ƥ२�顼����
function system_error($msg, $fname) {
	redirect('../common/system_error.php');
}

// ���å�����ѿ�����
function get_session_vars($pset, $prefix) {
	global $cookie_login_id;

	$argnum = func_num_args();

	$session_active = "{$prefix}_active";
	session_register($session_active);

	for ($i = 2; $i < $argnum; $i++) {
		$global_var = func_get_arg($i);
		$session_var = "{$prefix}_$global_var";

		session_register($session_var);

		if (!$pset) {
			if ($GLOBALS[$session_active])
				$value = $GLOBALS[$session_var];
			else {
				switch ($global_var) {
				case 'thread':
					$value = '1';
					break;
				case 'contents':
					$value = '1';
					break;
				case 'staff_id':
					$value = $cookie_login_id;
					break;
				default:
					$value = '';
				}
			}
			$GLOBALS[$global_var] = $value;
		}

		$GLOBALS[$session_var] = $GLOBALS[$global_var];
	}

	$GLOBALS[$session_active] = true;
}

?>