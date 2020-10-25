<?
// 乱数列
$rand = "53741124815912824351312340421961226677121447239016837629666308248620042664054530341772962530533784161814848363";

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

// 設定後URL表示
function set_url($ct_id) {
	$ct_id = encrypt_number($ct_id,8);
	echo "http://www.melonpan.net/cl.php?$ct_id";
}
?>