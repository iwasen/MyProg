<?
include('../inc/common.php');
include('../inc/database.php');

session_start();
$mailto = $_SESSION['p_mail_adr'];
if ($mailto == '')
	redirect('../index.php');

$sql = "SELECT MM_password FROM T_MEMBER WHERE MM_mail_adr=" . SqlStr($mailto) . " AND MM_status<>'9'";
$ds = CreateDynaset($sql, ORADYN_READONLY);

GetSendMailInfo('PASSWORD', $subject, $mailfrom, $body);
$body = str_replace('%PASSWORD%', $ds->GetValue('MM_password'), $body);
SendMail($mailto, $mailfrom, $subject, $body);

session_destroy();
?>
<html>

	<head>
		<meta http-equiv="content-type" content="text/html;charset=EUC-JP">
		<title>パスワード忘れ送信</title>
	</head>

	<body bgcolor="white" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
		<table cellspacing="0" cellpadding="0" border="0" height="58">
			<tr height="48">
				<td bgcolor="white" align="left" valign="top" height="48"><img height="48" width="150" src="../img/logo1.gif" alt="はいめーる・net"><img height="48" width="482" src="../img/logo2.gif" alt="interactive communication network"></td>
			</tr>
			<tr height="16">
				<td height="16" align="left" valign="top"><img height="3" width="642" src="../img/logo_line.gif"></td>
			</tr>
		</table><br>
		<table border="0" cellspacing="0" mm_noconvert="TRUE" cellpadding="0">
			<tr height="36">
				<td width="50" height="36"><img height="16" width="50" src="../img/space.gif"><br>
					<br>
				</td>
				<td width="550" height="36"><font color="#2f2180"><b>パスワードを配信したメールを送信しました。</b></font><br>
					<br>
					<br>
					<br>
					<br>
					<br>
					<br>
					<br>
					<hr>
					<br>
					<a href="../index.php"><img src="../img/home_b.gif" width="80" height="17" border="0" alt="ホームに戻る"></a> </td>
			</tr>
		</table>
	</body>

</html>
