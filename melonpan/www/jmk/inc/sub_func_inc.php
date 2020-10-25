<?
// AND¾ò·ï·ë¹ç
function and_join(&$where, $cond) {
	if ($where != '')
		$where .= ' AND ';

	$where .= $cond;
}

// ORDER BY¶çÀ¸À®
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

//¹àÌÜ½ç¥½¡¼¥È
function sort_header($column, $text) {
	global $sort_col, $sort_dir;

	if ($column) {
		if ($column == $sort_col) {
			if ($sort_dir == 0) {
				$color = ' color="teal"';
				$dir = 1;
				$title = "¥¯¥ê¥Ã¥¯¤¹¤ë¤È{$text}¤Ç¹ß½ç¤Ë¥½¡¼¥È¤·¤Ş¤¹";
			} else {
				$color = ' color="brown"';
				$dir = 0;
				$title = "¥¯¥ê¥Ã¥¯¤¹¤ë¤È{$text}¤Ç¾º½ç¤Ë¥½¡¼¥È¤·¤Ş¤¹";
			}
		} else {
			$dir = 0;
			$title = "¥¯¥ê¥Ã¥¯¤¹¤ë¤È{$text}¤Ç¾º½ç¤Ë¥½¡¼¥È¤·¤Ş¤¹";
		}
		echo "<th onclick='sort_list($column,$dir)' class='sort' title='$title'><font$color>$text</font></th>\n";
	} else
		echo "<th>$text</th>\n";
}

// Ç¯
function select_year($start_year, $default, $selected) {
	if ($default)
		echo "<option value=''>$default</option>\n";

	for ($i = $start_year; $i < $start_year + 10; $i++) {
		echo '<option ', value_selected($i, $selected), ">$i</option>\n";
	}
}

// Ç¯(¡Á¸½ºß)
function select_birth_year($start_year, $default, $selected) {
	if ($default)
		echo "<option value=''>$default</option>\n";

	for ($i = $start_year; $i <= date('Y'); $i++) {
		echo '<option ', value_selected($i, $selected), ">$i</option>\n";
	}
}

// ·î
function select_month($default, $selected) {
	if ($default)
		echo "<option value=''>$default</option>\n";

	for ($i = 1; $i <= 12; $i++)
		echo '<option ', value_selected($i, $selected), ">$i</option>\n";
}

// Æü
function select_day($default, $selected) {
	if ($default)
		echo "<option value=''>$default</option>\n";

	for ($i = 1; $i <= 31; $i++)
		echo '<option ', value_selected($i, $selected), ">$i</option>\n";
}

// »ş´Ö
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

// Ê¬¡Ê10Ê¬Ëè¡Ë
function select_minute($default, $selected) {
	if ($default)
		echo "<option value=''>$default</option>\n";

	for ($i = 0; $i <60; $i=$i+10)
		echo '<option ', value_selected($i, $selected), ">$i</option>\n";
}

//'=== ¥·¥ó¥°¥ë¥¯¥©¡¼¥ÈÃÖ´¹ ===
function replace_single($data) {
	return str_replace("'", "''", stripslashes($data));
}

//=== ²ş¹Ô¥³¡¼¥É¤ò¼è¤ë ===
function replace_return($data) {
	$data=str_replace("\n", "", $data);
	return str_replace("\r", "", $data);
}

//=== NULL¤ò<br>¤ËÃÖ¤­´¹¤¨ ===
function replace_br($str) {
	if ($str == "") {
		return $str = "<br>";
	} else {
		return $str;
	}
}

//=== NULL¤ò0(¥¼¥í)¤ËÃÖ¤­´¹¤¨ ===
function replace_zero($number) {
	if ($number == "") {
		return $number = 0;
	} else {
		return $number;
	}
}

//'=== ¥¨¥é¡¼¥á¡¼¥ë¥¢¥É¥ì¥¹¥Á¥§¥Ã¥¯ ===
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

//'=== 	ÅÔÆ»ÉÜ¸©¤ÎÁªÂò»è½ĞÎÏ ===
function comp($data1, $data2) {
	if ($data1 == $data2) {
		return " selected";
	}
}

function SelectKen($ken) {
	print "<option value=\"ËÌ³¤Æ»\"" . comp("ËÌ³¤Æ»", $ken) . ">ËÌ³¤Æ»</option>\n";
	print "<option value=\"ÀÄ¿¹¸©\"" . comp("ÀÄ¿¹¸©", $ken) . ">ÀÄ¿¹¸©</option>\n";
	print "<option value=\"´ä¼ê¸©\"" . comp("´ä¼ê¸©", $ken) . ">´ä¼ê¸©</option>\n";
	print "<option value=\"µÜ¾ë¸©\"" . comp("µÜ¾ë¸©", $ken) . ">µÜ¾ë¸©</option>\n";
	print "<option value=\"½©ÅÄ¸©\"" . comp("½©ÅÄ¸©", $ken) . ">½©ÅÄ¸©</option>\n";
	print "<option value=\"»³·Á¸©\"" . comp("»³·Á¸©", $ken) . ">»³·Á¸©</option>\n";
	print "<option value=\"Ê¡Åç¸©\"" . comp("Ê¡Åç¸©", $ken) . ">Ê¡Åç¸©</option>\n";
	print "<option value=\"°ñ¾ë¸©\"" . comp("°ñ¾ë¸©", $ken) . ">°ñ¾ë¸©</option>\n";
	print "<option value=\"ÆÊÌÚ¸©\"" . comp("ÆÊÌÚ¸©", $ken) . ">ÆÊÌÚ¸©</option>\n";
	print "<option value=\"·²ÇÏ¸©\"" . comp("·²ÇÏ¸©", $ken) . ">·²ÇÏ¸©</option>\n";
	print "<option value=\"ºë¶Ì¸©\"" . comp("ºë¶Ì¸©", $ken) . ">ºë¶Ì¸©</option>\n";
	print "<option value=\"ÀéÍÕ¸©\"" . comp("ÀéÍÕ¸©", $ken) . ">ÀéÍÕ¸©</option>\n";
	print "<option value=\"ÅìµşÅÔ\"" . comp("ÅìµşÅÔ", $ken) . ">ÅìµşÅÔ</option>\n";
	print "<option value=\"¿ÀÆàÀî¸©\"" . comp("¿ÀÆàÀî¸©", $ken) . ">¿ÀÆàÀî¸©</option>\n";
	print "<option value=\"¿·³ã¸©\"" . comp("¿·³ã¸©", $ken) . ">¿·³ã¸©</option>\n";
	print "<option value=\"ÉÙ»³¸©\"" . comp("ÉÙ»³¸©", $ken) . ">ÉÙ»³¸©</option>\n";
	print "<option value=\"ÀĞÀî¸©\"" . comp("ÀĞÀî¸©", $ken) . ">ÀĞÀî¸©</option>\n";
	print "<option value=\"Ê¡°æ¸©\"" . comp("Ê¡°æ¸©", $ken) . ">Ê¡°æ¸©</option>\n";
	print "<option value=\"»³Íü¸©\"" . comp("»³Íü¸©", $ken) . ">»³Íü¸©</option>\n";
	print "<option value=\"Ä¹Ìî¸©\"" . comp("Ä¹Ìî¸©", $ken) . ">Ä¹Ìî¸©</option>\n";
	print "<option value=\"´ôÉì¸©\"" . comp("´ôÉì¸©", $ken) . ">´ôÉì¸©</option>\n";
	print "<option value=\"ÀÅ²¬¸©\"" . comp("ÀÅ²¬¸©", $ken) . ">ÀÅ²¬¸©</option>\n";
	print "<option value=\"°¦ÃÎ¸©\"" . comp("°¦ÃÎ¸©", $ken) . ">°¦ÃÎ¸©</option>\n";
	print "<option value=\"»°½Å¸©\"" . comp("»°½Å¸©", $ken) . ">»°½Å¸©</option>\n";
	print "<option value=\"¼¢²ì¸©\"" . comp("¼¢²ì¸©", $ken) . ">¼¢²ì¸©</option>\n";
	print "<option value=\"µşÅÔÉÜ\"" . comp("µşÅÔÉÜ", $ken) . ">µşÅÔÉÜ</option>\n";
	print "<option value=\"ÂçºåÉÜ\"" . comp("ÂçºåÉÜ", $ken) . ">ÂçºåÉÜ</option>\n";
	print "<option value=\"Ê¼¸Ë¸©\"" . comp("Ê¼¸Ë¸©", $ken) . ">Ê¼¸Ë¸©</option>\n";
	print "<option value=\"ÆàÎÉ¸©\"" . comp("ÆàÎÉ¸©", $ken) . ">ÆàÎÉ¸©</option>\n";
	print "<option value=\"ÏÂ²Î»³¸©\"" . comp("ÏÂ²Î»³¸©", $ken) . ">ÏÂ²Î»³¸©</option>\n";
	print "<option value=\"Ä»¼è¸©\"" . comp("Ä»¼è¸©", $ken) . ">Ä»¼è¸©</option>\n";
	print "<option value=\"Åçº¬¸©\"" . comp("Åçº¬¸©", $ken) . ">Åçº¬¸©</option>\n";
	print "<option value=\"²¬»³¸©\"" . comp("²¬»³¸©", $ken) . ">²¬»³¸©</option>\n";
	print "<option value=\"¹­Åç¸©\"" . comp("¹­Åç¸©", $ken) . ">¹­Åç¸©</option>\n";
	print "<option value=\"»³¸ı¸©\"" . comp("»³¸ı¸©", $ken) . ">»³¸ı¸©</option>\n";
	print "<option value=\"ÆÁÅç¸©\"" . comp("ÆÁÅç¸©", $ken) . ">ÆÁÅç¸©</option>\n";
	print "<option value=\"¹áÀî¸©\"" . comp("¹áÀî¸©", $ken) . ">¹áÀî¸©</option>\n";
	print "<option value=\"°¦É²¸©\"" . comp("°¦É²¸©", $ken) . ">°¦É²¸©</option>\n";
	print "<option value=\"¹âÃÎ¸©\"" . comp("¹âÃÎ¸©", $ken) . ">¹âÃÎ¸©</option>\n";
	print "<option value=\"Ê¡²¬¸©\"" . comp("Ê¡²¬¸©", $ken) . ">Ê¡²¬¸©</option>\n";
	print "<option value=\"º´²ì¸©\"" . comp("º´²ì¸©", $ken) . ">º´²ì¸©</option>\n";
	print "<option value=\"Ä¹ºê¸©\"" . comp("Ä¹ºê¸©", $ken) . ">Ä¹ºê¸©</option>\n";
	print "<option value=\"·§ËÜ¸©\"" . comp("·§ËÜ¸©", $ken) . ">·§ËÜ¸©</option>\n";
	print "<option value=\"ÂçÊ¬¸©\"" . comp("ÂçÊ¬¸©", $ken) . ">ÂçÊ¬¸©</option>\n";
	print "<option value=\"µÜºê¸©\"" . comp("µÜºê¸©", $ken) . ">µÜºê¸©</option>\n";
	print "<option value=\"¼¯»ùÅç¸©\"" . comp("¼¯»ùÅç¸©", $ken) . ">¼¯»ùÅç¸©</option>\n";
	print "<option value=\"²­Æì¸©\"" . comp("²­Æì¸©", $ken) . ">²­Æì¸©</option>\n";
	print "<option value=\"³¤³°\"" . comp("³¤³°", $ken) . ">³¤³°</option>\n";
}
?>
