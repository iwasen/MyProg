#!/usr/local/bin/php4
<?
/******************************************************
' System :fletteスタッフページ
' Content:ログインエラー画面処理
'******************************************************/

$top = '..';
$inc = "$top/../inc";
include("$inc/common.php");

$inc = "$top/inc";
include("$inc/header.php");

?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<title><?=$g_title?></title>
<link rel="stylesheet" type="text/css" href="/css/staff.css">
</head>
<body onLoad="document.all.ok.focus()">

<? page_header('スタッフログインエラー', false, false) ?>

<div align="center">
<p class="msg">ログインに失敗しました。<br><br>戻るボタンをクリックしてもう一度メールアドレスとパスワードを入力してください。</p>
<p><input type="button" id="ok" value="　戻る　" onclick="history.back()"></p>
</div>

<? page_footer() ?>

</body>
</html>
