<?
$top = './..';
$inc = "$top/../inc";
include("$inc/mail.php");
include("$inc/encrypt.php");
$inc = "$top/inc";
$img = "$top/image";
include("$inc/mn_regist.php");
include("$inc/mn_header.php");

// ��Ͽ���դ��᡼������
function mail_send($reg, $seq_no) {
	get_mail_template('reg_mn1', $subject, $from, $cc, $bcc, $repty_to, $body);
	$fetch = get_system_info('sy_url_monitor');
	$body = str_replace('%MAIL_ADDR%', $reg->mail_addr, $body);
	$body = str_replace('%REGIST_URL%', "{$fetch->sy_url_monitor}regi-login.php?key=" . encrypt_number($seq_no, 8), $body);
	send_mail($subject, $reg->mail_addr, $from, $body, $cc, $bcc, $reply_to);
}

// ���å�������
session_start();
if (!isset($_SESSION['ss_regist']))
	redirect('regist2.php');
$reg = &$_SESSION['ss_regist'];

db_begin_trans();

// ����Ͽ�ơ��֥����¸
$seq_no = $reg->save_db();

// ���ե��ꥨ���Ȳ���Ͽ������
if ($cookie_affiliate_id) {
	$rec['afc_kari_count'] = 'afc_kari_count+1';
	db_update('t_affiliate_cnt', $rec, "afc_affiliate_id=$cookie_affiliate_id");
}

db_commit_trans();

// �᡼������
mail_send($reg, $seq_no);

// ���å�����ѿ����
session_destroy();
?>
<?monitor_menu()?>
<!-- ����ƥ�����ΤΥ������Ϥ��δ֤�ɽ�� -->
<TABLE cellSpacing=0 cellPadding=0 border=0 width="100%">
	<TBODY>
	<TR>
		<TD align="left" bgcolor="#EEEEEE" background="<?=$img?>/tit_bk.gif"><IMG src="<?=$img?>/tit_reg.gif" width=590 height=30 border=0 alt="��˥�����Ͽ"></TD>
		<TD align="right" bgcolor="#EEEEEE" background="<?=$img?>/tit_bk.gif"><IMG src="<?=$img?>/tit_bk.gif" width=10 height=30 border=0></TD>
	</TR>
</TABLE>
<TABLE cellSpacing=0 cellPadding=0 border=0 width="600">
<TR>
<TD align="left" colspan="2"><IMG src="../image/spacer.gif" width=1 height=15 border=0></TD>
</TR>
<TR>
<TD align="left"><IMG src="../image/reg5_m.gif" width=600 height=36 border=0 alt="����Ͽ��λ"></TD>
</TR>
<TR>
<TD align="left" colspan="2"><IMG src="../image/spacer.gif" width=1 height=15 border=0></TD>
</TR>
<TR>
<TD colspan="2">

	<TABLE cellSpacing=0 cellPadding=0 border=0 width="100%">
	<TBODY>
	<TR>
	<TD align="left">
<SPAN class=fs12_lh120>
<font color="#666666">
<B>����Ͽ����դ��ޤ�����</B><BR>
<BR>
�������ޤ���Ͽĺ���ޤ����᡼�륢�ɥ쥹���Ƥˡ�����Ͽ��ǧ�᡼��ۤ����餻��ĺ���Ƥ���ޤ��ΤǤ���ǧ���������� <br>
������Υ᡼��ϡ��᡼�륢�ɥ쥹����������Ͽ����Ƥ��뤫�ȡ����ܿ��ͤǤ��뤫�γ�ǧ�Τ���ȤʤäƤ��ޤ��� <br>
<BR>
</font></SPAN>
	</TD>
	</TR>
	<TR>
	<TD align="left" bgcolor="#EEEEEE">

	<TABLE cellSpacing=0 cellPadding=10 border=0 width="100%">
	<TBODY>
	<TR>
	<TD align="left">
<SPAN class=fs12_lh120>
<font color="#CC0000">
<b>����Ͽ��ǧ�᡼��ۤ򤴳�ǧ�ξ塢���Υ᡼��˵��ܤ��줿�գң̤򥯥�å�������Ͽ��³����λ�����Ƥ��������� </b><BR>
<BR>
�� ���μ�³����λ���ޤ���ȡ���Ͽ���줿���ȤˤϤʤ�ޤ���ΤǤ���ղ�������<br>
�� ����å��򤷤Ƥ�ڡ����������ʤ����ϡ��գң̤򥳥ԡ����ƥ֥饦���Υ��ɥ쥹�С���ľ��Ž���դ��Ʋ�������<BR>
</font>
</SPAN>
</TD>
</TR>
</TABLE>
</TD>
</TR>
</TBODY>
</TABLE>
<DIV align="left"><img height="75" width="600" src="../image/reg_flow05.gif" border="0" alt="����Ͽ��λ" vspace="15"></DIV>

</TD>
</TR>
</TBODY>
</TABLE>
<!-- ����ƥ�����ΤΥ������Ϥ��δ֤�ɽ�� -->
			</TD>
		</TR>
	</TBODY>
</TABLE>
</BODY>
</HTML>
