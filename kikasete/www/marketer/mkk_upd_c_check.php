<?
$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/check.php");
include("$inc/get_form.php");
$inc = "$top/inc";
include("$inc/mk_regist.php");
include("$inc/mkk_mypage.php");
include("$inc/mkk_header.php");
include("$inc/mk_error.php");

// ���å�������
if (!isset($_SESSION['ss_regist']))
	redirect('mkk_upd_c.php');
$reg = &$_SESSION['ss_regist'];

$reg->kinmu_name = trim($kinmu_name);
$reg->kinmu_unit = trim($kinmu_unit);
$reg->kinmu_post = trim($kinmu_post);
$reg->kinmu_zip1 = get_zip($kinmu_zip1);
$reg->kinmu_zip2 = get_zip($kinmu_zip2);
$reg->kinmu_area = $kinmu_area;
$reg->kinmu_addr1 = trim($kinmu_addr1);
$reg->kinmu_addr2 = trim($kinmu_addr2);
$reg->kinmu_addr3 = trim($kinmu_addr3);
$reg->kinmu_tel1 = get_tel($kinmu_tel1);
$reg->kinmu_tel2 = get_tel($kinmu_tel2);
$reg->kinmu_tel3 = get_tel($kinmu_tel3);
$reg->kinmu_fax1 = get_tel($kinmu_fax1);
$reg->kinmu_fax2 = get_tel($kinmu_fax2);
$reg->kinmu_fax3 = get_tel($kinmu_fax3);

// ���ϥ����å�
if ($reg->kinmu_name == '')
	$msg[] = '��̳��̾�����Ϥ��Ƥ���������';

if ($reg->kinmu_zip1 == '' || $reg->kinmu_zip2 == '')
	$msg[] = '��̳��͹���ֹ�����Ϥ��Ƥ���������';
elseif (!check_zip($reg->kinmu_zip1, $reg->kinmu_zip2))
	$msg[] = '��̳��͹���ֹ椬���������Ϥ���Ƥ��ޤ���';
else
	$kinmu_zip_ok = true;

if ($reg->kinmu_area == '')
	$msg[] = '��̳�轻�����ƻ�ܸ��ˤ����Ϥ��Ƥ���������';
else
	$kinmu_area_ok = true;

if ($kinmu_zip_ok && $kinmu_area_ok && !check_zip_area($reg->kinmu_zip1 . $reg->kinmu_zip2, $reg->kinmu_area))
	$msg[] = '��̳��͹���ֹ�ȶ�̳�轻�����ƻ�ܸ��ˤ����פ��Ƥ��ޤ���';

if ($reg->kinmu_addr1 == '')
	$msg[] = '��̳�轻��ʻԶ�ˤ����Ϥ��Ƥ���������';

if ($reg->kinmu_addr2 == '')
	$msg[] = '��̳�轻���Į¼�����ϡˤ����Ϥ��Ƥ���������';

if ($reg->kinmu_tel1 == '' || $reg->kinmu_tel2 == '' || $reg->kinmu_tel3 == '')
	$msg[] = '��̳�������ֹ�����Ϥ��Ƥ���������';
elseif (!check_tel($reg->kinmu_tel1, $reg->kinmu_tel2, $reg->kinmu_tel3))
	$msg[] = '��̳�������ֹ椬���������Ϥ���Ƥ��ޤ���';

if ($reg->kinmu_fax1 != '' || $reg->kinmu_fax2 != '' || $reg->kinmu_fax3 != '') {
	if (!check_tel($reg->kinmu_fax1, $reg->kinmu_fax2, $reg->kinmu_fax3))
		$msg[] = '��̳��FAX�ֹ椬���������Ϥ���Ƥ��ޤ���';
}

if (!$msg)
	redirect('mkk_upd_c2.php');
?>
<? marketer_header('��Ͽ����ι���', PG_NULL) ?>

<table width="789" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td>
<table width="788" border="0" cellspacing="0" cellpadding="0" bgcolor="#ffffff">
<tr>
	<td width="10"><img src="images/common/spacer.gif" width="10" height="10" border="0"></td>
	<td><img src="images/common/spacer.gif" width="778" height="10" border="0"></td>
</tr>
	<tr>
	<td width="10"><img src="images/common/spacer.gif" width="10" height="10" border="0"></td>
    <td width="400" valign="top"><img src="images/t_mkk_upd_c.gif" width="538" height="20"><br>
      <br></td>
	</tr>
</table>

<table width="788" border="0" cellspacing="0" cellpadding="0" bgcolor="#ffffff">
	<tr>
		<td width="10"><img src="images/common/spacer.gif" width="10" height="10" border="0"></td>
		<td><?
$hidden[] = '<input type="hidden" name="cont" value="1">';
error_msg($msg, 'mkk_upd_c.php', $hidden);
?></td>
	</tr>
</table>
</td>
<td width="1" bgcolor="#dbdbdb"><img src="images/common/spacer.gif" width="1" height="17" border="0"></td>
  </tr>
</table>

<? marketer_footer() ?>
