<?
/******************************************************
' System :「きゃららFactory」ユーザページ
' Content:似顔絵申し込みIDエラー画面
'******************************************************/

// Shift_JISで出力
mb_http_output('Shift_JIS');
ob_start('mb_output_handler');
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=Shift_JIS">
<title>きゃららFactory</title>
</head>
<body>
<form method="post" name="form1" action="index.php">
<h2 align="center"><font color="#FFCC00">★</font><font color="#dd0000">きゃらら</font><font color="#FFCC00">★</font></h2>
<hr color="#5555dd">
<?=$msg?>
<hr color="#5555dd">
<center><input type="submit" value="　戻る　"></center>
</form>
</body>
</html>
