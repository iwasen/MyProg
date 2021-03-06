<?
/******************************************************
' System :きかせて・net共通
' Content:フォーマット出力
'******************************************************/

// 日付(YYYY/MM/DD)
function format_date($date, $def='設定なし') {
	if ($date == '')
		return $def;
	else {
		sscanf($date, "%d-%d-%d", &$y, &$m, &$d);
		return sprintf("%04d/%02d/%02d", $y, $m, $d);
	}
}

// 日時(YYYY/MM/DD hh:mm)
function format_datetime($date, $def='設定なし') {
	if ($date == '')
		return $def;
	else {
		sscanf($date, "%d-%d-%d %d:%d", &$y, &$m, &$d, &$h, &$i);
		return sprintf("%04d/%02d/%02d %02d:%02d", $y, $m, $d, $h, $i);
	}
}

// 郵便番号(999-9999)
function format_zip($zip, $def='') {
	if ($zip == '')
		return $def;
	elseif (strlen($zip) > 3)
		return substr($zip, 0, 3) . '-' . substr($zip, 3, 4);
	else
		return $zip;
}

// 電話番号(99-9999-9999)
function format_tel($tel1, $tel2, $tel3, $def='') {
	if ($tel1 == '' && $tel2 == '' && $tel3 == '')
		return $def;
	else
		return "$tel1-$tel2-$tel3";
}

// パーセント
function format_percent($a, $b, $c = 0) {
	return number_format($b != 0 ? 100.0 * $a / $b : 0, $c) . '%';
}
?>