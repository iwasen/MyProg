<!--#INCLUDE FILE="../inc/common.asp"-->
<!--#INCLUDE FILE="../inc/database.asp"-->
<!--#INCLUDE FILE="../inc/sub.asp"-->
<!--#INCLUDE FILE="../inc/login.asp"-->
<%
'******************************************************
' System :�͂��߁[��Enet�����Ǘp�y�[�W
' Content:�����������O�̕\��
'******************************************************

SetHeaderTitle "�����������O�\��"
Response.CacheControl = "no-cache"
%>
<%
Dim ds, sql, line, kind, nline

kind = Request.QueryString("kind")
If IsEmpty(kind) Then
	kind = "9"
End If

nline = Request.QueryString("nline")
If Len(nline) > 0 Then
	nline = CInt(nline)
Else
	nline = 50
End If

sql = "SELECT *" & vbCrLf & _
			"FROM T_AUTOLOG" & vbCrLf & _
			"WHERE AL_kind>=" & kind & vbCrLf & _
			"ORDER BY AL_seq_no DESC"
If nline > 0 Then
	sql = "SELECT * FROM (" & sql & ") WHERE ROWNUM<=" & nline
End If
Set ds = CreateDynaset(sql, ORADYN_READONLY)
%>
<html>
<head>
<meta HTTP-EQUIV="Content-Type" Content="text/html; charset=x-sjis">
<meta HTTP-EQUIV="Pragma" Content="no-cache">
<title><%=title_text%></title>
<link rel="stylesheet" type="text/css" href="../haimail.css">
<SCRIPT LANGUAGE=javascript>
<!--
function OnClick_kind(kind) {
	location.href = "auto_log.asp?kind=" + kind;
}
function OnClick_sakujo() {
	if (confirm("���O���폜���Ă�낵���ł����H"))
		location.href = "auto_log2.asp?delete=1";
}
//-->
</SCRIPT>
</head>
<body>

<!--#INCLUDE FILE="../inc/header.asp"-->

<form style="margin:0">
<input type="radio" name="kind" onclick="submit()" <%ValueChecked "9", kind%>>�G���[�̂�
<input type="radio" name="kind" onclick="submit()" <%ValueChecked "1", kind%>>�����{�G���[
<input type="radio" name="kind" onclick="submit()" <%ValueChecked "0", kind%>>�S��
<nobr><font class="hc">�\���s��</font><select name="nline" onchange="submit()"><%SelectDispLine nline%></select></nobr>

<table border=1 cellpadding=1 cellspacing=1 width="100%" align="center">
	<tr bgcolor="#e0e0ff">
		<th>���</th>
		<th>���b�Z�[�W</th>
		<th>���O����</th>
	</tr>
<%
line = 1
Do Until ds.EOF
	Select Case ds("AL_kind")
	Case "0"
		Writeln "<tr>"
		Writeln "<td align='center'><nobr>����</nobr></td>"
	Case "1"
		Writeln "<tr bgcolor='#ddffdd'>"
		Writeln "<td align='center'><nobr>����</nobr></td>"
	Case "9"
		Writeln "<tr bgcolor='#ffdddd'>"
		Writeln "<td align='center'><nobr>�G���[</nobr></td>"
	End Select
	Writeln "<td align='left'>" & ds("AL_message") & "</td>"
	Writeln "<td align='left'><nobr>" & ds("AL_datetime") & "</nobr></td>"
	Writeln "</tr>"

	ds.MoveNext
	line = line + 1
Loop
%>

</table>

<br>
<center>
<form>
<input type="button" value=" �폜 " onclick="OnClick_sakujo()">
</form>
</center>

</form>

<!--#INCLUDE FILE="../inc/footer.asp"-->

</body>
</html>
