<!--#INCLUDE FILE="../inc/common.asp"-->
<!--#INCLUDE FILE="../inc/database.asp"-->
<!--#INCLUDE FILE="../inc/sub.asp"-->
<%
'******************************************************
' System :�͂��߁[��Enet�����Ǘp�y�[�W
' Content:�㗝�X���\������
'******************************************************

SetHeaderTitle "�㗝�X���"

Dim sql, ds, dairiten_id

dairiten_id = Request.QueryString("id")
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
<%
sql = "SELECT * FROM T_DAIRITEN WHERE DA_dairiten_id='" & dairiten_id & "'"
Set ds = CreateDynaset(sql, ORADYN_READONLY)
If Not ds.EOF Then
%>
	<table border=0 cellspacing=0 cellpadding=0 width='500'>
		<tr>
			<td class="m0">���㗝�X���</td>
		</tr>
		<tr>
			<td>
				<table border=0 cellspacing=2 cellpadding=3 width="100%">
					<tr>
						<td class="m1" width="30%">�㗝�X�h�c�@</td>
						<td class="n1"><%=dairiten_id%></td>
					</tr>
					<tr>
						<td class="m1">�㗝�X����</td>
						<td class="n1"><%=ReplaceTag(ds("DA_name").Value)%></td>
					</tr>
					<tr>
						<td class="m1">�㗝�X�N���X</td>
						<td class="n1"><%=ds("DA_class").Value%></td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
<%
End If
%>
	<br>
	<input type="button" value="�@����@" onclick="window.close()">
</center>
</body>
</html>
