<?
/******************************************************
' System :Eco-footprint ����ڡ���
' Content:�ܺپ������ϰ���
'******************************************************/

$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/encrypt.php");
$inc = "$top/inc";
include("$inc/regist2.php");

// ���å�������
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
<title>������xxx��������Club������</title>
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
					<td><img src="img/nyuukai/title/m_exp01.gif" width="364" height="78" alt="������Ͽ�ؤ褦����"></td>
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
		�᡼�륢�ɥ쥹�γ�ǧ���Ǥ��ޤ�����<br>
		�����Ͽ�Υ��ƥå�1����λ���ޤ�����<br>
		���������ϥ��ƥå�2�Ǥ���<br><br>
		</font>
			<table border="0" cellspacing="0" cellpadding="3">
				<tr bgcolor="#00CCFF">
					<td colspan="3" valign="middle" height="1"><img src="img/spacer.gif" width="600" height="1"></td>
				</tr>

				<tr>
					<td width="57"><img src="img/nyuukai/zou_hoshi.gif" width="75" height="76"></td>
					<td width="156" valign="middle">
					<div align="center"><img src="img/nyuukai/title/step_1.gif" width="126" height="76" alt="�Ŀ;���"></div></td>
					<td width="436" valign="middle">
					<font color="#808080" size=2>
					xxx��������Club�β��ID�ʥ˥å��͡���ˤȥѥ���ɤ���Ͽ���ޤ���<br>
					�����ʤ��Υѥ������ѥ᡼�륢�ɥ쥹���Ѱդ���������
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
					<td width="156" valign="middle"><div align="center"><img src="img/nyuukai/title/step_2.gif" width="126" height="76" alt="����ߥ졼�������"></div></td>
					<td width="436" valign="middle">
					<font size=2>
					���ʤ��β�²�䡢�����ޤ��ξ����ˤĤ��Ƥξ�������Ϥ��Ƥ���������<br><br>
					<font color="#FF0000">
					��xxx��������Club�Ǥϡ����줾��Υ��С��Τ����ޤ��ξ����䡢��²���������Ϥ��Ƥ����������Ȥǡ�
					������Ū��Eco��������뤿�����Ƥ�ԤäƤ���ͽ��Ǥ������������Ϥ��줿�Ŀ;���ϡ�
					���Ť˴�������ޤ��ΤǤ��¿�����������
					</font>
					<br><br>
					�Ŀ;���μ�갷���ˤĤ��Ƣ�<a href="xxx03.php" target="_blank" title="�ץ饤�Х������ݥꥷ��">�ץ饤�Х������ݥꥷ�����ɤ�</a>
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
					<td><a href="member_input02.php"><img src="img/nyuukai/button/next.gif" width="99" height="40" border="0" alt="����"></a></td>
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