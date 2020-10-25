<?
/******************************************************
' System :「きゃららFactory」インクルードファイル
' Content:チェック関数
'******************************************************/

// メールアドレスチェック
function check_mail_addr($addr) {
	return ereg("^[a-zA-Z0-9._/-]+@[a-zA-Z0-9_-]+\.[a-zA-Z0-9._-]+[^.]$", $addr);
}

// 数値チェック
function check_num($num, $min=false, $max=false) {
	if (!is_numeric($num))
		return false;

	if ($min && $num < $min)
		return false;

	if ($max && $num > $max)
		return false;

	return true;
}

// 半角文字チェック
function check_hankaku($str) {
	$len = strlen($str);
	for ($i = 0; $i < $len; $i++) {
		if (ord($str{$i}) >= 0x80)
			return false;
	}
	return true;
}

?>