#!/usr/local/bin/php4
<?
/******************************************************
' System :fletteͽ�����ϥڡ���
' Content:ͽ����������ե졼��
'******************************************************/

$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/reserve.php");

// ���å���󤫤�ͽ��������
session_start();
if ($reserve == null)
	redirect('timeout.php');
?>
<HTML lang="ja">
<HEAD>
<TITLE>FLETTE / ��ͽ��</TITLE>
<meta http-equiv="content-type" content="text/html; charset=EUC-JP">
<meta http-equiv="Content-Style-Type" content="text/css">
<link rel="stylesheet" href="../../css/style.css" type="text/css" media="screen">
</head>

<FRAMESET rows="340,*" FRAMEBORDER=0 framespacing=0 border=0>
<FRAME src="sche_head.php" name="head" noresize scrolling="no">
<FRAME src="sche_body.php" name="body" scrolling="yes">
</FRAMESET>

</HTML>
