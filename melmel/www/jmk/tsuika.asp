<!--#INCLUDE FILE="../melmel_inc.asp"-->
<!--#INCLUDE FILE="admin_inc.asp"-->
<%
'******************************************************
' System :めるめる・net事務局用ページ
' Content:追加依頼一覧表示
'******************************************************

SetHeaderTitle "追加依頼", TC_KOUKOKU
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
    <td align="left">■出稿済みの広告一覧 <font size="-1">（広告IDをクリックすると追加依頼を行います）</font></td>
    <td align="right">件数：<%=RS.RecordCount%></td>
  </tr>
</table>

<table border=1 cellpadding=1 cellspacing=1 width="100%" align="center">
  <tr bgcolor="#FFE0E0">
    <th>広告ID</th>
    <th>広告名</th>
    <th>広告依頼主名</th>
    <th>掲載週</th>
    <th>掲載部数</th>
    <th>保証部数</th>
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
    Writeln "<td>【広告ID:" & RS("KK_tsuika_id") & "の追加依頼分】</td>"
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
