<?
$top = './..';
$inc = "$top/../inc";
include("$inc/check.php");
$inc = "$top/inc";
$img = "$top/image";
include("$inc/mn_regist.php");
include("$inc/mn_partner.php");
include("$inc/mn_mypage.php");
include("$inc/mn_header2.php");
include("$inc/mn_error.php");

// ���å�������
if (!isset($_SESSION['ss_partner']))
	redirect('m_update.php');
$partner = &$_SESSION['ss_partner'];

// �ե����फ��ǡ�������
$partner->jitaku_zip1 = trim(mb_convert_kana($jitaku_zip1, 'n'));
$partner->jitaku_zip2 = trim(mb_convert_kana($jitaku_zip2, 'n'));
$partner->jitaku_area = $jitaku_area;
$partner->jitaku_addr1 = $jitaku_addr1;
$partner->jitaku_addr2 = $jitaku_addr2;
$partner->jitaku_addr3 = $jitaku_addr3;
$partner->jitaku_tel1 = trim(mb_convert_kana($jitaku_tel1, 'n'));
$partner->jitaku_tel2 = trim(mb_convert_kana($jitaku_tel2, 'n'));
$partner->jitaku_tel3 = trim(mb_convert_kana($jitaku_tel3, 'n'));
$partner->jitaku_fax1 = trim(mb_convert_kana($jitaku_fax1, 'n'));
$partner->jitaku_fax2 = trim(mb_convert_kana($jitaku_fax2, 'n'));
$partner->jitaku_fax3 = trim(mb_convert_kana($jitaku_fax3, 'n'));
/*
$partner->shokugyou_cd = $shokugyou_cd;
$partner->gyoushu_cd = $gyoushu_cd;
$partner->shokushu_cd = $shokushu_cd;
$partner->kinmu_name = $kinmu_name;
$partner->kinmu_zip1 = trim(mb_convert_kana($kinmu_zip1, 'n'));
$partner->kinmu_zip2 = trim(mb_convert_kana($kinmu_zip2, 'n'));
$partner->kinmu_area = $kinmu_area;
$partner->kinmu_addr1 = $kinmu_addr1;
$partner->kinmu_addr2 = $kinmu_addr2;
$partner->kinmu_addr3 = $kinmu_addr3;
$partner->kinmu_tel1 = trim(mb_convert_kana($kinmu_tel1, 'n'));
$partner->kinmu_tel2 = trim(mb_convert_kana($kinmu_tel2, 'n'));
$partner->kinmu_tel3 = trim(mb_convert_kana($kinmu_tel3, 'n'));
$partner->kinmu_fax1 = trim(mb_convert_kana($kinmu_fax1, 'n'));
$partner->kinmu_fax2 = trim(mb_convert_kana($kinmu_fax2, 'n'));
$partner->kinmu_fax3 = trim(mb_convert_kana($kinmu_fax3, 'n'));

for ($i = 1; $i <= 5; $i++) {
	$family = &$partner->family[$i];
	$family->family_rel_cd = $family_rel_cd[$i - 1];
	$family->age = $family_age[$i - 1];
}
*/
$reg = &$_SESSION['ss_regist'];
$reg->jitaku_zip1 = $partner->jitaku_zip1;
$reg->jitaku_zip2 = $partner->jitaku_zip2;
$reg->jitaku_area = $partner->jitaku_area;

// ���ƥ����å�
if ($partner->jitaku_zip1 == '' || $partner->jitaku_zip2 == '')
	$msg[] = '͹���ֹ�ʼ���ˤ����Ϥ���Ƥ��ʤ��褦�Ǥ���';
elseif (!check_zip($partner->jitaku_zip1, $partner->jitaku_zip2))
	$msg[] = '͹���ֹ�ʼ���ˤ����������Ϥ���Ƥ��ʤ��褦�Ǥ���';
else
	$jitaku_zip_ok = true;

if ($partner->jitaku_area == '')
	$msg[] = '�ｻ�ϰ�ʼ���ˤ����Ϥ���Ƥ��ʤ��褦�Ǥ���';
else
	$jitaku_area_ok = true;

if ($partner->jitaku_addr1 == '')
	$msg[] = '����ʻԶ�ˤ����Ϥ���Ƥ��ʤ��褦�Ǥ���';

if ($partner->jitaku_addr2 == '')
	$msg[] = '�����Į¼�ʲ��ˤ����Ϥ���Ƥ��ʤ��褦�Ǥ���';

if ($partner->jitaku_tel1 == '' || $partner->jitaku_tel2 == '' || $partner->jitaku_tel3 == '')
	$msg[] = '�����ֹ椬���Ϥ���Ƥ��ʤ��褦�Ǥ���';

if ($jitaku_zip_ok && $jitaku_area_ok && !check_zip_area($partner->jitaku_zip1 . $partner->jitaku_zip2, $partner->jitaku_area))
	$msg[] = "͹���ֹ�ʼ���ˤȵｻ�ϰ�ʼ���ˤ����פ��Ƥ��ʤ��褦�Ǥ���";
/*
if ($partner->shokugyou_cd == '')
	$msg[] = '���Ȥ����򤵤�Ƥ��ʤ��褦�Ǥ���';

if ($partner->gyoushu_cd == '')
	$msg[] = '�ȼ郎���򤵤�Ƥ��ʤ��褦�Ǥ���';

if ($partner->shokushu_cd == '')
	$msg[] = '���郎���򤵤�Ƥ��ʤ��褦�Ǥ���';

switch ($partner->shokugyou_cd) {
case 5:
case 9:
case 10:
case 11:
	break;
default:
	if ($partner->kinmu_name == '')
		$msg[] = '��̳��̾�����Ϥ���Ƥ��ʤ��褦�Ǥ���';

	if ($partner->kinmu_zip1 == '' || $partner->kinmu_zip2 == '')
		$msg[] = '͹���ֹ���̶���ˤ����Ϥ���Ƥ��ʤ��褦�Ǥ���';
	elseif (!check_zip($partner->kinmu_zip1, $partner->kinmu_zip2))
		$msg[] = '͹���ֹ���̶���ˤ����������Ϥ���Ƥ��ʤ��褦�Ǥ���';
	else
		$kinmu_zip_ok = true;

	if ($partner->kinmu_area == '')
		$msg[] = '�̶С��̳����ϰ褬���Ϥ���Ƥ��ʤ��褦�Ǥ���';
	else
		$kinmu_area_ok = true;

	if ($kinmu_zip_ok && $kinmu_area_ok && !check_zip_area($partner->kinmu_zip1 . $partner->kinmu_zip2, $partner->kinmu_area))
		$msg[] = '͹���ֹ���̶С��̳���ˤ��̶С��̳����ϰ褬���פ��Ƥ��ʤ��褦�Ǥ���';
	break;
}

if ($partner->kinmu_addr1 == '')
	$msg[] = '�̶��轻��ʻԶ�ˤ����Ϥ���Ƥ��ʤ��褦�Ǥ���';

if ($partner->kinmu_addr2 == '')
	$msg[] = '�̶��轻���Į¼�ʲ��ˤ����Ϥ���Ƥ��ʤ��褦�Ǥ���';

if ($partner->kinmu_tel1 == '' || $partner->kinmu_tel2 == '' || $partner->kinmu_tel3 == '')
	$msg[] = '�̶��������ֹ椬���Ϥ���Ƥ��ʤ��褦�Ǥ���';
*/
if (!$msg)
	redirect('m_confirm_p.php');
?>
<? monitor_menu() ?>

<form name="form1" method="post" action="regist2_check.php">
	<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
		<TBODY>
			<TR bgColor=#85A9DE>
				<TD><IMG height=29 src="<?=$img?>/spacer.gif" width=1 align=absMiddle>
					<SPAN class=mysttl_menu>�����ץ�ե�����</SPAN>
				</TD>
			</TR>
			<TR>
				<TD><IMG height=1 src="<?=$img?>/spacer.gif" width=1></TD></TR>
			<TR>
				<TD bgColor=#88b7d8><IMG height=1 src="<?=$img?>/spacer.gif" width=1></TD>
			</TR>
		</TBODY>
	</TABLE>
<br>
<table width="496" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td width="15">��</td>
		<td colspan="3"><table width="481" border="0" cellspacing="0" cellpadding="20">
			<? error_msg($msg) ?>
		</td>
	</tr>
</table>
</form>
</body>
</html>
