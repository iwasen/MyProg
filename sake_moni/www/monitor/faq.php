<?
$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/get_form.php");
include("$inc/check.php");
include("$inc/encrypt.php");
include("$inc/login_log.php");

// ���ϥѥ�᡼������
$mail_addr = get_mail_addr($_POST['mail_addr']);
$password = get_password($_POST['password']);
$enquete_id = get_password($_POST['enquete_id']);
$id = $_GET['id'];

if ($mail_addr != '') {
	if ($password == '')
		$err_msg = '�ѥ���ɤ����Ϥ���Ƥ��ʤ��褦�Ǥ�';
	elseif (!check_mail_addr($mail_addr))
		$err_msg = '�᡼�륢�ɥ쥹�η������������ʤ��褦�Ǥ�';
	else {
		$sql = sprintf("SELECT mn_monitor_id FROM t_monitor WHERE mn_mail_addr_low=%s AND mn_password=%s AND mn_status<>9",
				sql_char(strtolower($mail_addr)),
				sql_char($password));
		$result = db_exec($sql);
		if (pg_num_rows($result)) {
			$fetch = pg_fetch_object($result, 0);

			login_log(1, $mail_addr, $password, $fetch->mn_monitor_id);

			session_start();
			$_SESSION['ss_monitor_id'] = $fetch->mn_monitor_id;

			if ($enquete_id)
				redirect("m_remlenq3a.php?enquete_id=$enquete_id");
			else
				redirect('m_index.php');
		} else
			$err_msg = '�᡼�륢�ɥ쥹�ޤ��ϥѥ���ɤ��������ʤ��褦�Ǥ�';
	}
} elseif (strlen($id) == 14) {
	decrypt_param($id, $enquete_id, $monitor_id);
	if ($monitor_id != 0) {
		$sql = "SELECT mn_mail_addr FROM t_monitor WHERE mn_monitor_id=$monitor_id AND mn_status<>9";
		$mail_addr = db_fetch1($sql);
	} else
		$enquete_id = '';
}

login_log(1, $mail_addr, $password, null);
?>
<HTML>
<HEAD>
<TITLE>�������ʪ�Υ��󥱡��ȥ�˥����ä褯��������</TITLE>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<meta http-equiv="Content-Script-Type" content="text/JavaScript">
<link rel="stylesheet" href="style.css" type="text/css">
<script language="JavaScript" src="mouseover.js"></script>
</HEAD>
<body bgcolor=#DDDDDD text=#333333 link=#333333 vlink=#333333 alink=#333333 
leftmargin=0 topmargin=0 rightmargin=0 bottommargin=0 marginwidth=0 marginheight=0>
<a name="top"></a>
<TABLE WIDTH=760 BORDER=0 CELLPADDING=0 CELLSPACING=0>
<TR>
<TD bgcolor=#FFFFFF valign=top height=900>

	<TABLE WIDTH=720 BORDER=0 CELLPADDING=0 CELLSPACING=0>
	<TR>
	<TD><IMG SRC="images/monitor00_title.gif" WIDTH=720 HEIGHT=106 ALT="�������ʪ�Υ��󥱡��ȥ�˥���"></TD>
	</TR>
	<TR>
	<TD>
		<table border="0" cellspacing="0" cellpadding="10" width="100%">
		<tr>
			<td align="right"><span class="t12"><a href="m_login.php">���������̤����</a></span></td>
		</tr>
		</table>
	</TD>
	</TR>
	<TR>
	<TD align="center">
		<table border="0" cellspacing="0" cellpadding="2" width="508">
		<a name="q1"></a>
		<tr bgcolor="#dfeffe">
			<td width="35"><img src="images/q.gif" alt="����" width="32" height="32" border="0"></td>
			<td width="465" class="t12"><b>�������ʪ���󥱡��ȥ�˥����äơ�����</b></td>
		</tr>
		<tr bgcolor="#FDF1E6" valign="top">
			<td><img src="images/a.gif" alt="����" width="32" height="32" border="0"></td>
			<TD bgcolor="#fdf1e6" class=t12>��˥������罸��<A href="http://www.kikasete.net/" 
                  target=_blank>�������ơ�net</A>��<A href="http://www.imi.ne.jp/" 
                  target=_blank>iMi�ͥå�</A>���̤��ƹԤäƤ���ޤ��� 
			  �������ʪ�ˤĤ��Ƥ䡢���������Ƥ��뤪Ź�ˤĤ��Ƥ�ɾ���ʤɤ�ףţ¥��󥱡��ȤˤƤ�ʹ�����ޤ��� 
			  �ףţ¥��󥱡��Ȥν������Ǥ����衢�Żҥ᡼��Ǥ��Τ餻���ޤ��� 
			  ���󥱡����Ѥ����ѥڡ����˥����󤷤Ƥ������������󥱡��Ȥˤ��������������ޤ��� 
			  ��ư���֤ϡ���������ǯ�������������ǯ�������ͽ��Ǥ���</TD>
		</tr>
		<tr valign="top">
			<td width="35"><img src="images/spacer.gif" width="35" height="35" border="0"></td>
			<td width="465" class="t10" align="right"><a href="#top">�����Υڡ����Υȥåפ����</a></td>
		</tr>
		<a name="q2"></a>
		<tr bgcolor="#dfeffe">
			<td><img src="images/q.gif" alt="����" width="32" height="32" border="0"></td>
			<td class="t12"><b>��������ϤɤΤ褦�˻�ʧ����ΤǤ���������</b></td>
		</tr>
		<tr bgcolor="#FDF1E6" valign="top">
			<td><img src="images/a.gif" alt="����" width="32" height="32" border="0"></td>
			<TD bgcolor="#fdf1e6" 
                  class=t12><B>�ڤ������ơ�net�ۤ��̤��Ƥ����礤����������</B>�ϡ��������ơ�net�ݥ���ȤȤ��ơ� <BR>
			    <B>��iMi�ͥåȡۤ��̤��Ƥ����礤����������</B>�ϡ�iMi�ݥ���ȤȤ��ơ� <BR>
			  ���󥱡��Ȳ�����������ޤǤ˺����夲�ޤ��� </TD>
		</tr>
		<tr valign="top">
			<td><img src="images/spacer.gif" width="35" height="35" border="0"></td>
			<td class="t10" align="right"><a href="#top">�����Υڡ����Υȥåפ����</a></td>
		</tr>
		<a name="q3"></a>
		<tr bgcolor="#dfeffe">
			<td><img src="images/q.gif" alt="����" width="32" height="32" border="0"></td>
			<td class="t12"><b>�᡼�륢�ɥ쥹���ѹ��������ΤǤ���������</b></td>
		</tr>
		<tr bgcolor="#FDF1E6" valign="top">
			<td><img src="images/a.gif" alt="����" width="32" height="32" border="0"></td>
			<TD bgcolor="#fdf1e6" 
                  class=t12>�᡼�륢�ɥ쥹���ѹ����������ϡ�������塢�ȥåץڡ����α���ˤ���ޤ��ڥ᡼�륢�ɥ쥹���ѹ��ۤ����ѹ����Ƥ��������ޤ���</TD>
		</tr>
		<tr valign="top">
			<td><img src="images/spacer.gif" width="35" height="35" border="0"></td>
			<td class="t10" align="right"><a href="#top">�����Υڡ����Υȥåפ����</a></td>
		</tr>
		<a name="q4"></a>
		<tr bgcolor="#dfeffe">
			<td><img src="images/q.gif" alt="����" width="32" height="32" border="0"></td>
			<td class="t12"><b>�ѥ���ɤ��ѹ��������ΤǤ���������</b></td>
		</tr>
		<tr bgcolor="#FDF1E6" valign="top">
			<td><img src="images/a.gif" alt="����" width="32" height="32" border="0"></td>
			<td class="t12">�ѥ���ɤ��ѹ����������ϡ�������塢�ȥåץڡ����α���ˤ���ޤ��ڥѥ�����ѹ��ۤ����ѹ����Ƥ��������ޤ���</td>
		</tr>
		<tr valign="top">
			<td><img src="images/spacer.gif" width="35" height="35" border="0"></td>
			<td class="t10" align="right"><a href="#top">�����Υڡ����Υȥåפ����</a></td>
		</tr>
		<a name="q5"></a>
		<tr bgcolor="#dfeffe">
			<td><img src="images/q.gif" alt="����" width="32" height="32" border="0"></td>
			<td class="t12"><b>�ѥ���ɤ�˺��Ƥ��ޤä��ΤǤ���������</b></td>
		</tr>
		<tr bgcolor="#FDF1E6" valign="top">
			<td><img src="images/a.gif" alt="����" width="32" height="32" border="0"></td>
			<td class="t12">��������̤Ρ�<A 
                  href="m_pw.php">�ѥ����˺��Ϥ����餫��</A>�פ�ꤪ��礻���������ȥ᡼��ˤƥѥ���ɤ��Τ餻�������ޤ���</td>
		</tr>
		<tr valign="top">
			<td><img src="images/spacer.gif" width="35" height="35" border="0"></td>
			<td class="t10" align="right"><a href="#top">�����Υڡ����Υȥåפ����</a></td>
		</tr>
		<a name="q6"></a>
		<tr bgcolor="#dfeffe">
			<td><img src="images/q.gif" alt="����" width="32" height="32" border="0"></td>
			<td class="t12"><b>���󥱡��Ȥ��ʤ��ʤ��Ϥ��ʤ��ΤǤ���������</b></td>
		</tr>
		<tr bgcolor="#FDF1E6" valign="top">
			<td><img src="images/a.gif" alt="����" width="32" height="32" border="0"></td>
			<td class="t12">���󥱡��Ȥϼ�ˡ��ߤʤ��ޤ�����������֤�Ź�סʤ����礤���������ݤˤ����������������ˤ��Ȥ˹Ԥ��ޤ��� 
                  ��Ź�ˤ�äƤϥ��󥱡��Ȥ����٤��ۤʤäƤ��ޤ��ޤ�����λ������������</td>
		</tr>
		<tr valign="top">
			<td><img src="images/spacer.gif" width="35" height="35" border="0"></td>
			<td class="t10" align="right"><a href="#top">�����Υڡ����Υȥåפ����</a></td>
		</tr>
		<a name="q7"></a>
		<tr bgcolor="#dfeffe">
			<td><img src="images/q.gif" alt="����" width="32" height="32" border="0"></td>
			<td class="t12"><b>�����󤷤Ƥ⥢�󥱡��Ȥ�ɽ������Ƥ��ʤ��ΤǤ���������</b></td>
		</tr>
		<tr bgcolor="#FDF1E6" valign="top">
			<td><img src="images/a.gif" alt="����" width="32" height="32" border="0"></td>
			<td class="t12">���߼»���Υ��󥱡��Ȥ�̵����硢���󥱡��Ȥ�ɽ������Ƥ���ޤ��� 
                  �缡���󥱡��ȤΤ��ꤤ�򤷤Ƥ���ͽ��Ǥ��Τǡ����κݤϤ��Ҥ����Ϥ򤪴ꤤ�������ޤ���</td>
		</tr>
		<tr valign="top">
			<td><img src="images/spacer.gif" width="35" height="35" border="0"></td>
			<td class="t10" align="right"><a href="#top">�����Υڡ����Υȥåפ����</a></td>
		</tr>
		<a name="q8"></a>
		<tr bgcolor="#dfeffe">
		  <td><img src="images/q.gif" alt="����" width="32" height="32" border="0"></td>
		  <td class="t12"><b>�����������Ƥ��뤪Ź���Ѥ�äƤ��ޤä��ΤǤ���������</b></td>
		</tr>
		<tr bgcolor="#dfeffe">
		  <td valign="top" bgcolor="#FDF1E6"><img src="images/a.gif" alt="����" width="32" height="32" border="0"></td>
		  <td valign="top" bgcolor="#FDF1E6" class="t12">�����ۤ������Ѥ��Ƥ��뤪Ź����Ź�ʤɤǡ������������Ƥ��뤪Ź���Ѥ�äƤ��ޤä����ϡ����Ѥ�����Ǥ����֤������ʪ�Υ��󥱡��ȥ�˥�����̳�ɡפޤǤ�Ϣ����������</td>
		</tr>
		<tr>
		  <td valign="top"><img src="images/spacer.gif" width="35" height="35" border="0"></td>
		  <td align="right" valign="top" class="t10"><a href="#top">�����Υڡ����Υȥåפ����</a></td>
		</tr>
		<a name="q9"></a>
		<tr bgcolor="#dfeffe">
			<td><img src="images/q.gif" alt="����" width="32" height="32" border="0"></td>
			<td class="t12"><b>���Υ��󥱡��ȤϤɤ����ԤäƤ���Ρ�</b></td>
		</tr>
		<tr bgcolor="#FDF1E6" valign="top">
			<td><img src="images/a.gif" alt="����" width="32" height="32" border="0"></td>
			<td class="t12">������ҡ�������������<A href="http://www.xxxxxxx.co.jp/" 
                  target=_blank>http://www.xxxxxxx.co.jp/</A>�ˤˤƹԤäƤ���ޤ���������ҡ������������Ϻ�ǯ����ǯ�ܤ�ޤ����ޡ����ƥ��󥰲�ҤǤ���<BR>�Ŀ;���ϳ�����ҡ������������֤������ʪ���󥱡��ȥ�˥����׻�̳�ɤ����Ť˴������Ƥ���ޤ���</td>
		</tr>
		<tr valign="top">
			<td><img src="images/spacer.gif" width="35" height="35" border="0"></td>
			<td class="t10" align="right"><a href="#top">�����Υڡ����Υȥåפ����</a></td>
		</tr>
		</table>
	</TD>
	</TR>
	<TR>
	<TD>
		<table border="0" cellspacing="0" cellpadding="20" width="100%">
		<tr>
		  <td align="right"><table width="300" border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td><img src="images/td_top.gif" width="301" height="39"></td>
            </tr>
            <tr>
              <td style="border-color:#8EC8FF; border-style:solid; border-width:1px; padding:15px" class="t12">�嵭�ʳ��ˤ������������������ޤ����顢
              <br>
��̳�ɤޤǤ����䤯��������<br>
<br>
����礻�衡����<a href="mailto:sake_moni@xxxxxxx.co.jp?subject=%82%a8%96%e2%82%a2%8d%87%82%ed%82%b9">sake_moni@xxxxxxx.co.jp</a></td>
            </tr>
            <tr>
              <td><img src="images/td_btt.gif" width="301" height="20"></td>
            </tr>
          </table></td>
		  </tr>
		<tr>
			<td align="right"><span class="t12"><a href="m_login.php">���������̤����</a></span></td>
		</tr>
		</table>
	</TD>
	</TR>
	<TR>
	<TD><IMG SRC="images/footer_bar.gif" WIDTH=720 HEIGHT=2></TD>
	</TR>
	<TR>
	<TD align=right><IMG SRC="images/copyright.gif" WIDTH=349 HEIGHT=22 ALT="copyright"></TD>
	</TR>
	<TR>
	<TD align=right>
<SPAN class="copyright">���Υۡ���ڡ����˻Ȥ��Ƥ���������̿����������ϡ�����&nbsp;<a href="http://www.xxxxxxx.co.jp/" target="_blank">������ҡ�����������</a>&nbsp;�˵�°���ޤ���<BR>
̵�Ǥ�ʣ���������Ѥ��뤳�Ȥ�ˡΧ�ˤ��ؤ����Ƥ��ޤ���<BR><BR></SPAN>
	</TD>
	</TR>
	</TABLE>

</TD>
<TD background="images/bg_gray.gif"><IMG SRC="images/spacer.gif" WIDTH=40 HEIGHT=1></TD>
</TR>
</table>

</BODY>
</HTML>