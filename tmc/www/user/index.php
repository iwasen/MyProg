<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<title>�桼��������</title>
</head>
<body>
<h3>�桼��������ڡ���</h3>
<hr>
<?
if ($_GET['err']) {
?>
<font color="red">������Ǥ��ޤ���Ǥ�����</font>
<br>
<?
}
?>
<form method="post" name="form1" action="login.php">
������ID <input type="text" name="login_id" value="<?=htmlspecialchars($_GET['login_id'])?>">
<br>
�ѥ���� <input type="password" name="password" value="">
<br><br>
<input type="submit" value="������">
<input type="hidden" name="page" value="<?=htmlspecialchars($_GET['page'])?>">
</form>
</body>
</html>
