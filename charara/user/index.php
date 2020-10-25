<?
/******************************************************
' System :｡ﾖ､ｭ､网鬢颶actory｡ﾗ･譯ｼ･ｶ･ﾚ｡ｼ･ｸ
' Content:ｻ魑ｨｿｽ､ｷｹ､ﾟIDﾆﾎﾏ
'******************************************************/

// ･愠ｴｲ霖�
if (strpos($_SERVER['HTTP_USER_AGENT'], 'DoCoMo') !== false)
	$rogo = 'charara_rogo.gif';
else
	$rogo = 'charara_rogo.png';

// Shift_JIS､ﾇｽﾐﾎﾏ
mb_http_output('Shift_JIS');
ob_start('mb_output_handler');
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=Shift_JIS">
<title>､ｭ､网鬢颶actory</title>
</head>
<body>
<form method="post" name="form1" action="input.php">
<h2 align="center"><img src="img/<?=$rogo?>" width=95 height=55></h2>
<hr color="#5555dd">
<font color="red">｡�</font>､ﾋ､ｬ､ｪ､ｨｿｽ､ｷｹ､ﾟID､ﾎﾏ､ｷ､ﾆﾁｮ･ﾜ･ｿ･｡､ｷ､ﾆ､ｯ､ﾀ､ｵ､､｡｣<br>
<center>ID:<input type="text" name="id" size="9" istyle="3"></center>
<hr color="#5555dd">
<center><input type="submit" value="｡｡ﾁｮ｡｡"></center>
</form>
</body>
</html>
