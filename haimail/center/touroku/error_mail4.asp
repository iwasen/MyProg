<!--#INCLUDE FILE="../inc/common.asp"-->
<!--#INCLUDE FILE="../inc/sub.asp"-->
<!--#INCLUDE FILE="../inc/database.asp"-->
<!--#INCLUDE FILE="../inc/login.asp"-->
<%
'******************************************************
' System :�͂��߁[��Enet�����Ǘp�y�[�W
' Content:�o�^�G���[���[������
'******************************************************

'=== ���C������ ===
SetHeaderTitle "�ԐM���e�\��"

Dim seq_no, sql, ds

seq_no = Request.QueryString("no")
If IsEmpty(seq_no) Then
	Response.End
End If

sql = "SELECT * FROM T_TOUROKU_MAIL WHERE TM_seq_no=" & seq_no
Set ds = CreateDynaset(sql, ORADYN_READONLY)
If ds.EOF Then
	Response.End
End If
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
<table border=0 cellspacing=1 cellpadding=0 width="100%">
	<tr>
		<td class="m0">���������o�^�G���[���[������</td>
	</tr>
	<tr>
		<td colspan=2>
			<table border=1 cellspacing=0 cellpadding=3 bordercolor="navy" width="100%">
				<tr>
					<td>
					<xmp class="np">
<%
Response.Write ReadCLOB(ds("TM_body").Value)
%>
					</xmp>
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
