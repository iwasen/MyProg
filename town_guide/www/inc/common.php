<?
/******************************************************
' System :タウンガイドWebアンケート
' Content:共通インクルードファイル
'******************************************************/

// リダイレクト
function redirect($url) {
	header("location: $url");
	exit;
}

// value 出力
function value($value) {
	return 'value="' . htmlspecialchars(trim($value)) . '"';
}

// checked 出力
function checked($flag) {
	return $flag ? 'checked' : '';
}

// value, checked 出力
function value_checked($value, $checked) {
	if ($value == $checked)
		return "value='$value' checked";
	else
		return "value='$value'";
}

// selected 出力
function selected($flag) {
	return $flag ? 'selected' : '';
}

// value, selected 出力
function value_selected($value, $selected) {
	if ($value == $selected)
		return "value='$value' selected";
	else
		return "value='$value'";
}
?>