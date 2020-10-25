<?
include('../inc/common.php');
include('../inc/database.php');

$mailto = ToHankaku(trim($_POST['mail_adr']));
if ($mailto == '')
	redirect('../index.php');

if (!MailAdrCheck($mailto))
	redirect('slow_error.html');

//登録済みの場合はエラー
$sql = "SELECT MM_mail_adr FROM T_MEMBER WHERE MM_mail_adr=". SqlStr($mailto) . " AND MM_status<>'9'";
$ds = CreateDynaset($sql, ORADYN_READONLY);
if (!$ds->EOF())
	redirect('slow_error2.html');

//=== 登録登録フォーム送信 ===
GetSendMailInfo('SLOW', $subject, $mailfrom, $body);
$body = str_replace('%MAIL_ADDRESS%', $mailto, $body);
$result = SendMail($mailto, $mailfrom, $subject, $body);
if ($result != '')
	redirect('mail_error.html');

//ゆっくり登録申請ログに記録
$sql = "SELECT * FROM T_TOUROKU_REQ WHERE TR_mail_adr=". SqlStr($mailto);
$ds = CreateDynaset($sql, ORADYN_DEFAULT);
if ($ds->EOF()) {
	$sql = sprintf("INSERT INTO T_TOUROKU_REQ (TR_mail_adr,TR_count,TR_pr_id,TR_date) VALUES (%s,1,%s,SYSDATE)",
			SqlStr($mailto),
			SqlStr($_COOKIE['PRID']));
} else {
	$sql = sprintf("UPDATE T_TOUROKU_REQ SET TR_count=TR_count+1,TR_pr_id=%s,TR_date=SYSDATE WHERE TR_mail_adr=%s",
			SqlStr($_COOKIE['PRID']),
			SqlStr($mailto));
}
ExecuteSQL($sql);
?>
<html>

	<head>
		<meta http-equiv="content-type" content="text/html;charset=EUC-JP">
		<title>ゆっくり登録</title>
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
		<table width="604" border="0" cellspacing="0" mm_noconvert="TRUE" cellpadding="0">
			<tr height="36">
				<td width="50" height="36" align="right" valign="top"><img height="16" width="50" src="../img/space.gif"></td>
				<td width="561" height="36">
					<div align="left">
						<font color="#2f2180"><b>登録フォームをお送りいたしました。<br>
						はいめーる・netへのご登録をお待ちしています。</b></font></div>
				</td>
			</tr>
			<tr height="36">
				<td width="50" height="36">　</td>
				<td width="561" height="36"><font size="2"><br>
					<br>
					<br>
					<br>
					<br>
					<br>
					<br>
					</font>
					<hr>
					<a href="../index.php"><img height="17" width="80" src="../img/home_b.gif" border="0" alt="ホームへ戻る"></a></td>
			</tr>
		</table>　
	</body>

</html>
