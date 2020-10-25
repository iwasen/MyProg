<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
</head>
<body>
<input type="button" value="アンケート開始終了" onclick="location.href='enquete.php'">
<input type="button" value="全配信" onclick="location.href='send_all.php'">
<br>
<hr>
<h2>インポート</h2>
<a href="import_template.xls">テンプレートファイル</a><br>
実際はCSVファイルでアップロードしてください。
<form method="post" action="import.php" enctype="multipart/form-data">
<input type="file" name="import_file" size=70><br>
<input type="checkbox" name="force" value="1">エラーがあっても登録
<input type="checkbox" name="check" value="1">チェックのみ
<input type="submit" value="インポート">
</form>

</body>
<html>
