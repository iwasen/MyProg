<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<title>Batch Test</title>
</head>
<body>

<h2>バッチテスト</h2>

<input type="button" value="販売店メール送信" onclick="location.href='dealer_mail.php'">
<input type="button" value="エラーメール受信" onclick="location.href='err_mail.php'">
<input type="button" value="メール受信" onclick="location.href='recv_mail.php'">
<input type="button" value="初期フォローメール送信" onclick="location.href='follow_mail.php'">
<input type="button" value="継続リレーションメール送信" onclick="location.href='relation_mail.php'">
<hr>
<h2>インポートテスト</h2>
<form method="post" action="import.php" enctype="multipart/form-data">
<input type="file" name="import_file" size=70><br>
<input type="checkbox" name="force" value="1">エラーがあっても登録
<input type="checkbox" name="check" value="1">チェックのみ
<input type="submit" value="インポート">
</form>

</body>
</html>
