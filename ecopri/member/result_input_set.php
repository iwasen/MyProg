<?
/******************************************************
' System :Eco-footprint ����ڡ���
' Content:
'******************************************************/

$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");
include("$inc/decode.php");
$inc = "$top/inc";
include("$inc/mypage.php");

// �������ѥ����å�
function check_gas($code) {
	if ($code == 3)
		echo "<font color='gray'>̤����</font>";
	else
		echo "<font color='red'>���Ϥ���</font>";
}

// ��������ɽ��
function decode_ol($code) {
	if ($code == '')
		return "̤����";
	elseif ($code == 1)
		return "���Ѥ���";
	elseif ($code == 0)
		return "���Ѥʤ�";
}
// �������������
$sql = "SELECT mb_boiler_cd,mb_gas_kind_cd,mb_gas_type_cd,mb_water_month,mb_gcar_num,mb_dcar_num,mb_bike_num,mb_ol_flag,mb_gm_flag,mb_gl_flag"
	. " FROM t_member where mb_seq_no=" . $_SESSION['ss_seq_no'];
$result = db_exec($sql);
if (pg_numrows($result))
	$fetch = pg_fetch_object($result, 0);

?>
<html>
<head>
<meta http-equiv="Pragma" content="no-cache">
<meta http-equiv="content-type" content="text/html;charset=EUC-JP">
<title>������xxx��������Club������</title>
</head>

<body bgcolor="#FF0000" leftmargin="8" topmargin="8">
<div align="center">
<form name="form1" method="post" action="result_input_set_check.php">
<table width="714" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
	<tr height=80>
		<td width="35" align="left" valign="top"><img src="img/kado_1.gif" width="35" height="35"></td>
		<td width=644 align="center" valign="bottom"><img src="img/input_set/titleA_01.gif"></td>
		<td width="35" align="right" valign="top"><img src="img/kado_2.gif" width="35" height="35"></td>
	</tr>
	<tr>
		<td colspan=3 align="center"><hr size=1 width=90%></td>
	</tr>
	<tr>
		<td colspan=3 align="center">
			<table border=0 cellspacing=0 cellpadding=0 width=520>
				<tr height=30>
					<td valign="top"><font size=2 color="#000066">
					<br>
					���Ϥ򳫻Ϥ������ˡ����줫�����Ū�����Ϥ��Ƥ����إ��ͥ륮�����ܡ٤����ꤷ��ĺ���ޤ���<br>
					<br>
					</font></td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td colspan=3 align="center">
			<table border=0 cellspacing=0 cellpadding=0 bgcolor="#bbccff" width=540 height=200>
				<tr height=90%>
					<td align="center" valign="middle" valign="middle">
						<table border=0 cellspacing=0 cellpadding=0 bgcolor="#bbccff" width=500>
							<tr>
								<td align="center" height=40 valign="bottom"><img src="img/input_set/titleB_01.gif" width=250 height=29></td>
							</tr>
							<tr height=30>
								<td align="center" valign="middle"><font color="navy"><font size=2>������Ͽ����Ͽ�������ͥ륮�������ƤǤ�</font></font></td>
							</tr>
							<tr>
								<td>
									<table border=1 bordercolor="darkcyan" cellspacing=0 cellpadding=3 bgcolor="white" width=500>
										<tr>
											<td bgcolor="#ffffcc"><font size=2>������</font></td>
											<td><font size=2><?=decode_gas_kind($fetch->mb_gas_kind_cd)?>
												<?=$fetch->mb_gas_type_cd != '' ?'��' . decode_gas_type($fetch->mb_gas_type_cd) . '��':''?></font></td>
										</tr>
										<tr>
											<td bgcolor="#ffffcc"><font size=2>�����</font></td>
											<td><font size=2><?=decode_boiler($fetch->mb_boiler_cd)?></font></td>
										</tr>
										<tr>
											<td bgcolor="#ffffcc"><font size=2>��ƻ�������</font></td>
											<td><font size=2><?=decode_water($fetch->mb_water_month)?></font></td>
										</tr>
										<tr>
											<td bgcolor="#ffffcc"><font size=2>��������</font></td>
											<td><font size=2><?=$fetch->mb_gcar_num?>��</font></td>
										</tr>
										<tr>
											<td bgcolor="#ffffcc"><font size=2>�ǥ��������</font></td>
											<td><font size=2><?=$fetch->mb_dcar_num?>��</font></td>
										</tr>
										<tr>
											<td bgcolor="#ffffcc"><font size=2>�Х���</font></td>
											<td><font size=2><?=$fetch->mb_bike_num?>��</font></td>
										</tr>
										<tr>
											<td bgcolor="#ffffcc"><font size=2>����</font></td>
											<td><font size=2><?=decode_ol($fetch->mb_ol_flag)?></font></td>
										</tr>
									</table>
									<table width=500>
										<tr>
											<td align="right"><font size=2 color="navy">���������Ƥ�<a href="member_menu.php">����Ͽ������ѹ���</a>�����ѹ��Ǥ��ޤ���</font></td>
										</tr>
									</table>
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td colspan=3 align="center" height=50 valign="middle">
		<font color="palegreen">
		��&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		��&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		��&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		��&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		��&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		��&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		��&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		��&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		��&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		</font>
		</td>
	</tr>
	<tr>
		<td colspan=3 align="center">
			<table border=0 cellspacing=0 cellpadding=0 bgcolor="#bfddf2" width=540 height=200>
				<tr>
					<td align="center" valign="middle">
						<table border=0 cellspacing=0 cellpadding=0 bgcolor="#bfddf2" width=500>
							<tr>
								<td align="center" height=40 valign="top"><img src="img/input_set/titleB_02.gif" width=250 height=29></td>
							</tr>
							<tr>
								<td align="center">
									<table width=400>
										<tr>
											<td>
												<font size=2 color="darkslategray">
												���ŵ�����������ƻ�θ���ɽ��ߤ����Ϥ���ĺ���ǡ����Ǥ���<br>
												��§�Ȥ��ơ�������������Ϥ򤪴ꤤ���Ƥ��ޤ���<br><br>
												</font>
											</td>
										</tr>
									</table>
								</td>
							</tr>
							<tr>
								<td align="center">
									<table border=1 bordercolor="darkcyan" cellspacing=0 cellpadding=3 bgcolor="white" width=450>
										<tr>
											<td width=35% align="center" bgcolor="#ffffcc"><img src="img/input_set/denki.gif" alt="�ŵ�"></td>
											<td width=65% align="center" bgcolor="#e9e9e9"><font size=2 color="red">���Ϥ���</font></td>
										</tr>
										<tr>
											<input type="hidden" name="gas_kind" <?=value($fetch->mb_gas_kind_cd)?>>
											<td align="center" bgcolor="#ffffcc"><img src="img/input_set/gas.gif" alt="����"></td>
											<td align="center" bgcolor="#e9e9e9"><font size=2><?=check_gas($fetch->mb_gas_kind_cd)?></font></td>
										</tr>
										<tr>
											<td align="center" bgcolor="#ffffcc"><img src="img/input_set/suidou.gif" alt="����"></td>
											<td align="center" bgcolor="#e9e9e9"><font size=2 color="red">���Ϥ���</font></td>
										</tr>
									</table>
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
			<table border=0 cellspacing=0 cellpadding=0 bgcolor="#f8f17d" width=540 height=250>
				<tr>
					<td align="center" valign="middle">
						<table border=0 cellspacing=0 cellpadding=0 bgcolor="#f8f17d" width=500>
							<tr>
								<td align="center" height=40 valign="top"><img src="img/input_set/titleB_03.gif" width=250 height=29></td>
							</tr>
							<tr>
								<td align="center">
									<table width=400>
										<tr>
											<td>
												<font size=2 color="darkslategray">
												���ʤ��β���Ǥ����ӽ��̤򻻽Ф��뤿���ɬ�פʾ���Ǥ���<br>
												���Ϥ��뤫�ɤ����ϡ���ͳ����Ǥ���<br>
												��³Ū�����Ϥ��Ƥ������������ϡ����Ϥ���פ������ѹ����Ƥ���������<br>
												�ޤ���<font color="#CC3333">���������Ѥ��Ƥ��������������������Ϥ򤪴ꤤ���Ƥ��ޤ���</font><br><br>
												</font>
											</td>
										</tr>
									</table>
								</td>
							</tr>
							<tr>
								<td align="center">
									<table border=1 bordercolor="darkcyan" cellspacing=0 cellpadding=3 bgcolor="white" width=450>
<?
if ($fetch->mb_ol_flag != '') {
?>
										<tr>
											<td width=35% align="center" bgcolor="#ffffcc"><img src="img/input_set/touyu.gif" alt="����"></td>
											<td align="center" bgcolor="#e9e9e9"><font size=2>
												<?=$fetch->mb_ol_flag==1?'<font color=\'red\'>���Ϥ���</font>':'<font color=\'gray\'>̤����</font>'?>
											</font></td>
											<input type="hidden" name="ol_flag" value=<?=$fetch->mb_ol_flag == 1?1:0?>>
										</tr>
<?
} else {
?>
										<tr>
											<td width=35% align="center" bgcolor="#ffffcc"><img src="img/input_set/touyu.gif" alt="����"></td>
											<td width=65% align="center" bgcolor="#e9e9e9">
											<select name="ol_flag">
											<option <?=value_selected('',$fetch->mb_ol_flag)?>>���Ϥ��ʤ�</option>
											<option <?=value_selected(1, $fetch->mb_ol_flag)?>>���Ϥ���</option>
											</select>
											</td>
										</tr>
<?
}
?>
										<tr>
											<td width=35% align="center" bgcolor="#ffffcc"><img src="img/input_set/gomi.gif" alt="����"></td>
											<td width=65% align="center">
											<select name="gm_flag">
											<option <?=value_selected('',$fetch->mb_gm_flag)?>>���Ϥ��ʤ�</option>
											<option <?=value_selected(1, $fetch->mb_gm_flag)?>>���Ϥ���</option>
											</select>
											</td>
										</tr>
<?
if ($fetch->mb_gcar_num <= 0 && $fetch->mb_dcar_num <= 0 && $fetch->mb_bike_num <= 0) {
?>
										<tr>
											<input type="hidden" name="gl_flag" value=0>
											<td align="center" bgcolor="#ffffcc"><img src="img/input_set/gasoline.gif" alt="�������"></td>
											<td align="center"><font size=2 color="gray">̤����</font></td>
										</tr>
<?
} else {
?>
										<tr>
											<td align="center" bgcolor="#ffffcc"><img src="img/input_set/gasoline.gif" alt="�������"></td>
											<td align="center">
											<select name="gl_flag">
											<option <?=value_selected('',$fetch->mb_gl_flag)?>>���Ϥ��ʤ�</option>
											<option <?=value_selected(1, $fetch->mb_gl_flag)?>>���Ϥ���</option>
											</select>
											</td>
										</tr>
<?
}
?>
									</table>
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr><td colspan=3 height=20></td></tr>
	<tr>
		<td align="center" colspan=3><hr width=95% size=1></td>
	</tr>
	<tr>
		<td width="35" align="left" valign="bottom"><img src="img/kado_3.gif" width="35" height="35"></td>
		<td width=644 align="center" valign="top">
			<a href="main.php"><img src="img/button/input/main.gif" border=0 width=79 height=44 alt="�ᥤ���"></a>
			<input type="image" src="img/button/input/settei.gif" alt="���ꤹ��" border=0 width=99 height=44>
			<a href='Javascript:onClick=history.back()'><img src="img/button/input/back1.gif" border=0 width=72 height=44 alt="���"></a>
		</td>
		<td width="35" height="35" align="right" valign="bottom"><img src="img/kado_4.gif" width="35" height="35"></td>
	</tr>
</table>
</form>
</div>
</body>
</html>