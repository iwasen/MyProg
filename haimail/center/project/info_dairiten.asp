<!--#INCLUDE FILE="../inc/common.asp"-->
<!--#INCLUDE FILE="../inc/database.asp"-->
<!--#INCLUDE FILE="../inc/sub.asp"-->
<%
'******************************************************
' System :Ν’ί[ιEnet±Ηpy[W
' Content:γXξρC³
'******************************************************

SetHeaderTitle "γXξρ"

Dim sql, ds, dairiten_id, id, dairiten_name, dairiten_class

dairiten_id = Request.QueryString("id")
sql = "SELECT * FROM T_DAIRITEN WHERE DA_dairiten_id='" & dairiten_id & "'"
Set ds = CreateDynaset(sql, ORADYN_READONLY)
If Not ds.EOF Then
	dairiten_name = ds("DA_name").Value
	dairiten_class = ds("DA_class").Value
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
	<table border=0 cellspacing=0 cellpadding=0 width='500'>
		<tr>
			<td class="m0">‘γXξρ </font></td>
		</tr>
		<tr>
			<td>
				<table border=0 cellspacing=2 cellpadding=3 width="100%">
					<tr>
						<td class="m3">γXhc@</td>
						<td class="n3"><%=dairiten_id%></td>
					</tr>
					<tr>
						<td class="m3">γXΌΜ</td>
						<td class="n3"><%=dairiten_name%></td>
					</tr>
					<tr>
						<td class="m3">γXNX</td>
						<td class="n3"><%=dairiten_class%></td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
	<br>
	<input type="button" value="@ΒΆι@" onclick="window.close()">
</center>
</body>
</html>
