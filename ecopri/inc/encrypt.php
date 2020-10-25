<?
/******************************************************
' System :Eco-footprint 共通
' Content:暗号化処理
'******************************************************/

// 乱数列
$rand = "5374112481591282435131234042196122667712144723901683762966630824862004266405453034177296253053378416";

// 文字列の暗号化
function encrypt_number($num, $len) {
	global $rand;

	$str = sprintf("%0${len}d", $num);
	$offset = $num % 100;

	for ($i = 0; $i < $len - 2; $i++) {
		$code .= chr((substr($str, $i, 1) + substr($rand, $offset, 1)) % 10 + ord("0"));
		$offset = ($offset + 1) % 100;
	}

	$offset = (int)substr($code, -2, 2);
	for ($i = $len - 2; $i < $len; $i++) {
		$code .= chr((substr($str, $i, 1) + substr($rand, $offset, 1)) % 10 + ord("0"));
		$offset = ($offset + 1) % 100;
	}

	$sum = 0;
	for ($i = 0; $i < $len; $i++) {
		$sum = ($sum + substr($code, $i, 1)) % 10;
	}
	$code .= chr((10 - $sum) % 10 + ord("0"));

	return $code;
}

// 文字列の復号
function decrypt_number($code) {
	global $rand;

	$len = strlen($code);

	$sum = 0;
	for ($i = 0; $i < $len; $i++) {
		$temp = substr($code, $i, 1);
		if ($temp < "0" || $temp > "9")
			return false;
		$sum = ($sum + $temp) % 10;
	}

	if ($sum != 0)
		return false;

	$len--;
	$code = substr($code, 0, $len);
	$offset = (int)substr($code, -4, 2);
	for ($i = $len - 2; $i < $len; $i++) {
		$str1 .= chr((substr($code, $i, 1) - substr($rand, $offset, 1) + 10) % 10 + ord("0"));
		$offset = ($offset + 1) % 100;
	}


	$offset = (int)$str1;
	for ($i = 0; $i < $len - 2; $i++) {
		$str2 .= chr((substr($code, $i, 1) - substr($rand, $offset, 1) + 10) % 10 + ord("0"));
		$offset = ($offset + 1) % 100;
	}

	$str = $str2 . $str1;

	return (int)$str;
}

// モニターIDとアンケートIDの暗号化
function encrypt_param($enquete_id, $monitor_id) {
	return encrypt_number($enquete_id, 6) . encrypt_number($monitor_id, 6);
}

// モニターIDとアンケートIDの復号
function decrypt_param($code, &$enquete_id, &$monitor_id) {
	$enquete_id = decrypt_number(substr($code, 0, 7));
	$monitor_id = decrypt_number(substr($code, 7, 7));
}
?>