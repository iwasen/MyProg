<?
$top = '../..';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/mail.php");
$inc = "$top/inc";
include("$inc/mk_header.php");
include("$inc/mk_error.php");

// �ѥ�������Υ᡼������
function mail_send($mail_addr, $password) {
	get_mail_template('pwd_mk', $subject, $from, $cc, $bcc, $repty_to, $body);
	$body = str_replace('%PASSWORD%', $password, $body);
	send_mail($subject, $mail_addr, $from, $body, $cc, $bcc, $reply_to);
}

if ($mail_addr == '')
	$msg[] = "�᡼�륢�ɥ쥹�����Ϥ���Ƥ��ʤ��褦�Ǥ���";

if (!$msg) {
	$sql = "SELECT mr_mail_addr,mr_password FROM t_marketer WHERE mr_mail_addr='$mail_addr' AND mr_status<>9";
	$result = db_exec($sql);
	if (pg_numrows($result)) {
		$fetch = pg_fetch_object($result, 0);
		mail_send($fetch->mr_mail_addr, $fetch->mr_password);
	} else
		$msg[] = "���Ϥ����᡼�륢�ɥ쥹����Ͽ����Ƥ��ʤ��褦�Ǥ���";
}
?>
<? marketer_header('���䤤��碌��λ', PG_NULL) ?>
<a name="top"></a>

<table border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
  <tr>
    <td width="788">
      <table width="788" border="0" cellpadding="0" cellspacing="0">
        <tr> 
          <td valign="top"><img src="../../images/common/spacer.gif" width="10" height="15" /></td>
          <td bgcolor="#dbdbdb"> 
          <td valign="top"> 
          <td valign="top">&nbsp;</td>
        </tr>
        <tr> 
          <td width="170" valign="top" bgcolor="#e5eaf0"><img src="../../images/mk_pw/side_title.gif" alt="�������ơ�net�ˤĤ���" width="170" height="63"></td>
          <td width="1" bgcolor="#dbdbdb"><img src="../../images/common/spacer.gif" width="1" height="10" /> 
          <td width="12" valign="top"><img src="../../images/common/spacer.gif" width="12" height="10"> 
          <td valign="top"> 
            <table width="100%" border="0" cellpadding="0" cellspacing="0">
<tr> 
<td class="map"><a href="../../index.php">HOME</a>���䡡�ѥ���ɤΤ��䤤��碌</td>
</tr>
<tr>
<td><img src="../../images/common/spacer.gif" width="10" height="10" /></td>
</tr>
              <tr> 
                <td><img src="../../images/common/title_pw.gif" width="200" height="30"></td>
              </tr>
              <tr> 
                <td><img src="../../images/common/spacer.gif" width="10" height="30"></td>
              </tr>
              <?
if (!$msg) {
?>
              <tr> 
                <td class="description"> 
                  <p>�ѥ���ɤΤ��䤤��碌����դ��ޤ����� </p>
                  <p>�� </p>
                  <p>����Ͽ�Υ᡼�륢�ɥ쥹���Ƥˡ��ѥ���ɤ��Τ餻�Υ᡼������ꤷ�Ƥ���ޤ��Τǡ�<br>
                    �᡼��򤴳�ǧ��������</p>
                  <p> ��褷�ʤ����ϡ�������Ǥ�����̳�ɤޤǤ��䤤��碌��������<br>
                    <a href="mailto:k-pw@kikasete.net?subject=%81%a1%82%a8%96%e2%82%a2%8d%87%82%ed%82%b9"><img src="../../images/common/icon_mail.gif" width="14" height="9" border="0"> 
                    �������ơ�net ��̳��</a> </p>
                  <p>��</p>
                </td>
              </tr>
              <?
} else {
	$hidden[] = '<input type="hidden" name="mail_addr" ' . value($mail_addr) . '>';
	error_msg($msg, 'mk_pwrequest.php', $hidden);
}
?>

            </table>
          </td>
        </tr>
      </table>

</tr>
</table>

<? marketer_footer(BLUE); ?>

