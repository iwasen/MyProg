<?
include('../inc/common.php');
include('../inc/database.php');

$member_id = $id;
if ($member_id != '') {
	$sql = "SELECT MM_zip_kyojuu,MM_pr_id FROM T_MEMBER WHERE MM_member_id='$member_id'";
	$ds = CreateDynaset($sql, ORADYN_READONLY);
	if (!$ds->EOF()) {
		$zip_kyojuu = $ds->GetValue('MM_zip_kyojuu');
		$pr_id = $ds->GetValue('MM_pr_id');
	}
}
?>
<html>

	<head>
		<meta http-equiv="content-type" content="text/html;charset=EUC-JP">
		<title></title>
	</head>

	<body bgcolor="white" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">

		<table cellspacing="0" cellpadding="0" border="0" height="52">
			<tr height="48">
				<td bgcolor="white" align="left" valign="top" height="48"><img height="48" width="150" src="../img/logo1.gif" alt="はいめーる・net"><img height="48" width="482" src="../img/logo2.gif" alt="interactive communication network"></td>
			</tr>
			<tr height="16">
				<td height="16" align="left" valign="top"><img height="3" width="642" src="../img/logo_line.gif"></td>
			</tr>
		</table>
		<br>
		<table border="0" cellspacing="0" mm_noconvert="TRUE" cellpadding="0">
			<tr height="36">

		<td width="50" height="36"><img src="http://www.info-cash.com/cgi-bin/ictrap/trapchk.cgi?UID=045D582307&PID=<?=$id?>" height="1" width="1"><img height="16" width="50" src="../img/space.gif"></td>
									 <img src="https://admin.ad4commerce.com/e/wfon.pl?m=40&amount=1&count=1&name=<?=$zip_kyojuu?>&email=<?=$member_id?>&option=" width="1" height="1">
				<td width="550" height="36" valign="top"><font color="#2f2180"><b>はいめーる・netへのご登録、ありがとうございました。<br>
					これで登録はすべて完了です。
					<hr>
					</b></font><font size="2"><br>
					あとは「はいめーる」が届くのを楽しみにお待ちください。<br>
					<br>
					また、ＭｙPａｇｅ（あなた専用のページ）では、登録情報の変更や<br>
					お友達に紹介メールを送るなどの機能をいろいろご用意しております。<br>
					ぜひご利用ください。<br>
					<br>
					ＭｙＰａｇｅへは、ＴＯＰからメールアドレスとパスワードを入力するとログインできます。<br>
					<br>
					</font><center><a href="../index.php"><img height="17" width="80" src="../img/home_b.gif" border="0" alt="ホームへ戻る"></a><br></center>

			<p><br>
				<br>
				<br>
				<b><br>
				<font size="2">はいめーる・netでは、現在「<font color="#333399">バナーで紹介</font>」キャンペーンを実施しています。<br>
				お申し込みはＭｙＰａｇｅにて受け付け中！</font></b><br>
				<br>
				<br>
				<br>
				<br>
				<br>
				<br>
				<br>
				<br>
				<br>
				<br>
				<br>
				<br>
			</p>
<?
// Ｇマイル用タグ挿入
if ($pr_id == '0003200') {
?>
<img src="https://ssl.p-advg.com/count4cosmo?uid=732&price=10&code=<?=$member_id?>" width=1 height=1>
<?
}
?>
			</td></tr>
			<tr height="36">
				<td width="50" height="36"></td>

		<td colspan="2" align="center" valign="top"><font size="1">
			<hr width="100%" size="1">
			「はいめーる・net」は株式会社○○○○○○が運営しています。<br>
			Copyright (C) 2002-2002 xx xxxxx, Inc. All rights reserved. <br>
			運営協力：株式会社インプレス<br>
			<br>
			<a href="http://www.xxxxxxx.co.jp/guide/xxxxxxx.html"target="top"> </a></font>
			<table width="200" border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td width="100">
						<div align="center"><a href="http://www.xxxxxxx.co.jp/guide/xxxxxxx.html"><img src="../img/xxxxxxx_logo_i.gif" width="43" height="46" border="0" alt="xx xxxxx"></a></div>
					</td>
					<td width="100">
						<div align="center"><a href="http://www.impress.co.jp/"><img src="../img/impress_logo_i.gif" width="53" height="39" border="0" alt="impress"></a></div>
					</td>
				</tr>
			</table>
			<font size="1"><br>
			<br>
			</font></td>
			</tr>
		</table>
	</body>

</html>
