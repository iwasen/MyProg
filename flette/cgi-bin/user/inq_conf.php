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
お問合せの確認画面を表示
<br>
<form method="post" name="form1" action="inq_thanks.php">
<input type="submit" value="　送信　">
<input type="button" value="　戻る　" onclick="history.back()">
<input type="hidden" name="name" <?=value($name)?>>
<input type="hidden" name="mail_addr" <?=value($mail_addr)?>>
<input type="hidden" name="inq_subject" <?=value($inq_subject)?>>
<input type="hidden" name="inq_content" <?=value($inq_content)?>>
</form>
</body>
</html>
