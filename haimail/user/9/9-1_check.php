<?
include('../inc/common.php');
include('../inc/database.php');

session_start();

$mail_adr = ToHankaku(trim($_POST['mail_adr']));

$_SESSION['p_mail_adr'] = $mail_adr;

if (!MailAdrCheck($mail_adr))
	redirect('9-1_error.html');
else {
	$sql = "SELECT MM_mail_adr FROM T_MEMBER WHERE MM_mail_adr=" . SqlStr($mail_adr) . " AND MM_status<>'9'";
	$ds = CreateDynaset($sql, ORADYN_READONLY);
	if ($ds->EOF())
		$url = '9-1_error.html';
	else
		$url = '9-2.php';

	redirect($url);
}
?>