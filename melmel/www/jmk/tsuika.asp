<!--#INCLUDE FILE="../melmel_inc.asp"-->
<!--#INCLUDE FILE="admin_inc.asp"-->
<%
'******************************************************
' System :‚ß‚é‚ß‚éEnet––±‹Ç—pƒy[ƒW
' Content:’Ç‰ÁˆË—Šˆê——•\¦
'******************************************************

SetHeaderTitle "’Ç‰ÁˆË—Š", TC_KOUKOKU
Response.CacheControl = "no-cache"
%>
<%
Dim DB, RS, SQL, SUB1, line

Set DB = OpenDB()
Set RS = Server.CreateObject("ADODB.RecordSet")

SUB1 = "SELECT KK_koukoku_id AS S1_koukoku_id FROM V_KOUKOKU WHERE KK_status=3 OR KK_status=4"

SQL = "SELECT KK_koukoku_id,KK_koukoku_name,KK_client_name,KK_koukoku_shuu,KK_pub_start,keisai_busuu,KK_busuu,KK_tsuika_id" & vbCrLf & _
      "FROM ((" & SUB1 & ") AS SUB1" & vbCrLf & _
      " INNER JOIN V_KOUKOKU ON S1_koukoku_id=ISNULL(KK_tsuika_id,KK_koukoku_id))" & vbCrLf & _
      " LEFT OUTER JOIN V_KEISAI_BUSUU ON KK_koukoku_id=koukoku_id" & vbCrLf & _
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
    <td align="left">¡oeÏ‚İ‚ÌLˆê—— <font size="-1">iLID‚ğƒNƒŠƒbƒN‚·‚é‚Æ’Ç‰ÁˆË—Š‚ğs‚¢‚Ü‚·j</font></td>
    <td align="right">Œ”F<%=RS.RecordCount%></td>
  </tr>
</table>

<table border=1 cellpadding=1 cellspacing=1 width="100%" align="center">
  <tr bgcolor="#FFE0E0">
    <th>LID</th>
    <th>L–¼</th>
    <th>LˆË—Šå–¼</th>
    <th>ŒfÚT</th>
    <th>ŒfÚ•””</th>
    <th>•ÛØ•””</th>
  </tr>

<%
line = 1
Do Until RS.EOF
  If line Mod 2 = 0 Then
    Writeln "<tr bgcolor='#FFFFFF'>"
  Else
    Writeln "<tr bgcolor='#E0FFE0'>"
  End If
  If IsNull(RS("KK_tsuika_id")) Then
    Writeln "<td align='center'><a href='tsuika2.asp?id=" & RS("KK_koukoku_id") & "'>" & RS("KK_koukoku_id") & "</a></td>"
    Writeln "<td>" & ReplaceTag(RS("KK_koukoku_name")) & "</td>"
    Writeln "<td>" & ReplaceTag(RS("KK_client_name")) & "</td>"
  Else
    Writeln "<td align='center'>" & RS("KK_koukoku_id") & "</td>"
    Writeln "<td>" & RS("KK_koukoku_name") & "</td>"
    Writeln "<td>yLID:" & RS("KK_tsuika_id") & "‚Ì’Ç‰ÁˆË—Š•ªz</td>"
  End If
  Writeln "<td align='center'>" & RS("KK_koukoku_shuu") & " (" & DateFormat(RS("KK_pub_start")) & ")</td>"
  Writeln "<td align='right'>" & NumFormat(RS("keisai_busuu")) & "</td>"
  Writeln "<td align='right'>" & NumFormat(RS("KK_busuu")) & "</td>"
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
