<?
$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");

$fetch = get_system_info('sy_url_top');
$url = "{$fetch->sy_url_top}sol/receive.php";
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<title>Sol受信テスト</title>
</head>
<body>
<form method="post" name="form1" action="<?=$url?>">
<b>command=1</b><br>
<input type="hidden" name="command" value="1">
<table border=1>
	<tr>
		<td>id</td>
		<td><input type="text" name="id" value="aizawa"></td>
	</tr>
	<tr>
		<td>password</td>
		<td><input type="text" name="password" value="qqqqqq"></td>
	</tr>
	<tr>
		<td>version</td>
		<td><input type="text" name="version" value="1"></td>
	</tr>
	<tr>
		<td>last_date</td>
		<td><input type="text" name="last_date" value="2002-12-31_23-59"></td>
	</tr>
	<tr>
		<td>this_date</td>
		<td><input type="text" name="this_date" value="2003-01-31_23-59"></td>
	</tr>
	<tr>
		<td>ele</td>
		<td><input type="text" name="ele" value="100"></td>
	</tr>
	<tr>
		<td>sun</td>
		<td><input type="text" name="sun" value="50"></td>
	</tr>
	<tr>
		<td>gas</td>
		<td><input type="text" name="gas" value="20"></td>
	</tr>
	<tr>
		<td>wtr</td>
		<td><input type="text" name="wtr" value="30"></td>
	</tr>
</table>
<input type="reset" value="ﾘｾｯﾄ">
<input type="submit" value="送信">
</form>

<form method="post" name="form1" action="<?=$url?>">
<b>command=2</b><br>
<input type="hidden" name="command" value="2">
<table border=1>
	<tr>
		<td>id</td>
		<td><input type="text" name="id" value="aizawa"></td>
	</tr>
	<tr>
		<td>password</td>
		<td><input type="text" name="password" value="qqqqqq"></td>
	</tr>
	<tr>
		<td>version</td>
		<td><input type="text" name="version" value="1"></td>
	</tr>
</table>
<input type="reset" value="ﾘｾｯﾄ">
<input type="submit" value="送信">
</form>

</body>
</html>
