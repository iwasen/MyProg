<!--#INCLUDE FILE="../melmel_inc.asp"-->
<!--#INCLUDE FILE="admin_inc.asp"-->
<%
'******************************************************
' System :めるめる・net事務局用ページ
' Content:予約一覧表示
'******************************************************

SetHeaderTitle "予約", TC_KOUKOKU
Response.CacheControl = "no-cache"
%>
<%
Dim DB, RS, SQL, line, koukoku_id

Set DB = OpenDB()
Set RS = Server.CreateObject("ADODB.RecordSet")
SQL = "SELECT KK_koukoku_id,KK_koukoku_name,KK_client_name,KK_koukoku_shuu,KK_busuu,WE_start_date" & vbCrLf & _
      "FROM V_KOUKOKU LEFT OUTER JOIN T_WEEK ON KK_koukoku_shuu=WE_shuu_code" & vbCrLf & _
      "WHERE KK_status=0" & vbCrLf & _
      "ORDER BY KK_koukoku_id"
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
function OnClick_cancel(koukoku_id) {
  if (confirm("予約をキャンセルします。よろしいですか？"))
    location.href = "yoyaku4.asp?id=" + koukoku_id;
}
function OnClick_nyuukou(koukoku_id) {
  location.href = "nyuukou2.asp?id=" + koukoku_id;
}
//-->
</SCRIPT>
</head>
<body>

<!--#INCLUDE FILE="admin_header.asp"-->

<table width="100%" border=0 cellpadding=0 cellspacing=0><tr><td>
<form>

<table width="100%">
  <tr>
    <td align="left">■予約の一覧 <font size="-1">（広告IDをクリックすると修正することができます）</font></td>
    <td align="right">件数：<%=RS.RecordCount%></td>
  </tr>
</table>

<table border=1 cellpadding=1 cellspacing=1 width="100%" align="center">
  <tr bgcolor="#FFE0E0">
    <th>広告ID</th>
    <th>広告名</th>
    <th>広告依頼主名</th>
    <th>掲載週</th>
    <th>発行部数</th>
    <th>処理</th>
  </tr>

<%
line = 1
Do Until RS.EOF
  If line Mod 2 = 0 Then
    Writeln "<tr bgcolor='#FFFFFF'>"
  Else
    Writeln "<tr bgcolor='#E0FFE0'>"
  End If
  koukoku_id = RS("KK_koukoku_id")
  Writeln "<td align='center'><a href='yoyaku2.asp?id=" & koukoku_id & "'>"  & koukoku_id & "</a></td>"
  Writeln "<td>" & ReplaceTag(RS("KK_koukoku_name")) & "</td>"
  Writeln "<td>" & ReplaceTag(RS("KK_client_name")) & "</td>"
  Writeln "<td align='center'>" & RS("KK_koukoku_shuu") & " (" & DateFormat(RS("WE_start_date")) & ")</td>"
  Writeln "<td align='right'>" & NumFormat(RS("KK_busuu")) & "</td>"
  Writeln "<td align='center'><input type='button' value='入稿' onClick='OnClick_nyuukou(""" & koukoku_id & """)'>"
  Writeln "<input type='button' value='ｷｬﾝｾﾙ' onClick='OnClick_cancel(""" & koukoku_id & """)'></td>"
  Writeln "</tr>"

  RS.MoveNext
  line = line + 1
Loop
RS.close
%>

</table>

<center>
<br>
<input type="button" value="新規予約" onClick="location.href='yoyaku2.asp'">
</center>

</form>
</td></tr></table>

<!--#INCLUDE FILE="admin_footer.asp"-->

</body>
</html>
