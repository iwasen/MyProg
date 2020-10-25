<?
/******************************************************
' System :Eco-footprint 会員ページ
' Content:個人情報変更 ログイン
'******************************************************/

$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
$inc = "$top/inc";
include("$inc/mypage.php");
include("$inc/regist2.php");

// セッション処理
$_SESSION['ss_regist2'] = new mm_regist2;
$reg = &$_SESSION['ss_regist2'];

$reg->read_db1($_SESSION['ss_seq_no']);

?>
<html>
<head>
<meta http-equiv="content-type" content="text/html;charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<title>☆★☆xxxゾウさんClub☆★☆</title>
</head>

<body bgcolor="#FF0000" leftmargin="8" topmargin="8">
<div align="center">
<form method="post" action="member_change01_check.php">
<table width="714" height="90%" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
	<tr>
		<td width="35" align="left" valign="top" bgcolor="#FFFFFF"> <img src="img/kado_1.gif" width="35" height="35"></td>
		<td align="center" valign="top"><br>
			<table border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td align="center"><img src="img/taikai/title/m_change01.gif" alt="入力されていた情報が変更されます" width="381" height="37"></td>
				</tr>
			</table>
			<hr width="95%" size="1">
		</td>
		<td width="35" align="right" valign="top">
		<img src="img/kado_2.gif" width="35" height="35"></td>
	</tr>

	<tr>
		<td><br></td>
		<td align="center" valign="top">
		<br>
		<br>
		<br>
			<table border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td><font color="#00CCFF">★★★</font></td>
					<td><img src="img/taikai/title/m_change02.gif" width="256" height="24"></td>
					<td><font color="#00CCFF">★★★</font></td>
				</tr>
			</table>
			<br>
			<table width=550>
				<tr>
					<td align="center" valign="top" width="327">
						<table border="0" width="300" cellspacing="20">
							<td colspan="2" height="2" bgcolor="#FFCC66"><img src="img/spacer.gif" width="300" height="2"></td>
							<tr>
								<td align="right" width="95"><font size=2>ID：</font></td>
								<td width="188"><input type="text" name="member_id"></td>
							</tr>
							<tr>
								<td align="right" width="95"><font size=2>パスワード：</font></td>
								<td width="188"><input type="password" name="password"></td>
							</tr>
							<tr>
								<td colspan="2" height="2" bgcolor="#FFCC66"><img src="img/spacer.gif" width="300" height="2"></td>
							</tr>
						</table>
						<td width="211">
						<div align="center"><img src="img/taikai/zou_img/m_change01.gif" alt="確認してね" width="210" height="109"></div>
					</td>
				</tr>
			</table>
			<br>
		<br>

		</td>
		<td><br></td>
	</tr>
	<tr>
		<td width="35" align="left" valign="bottom"> <img src="img/kado_3.gif" width="35" height="35"></td>
		<td align="center" valign="bottom">
		<hr width="95%" size="1">
			<table border="0" cellspacing="0" cellpadding="0">
				<tr align="center">
					<!-- ↓ダイアログボックスを表示させる -->
					<input type="hidden" name="change" value="on">
					<td><a href="member_menu.php"><img src="img/nyuukai/button/back.gif" border="0" alt="戻る" width="99" height="40"></a></td>
					<td width="10"></td>
					<td><input type="image" src="img/nyuukai/button/next.gif" border="0" alt="次へ" width="99" height="40"></td>
				</tr>
				<tr align="center">
					<td height="7" colspan="5"><img src="img/spacer.gif" height="7"></td>
				</tr>
			</table>
		</td>
		<td width="35" height="35" align="right" valign="bottom">
		<img src="img/kado_4.gif" width="35" height="35"></td>
	</tr>
</table>
</form>
</div>
</body>
</html>