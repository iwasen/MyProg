<?
/******************************************************
' System :�᡼���ۿ������ӥ�
' Content:����������å�
'******************************************************/

$top = '..';
$inc = "$top/inc";
include("$inc/common.php");
include("$inc/database.php");

$sql = sprintf("SELECT us_user_id,us_mail_addr FROM m_user WHERE us_mail_addr=%s AND us_password=%s",
		sql_char($_POST['login_id']),
		sql_char($_POST['password']));
$result = db_exec($sql);
if (pg_numrows($result)) {
	$fetch = pg_fetch_object($result, 0);

	// ���å����˥᡼�륢�ɥ쥹����¸�ʼ���ǥե���Ȥ�ɽ�������뤿���
	if ($_POST['system'] == '')
		SetCookie('sma_login_id', $fetch->us_mail_addr, COOKIE_EXPIRE, '/');

	// ���å����˥�����������¸
	session_start();
	$_SESSION['sma_login_id'] = $fetch->us_user_id;
	$_SESSION['sma_mail_addr'] = $fetch->us_mail_addr;
	$_SESSION['sma_system'] = $_POST['system'];

	redirect('../sendmail/list.php');
} else {
	redirect('login_error.php');
}
?>
