<?
/******************************************************
' System :�᡼���ۿ������ӥ�
' Content:����������å�����
'******************************************************/

session_start();

$user_login_id = $_SESSION['sma_login_id'];
$user_mail_addr = $_SESSION['sma_mail_addr'];

if ($user_login_id == '') {
	header("location: $top/login/login.php");
}
?>