<?
session_start();
$g_user_id = $_SESSION['user_id'];
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<title>ユーザＭｙページ</title>
</head>
<body>
<h3>ユーザＭｙページ</h3>
<hr>
ユーザID=<?=$g_user_id?>
</body>
</html>
