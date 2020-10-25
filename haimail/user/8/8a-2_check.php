<?
include('../inc/common.php');
include('../inc/database.php');
include('../inc/member.php');
include('../inc/get_profile.php');

//=== 登録済みチェック ===
function Regixxxheck($mail_adr) {
	global $member;

	$sql = "SELECT MM_member_id FROM T_MEMBER WHERE MM_mail_adr=" . SqlStr($mail_adr) . " AND MM_status<>'9' AND MM_member_id<>'$member->member_id'";
	$ds = CreateDynaset($sql, ORADYN_READONLY);
	return $ds->EOF() ? false : true;
}

//=== 郵便番号存在チェック ===
function ZipCodeCheck($zip_code) {
	if ($zip_code == '0000000')
		$check = true;
	else {
		$sql = "SELECT ZC_zip_code FROM T_ZIPCODE WHERE ZC_zip_code='$zip_code'";
		$ds = CreateDynaset($sql, ORADYN_READONLY);
		$check = $ds->EOF() ? false : true;
	}
	return $check;
}

//=== メイン処理 ===
session_start();
$member = new CMember;

$member->kyoumi_genre = GetKyoumiGenre();
$member->mail_adr = ToHankaku(trim($_POST['mail_adr']));
$member->mail_adr2 = ToHankaku(trim($_POST['mail_adr2']));
$member->password = ToHankaku(trim($_POST['password']));
$member->password2 = ToHankaku(trim($_POST['password2']));
$member->seibetsu = $_POST['seibetsu'];
$member->mikikon = $_POST['mikikon'];
$member->sei_nen = ToHankaku(trim($_POST['sei_nen']));
$member->sei_getsu = $_POST['sei_getsu'];
$member->sei_hi = $_POST['sei_hi'];
$member->zip_kyojuu1 = ToHankaku(trim($_POST['zip_kyojuu1']));
$member->zip_kyojuu2 = ToHankaku(trim($_POST['zip_kyojuu2']));
$member->zip_kinmu1 = ToHankaku(trim($_POST['zip_kinmu1']));
$member->zip_kinmu2 = ToHankaku(trim($_POST['zip_kinmu2']));
$member->shokugyou = $_POST['shokugyou'];
$member->html_mail = $_POST['html_mail'];
$member->yokuiku_mise = GetYokuikuMise();
$member->docchiha = GetDocchiHa();
$member->oshirase_mail = $_POST['oshirase_mail'];

$member->ClearErrFlag();

$err_flag = false;

if (strstr($member->kyoumi_genre, '1') === false && strstr($member->kyoumi_genre, '2') === false) {
	$member->err_kyoumi_genre = true;
	$err_flag = true;
}

if (!MailAdrCheck($member->mail_adr)) {
	$member->err_mail_adr = true;
	$err_flag = true;
} elseif ($member->mail_adr != $member->mail_adr2) {
	$member->err_mail_adr = true;
	$err_flag = true;
} elseif (Regixxxheck($member->mail_adr)) {
	$member->err_mail_adr2 = true;
	$err_flag = true;
}

if (strlen($member->password) < 6) {
	$member->err_password = true;
	$err_flag = true;
}

if ($member->password != $member->password2) {
	$member->err_password = true;
	$err_flag = true;
}

if ($member->seibetsu == '') {
	$member->err_seibetsu = true;
	$err_flag = true;
}

if ($member->mikikon == '') {
	$member->err_mikikon = true;
	$err_flag = true;
}

if (strlen($member->sei_nen) != 4 || !NumCheck($member->sei_nen)) {
	$member->err_seinengappi = true;
	$err_flag = true;
}

if (strlen($member->zip_kyojuu1) != 3 || strlen($member->zip_kyojuu2) != 4 || !ZipCodeCheck($member->zip_kyojuu1 . $member->zip_kyojuu2)) {
	$member->err_zip_kyojuu = true;
	$err_flag = true;
}

if (strlen($member->zip_kinmu1) != 0 || strlen($member->zip_kinmu2) != 0) {
	if (strlen($member->zip_kinmu1) != 3 || strlen($member->zip_kinmu2) != 4 || !ZipCodeCheck($member->zip_kinmu1 . $member->zip_kinmu2)) {
		$member->err_zip_kinmu = true;
		$err_flag = true;
	}
}

if ($member->shokugyou == '') {
	$member->err_shokugyou = true;
	$err_flag = true;
}

if ($member->html_mail == '') {
	$member->err_html_mail = true;
	$err_flag = true;
}

if ($member->oshirase_mail == '') {
	$member->err_oshirase_mail = true;
	$err_flag = true;
}

$member->SaveData();

if ($err_flag)
	redirect('8a-2_error.php');
else
	redirect('8a-3.php');
?>