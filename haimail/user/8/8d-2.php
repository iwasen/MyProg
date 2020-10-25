<?
include('../inc/common.php');
include('../inc/database.php');
include('../inc/member.php');

//=== 企業一覧表示 ===
function WriteKyohiKugyou() {
	global $member;

	$sql = "SELECT CM_company_id,CM_name,SC_kyohi_flag"
			. " FROM T_SEND_COMPANY,T_COMPANY"
			. " WHERE SC_company_id=CM_company_id AND SC_member_id='$member->member_id'";
	$ds = CreateDynaset($sql, ORADYN_READONLY);
	$n = 0;
	while (!$ds->EOF()) {
		$n++;
		Writeln("<tr height='18' bgcolor='#fff6ea'>");
		Writeln("<td width='18' align='center' valign='middle' bgcolor='#b2cfef' height='18'><font size='2'>$n</font></td>");
		Writeln("<td width='180' align='left' valign='middle' height='18'><font size='2'>" . $ds->GetValue('CM_name') . "</font></td>");
		Writeln("<td width='18' align='left' valign='middle' height='18'><input type='checkbox' value='" . $ds->GetValue('CM_company_id') . "' name='company_id[]'" . Checked($ds->GetValue('SC_kyohi_flag')) . "></td>");
		$ds->MoveNext();
		if (!$ds->EOF()) {
			$n++;
			Writeln("<td width='18' align='center' valign='middle' bgcolor='#b2cfef' height='18'><font size='2'>$n</font></td>");
			Writeln("<td width='180' align='left' valign='middle' height='18'><font size='2'>" . $ds->GetValue('CM_name') . "</font></td>");
			Writeln("<td height='18' align='left' valign='middle' height='18'><input type='checkbox' value='" . $ds->GetValue('CM_company_id') . "' name='company_id[]'" . Checked($ds->GetValue('SC_kyohi_flag')) . "></td>");
			$ds->MoveNext();
		}
		Writeln("</tr>");
	}

	return $n;
}

//=== メイン処理 ===
session_start();
if (!$_SESSION['login'])
	redirect('../index.php');

$member = new CMember;
?>
<html>

	<head>
		<meta http-equiv="content-type" content="text/html;charset=EUC-JP">
		<title>MY 受信拒否</title>
	</head>

	<body bgcolor="white" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
		<table cellspacing="0" cellpadding="0" border="0">
			<tr height="45">
				<td width="202" rowspan="2" bgcolor="#2f2180" align="left" valign="top">
					<div align="right">
						<img height="111" width="202" src="../img/title_left.gif" alt="はいめーる・net"></div>
				</td>
				<td height="45" valign="bottom" bgcolor="#2f2180" align="left" width="536">
					<table border="0" cellspacing="0" bgcolor="#2f2180" height="43">
						<form name="login" action="index.php" method="post">
						<tr height="27">
							<td width="58" align="left" valign="middle" height="27"><img src="../img/mypage.gif" width="58" height="22"></td>
							<td width="67" height="27" align="right" valign="middle"><img src="../img/mailaddress.gif" width="65" height="12"></td>
							<td width="86" height="27"><input type="text" name="mail_adr" size="23"></td>
							<td height="27" width="55" align="right" valign="middle"><img src="../img/password.gif" width="49" height="12"> </td>
							<td height="27" width="61" align="left" valign="middle"><input type="password" name="password" size="10"></td>
							<td width="62" height="27" align="center" valign="middle"><input type="submit" value="ログイン" name="login"></td>
						</tr>
						<tr>
							<td width="58"></td>
							<td width="67"></td>
							<td colspan="3" align="right" valign="middle"><a href="../9/9-1.php"><img src="../img/password_right.gif" width="185" height="14" border=0></a></td>
							<td width="62"></td>
						</tr>
						</form>
					</table></td>
			</tr>
			<tr>
				<td bgcolor="#5d85b8" align="left" valign="bottom" width="536"><img src="../img/head.jpg" width="535" height="66" alt="interactive communication network"></td>
			</tr>
			<tr height="18">
				<td colspan="2" bgcolor="#3f659e" height="18" align="left" valign="middle"><img height="16" width="14" src="../img/space.gif"><a href="../index.php"><img src="../img/home.gif" width="53" height="16" alt="HOME" border="0"></a><a href="../2/index.html"><img src="../img/himail_net.gif" width="130" height="16" border="0" alt="はいめーる・netって？"></a><a href="../3/index.html"><img src="../img/promise.gif" width="44" height="16" border="0" alt="お約束"></a><a href="../4/index.html"><img src="../img/policy.gif" width="119" height="16" border="0" alt="プライバシーポリシー"></a><a href="../5/index.html"><img src="../img/faq.gif" width="42" height="16" border="0" alt="FAQ"></a><a href="../6/index.php"><img src="../img/use_business.gif" width="108" height="16" border="0" alt="ビジネスで利用する"></a><a href="../7/index.html"><img src="../img/use_personal.gif" width="88" height="16" border="0" alt="個人で利用する"></a></td>
			</tr>
		</table><br>
		<br>
		<table width="595" border="0" cellspacing="0" mm_noconvert="TRUE" cellpadding="0">
			<form name="FormName" action="8d-3.php" method="post">
			<tr height="36">
				<td width="43" height="36" align="right" valign="top"><img height="16" width="43" src="../img/space.gif"></td>
				<td height="36" align="left" valign="top">
					<div align="left">
						<b><img height="26" width="435" src="img/kyohi_title.gif" alt="受信拒否企業設定"></b></div>
				</td>
			</tr>
			<tr height="36">
				<td width="43" height="36" align="right" valign="top">　</td>
				<td height="36"><table border="0" cellspacing="0">
						<tr>
							<td width="551"><font size="2"><br>
								あなたにメールをお届けした企業のリストから、<br>
								今後メールを受信したくない企業がある場合、受信拒否設定ができます。<br>
								以下のリストから、受信を拒否したい企業にチェックし「設定する」ボタンを押しください。<br>
								受信拒否企業の解除は、設定の解除をしたい企業のチェックを<br>
								はずしてから「設定する」ボタンを押してください。<br>
								<br>
								</font><table border="0" cellpadding="0" cellspacing="2" width="432">
<?
$kigyou_num = WriteKyohiKugyou();
?>
								</table></td>
						</tr>
					</table><br>
				</td>
			</tr>
			<tr height="36">
				<td width="43" height="36">　</td>
<?
if ($kigyou_num > 0) {
?>
				<td height="36"><input type="submit" value="　設定する　" name="submitButtonName"><font size="2">
<?
} else {
?>
				<td height="36"><font size="2">　</font>
<?
}
?>
					<br>
					<br>
					<br>
					<br>
					<hr>
					</font><a href="index.php"><img height="17" width="80" src="img/mypage.gif" alt="my pageに戻る" border="0"></a><br>
					<br>
				</td>
			</tr>
			</form>
		</table>
	</body>

</html>
