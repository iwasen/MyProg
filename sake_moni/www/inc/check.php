<?
/******************************************************
' System :�������ʪ�Υ��󥱡��ȥ�˥�������
' Content:�Ƽ�ǡ��������å�
'******************************************************/

// �᡼�륢�ɥ쥹�����å�
function check_mail_addr($addr) {
	return ereg("^[a-zA-Z0-9._/-]+@[a-zA-Z0-9_-]+\.[a-zA-Z0-9._-]+[^.]$", $addr);
}

// URLɽ�������å�
function check_url($url) {
	return ereg("^https?://.+", $url);
}

// ���ͥ����å�
function check_num($num, $min=false, $max=false) {
	if (!is_numeric($num))
		return false;

	if ($min && $num < $min)
		return false;

	if ($max && $num > $max)
		return false;

	return true;
}

// Ⱦ��ʸ�������å�
function check_hankaku($str) {
	$len = strlen($str);
	for ($i = 0; $i < $len; $i++) {
		if (ord($str{$i}) >= 0x80)
			return false;
	}
	return true;
}

// �ѥ����ͭ���������å�
function check_password($password) {
	return ereg("^[a-zA-Z0-9._-]+$", $password);
}

// ͹���ֹ�����å�
function check_zip($zip1, $zip2) {
	if (!is_numeric($zip1) || !is_numeric($zip2))
		return false;

	if (strlen($zip1) != 3 || strlen($zip2) != 4)
		return false;

	return true;
}

// ͹���ֹ�����å�
function check_zip2($zip) {
	if (!is_numeric($zip))
		return false;

	if (strlen($zip) != 7)
		return false;

	return true;
}

// �����ֹ�����å�
function check_tel($tel1, $tel2, $tel3) {
	if (!is_numeric($tel1) || !is_numeric($tel2) || !is_numeric($tel3))
		return false;

	if (strlen($tel1) < 2 || strlen($tel2) < 1 || strlen($tel3) < 4)
		return false;

	return true;
}

// �������ʥ����å�
function check_kana($c) {
		$retcd = true;

		$len = mb_strlen($c);
		for ($i = 0; $i < $len; $i++) {
			$c1 = mb_substr($c, $i, 1);
			if (($c1 < '��' || '��' < $c1) && $c1 != '��' && $c1 != '��' && $c1 != '��' && $c1 != '��') {
				$retcd = false;
				break;
			}
		}
		return $retcd;
}

// ���ե����å�
function check_date($date) {
	$ary = explode('/', $date);
	if (count($ary) != 3)
		return false;

	return checkdate($ary[1], $ary[2], $ary[0]);
}
?>