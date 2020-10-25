<?
/******************************************************
' System :Eco-footprint 会員ページ
' Content:詳細情報登録完了
'******************************************************/

$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/select.php");
include("$inc/decode.php");
$inc = "$top/inc";
include("$inc/regist2.php");

// セッション処理
session_start();
if (!isset($_SESSION['ss_regist2']))
	redirect('member_menu.php');
$reg = &$_SESSION['ss_regist2'];

$reg->save_db($_SESSION['ss_seq_no'],3);

// セッション変数削除
unset($_SESSION['ss_regist2']);
session_unregister('ss_regist2');

?>
<html>
<head>
<meta http-equiv="content-type" content="text/html;charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<title>☆★☆xxxゾウさんClub☆★☆</title>
</head>

<body bgcolor="#FF0000" leftmargin="8" topmargin="8">
<div align="center">
<table width="714" height ="90%" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
	<tr>
		<td width="35" height="35" align="left" valign="top" bgcolor="#FFFFFF">
		<img src="img/kado_1.gif" width="35" height="35"></td>
		<td><img src="img/spacer.gif" width="690" height="35"></td>

		<td width="35" align="right" valign="top">
		<img src="img/kado_2.gif" width="35" height="35"></td>
	</tr>

	<tr>
		<td><br></td>
		<td align="center" valign="middle"> <br>
			<table border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td valign="middle"><font color="#FF0000">変更は終了いたしました。</font></td>
					<td><img src="img/spacer.gif" width="10" height="217"></td>
				</tr>
			</table>
			<br>
		</td>
		<td><br></td>
	</tr>
	<tr>
		<td width="35" height="35" align="left" valign="bottom">
		<img src="img/kado_3.gif" width="35" height="35"></td>
		<td align="center" valign="bottom"><hr width="95%" size="1">
			<table border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td align="center"><a href="member_menu.php"><img src="img/nyuukai/button/kanryou.gif" border="0" alt="完了" width="99" height="40"></a></td>
				</tr>
				<tr>
					<td height="7"><img src="img/spacer.gif" width="96" height="7"></td>
				</tr>
			</table>
		</td>
		<td width="35" height="35" align="right" valign="bottom">
	<img src="img/kado_4.gif" width="35" height="35"></td>
	</tr>
</table>

</div>
</body>
</html>