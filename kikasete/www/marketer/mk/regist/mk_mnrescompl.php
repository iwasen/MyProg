<?
$top = '../..';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
$inc = "$top/inc";
include("$inc/mk_header.php");
include("$inc/mk_error.php");

if ($mail_addr == '')
	$msg = '�᡼�륢�ɥ쥹�����Ϥ���Ƥ��ޤ���';
?>
<? marketer_header('�᡼��˥塼����Ͽ�����λ', PG_REGIST) ?>
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
                
                <td><a href="mk_regist.php#mypage" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('Image60','','../../images/regist/side_mypage_on.gif',1)"><img src="../../images/regist/side_mypage.gif" alt="My�ڡ�����Ͽ" name="Image60" width="170" height="31" border="0"></a></td>
              </tr>
              <tr>
                <td><img src="../../images/regist/side_mailnews_on.gif" alt="�᡼��˥塼����Ͽ" width="170" height="31"></td>
              </tr>
            </table></td>
          <td width="1" bgcolor="#dbdbdb"><img src="../../images/common/spacer.gif" width="1" height="10" />
          <td width="12" valign="top"><img src="../../images/common/spacer.gif" width="12" height="10">          
          <td valign="top">
<?
if (!$msg) {
?>		  
            <table width="100%" border="0" cellpadding="0" cellspacing="0">
<tr> 
<td class="map"><a href="../../index.php">HOME</a>���䡡<a href="mk_regist.php">��Ͽ</a>���䡡�᡼��˥塼����Ͽ���</td>
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
                <td width="578"><img src="../../images/regist/email_end.gif" width="553" height="24"></td>
              </tr>
              <tr> 
                <td>&nbsp;</td>
              </tr>
              <tr> 
                <td class="description">
<?
$sql = "SELECT mr_marketer_id FROM t_marketer WHERE mr_mail_addr=" . sql_char($mail_addr) . " AND mr_status<>9 AND mr_type=1";
$result = db_exec($sql);
if (pg_numrows($result)) {
	$fetch = pg_fetch_object($result, 0);
	$sql = "UPDATE t_marketer SET mr_status=9 WHERE mr_marketer_id=$fetch->mr_marketer_id";
	db_exec($sql);
?>				
						�᡼��˥塼������Ͽ�����������ޤ�����<p>
						����ޤǤ��դ��礤ĺ�����꤬�Ȥ��������ޤ�����
						<br>����Ȥ⡢����³����������ꤤ�����夲�ޤ���
						<br>�ޤ������Ȥˤ��ۤ�ĺ����Τ�ڤ��ߤˤ��Ԥ����Ƥ���ޤ���
						<p>�ʤ����ۿ��Ƴ��򤴴�˾�ξ��ϡ������ݤǤ�������Ͽ�򤪴ꤤ�������ޤ���
<?
} else {
?>
						���Υ᡼�륢�ɥ쥹�Ǥ�����³����Ԥ����Ȥ��Ǥ��ޤ���Ǥ�����<br>
						<br>
						���ߥ᡼��˥塼���ۿ������ӥ�����Ͽ����Ƥ��ʤ�����My�ڡ�����Ͽ
						����Ƥ����ǽ��������ޤ���<br>
						<br>
						My�ڡ�����Ͽ�����ϡ����줾���My�ڡ���
						��������³����ԤäƤ���������<br>
						<br>
						<br>
						�嵭�ʳ��θ���������³����Ԥ����Ȥ��Ǥ��ʤ��ä����ϡ������
						�Ǥ�����̳�ɤޤǤ��䤤��碌����������<br>
						<br>
                    <strong>�������ơ�net ��̳�ɡ�<a href=\"mailto:k-agent@kikasete.net?subject=%81%a1%82%a8%96%e2%82%a2%8d%87%82%ed%82%b9\">k-agent@kikasete.net</a></strong><br>
<?
}
?>

                </td>
              </tr>
              <tr> 
                <td><img src="../../images/common/spacer.gif" width="10" height="10"></td>
              </tr>
              <tr> 
                <td align="right"><a href="#top"><img src="../../images/common/go_top.gif" alt="GO TOP" width="57" height="11" border="0"></a></td>
              </tr>
              <tr> 
                <td><img src="../../images/common/spacer.gif" width="10" height="30"></td>
              </tr>
            </table>
<?
} else
	error_msg($msg);
?>			
          </td>
          <td valign="top">&nbsp;</td>
        </tr>
      </table>
<td width="1" bgcolor="#dbdbdb"><img src="../../images/common/spacer.gif" width="1" height="10"></td>
</tr>
</table>


<? marketer_footer() ?>
