<?
/******************************************************
' System :Eco-footprint ����ڡ���
' Content:�����������
'******************************************************/

$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/select.php");
$inc = "$top/inc";
include("$inc/regist3.php");

// ���Ϸ����
function get_m($i) {
	global $year, $month;
	return (int)(date("m", mktime (0,0,0,$month + $i,1,$year)));
}
// ����ǯ����
function get_y($i) {
	global $year, $month;
	return (int)(date("Y", mktime (0,0,0,$month + $i,1,$year)));
}

// ���å�������
session_start();
if (!isset($_SESSION['ss_regist3']))
	redirect('fiducial_exp.php');
$reg = &$_SESSION['ss_regist3'];
$year = $reg->start_y;
$month = $reg->start_m;

?>
<html>
<head>
<meta http-equiv="Pragma" content="no-cache">
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<title>������xxx��������Club������</title>
</head>

<body bgcolor="#FF0000" leftmargin="8" topmargin="8">
<form name="form1" method="post" action="fiducial_input_check.php">
<div align="center">
<table width="714" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
	<tr>
		<td width="35" align="left" valign="top" bgcolor="#FFFFFF">
			<img src="img/kado_1.gif" width="35" height="35"></td>
		<td align="center" valign="bottom"><br>
			<table border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td><div align="center"><img src="img/nyuukai/title/f_input01.gif" width="214" height="38"�� alt="���ä������Ϥ��褦"></div></td>
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
			<table border="0" cellspacing="0" cellpadding="0" height="42">
				<tr>
					<td><img src="img/nyuukai/cyuui.gif" width="93" height="42" alt="���"></td>
				</tr>
			</table>

			<table width="599" border="1" cellspacing="5" cellpadding="0" bordercolor="#00CCFF">
				<tr>
					<td bgcolor="#CCFFFF"><font size=2><br>
					<ul>
					<li>��ư���礭�����ŵ���������������12����ʬ���Ϥ��Ʋ�����</li>
					<br>
					<br>
					<li>��ư������������ƻ��������󡿥��ߤ�����ʬ�Τ����Ϥ��Ʋ�����</li>
					<br>
					<br>
					<li>���������������ۤ�<font color="blue">�����ʾ���"����"</font>��<font color="red">̤���Ѥξ���"0"</font>�����Ϥ��Ʋ�����</li>
					<br>
					(�μ���ʸ���ɼ�ˤ�̵��������Τ�ʿ���ͤ����ͤȤ��ޤ�)
					<br>
					<br>
					<li>����ޤ侮���������줺�ˡ������Τ����Ϥ��Ʋ�������</li>
					<br>
					</ul>
					</font></td>
				</tr>
			</table>
			<br>
			<table border="1" width ="600" cellspacing="2" bordercolor="#FFCC99">
				<tr>
					<td valign="top" bgcolor="#FFFFCC" width="600">
					<div align="center"><font color="#0000FF" size=3>��ǯ����ư�ξ��������</font></div></td>
				</tr>
			</table>
			<table>
				<tr>
					<td><font size=2>���������ʬ���μ���ʸ���ɼ�ˡ�<font color="blue">��ƻ���μ���ʸ���ɼ�ˤι�פ��顢��껻���ƣ�����ʬ</font>�ˤΤ����Ϥ��Ʋ�������</font></td>
				</tr>
				<tr>
					<td width="600">
						<table border="1" bordercolor="#5cb8a6" align="center">
							<tr>
								<td width=200><div align="center"><img src="img/character/suidou.gif" alt="�����ɤ�" width="62" height="72"></div></td>
								<td width=200><div align="center"><img src="img/character/gasoline.gif" width="62" height="72" alt="�������"></div></td>
								<td width=200><div align="center"><img src="img/character/gomi.gif" width="62" height="72" alt="����"></div></td>
							</tr>
							<tr>
								<td align="center">
									<table>
										<tr>
											<td align="center"><input type="text" name="wt_use" size="10" maxlength=4 <?=value($reg->wt_input_use)?>>&nbsp;m3</td>
											<td><input type="text" name="wt_sum" size="10" maxlength=6 <?=value($reg->wt_input_sum)?>>&nbsp;��</td>
										</tr>
									</table>
								</td>
								<td align="center">
									<table>
										<tr>
										<td align="center"><input type="text" name="gl_use" size="10" maxlength=4 <?=value($reg->gl_input_use)?>>&nbsp;L</td>
										<td><input type="text" name="gl_sum" size="10" maxlength=6 <?=value($reg->gl_input_sum)?>>&nbsp;��</td>
										</tr>
									</table>
								</td>
								<td align="center"><input type="text" name="gm_use" size="10" maxlength=4 <?=value($reg->gm_input_use)?>>&nbsp;kg</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
			<br>
			<table border="1" width ="600" cellspacing="2" bordercolor="#FFCC99">
				<tr>
					<td bgcolor="#FFFFCC" width ="600">
					<div align="center"><font color="#FF0000" size=3>��ǯ����ư���礭�����</font><br>
					</div>
					</td>
				</tr>
			</table>
			<table border="0" width ="600">
				<tr>
					<td><font size=2>����ǯʬ���μ���ʸ���ɼ�ˤ��Ȥ����Ϥ��Ʋ�������</font></td>
				</tr>
				<tr valign="top">
					<td rowspan="2">
						<table border="1"bordercolor="#5cb8a6">
							<tr>
								<td width = "60"><div align="center"><font size=2>�������</font></div></td>
								<td width=180><div align="center"><img src="img/character/denki.gif" alt="�Ǥ�" width="62" height="72"></div></td>
								<td width=180><div align="center"><img src="img/character/gas.gif" alt="����" width="62" height="72"></div></td>
								<td width=180><div align="center"><img src="img/character/touyu.gif" width="62" height="72" alt="�Ȥ���"></div></td>
							</tr>
<?
for ($i = 1; $i <= 12; $i++) {
	$energy = &$reg->energy[get_m($i)];
?>

							<tr>
								<td><div align="center"><?=get_y($i)."/".sprintf("%02d",get_m($i))?></div></td>
								<td align="center">
									<table>
										<tr>
											<td align="center"><font size=2><input type="text" name="el_use[]" size="8" maxlength=4 <?=value($energy->el_input_use)?>>&nbsp;kwh&nbsp;<input type="text" name="el_sum[]" size="10" maxlength=6 <?=value($energy->el_input_sum)?>>&nbsp;��</font></td>
										</tr>
									</table>
								</td>
								<td align="center">
									<table>
										<tr>
											<td align="center"><font size=2><input type="text" name="gs_use[]" size="8" maxlength=4 <?=value($energy->gs_input_use)?>>&nbsp;m3&nbsp;<input type="text" name="gs_sum[]" size="10" maxlength=6 <?=value($energy->gs_input_sum)?>>&nbsp;��</font></td>
										</tr>
									</table>
								</td>
								<td align="center">
									<table>
										<tr>
											<td align="center"><font size=2><input type="text" name="ol_use[]" size="8" maxlength=4 <?=value($energy->ol_input_use)?>>&nbsp;L&nbsp;<input type="text" name="ol_sum[]" size="10" maxlength=6 <?=value($energy->ol_input_sum)?>>&nbsp;��</font></td>
										</tr>
									</table>
								</td>
							</tr>
<?
}
?>
						</table>
					</td>
				</tr>
			</table>
			<table>
				<tr valign="top">
					<td width="280" valign="bottom"><div align="right"><img src="img/nyuukai/zou_img/f_input.gif" alt="�ɡ����ä����ʤ��ä������Ϥ��褦��" width="200" height="213"></div></td>
				</tr>
			</table>
			<hr width="95%" size="1"></td>

		</tr>

		<td width="35" align="left" valign="bottom">
		<img src="img/kado_3.gif" width="35" height="35"></td>
		<td align="center" valign="bottom">
			<table border="0" cellspacing="0" cellpadding="0">
				<tr align="center">


					<td><a href="fiducial_exp.php"><img src="img/nyuukai/button/back.gif" width="99" height="40" alt="���" border="0"></a></td>
					<td><input type="image" src="img/nyuukai/button/ok.gif" width="99" height="40" alt="�����OK" border="0"></a></td>
				</tr>
			</table></td>
		<td width="35" height="35" align="right" valign="bottom">
		<img src="img/kado_4.gif" width="35" height="35"></td>
	</tr>
</table>

</div>
</form>
</body>
</html>