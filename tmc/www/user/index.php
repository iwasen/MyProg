<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<title>ユーザログイン</title>
</head>
<body>
<h3>ユーザログインページ</h3>
<hr>
<?
if ($_GET['err']) {
?>
<font color="red">ログインできませんでした。</font>
<br>
<?
}
?>
<form method="post" name="form1" action="login.php">
ログインID <input type="text" name="login_id" value="<?=htmlspecialchars($_GET['login_id'])?>">
<br>
パスワード <input type="password" name="password" value="">
<br><br>
<input type="submit" value="ログイン">
<input type="hidden" name="page" value="<?=htmlspecialchars($_GET['page'])?>">
</form>
</body>
</html>
