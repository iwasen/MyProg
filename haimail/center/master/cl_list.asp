<!--#INCLUDE FILE="../inc/common.asp"-->
<!--#INCLUDE FILE="../inc/sub.asp"-->
<!--#INCLUDE FILE="../inc/database.asp"-->
<!--#INCLUDE FILE="../inc/login.asp"-->
<%
'******************************************************
' System :はいめーる・net事務局用ページ
' Content:不達メール一覧表示処理
'******************************************************

Dim sql, ds, line, from

SetHeaderTitle "メールアドレスクリーニング"

sql = "SELECT UD_count,MM_member_id,MM_regist_date,MM_mail_adr" & vbCrLf & _
			"FROM T_UNDELIVERED,T_MEMBER" & vbCrLf & _
			"WHERE MM_status<>'9' AND MM_member_id=UD_member_id" & vbCrLf & _
			"ORDER BY UD_count DESC,MM_member_id"
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
function onclick_delete() {
	var f = document.form1;
	var ok = false;
	if (f.delete_id.length) {
		for (var i = 0; i < f.delete_id.length; i++) {
			if (f.delete_id[i].checked) {
				ok = true;
				break;
			}
		}
	} else {
		if (f.delete_id.checked)
			ok = true;
	}
	if (ok) {
		if (confirm("選択したメールアドレスを削除します。よろしいですか？"))
			f.submit();
	} else
		alert("削除するメールアドレスを選択してください。");
}
//-->
</SCRIPT>
</head>
<body>

<!--#INCLUDE FILE="../inc/header.asp"-->

<table border=0 cellpadding=0 cellspacing=1 width="100%">
	<tr>
		<td>件数：<%=ds.RecordCount%></td>
		<td align="right">
<%
If ds.RecordCount > 0 Then
%>
			<input type="button" value=" 削除 " onclick="onclick_delete()">
<%
End If
%>
			<input type="button" value=" 戻る " onclick="location.href='index.asp'">
		</td>
	</tr>
</table>

<table border=1 cellpadding=0 cellspacing=1 width="100%">
	<form method="post" name="form1" action="cl_delete.asp">
	<tr bgcolor="#E0FFE0">
		<th>不達回数</th>
		<th>メンバーID</th>
		<th>登録日</th>
		<th>メールアドレス</th>
		<th>削除</th>
	</tr>
<%
line = 1
Do Until ds.EOF
	Writeln "<tr class='tc" & line Mod 2 & "'>"
	Writeln "<td align='right'>" & NumFormat(ds("UD_count").Value) & "</td>"
	Writeln "<td align='center'>" & ds("MM_member_id").Value & "</td>"
	Writeln "<td align='center'>" & DateFormat(ds("MM_regist_date").Value) & "</td>"
	Writeln "<td><a href='cl_edit.asp?id=" & ds("MM_member_id").Value & "' title='メールアドレスを修正します'>" & ReplaceTag(ds("MM_mail_adr").Value) & "</a></td>"
	Writeln "<td align='center'><input type='checkbox' name='delete_id' value='" & ds("MM_member_id").Value & "'></td>"
	Writeln "</tr>"
	ds.MoveNext
	line = line + 1
Loop
%>
	</form>
</table>

<!--#INCLUDE FILE="../inc/footer.asp"-->

</body>
</html>
