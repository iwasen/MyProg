<?
// AND条件結合
function and_join(&$where, $cond) {
	if ($where != '')
		$where .= ' AND ';

	$where .= $cond;
}

// ORDER BY句生成
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

//項目順ソート
function sort_header($column, $text) {
	global $sort_col, $sort_dir;

	if ($column) {
		if ($column == $sort_col) {
			if ($sort_dir == 0) {
				$color = ' color="teal"';
				$dir = 1;
				$title = "クリックすると{$text}で降順にソートします";
			} else {
				$color = ' color="brown"';
				$dir = 0;
				$title = "クリックすると{$text}で昇順にソートします";
			}
		} else {
			$dir = 0;
			$title = "クリックすると{$text}で昇順にソートします";
		}
		echo "<th onclick='sort_list($column,$dir)' class='sort' title='$title'><font$color>$text</font></th>\n";
	} else
		echo "<th>$text</th>\n";
}

// 年
function select_year($start_year, $default, $selected) {
	if ($default)
		echo "<option value=''>$default</option>\n";

	for ($i = $start_year; $i < $start_year + 10; $i++) {
		echo '<option ', value_selected($i, $selected), ">$i</option>\n";
	}
}

// 年(〜現在)
function select_birth_year($start_year, $default, $selected) {
	if ($default)
		echo "<option value=''>$default</option>\n";

	for ($i = $start_year; $i <= date('Y'); $i++) {
		echo '<option ', value_selected($i, $selected), ">$i</option>\n";
	}
}

// 月
function select_month($default, $selected) {
	if ($default)
		echo "<option value=''>$default</option>\n";

	for ($i = 1; $i <= 12; $i++)
		echo '<option ', value_selected($i, $selected), ">$i</option>\n";
}

// 日
function select_day($default, $selected) {
	if ($default)
		echo "<option value=''>$default</option>\n";

	for ($i = 1; $i <= 31; $i++)
		echo '<option ', value_selected($i, $selected), ">$i</option>\n";
}

// 時間
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

// 分（10分毎）
function select_minute($default, $selected) {
	if ($default)
		echo "<option value=''>$default</option>\n";

	for ($i = 0; $i <60; $i=$i+10)
		echo '<option ', value_selected($i, $selected), ">$i</option>\n";
}

//'=== シングルクォート置換 ===
function replace_single($data) {
	return str_replace("'", "''", stripslashes($data));
}

//=== 改行コードを取る ===
function replace_return($data) {
	$data=str_replace("\n", "", $data);
	return str_replace("\r", "", $data);
}

//=== NULLを<br>に置き換え ===
function replace_br($str) {
	if ($str == "") {
		return $str = "<br>";
	} else {
		return $str;
	}
}

//=== NULLを0(ゼロ)に置き換え ===
function replace_zero($number) {
	if ($number == "") {
		return $number = 0;
	} else {
		return $number;
	}
}

//'=== エラーメールアドレスチェック ===
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

//'=== 	都道府県の選択肢出力 ===
function comp($data1, $data2) {
	if ($data1 == $data2) {
		return " selected";
	}
}

function SelectKen($ken) {
	print "<option value=\"北海道\"" . comp("北海道", $ken) . ">北海道</option>\n";
	print "<option value=\"青森県\"" . comp("青森県", $ken) . ">青森県</option>\n";
	print "<option value=\"岩手県\"" . comp("岩手県", $ken) . ">岩手県</option>\n";
	print "<option value=\"宮城県\"" . comp("宮城県", $ken) . ">宮城県</option>\n";
	print "<option value=\"秋田県\"" . comp("秋田県", $ken) . ">秋田県</option>\n";
	print "<option value=\"山形県\"" . comp("山形県", $ken) . ">山形県</option>\n";
	print "<option value=\"福島県\"" . comp("福島県", $ken) . ">福島県</option>\n";
	print "<option value=\"茨城県\"" . comp("茨城県", $ken) . ">茨城県</option>\n";
	print "<option value=\"栃木県\"" . comp("栃木県", $ken) . ">栃木県</option>\n";
	print "<option value=\"群馬県\"" . comp("群馬県", $ken) . ">群馬県</option>\n";
	print "<option value=\"埼玉県\"" . comp("埼玉県", $ken) . ">埼玉県</option>\n";
	print "<option value=\"千葉県\"" . comp("千葉県", $ken) . ">千葉県</option>\n";
	print "<option value=\"東京都\"" . comp("東京都", $ken) . ">東京都</option>\n";
	print "<option value=\"神奈川県\"" . comp("神奈川県", $ken) . ">神奈川県</option>\n";
	print "<option value=\"新潟県\"" . comp("新潟県", $ken) . ">新潟県</option>\n";
	print "<option value=\"富山県\"" . comp("富山県", $ken) . ">富山県</option>\n";
	print "<option value=\"石川県\"" . comp("石川県", $ken) . ">石川県</option>\n";
	print "<option value=\"福井県\"" . comp("福井県", $ken) . ">福井県</option>\n";
	print "<option value=\"山梨県\"" . comp("山梨県", $ken) . ">山梨県</option>\n";
	print "<option value=\"長野県\"" . comp("長野県", $ken) . ">長野県</option>\n";
	print "<option value=\"岐阜県\"" . comp("岐阜県", $ken) . ">岐阜県</option>\n";
	print "<option value=\"静岡県\"" . comp("静岡県", $ken) . ">静岡県</option>\n";
	print "<option value=\"愛知県\"" . comp("愛知県", $ken) . ">愛知県</option>\n";
	print "<option value=\"三重県\"" . comp("三重県", $ken) . ">三重県</option>\n";
	print "<option value=\"滋賀県\"" . comp("滋賀県", $ken) . ">滋賀県</option>\n";
	print "<option value=\"京都府\"" . comp("京都府", $ken) . ">京都府</option>\n";
	print "<option value=\"大阪府\"" . comp("大阪府", $ken) . ">大阪府</option>\n";
	print "<option value=\"兵庫県\"" . comp("兵庫県", $ken) . ">兵庫県</option>\n";
	print "<option value=\"奈良県\"" . comp("奈良県", $ken) . ">奈良県</option>\n";
	print "<option value=\"和歌山県\"" . comp("和歌山県", $ken) . ">和歌山県</option>\n";
	print "<option value=\"鳥取県\"" . comp("鳥取県", $ken) . ">鳥取県</option>\n";
	print "<option value=\"島根県\"" . comp("島根県", $ken) . ">島根県</option>\n";
	print "<option value=\"岡山県\"" . comp("岡山県", $ken) . ">岡山県</option>\n";
	print "<option value=\"広島県\"" . comp("広島県", $ken) . ">広島県</option>\n";
	print "<option value=\"山口県\"" . comp("山口県", $ken) . ">山口県</option>\n";
	print "<option value=\"徳島県\"" . comp("徳島県", $ken) . ">徳島県</option>\n";
	print "<option value=\"香川県\"" . comp("香川県", $ken) . ">香川県</option>\n";
	print "<option value=\"愛媛県\"" . comp("愛媛県", $ken) . ">愛媛県</option>\n";
	print "<option value=\"高知県\"" . comp("高知県", $ken) . ">高知県</option>\n";
	print "<option value=\"福岡県\"" . comp("福岡県", $ken) . ">福岡県</option>\n";
	print "<option value=\"佐賀県\"" . comp("佐賀県", $ken) . ">佐賀県</option>\n";
	print "<option value=\"長崎県\"" . comp("長崎県", $ken) . ">長崎県</option>\n";
	print "<option value=\"熊本県\"" . comp("熊本県", $ken) . ">熊本県</option>\n";
	print "<option value=\"大分県\"" . comp("大分県", $ken) . ">大分県</option>\n";
	print "<option value=\"宮崎県\"" . comp("宮崎県", $ken) . ">宮崎県</option>\n";
	print "<option value=\"鹿児島県\"" . comp("鹿児島県", $ken) . ">鹿児島県</option>\n";
	print "<option value=\"沖縄県\"" . comp("沖縄県", $ken) . ">沖縄県</option>\n";
	print "<option value=\"海外\"" . comp("海外", $ken) . ">海外</option>\n";
}
?>
