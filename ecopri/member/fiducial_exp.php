<?
/******************************************************
' System :Eco-footprint ����ڡ���
' Content:��ǯ�������ϰ���
'******************************************************/

$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/cnv_eng_mny.php");
include("$inc/cnv_unit.php");
$inc = "$top/inc";
include("$inc/regist3.php");

// ���å�������
session_start();
if (!isset($_SESSION['ss_seq_no']))
	redirect('login.php');

$_SESSION['ss_regist3'] = new mm_regist3;
$reg = &$_SESSION['ss_regist3'];

$reg->set_start_ym();
$reg->set_energy_cd($_SESSION['ss_seq_no']);
$reg->set_family_cd($_SESSION['ss_seq_no']);
$reg->set_price_unit();
$reg->set_all_average();

?><html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<title>������xxx��������Club������</title>
</head>

<body bgcolor="#FF0000" leftmargin="8" topmargin="8">
<div align="center">
<table width="714" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
	<tr>
		<td width="35" align="left" valign="top" bgcolor="#FFFFFF">
		<img src="img/kado_1.gif" width="35" height="35"></td>
		<td align="center" valign="bottom"><br>
			<table border="0" cellspacing="0" cellpadding="0">
				<tr>

					<td align="center"><img src="img/nyuukai/title/f_exp01.gif" width="250" height="38"></td>
				</tr>
			</table>
		</td>
		<td width="35" align="right" valign="top">
		<img src="img/kado_2.gif" width="35" height="35"></td>
	</tr>

	<tr>
		<td><br></td>
		<td align="center" valign="top">
		<hr width="95%" size="1">
		<br>
			<table border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td width ="500" valign="top"><font size=2> ���ܥ����ӥ�����ǯ�λ����̤Ȥ���Ӥ򤹤�ݡ��μ���ʸ���ɼ�ˤ���������Τ���Ӥ��Ǥ��ޤ�<br>
					���μ���ʸ���ɼ�ˤϡ��ŵ��ס֥����פϰ�ǯʬ���ֿ�ƻ�פϤ�������ΰ��֤����餷����Τ��Ѱդ������� <br>
					<br>
					����ǯ�٤��μ���ʸ���ɼ�ˡ��ŵ�����������ƻ�ˤȡ��⤷���������������������ε������١�
					���ߤνŤ����������Ρ��Ȥ��Ѱդ�������<br><br>
					���μ���ʸ���ɼ�ˤ����긵�ˤʤ����Ǥ��³����ǽ�Ǥ�<br>
					���ŵ�����������ƻ���μ���ʸ���ɼ�ˤ��ʤ����ǡ���ʬ�Υǡ��������줿���Ȥ������ϳ���������䤤��碌������᤹�뤳�Ȥ��Ǥ��ޤ� </font></td>
				</tr>
			</table><br>
			<font color="#FF0000"><img src="img/nyuukai/cyuui.gif" alt="���"></font>
			<br>

			<table width="500" border="1" cellspacing="3" cellpadding="0" bordercolor="#00CCFF">
				<tr>
					<td bgcolor="#CCFFFF"><font size=2>
					<br>
					<ul>
					<li>��ǯ���μ���ʸ���ɼ�����Ƥ����Ϥ���Ȥ��ʤ�����ǯ���Ӥ򸵤��ܥ����ӥ������ѤǤ��ޤ�</li><br>
					<li>��ǯ���μ���ʸ���ɼ�ˤ���äƤʤ������μ���ʸ���ɼ�ˤ�����·�äƤ��ʤ����ξ�硢��ưŪ�˲����ʿ���ͤ����Ϥ���ޤ�</li><br>
					<li>���ĤǤ�ڲ��������ѹ��ۤ���ǡ������Ƥ��ѹ�����ǽ�Ǥ�</li>
					</ul>
					</font></td>
				</tr>
			</table>

			<br>
			<table border="1" cellspacing="3" cellpadding="0" width="500" bordercolor="#FF99CC">
				<tr>
					<td  width="150">
					<div align="center"><img src="img/nyuukai/zou_hoshi.gif" width="57" height="53"></div>
					</td>
					<td>
					<div align="center"><img src="img/nyuukai/title/exp01.gif" alt="�Ŀ;���"></div>
					</td>
				</tr>
				<tr>
					<td>
					<div align="center"><img src="img/nyuukai/zou_hoshi.gif" width="57" height="53"></div>
					</td>
					<td>
					<div align="center"><img src="img/nyuukai/title/exp02.gif" alt="����ߥ졼�������"></div>
					</td>
				</tr>
				<tr>
					<td height ="53" valign="middle">
					<div align="center"><img src="img/nyuukai/hata/hata_3.gif"></div>
					</td>
					<td>
					<div align="center"><img src="img/nyuukai/title/exp03.gif" alt="��ǯ��������"></div>
					</td>
				</tr>
			</table>
		</td>
		<td><br></td>
	</tr>
	<tr>
		<td width="35" align="left" valign="bottom">
		<img src="img/kado_3.gif" width="35" height="35"></td>
		<td align="center" valign="bottom">
		<br>
			<table border="0" cellspacing="0" cellpadding="0">
				<tr align="center">
				<form name="form1" method="post" action="fiducial_input.php">
					<td width="100" valign="bottom"><input type="image" src="img/nyuukai/button/motteru.gif" width="171" height="114" alt="���äƤ���" border="0"></a></td>
					<td width><img src="img/nyuukai/zou_img/f_exp01.gif" alt="���ʤ�����ǯ���μ���ʸ���ɼ��"></td>
				</form>
				<form name="form2" method="post" action="fiducial_average.php">
					<td width="100" valign="bottom">
					<input type="image" src="img/nyuukai/button/mottenai.gif" width="99" height="40" alt="���äƤ��ʤ�" border="0"></a></td>
				</form>
				</tr>
				<tr align="center">
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