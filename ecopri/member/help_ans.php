<?
/******************************************************
' System :Eco-footprint ����ڡ���
' Content:ID,Password ���䤤��碌
'******************************************************/

$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/encrypt.php");

function check_name($name1, $name2) {
	if ($name1 != '')
		return "$name1 $name2 �ͤ�";
}
// ���å�������
if ($key) {
	$seq_no= decrypt_number($key);
	if ($seq_no) {

		$sql = "select mb_id,mb_password,mb_name1,mb_name2 from t_member where mb_step<>9 and mb_seq_no=$seq_no";
		$result = db_exec($sql);
		if (pg_numrows($result)) {
			$fetch = pg_fetch_object($result, 0);
		} else {
			$msg = "��URL���������ʤ��褦�Ǥ���";
		}
	} else
		$msg = "��URL���������ʤ��褦�Ǥ���";
} else
	$msg = "��URL���������ʤ��褦�Ǥ���";


?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<title>������xxx��������Club������</title>
</head>

<body bgcolor="#FF0000" text="#000000" link="#66ccff" vlink="#66ccff" leftmargin="8" topmargin="8" alink="#66ccff">
<div align="center">

<!-- ��ݳѥơ��֥� -->
<table border="0" cellpadding="0" cellspacing="0" width="714" bgcolor="#FFFFFF">
	<tr>
		<td width="35" align="left" valign="top" bgcolor="#FFFFFF">
			<img src="img/kado_1.gif" width="35" height="35">
		</td>
		<td align="center" valign="top">
			<!-- �����ȥ���ʬ -->
			<br>
			<img src="img/help/help_title.gif" width="207" height="56">
			<hr width="95%" size="1"><br>
			<!-- �����ȥ���ʬ��λ -->

			<!-- ���������� -->
<?
if ($msg) {
?>
			<table border="0" cellpadding="0" cellspacing="0" width=400>
				<tr>
					<td align="left" height="80"><font color='navy'><?=$msg?></font></td>
				</tr>
			</table>
<?
} else {
?>
			<table border="0" cellpadding="0" cellspacing="0" width=400>
				<tr>
					<td align="left"><font color="navy">��<?=check_name($fetch->name1,$fetch->name2)?>��Ͽ���Ƥϰʲ����̤�Ǥ���</font></td>
				</tr>
			</table>
			<table border="1" cellpadding="0" cellspacing="0" width=400 bordercolor="orange">
				<tr bgcolor="mistyrose">
					<td align="center" width=200><b>ID</b></td>
					<td align="center" width=200><b>�ѥ����</b></td>
				</tr>
				<tr>
					<td align="center" width=200 height=30><?=htmlspecialchars($fetch->mb_id)?></td>
					<td align="center" width=200><?=htmlspecialchars($fetch->mb_password)?></td>
				</tr>
			</table>
<?
}
?>
			<!-- �����ޤ� -->
		</td>
		<td width="35" align="right" valign="top" bgcolor="#FFFFFF">
			<img src="img/kado_2.gif" width="35" height="35">
		</td>
	</tr>

	<tr>
		<td width="35" valign="bottom"><img src="img/kado_3.gif" width="35" height="35"></td>
		<td align="center" height=70><a href="top.php"><img src="img/nyuukai/button/top.gif" border="0" width="99" height="40"></a></td>
		<td width="35" valign="bottom"><img src="img/kado_4.gif" width="35" height="35"></td>
	</tr>
</table>
<!-- ��ݳѥơ��֥뽪λ -->

<!--�եå����ܥ������-->
<!--
<br>
	<table border="0" cellpadding="0" cellspacing="0" align="center" width="714" height="54">
		<tr align="center" valign="middle" bgcolor="#ffffff">
			<td align="left" valign="top"><img src="img/kadoa_1.gif" border="0" width="10" height="54"></td>
			<td align="center" valign="middle"><a href="top.php" target="_top"><img src="img/button/top/button_top1.gif" border="0" width="68" height="51"></a></td>
			<td align="center" valign="middle"><a href="what_eco.php"><img src="img/button/top/button_eco1.gif" border="0" width="68" height="51"></a></td>
			<td align="center" valign="middle"><a href="what_can.php" target="_top"><img src="img/button/top/button_can1.gif" border="0" width="68" height="51"></a></td>
			<td align="center" valign="middle"><a href="what_use.php" target="_top"><img src="img/button/top/button_use1.gif" border="0" width="68" height="51"></a></td>
			<td align="center" valign="middle"><a href="what_family.php" target="_top"><img src="img/button/top/button_family1.gif" border="0" width="68" height="51"></a></td>
			<td align="center" valign="middle"><a href="others_char.php"><img src="img/button/top/button_char1.gif" border="0" width="68" height="51"></a></td>
			<td align="center" valign="middle"><a href="become_member.php" target="_top"><img src="img/button/top/button_menber1.gif" border="0" width="68" height="51"></a></td>
			<td align="center" valign="middle"><img src="img/button/top/button_help2.gif" border="0" width="68" height="51"></td>
			<td align="center" valign="middle"><a href="xxx.php" target="_top"><img src="img/button/top/button_xxx1.gif" border="0" width="68" height="51"></a></td>
			<td align="center" valign="middle"><a href="rule.php" target="_top"><img src="img/button/top/button_exp1.gif" border="0" width="68" height="51"></a></td>
			<td align="right" valign="top"><img src="img/kadoa_2.gif" border="0" width="10" height="54"></td>
		</tr>
	</table>
-->
<!--�եå����ܥ������ ��λ-->

</div>
</body>
</html>