<?
$top = '../..';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/decode.php");
include("$inc/mail.php");
$inc = "$top/inc";
include("$inc/mk_header_sem.php");

// �᡼������
get_mail_template('inq_mkt', $subject, $from, $cc, $bcc, $repty_to, $body);
$from = str_replace('%MAIL_ADDR%', "$name1 $name2 <$mail_addr>", $from);
$body = str_replace('%CONTENTS%', $contents, $body);
$body = str_replace('%COMPANY%', $company, $body);
$body = str_replace('%REGIST%', decode_regist($regist), $body);
$fetch = get_system_info('sy_mk_inq_to');

send_mail($subject, $fetch->sy_mk_inq_to, $from, $body, $cc, $bcc, $reply_to);
?>
<? marketer_header('���䤤��碌��λ', PG_CONTACT) ?>
<table border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
  <tr>
    <td width="788">

      <table width="660"  border="0" align="center" cellpadding="0" cellspacing="0">

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
            <td class="description">���䤤��碌���꤬�Ȥ��������ޤ�����
						<br>
						ĺ�������䤤��碌���ƤˤĤ��ޤ��Ƥϡ��ɤäƥ���������Ȥ�ꤴϢ������ĺ���ޤ���
						<br>
						��������ꤤ�����夲�ޤ���
						<br><br>
						�ʤ����������äƤ⥨��������Ȥ��Ϣ��Τʤ����ϡ�������Ǥ�����
						<a href="mailto:k-agent@kikasete.net?subject=%81%a1%82%a8%96%e2%82%a2%8d%87%82%ed%82%b9">��̳��</a>
						�ޤǤ��䤤��碌��������</td>
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

      </table>
<td width="1" bgcolor="#dbdbdb"><img src="../../images/common/spacer.gif" width="1" height="10"></td>
</tr>
</table>


<? marketer_footer(false); ?>



</body>
</html>
