<?
/******************************************************
' System :ICP���ڥ졼���ڡ���
' Content:�����ս���
'******************************************************/

$top = "..";
$inc = "$top/../inc";
include("$inc/common.php");

$inc = "$top/inc";
include("$inc/ope_common.php");

// ���ڥ졼���ڡ����ѥ��å�������
session_start();
unset($_SESSION[SCD]);

// ������ڡ�����
redirect('login.php');
?>