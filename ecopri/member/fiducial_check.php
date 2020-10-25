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
include("$inc/decode.php");
$inc = "$top/inc";
include("$inc/regist3.php");

// ���Ϸ����
function get_m($i) {
	global $ym;
	return (int)(get_datepart("M", add_date($ym, 0, $i, 0)));
}
// ����ǯ����
function get_y($i) {
	global $ym;
	return (int)(get_datepart("Y", add_date($ym, 0, $i, 0)));
}

// ��²�Ϳ�ɽ��
function info_family($code) {
	if ($code < 6)
		return "$code ��";
	elseif ($code >= 6)
		return "6�Ͱʾ�";
}
// �����ɽ��
function info_boiler($eng, $code) {
	switch($eng) {
	case 'el':
		if ($code == 1 || $code ==2|| $code ==6)
			return '�ŵ���';
		else
			return '--';
	case 'gs':
		if ($code == 3 || $code ==4|| $code ==7)
			return '������';
		else
			return '--';
	case 'ol':
		if ($code == 5)
			return '������';
		else
			return '--';
	}
}

// ����/�ʤ�ɽ��
function decode_flag($code) {
	if ($code == 1)
		return '����';
	else
		return '�ʤ�';
}

// ���å�������
session_start();
if (!isset($_SESSION['ss_seq_no']))
	redirect('login.php');

$_SESSION['ss_regist3'] = new mm_regist3;
$reg = &$_SESSION['ss_regist3'];

$reg->set_start_ym();		// ɽ������ǯ���start_y,start_m�˥��å�
$reg->set_energy_cd();	// ���ͥ륮�������ɥ��å�
$reg->set_family_num();	// ��²�Ϳ���family_num�˥��å�
$reg->set_price_unit();	// ñ�����å�
$reg->set_all_average();	// ʿ���ͥ��å�

$ym = $reg->start_ym;
//$year = $reg->start_y;
//$month = $reg->start_m;
$reg->wt_use = $reg->wt_use_av;
$reg->wt_use_flag = 4;
$reg->wt_sum = (int) ($reg->wt_use_av * $reg->wt_price_unit);
$reg->wt_sum_flag = 4;
$reg->gl_use = $reg->gl_use_av;
$reg->gl_use_flag = 4;
$reg->gl_sum = (int) ($reg->gl_use_av * $reg->gl_price_unit);
$reg->gl_sum_flag = 4;
$reg->gm_use = $reg->gm_use_av;
$reg->gm_use_flag = 4;
for ($i = 1; $i <= 12; $i++) {
	$energy = &$reg->energy[get_m($i)];
	$energy->el_use =$energy->el_use_av;
	$energy->el_use_flag = 4;
	$energy->el_sum = $energy->el_use * $reg->el_price_unit;
	$energy->el_sum_flag = 4;
	$energy->gs_use = $energy->gs_use_av;
	$energy->gs_use_flag = 4;
	$energy->gs_sum = $energy->gs_use * $reg->gs_price_unit;
	$energy->gs_sum_flag = 4;
	$energy->ol_use = $energy->ol_use_av;
	$energy->ol_use_flag = 4;
	$energy->ol_sum = $energy->ol_use * $reg->ol_price_unit;
	$energy->ol_sum_flag = 4;
}

?>
<html>
<head>
<title>������xxx��������Club������</title>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
</head>

<body bgcolor="#FF0000" leftmargin="8" topmargin="8">

<div align="center">
<table width="714" height="90%" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
	<tr>
		<td width="35" align="left" valign="top" bgcolor="#FFFFFF"><img src="img/kado_1.gif" width="35" height="35"></td>
		<td align="center" valign="top" colspan=2>
		<br>

			<table border="0" cellspacing="0" cellpadding="0">
				<tr align="center">
					<td><font size="5"><?=$reg->mb_name1?>�Ȥο��������</font></td>
				</tr>
				<tr>
					<td><br></td>
				</tr>
				<tr align="center">
					<td>
					<font size=2 color="blue">
					���ο�������̤ϡ������Ϥ���ĺ�����ƾ�����˻��Ф��������̤Ǥ���<br><br>
					</font>
					</td>
				</tr>
			</table>
		</td>
		<td width="35" align="right" valign="top">
		<img src="img/kado_2.gif" width="35" height="35"></td>
	</tr>

	<tr>
		<td></td>
		<td align="center" valign="top" colspan=2>
		<hr width="95%" size="1">
		</td>
		<br>
	</tr>
	<tr>
		<td></td>
		<td></td>
		<td></td>
	</tr>
	<tr>
		<td></td>
		<td colspan=2>
			<table border="1" width="650" bordercolor="#33ff00" cellpadding="0" cellspacing="1">
				<tr align="center" bgcolor="#eeffee">
					<td width=110><font size=2><b>�ޥ󥹥꡼�ǡ���<br></b></font><font size=1>��ñ�̤�<br>ȯ������ǡ���</font></td>
					<td colspan="2" width=180 valign="center">
						<table border=0 cellspacing=0 cellpadding=0 width=100%>
							<tr>
								<td rowspan=5 width=65 align="center"><img src="img/character/denki.gif" border="0" width="62" height="72"></td>
								<td width=115 height=5></td>
							</tr>
							<tr>
								<td><font size=2><font color="darkslategray">Ʊ��Ϳ��� </font><font color="royalblue"><?=info_family($reg->family_num)?></font></font></td>
							</tr>
							<tr>
								<td><font size=2><font color="darkslategray">�ϰ衧 </font><font color="royalblue"><?=decode_area2($reg->area_cd)?></font></font></td>
							</tr>
							<tr>
								<td><font size=2><font color="darkslategray">���� </font><font color="royalblue"><?=info_boiler('el',$reg->boiler_cd)?></font></font></td>
							</tr>
							<tr>
								<td height=5></td>
							</tr>
						</table>
					</td>
					<td colspan="2" width=180>
						<table border=0 cellspacing=0 cellpadding=0 width=100%>
							<tr>
								<td rowspan=7 width=65 align="center" valign="center"><img src="img/character/gas.gif" border="0" width="62" height="72"></td>
								<td width=115 height=5></td>
							</tr>
							<tr>
								<td><font size=2><font color="darkslategray">Ʊ��Ϳ��� </font><font color="royalblue"><?=info_family($reg->family_num)?></font></font></td>
							</tr>
							<tr>
								<td><font size=2><font color="darkslategray">�ϰ衧 </font><font color="royalblue"><?=decode_area2($reg->area_cd)?></font></font></td>
							</tr>
							<tr>
								<td><font size=2><font color="darkslategray">����� </font><font color="royalblue"><?=decode_gas_kind($reg->gs_kind)?></font></td>
							</tr>
<?
if ($reg->gs_kind == 1) {
?>
							<tr>
								<td><font size=2><font color="darkslategray">���������ס� </font><font color="royalblue"><?=decode_gas_type($reg->gs_type)?></font></td>
							</tr>
<?
}
?>
							<tr>
								<td><font size=2><font color="darkslategray">���� </font><font color="royalblue"><?=info_boiler('gs',$reg->boiler_cd)?></font></font></td>
							</tr>
							<tr>
								<td height=5></td>
							</tr>
						</table>
					</td>
					<td colspan="2" width=180>
						<table border=0 cellspacing=0 cellpadding=0 width=100%>
							<tr>
								<td rowspan=4 width=65 align="center" valign="center"><img src="img/character/suidou.gif" border="0" width="62" height="72"></td>
								<td width=115 height=5></td>
							</tr>
							<tr>
								<td><font size=2><font color="darkslategray">Ʊ��Ϳ��� </font><font color="royalblue"><?=info_family($reg->family_num)?></font></font></td>
							</tr>
							<tr>
								<td><font size=2><font color="darkslategray">�ϰ衧 </font><font color="royalblue"><?=decode_area2($reg->area_cd)?></font></font></td>
							</tr>
							<tr>
								<td height=5></td>
							</tr>
						</table>
					</td>
				</tr>
<?
for ($i = 1; $i <= 12; $i++) {
	$energy = &$reg->energy[get_m($i)];
?>

				<tr align="right">
					<td width=110 align="center"><?=get_y($i)."/".sprintf("%02d", get_m($i))?></td>
					<td width=90><font size=2><font color="red"><?=number_format((int)($energy->el_use))?></font>&nbsp;kmh&nbsp;</font></td>
					<td width=90><font size=2><font color="red"><?=number_format((int)($energy->el_sum))?></font>&nbsp;��&nbsp;</font></td>
					<td width=90><font size=2><font color="red"><?=number_format((int)($energy->gs_use))?></font>&nbsp;m3&nbsp;</font></td>
					<td width=90><font size=2><font color="red"><?=number_format((int)($energy->gs_sum))?></font>&nbsp;��&nbsp;</font></td>
					<td width=90><font size=2><font color="red"><?=number_format((int)($reg->wt_use))?></font>&nbsp;m3&nbsp;</font></td>
					<td width=90><font size=2><font color="red"><?=number_format((int)($reg->wt_sum))?></font>&nbsp;��&nbsp;</font></td>
				</tr>
<?
}
?>
			</table>
			<br>
			<div align="right">
			</div>
		</td>
	</tr>
	<tr>
		<td></td>
		<td></td>
		<td></td>
	</tr>
	<tr>
		<td></td>
		<td colspan=2>
			<table border="1" width="650" bordercolor="#cc66ff" cellpadding="0" cellspacing="1">
				<tr align="center" bgcolor="#ffeeff">
					<td width=110><font size=2><b>�ǥ��꡼�ǡ���<br></b></font><font size=1>��ñ�̤�<br>ȯ������>�ǡ���</font></td>
					<td colspan="2" width=180 valign="center">
						<table border=0 cellspacing=0 cellpadding=0 width=100%>
							<tr>
								<td rowspan=3 width=65 align="center" valign="center"><img src="img/character/gomi_n.gif" border="0" width="62" height="72"></td>
								<td width=115 height=5></td>
							</tr>
							<tr>
								<td><font size=2><font color="darkslategray">Ʊ��Ϳ��� </font><font color="royalblue"><?=info_family($reg->family_num)?></font></font></td>
							</tr>
							<tr>
								<td height=5></td>
							</tr>
						</table>
					</td>
					<td colspan="2" width=180 valign="center">
						<table border=0 cellspacing=0 cellpadding=0 width=100%>
							<tr>
								<td rowspan=6 width=65 align="center" valign="center"><img src="img/character/touyu.gif" border="0" width="62" height="72"></td>
								<td width=115 height=5></td>
							</tr>
							<tr>
								<td><font size=2><font color="darkslategray">Ʊ��Ϳ��� </font><font color="royalblue"><?=info_family($reg->family_num)?></font></font></td>
							</tr>
							<tr>
								<td><font size=2><font color="darkslategray">�ϰ衧 </font><font color="royalblue"><?=decode_area2($reg->area_cd)?></font></font></td>
							</tr>
							<tr>
								<td><font size=2><font color="darkslategray">���� </font><font color="royalblue"><?=info_boiler('ol',$reg->boiler_cd)?></font></font></td>
							</tr>
							<tr>
								<td><font size=2><font color="darkslategray">�������ѡ� </font><font color="royalblue"><?=decode_flag($reg->ol_flag)?></font></font></td>
							</tr>
							<tr>
								<td height=5></td>
							</tr>
						</table>
					</td>
					<td colspan="2" width=180 valign="center">
						<table border=0 cellspacing=0 cellpadding=0 width=100%>
							<tr>
								<td rowspan=5 width=65 align="center" valign="center"><img src="img/character/gasoline_n.gif" border="0" width="62" height="72"></td>
								<td width=115 height=5></td>
							</tr>
							<tr>
								<td><font size=2><font color="darkslategray">Ʊ��Ϳ��� </font><font color="royalblue"><?=info_family($reg->family_num)?></font></font></td>
							</tr>
							<tr>
								<td><font size=2><font color="darkslategray">�ϰ衧 </font><font color="royalblue"><?=decode_area2($reg->area_cd)?></font></font></td>
							</tr>
							<tr>
								<td><font size=2><font color="darkslategray">�ֽ�ͭ = </font><font color="royalblue"><?=decode_flag($reg->car_flag)?></font></td>
							</tr>
							<tr>
								<td height=5></td>
							</tr>
						</table>
					</td>

				</tr>
<?
for ($i = 1; $i <= 12; $i++) {
	$energy = &$reg->energy[get_m($i)];
?>

				<tr align="right">
					<td width=110 align="center"><?=get_y($i)."/".sprintf("%02d", get_m($i))?></td>
					<td width=180 colspan=2><font size=2><font color="red"><?=number_format((int)($reg->gm_use_av))?></font>&nbsp;kg&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</font></td>
					<td width=90><font size=2><font color="red"><?=number_format((int)($energy->ol_use))?></font>&nbsp;L&nbsp;</font></td>
					<td width=90><font size=2><font color="red"><?=number_format((int)($energy->ol_sum))?></font>&nbsp;��&nbsp;</font></td>
					<td width=90><font size=2><font color="red"><?=number_format((int)($reg->gl_use_av))?></font>&nbsp;L&nbsp;</font></td>
					<td width=90><font size=2><font color="red"><?=number_format((int)($reg->gl_sum))?></font>&nbsp;��&nbsp;</font></td>
				</tr>
<?
}
?>
			</table>
			<br>
		</td>
	</tr>
	<tr>
		<td></td>
		<td colspan=2>
			<br>
			<table border=1 cellspacing=0 cellpadding=0 bgcolor="#FFFFE0">
				<tr>
					<td align="center">
						<table border=0 cellspacing=6 cellpadding=0 width=97%>
							<tr>
								<td align="center">
									<font size=2>
									<b>���줬���ʤ��ο�������̤Ǥ�</b><br>
								</td>
							</tr>
							<tr>
								<td>
									<font size=2 color="#003333">
									��������̤ϡ����ʤ��βȤΣ�������ʬ�Υ��ͥ륮�������̤���ꤷ�ƻ��Ф������ͤǤ���
									���줫��������Ϥ��Ƥ�������<font color="#CC3333">���ʤ��βȤλ�����</font>��
									����<font color="#CC3333">���������</font>����Ӥ��뤳�Ȥǡ�
									���ʤ��βȤ�<font color="#CC3333">Eco������������</font>���Τ뤳�Ȥ��Ǥ��ޤ���<br>
									�ޤ������ο�������̤��ؤ��ơ����ʤ��βȤ�<font color="#CC3333">��ǯʬ�μºݤλ�����</font>
									�ʲ�ǯʬ���ŵ�����������ƻ���μ������ơˤ����Ϥ��Ƥ��������ȡ�
									<font color="#CC3333">������Τ��������</font>���狼�ꡢ<font color="#CC3333">��������Υҥ��</font>�Ȥ�����Ω�Ƥ뤳�Ȥ��Ǥ��ޤ���

									</font>
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
			<table border=0 cellspacing=0 cellpadding=0>
				<tr>
					<td height=2></td>
				<tr>
					<td>
						<table border=0 cellspacing=6 cellpadding=0 width=97%>
							<tr>
								<td valign="top"><font size=2 color="teal">��</font></td>
								<td><font size=2 color="teal">
								 ���ߤȥ�������ɬ�����ϤǤϤ���ޤ��󤬡����Ϥ��뤳�Ȥǡ�����˺٤������CO2�ӽ��̤��İ����뤳�Ȥ��Ǥ��ޤ���
								 ���ߤνŤ���פä��ꡢ���������μ���ο��������Ϥ���ΤϤ���ä����Ѥ����Τ�ޤ��󤬡����ʤ��βȤ�Eco�����ͤ��뤿��ˡ�
								 �������Ϥ��ƤߤƤ���������<br><br>
								</font>
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>

		<hr width="95%" size="1"></td>
		<td><br></td>
	</tr>
	<tr>
		<td width="35" align="left" valign="bottom"><img src="img/kado_3.gif" width="35" height="35"></td>
		<td align="center" valign="bottom" colspan=2>

			<table border="0" cellspacing="0" cellpadding="0">
				<tr align="center">
					<td><a href="fiducial_end.php"><img src="img/nyuukai/button/ok.gif" width="99" height="40" border="0" alt=""></a></td>
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
