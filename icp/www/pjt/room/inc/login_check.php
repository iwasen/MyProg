<?
/******************************************************
' System :ICP��ļ��ڡ���
' Content:������Ѥߥ����å�
'******************************************************/

session_start();

$g_member_id = $_SESSION[SCD]['member_id'];

if ($g_member_id == '') {
	echo "<script type='text/javascript'>\n";
	echo "top.location.href = '$top/../login/login.php'\n";
	echo "</script>\n";
	exit;
}
?>