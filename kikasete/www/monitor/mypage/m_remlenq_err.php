<?
$top = './..';
$inc = "$top/../inc";
include("$inc/database.php");
include("$inc/common.php");
$inc = "$top/inc";
$img = "$top/image";
include("$inc/mn_request.php");

if ($_SESSION['ss_req_flg'] == 't') {
	$onload = 'opener_redirect();';
	$onclick = 'javascript:self.close();';
	$button = '���Ĥ��롡';
} else {
	$onload = '';
	$onclick = "location.href='{$_SESSION['ss_ret_url']}&status=$status'";
	$button = 'TOP�ڡ��������';
}
?>
<HTML>
<HEAD>
	<TITLE>�������ơ�net</TITLE>
	<META http-equiv=Content-Type content="text/html; charset=EUC-JP">
<script type="text/javascript">
<!--
function opener_redirect(){
	window.parent.opener.top.location.href="/monitor/index.php";
}
// -->
</script>
</HEAD>
<BODY onLoad="<?=$onload?>">
	<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
		<TBODY>
			<TR bgColor=#c0e2fa>
				<TD><IMG height=29 alt=�̾異�󥱡��� src="<?=$img?>/my_enq.gif" width=45 align=absMiddle>
					<SPAN class=mysttl> �̾異�󥱡��� </SPAN>
				</TD>
			</TR>
			<TR>
				<TD><IMG height=1 src="<?=$img?>/spacer.gif" width=1></TD></TR>
			<TR>
				<TD bgColor=#88b7d8><IMG height=1 src="<?=$img?>/spacer.gif" width=1></TD>
			</TR>
		</TBODY>
	</TABLE>
	<table width="100%" border="0" cellspacing="0" cellpadding="0">
		<tr>
			<td width="15">��</td>
			<td colspan="2">
				<table border="0" cellpadding="20" cellspacing="0" width="100%" bgcolor="white">
					<tr>
						<td align="center"><font color="#2b2b63"><br>
<b>���ѿ���������ޤ��󤬡����󥱡��Ȥ˥������������椷�Ƥ��뤿��<br>���󥱡��Ȥ����Ǥ���ޤ�����</b></font><br><br>
<font size="2">�����������Ϳ���Ƥ���ޤ��Τǡ���´���ƼϤ��������ޤ���<br><br>
����ʹߤ⤴���ϤΤۤɤ������ꤤ�������ޤ���</font><br><br>
							<hr width="100%">
							<input type="button" value="<?=$button?>" onclick="<?=$onclick?>">
						</td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
</BODY>
</HTML>
