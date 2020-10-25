<?
/******************************************************
' System :「きゃららFactory」インクルードファイル
' Content:フォーマット出力
'******************************************************/

// 日付(YYYY/MM/DD)
function format_date($date, $def='設定なし') {
	if ($date == '')
		return $def;
	else
		return  date('Y/m/d', sql_time($date));
}

// 日時(YYYY/MM/DD hh:mm)
function format_datetime($date, $def='設定なし') {
	if ($date == '')
		return $def;
	else
		return date('Y/m/d H:i', sql_time($date));
}

// 郵便番号(999-9999)
function format_zip($zip, $def='') {
	if ($zip == '')
		return $def;
	else
		return substr($zip, 0, 3) . '-' . substr($zip, 3, 4);
}

// 電話番号(99-9999-9999)
function format_tel($tel1, $tel2, $tel3, $def='') {
	if ($tel1 == '' && $tel2 == '' && $tel3 == '')
		return $def;
	else
		return "$tel1-$tel2-$tel3";
}
?>