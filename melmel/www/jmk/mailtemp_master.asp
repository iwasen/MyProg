<!--#INCLUDE FILE="../melmel_inc.asp"-->
<!--#INCLUDE FILE="admin_inc.asp"-->
<%
'******************************************************
' System :めるめる・net事務局用ページ
' Content:送信メールテンプレート一覧表示
'******************************************************

SetHeaderTitle "送信メールテンプレート", TC_MASTER
Response.CacheControl = "no-cache"

Dim DB, RS, SQL, line

Set DB = OpenDB()
Set RS = Server.CreateObject("ADODB.RecordSet")
SQL = "SELECT SM_id,SM_guide,SM_subject,SM_from" & vbCrLf & _
      "FROM T_SENDMAIL" & vbCrLf & _
      "ORDER BY SM_id"
RS.Open SQL, DB, adOpenKeyset, adLockReadOnly
%>
<html>
<head>
<meta HTTP-EQUIV="Content-Type" Content="text/html; charset=x-sjis">
<meta HTTP-EQUIV="Pragma" Content="no-cache">
<meta name="GENERATOR" Content="Microsoft Visual Studio 6.0">
<title><%=title_text%></title>
<link rel="stylesheet" type="text/css" href="admin.css">
</head>
<body>

<!--#INCLUDE FILE="admin_header.asp"-->

<table align="center" width="100%">
  <tr>
    <td align="left">■送信メールテンプレートの一覧 <font size="-1">（種類をクリックすると修正することができます）</font></td>
  </tr>
</table>

<table border=1 cellpadding=1 cellspacing=1 width="100%" align="center">
  <tr bgcolor="#FFE0E0">
    <th>種類</th>
    <th>Subject</th>
    <th>From</th>
  </tr>

<%
line = 1
Do Until RS.EOF
  If line Mod 2 = 0 Then
    Writeln "<tr bgcolor='#FFFFFF'>"
  Else
    Writeln "<tr bgcolor='#E0FFE0'>"
  End If
  Writeln "<td align='left'><a href='mailtemp_master2.asp?id=" & RS("SM_id") & "'>"  & RS("SM_guide") & "</a></td>"
  Writeln "<td align='left'>" & RS("SM_subject") & "</td>"
  Writeln "<td align='left'>" & ReplaceTag(RS("SM_from")) & "</td>"
  Writeln "</tr>"

  RS.MoveNext
  line = line + 1
Loop
RS.close
%>

</table>

<!--#INCLUDE FILE="admin_footer.asp"-->

</body>
</html>
