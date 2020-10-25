<%
'******************************************************
' System :�A���P�[�g�W�v�T�[�r�X
' Content:�w�b�_�[�t�b�^
'******************************************************

'=== �߂�� ===
Const BACK_NONE = 0
Const BACK_LOGOFF = 1
Const BACK_TOP = 2
Const BACK_CLOSE = 3

Dim g_title, g_back

'=== �w�b�_ ===
Sub page_header()
	Dim section

	section = "�A���P�[�g�W�v�T�[�r�X"
%>
<table cellspacing=0 cellpadding=0 width="100%">
	<tr>
		<td align="left" style="font-size:x-small;font-weight:bold;color:#666666"><%=section%></td>
		<td align="right" valign=bottom norap><%=back_link()%></td>
	</tr>
</table>
<table border=0 width="100%" cellspacing=1 cellpadding=3 class="title">
	<tr onclick="location.reload()">
		<td align="center"><nobr><%=g_title%></nobr></td>
	</tr>
</table>
<hr class="header">
<%
End Sub

'=== ���ʃt�b�^ ===
Sub page_footer()
	Dim user_login_id, user_mail_addr, login

	user_login_id = Request.Cookies("user_login_id")
	user_mail_addr = Request.Cookies("user_mail_addr")

	If user_login_id <> "" Then
		login = "Login: " & user_mail_addr
	End If
%>
<hr class="footer">
<table cellspacing=0 cellpadding=0 width="100%">
	<tr>
		<td align="left" style="font-size:x-small;font-weight:bold;color:#666666"><%=login%></td>
		<td align="right" valign=bottom norap><%=back_link()%></td>
	</tr>
</table>
<div align="center">Copyright(c) <%=Year(Date)%> xx xxxxx Inc. All rights reserved.</div>
<%
End Sub

'=== �߂胊���N ===
Function back_link()
	Select Case g_back
	Case BACK_LOGOFF
		back_link = "<small><font color=blue>��</font><a href='" & GetRoot & "login/logoff.asp'>���O�I�t</a></small>"
	Case BACK_TOP
		back_link = "<small><font color=blue>��</font><a href='" & GetRoot & "index.asp'>�g�b�v�֖߂�</a></small>"
	Case BACK_CLOSE
		back_link = "<small><font color=blue>��</font><a href='javascript:window.close()'>����</a></small>"
	End Select
End Function

'=== �O���[�o���ϐ��ݒ� ===
Sub set_global(title, back)
	g_title = title
	g_back = back
End Sub
%>