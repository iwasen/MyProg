<?
/******************************************************
' System :Eco-footprint ����ڡ���
' Content:
'******************************************************/

$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/get_form.php");
$inc = "$top/inc";
include("$inc/mypage.php");

function decode_input($code) {
	if ($code == 0)
		echo "<font color='gray'>���Ϥ��ʤ�</font>";
	elseif ($code == 1)
		echo "<font color='red'>���Ϥ���</font>";
}

// �ե����फ��ǡ�������
$gas_kind = get_number($gas_kind);
if ($gas_kind == '3')
	$gas_flag = 0;
else
	$gas_flag = 1;
$ol_flag = get_number($ol_flag);
if ($ol_flag == '')
	$ol_flag = 0;
$gm_flag = get_number($gm_flag);
if ($gm_flag == '')
	$gm_flag = 0;
$gl_flag = get_number($gl_flag);
if ($gl_flag == '')
	$gl_flag = 0;

$sql = "UPDATE t_member SET mb_ol_flag=$ol_flag,mb_gm_flag=$gm_flag,mb_gl_flag=$gl_flag WHERE mb_seq_no=" . $_SESSION['ss_seq_no'];
db_exec($sql);
?>
<html>
<head>
<meta http-equiv="Pragma" content="no-cache">
<meta http-equiv="content-type" content="text/html;charset=EUC-JP">
<title>������xxx��������Club������</title>
</head>

<body bgcolor="#FF0000" leftmargin="8" topmargin="8">
<div align="center">
<form name="form1" method="post" action="result_input_check.php">
<table width="714" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
	<tr height=80>
		<td width="35" align="left" valign="top"><img src="img/kado_1.gif" width="35" height="35"></td>
		<td width=644 align="center" valign="bottom"><img src="img/input_set/titleA_02.gif"></td>
		<td width="35" align="right" valign="top"><img src="img/kado_2.gif" width="35" height="35"></td>
	</tr>
	<tr>
		<td colspan=3 align="center" height=30><hr size=1 width=90%></td>
	</tr>
	<tr>
		<td colspan=3 align="center">
			<table border=0 cellspacing=0 cellpadding=0 bgcolor="#bfddf2" width=540 height=160>
				<tr>
					<td align="center" valign="middle">
						<table border=0 cellspacing=0 cellpadding=0 bgcolor="#bfddf2" width=500>
							<tr>
								<td align="center" height=40 valign="top"><img src="img/input_set/titleB_02.gif" width=250 height=29></td>
							</tr>
<!--
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
-->
							<tr>
								<td align="center">
									<table border=1 bordercolor="darkcyan" cellspacing=0 cellpadding=3 bgcolor="white" width=450>
										<tr>
											<td width=35% align="center" bgcolor="#ffffcc"><img src="img/input_set/denki.gif" alt="�ŵ�"></td>
											<td width=65% align="center"><font size=2 color="red">���Ϥ���</font></td>
										</tr>
										<tr>
											<td align="center" bgcolor="#ffffcc"><img src="img/input_set/gas.gif" alt="����"></td>
											<td width=65% align="center"><font size=2><?=decode_input($gas_flag)?></font></td>
										</tr>
										<tr>
											<td align="center" bgcolor="#ffffcc"><img src="img/input_set/suidou.gif" alt="����"></td>
											<td align="center"><font size=2 color="red">���Ϥ���</font></td>
										</tr>
									</table>
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
			<table width="540" style="position:absolute;margin-top:-5%;margin-left:70%;">
				<tr>
					<td><img src="img/input_set/02_1.gif" border=0 width=160 height=210 alt="��ǧ���Ƥ�"></td>
				</tr>
			</table>
			<table border=0 cellspacing=0 cellpadding=0 bgcolor="#f8f17d" width=540 height=160>
				<tr>
					<td align="center" valign="middle">
						<table border=0 cellspacing=0 cellpadding=0 bgcolor="#f8f17d" width=500>
							<tr>
								<td align="center" height=40 valign="top"><img src="img/input_set/titleB_03.gif" width=250 height=29></td>
							</tr>
<!--
							<tr>
								<td align="center">
									<table width=400>
										<tr>
											<td>
												<font size=2 color="darkslategray">
												���Ϥ��뤫�ɤ����ϡ��Ʋ���μ�ͳ����Ǥ���<br>
												���ʤ��β���Ǥ�CO2���ӽ��̤򻻽Ф��뤿���ɬ�פʾ���Ǥ���<br>
												��³Ū�����Ϥ��Ƥ������������ϡ����Ϥ���פ������ѹ����Ƥ���������<br>
												�ޤ������������Ѥ��Ƥ��������������������Ϥ򤪴ꤤ���Ƥ��ޤ���<br><br>
												</font>
											</td>
										</tr>
									</table>
								</td>
							</tr>
-->							<tr>
								<td align="center">
									<table border=1 bordercolor="darkcyan" cellspacing=0 cellpadding=3 bgcolor="white" width=450>
										<tr>
											<td width=35% align="center" bgcolor="#ffffcc"><img src="img/input_set/touyu.gif" alt="����"></td>
											<td width=65% align="center"><font size=2><?=decode_input($ol_flag)?></font></td>
											<input type="hidden" name="ol_flag" value=1>
										</tr>
										<tr>
											<td width=35% align="center" bgcolor="#ffffcc"><img src="img/input_set/gomi.gif" alt="����"></td>
											<td width=65% align="center"><font size=2><?=decode_input($gm_flag)?></font></td>
										</tr>
										<tr>
											<input type="hidden" name="gl_flag" value=0>
											<td align="center" bgcolor="#ffffcc"><img src="img/input_set/gasoline.gif" alt="�������"></td>
											<td width=65% align="center"><font size=2><?=decode_input($gl_flag)?></font></td>
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

	<tr><td colspan=3 height=20></td></tr>
	<tr>
		<td align="center" colspan=3><hr width=95% size=1></td>
	</tr>
	<tr>
		<td width="35" align="left" valign="bottom"><img src="img/kado_3.gif" width="35" height="35"></td>
		<td width=644 align="center" valign="top">
			<a href="main.php"><img src="img/button/input/main.gif" border=0 width=79 height=44 alt="�ᥤ���"></a>
			<a href="result_input.php"><img src="img/button/input/nyuuryoku.gif" border=0 width=99 height=44 alt="���ϲ��̤�"></a>
			<a href='Javascript:onClick=history.back()'><img src="img/button/input/back1.gif" border=0 width=72 height=44 alt="���"></a>
		</td>
		<td width="35" height="35" align="right" valign="bottom"><img src="img/kado_4.gif" width="35" height="35"></td>
	</tr>
</table>
</form>
</div>
</body>
</html>