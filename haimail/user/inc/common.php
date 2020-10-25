<?
//ob_start();
//mb_http_output('SJIS');
//ob_start('mb_output_handler');

//=== System Constant ===
define('MAX_KYOUMIGENRE', 200);
define('MAX_YOKUIKUMISE', 200);
define('MAX_DOCCHIHA', 100);

//=== SMTP/POP ===
define('OFFICE_ADDRESS', '�Ϥ��᡼�롦net��̳�� <office@haimail.net>');

//=== ����ID ===
define('BT_IMPRESS', '30');
define('BT_NETMILE', '32');

// ����������ʸ�����
if (get_magic_quotes_gpc()) {
	foreach (array_keys($_GET) as $varname) {
		if (!is_array($_GET[$varname]))
			$_GET[$varname] = stripslashes($_GET[$varname]);
		if (!is_array($GLOBALS[$varname]))
			$GLOBALS[$varname] = stripslashes($GLOBALS[$varname]);
	}
	foreach (array_keys($_POST) as $varname) {
		if (!is_array($_POST[$varname]))
			$_POST[$varname] = stripslashes($_POST[$varname]);
		if (!is_array($GLOBALS[$varname]))
			$GLOBALS[$varname] = stripslashes($GLOBALS[$varname]);
	}
}

//=== �����å��ܥå����ξ��ּ��� ===
function Checkbox($cb) {
	if ($cb == '')
		return false;
	else
		return true;
}

//=== �����å��ܥå����ξ������� ===
function Checked($cb) {
	if ($cb == '1')
		return ' checked';
}

//=== �饸���ܥ���ξ������� ===
function RadioChecked($v1, $v2) {
	if ($v1 == $v2)
		return ' checked';
}

//=== �����å��ܥå����ξ�������2 ===
function NotChecked($cb) {
	if ($cb)
		;
	else
		return ' checked';
}

//=== ����ܥå����ξ������� ===
function Selected($v1, $v2) {
	if ($v1 == $v2)
		return ' selected';
}

//=== �᡼������ ===
function SendMail($mailto, $mailfrom, $subject, $body) {
	if ($mailto == '')
		return;

	$header = "From: $mailfrom\n";

	mb_language('Japanese');
	return mb_send_mail($mailto, $subject, str_replace("\r", '', $body), $header) ? '' : 'Error';
}

//=== �᡼������������� ===
function GetSendMailInfo($id, &$subject, &$from, &$body) {
	$sql = "SELECT * FROM T_MAIL_TEMPLATE WHERE MT_id='$id'";
	$ds = CreateDynaset($sql, ORADYN_READONLY);
	if (!$ds->EOF()) {
		$subject = $ds->GetValue('MT_subject');
		$from  = $ds->GetValue('MT_from');
		$body = $ds->GetValue('MT_body');
	}
}

//=== ���Խ��� ===
function Writeln($str) {
	echo $str, "\n";
}

//=== NULL���Ѵ� ===
function NVL($val, $nv) {
	return ($val == null) ? $nv : $val;
}

//=== ���ͤΥ���޶��ڤ� ===
function NumFormat($num) {
	return number_format($num);
}

//=== ��ʸ����<br>���Ѵ� ===
function BlankToBR($text) {
	return ($text == '') ? '<br>' : $text;
}

//=== HTML�Υ�����ɽ����ǽʸ�����Ѵ� ===
function ReplaceTag($text) {
	return nl2br(htmlspecialchars($text));
}

//=== ���������å� ===
function NumCheck($num) {
	return is_numeric($num);
}

//=== ���ϥ��顼����ɽ�� ===
function ErrMark($err_flag) {
	if ($err_flag)
		echo '<sup><font color="red">*</font></sup>';
}

//=== �᡼�륢�ɥ쥹�����å� ===
function MailAdrCheck($mail_adr) {
//	Const mc = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ{}./+-*=~!#$%^&_?"
	return ereg("^[a-zA-Z0-9._-]+@[a-zA-Z0-9_-]+\.[a-zA-Z0-9._-]+", $mail_adr);
}

//=== ͹���ֹ���������� ===
function Zip1($zip_code) {
	return substr($zip_code, 0, 3);
}

//=== ͹���ֹ�壴����� ===
function Zip2($zip_code) {
	return substr($zip_code, 3, 4);
}

//=== ʸ����Х��ȿ����� ===
function StrBytes($str) {
	$n = 0;
	$l = strlen($str);
	for ($i = 1; $i <= $l; $i++) {
		$a = ord($str{$i});
		if ($a >= 0 && $a < 256)
			$n++;
		else
			$n += 2;
	}

	return $n;
}

//=== ����ֹ���� ===
function GetNextVal($name) {
	$sql = "SELECT $name.NEXTVAL NEXTV FROM DUAL";
	$ds = CreateDynaset($sql, ORADYN_READONLY);
	if (!$ds->EOF())
		return $ds->GetValue('NEXTV');
}

//=== Ⱦ���Ѵ� ===
function ToHankaku($str) {
	return mb_convert_kana($str, 'as');
}

//=== SQLʸ�����Ѵ� ===
function SqlStr($str) {
	return "'" . str_replace("'", "''", $str) . "'";
}

//=== SQL���� ===
function SqlNum($num) {
	return $num == '' ? 'NULL' : $num;
}

//=== SQL���� ===
function SqlDate($num) {
	return $num == '' ? 'NULL' : "'$num'";
}

//=== ǯ����� ===
function GetAge($seinengappi) {
	$today = time();
	$ary = explode('/', $seinengappi);
	if (date('m', $today) * 100 + date('d', $today) >= $ary[1] * 100 + $ary[2])
		return date('Y', $today) - $ary[0];
	else
		return date('Y', $today) - $ary[0] - 1;
}

//=== ͹���ֹ椫�齻����� ===
function GetAddress($zip) {
	$sql = "SELECT ZC_todoufuken,ZC_shikuchouson FROM T_ZIPCODE WHERE ZC_zip_code='$zip'";
	$ds = CreateDynaset($sql, ORADYN_READONLY);
	if ($ds->EOF())
		return '';
	else
		return $ds->GetValue('ZC_todoufuken') . $ds->GetValue('ZC_shikuchouson');
}

//=== ���ͤ���ID���� ===
function MakeID($num, $l) {
	return str_pad($num, $l, '0', STR_PAD_LEFT);
}

// ������쥯��
function redirect($url) {
	header("location: $url");
	exit;
}
?>