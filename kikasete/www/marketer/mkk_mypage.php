<?
/******************************************************
' System :�������ơ�net�ޡ��������ͣ��ڡ���
' Content:ǧ�ڳ�ǧ����
'******************************************************/

session_start();
if (!isset($_SESSION['ss_marketer_id']) || ($_SESSION['ss_marketer_type'] != 3 && $_SESSION['ss_marketer_type'] != 4)) {
		header("location: mk_login.php");
		exit;
}
?>