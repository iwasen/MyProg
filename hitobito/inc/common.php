<?php
/**
 * ひとびと･net 共通ファイル
 *
 * 事務局共通関数
 *
 *
 * @package
 * @author
 * @version
 */

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
	if (is_array($selected)) {
		if (in_array($value, $selected))
			return "value='$value' selected";
	} else {
		if ($value == $selected)
			return "value='$value' selected";
	}
	return "value='$value'";
}

// disabled 出力
function disabled($flag) {
	if ($flag)
		return ' disabled';
}

// SQLタイムスタンプ取得
function sql_time($time) {
	return strtotime(strtok($time, '.+'));
}

// メッセージ表示
function disp_msg($msg, $pre='', $post='') {
	if (is_array($msg)) {
		foreach($msg as $str)
			echo "$pre$str$post\n";
	} elseif ($msg != '')
		echo "$pre$msg$post\n";
}
?>