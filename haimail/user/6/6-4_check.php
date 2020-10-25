<?
include('../inc/common.php');
include('../inc/database.php');
include('../inc/haimail_letter.php');

session_start();
$h_letter = new CHaimailLetter;

$h_letter->mail_adr_del = ToHankaku(trim($mail_adr_del));

$h_letter->ClearErrFlag();

$err_flag = false;
if (!MailAdrCheck($h_letter->mail_adr_del)) {
	$h_letter->err_mail_adr_del = true;
	$err_flag = true;
}

if (!$err_flag) {
	$sql = "SELECT HL_hml_id FROM T_HAIMAIL_LETTER WHERE HL_mail_adr=" . SqlStr($h_letter->mail_adr_del);
	$ds = CreateDynaset($sql, ORADYN_READONLY);
	if ($ds->EOF()) {
		$h_letter->err_unregist = true;
		$err_flag = true;
	}
}

$h_letter->SaveData();

if ($err_flag)
	redirect('6-4_error.php');
else
	redirect('6-5.php');
?>