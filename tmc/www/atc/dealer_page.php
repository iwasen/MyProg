<?
/******************************************************
' System :�֤���������� �������������ӥ��ץ����ڡ����̳���ѥڡ���
' Content:����Ź�ڡ���������
'******************************************************/

$top = ".";
$inc = "$top/../inc";
include("login_check.php");
include("$inc/common.php");
include("$inc/database.php");

// ���å���������Ź�����ɤ���¸
$_SESSION['s_dealer_cd'] = $_GET['dealer_cd'];

// ����Ź�ڡ���������
$url = get_system_info('url_dealer_page');
redirect($url . 'monthly.php');
?>