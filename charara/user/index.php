<?
/******************************************************
' System :「きゃららFactory」ユーザページ
' Content:似顔絵申し込みID入力
'******************************************************/

// ロゴ画像
if (strpos($_SERVER['HTTP_USER_AGENT'], 'DoCoMo') !== false)
	$rogo = 'charara_rogo.gif';
else
	$rogo = 'charara_rogo.png';

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
<form method="post" name="form1" action="input.php">
<h2 align="center"><img src="img/<?=$rogo?>" width=95 height=55></h2>
<hr color="#5555dd">
<font color="red">●</font>にがおえ申し込みIDを入力して送信ボタンを押してください。<br>
<center>ID:<input type="text" name="id" size="9" istyle="3"></center>
<hr color="#5555dd">
<center><input type="submit" value="　送信　"></center>
</form>
</body>
</html>
