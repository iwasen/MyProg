<?
// �����
$rand = "5374112481591282435131234042196122667712144723901683762966630824862004266405453034177296253053378416";

// ���祳��������
function encode_present($present_id, $mag_id, $keyword, $vol_no) {

	// �ܥ�塼���ֹ��ղ�
	$offset = $vol_no % 100;
	$code = sprintf("%02d", $offset);

	// �ץ쥼���ID�Ź沽
	$code .= encode_str($present_id, $offset);

	// ���ޥ�ID�Ź沽
	$code .= encode_str($mag_id, $offset);

	// ������ɰŹ沽
	$code .= encode_str(encode_keyword($keyword), $offset);

	// �����å������ղ�
	$sum = 0;
	for ($i = 0; $i < strlen($code); $i++) {
		$sum = ($sum + substr($code, $i, 1)) % 10;
	}
	$code .= chr((10 - $sum) % 10 + ord("0"));

	$code = substr($code, 0, 5) . "-" . substr($code, 5, 5) . "-" . substr($code, 10, 5) . "-" . substr($code, 15, 2);

	return $code;
}

// ʸ����ΰŹ沽
function encode_str($str, $offset) {
	global $rand;

	$code = "";
	for ($i = 0; $i < strlen($str); $i++) {
		$code .= chr((substr($str, $i, 1) + substr($rand, $offset, 1)) % 10 + ord("0"));
		$offset = ($offset + 1) % 100;
	}
	return $code;
}

// ������ɤΥ����ɲ�
function encode_keyword($keyword) {
	$sum = 0;
	for ($i = 0; $i < strlen($keyword); $i++) {
		$sum = ($sum + ord(substr($keyword, $i, 1)) * ($i + 1)) % 1000;
	}
	return sprintf("%03d", $sum);
}

// ������ɥ����å�
function keyword_check($code, $keyword) {
	// ������ե��åȼ���
	$offset = substr($code, 0, 2) + 0;

	// ����Υϥ��ե����
	$code = str_replace("-", "", $code);

	if (encode_str(encode_keyword($keyword), $offset) == substr($code, 13, 3))
		return true;
	else
		return false;
}

// ���祳���ɤ���ץ쥼���ID,���ޥ�ID����
function decode_present($code, &$present_id, &$mag_id) {
	// ʸ���������å�
	if (strlen($code) != 20)
		return false;

	// ����Υϥ��ե����
	$code = str_replace("-", "", $code);

	// �����å�����
	$sum = 0;
	for ($i = 0; $i < strlen($code); $i++) {
		$temp = substr($code, $i, 1);
		if ($temp < "0" || $temp > "9")
			return false;
		$sum = ($sum + $temp) % 10;
	}

	if ($sum != 0)
		return false;

	// ������ե��åȼ���
	$offset = substr($code, 0, 2) + 0;

	// �ץ쥼���ID,���ޥ�ID���Ф�
	$present_id = decode_str(substr($code, 2, 5), $offset);
	$mag_id = decode_str(substr($code, 7, 6), $offset);

	return true;
}

// ʸ���������
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