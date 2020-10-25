<?
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

//���ܽ祽����
function sort_header($column, $text) {
	global $sort_col, $sort_dir;

	if ($column) {
		if ($column == $sort_col) {
			if ($sort_dir == 0) {
				$color = ' color="teal"';
				$dir = 1;
				$title = "����å������{$text}�ǹ߽�˥����Ȥ��ޤ�";
			} else {
				$color = ' color="brown"';
				$dir = 0;
				$title = "����å������{$text}�Ǿ���˥����Ȥ��ޤ�";
			}
		} else {
			$dir = 0;
			$title = "����å������{$text}�Ǿ���˥����Ȥ��ޤ�";
		}
		echo "<th onclick='sort_list($column,$dir)' class='sort' title='$title'><font$color>$text</font></th>\n";
	} else
		echo "<th>$text</th>\n";
}

// ǯ
function select_year($start_year, $default, $selected) {
	if ($default)
		echo "<option value=''>$default</option>\n";

	for ($i = $start_year; $i < $start_year + 10; $i++) {
		echo '<option ', value_selected($i, $selected), ">$i</option>\n";
	}
}

// ǯ(������)
function select_birth_year($start_year, $default, $selected) {
	if ($default)
		echo "<option value=''>$default</option>\n";

	for ($i = $start_year; $i <= date('Y'); $i++) {
		echo '<option ', value_selected($i, $selected), ">$i</option>\n";
	}
}

// ��
function select_month($default, $selected) {
	if ($default)
		echo "<option value=''>$default</option>\n";

	for ($i = 1; $i <= 12; $i++)
		echo '<option ', value_selected($i, $selected), ">$i</option>\n";
}

// ��
function select_day($default, $selected) {
	if ($default)
		echo "<option value=''>$default</option>\n";

	for ($i = 1; $i <= 31; $i++)
		echo '<option ', value_selected($i, $selected), ">$i</option>\n";
}

// ����
function select_hour($default, $selected) {
	if ($default)
		echo "<option value=''>$default</option>\n";

	for ($i = 0; $i < 24; $i++) {
		if ($i < 12)
			echo '<option ', value_selected($i, $selected), ">AM $i</option>\n";
		else
			echo '<option ', value_selected($i, $selected), ">PM ", $i - 12, "</option>\n";
	}
}

// ʬ��10ʬ���
function select_minute($default, $selected) {
	if ($default)
		echo "<option value=''>$default</option>\n";

	for ($i = 0; $i <60; $i=$i+10)
		echo '<option ', value_selected($i, $selected), ">$i</option>\n";
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

//=== NULL��0(����)���֤����� ===
function replace_zero($number) {
	if ($number == "") {
		return $number = 0;
	} else {
		return $number;
	}
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
?>
