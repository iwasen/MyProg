<?
/******************************************************
' System :きかせて・net事務局用ページ
' Content:モニタープロファイル集計処理
'******************************************************/

$top = '../..';
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");
include("$inc/profile.php");

set_global('monitor', 'モニター管理', 'モニタープロファイル', BACK_TOP);

monitor_profile();

?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<title><?=$g_title?></title>
<link rel="stylesheet" type="text/css" href="<?=$top?>/css/main.css">
</head>
<body>
<? page_header() ?>

<div align="center">
<p class="msg">メンバープロファイルの集計が終了しました。</p>
<p><input type="button" value="　戻る　" onclick="location.href='show.php'"></p>
</div>

<? page_footer() ?>
</body>
</html>
