<?
/******************************************************
' System :�������ơ�net��̳���ѥڡ���
' Content:My�ѡ��ȥʡ����ꥯ�롼�ȥ���ֿ�����Ͽ
'******************************************************/

$top = '../..';
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");
$inc = "$top/../inc";
include("$inc/common.php");

redirect("r_job_edit.php?pjt_id=$pjt_id&new=1");
?>