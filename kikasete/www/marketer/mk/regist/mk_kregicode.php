<?
$top = '../..';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/mail.php");
include("$inc/encrypt.php");
include("$inc/decode.php");
$inc = "$top/inc";
include("$inc/mk_regist.php");
include("$inc/mk_header_sem.php");

// ��Ͽ���դ��᡼������
function mail_send($reg, $seq_no) {
	$fetch = get_system_info('sy_url_marketer');
	get_mail_template('reg_mkp1', $subject, $from, $cc, $bcc, $repty_to, $body);
	$body = str_replace('%MAIL_ADDR%', $reg->mail_addr, $body);
	$body = str_replace('%REGIST_URL%', "{$fetch->sy_url_marketer}mk_kregicompl.php?key=" . encrypt_number($seq_no, 8), $body);
	send_mail($subject, $reg->mail_addr, $from, $body, $cc, $bcc, $reply_to);
}

// ���å�������
session_start();
if (!isset($_SESSION['ss_regist']))
	redirect('mk_kregi1.php');
$reg = &$_SESSION['ss_regist'];

// ����Ͽ�ơ��֥����¸
$seq_no = $reg->save_db();

// �᡼������
mail_send($reg, $seq_no);

// ���å�����ѿ����
session_destroy();
?>
<? marketer_header('��Ͽ���մ�λ', PG_REGIST) ?>

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
            <table width="578" border="0" cellpadding="0" cellspacing="0">
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
                <td align="center"><img src="../../images/regist/finish.gif"></td>
              </tr>
              ���� 
              <tr> 
                <td><img src="../../images/common/spacer.gif" width="10" height="20"></td>
              </tr>
              <tr> 
                <td> 
                  <p>��Ͽ����դ��ޤ�����</p>
                  <p>�������ޤ���Ͽĺ���ޤ����᡼�륢�ɥ쥹���Ƥˡ�����Ͽ��ǧ�᡼��ۤ����餻��ĺ���Ƥ���ޤ��ΤǤ���ǧ����������</p>
                  <p><Span Style=" color:#FF0000;">�᡼��򤴳�ǧ�ξ塢���Υ᡼��˵��ܤ��줿�գң̤���֥륯��å�������Ͽ��³����λ�����Ƥ���������</Span><br>
                  </p>
                  <p>�� </p>
                  <p> �� ���μ�³����λ���ޤ���ȡ���Ͽ���줿���ȤˤϤʤ�ޤ���ΤǤ���ղ������� <br>
                    �� ���֥륯��å��򤷤Ƥ�ڡ����������ʤ����ϡ��գң̤򥳥ԡ����ƥ֥饦���Υ��ɥ쥹�С���ľ��Ž���դ��Ʋ�������</p>
                  <p>��</p>
                  <p>�����졢�����ʾ夿�äƤ�᡼�뤬�Ϥ��ʤ����ϡ���̳�ɤޤǤ����碌��������</p>
                  <p>�������ơ�net ��̳�ɡ�<img src="../../images/common/icon_mail.gif" width="14" height="9">��<a href="mailto:k-info@kikasete.net?subject=%81%a1%82%a8%96%e2%82%a2%8d%87%82%ed%82%b9">k-info@kikasete.net</a></p>
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
