#!/usr/local/bin/php4
<?
/******************************************************
' System :flette�䤤��碌�ڡ���
' Content:�䤤��碌���Ͻ���
'******************************************************/

$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/inquiry.php");

// ���å�����ͽ��������¸
session_register('inquiry');

// �䤤��碌���󥯥饹������
$inquiry = new inquiry_class;

// ͽ�����������̤�����
redirect('info_form.php');
?>