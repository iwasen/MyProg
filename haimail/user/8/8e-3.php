<?
include('../inc/common.php');
include('../inc/database.php');
include('../inc/member.php');

//=== 退会処理 ===
function Taikai($member_id) {
	BeginTrans();

	//会員マスタの状態を9（退会）に設定
	$sql = "UPDATE T_MEMBER SET MM_status='9' WHERE MM_member_id='$member_id'";
	ExecuteSQL($sql);

	//退会理由を保存
	$sql = "SELECT * FROM T_TAIKAI WHERE TK_member_id='$member_id'";
	$ds = CreateDynaset($sql, ORADYN_DEFAULT);
	if ($ds->EOF()) {
		$sql = sprintf("INSERT INTO T_TAIKAI (TK_member_id,TK_taikai_riyuu,TK_taikai_date) VALUES (%s,%s,SYSDATE)",
				SqlStr($member_id),
				SqlStr($_POST['taikai_riyuu']));
	} else {
		$sql = sprintf("UPDATE T_TAIKAI SET TK_taikai_riyuu=%s WHERE TK_member_id='$member_id'".
				SqlStr($_POST['taikai_riyuu']));
	}
	ExecuteSQL($sql);

	CommitTrans();

	ExecuteSQL("BEGIN S_DELETE_SEARCH_DATA('$member_id'); END;");
}

//=== メイン処理 ===
session_start();
if (!$_SESSION['login'])
	redirect('../index.php');

$member = new CMember;

Taikai($member->member_id);

session_destroy();
?>
<html>

	<head>
		<meta http-equiv="content-type" content="text/html;charset=EUC-JP">
		<title>MY退会完了</title>
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
		<table width="604" border="0" cellspacing="0" mm_noconvert="TRUE" cellpadding="0">
			<tr>
				<td width="50">　<img height="16" width="50" src="../img/space.gif"></td>
				<td width="561"><font color="#2f2180"><b>メンバーを退会しました</b>。</font><font size="2"><br>
					<br>
					<br>
					<br>
					<br>
					<br>
					<br>
					<br>
					<br>
					<br>
					<hr>
					</font><a href="../index.php"><img height="17" width="80" src="../img/home_b.gif" border="0" alt="ホームへ戻る"></a><br>
					<br>
				</td>
			</tr>
		</table>
	</body>

</html>
