<?
$top = '../..';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/check.php");
include("$inc/decode.php");
$inc = "$top/inc";
include("$inc/mk_header.php");
include("$inc/mk_error.php");

if ($contents == '')
	$msg[] = '����礻���Ƥ����Ϥ��Ƥ���������';

if ($name1 == '')
	$msg[] = '��̾�������ˤ����Ϥ��Ƥ���������';

if ($name2 == '')
	$msg[] = '��̾����̾�ˤ����Ϥ��Ƥ���������';

if ($mail_addr == '')
	$msg[] = '�᡼�륢�ɥ쥹�����Ϥ��Ƥ���������';
elseif (!check_mail_addr($mail_addr))
	$msg[] = '�᡼�륢�ɥ쥹�����������Ϥ���Ƥ��ʤ��褦�Ǥ���';

if ($company == '')
	$msg[] = '���̾�����Ϥ��Ƥ���������';

if ($regist == '')
	$msg[] = '�������ơ�net �桼������Ͽ��̵ͭ�����򤷤Ƥ���������';
?>

<? marketer_header('CONTACT US | �������ơ�net', PG_CONTACT) ?>

<table width="789" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td>

<table border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
  <tr>
    <td width="788">
<?
if (!$msg) {
?>

<table width="660"  border="0" align="center" cellpadding="0" cellspacing="0">
              <form method="post" action="mk_contact3.php">
                <input type="hidden" name="contents" <?=value($contents)?>>
<input type="hidden" name="name1" <?=value($name1)?>>
<input type="hidden" name="name2" <?=value($name2)?>>
<input type="hidden" name="mail_addr" <?=value($mail_addr)?>>
<input type="hidden" name="company" <?=value($company)?>>
<input type="hidden" name="regist" <?=value($regist)?>>

<tr> 
<td><img src="../../images/common/spacer.gif" width="10" height="15"></td>
</tr>
<tr> 
<td class="map"><a href="../../index.php">HOME</a>���䡡Contact Us</td>
</tr>
<tr>
<td><img src="../../images/common/spacer.gif" width="10" height="10" /></td>
</tr>
<tr> 
<td><img src="../../images/contact/title.gif" alt="Ĵ�����ꡧ���䤤��碌�����Ĥ�" width="201" height="28"></td>
</tr>
<tr> 
<td><img src="../../images/common/spacer.gif" width="10" height="20"></td>
</tr>
<tr> 
<td class="description">���������ƤǤ�����Ǥ�����<br>
���Ƥ���������ϡ��ֽ����ץܥ���򲡤��Ʋ�����������ξ��ϡ������ץܥ���򲡤��Ʋ�������</td>
</tr>
<tr> 
<td><img src="../../images/common/spacer.gif" width="10" height="20"></td>
</tr>
<tr> 
<td bgcolor="#dadada">
<table width="100%"  border="0" cellspacing="1" cellpadding="10">
<tr class="description"> 
<td width="180" valign="top" bgcolor="#e5eaf0">�����ե�����</td>
<td bgcolor="#FFFFFF"><?=nl2br($contents)?><br /><br />
<div class="footer_text" style="color:red;">�ʤ������ֹ�Τ�����ϳ��Ϥ������ޤ��󤫡���</div></td>
</tr>
<tr class="description"> 
<td bgcolor="#e5eaf0">��̾��</td>
<td bgcolor="#FFFFFF"><?=htmlspecialchars($name1)?><?=htmlspecialchars($name2)?></td>
</tr>
<tr class="description"> 
<td bgcolor="#e5eaf0">�᡼�륢�ɥ쥹</td>
<td bgcolor="#FFFFFF"><?=htmlspecialchars($mail_addr)?></td>
</tr>
<tr class="description"> 
<td bgcolor="#e5eaf0">���̾������̾</td>
<td bgcolor="#FFFFFF"><?=htmlspecialchars($company)?></td>
</tr>
<tr class="description"> 
<td bgcolor="#e5eaf0">�������ơ�net �桼����Ͽ</td>
<td bgcolor="#FFFFFF"><?=decode_regist($regist)?></td>
</tr>
</table>
</td>
</tr>
<tr> 
<td><img src="../../images/common/spacer.gif" width="10" height="30"></td>
</tr>
<tr> 
<td align="center"><a href="javascript:history.back();" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('edit','','../../images/common/shusei_ov.gif',1)"><img src="../../images/common/shusei.gif" alt="����" name="edit" width="118" height="31" border="0"></a>��
<input type="image" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('sousin','','../../images/common/send_on.gif',1)" src="../../images/common/send.gif" alt="����" name="sousin" width="120" height="32" border="0">
</td>
</tr>
<tr>
<td align="center"><img src="../../images/common/spacer.gif" width="10" height="20"></td>
</tr>
<tr> 
<td align="right"><a href="#top"><img src="../../images/common/go_top.gif" alt="" width="57" height="11" border="0"></a></td>
</tr>
<tr> 
<td><img src="../../images/common/spacer.gif" width="10" height="20"></td>
</tr>
</form>
</table></tr>
</table>
<?
} else {
?>
<table width="788" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td>
	<? error_msg($msg);?>
	<? echo("&nbsp;");?></td>
  </tr>
</table>
<? }?>
</td>
<td width="1" bgcolor="#dbdbdb"><img src="../../images/common/spacer.gif" width="1" height="17" border="0"></td>
  </tr>
</table>
<? marketer_footer(false); ?>
</body>
</html>
