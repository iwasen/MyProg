<?
//ob_start();
//mb_http_output('SJIS');
//ob_start('mb_output_handler');

//=== System Constant ===
define('MAX_KYOUMIGENRE', 200);
define('MAX_YOKUIKUMISE', 200);
define('MAX_DOCCHIHA', 100);

//=== SMTP/POP ===
define('OFFICE_ADDRESS', 'はいめーる・net事務局 <office@haimail.net>');

//=== 媒体ID ===
define('BT_IMPRESS', '30');
define('BT_NETMILE', '32');

// エスケープ文字削除
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

//=== チェックボックスの状態取得 ===
function Checkbox($cb) {
	if ($cb == '')
		return false;
	else
		return true;
}

//=== チェックボックスの状態設定 ===
function Checked($cb) {
	if ($cb == '1')
		return ' checked';
}

//=== ラジオボタンの状態設定 ===
function RadioChecked($v1, $v2) {
	if ($v1 == $v2)
		return ' checked';
}

//=== チェックボックスの状態設定2 ===
function NotChecked($cb) {
	if ($cb)
		;
	else
		return ' checked';
}

//=== 選択ボックスの状態設定 ===
function Selected($v1, $v2) {
	if ($v1 == $v2)
		return ' selected';
}

//=== メール送信 ===
function SendMail($mailto, $mailfrom, $subject, $body) {
	if ($mailto == '')
		return;

	$header = "From: $mailfrom\n";

	mb_language('Japanese');
	return mb_send_mail($mailto, $subject, str_replace("\r", '', $body), $header) ? '' : 'Error';
}

//=== メール送信情報取得 ===
function GetSendMailInfo($id, &$subject, &$from, &$body) {
	$sql = "SELECT * FROM T_MAIL_TEMPLATE WHERE MT_id='$id'";
	$ds = CreateDynaset($sql, ORADYN_READONLY);
	if (!$ds->EOF()) {
		$subject = $ds->GetValue('MT_subject');
		$from  = $ds->GetValue('MT_from');
		$body = $ds->GetValue('MT_body');
	}
}

//=== １行出力 ===
function Writeln($str) {
	echo $str, "\n";
}

//=== NULL値変換 ===
function NVL($val, $nv) {
	return ($val == null) ? $nv : $val;
}

//=== 数値のカンマ区切り ===
function NumFormat($num) {
	return number_format($num);
}

//=== 空文字を<br>に変換 ===
function BlankToBR($text) {
	return ($text == '') ? '<br>' : $text;
}

//=== HTMLのタグを表示可能文字に変換 ===
function ReplaceTag($text) {
	return nl2br(htmlspecialchars($text));
}

//=== 数字チェック ===
function NumCheck($num) {
	return is_numeric($num);
}

//=== 入力エラー記号表示 ===
function ErrMark($err_flag) {
	if ($err_flag)
		echo '<sup><font color="red">*</font></sup>';
}

//=== メールアドレスチェック ===
function MailAdrCheck($mail_adr) {
//	Const mc = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ{}./+-*=~!#$%^&_?"
	return ereg("^[a-zA-Z0-9._-]+@[a-zA-Z0-9_-]+\.[a-zA-Z0-9._-]+", $mail_adr);
}

//=== 郵便番号前３桁取得 ===
function Zip1($zip_code) {
	return substr($zip_code, 0, 3);
}

//=== 郵便番号後４桁取得 ===
function Zip2($zip_code) {
	return substr($zip_code, 3, 4);
}

//=== 文字列バイト数取得 ===
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

//=== 順序番号取得 ===
function GetNextVal($name) {
	$sql = "SELECT $name.NEXTVAL NEXTV FROM DUAL";
	$ds = CreateDynaset($sql, ORADYN_READONLY);
	if (!$ds->EOF())
		return $ds->GetValue('NEXTV');
}

//=== 半角変換 ===
function ToHankaku($str) {
	return mb_convert_kana($str, 'as');
}

//=== SQL文字列変換 ===
function SqlStr($str) {
	return "'" . str_replace("'", "''", $str) . "'";
}

//=== SQL数値 ===
function SqlNum($num) {
	return $num == '' ? 'NULL' : $num;
}

//=== SQL日付 ===
function SqlDate($num) {
	return $num == '' ? 'NULL' : "'$num'";
}

//=== 年齢取得 ===
function GetAge($seinengappi) {
	$today = time();
	$ary = explode('/', $seinengappi);
	if (date('m', $today) * 100 + date('d', $today) >= $ary[1] * 100 + $ary[2])
		return date('Y', $today) - $ary[0];
	else
		return date('Y', $today) - $ary[0] - 1;
}

//=== 郵便番号から住所取得 ===
function GetAddress($zip) {
	$sql = "SELECT ZC_todoufuken,ZC_shikuchouson FROM T_ZIPCODE WHERE ZC_zip_code='$zip'";
	$ds = CreateDynaset($sql, ORADYN_READONLY);
	if ($ds->EOF())
		return '';
	else
		return $ds->GetValue('ZC_todoufuken') . $ds->GetValue('ZC_shikuchouson');
}

//=== 数値からID作成 ===
function MakeID($num, $l) {
	return str_pad($num, $l, '0', STR_PAD_LEFT);
}

// リダイレクト
function redirect($url) {
	header("location: $url");
	exit;
}
?>