<?
$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/mr_permission.php");
include("$inc/enquete.php");
include("$inc/search.php");
include("$inc/my_partner.php");
$inc = "$top/inc";
include("$inc/mkk_mypage.php");
include("$inc/mkk_header.php");
include("$inc/mkk_myp_check.php");

// My�ѡ��ȥʡ����饹����
if ($edit) {
	$myp = &$_SESSION['s_my_partner'];
	$pjt_id = $myp->pjt_id;
} else {
	$_SESSION['s_my_partner'] = new my_partner_class;
	$myp = &$_SESSION['s_my_partner'];
	if ($pjt_id)
		$myp->read_db($pjt_id);
	else {
		$myp->marketer_id = $_SESSION['ss_owner_id'];
		$myp->save_enquete = true;
		$myp->save_search = true;

		$fetch = get_system_info('sy_myp_max_send');
		$myp->send_num = $fetch->sy_myp_max_send;
	}
}
?>
<? marketer_header('�������ơ�net �ͥåȥꥵ�����˥ץ�Υ���������Ȥ�', PG_NULL) ?>


<!--contents start-->
<table border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
<tr> 
<td width="788"> 
<table width="788" border="0" cellpadding="0" cellspacing="0">
<tr> 
<td valign="top"><img src="images/common/spacer.gif" width="10" height="15"></td>
<td bgcolor="#dbdbdb"> 
<td width="12" rowspan="2"> <img src="images/common/spacer.gif" width="18" height="10"> 
<td valign="top"><img src="images/common/spacer.gif" width="10" height="15"></td>
</tr>
<tr> 
<td width="170" valign="top" bgcolor="#e5eaf0"> 

<? marketer_menu(M_MYPARTNER); ?>

</td>
<td width="1" bgcolor="#dbdbdb"><img src="images/common/spacer.gif" width="1" height="10" /> 
<td valign="top"> 
<table width="553" border="0" cellpadding="0" cellspacing="0">
<tr> 
<td width="553"><img src="images/mkk_myenq/title01.gif" alt="MY MENU" width="553" height="25"></td>
</tr>
<tr> 
<td><img src="images/common/spacer.gif" width="10" height="10"></td>
</tr>
<tr> 
<td><img src="images/mp_pjt_list/title01.gif" alt="My�ѡ��ȥʡ�" width="123" height="28"> 
</td>
</tr>
<tr> 
<td><img src="images/common/spacer.gif" width="10" height="10" /></td>
</tr>
<tr> 
<td><img src="images/mp_pjt_list/title05.gif" alt="�ͣ��ѡ��ȥʡ�����ɽ" width="553" height="24"></td>
</tr>
<tr> 
<td><img src="images/common/spacer.gif" width="10" height="10"></td>
</tr>
<tr> 
<td class="footer_text"><img src="images/mp_pjt_list/title06.gif" alt="��������" width="176" height="15"></td>
</tr>
<tr> 
<td bgcolor="#dadada"> 
<table border=0 cellspacing=1 cellpadding=5 width="100%" class="nor_text">
<tr> 
<td width="20%" align="center" bgcolor="#e5eaf0">&nbsp;</td>
<td width="40%" align="center" bgcolor="#e5eaf0">��������</td>
<td width="40%" align="center" bgcolor="#e5eaf0">�������ܰʹ�</td>
</tr>
<tr> 
<td bgcolor="#e5eaf0" align="center" width="20%">�ꥯ�롼�ȼ»�</td>
<td bgcolor="#FFFFFF" align="center" width="40%">50,000��</td>
<td bgcolor="#FFFFFF" align="center" width="40%">��</td>
</tr>
<tr> 
<td bgcolor="#e5eaf0" align="center" width="20%">��ļ����šʣ�̾��</td>
<td bgcolor="#FFFFFF" align="center" width="40%">98,000��</td>
<td bgcolor="#FFFFFF" align="center" width="40%">98,000��</td>
</tr>
</table>
</td>
</tr>
<tr> 
<td><img src="images/common/spacer.gif" width="10" height="20"></td>
</tr>
<tr> 
<td><img src="images/mp_pjt_list/title07.gif" alt="���С��ɲå��ץ����" width="176" height="15"></td>
</tr>
<tr> 
<td bgcolor="#dadada"> 
<table border=0 cellspacing=1 cellpadding=5 width="100%" class="nor_text">
<tr bgcolor="#e5eaf0"> 
<td width="20%" align="center">&nbsp;</td>
<td width="40%" align="center">&nbsp;</td>
<td width="20%" align="center">��������</td>
<td width="20%" align="center">�������ܰʹ�</td>
</tr>
<tr> 
<td bgcolor="#e5eaf0" width="20%">���С��ɲ�<br>
�ꥯ�롼����ʣ�̾���ȡ�</td>
<td bgcolor="#FFFFFF" width="40%" rowspan="2">���С����ɲáʣ�̾���ȡˤˤ���������Ǥ���</td>
<td bgcolor="#FFFFFF" align="center" width="20%" rowspan="2">80,000��</td>
<td bgcolor="#FFFFFF" align="center" width="20%">��</td>
</tr>
<tr> 
<td bgcolor="#e5eaf0" width="20%">���С��ɲ�ʬ<br>
��ļ�������ʣ�̾���ȡ�</td>
<td bgcolor="#FFFFFF" align="center" width="20%">40,000��</td>
</tr>
</table>
</td>
</tr>
<tr> 
<td><img src="images/common/spacer.gif" width="10" height="20"></td>
</tr>
<tr> 
<td><img src="images/mp_pjt_list/title08.gif" alt="�ꥯ�롼�ȥ��ץ����" width="176" height="15"></td>
</tr>
<tr> 
<td bgcolor="#dadada"> 
<table border=0 cellspacing=1 cellpadding=5 width="100%" class="nor_text">
<tr bgcolor="#e5eaf0"> 
<td width="20%" align="center">&nbsp;</td>
<td width="40%" align="center">&nbsp;</td>
<td width="20%" align="center">��������</td>
<td width="20%" align="center">�������ܰʹ�</td>
</tr>
<tr> 
<td bgcolor="#e5eaf0" align="center" width="20%">�ꥯ�롼�������</td>
<td bgcolor="#FFFFFF" width="40%">��̳�ɤǥꥯ�롼�Ȥ���Ԥ���������ѤǤ���</td>
<td bgcolor="#FFFFFF" align="center" width="20%">30,000��</td>
<td bgcolor="#FFFFFF" align="center" width="20%">��</td>
</tr>
<tr>
  <td bgcolor="#e5eaf0" align="center">�ɲ�°��</td>
  <td bgcolor="#FFFFFF">�оݾ������ǡֻҶ���̵ͭ�ס�Ʊ���²�פ��оݼԹʹ��߾��Ȥ��Ƥ����Ѥ�����������������Ǥ���</td>
  <td bgcolor="#FFFFFF" align="center">5,000��/1°��</td>
  <td bgcolor="#FFFFFF" align="center">&nbsp;</td>
</tr>
<tr> 
<td bgcolor="#e5eaf0" align="center" width="20%">�����ù�</td>
<td bgcolor="#FFFFFF" width="40%">�ꥯ�롼�ȥ��󥱡��Ȥǲ����򸫤�����ˡ���̳�ɤǲ�����ù��������ѤǤ���</td>
<td bgcolor="#FFFFFF" align="center" width="20%">3,000��</td>
<td bgcolor="#FFFFFF" align="center" width="20%">��</td>
</tr>
<tr> 
<td bgcolor="#e5eaf0" align="center" width="20%">�����С�������</td>
<td bgcolor="#FFFFFF" width="40%">�ꥯ�롼�ȥ��󥱡��Ȥǲ����򸫤�����Υ����С��������Ǥ���</td>
<td bgcolor="#FFFFFF" align="center" width="20%">5,000��</td>
<td bgcolor="#FFFFFF" align="center" width="20%">��</td>
</tr>
<tr> 
<td bgcolor="#e5eaf0" align="center" width="20%">���ԡ��ɥ��㡼��</td>
<td bgcolor="#FFFFFF" width="40%">��������᤮�Ƥο����ǡ�������ȯ�����������ˤϥ��ԡ��ɥ��㡼���򤤤������ޤ��������������������ʹߤο����ξ��������Σ���������ȯ���Ȥʤ�ޤ���</td>
<td bgcolor="#FFFFFF" align="center" width="20%">&nbsp;&nbsp;10,000��</td>
<td bgcolor="#FFFFFF" align="center" width="20%">��</td>
</tr>
</table>
</td>
</tr>
<tr> 
<td><img src="images/common/spacer.gif" width="10" height="20"></td>
</tr>
<tr> 
<td><img src="images/mp_pjt_list/title09.gif" alt="��ļ����ĥ��ץ����" width="176" height="15"></td>
</tr>
<tr> 
<td bgcolor="#dadada"> 
<table border=0 cellspacing=1 cellpadding=5 width="100%" class="nor_text">
<tr bgcolor="#e5eaf0"> 
<td width="20%" align="center">&nbsp;</td>
<td width="40%" align="center">&nbsp;</td>
<td width="20%" align="center">��������</td>
<td width="20%" align="center">�������ܰʹ�</td>
</tr>
<tr> 
<td bgcolor="#e5eaf0" align="center" width="20%">�ե��������</td>
<td bgcolor="#FFFFFF" width="40%">��̳�ɤǥ��󥿥ӥ塼�ե����������������ѤǤ���</td>
<td bgcolor="#FFFFFF" align="center" width="40%" colspan="2">150,000��</td>
</tr>
<tr> 
<td bgcolor="#e5eaf0" align="center" width="20%">��ǥ졼��������</td>
<td bgcolor="#FFFFFF" width="40%">��̳�ɤ��ʲ�ʹԤ�Ԥ����ˤ��������ѤǤ���</td>
<td bgcolor="#FFFFFF" align="center" width="20%">100,000��</td>
<td bgcolor="#FFFFFF" align="center" width="20%">100,000��</td>
</tr>
<tr> 
<td bgcolor="#e5eaf0" align="center" width="20%">�����ù�</td>
<td bgcolor="#FFFFFF" width="40%">��ļ���ǲ����򸫤�����ˡ���̳�ɤǲ�����ù��������ѤǤ���</td>
<td bgcolor="#FFFFFF" align="center" width="40%" colspan="2">3,000��</td>
</tr>
<tr> 
<td bgcolor="#e5eaf0" align="center" width="20%">�����С�������</td>
<td bgcolor="#FFFFFF" width="40%">��ļ���ǲ����򸫤�����Υ����С��������Ǥ���</td>
<td bgcolor="#FFFFFF" align="center" width="40%" colspan="2">5,000��</td>
</tr>
</table>
</td>
</tr>
<tr> 
<td><img src="images/common/spacer.gif" width="10" height="20"></td>
</tr>
<tr> 
<td align="right"><a href="#top"><img src="images/common/go_top.gif" alt="GO TOP" width="57" height="11" border="0"></a></td>
</tr>
<tr> 
<td><img src="images/common/spacer.gif" width="10" height="30"></td>
</tr>
</table>
</td>
</tr>
</table>
</td>
<td width="1" bgcolor="#dbdbdb"><img src="images/common/spacer.gif" width="1" height="10"></td>
</tr>
</table>
<!--footer start-->

<? marketer_footer () ?>
