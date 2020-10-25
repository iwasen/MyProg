<?
// 乱数列
$rand = "5374112481591282435131234042196122667712144723901683762966630824862004266405453034177296253053378416";

// 応募コード生成
function encode_present($present_id, $mag_id, $keyword, $vol_no) {

	// ボリューム番号付加
	$offset = $vol_no % 100;
	$code = sprintf("%02d", $offset);

	// プレゼントID暗号化
	$code .= encode_str($present_id, $offset);

	// メルマガID暗号化
	$code .= encode_str($mag_id, $offset);

	// キーワード暗号化
	$code .= encode_str(encode_keyword($keyword), $offset);

	// チェックサム付加
	$sum = 0;
	for ($i = 0; $i < strlen($code); $i++) {
		$sum = ($sum + substr($code, $i, 1)) % 10;
	}
	$code .= chr((10 - $sum) % 10 + ord("0"));

	$code = substr($code, 0, 5) . "-" . substr($code, 5, 5) . "-" . substr($code, 10, 5) . "-" . substr($code, 15, 2);

	return $code;
}

// 文字列の暗号化
function encode_str($str, $offset) {
	global $rand;

	$code = "";
	for ($i = 0; $i < strlen($str); $i++) {
		$code .= chr((substr($str, $i, 1) + substr($rand, $offset, 1)) % 10 + ord("0"));
		$offset = ($offset + 1) % 100;
	}
	return $code;
}

// キーワードのコード化
function encode_keyword($keyword) {
	$sum = 0;
	for ($i = 0; $i < strlen($keyword); $i++) {
		$sum = ($sum + ord(substr($keyword, $i, 1)) * ($i + 1)) % 1000;
	}
	return sprintf("%03d", $sum);
}

// キーワードチェック
function keyword_check($code, $keyword) {
	// 乱数オフセット取得
	$offset = substr($code, 0, 2) + 0;

	// 途中のハイフンを削除
	$code = str_replace("-", "", $code);

	if (encode_str(encode_keyword($keyword), $offset) == substr($code, 13, 3))
		return true;
	else
		return false;
}

// 応募コードからプレゼントID,メルマガID解読
function decode_present($code, &$present_id, &$mag_id) {
	// 文字数チェック
	if (strlen($code) != 20)
		return false;

	// 途中のハイフンを削除
	$code = str_replace("-", "", $code);

	// チェックサム
	$sum = 0;
	for ($i = 0; $i < strlen($code); $i++) {
		$temp = substr($code, $i, 1);
		if ($temp < "0" || $temp > "9")
			return false;
		$sum = ($sum + $temp) % 10;
	}

	if ($sum != 0)
		return false;

	// 乱数オフセット取得
	$offset = substr($code, 0, 2) + 0;

	// プレゼントID,メルマガID取り出し
	$present_id = decode_str(substr($code, 2, 5), $offset);
	$mag_id = decode_str(substr($code, 7, 6), $offset);

	return true;
}

// 文字列の復号
function decode_str($str, $offset) {
	global $rand;

	$code = "";
	for ($i = 0; $i < strlen($str); $i++) {
		$code .= chr((substr($str, $i, 1) - substr($rand, $offset, 1) + 10) % 10 + ord("0"));
		$offset = ($offset + 1) % 100;
	}
	return $code;
}
?>