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
include("$inc/mypage.php");
include("$inc/fiducial.php");

// ���Ϸ����
function disp_m($i) {
	$start_m = mktime (0,0,0,$reg->start_m + ($i - 2),1,$reg->start_y);
	$m = strftime ("%m", $start_m);
	return (int)$m;
}

$_SESSION['ss_fiducial'] = new fiducial;
$reg = &$_SESSION['ss_fiducial'];


?>
<html>
<head>
<meta http-equiv="content-type" content="text/html;charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<title>������xxx��������Club������</title>
</head>

<body bgcolor="#FF0000" leftmargin="8" topmargin="8">
<div align="center">
<table width="714" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
	<tr>
		<td width="35" height="35" align="left" valign="top" bgcolor="#FFFFFF">
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
		<img src="img/nyuukai/cyuui.gif">
			<table width="600" border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td><img src="img/spacer.gif" width="600" height="5"></td>
				</tr>
			</table>
			<table width="599" border="1" cellspacing="5" cellpadding="0" bordercolor="#00CCFF">
				<tr>
					<td bgcolor="#CCFFFF"><br>
					<font size=2>
					<ul>
					<li>��ư���礭�����ŵ���������������12����ʬ���Ϥ��Ʋ�����</li>
					<br>
					<br>
					<li>��ư�����ʤ�����ƻ��������󡿥��ߤ�����ʬ�Τ����Ϥ��Ʋ�����</li>
					<br>
					(�μ���ʸ���ɼ�ˤ�̵���������Τ�ʿ���ͤ����ͤȤ��ޤ�)
					</ul>
					</font>
					</td>
				</tr>
			</table>

			<br>
			<table border="1" width ="600" cellspacing="2" bordercolor="#FFCC99">
				<tr>
					<td bgcolor="#FFFFCC" width ="300" align="center"><font color="#FF0000" size=3><b>��ư���礭�����</b></font></td>
					<td valign="top" bgcolor="#FFFFCC" width="300" align="center"><font color="#0000FF" size=3><a href="">��ư�ξ��������</a></font></td>
				</tr>
			</table>
			<table border="0" width ="600">
				<tr>
					<td><font size=2></td>
				</tr>
				<tr valign="top">
					<td rowspan="2">
						<table border="1"bordercolor="#5cb8a6">
							<tr>
								<td width = "60"><div align="center"><font size=2>�������</font></div></td>
								<td width=200><div align="center"><img src="img/character/suidou.gif" alt="�����ɤ�" width="62" height="72"></div></td>
								<td width=200><div align="center"><img src="img/character/gasoline.gif" width="62" height="72" alt="�������"></div></td>
								<td width=200><div align="center"><img src="img/character/gomi.gif" width="62" height="72" alt="����"></div></td>
							</tr>
<?
for ($i = 1; $i <= 12; $i++) {
	$energy = &$reg->energy[disp_m($i)];
?>

							<tr>
								<td><div align="center"><?=date("Y/m", mktime(0,0,0,$reg->start_m + ($i - 1), 1, $reg->start_y))?></div></td>
								<td align="center">
									<table>
										<tr>
											<td align="center"><font size=2><input type="text" name="el_use[]" size="8" <?=value($energy->el_input_use)?>>&nbsp;kwh&nbsp;<input type="text" name="el_sum[]" size="10" <?=value($energy->el_input_sum)?>>&nbsp;��</font></td>
										</tr>
									</table>
								</td>
								<td align="center">
									<table>
										<tr>
											<td align="center"><font size=2><input type="text" name="gs_use[]" size="8" <?=value($energy->gs_input_use)?>>&nbsp;m3&nbsp;<input type="text" name="gs_sum[]" size="10" <?=value($energy->gs_input_sum)?>>&nbsp;��</font></td>
										</tr>
									</table>
								</td>
								<td align="center">
									<table>
										<tr>
											<td align="center"><font size=2><input type="text" name="ol_use[]" size="8" <?=value($energy->ol_input_use)?>>&nbsp;m3&nbsp;<input type="text" name="ol_sum[]" size="10" <?=value($energy->ol_input_sum)?>>&nbsp;��</font></td>
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
			<br>
<!--
			<table border="1" width ="600" cellspacing="2" bordercolor="#FFCC99">
				<tr>
					<td bgcolor="#FFFFCC" width ="600">
					<div align="center"><font color="#FF0000" size=3>��ư���礭�����</font><br>
					</div>
					</td>
				</tr>
			</table>
			<table border="0" width ="600">
				<tr>
					<td><font size=2></font></td>
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
	$energy = &$reg->energy[disp_m($i)];
?>

							<tr>
								<td><div align="center"><?=date("Y/m", mktime(0,0,0,$reg->start_m + ($i - 1), 1, $reg->start_y))?></div></td>
								<td align="center">
									<table>
										<tr>
											<td align="center"><font size=2><input type="text" name="el_use[]" size="8" <?=value($energy->el_input_use)?>>&nbsp;kwh&nbsp;<input type="text" name="el_sum[]" size="10" <?=value($energy->el_input_sum)?>>&nbsp;��</font></td>
										</tr>
									</table>
								</td>
								<td align="center">
									<table>
										<tr>
											<td align="center"><font size=2><input type="text" name="gs_use[]" size="8" <?=value($energy->gs_input_use)?>>&nbsp;m3&nbsp;<input type="text" name="gs_sum[]" size="10" <?=value($energy->gs_input_sum)?>>&nbsp;��</font></td>
										</tr>
									</table>
								</td>
								<td align="center">
									<table>
										<tr>
											<td align="center"><font size=2><input type="text" name="ol_use[]" size="8" <?=value($energy->ol_input_use)?>>&nbsp;m3&nbsp;<input type="text" name="ol_sum[]" size="10" <?=value($energy->ol_input_sum)?>>&nbsp;��</font></td>
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
-->
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
</body>
</html>