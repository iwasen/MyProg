<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
</head>
<body>
<input type="button" value="���󥱡��ȳ��Ͻ�λ" onclick="location.href='enquete.php'">
<input type="button" value="���ۿ�" onclick="location.href='send_all.php'">
<br>
<hr>
<h2>����ݡ���</h2>
<a href="import_template.xls">�ƥ�ץ졼�ȥե�����</a><br>
�ºݤ�CSV�ե�����ǥ��åץ��ɤ��Ƥ���������
<form method="post" action="import.php" enctype="multipart/form-data">
<input type="file" name="import_file" size=70><br>
<input type="checkbox" name="force" value="1">���顼�����äƤ���Ͽ
<input type="checkbox" name="check" value="1">�����å��Τ�
<input type="submit" value="����ݡ���">
</form>

</body>
<html>
