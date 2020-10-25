#!/usr/local/bin/php4
<?
$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<title>flette</title>
<link rel="stylesheet" type="text/css" href="/css/staff.css">
</head>
<body>
確認画面を表示
<br>
<input type="button" value="予約申込み" onclick="location.href='thanks.php'">
<input type="button" value="　戻る　" onclick="location.href='reserve.php'">
</body>
</html>
