<?
include('../inc/common.php');
include('../inc/database.php');
include('../inc/reginfo.php');
include('../inc/get_profile.php');

//=== 登録済みチェック ===
function Regixxxheck($mail_adr) {
	$sql = "SELECT MM_member_id FROM T_MEMBER WHERE MM_mail_adr=" . SqlStr($mail_adr) . " AND MM_status<>'9'";
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
$reginfo = new CRegInfo;

$reginfo->mail_adr = ToHankaku(trim($_POST['mail_adr']));
$reginfo->mail_adr2 = ToHankaku(trim($_POST['mail_adr2']));
$reginfo->password = ToHankaku(trim($_POST['password']));
$reginfo->password2 = ToHankaku(trim($_POST['password2']));
$reginfo->seibetsu = $_POST['seibetsu'];
$reginfo->mikikon = $_POST['mikikon'];
$reginfo->sei_nen = ToHankaku(trim($_POST['sei_nen']));
$reginfo->sei_getsu = $_POST['sei_getsu'];
$reginfo->sei_hi = $_POST['sei_hi'];
$reginfo->zip_kyojuu1 = ToHankaku(trim($_POST['zip_kyojuu1']));
$reginfo->zip_kyojuu2 = ToHankaku(trim($_POST['zip_kyojuu2']));
$reginfo->zip_kinmu1 = ToHankaku(trim($_POST['zip_kinmu1']));
$reginfo->zip_kinmu2 = ToHankaku(trim($_POST['zip_kinmu2']));
$reginfo->shokugyou = $_POST['shokugyou'];
$reginfo->html_mail = $_POST['html_mail'];
$reginfo->yokuiku_mise = GetYokuikuMise();
$reginfo->docchiha = GetDocchiHa();
$reginfo->oshirase_mail = $_POST['oshirase_mail'];

//ミレニアム・キャンペーン====
$_SESSION['present'] = $_POST['present'];
//============================

if (!isset($_POST['btn_kakunin'])) {
	$reginfo->SaveData();
	redirect('../index.php');
}

$reginfo->ClearErrFlag();

$err_flag = false;
if (!MailAdrCheck($reginfo->mail_adr)) {
	$reginfo->err_mail_adr = true;
	$err_flag = true;
} elseif ($reginfo->mail_adr != $reginfo->mail_adr2) {
	$reginfo->err_mail_adr = true;
	$err_flag = true;
} elseif (Regixxxheck($reginfo->mail_adr)) {
	$reginfo->err_already_regist = true;
	$err_flag = true;
}

if (strlen($reginfo->password) < 6) {
	$reginfo->err_password = true;
	$err_flag = true;
}

if ($reginfo->password != $reginfo->password2) {
	$reginfo->err_password = true;
	$err_flag = true;
}

if (strlen($reginfo->seibetsu) == 0) {
	$reginfo->err_seibetsu = true;
	$err_flag = true;
}

if (strlen($reginfo->mikikon) == 0) {
	$reginfo->err_mikikon = true;
	$err_flag = true;
}

if (strlen($reginfo->sei_nen) != 4 || !NumCheck($reginfo->sei_nen)) {
	$reginfo->err_seinengappi = true;
	$err_flag = true;
} else {
	if (checkdate($reginfo->sei_getsu, $reginfo->sei_hi, $reginfo->sei_nen)) {
		if (GetAge($seinengappi) < 10) {
			$reginfo->err_seinengappi = true;
			$err_flag = true;
		}
	} else {
		$reginfo->err_seinengappi = true;
		$err_flag = true;
	}
}

if (strlen($reginfo->zip_kyojuu1) != 3 || strlen($reginfo->zip_kyojuu2) != 4 || !ZipCodeCheck($reginfo->zip_kyojuu1 . $reginfo->zip_kyojuu2)) {
	$reginfo->err_zip_kyojuu = true;
	$err_flag = true;
}

if (strlen($reginfo->zip_kinmu1) != 0 || strlen($reginfo->zip_kinmu2) != 0) {
	if (strlen($reginfo->zip_kinmu1) != 3 || strlen($reginfo->zip_kinmu2) != 4 || !ZipCodeCheck($reginfo->zip_kinmu1 . $reginfo->zip_kinmu2)) {
		$reginfo->err_zip_kinmu = true;
		$err_flag = true;
	}
}

if (strlen($reginfo->shokugyou) == 0) {
	$reginfo->err_shokugyou = true;
	$err_flag = true;
}

if (strlen($reginfo->html_mail) == 0) {
	$reginfo->err_html_mail = true;
	$err_flag = true;
}

if (strlen($reginfo->oshirase_mail) == 0) {
	$reginfo->err_oshirase_mail = true;
	$err_flag = true;
}

$reginfo->SaveData();

if ($err_flag)
	redirect('personal_error.php');
else
	redirect('1-3.php');
?>