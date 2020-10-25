<?
include('../inc/common.php');
include('../inc/database.php');
include('../inc/member.php');

//=== 受信拒否企業をDBの保存
function SetKyohiKigyou() {
	global $member;

	BeginTrans();

	//一旦拒否フラグをクリア
	$sql = "UPDATE T_SEND_COMPANY SET SC_kyohi_flag='0' WHERE SC_member_id='$member->member_id'";
	ExecuteSQL($sql);

	//チェックされた企業に受信拒否フラグをセット
	$kyohi_kigyou = $_POST['company_id'];
	if (is_array($kyohi_kigyou)) {
		$sql = "UPDATE T_SEND_COMPANY SET SC_kyohi_flag='1' WHERE SC_member_id='$member->member_id' AND SC_company_id IN (";
		$first = true;
		foreach ($kyohi_kigyou as $company_id) {
			if ($first)
				$first = false;
			else
				$sql .= ',';
			$sql .= "'$company_id'";
		}
		$sql .= ')';
		ExecuteSQL($sql);
	}

	CommitTrans();
}

//=== 企業一覧表示 ===
function WriteKyohiKugyou() {
	global $member;

	$sql = "SELECT CM_name,DECODE(SC_kyohi_flag, '1', '拒否', '--') AS kyohi"
			. " FROM T_SEND_COMPANY,T_COMPANY"
			. " WHERE SC_company_id=CM_company_id AND SC_member_id='$member->member_id'";
	$ds = CreateDynaset($sql, ORADYN_READONLY);
	$n = 0;
	while (!$ds->EOF()) {
		$n++;
		Writeln("<tr height='18' bgcolor='#fff6ea'>");
		Writeln("<td width='18' align='center' valign='middle' bgcolor='#b2cfef' height='18'><font size='2'>$n</font></td>");
		Writeln("<td width='180' align='left' valign='middle' height='18'><font size='2'>" . $ds->GetValue('CM_name') . "</font></td>");
		Writeln("<td width='35' align='center' valign='middle' height='18'><font size='2'>" . $ds->GetValue('kyohi') . "</font></td>");
		$ds->MoveNext();
		if (!$ds->EOF()) {
			$n++;
			Writeln("<td width='18' align='center' valign='middle' bgcolor='#b2cfef' height='18'><font size='2'>$n</font></td>");
			Writeln("<td width='180' align='left' valign='middle' height='18'><font size='2'>" . $ds->GetValue('CM_name') . "</font></td>");
		Writeln("<td width='35' align='center' valign='middle' height='18'><font size='2'>" . $ds->GetValue('kyohi') . "</font></td>");
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

SetKyohiKigyou();
?>
<html>

	<head>
		<meta http-equiv="content-type" content="text/html;charset=EUC-JP">
		<title>受信拒否完了</title>
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

		<td colspan="2" bgcolor="#3f659e" height="18" align="left" valign="middle"><img height="16" width="14" src="../img/space.gif"><a href="../index.php"><img src="../img/home.gif" width="53" height="16" alt="HOME" border="0"></a><a href="../2/index.html"><img src="../img/himail_net.gif" width="130" height="16" border="0" alt="はいめーる・netって？"></a><a href="../movie/index.html"><img src="../img/tsushin2.gif" width="119" height="16" alt="はいめーる映画通信" border="0"></a><a href="../3/index.html"><img src="../img/promise.gif" width="44" height="16" border="0" alt="お約束"></a><a href="../4/index.html"><img src="../img/policy.gif" width="119" height="16" border="0" alt="プライバシーポリシー"></a><a href="../5/index.html"><img src="../img/faq.gif" width="42" height="16" border="0" alt="FAQ"></a><a href="../6/index.php"><img src="../img/use_business.gif" width="108" height="16" border="0" alt="ビジネスで利用する"></a><a href="../7/index.html"><img src="../img/use_personal.gif" width="88" height="16" border="0" alt="個人で利用する"></a></td>
			</tr>
		</table><br>
		<br>
		<table width="595" border="0" cellspacing="0" mm_noconvert="TRUE" cellpadding="0">
			<tr height="36">
				<td width="43" height="36" align="right" valign="top"><img height="16" width="43" src="../img/space.gif"></td>
				<td height="36" align="left" valign="top">
					<div align="left">
						<b><img height="26" width="435" src="img/kyohi_title.gif" alt="受信拒否企業設定"></b></div>
				</td>
			</tr>
			<tr>
				<td width="43" align="right" valign="top">　</td>
				<td><font size="2">以下の企業を受信拒否企業として設定しました。<br>
					<br>
					</font><table border="0" cellpadding="0" cellspacing="2" width="466">
<?
WriteKyohiKugyou();
?>
					</table></td>
			</tr>
			<tr height="36">
				<td width="43" height="36">　</td>
				<td height="36"><br>
					<font size="2"><br>
					<br>
					<br>
					<hr>
					</font><a href="index.php"><img height="17" width="80" src="img/mypage.gif" alt="my pageに戻る" border="0"></a><br>
					<br>
				</td>
			</tr>
		</table>
	</body>

</html>
