<!--#INCLUDE FILE="../melmel_inc.asp"-->
<!--#INCLUDE FILE="admin_inc.asp"-->
<%
'******************************************************
' System :‚ß‚é‚ß‚éEnet––±‹Ç—pƒy[ƒW
' Content:L“üe‚Ìˆê——•\¦
'******************************************************

SetHeaderTitle "L“üe", TC_KOUKOKU
Response.CacheControl = "no-cache"

Dim DB, RS, SQL, line

Set DB = OpenDB()
Set RS = Server.CreateObject("ADODB.RecordSet")
SQL = "SELECT KK_koukoku_id,KK_koukoku_name,KK_client_name,KK_company,KK_koukoku_shuu,KK_pub_start,KK_busuu,KK_tsuika_id" & vbCrLf & _
      "FROM V_KOUKOKU" & vbCrLf & _
      "WHERE KK_status=1 OR KK_status=2" & vbCrLf & _
      "ORDER BY ISNULL(KK_tsuika_id,KK_koukoku_id),KK_koukoku_id"
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

<table width="100%">
  <tr>
    <td align="left">¡“üeÏ‚İi–¢oej‚ÌLˆê—— <font size="-1">iLID‚ğƒNƒŠƒbƒN‚·‚é‚ÆC³/íœ‚·‚é‚±‚Æ‚ª‚Å‚«‚Ü‚·j</font></td>
    <td align="right">Œ”F<%=RS.RecordCount%></td>
  </tr>
</table>

<table border=1 cellpadding=1 cellspacing=1 width="100%" align="center">
  <tr bgcolor="#FFE0E0">
    <th>LID</th>
    <th>L–¼</th>
    <th>LˆË—Šå–¼</th>
    <th>‘gD–¼</th>
    <th>ŒfÚT</th>
    <th>”­s•””</th>
  </tr>

<%
line = 1
Do Until RS.EOF
  If line Mod 2 = 0 Then
    Writeln "<tr bgcolor='#FFFFFF'>"
  Else
    Writeln "<tr bgcolor='#E0FFE0'>"
  End If
  Writeln "<td align='center'><a href='nyuukou2.asp?id=" & RS("KK_koukoku_id") & "'>"  & RS("KK_koukoku_id") & "</a></td>"
  Writeln "<td>" & ReplaceTag(RS("KK_koukoku_name")) & "</td>"
  If IsNull(RS("KK_tsuika_id")) Then
    Writeln "<td>" & ReplaceTag(RS("KK_client_name")) & "</td>"
    Writeln "<td>" & BlankToBR(RS("KK_company")) & "</td>"
  Else
    Writeln "<td colspan=2>yLID:" & RS("KK_tsuika_id") & "‚Ì’Ç‰ÁˆË—Š•ªz</td>"
  End If
  Writeln "<td align='center'>" & RS("KK_koukoku_shuu") & " (" & DateFormat(RS("KK_pub_start")) & ")</td>"
  Writeln "<td align='right'>" & NumFormat(RS("KK_busuu")) & "</td>"
  Writeln "</tr>"

  RS.MoveNext
  line = line + 1
Loop
RS.close
%>

</table>

<center>
<form>
<input type="button" value="V‹K“o˜^" onClick="location.href='nyuukou2.asp'">
</form>
</center>

<!--#INCLUDE FILE="admin_footer.asp"-->

</body>
</html>
