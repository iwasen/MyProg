<?
$top = '../..';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/check.php");
include("$inc/get_form.php");
$inc = "$top/inc";
include("$inc/mk_regist.php");
include("$inc/mk_header.php");
include("$inc/mk_error.php");

// ���å�������
session_start();
if (!isset($_SESSION['ss_regist']))
	redirect('mk_kregi1.php');
$reg = &$_SESSION['ss_regist'];

// �ե����फ��ǡ�������
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
	$msg[] = '���̾�����Ϥ��Ƥ���������';

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
	$msg[] = '��̳�轻���Į¼�ˤ����Ϥ��Ƥ���������';

if ($reg->kinmu_tel1 == '' || $reg->kinmu_tel2 == '' || $reg->kinmu_tel3 == '')
	$msg[] = '��̳�������ֹ�����Ϥ��Ƥ���������';
elseif (!check_tel($reg->kinmu_tel1, $reg->kinmu_tel2, $reg->kinmu_tel3))
	$msg[] = '��̳�������ֹ椬���������Ϥ���Ƥ��ޤ���';

if ($reg->kinmu_fax1 != '' || $reg->kinmu_fax2 != '' || $reg->kinmu_fax3 != '') {
	if (!check_tel($reg->kinmu_fax1, $reg->kinmu_fax2, $reg->kinmu_fax3))
		$msg[] = '��̳��FAX�ֹ椬���������Ϥ���Ƥ��ޤ���';
}

if (!$msg)
	redirect('mk_kregi3.php');
?>
<? marketer_header('���ܾ������Ͽ', PG_REGIST) ?>

<table border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
  <tr>
    <td width="788">
    <table width="788" border="0" cellpadding="0" cellspacing="0">
        <tr>
          <td valign="top"><img src="../../images/common/spacer.gif" width="10" height="15" /></td>
          <td bgcolor="#dbdbdb">        
          <td valign="top">        
          <td valign="top">&nbsp;</td>
          <td valign="top"><img src="../../images/common/spacer.gif" width="20" height="15"></td>
        </tr>
        <tr>
          <td width="170" valign="top" bgcolor="#e5eaf0">
            <table width="100%"  border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td align="right" bgcolor="#FFFFFF"><img src="../../images/regist/side_regist.gif" alt="����Ͽ" width="57" height="18"></td>
              </tr>
              <tr>
                <td><img src="../../images/regist/side_mypage_on.gif" alt="My�ڡ�����Ͽ" width="170" height="31"></td>
              </tr>
              <tr>
                
                <td><a href="mk_regist.php#mail" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('Image59','','../../images/regist/side_mailnews_on.gif',1)"><img src="../../images/regist/side_mailnews.gif" alt="�᡼��˥塼����Ͽ" name="Image59" width="170" height="31" border="0"></a></td>
              </tr>
            </table></td>
          <td width="1" bgcolor="#dbdbdb"><img src="../../images/common/spacer.gif" width="1" height="10" />
          <td width="12" valign="top"><img src="../../images/common/spacer.gif" width="12" height="10">          
          <td valign="top">
            <table width="100%" border="0" cellpadding="0" cellspacing="0">
<tr> 
<td class="map"><a href="../../index.php">HOME</a>���䡡<a href="mk_regist.php">��Ͽ</a>���䡡My�ڡ�����Ͽ</td>
</tr>
<tr>
<td><img src="../../images/common/spacer.gif" width="10" height="10" /></td>
</tr>
              <tr> 
                <td><img src="../../images/regist/title.gif" alt="����Ͽ�ˤĤ���" width="114" height="28"></td>
              </tr>
              <tr> 
                <td><img src="../../images/common/spacer.gif" width="10" height="30"></td>
              </tr>
              <tr> 
                <td width="578"><img src="../../images/regist/header1.gif" alt="My�ڡ�����Ͽ" width="578" height="38"></td>
              </tr>
              <tr> 
                <td>&nbsp;</td>
              </tr>
              <tr> 
                <td align="center"><img src="../../images/regist/sequence3.gif" width="539" height="30"></td>
              </tr>
              <tr> 
                <td>&nbsp;</td>
              </tr>
              <tr> 
                <td width="578">
                  <? error_msg($msg, 'mk_kregi2.php') ?>
                </td>
              </tr>
              <tr> 
                <td><img src="../../images/common/spacer.gif" width="10" height="20"></td>
              </tr>
              <tr> 
                <td align="right"><a href="#top"><img src="../../images/common/go_top.gif" alt="GO TOP" width="57" height="11" border="0"></a></td>
              </tr>
              <tr> 
                <td><img src="../../images/common/spacer.gif" width="10" height="30"></td>
              </tr>
            </table>
          </td>
          <td valign="top">&nbsp;</td>
        </tr>
      </table>
<td width="1" bgcolor="#dbdbdb"><img src="../../images/common/spacer.gif" width="1" height="10"></td>
</tr>
</table>

<? marketer_footer(BLUE); ?>

</body>
</html>
