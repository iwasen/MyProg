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
include("$inc/regist2.php");

// ���å�������
session_start();
if (!isset($_SESSION['ss_regist2']))
	redirect('member_input_exp.php');
$reg = &$_SESSION['ss_regist2'];

$reg->read_db_b($_SESSION['ss_seq_no']);
?>
<html>
<head>
<title>������xxx��������Club������</title>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
</head>

<body bgcolor="#FF0000" leftmargin="8" topmargin="8">
<div align="center">
<form name="form1" method="post" action="member_input02_check.php">
<table width="714" height="90%" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
	<tr>
		<td width="35" align="left" valign="top" bgcolor="#FFFFFF">
		<img src="img/kado_1.gif" width="35" height="35"></td>
		<td align="center" valign="bottom"><br>
			<table border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td align="center"><img src="img/nyuukai/title/m_input02_01.gif" width="262" height="37" alt="�椬�Ȥβ�²��������1"></td>
				</tr>
			</table>
			<hr width="95%" size="1">
		</td>
		<td width="35" align="right" valign="top"><img src="img/kado_2.gif" width="35" height="35"></td>
	</tr>
	<tr>
		<td><br></td>
		<td align="center" valign="top">
			<table width="500" border="1" cellspacing="3" cellpadding="8" bordercolor="#00CCFF">
				<tr>
					<td>
					<font size=2>
					���������ԤˤĤ��Ƥ�������������
					</font>
					</td>
				</tr>
			</table>
			<br>
			<table border="0" cellspacing="0" cellpadding="0" bgcolor="#00CCFF">
				<tr>
					<td><img src="img/spacer.gif" width="600" height="4"></td>
				</tr>
			</table>
			<br>
			<table border="0" width="630">
				<tr>
					<td align="left" height=20 width=60><font size=2><nobr>�� ̾ ��</nobr></td><td align="left" width=10>��</font></td>
					<td width=385>
					<input type="text" name="name1" size=15 <?=value($reg->name1)?>>
					<input type="text" name="name2" size=15 <?=value($reg->name2)?>>
					<font size=2>�����Ѵ����� </font>
					</td>
					<td rowspan="5" width="175" valign="top"><img src="img/nyuukai/zou_img/m_input02_01.gif" alt="���á����������ʲ椬�Ȥβ�²��������1��" width="175" height="184"></td>
				</tr>
				<tr>
					<td height ="20" align="left"><font size=2><nobr>�եꥬ��</nobr></font></td><td>��</td>
					<td>
					<input type="text" name="name1_kana" size=15 <?=value($reg->name1_kana)?>>
					<input type="text" name="name2_kana" size=15 <?=value($reg->name2_kana)?>>
					<font size=2>�����ѥ������ʡ� </font>
					</td>
				</tr>
				<tr>
					<td height ="30" align="left"><font size=2>��������</font></td><td>��</td>
					<td><font size=2>
					<input type="radio" name="sex" <?=value_checked('1', $reg->sex)?>>����
					<input type="radio" name="sex" <?=value_checked('2', $reg->sex)?>>����
					</font>
					</td>
				</tr>
				<tr>
					<td height ="30" align="left" height=40><font size=2>��ǯ����</font></td><td>��</td>
					<td><font size=2>���� <input type="text" name="birthday_y" size=4 maxlength=4 <?=value($reg->birthday_y)?>>ǯ <input type="text" name="birthday_m" size=2 maxlength=2 <?=value($reg->birthday_m)?>>�� <input type="text" name="birthday_d" size=2 maxlength=2 <?=value($reg->birthday_d)?>>��</font>
					</td>
				</tr>
				<tr>
					<td height ="30" align="left"><font size=2>͹���ֹ�</font></td><td>��</td>
					<td>
					<input type="text" name="zip1" size="3" maxlength="3" <?=value($reg->zip1)?>>-
					<input type="text" name="zip2" size="4" maxlength="4" <?=value($reg->zip2)?>>
					<font size=2><a href="zip_search.php" title="͹���ֹ渡��" target="zip_search">͹���ֹ�򸡺�</a></font></td>
				</tr>
				<tr>
					<td valign="top"><font size=2>��������</font></td><td valign="top">��</td>
					<td valign="top" colspan=2>
					<select name="area_cd"><? select_area('���򤷤Ƥ�������', $reg->area_cd) ?></select><br>
					<input type="text" name="address" size="70" <?=value($reg->address)?>><br>
					</td>
				</tr>
			</table><br>

			<table border="0" cellspacing="0" cellpadding="0" bgcolor="#00CCFF">
				<tr>
					<td><img src="img/spacer.gif" width="600" height="4"></td>
				</tr>
			</table>
			<br>
			<table border="0" cellspacing="0" cellpadding="0" width="3">
				<tr>
					<td align="center"><img src="img/nyuukai/page/01.gif" width="44" height="44"></td>
				</tr>
			</table>
		</td>
		<td><br></td>
	</tr>
	<tr>
		<td width="35" align="left" valign="bottom">
		<img src="img/kado_3.gif" width="35" height="35"></td>
		<td align="center" valign="bottom">
		<hr width="95%" size="1">
			<table border="0" cellspacing="0" cellpadding="0">
				<tr align="center">
<?
if (!$reg->back_page) {
?>
					<td><a href="member_input_exp.php"><img src="img/nyuukai/button/back.gif" width="99" height="40" border="0" alt="���"></a></td>
<!--
					<td width="10"></td>
					<td><a href="member_input02.php"><img src="img/nyuukai/button/cancell.gif" width="99" height="40" border="0" alt="����󥻥�"></a></td>
-->
					<td width="10"></td>
					<td><input type="image" src="img/nyuukai/button/next.gif" width="99" height="40" alt="����" border="0"></td>
<?
} else {
?>
					<td><a href="javascript:history.back()"><img src="img/nyuukai/button/back.gif" width="99" height="40" border="0" alt="���"></a></td>
					<td width="10"></td>
					<td><input type="image" src="img/nyuukai/button/syuusei.gif" width="99" height="40" alt="����" border="0"></td>
<?
}
?>
				</tr>
				<tr>
					<td height="7" colspan="6"><img src="img/spacer.gif" height="7"></td>
				</tr>
			</table>
		</td>
		<td width="35" height="35" align="right" valign="bottom">
		<img src="img/kado_4.gif" width="35" height="35"></td>
	</tr>
</table>
</form>
</div>
</body>
</html>
