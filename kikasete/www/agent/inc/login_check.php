<?
/******************************************************
' System :�������ơ�net��̳���ѥڡ���
' Content:����������å�����
'******************************************************/

session_cache_limiter('none');

session_start();

if ($agent_login_id == '') {
	header("location: $top/login/logoff.php");
	exit;
}
?>
