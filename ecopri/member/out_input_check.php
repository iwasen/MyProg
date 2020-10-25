<?
/******************************************************
' System :Eco-footprint 会員ページ
' Content:会員情報変更 退会　ログインチェック
'******************************************************/

$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
$inc = "$top/inc";
include("$inc/mypage.php");
include("$inc/login_log.php");

if (isset($member_id)) {
	if ($member_id == '')
		$msg[] = 'ＩＤが入力されていないようです。';

	if ($password == '')
		$msg[] = 'パスワードが入力されていないようです。';
} else
	$msg[] = 'ログインしていないか、またはログインがタイムアウトしました。もう一度ログインしてください。';

if (!$msg) {
	$sql = "select mb_seq_no,mb_id,mb_password,mb_step"
				. " from t_member where mb_id=" . sql_char($member_id) . " and mb_step<>9";
	$result = db_exec($sql);

	if (pg_numrows($result) == 0)
		$msg[] = 'IDが間違っている恐れがあります。もう一度お確かめの上、入力してください。';
	else {
		$fetch = pg_fetch_object($result, 0);
		if ($fetch->mb_seq_no != $_SESSION['ss_seq_no'])
			$msg[] = 'ＩＤが間違っている恐れがあります。もう一度お確かめの上、入力してください。';
		elseif ($fetch->mb_password != $password)
			$msg[] = 'パスワードが間違っている恐れがあります。もう一度お確かめの上、入力してください。';
		else {
//			member_login_log($member_id, $password, $fetch->mb_step, $fetch->mb_seq_no);
			redirect($url != '' ? $url : 'out_quest.php');
		}
	}
}

//member_login_log($member_id, $password, null, null);
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
		<td width="35" align="left" valign="top" bgcolor="#FFFFFF"> <img src="img/kado_1.gif" width="35" height="35"></td>
		<td align="center" valign="top">
			<table border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td><img src="img/nyuukai/title/m_check.gif"></td>
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
			<br><br><br><br>
			<img src="img/nyuukai/zou_img/m_error.gif" width="162" height="124" alt="あれ？">
			<br>
			<table border="0" width="350" cellspacing="10">
				<td colspan="2" height="2" bgcolor="#006600"><img src="img/spacer.gif" width="300" height="2"></td>
				<tr>
					<td><font size="2"><br><?=disp_msg($msg, '・', '<br><br>')?><br></font></td>
				</tr>
				<tr>
					<td colspan="2" height="2" bgcolor="#006600"><img src="img/spacer.gif" width="300" height="2"></td>
				</tr>
			</table>
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
					<td><a href="out_input.php"><img width="99" height="40" border="0" alt="やり直す" src="img/nyuukai/button/naosu.gif"></a></td>
				</tr>
				<tr>
					<td height="7" colspan="3"><img src="img/spacer.gif" width="208" height="7"></td>
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
