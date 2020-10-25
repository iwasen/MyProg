<?
/******************************************************
' System :お酒と買物のアンケートモニター事務局用ページ
' Content:ログインエラー画面処理
'******************************************************/

$top = '..';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
$inc = "$top/inc";
include("$inc/header.php");

set_global('login', '', 'お酒と買物のアンケートモニター事務局ログイン', BACK_NONE);
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<title><?=$g_title?></title>
<link rel="stylesheet" type="text/css" href="<?=$top?>/css/main.css">
</head>
<body onLoad="document.all.ok.focus()">

<? page_header() ?>

<div align="center">
<p class="msg">ログインに失敗しました。<br><br>戻るボタンをクリックしてもう一度ＩＤとパスワードを入力してください。</p>
<p><input type="button" id="ok" value="　戻る　" onclick="history.back()"></p>
</div>

<? page_footer() ?>

</body>
</html>
