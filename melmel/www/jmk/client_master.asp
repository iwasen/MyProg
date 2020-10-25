<!--#INCLUDE FILE="../melmel_inc.asp"-->
<!--#INCLUDE FILE="admin_inc.asp"-->
<%
'******************************************************
' System :めるめる・net事務局用ページ
' Content:クライアントマスタ一覧表示
'******************************************************

SetHeaderTitle "クライアントマスタ", TC_MASTER
Response.CacheControl = "no-cache"

Dim DB, RS, SQL, line

Set DB = OpenDB()
Set RS = Server.CreateObject("ADODB.RecordSet")
SQL = "SELECT * FROM T_CLIENT ORDER BY CM_client_id"
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

<center>
<table width="100%">
  <tr>
    <td align="left">■クライアントマスタの一覧 <font size="-1">（クライアントIDをクリックすると修正することができます）</font></td>
    <td align="right">件数：<%=RS.RecordCount%></td>
  </tr>
</table>

<table border=1 cellpadding=1 cellspacing=1 width="100%">
  <tr bgcolor="#FFE0E0">
    <th>クライアントID</th>
    <th>クライアント名</th>
    <th>組織名</th>
    <th>担当者名</th>
  </tr>
<%
line = 1
Do Until RS.EOF
  If line Mod 2 = 0 Then
    Writeln "<tr bgcolor='#FFFFFF'>"
  Else
    Writeln "<tr bgcolor='#E0FFE0'>"
  End If
  Writeln "<td align='center'><a href='client_master3.asp?id=" & RS("CM_client_id") & "'>"  & RS("CM_client_id") & "</a></td>"
  Writeln "<td>" & ReplaceTag(RS("CM_client_name")) & "</td>"
  Writeln "<td>" & BlankToBR(ReplaceTag(RS("CM_company"))) & "</td>"
  Writeln "<td>" & BlankToBR(ReplaceTag(RS("CM_tantousha"))) & "</td>"

  RS.MoveNext
  line = line + 1
Loop
RS.close
%>

</table>

<form id=form1 name=form1>
<input type="button" value="新規登録" onClick="location.href='client_master2.asp'" id=button1 name=button1>
</form>

</center>

<!--#INCLUDE FILE="admin_footer.asp"-->

</body>
</html>
