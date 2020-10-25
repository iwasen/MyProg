<?
/******************************************************
' System :Eco-footprint 会員ページ
' Content:詳細情報入力案内
'******************************************************/

$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/encrypt.php");
$inc = "$top/inc";
include("$inc/regist2.php");

// セッション処理
session_start();
if ($key) {
	$seq_no= decrypt_number($key);
	if ($seq_no) {
		$_SESSION['ss_seq_no'] = $seq_no;
		$_SESSION['ss_yet_flag'] = 1;

		$sql = "select mb_step from t_member where mb_step<>9 and mb_seq_no=$seq_no";
		$result = db_exec($sql);
		if (pg_numrows($result)) {
			$fetch = pg_fetch_object($result, 0);
			if ($fetch->mb_step == 2)
				redirect('fiducial_check.php');
			elseif ($fetch->mb_step == 3)
				redirect('main.php');
		} else
			redirect('main.php');
	}
}

if (!isset($_SESSION['ss_seq_no']))
	redirect('login.php');

$_SESSION['ss_regist2'] = new mm_regist2;
$reg = &$_SESSION['ss_regist2'];

?>
<html>
<head>
<title>☆★☆xxxゾウさんClub☆★☆</title>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
</head>
<body bgcolor="#FF0000" leftmargin="8" topmargin="8">
<div align="center">
<table width="714" height="90%" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
	<tr>
		<td width="35" align="left" valign="top" bgcolor="#FFFFFF"><img src="img/kado_1.gif" width="35" height="35"></td>
		<td align="center" valign="bottom">
			<table border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td><img src="img/nyuukai/title/m_exp01.gif" width="364" height="78" alt="入会登録へようこそ"></td>
				</tr>
			</table><hr width="95%" size="1">
		</td>
		<td width="35" align="right" valign="top">
		<img src="img/kado_2.gif" width="35" height="35"></td>
	</tr>

	<tr>
		<td><br></td>
		<td align="center" valign="top">
		<font size=2><br>
		メールアドレスの確認ができました。<br>
		会員登録のステップ1が完了しました。<br>
		さぁ、次はステップ2です。<br><br>
		</font>
			<table border="0" cellspacing="0" cellpadding="3">
				<tr bgcolor="#00CCFF">
					<td colspan="3" valign="middle" height="1"><img src="img/spacer.gif" width="600" height="1"></td>
				</tr>

				<tr>
					<td width="57"><img src="img/nyuukai/zou_hoshi.gif" width="75" height="76"></td>
					<td width="156" valign="middle">
					<div align="center"><img src="img/nyuukai/title/step_1.gif" width="126" height="76" alt="個人情報"></div></td>
					<td width="436" valign="middle">
					<font color="#808080" size=2>
					xxxゾウさんClubの会員ID（ニックネーム）とパスワードを登録します。<br>
					★あなたのパソコン用メールアドレスをご用意ください。
					</font>
					</td>
				</tr>

				<tr bgcolor="#00CCFF">
					<td colspan="3" height="1">
					<div align="center"><img src="img/spacer.gif" width="600" height="1"></div>
					</td>
				</tr>

				<tr>
					<td width="57" valign="middle"><img src="img/nyuukai/hata/hata_2.gif" width="75" height="76"></td>
					<td width="156" valign="middle"><div align="center"><img src="img/nyuukai/title/step_2.gif" width="126" height="76" alt="シュミレーション条件"></div></td>
					<td width="436" valign="middle">
					<font size=2>
					あなたの家族や、お住まいの状況についての情報を入力してください。<br><br>
					<font color="#FF0000">
					★xxxゾウさんClubでは、それぞれのメンバーのお住まいの状況や、家族構成を入力していただくことで、
					より効果的なEco生活を送るための提案を行っていく予定です。ここで入力された個人情報は、
					厳重に管理されますのでご安心ください。
					</font>
					<br><br>
					個人情報の取り扱いについて→<a href="xxx03.php" target="_blank" title="プライバシー・ポリシー">プライバシー・ポリシーを読む</a>
					</font>
					</td>
				</tr>

				<tr bgcolor="#00CCFF"><td height="1" colspan="3"><img src="img/spacer.gif" width="600" height="1"></td>
				</tr>
			</table>
			<br>
			<table border=0>
				<tr>
					<td align="center">
						<img src="img/nyuukai/present/present.gif" width=669 height=337 alt="present" border=0 usemap="#map_zoumama">
						<map name="map_zoumama"><area coords="370,100,620,300" shape="rect" href="secret_mama.php" target="_blank"></map>
					</td>
				</tr>
			</table>
			<hr width="95%" size="1"></td>
		<td><br></td>
	</tr>
	<tr>
		<td width="35" align="left" valign="bottom"><img src="img/kado_3.gif" width="35" height="35"></td>
		<td align="center" valign="bottom">

			<table border="0" cellspacing="0" cellpadding="0">
				<tr align="center">
					<td><a href="member_input02.php"><img src="img/nyuukai/button/next.gif" width="99" height="40" border="0" alt="次へ"></a></td>
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