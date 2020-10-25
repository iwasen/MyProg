<!--#INCLUDE FILE="../melmel_inc.asp"-->
<!--#INCLUDE FILE="admin_inc.asp"-->
<%
'******************************************************
' System :めるめる・net事務局用ページ
' Content:広告マスタ一覧表示
'******************************************************

SetHeaderTitle "広告マスタ", TC_MASTER
Response.CacheControl = "no-cache"
%>
<%
Dim DB, RS, SQL, line, status, sort

Sub TableHeader(text, sno)
  If sno = sort Then
    Writeln "<th><font color='brown'>" & text & "</font></th>"
  Else
    Writeln "<th class='sort' onClick='location.href=""koukoku_master.asp?sort=" & sno & """'>" & text & "</th>"
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
SQL = "SELECT KK_koukoku_id,KK_koukoku_name,KK_client_name,KK_company,KK_koukoku_shuu,KK_status" & vbCrLf & _
      "FROM V_KOUKOKU" & vbCrLf & _
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
<SCRIPT LANGUAGE=javascript>
<!--
function OnClick_koukoku_id(id) {
  var win;
  win = window.open("koukoku_info.asp?id=" + id, "koukoku_info", "resizable=yes,scrollbars=yes,width=840");
  win.focus();
}
//-->
</SCRIPT>
</head>
<body>

<!--#INCLUDE FILE="admin_header.asp"-->

<table align="center" width="100%">
  <tr>
    <td align="left">■広告の一覧 <font size="-1">（ヘッダをクリックするとその項目でソート、広告IDをクリックすると内容を参照することができます）</font></td>
    <td align="right">件数：<%=RS.RecordCount%></td>
  </tr>
</table>

<table border=1 cellpadding=1 cellspacing=1 width="100%" align="center">
  <tr bgcolor="#FFE0E0">
<%
TableHeader "広告ID", 1
TableHeader "広告名", 2
TableHeader "広告依頼主名", 3
TableHeader "組織名", 4
TableHeader "掲載週", 5
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
  Writeln "<td align='center'><a href='JavaScript:OnClick_koukoku_id(""" & RS("KK_koukoku_id") & """)'>" & RS("KK_koukoku_id") & "</a></td>"
  Writeln "<td>" & ReplaceTag(RS("KK_koukoku_name")) & "</td>"
  Writeln "<td>" & ReplaceTag(RS("KK_client_name")) & "</td>"
  Writeln "<td>" & BlankToBR(ReplaceTag(RS("KK_company"))) & "</td>"
  Writeln "<td align='center'>" & RS("KK_koukoku_shuu") & "</td>"
  Select Case RS("KK_status")
  Case 0
    status = "<font color='green'><nobr>予約</nobr></font>"
  Case 1
    status = "<font color='blue'><nobr>入稿済み</nobr></font>"
  Case 2
    status = "<font color='purple'><nobr>ﾃｽﾄﾒｰﾙ送信済み</nobr></font>"
  Case 3
    status = "<font color='red'><nobr>依頼済み</nobr></font>"
  Case 4
    status = "<font color='maroon'><nobr>報告書出力済み</nobr></font>"
  Case 9
    status = "<font color='black'><nobr>終了</nobr></font>"
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
