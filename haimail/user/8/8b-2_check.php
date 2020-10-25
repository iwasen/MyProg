<?
include('../inc/common.php');
include('../inc/shoukai.php');

session_start();
$shoukai = new CShoukai;

$shoukai->nickname = $_POST['nickname'];
$shoukai->tomodachi_adr = $_POST['tomodachi_adr'];
$shoukai->message = $_POST['message'];

$shoukai->ClearErrFlag();

$err_flag = false;
if ($shoukai->nickname == '') {
	$shoukai->err_nickname = true;
	$err_flag = true;
}

if ($shoukai->tomodachi_adr == '') {
	$shoukai->err_tomodachi_adr = true;
	$err_flag = true;
} else {
	$arry = explode("\n", $shoukai->tomodachi_adr);
	foreach ($arry as $mail_adr) {
		$mail_adr = ToHankaku(trim($mail_adr));
		if ($mail_adr != '') {
			if (!MailAdrCheck($mail_adr)) {
				$shoukai->err_tomodachi_adr = true;
				$err_flag = true;
				break;
			}
		}
	}
}

if ($shoukai->message == '') {
	$shoukai->err_message = true;
	$err_flag = true;
} else {
	if (StrBytes($shoukai->message) > 400) {
		$shoukai->err_message_len = true;
		$err_flag = true;
	}
}

$shoukai->SaveData();

if ($err_flag)
	redirect('8b-2_error.php');
else
	redirect('8b-3.php');
?>