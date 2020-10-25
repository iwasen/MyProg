<?
/******************************************************
' System :「きゃららFactory」インクルードファイル
' Content:共通汎用関数
'******************************************************/

// リダイレクト
function redirect($url) {
	header("location: $url");
	exit;
}

// SQLタイムスタンプ取得
function sql_time($time) {
	return strtotime(strtok($time, '.+'));
}

// value 出力
function value($value) {
	return 'value="' . htmlspecialchars(trim($value)) . '"';
}

// value, checked 出力
function value_checked($value, $checked) {
	if ($value == $checked)
		return "value='$value' checked";
	else
		return "value='$value'";
}

// value, selected 出力
function value_selected($value, $selected) {
	if ($value == $selected)
		return "value='$value' selected";
	else
		return "value='$value'";
}

// checked 出力
function checked($flag) {
	return $flag ? 'checked' : '';
}

// disabled 出力
function disabled($flag) {
	if ($flag)
		return ' disabled';
}

// メッセージ表示
function disp_msg($msg, $pre='', $post='') {
	if (is_array($msg)) {
		foreach($msg as $str)
			echo "$pre$str$post\n";
	} elseif ($msg != '')
		echo "$pre$msg$post\n";
}

// 空白削除
function strip_blank($str) {
	return str_replace(' ', '', mb_convert_kana($str, 's'));
}

?>