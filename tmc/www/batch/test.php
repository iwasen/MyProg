<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<title>Batch Test</title>
</head>
<body>

<h2>�Хå��ƥ���</h2>

<input type="button" value="����Ź�᡼������" onclick="location.href='dealer_mail.php'">
<input type="button" value="���顼�᡼�����" onclick="location.href='err_mail.php'">
<input type="button" value="�᡼�����" onclick="location.href='recv_mail.php'">
<input type="button" value="����ե����᡼������" onclick="location.href='follow_mail.php'">
<input type="button" value="��³��졼�����᡼������" onclick="location.href='relation_mail.php'">
<hr>
<h2>����ݡ��ȥƥ���</h2>
<form method="post" action="import.php" enctype="multipart/form-data">
<input type="file" name="import_file" size=70><br>
<input type="checkbox" name="force" value="1">���顼�����äƤ���Ͽ
<input type="checkbox" name="check" value="1">�����å��Τ�
<input type="submit" value="����ݡ���">
</form>

</body>
</html>
