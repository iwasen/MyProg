<?
/******************************************************
' System :�֤�����Factory�ץ桼���ڡ���
' Content:���鳨��������ID���顼����
'******************************************************/

// Shift_JIS�ǽ���
mb_http_output('Shift_JIS');
ob_start('mb_output_handler');
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=Shift_JIS">
<title>������Factory</title>
</head>
<body>
<form method="post" name="form1" action="index.php">
<h2 align="center"><font color="#FFCC00">��</font><font color="#dd0000">������</font><font color="#FFCC00">��</font></h2>
<hr color="#5555dd">
<?=$msg?>
<hr color="#5555dd">
<center><input type="submit" value="����롡"></center>
</form>
</body>
</html>
