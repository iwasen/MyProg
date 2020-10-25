<!--#INCLUDE FILE="../melmel_inc.asp"-->
<!--#INCLUDE FILE="admin_inc.asp"-->
<%
'******************************************************
' System :めるめる・net事務局用ページ
' Content:自動処理ログの表示
'******************************************************

SetHeaderTitle "自動処理ログ表示", TC_OTHER
Response.CacheControl = "no-cache"
%>
<%
Dim DB, RS, SQL, line, kind

kind = Request.QueryString("kind")
If IsEmpty(kind) Then
  kind = "9"
End If

Set DB = OpenDB()
Set RS = Server.CreateObject("ADODB.RecordSet")
SQL = "SELECT *" & vbCrLf & _
      "FROM T_AUTOLOG" & vbCrLf & _
      "WHERE AL_kind>=" & kind & vbCrLf & _
      "ORDER BY AL_seq_no DESC"
RS.Open SQL, DB, adOpenForwardOnly, adLockReadOnly
%>
<html>
<head>
<meta HTTP-EQUIV="Content-Type" Content="text/html; charset=x-sjis">
<meta HTTP-EQUIV="Pragma" Content="no-cache">
<meta HTTP-EQUIV="Refresh" Content="180">
<meta name="GENERATOR" Content="Microsoft Visual Studio 6.0">
<title><%=title_text%></title>
<link rel="stylesheet" type="text/css" href="admin.css">
<SCRIPT LANGUAGE=javascript>
<!--
function OnClick_kind(kind) {
  location.href = "auto_log.asp?kind=" + kind;
}
function OnClick_sakujo() {
  if (confirm("ログを削除してよろしいですか？"))
    location.href = "auto_log2.asp?delete=1";
}
//-->
</SCRIPT>
</head>
<body>

<!--#INCLUDE FILE="admin_header.asp"-->

<table width="100%" border=0 cellpadding=0 cellspacing=0><tr><td>
<form>

<input type="radio" onClick="OnClick_kind('9')" <%If kind = "9" Then Response.Write " checked"%>>エラーのみ
<input type="radio" onClick="OnClick_kind('1')" <%If kind = "1" Then Response.Write " checked"%>>処理＋エラー
<input type="radio" onClick="OnClick_kind('0')" <%If kind = "0" Then Response.Write " checked"%>>全て


<table border=1 cellpadding=1 cellspacing=1 width="100%" align="center">
  <tr bgcolor="#e0e0ff">
    <th>種類</th>
    <th>メッセージ</th>
    <th>ログ日時</th>
  </tr>

<%
line = 1
Do Until RS.EOF
  Select Case RS("AL_kind")
  Case 0
    Writeln "<tr>"
    Writeln "<td align='center'>正常</nobr></td>"
  Case 1
    Writeln "<tr bgcolor='#ddffdd'>"
    Writeln "<td align='center'><nobr>処理</nobr></td>"
  Case 9
    Writeln "<tr bgcolor='#ffdddd'>"
    Writeln "<td align='center'><nobr>エラー</nobr></td>"
  End Select
  Writeln "<td align='left'>" & RS("AL_message") & "</td>"
  Writeln "<td align='left'><nobr>" & RS("AL_datetime") & "</nobr></td>"
  Writeln "</tr>"

  RS.MoveNext
  line = line + 1
Loop
RS.close
%>

</table>

<br>
<center>
<form>
<input type="button" value=" 削除 " onClick="OnClick_sakujo()">
</form>
</center>

</form>
</td></tr></table>

<!--#INCLUDE FILE="admin_footer.asp"-->

</body>
</html>
