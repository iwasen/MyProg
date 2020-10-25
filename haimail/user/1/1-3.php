<?
include('../inc/common.php');
include('../inc/database.php');
include('../inc/reginfo.php');
include('../inc/show_profile.php');

//=== 住所取得 ===
function SetAddress($zip) {
	if ($zip == '0000000')
		$address = '海外在住';
	else {
		$address = GetAddress($zip);
		if ($address == '')
			$address = '郵便番号に該当する住所がみつかりません';
	}
	return "（${address}）";
}

//=== メイン処理 ===
session_start();
$reginfo = new CRegInfo;

$age = '（' . GetAge("$reginfo->sei_nen/$reginfo->sei_getsu/$reginfo->sei_hi") . '才）';

if ($reginfo->zip_kyojuu1 != '')
	$adr_kyojuu = SetAddress($reginfo->zip_kyojuu1 . $reginfo->zip_kyojuu2);

if ($reginfo->zip_kinmu1 != '')
	$adr_kinmu = SetAddress($reginfo->zip_kinmu1 . $reginfo->zip_kinmu2);

//プライスロト対応================
if ($_COOKIE['PRID'] == '0000400')
	$next_page = '1-4-priceloto.php';
else
	$next_page = '1-4.php';
//================================
?>
<html>

	<head>
		<meta http-equiv="content-type" content="text/html;charset=EUC-JP">
		<title>あなたの登録情報</title>
	</head>

	<body bgcolor="white" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
		<form method="post" action="<?=$next_page?>">
		<table cellspacing="0" cellpadding="0" border="0" height="52">
			<tr height="48">
				<td bgcolor="white" align="left" valign="top" height="48"><img height="48" width="150" src="../img/logo1.gif" alt="はいめーる・net"><img height="48" width="482" src="../img/logo2.gif" alt="interactive communication network"></td>
			</tr>
			<tr height="16">
				<td height="16" align="left" valign="top"><img height="3" width="642" src="../img/logo_line.gif"></td>
			</tr>
		</table><br>
		<table border="0" cellspacing="0" width="605" cellpadding="0">
			<tr>
				<td colspan="2"><b><table border="0" cellpadding="0" cellspacing="0" width="456">
						<tr>
							<td><img height="16" width="25" src="../img/space.gif"></td>
							<td><img height="26" width="435" src="img/1-3title.gif" alt="あなたの登録情報"></td>
						</tr>
					</table></b></td>
			</tr>
			<tr>
				<td width="25"><img height="16" width="25" src="../img/space.gif"></td>
				<td width="585"><font size="2"><br>
					<br>
					</font><font color="#2f2180"><b>あなたが登録している情報です。<br>
					</b></font><font size="2"><br>
					登録をやりなおしたい場合は「戻る」ボタンを、<br>
					これでよろしければ「送信」ボタンを押してください。<br>
					<br>
					</font><table width="579" border="0" cellspacing="2" cellpadding="0">
						<tr>
							<td width="144" bgcolor="#b2cfef" align="left" valign="middle"><font size="2">メールアドレス</font></td>
							<td width="433" bgcolor="#fff6ea"><font size="2"><?=$reginfo->mail_adr?></font></td>
						</tr>
						<tr>
							<td width="144" bgcolor="#b2cfef" align="left" valign="middle"><font size="2">パスワード</font></td>
							<td width="433" bgcolor="#fff6ea"><font size="2"><?=$reginfo->password?></font></td>
						</tr>
						<tr>
							<td width="144" bgcolor="#b2cfef" align="left" valign="middle"><font size="2">性別</font></td>
							<td width="433" align="left" valign="middle" bgcolor="#fff6ea"><font size="2"><?=SeibetsuText($reginfo->seibetsu)?></font> </td>
						</tr>
						<tr>
							<td width="144" bgcolor="#b2cfef" align="left" valign="middle"><font size="2">未既婚</font></td>
							<td width="433" align="left" valign="top" bgcolor="#fff6ea"><font size="2"><?=MikikonText($reginfo->mikikon)?></font></td>
						</tr>
						<tr>
							<td width="144" bgcolor="#b2cfef" align="left" valign="middle"><font size="2">生年月日</font></td>
							<td width="433" bgcolor="#fff6ea"><font size="2"><?="{$reginfo->sei_nen}年{$reginfo->sei_getsu}月{$reginfo->sei_hi}日 $age"?></font></td>
						</tr>
						<tr>
							<td width="144" bgcolor="#b2cfef" align="left" valign="middle"><font size="2">居住地郵便番号</font></td>
							<td width="433" align="left" valign="middle" bgcolor="#fff6ea"><font size="2"><?="$reginfo->zip_kyojuu1-$reginfo->zip_kyojuu2 $adr_kyojuu"?></font></td>
						</tr>
						<tr>
							<td width="144" bgcolor="#b2cfef" align="left" valign="middle"><font size="2">勤務地郵便番号</font></td>
							<td align="left" valign="middle" width="433" bgcolor="#fff6ea"><font size="2"><?="$reginfo->zip_kinmu1-$reginfo->zip_kinmu2 $adr_kinmu"?></font></td>
						</tr>
						<tr>
							<td width="144" bgcolor="#b2cfef" align="left" valign="middle"><font size="2">職業</font></td>
							<td align="left" valign="middle" width="433" bgcolor="#fff6ea"><font size="2"><?=ShokugyouText($reginfo->shokugyou)?></font></td>
						</tr>
						<tr>
							<td width="144" bgcolor="#b2cfef" align="left" valign="middle"><font size="2">HTMLメール受信設定</font></td>
							<td align="left" valign="middle" width="433" bgcolor="#fff6ea"><font size="2"><?=HtmlMailText($reginfo->html_mail)?></font></td>
						</tr>
					</table></td>
			</tr>
		</table><br>
		<br>
		<table cellspacing="0" cellpadding="0" border="0" width="610">
			<tr>
				<td width="20" valign="top"><img height="16" width="20" src="../img/space.gif"></td>
				<td valign="top" width="282"><font size="2"><table border="0" cellpadding="0" cellspacing="2">
						<tr>
							<td width="40" align="left" valign="top" bgcolor="white"></td>
							<td width="190" bgcolor="#b2cfef"><font size="2">興味ジャンル　　　</font></td>
							<td width="40" bgcolor="#b2cfef" align="center" valign="middle"><br>
							</td>
						</tr>
						<tr bgcolor="white" height="16">
							<td width="40" rowspan="13" align="left" valign="top" bgcolor="white"><img src="../img/eat_little.gif" width="40" height="53" alt="食"></td>
							<td width="190" height="16"></td>
							<td width="40" height="16"></td>
						</tr>
<?
WriteKyoumiGenre('食', $reginfo->kyoumi_genre);
?>
					</table>
					<hr>
					<table border="0" cellpadding="0" cellspacing="2">
						<tr bgcolor="#ffffcf" height="34">
							<td width="40" rowspan="2" bgcolor="white"><img src="../img/clothes_little.gif" width="40" height="52" alt="衣"></td>
							<td colspan="2" bgcolor="white" height="34"></td>
						</tr>
<?
WriteKyoumiGenre('衣', $reginfo->kyoumi_genre);
?>
					</table>
					<hr>
					<table border="0" cellpadding="0" cellspacing="2">
						<tr bgcolor="white" height="16">
							<td width="40" rowspan="6" align="left" valign="top" bgcolor="white"><img src="../img/living_little.gif" width="40" height="52" alt="住"></td>
							<td width="190" height="16"></td>
							<td width="40" height="16"></td>
						</tr>
<?
WriteKyoumiGenre('住', $reginfo->kyoumi_genre);
?>
					</table>
					<hr>
					<table border="0" cellpadding="0" cellspacing="2">
						<tr bgcolor="#ffffcf" height="16">
							<td width="40" rowspan="7" align="left" valign="top" bgcolor="white"><img src="../img/work_little.gif" width="40" height="52" alt="働"></td>
							<td colspan="2" bgcolor="white" height="16"></td>
						</tr>
<?
WriteKyoumiGenre('働', $reginfo->kyoumi_genre);
?>
					</table>
					<hr>
					<table border="0" cellpadding="0" cellspacing="2">
						<tr bgcolor="#ffffcf" height="16">
							<td width="40" rowspan="5" align="left" valign="top" bgcolor="white"><img src="../img/life_little.gif" width="40" height="52" alt="暮"></td>
							<td colspan="2" bgcolor="white" height="16"></td>
						</tr>
<?
WriteKyoumiGenre('暮', $reginfo->kyoumi_genre);
?>
					</table>
					<hr>
					<table border="0" cellpadding="0" cellspacing="2">
						<tr height="16">
							<td width="40" rowspan="3" align="left" valign="top"><img src="../img/relax_little.gif" width="40" height="52" alt="休"></td>
							<td colspan="2" bgcolor="white" height="16"></td>
						</tr>
<?
WriteKyoumiGenre('休', $reginfo->kyoumi_genre);
?>
					</table></font></td>
				<td width="25" valign="top"><img height="16" width="25" src="../img/space.gif"></td>
				<td valign="top"><font size="2"><table border="0" cellpadding="0" cellspacing="2">
						<tr>
							<td width="40" bgcolor="white"></td>
							<td bgcolor="#b2cfef" width="190"><font size="2">興味ジャンル</font></td>
							<td bgcolor="#b2cfef" width="40" align="center" valign="middle"><br>
							</td>
						</tr>
						<tr bgcolor="white" height="16">
							<td width="40" rowspan="17" valign="top" bgcolor="white"><img src="../img/play_little.gif" width="40" height="52" alt="遊"></td>
							<td colspan="2" height="16"></td>
						</tr>
<?
WriteKyoumiGenre('遊', $reginfo->kyoumi_genre);
?>
					</table>
					<hr>
					<table border="0" cellpadding="0" cellspacing="2">
						<tr align="left" valign="top" bgcolor="white" height="16">
							<td width="40" rowspan="4" bgcolor="white"><img src="../img/beauty_little.gif" width="40" height="52" alt="美"></td>
							<td width="190" bgcolor="white" height="16"></td>
							<td width="40" height="16"></td>
						</tr>
<?
WriteKyoumiGenre('美', $reginfo->kyoumi_genre);
?>
					</table>
					<hr>
					<table border="0" cellpadding="0" cellspacing="2">
						<tr align="left" valign="top" bgcolor="white" height="16">
							<td width="40" rowspan="15" bgcolor="white"><img src="../img/knowridge_little.gif" width="40" height="52" alt="知"></td>
							<td width="190" height="16"></td>
							<td width="40" height="16"></td>
						</tr>
<?
WriteKyoumiGenre('知', $reginfo->kyoumi_genre);
?>
					</table></font></td>
			</tr>
		</table><br>
		<table width="616" border="0" cellspacing="0" mm_noconvert="TRUE" cellpadding="0">
			<tr height="36">
				<td width="50" height="36" align="left" valign="top"><img height="16" width="50" src="../img/space.gif"></td>
				<td height="36" align="center" valign="top"><br>
					<table width="400" border="0" cellspacing="0" cellpadding="0">
						<tr>
							<td width="198" valign="top"><table width="190" border="0" cellspacing="2" cellpadding="0">
									<tr>
										<td width="128" align="left" valign="middle" bgcolor="#b2cce4"><font size="2">よく行くコンビニ</font></td>
									</tr>
<?
WriteYokuikuMise('01', $reginfo->yokuiku_mise);
?>
								</table></td>
							<td width="186" align="right" valign="top"><table width="190" border="0" cellspacing="2" cellpadding="0">
									<tr>
										<td width="187" align="left" valign="middle" bgcolor="#b2cfef"><font size="2">よく行くスーパー</font></td>
									</tr>
<?
WriteYokuikuMise('02', $reginfo->yokuiku_mise);
?>
								</table></td>
						</tr>
					</table><br>
					<table width="400" border="0" cellspacing="2" cellpadding="0">
						<tr>
							<td colspan="2" bgcolor="#b2cfef"><font size="2">その他のよく行くお店</font></td>
						</tr>
						<tr>
							<td width="198"><table width="190" border="0" cellspacing="2" cellpadding="0">
<?
WriteYokuikuMise('03', $reginfo->yokuiku_mise);
?>
								</table></td>
							<td width="186" align="right" valign="top"><table width="190" border="0" cellspacing="2" cellpadding="0">
<?
WriteYokuikuMise('04', $reginfo->yokuiku_mise);
?>
								</table></td>
						</tr>
					</table><br>
					<table border="0" cellspacing="2" cellpadding="0">
						<tr>
							<td width="400" bgcolor="#b2cfef"><font size="2">あなたはどっち派？</font></td>
						</tr>
<?
WriteDocchiHa($reginfo->docchiha);
?>
					</table><br>
					<table border="0" cellspacing="2" cellpadding="0">
						<tr>
							<td width="400" bgcolor="#b2cfef"><font size="2">「発信する生活者」を応援するお知らせをｅメールで受け取りますか？<br>
								</font></td>
						</tr>
						<tr>
							<td width="400"><font size="2"><?=OshiraseMailText($reginfo->oshirase_mail)?></font></td>
						</tr>
					</table><br>
					<hr>
					<table width="400" border="0" cellspacing="2" cellpadding="0">
						<tr>
							<td align="left" valign="top"><font size="2">送信後、確認メールが届きます。<br>
								確認メールにあるURLをクリックすれば、登録はすべて完了です。<br>
								<br>
<!-- 2000/12/01 J.Koyama/xx xxxxx 修正ここから -->
<!--
								</font><input type="button" value="　戻　る　" onClick="location.href='1-2.php'"> 　 <input type="submit" value="　送　信　" name="完了"><br>
-->
								</font><input type="submit" value="　送　信　" name="完了">　<input type="button" value="　戻　る　"onClick="location.href='1-2.php'"><br>
<!-- 修正ここまで -->
							</td>
						</tr>
					</table></td>
			</tr>
		</table><br>
		</form>
	</body>

</html>
