<!--#INCLUDE FILE="../inc/common.asp"-->
<!--#INCLUDE FILE="../inc/sub.asp"-->
<!--#INCLUDE FILE="../inc/database.asp"-->
<%
'******************************************************
' System :�͂��߁[��Enet�����ǁE�c�Ƌ��ʃy�[�W
' Content:HTML�p�w�b�_�A���z���A�t�b�^�\������
'******************************************************

'=== ���C������ ===
SetHeaderTitle "�w�b�_�A���z���A�t�b�^�\��"

Dim body, header, impression, footer

header = Request.Form("header")
impression =Request.Form("impression")
footer = Request.Form("footer")

body = "<br><center><h1><font color='blue'>�{ ��</font></h1></center><br>"
body = header & body & impression & footer
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
		<td colspan=2>
			<table border=1 cellspacing=0 cellpadding=3 bordercolor="navy" width="100%">
				<tr>
					<td><%=body%></td>
				</tr>
			</table>
		</td>
	</tr>
</table>
<input type="button" value=" ���� " onclick="window.close()">
</center>

</body>
</html>
