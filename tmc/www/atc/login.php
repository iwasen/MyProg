<?
/******************************************************
' System :�֤���������� �������������ӥ��ץ����ڡ����̳���ѥڡ���
' Content:����������å�
'******************************************************/

$top = ".";
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");

// ID�ȥѥ���ɤ�����å�
$sql = sprintf("SELECT clg_campaign_id,clg_privilege FROM t_campaign_login WHERE clg_campaign_id=%s AND clg_password=%s AND clg_status=0",
		sql_char($_POST['login_id']),
		sql_char(md5($_POST['password'])));
$result = db_exec($sql);
if (pg_num_rows($result) > 0) {
	$fetch = pg_fetch_object($result, 0);

	// ���å����˥᡼�륢�ɥ쥹����¸�ʼ���ǥե���Ȥ�ɽ�������뤿���
	SetCookie('login_campaign_id', $fetch->clg_campaign_id, COOKIE_EXPIRE, '/');

	// ���å����˥�����������¸
	session_start();
	$_SESSION['s_campaign_id'] = $fetch->clg_campaign_id;
	$_SESSION['s_privilege'] = $fetch->clg_privilege;
	redirect('top.php');
} else {
	// �����󥨥顼
	redirect('index.php?fail=1');
}
?>
