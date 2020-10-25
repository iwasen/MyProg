<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<title>MD5</title>
</head>
<body>
<form method="post">
テキスト：<input type="text" name="text" size=20 value="<?=$_POST['text']?>">
<?
if ($_POST['text'] != '') {
?>
→MD5：<input type="text" size=45 value="<?=md5($_POST['text'])?>" readonly>
<?
}
?>
<br>
<input type="submit" value="変換">
</form>
</body>
</html>
