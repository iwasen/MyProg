<?
/******************************************************
' System :ICP�ץ������ȥڡ���
' Content:�����ս���
'******************************************************/

$top = "..";
$inc = "$top/../inc";
include("$inc/common.php");

$inc = "$top/inc";
include("$inc/pjt_common.php");

// �ץ������ȥڡ����ѥ��å�������
session_start();
unset($_SESSION[SCD]);

// ������ڡ�����
redirect('login.php');
?>