<?
/******************************************************
' System :�������ơ�net��˥����ͣ��ڡ���
' Content:�ͣ��ڡ���ǧ�ڳ�ǧ
'******************************************************/

session_start();
if (!isset($_SESSION['ss_monitor_id'])) {
		header("location: $top/mypage/login_error_main.php");
		exit;
}
?>
