<?
/******************************************************
' System :Eco-footprint 会員ページ
' Content:ID.Password確認画面
'******************************************************/

$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
$inc = "$top/inc";
include("$inc/regist2.php");

// セッション処理
session_start();
if (!isset($_SESSION['ss_regist2']))
	redirect('member_input_exp.php');
$reg = &$_SESSION['ss_regist2'];

$reg->read_db_b($_SESSION['ss_seq_no']);

?>
<html>
<head>
<meta http-equiv="content-type" content="text/html;charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<title>☆★☆xxxゾウさんClub☆★☆</title>
</head>

<body bgcolor="#FF0000" leftmargin="8" topmargin="8">
<div align="center">
<table width="714" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF" height="90%">
<tr>
<td width="35" align="left" valign="top" bgcolor="#FFFFFF">
<img src="img/kado_1.gif" width="35" height="35"></td>
<td align="center" valign="bottom">
<table border="0" cellspacing="0" cellpadding="0">
<tr>
<td align="center"><img src="img/nyuukai/title/m_kakunin.gif" width="127" height="38" alt="確認してね"></td>
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
<br><br>
<br>
<br><br>
<br>
<table width=550>
<tr>
<td align="center" valign="top">
<table border="0" width="300" cellspacing="20">
<td colspan="2" height="2" bgcolor="#00CCFF"><img src="img/spacer.gif" width="300" height="2"></td>
<tr>
<td width="95"><font size=2>ＩＤ：</font></td>
<td width="188"><font size=2><?=$reg->mb_id?></font></td>
</tr>
<tr>
<td width="95"><font size=2>メールアドレス：</font></td>
<td width="188"><font size=2><?=$reg->mb_mail_addr?></font></td>
</tr>
<tr>
<td colspan="2" height="2" bgcolor="#00CCFF"><img src="img/spacer.gif" width="300" height="2"></td>
</tr>
</table>
<td>
<div align="center"><img src="img/nyuukai/zou_img/m_input01_01gif.gif" alt="確認してね" width="160" height="202"></div>
</td>
</tr>

</table>

<br><br>
<br>
<br>
<table border="0" cellspacing="0" cellpadding="0">
<tr>
<td align="center"><img src="img/nyuukai/page/01.gif"></td>
</tr>
</table>
</td>
<td><br></td>
</tr>
<tr>
<td width="35" align="left" valign="bottom">
<img src="img/kado_3.gif" width="35" height="35"></td>
<td align="center" valign="bottom">
<hr width="95%" size="1">
<table border="0" cellspacing="0" cellpadding="0">
<tr align="center">
<td><a href="member_input_exp.php"><img src="img/nyuukai/button/back.gif" width="99" height="40" border="0" alt="戻る"></a></td>
<td width="10"></td>
<td><a href="member_input02.php"><img src="img/nyuukai/button/next.gif" width="99" height="40" alt="次へ" border="0"></a></td>
</tr>
<tr>
<td height="7" colspan="5"><img src="img/spacer.gif" height="7"></td>
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