<?
$top = './..';
$inc = "$top/../inc";
include("$inc/decode.php");
include("$inc/ma_profile.php");
$inc = "$top/inc";
$img = "$top/image";
include("$inc/mn_regist.php");
include("$inc/mn_header.php");

// ���å�������
session_start();
if (!isset($_SESSION['ss_regist']))
	redirect('regist2.php');
$reg = &$_SESSION['ss_regist'];

// �ե����फ��ǡ�������
$reg->spouse_flg = $spouse_flg;
$reg->spouse_other = $spouse_other;
$reg->spouse_birthday_y = $spouse_birthday_y;
$reg->spouse_birthday_m = $spouse_birthday_m;
$reg->spouse_birthday_d = $spouse_birthday_d;
$reg->spouse_shokugyo_cd = $spouse_shokugyo_cd;
$reg->have_child = $have_child;
$reg->senior_flg = $senior_flg;
$reg->housing_form = $housing_form;
$reg->have_car = $have_car;
$reg->conveni = get_multi_data($conveni);
$reg->super = get_multi_data($super);
$reg->genre = get_multi_data($genre);
$reg->ma_profile = get_multi_data($ma_profile);

$reg->child = array();
if ($have_child == '1') {
	for ($i = 0; $i < $child_num; $i++) {
		$reg->child[$i]->sex = $child_sex[$i];
		$reg->child[$i]->birthday_y = $child_birthday_y[$i];
		$reg->child[$i]->birthday_m = $child_birthday_m[$i];
		$reg->child[$i]->birthday_d = $child_birthday_d[$i];
	}
}
/*
if ($reg->spouse_flg == '')
	$msg[] = '�۶��Ԥ�̵ͭ�����򤵤�Ƥ��ʤ��褦�Ǥ���';
else if ($reg->spouse_flg == '1') {
	if ($reg->spouse_birthday_y == '' || $reg->spouse_birthday_m == '' || $reg->spouse_birthday_d == '')
		$msg[] = '�۶��Ԥ���ǯ���������򤵤�Ƥ��ʤ��褦�Ǥ���';

	if ($reg->spouse_shokugyo_cd == '')
		$msg[] = '�۶��Ԥο��Ȥ����򤵤�Ƥ��ʤ��褦�Ǥ���';
}

if ($reg->have_child == '')
	$msg[] = '���Ҥ��ޤ�̵ͭ�����򤵤�Ƥ��ʤ��褦�Ǥ���';
else if ($reg->have_child == '1') {
	foreach ($reg->child as $i => &$child) {
		$n = $i + 1;

		if ($child->sex == '')
			$msg[] = "{$n}���ܤΤ��Ҥ��ޤ����̤����򤵤�Ƥ��ʤ��褦�Ǥ���";

		if ($child->birthday_y == '' || $child->birthday_m == '' || $child->birthday_d == '')
			$msg[] = "{$n}���ܤΤ��Ҥ��ޤ���ǯ���������򤵤�Ƥ��ʤ��褦�Ǥ���";
	}
}

if ($reg->senior_flg == '')
	$msg[] = '����Ʊ�蘆��Ƥ��륷�˥��ؤ��������򤵤�Ƥ��ʤ��褦�Ǥ���';

if ($reg->housing_form == '')
	$msg[] = '������֤����򤵤�Ƥ��ʤ��褦�Ǥ���';

if ($reg->have_car == '')
	$msg[] = '�֤ν�ͭ�����򤵤�Ƥ��ʤ��褦�Ǥ���';
*/
if (!$msg)
	redirect('regi_confirm.php');
?>
<?monitor_menu()?>
<!-- ����ƥ�����ΤΥ������Ϥ��δ֤�ɽ�� -->
<form name="form1" method="post" action="regist3.php">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td valign="top" width="7" align="left"><img src="<?=$img?>/contents2.gif" width="7" height="69" alt="CONTENTS"></td>
		<td width="100%" align="center" valign="top"><br>
			<br>
			<table border="0" cellpadding="15" cellspacing="0" width="500" bgcolor="white">
				<tr>
					<td><font size="2">
						<table border="0" cellpadding="3" cellspacing="2" width="462">
							<tr>
								<td><font color="#2b2b63"><b>���������Ϥ���Ƥ��ʤ����ܤ�����褦�Ǥ��� </b></font>
									<hr>
									<font size="2"><br><?=disp_msg($msg, '��', '<br><br>')?></font>
									<hr>
									<center><input type="submit" value="���ᡡ�롡"></center>
								</td>
							</tr>
						</table><br>
						<br>
						</font>
					</td>
				</tr>
			</table>
			<br><br>
		</td>
	</tr>
</table>
</form>
<!-- ����ƥ�����ΤΥ������Ϥ��δ֤�ɽ�� -->
			</TD>
		</TR>
	</TBODY>
</TABLE>
</BODY>
</HTML>