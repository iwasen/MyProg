<?
include('../inc/common.php');
include('../inc/haimail_letter.php');

session_start();
$h_letter = new CHaimailLetter;

$h_letter->mail_adr = ToHankaku(trim($mail_adr));

$h_letter->ClearErrFlag();

$err_flag = false;
if (!MailAdrCheck($h_letter->mail_adr)) {
	$h_letter->err_mail_adr = true;
	$err_flag = true;
}

$h_letter->SaveData();

if ($err_flag)
	redirect('6-1_error.php');
else
	redirect('6-2.php');
?>