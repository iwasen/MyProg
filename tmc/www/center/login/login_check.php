<?
/******************************************************
' System :�֤���������� �������������ӥ��׻�̳���ѥڡ���
' Content:����������å�
'******************************************************/

$top = "..";
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");

// �᡼�륢�ɥ쥹�ȥѥ���ɤ�����å�
$sql = sprintf("SELECT stf_staff_id,stf_mail_addr,stf_privilege FROM t_staff WHERE stf_mail_addr=%s AND stf_password=%s AND stf_status=0",
		sql_char(strtolower($_POST['mail_addr'])),
		sql_char(md5($_POST['password'])));
$result = db_exec($sql);
if (pg_num_rows($result) > 0) {
	$fetch = pg_fetch_object($result, 0);

	// ���å����˥᡼�륢�ɥ쥹����¸�ʼ���ǥե���Ȥ�ɽ�������뤿���
	SetCookie('login_mail_addr', $fetch->stf_mail_addr, COOKIE_EXPIRE, '/');

	// ���å����˥�����������¸
	session_start();
	$_SESSION['s_staff_id'] = $fetch->stf_staff_id;
	$_SESSION['s_mail_addr'] = $fetch->stf_mail_addr;
	$_SESSION['s_privilege'] = $fetch->stf_privilege;
	redirect('../index.php');
} else {
	// �����󥨥顼
	redirect('login_error.php');
}
?>
