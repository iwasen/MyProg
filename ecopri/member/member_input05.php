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
include("$inc/decode.php");
$inc = "$top/inc";
include("$inc/regist2.php");

//����¾ɽ��
function disp_etc($str) {
	if ($str)
		echo "( " . htmlspecialchars($str) . " )";
}
// �ء��٥�ϥ���
function decode_hebel($code) {
	if ($code)
		echo "�Ϥ�";
	else
		echo "������";
}
// ����/�ʤ�ɽ��
function decode_yesno($code) {
	if ($code == 1)
		echo "����";
	else
		echo "�ʤ�";
}
// �۶���ɽ��
function disp_parent($code) {
	if ($code == '1')
		echo "��";
	elseif ($code == '2')
		echo "��";
}

// ���å�������
session_start();
if (!isset($_SESSION['ss_regist2']))
	redirect('member_input_exp.php');
$reg = &$_SESSION['ss_regist2'];

$reg->back_page = "member_input05.php";
?>
<html>
<head>
<title>������xxx��������Club������</title>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
</head>

<body bgcolor="#FF0000" leftmargin="8" topmargin="8">
<form action="member_input_end.php" name="form1" method="post">

<div align="center">
<table width="714" height="90%" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
	<tr>
		<td width="35" align="left" valign="top" bgcolor="#FFFFFF">
		<img src="img/kado_1.gif" width="35" height="35"></td>
		<td align="center" valign="bottom"><br>
			<table border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td align="center"><img src="img/nyuukai/zou_img/m_input05_01.gif" width="400" height="124" alt="�����ˤ󤷤Ƥ�"></td>
				</tr>
			</table>
		</td>
		<td width="35" align="right" valign="top">
		<img src="img/kado_2.gif" width="35" height="35"></td>
	</tr>

	<tr>
		<td><br></td>
		<td align="center" valign="top">

		<br>
			<table border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td><font size=2><b>���Ϥ��줿��̤Ϥ��Τ褦�ˤʤ�ޤ�����</b></font></td>
				</tr>
			</table>
			<br>
			<table border="1" cellspacing="0" cellpadding="3" bordercolor="#5F9EA0" frame="box">
				<tr bgcolor="#ccffcc">
					<td align="center" colspan=3><font size=2>�������ԤˤĤ���</font></td>
				</tr>
				<tr>
					<td width=100 bgcolor="#fff8dd"><font size=2>��̾��</font></td>
					<td width=300><font size=2><?=htmlspecialchars($reg->name1)?> <?=htmlspecialchars($reg->name2)?></font></td>
					<td rowspan=6><a href="member_input02.php"><img src="img/nyuukai/button/syusei.gif" border="0" name="��������"></a></td>
				</tr>
				<tr>
					<td bgcolor="#fff8dd"><font size=2>�եꥬ��</font></td>
					<td><font size=2><?=htmlspecialchars($reg->name1_kana)?> <?=htmlspecialchars($reg->name2_kana)?></font></td>
				</tr>
				<tr>
					<td bgcolor="#fff8dd"><font size=2>����</font></td>
					<td><font size=2><?=decode_sex($reg->sex,'')?></font></td>
				</tr>
				<tr>
					<td bgcolor="#fff8dd"><font size=2>��ǯ����</font></td>
					<td><font size=2><?=$reg->birthday_y?>ǯ<?=$reg->birthday_m?>��<?=$reg->birthday_d?>��</font></td>
				</tr>
				<tr>
					<td bgcolor="#fff8dd"><font size=2>͹���ֹ�</font></td>
					<td><font size=2><?=$reg->zip1?>-<?=$reg->zip2?></font></td>
				</tr>
				<tr>
					<td bgcolor="#fff8dd"><font size=2>����</font></td>
					<td><font size=2><?=decode_area($reg->area_cd,'')?><br><?=htmlspecialchars($reg->address)?></font></td>
				</tr>
				<tr bgcolor="#ccffcc">
					<td align="center" colspan=3><font size=2>Ʊ��Τ���²�ˤĤ���</font></td>
				</tr>
				<tr>
					<td bgcolor="#fff8dd"><font size=2>Ʊ��Τ���²</font></td>
					<td><font size=2>
<?
$family = $reg->family[1];
if ($family->family_rel) {
?>
					<?=disp_parent($reg->sex)?>��<?=$family->family_age?>�С�<br>
<?
}
for ($i = 2; $i <= 14; $i++) {
	$family = &$reg->family[$i];
	if (!$family->family_rel)
		break;
?>
					<?=decode_family_rel($family->family_rel)?>��<?=$family->family_age?>�С�<br>
<?
}
?>
					</font></td>
					<td><a href="member_input03.php"><img src="img/nyuukai/button/syusei.gif" border="0" name="��������"></a></td>
				</tr>
				<tr bgcolor="#ccffcc">
					<td align="center" colspan=3><font size=2>����ˤĤ���</font></td>
				</tr>
				<tr>
					<td bgcolor="#fff8dd"><font size=2>�������</font></td>
					<td><font size=2><?=decode_keitai($reg->keitai_cd)?><?=disp_etc($reg->keitai_etc)?></font></td>
					<td rowspan=11><a href="member_input04.php"><img src="img/nyuukai/button/syusei.gif" border="0" name="��������"></a></td>
				</tr>
				<tr>
					<td bgcolor="#fff8dd"><font size=2>���ﹽ¤</font></td>
					<td><font size=2><?=decode_sozai($reg->sozai_cd)?></font></td>
				</tr>
				<tr>
					<td bgcolor="#fff8dd"><font size=2>�ء��٥�ϥ���</font></td>
					<td><font size=2><?=decode_hebel($reg->hebel_flag)?></font></td>
				</tr>
				<tr>
					<td bgcolor="#fff8dd"><font size=2>����ǯ��</font></td>
					<td><font size=2><?=$reg->chikunen_cd != ''?decode_chikunen($reg->chikunen_cd).'ǯ':'--'?></font></td>
				</tr>
				<tr>
					<td bgcolor="#fff8dd"><font size=2>�ּ��</font></td>
					<td><font size=2><?=decode_room($reg->room_cd)?></font></td>
				</tr>
				<tr>
					<td bgcolor="#fff8dd"><font size=2>��������</font></td>
					<td><font size=2><?=$reg->space_cd != ''?decode_space($reg->space_cd).'m3':'--'?></font></td>
				</tr>
				<tr>
					<td bgcolor="#fff8dd"><font size=2>����勵����</font></td>
					<td><font size=2><?=decode_boiler($reg->boiler_cd)?><?=disp_etc($reg->boiler_etc)?></font></td>
				</tr>
				<tr>
					<td bgcolor="#fff8dd"><font size=2>������</font></td>
					<td><font size=2><?=decode_gas_kind($reg->gas_kind_cd)?><?=disp_etc(decode_gas_type($reg->gas_type_cd))?></font></td>
				</tr>
				<tr>
					<td bgcolor="#fff8dd"><font size=2>��ƻ�������</font></td>
					<td><font size=2><?=decode_water($reg->water_month)?></font></td>
				</tr>
				<tr>
					<td bgcolor="#fff8dd"><font size=2>�ֽ�ͭ</font></td>
					<td>
<?
if ($reg->car_flag == 0) {
?>
					<font size=2>�ʤ�</font><br>
<?
} else {
?>
						<table cellspacing=0 cellpadding=0 border=0>
							<tr>
								<td width=80><font size=2>��������</font></td>
								<td width=20><font size=2>��</font></td>
								<td><font size=2><?=$reg->gcar_num?>��</font></td>
							</tr>
							<tr>
								<td><font size=2>�ǥ��������</font></td>
								<td><font size=2>��</font></td>
								<td><font size=2><?=$reg->dcar_num?>��</font></td>
							</tr>
							<tr>
								<td><font size=2>�Х���</font></td>
								<td><font size=2>��</font></td>
								<td><font size=2><?=$reg->bike_num?>��</font></td>
							</tr>
						</table>
					</td>
				</tr>
<?
}
?>
				<tr>
					<td bgcolor="#fff8dd"><font size=2>��������</font></td>
					<td><font size=2><?=decode_yesno($reg->ol_flag)?></font></td>
				</tr>
			</table>
			<br>

			<table border="0" cellspacing="10" cellpadding="0">
				<tr>
					<td><img src="img/nyuukai/zou_img/m_input05_02.gif" alt="�ɤ����ä���"></td>
					<td align="left" valign="middle">
						<table border="0" cellspacing="0" cellpadding="0" bgcolor="#00CCFF">
							<tr>
								<td><img src="img/spacer.gif" width="320" height="3"></td>
							</tr>
						</table>
						<br><br>
						<font size=2>
						���Ϥ��ʤ�������<img src="img/nyuukai/button/syusei_s.gif" alt="��������" border="0" align="middle" width="49" height="21">
						�򲡤��ƽ������Ƥ���������
						</font>
						<br>
						<br>
						<br>
						<table border="0" cellspacing="0" cellpadding="0" bgcolor="#00CCFF">
							<tr>
								<td><img src="img/spacer.gif" width="320" height="3"></td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
			<br>
			<table border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td align="center"><img src="img/nyuukai/page/04.gif" width="44" height="44"></td>
				</tr>
			</table>
		</td>
		<td><br></td>
	</tr>
	<tr>
		<td width="35" align="left" valign="bottom">
		<img src="img/kado_3.gif" width="35" height="35"></td>
		<td align="center" valign="bottom"><hr width="95%" size="1">
			<table border="0" cellspacing="0" cellpadding="0">
				<tr align="center">
					<td><a href="member_input_end.php"><img src="img/nyuukai/button/koreok.gif" border="0" width="180" height="51"></a></td>
				</tr>
				<tr>
					<td height="7" colspan="3"><img src="img/spacer.gif" height="7" width="208"></td>
				</tr>
			</table>
		</td>
		<td width="35" height="35" align="right" valign="bottom">
		<img src="img/kado_4.gif" width="35" height="35"></td>
	</tr>
</table>

</div>
</form>
</body>
</html>