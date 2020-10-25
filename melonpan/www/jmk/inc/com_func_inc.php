<?php

//'=== 	Ç¯¤ÎÁªÂò»è½ÐÎÏ(2000¡Á¸½ºß+10Ç¯) ===
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
//'=== 	Ç¯¤ÎÁªÂò»è½ÐÎÏ(1900¡Á) ===
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
//'=== 	Ç¯¤ÎÁªÂò»è½ÐÎÏ(systemÇ¯¡Á10Ç¯) ===
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
//'=== ·î¤ÎÁªÂò»è½ÐÎÏ ===
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

//'=== Æü¤ÎÁªÂò»è½ÐÎÏ ===
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

//'=== »þ´ÖÁªÂò»è½ÐÎÏ ===
function SelectHour($h) {
	$i = 0;
	if (!$h) {
		print "<option value=\"\" selected></option>\n";
		while ($i <= 23) {
			if ($i < 12 ) {
				$hour = "AM " . $i .  "»þ";
			} elseif ($i >= 12) {
				$hour = "PM " . ($i-12) . "»þ";
			}
			print "<option value=\"$i\">" . $hour . "</option>\n";
			$i++;
		}
	} else {
		while ($i <= 23) {
			if ($i < 12 ) {
				$hour = "AM " . $i .  "»þ";
			} elseif ($i >= 12) {
				$hour = "PM " . ($i-12) . "»þ";
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
//'=== Ê¬ÁªÂò»è½ÐÎÏ ===
function SelectMinute($mi) {
	$i = 0;
	if (!$mi) {
		print "<option value=\"\" selected></option>\n";
		while ($i < 60) {
			$minute =  $i .  "Ê¬";
			print "<option value=\"$i\">" . $minute	 . "</option>\n";
			$i=$i+10;
		}
	} else {
		while ($i < 60) {
			$minute =  $i .  "Ê¬";
			if ($i == $mi) {
				print "<option value=\"$i\" selected>". $minute ."</option>\n";
			} else {
				print "<option value=\"$i\">" . $minute . "</option>\n";
			}
			$i=$i+10;
		}
	}
}
//'=== ¥·¥ó¥°¥ë¥¯¥©¡¼¥ÈÃÖ´¹ ===
function replace_single($data) {
	return str_replace("'", "''", stripslashes($data));
}
//=== ²þ¹Ô¥³¡¼¥É¤ò¼è¤ë ===
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
//'=== 	ÅÔÆ»ÉÜ¸©¤ÎÁªÂò»è½ÐÎÏ ===
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
	print "<option value=\"ÅìµþÅÔ\"" . comp("ÅìµþÅÔ", $ken) . ">ÅìµþÅÔ</option>\n";
	print "<option value=\"¿ÀÆàÀî¸©\"" . comp("¿ÀÆàÀî¸©", $ken) . ">¿ÀÆàÀî¸©</option>\n";
	print "<option value=\"¿·³ã¸©\"" . comp("¿·³ã¸©", $ken) . ">¿·³ã¸©</option>\n";
	print "<option value=\"ÉÙ»³¸©\"" . comp("ÉÙ»³¸©", $ken) . ">ÉÙ»³¸©</option>\n";
	print "<option value=\"ÀÐÀî¸©\"" . comp("ÀÐÀî¸©", $ken) . ">ÀÐÀî¸©</option>\n";
	print "<option value=\"Ê¡°æ¸©\"" . comp("Ê¡°æ¸©", $ken) . ">Ê¡°æ¸©</option>\n";
	print "<option value=\"»³Íü¸©\"" . comp("»³Íü¸©", $ken) . ">»³Íü¸©</option>\n";
	print "<option value=\"Ä¹Ìî¸©\"" . comp("Ä¹Ìî¸©", $ken) . ">Ä¹Ìî¸©</option>\n";
	print "<option value=\"´ôÉì¸©\"" . comp("´ôÉì¸©", $ken) . ">´ôÉì¸©</option>\n";
	print "<option value=\"ÀÅ²¬¸©\"" . comp("ÀÅ²¬¸©", $ken) . ">ÀÅ²¬¸©</option>\n";
	print "<option value=\"°¦ÃÎ¸©\"" . comp("°¦ÃÎ¸©", $ken) . ">°¦ÃÎ¸©</option>\n";
	print "<option value=\"»°½Å¸©\"" . comp("»°½Å¸©", $ken) . ">»°½Å¸©</option>\n";
	print "<option value=\"¼¢²ì¸©\"" . comp("¼¢²ì¸©", $ken) . ">¼¢²ì¸©</option>\n";
	print "<option value=\"µþÅÔÉÜ\"" . comp("µþÅÔÉÜ", $ken) . ">µþÅÔÉÜ</option>\n";
	print "<option value=\"ÂçºåÉÜ\"" . comp("ÂçºåÉÜ", $ken) . ">ÂçºåÉÜ</option>\n";
	print "<option value=\"Ê¼¸Ë¸©\"" . comp("Ê¼¸Ë¸©", $ken) . ">Ê¼¸Ë¸©</option>\n";
	print "<option value=\"ÆàÎÉ¸©\"" . comp("ÆàÎÉ¸©", $ken) . ">ÆàÎÉ¸©</option>\n";
	print "<option value=\"ÏÂ²Î»³¸©\"" . comp("ÏÂ²Î»³¸©", $ken) . ">ÏÂ²Î»³¸©</option>\n";
	print "<option value=\"Ä»¼è¸©\"" . comp("Ä»¼è¸©", $ken) . ">Ä»¼è¸©</option>\n";
	print "<option value=\"Åçº¬¸©\"" . comp("Åçº¬¸©", $ken) . ">Åçº¬¸©</option>\n";
	print "<option value=\"²¬»³¸©\"" . comp("²¬»³¸©", $ken) . ">²¬»³¸©</option>\n";
	print "<option value=\"¹­Åç¸©\"" . comp("¹­Åç¸©", $ken) . ">¹­Åç¸©</option>\n";
	print "<option value=\"»³¸ý¸©\"" . comp("»³¸ý¸©", $ken) . ">»³¸ý¸©</option>\n";
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

// ¥½¡¼¥È¥ê¥¹¥È¥Ø¥Ã¥À
function sort_header($column, $text) {
	global $sort_col, $sort_dir;

	if ($column) {
		if ($column == $sort_col) {
			if ($sort_dir == 0) {
				$bgcolor = ' bgcolor="skyblue"';
				$dir = 1;
				$title = "¥¯¥ê¥Ã¥¯¤¹¤ë¤È{$text}¤Ç¹ß½ç¤Ë¥½¡¼¥È¤·¤Þ¤¹";
			} else {
				$bgcolor = ' bgcolor="pink"';
				$dir = 0;
				$title = "¥¯¥ê¥Ã¥¯¤¹¤ë¤È{$text}¤Ç¾º½ç¤Ë¥½¡¼¥È¤·¤Þ¤¹";
			}
		} else {
			$dir = 0;
			$title = "¥¯¥ê¥Ã¥¯¤¹¤ë¤È{$text}¤Ç¾º½ç¤Ë¥½¡¼¥È¤·¤Þ¤¹";
		}
		echo "<th$bgcolor onclick='sort_list($column,$dir)' style='cursor:hand' title='$title'>$text</th>\n";
	} else
		echo "<th>$text</th>\n";
}

// É½¼¨¹Ô¿ô
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

// ¿ô»ú¥Õ¥©¡¼¥Þ¥Ã¥È
function format_number($number) {
	if ($number < 0)
		return '<font color="red">'. number_format($number). '</font>';
	else
		return number_format($number);
}

// ¥·¥¹¥Æ¥à¥¨¥é¡¼½èÍý
function system_error($msg, $fname) {
	redirect('../common/system_error.php');
}

// ¥»¥Ã¥·¥ç¥óÊÑ¿ô¼èÆÀ
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