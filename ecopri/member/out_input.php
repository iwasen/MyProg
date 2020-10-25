<?
/******************************************************
' System :Eco-footprint 会員ページ
' Content:個人情報変更　退会確認
'******************************************************/

$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
$inc = "$top/inc";
include("$inc/mypage.php");

?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<title>☆★☆xxxゾウさんClub☆★☆</title>
</head>

<body bgcolor="#FF0000" text="#000000" link="#66ccff" vlink="#66ccff" leftmargin="8" topmargin="8" alink="#66ccff">
<div align="center">
<form name="form1" method="post" action="out_input_check.php">

<!-- 白丸角テーブル -->
<table border="0" cellpadding="0" cellspacing="0" width="714" height="90%" bgcolor="#FFFFFF">
	<tr>
		<td height="35" align="left" valign="top" bgcolor="#FFFFFF">
		<img src="img/kado_1.gif" width="35" height="35">
		</td>
		<td valign="bottom"><img src="img/spacer.gif" border="0" width="1" height="1"></td>
		<td height="35" align="right" valign="top" bgcolor="#FFFFFF"><img src="img/kado_2.gif" width="35" height="35"></td>
	</tr>
	<tr>
		<td align="left" width="151"><img src="img/taikai/zou_img/o_input01.gif"></td>
		<td align="center" valign="middle">

		<!-- ここに入力 -->
		<font size="5" color="#0066cc"><b>IDとパスワードを入力してください。</b></font><br>
		<br><br>
			<table border="0">
				<tr>
					<td align="right"><img src="img/taikai/id.gif" border="0" width="55" height="16"></td><td><input type="text" name="member_id"></td>
				</tr>
				<tr>
					<td align="right"><img src="img/taikai/pass.gif" border="0" width="55" height="16"></td><td><input type="password" name="password"></td>
				</tr>
			</table>
		<!-- ここまで -->
		</td>
		<td align="right" width="151"><img src="img/taikai/zou_img/o_input02.gif"></td>
	</tr>
	<tr>
		<td height="35" align="left" valign="bottom"><img src="img/kado_3.gif" width="35" height="35"></td>

		<td align="center"><a href="main.php"><img src="img/taikai/button/yameru.gif" alt="やめる" border="0" width=131 height=49></a>
		<img src="img/spacer.gif" width="10" height="49">
		<input type="image" src="img/taikai/button/ok.gif" alt="OK" width="96" height="49" border="0">
		</td>
		<td height="35" align="right" valign="bottom"><img src="img/kado_4.gif" width="35" height="35"></td>
	</tr>
</table>
<!-- 白丸角テーブル終了 -->
</form>
</div>
</body>
</html>