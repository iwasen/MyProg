<?
/******************************************************
' System :�֤���������� �������������ӥ�������Ź���ѥڡ���
' Content:����������å�
'******************************************************/

$top = ".";
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");

// ID�ȥѥ���ɤ�����å�
$sql = sprintf("SELECT dlg_dealer_cd FROM t_dealer_login WHERE dlg_dealer_cd=%s AND dlg_password=%s AND dlg_status=0",
		sql_char($_POST['login_id']),
		sql_char(md5($_POST['password'])));
$result = db_exec($sql);
if (pg_num_rows($result) > 0) {
	$fetch = pg_fetch_object($result, 0);

	// ���å����˥᡼�륢�ɥ쥹����¸�ʼ���ǥե���Ȥ�ɽ�������뤿���
	SetCookie('login_dealer_cd', $fetch->dlg_dealer_cd, COOKIE_EXPIRE, '/');

	// ���å����˥�����������¸
	session_start();
	$_SESSION['s_dealer_cd'] = $fetch->dlg_dealer_cd;
	redirect('top.php');
} else {
	// �����󥨥顼
	redirect('index.php?fail=1');
}
?>
