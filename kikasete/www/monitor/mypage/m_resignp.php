<?
$top = './..';
$inc = "$top/../inc";
include("$inc/point.php");
include("$inc/resign.php");
$inc = "$top/inc";
$img = "$top/image";
include("$inc/mn_mypage.php");
include("$inc/mn_header2.php");

// �މ��
resign_monitor($_SESSION['ss_monitor_id']);

// �Z�b�V�����폜
session_destroy();
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
</head>
<body onload="top.location.href='../index.php'">
</body>
</html>
