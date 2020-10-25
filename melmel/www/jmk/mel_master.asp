<!--#INCLUDE FILE="../melmel_inc.asp"-->
<!--#INCLUDE FILE="admin_inc.asp"-->
<%
'******************************************************
' System :めるめる・net事務局用ページ
' Content:発行者マスタ一覧表示
'******************************************************

SetHeaderTitle "発行者マスタ", TC_MASTER
Response.CacheControl = "no-cache"

Dim DB, RS, SQL, line, status, sort

Sub TableHeader(text, sno)
  If sno = sort Then
    Writeln "<th><font color='brown'>" & text & "</font></th>"
  Else
    Writeln "<th class='sort' onClick='location.href=""mel_master.asp?sort=" & sno & """'>" & text & "</th>"
  End If
End Sub

sort = Request.QueryString("sort")
If IsEmpty(sort) Then
  sort = 1
Else
  sort = CInt(sort)
End If

Set DB = OpenDB()
Set RS = Server.CreateObject("ADODB.RecordSet")
SQL = "SELECT MM_mel_id,MM_password,MM_h_name,MM_mail_adr,MM_regist_date,MM_status" & vbCrLf & _
      "FROM T_MELMEL" & vbCrLf & _
      "ORDER BY " & sort
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
    <td align="left">■発行者の一覧 <font size="-1">（ヘッダをクリックするとその項目でソート、発行者IDをクリックすると修正することができます）</font></td>
    <td align="right">件数：<%=RS.RecordCount%></td>
  </tr>
</table>

<table border=1 cellpadding=1 cellspacing=1 width="100%" align="center">
  <tr bgcolor="#FFE0E0">
<%
TableHeader "発行者ID", 1
TableHeader "パスワード", 2
TableHeader "発行者名", 3
TableHeader "e-mailアドレス", 4
TableHeader "登録日", 5
TableHeader "状態", 6
%>
  </tr>
<%
line = 1
Do Until RS.EOF
  If line Mod 2 = 0 Then
    Writeln "<tr bgcolor='#FFFFFF'>"
  Else
    Writeln "<tr bgcolor='#E0FFE0'>"
  End If
  Writeln "<td align='center'><a href='mel_master2.asp?id=" & RS("MM_mel_id") & "'>"  & RS("MM_mel_id") & "</a></td>"
  Writeln "<td align='left'>" & RS("MM_password") & "</td>"
  Writeln "<td align='left'>" & ReplaceTag(RS("MM_h_name")) & "</td>"
  Writeln "<td align='left'>" & ReplaceTag(RS("MM_mail_adr")) & "</td>"
  Writeln "<td align='center'>" & DateFormat(RS("MM_regist_date")) & "</td>"
  Select Case RS("MM_status")
  Case 0
    status = "活動中"
  Case 1
    status = "<font color='red'>一時停止</font>"
  Case 8
    status = "<font color='magenta'>休会中</font>"
  Case 9
    status = "<font color='blue'>退会</font>"
  Case Else
    status = "不明"
  End Select
  Writeln "<td align='center'>" & status & "</td>"
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
