<?
include('../inc/common.php');
include('../inc/database.php');
include('../inc/member.php');
include('../inc/show_profile.php');

//=== ������� ===
function SetAddress($zip) {
	if ($zip == '0000000')
		$address = '�����߽�';
	else {
		$address = GetAddress($zip);
		if ($address == '')
			$address = '͹���ֹ�˳������뽻�꤬�ߤĤ���ޤ���';
	}
	return "��${address}��";
}

//=== �ᥤ����� ===
session_start();
if (!$_SESSION['login'])
	redirect('../index.php');

$member = new CMember;

$age = '��' . GetAge("$member->sei_nen/$member->sei_getsu/$member->sei_hi") . '�͡�';

if ($member->zip_kyojuu1 != '') {
	$adr_kyojuu = SetAddress($member->zip_kyojuu1 . $member->zip_kyojuu2);
}

if ($member->zip_kinmu1 != '') {
	$adr_kinmu = SetAddress($member->zip_kinmu1 . $member->zip_kinmu2);
}
?>
<html>

	<head>
		<meta http-equiv="content-type" content="text/html;charset=EUC-JP">
		<title>MY ��Ͽ�����ǧ</title>
	</head>

	<body bgcolor="white" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
		<form method="post" action="8a-4.php">
		<table cellspacing="0" cellpadding="0" border="0" height="58">
			<tr height="48">
				<td bgcolor="white" align="left" valign="top" height="48"><img height="48" width="150" src="../img/logo1.gif" alt="�Ϥ��᡼�롦net"><img height="48" width="482" src="../img/logo2.gif" alt="interactive communication network"></td>
			</tr>
			<tr height="16">
				<td height="16" align="left" valign="top"><img height="3" width="642" src="../img/logo_line.gif"></td>
			</tr>
		</table><br>
		<b><table border="0" cellpadding="0" cellspacing="0" width="632">
			<tr height="36">
				<td width="20" height="36"><img height="16" width="20" src="../img/space.gif"></td>
				<td height="36" align="left" valign="top"><b><img height="26" width="435" src="img/8a-3_title.gif" alt="��Ͽ����γ�ǧ"></b></td>
			</tr>
			<tr>
				<td width="20"></td>
				<td><font color="#2f2180"><b>���ʤ���������������Ǥ���<br>
					<br>
					</b></font><font size="2">���ʤ������ϡ����ץܥ���򡢤���Ǥ��������Сֹ����ץܥ���򲡤��Ƥ���������<br>
					<br>
					</font><table width="579" border="0" cellspacing="2" cellpadding="0">
						<tr>
							<td width="144" bgcolor="#b2cfef" align="left" valign="middle"><font size="2">�᡼�륢�ɥ쥹</font></td>
							<td width="433" bgcolor="#fff6ea"><font size="2"><?=$member->mail_adr?></font></td>
						</tr>
						<tr>
							<td width="144" bgcolor="#b2cfef" align="left" valign="middle"><font size="2">�ѥ����</font></td>
							<td width="433" bgcolor="#fff6ea"><font size="2"><?=$member->password?></font></td>
						</tr>
						<tr>
							<td width="144" bgcolor="#b2cfef" align="left" valign="middle"><font size="2">����</font></td>
							<td width="433" align="left" valign="middle" bgcolor="#fff6ea"><font size="2"><?=SeibetsuText($member->seibetsu)?></font> </td>
						</tr>
						<tr>
							<td width="144" bgcolor="#b2cfef" align="left" valign="middle"><font size="2">̤����</font></td>
							<td width="433" align="left" valign="top" bgcolor="#fff6ea"><font size="2"><?=MikikonText($member->mikikon)?></font></td>
						</tr>
						<tr>
							<td width="144" bgcolor="#b2cfef" align="left" valign="middle"><font size="2">��ǯ����</font></td>
							<td width="433" bgcolor="#fff6ea"><font size="2"><?="{$member->sei_nen}ǯ{$member->sei_getsu}��{$member->sei_hi}�� $age"?></font></td>
						</tr>
						<tr>
							<td width="144" bgcolor="#b2cfef" align="left" valign="middle"><font size="2">�ｻ��͹���ֹ�</font></td>
							<td width="433" align="left" valign="middle" bgcolor="#fff6ea"><font size="2"><?="$member->zip_kyojuu1-$member->zip_kyojuu2 $adr_kyojuu"?></font></td>
						</tr>
						<tr>
							<td width="144" bgcolor="#b2cfef" align="left" valign="middle"><font size="2">��̳��͹���ֹ�</font></td>
							<td align="left" valign="middle" width="433" bgcolor="#fff6ea"><font size="2"><?="$member->zip_kinmu1-$member->zip_kinmu2 $adr_kinmu"?></font></td>
						</tr>
						<tr>
							<td width="144" bgcolor="#b2cfef" align="left" valign="middle"><font size="2">����</font></td>
							<td align="left" valign="middle" width="433" bgcolor="#fff6ea"><font size="2"><?=ShokugyouText($member->shokugyou)?></font></td>
						</tr>
						<tr>
							<td width="144" bgcolor="#b2cfef" align="left" valign="middle"><font size="2">HTML�᡼���������</font></td>
							<td align="left" valign="middle" width="433" bgcolor="#fff6ea"><font size="2"><?=HtmlMailText($member->html_mail)?></font></td>
						</tr>
					</table></td>
			</tr>
		</table></b><br>
		<br>
		<table cellspacing="0" cellpadding="0" border="0" width="610">
			<tr>
				<td width="20" valign="top"><img height="16" width="20" src="../img/space.gif"></td>
				<td valign="top" width="282"><font size="2"><table border="0" cellpadding="0" cellspacing="2">
						<tr>
							<td width="40" align="left" valign="top" bgcolor="white"></td>
							<td width="190" bgcolor="#b2cfef"><font size="2">��̣�����롡����</font></td>
							<td width="40" bgcolor="#b2cfef" align="center" valign="middle"><br>
							</td>
						</tr>
						<tr bgcolor="white" height="16">
							<td width="40" rowspan="13" align="left" valign="top" bgcolor="white"><img src="../img/eat_little.gif" width="40" height="53" alt="��"></td>
							<td width="190" height="16"></td>
							<td width="40" height="16"></td>
						</tr>
<?
WriteKyoumiGenre('��', $member->kyoumi_genre);
?>
					</table>
					<hr>
					<table border="0" cellpadding="0" cellspacing="2">
						<tr bgcolor="#ffffcf" height="34">
							<td width="40" rowspan="2" bgcolor="white"><img src="../img/clothes_little.gif" width="40" height="52" alt="��"></td>
							<td colspan="2" bgcolor="white" height="34"></td>
						</tr>
<?
WriteKyoumiGenre('��', $member->kyoumi_genre);
?>
					</table>
					<hr>
					<table border="0" cellpadding="0" cellspacing="2">
						<tr bgcolor="white" height="16">
							<td width="40" rowspan="6" align="left" valign="top" bgcolor="white"><img src="../img/living_little.gif" width="40" height="52" alt="��"></td>
							<td width="190" height="16"></td>
							<td width="40" height="16"></td>
						</tr>
<?
WriteKyoumiGenre('��', $member->kyoumi_genre);
?>
					</table>
					<hr>
					<table border="0" cellpadding="0" cellspacing="2">
						<tr bgcolor="#ffffcf" height="16">
							<td width="40" rowspan="7" align="left" valign="top" bgcolor="white"><img src="../img/work_little.gif" width="40" height="52" alt="Ư"></td>
							<td colspan="2" bgcolor="white" height="16"></td>
						</tr>
<?
WriteKyoumiGenre('Ư', $member->kyoumi_genre);
?>
					</table>
					<hr>
					<table border="0" cellpadding="0" cellspacing="2">
						<tr bgcolor="#ffffcf" height="16">
							<td width="40" rowspan="5" align="left" valign="top" bgcolor="white"><img src="../img/life_little.gif" width="40" height="52" alt="��"></td>
							<td colspan="2" bgcolor="white" height="16"></td>
						</tr>
<?
WriteKyoumiGenre('��', $member->kyoumi_genre);
?>
					</table>
					<hr>
					<table border="0" cellpadding="0" cellspacing="2">
						<tr height="16">
							<td width="40" rowspan="3" align="left" valign="top"><img src="../img/relax_little.gif" width="40" height="52" alt="��"></td>
							<td colspan="2" bgcolor="white" height="16"></td>
						</tr>
<?
WriteKyoumiGenre('��', $member->kyoumi_genre);
?>
					</table></font></td>
				<td width="25" valign="top"><img height="16" width="25" src="../img/space.gif"></td>
				<td valign="top"><font size="2"><table border="0" cellpadding="0" cellspacing="2">
						<tr>
							<td width="40" bgcolor="white"></td>
							<td bgcolor="#b2cfef" width="190"><font size="2">��̣������</font></td>
							<td bgcolor="#b2cfef" width="40" align="center" valign="middle"><br>
							</td>
						</tr>
						<tr bgcolor="white" height="16">
							<td width="40" rowspan="17" valign="top" bgcolor="white"><img src="../img/play_little.gif" width="40" height="52" alt="ͷ"></td>
							<td colspan="2" height="16"></td>
						</tr>
<?
WriteKyoumiGenre('ͷ', $member->kyoumi_genre);
?>
					</table>
					<hr>
					<table border="0" cellpadding="0" cellspacing="2">
						<tr align="left" valign="top" bgcolor="white" height="16">
							<td width="40" rowspan="4" bgcolor="white"><img src="../img/beauty_little.gif" width="40" height="52" alt="��"></td>
							<td width="190" bgcolor="white" height="16"></td>
							<td width="40" height="16"></td>
						</tr>
<?
WriteKyoumiGenre('��', $member->kyoumi_genre);
?>
					</table>
					<hr>
					<table border="0" cellpadding="0" cellspacing="2">
						<tr align="left" valign="top" bgcolor="white" height="16">
							<td width="40" rowspan="15" bgcolor="white"><img src="../img/knowridge_little.gif" width="40" height="52" alt="��"></td>
							<td width="190" height="16"></td>
							<td width="40" height="16"></td>
						</tr>
<?
WriteKyoumiGenre('��', $member->kyoumi_genre);
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
							<td width="198"><table width="190" border="0" cellspacing="2" cellpadding="0">
									<tr>
										<td width="128" align="left" valign="middle" bgcolor="#b2cce4"><font size="2">�褯�Ԥ�����ӥ�</font></td>
									</tr>
<?
WriteYokuikuMise('01', $member->yokuiku_mise);
?>
								</table></td>
							<td width="186" align="right" valign="top"><table width="190" border="0" cellspacing="2" cellpadding="0">
									<tr>
										<td width="187" align="left" valign="middle" bgcolor="#b2cfef"><font size="2">�褯�Ԥ������ѡ�</font></td>
									</tr>
<?
WriteYokuikuMise('02', $member->yokuiku_mise);
?>
								</table></td>
						</tr>
					</table><br>
					<table width="400" border="0" cellspacing="2" cellpadding="0">
						<tr>
							<td colspan="2" bgcolor="#b2cfef"><font size="2">����¾�Τ褯�Ԥ���Ź</font></td>
						</tr>
						<tr>
							<td width="198"><table width="190" border="0" cellspacing="2" cellpadding="0">
<?
WriteYokuikuMise('03', $member->yokuiku_mise);
?>
								</table></td>
							<td width="186" align="right" valign="top"><table width="190" border="0" cellspacing="2" cellpadding="0">
<?
WriteYokuikuMise('04', $member->yokuiku_mise);
?>
								</table></td>
						</tr>
					</table><br>
					<table border="0" cellspacing="2" cellpadding="0">
						<tr>
							<td width="400" bgcolor="#b2cfef"><font size="2">���ʤ��Ϥɤä��ɡ�</font></td>
						</tr>
<?
WriteDocchiHa($member->docchiha);
?>
					</table><br>
					<table border="0" cellspacing="2" cellpadding="0">
						<tr>
							<td width="400" bgcolor="#b2cfef"><font size="2">��ȯ����������ԡפ���礹�뤪�Τ餻���᡼��Ǽ������ޤ�����<br>
								</font></td>
						</tr>
						<tr>
							<td width="400"><font size="2"><?=OshiraseMailText($member->oshirase_mail)?></font></td>
						</tr>
					</table><br>
					<hr>
					<table width="400" border="0" cellspacing="2" cellpadding="0">
						<tr>
							<td align="left" valign="top"><input type="submit" value="����������" name="��λ"> �� <input type="button" value="���ᡡ�롡" onClick="location.href='8a-2.php'"><br>
							</td>
						</tr>
					</table></td>
			</tr>
		</table><br>
		</form>
	</body>

</html>