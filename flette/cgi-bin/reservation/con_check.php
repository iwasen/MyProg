#!/usr/local/bin/php4
<?
/******************************************************
' System :fletteͽ�����ϥڡ���
' Content:�����������ϥ����å�����
'******************************************************/

$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/reserve.php");

// ���å���󤫤�ͽ��������
session_start();
if ($reserve == null)
	redirect('timeout.php');

// ���顼�ꥻ�å�
$reserve->error = null;

// �����󥻥顼�����̼���
for ($i = 0; $i < count($sex); $i++)
	$reserve->choice[$i]->sex = $sex[$i];

// ��˾��̤������ؤ�
for ($i = 0; $i < count($choice); $i++)
	$temp[$choice[$i]] = $reserve->choice[$i];
for ($i = 0; $i < count($choice); $i++)
	$reserve->choice[$i] = $temp[$i];

// �ե����फ��ǡ�������
$reserve->counseling_id = $counseling_id ? $counseling_id : Array();
$reserve->change = $change;
$reserve->comment = $comment;

// ���ڡ�����
redirect('pro_form.php');
?>