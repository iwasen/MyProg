<!--#INCLUDE FILE="../inc/common.asp"-->
<!--#INCLUDE FILE="../inc/sub.asp"-->
<!--#INCLUDE FILE="../inc/database.asp"-->
<%
'******************************************************
' System :�͂��߁[��Enet�c�Ɨp�y�[�W
' Content:���[���R���e���c�G���[�\������
'******************************************************

'=== ���C������ ===
SetHeaderTitle "���[���R���e���c�G���["

Dim mail_err, body, ary, s, i, err_msg

Set mail_err = Session("mail_err")
'Session("mail_err") = Empty

If Not IsObject(mail_err) Then
	Response.End
End If

body = Session("body_err")
ary = Split(body, vbCrLf)
%>
<html>
<head>
<meta HTTP-EQUIV="Content-Type" Content="text/html; charset=x-sjis">
<meta HTTP-EQUIV="Pragma" Content="no-cache">
<title><%=title_text%></title>
<link rel="stylesheet" type="text/css" href="../haimail.css">
</head>
<body>

<center>
<table border=0 cellspacing=1 cellpadding=0>
	<tr>
		<td class="m0">�����[���R���e���c�ɃG���[������܂�</td>
	</tr>
	<tr>
		<td colspan=2>
			<table border=1 cellspacing=0 cellpadding=3 bordercolor="navy" width="100%">
				<tr>
					<td>
					<font class="np">
<%
For i = 0 To Ubound(ary)
	Writeln "<nobr>" & Server.HTMLEncode(ary(i)) & "</nobr><br>"
	err_msg = mail_err.Item(i)
	If Not IsEmpty(err_msg) Then
		Writeln "<font color='red'>" & ReplaceTag(err_msg) & "</font>"
	End If
Next
%>
					</font>
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>
<input type="button" value=" ���� " onclick="window.close()">
</center>

</body>
</html>
