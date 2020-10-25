<!--#INCLUDE FILE="../melmel_inc.asp"-->
<!--#INCLUDE FILE="admin_inc.asp"-->
<%
'******************************************************
' System :めるめる・net事務局用ページ
' Content:メルマガ別クリック数表示
'******************************************************

SetHeaderTitle "メルマガ別クリック数", TC_HAKKOUSHA
Response.CacheControl = "no-cache"
%>
<%
Dim DB, RS, SQL, SUB1, SUB2, line, keisai_busuu, click_count, click_rate

Set DB = OpenDB()
Set RS = Server.CreateObject("ADODB.RecordSet")
SUB1 = "SELECT CL_mag_id,COUNT(*) AS S1_cnt FROM T_CLICKLOG GROUP BY CL_mag_id"
SUB2 = "SELECT mag_id,COUNT(*) AS real_cnt,SUM(busuu) AS keisai_busuu FROM V_KEISAI_ALL GROUP BY mag_id"
SQL = "SELECT MG_mag_id,MG_m_name,keisai_busuu,S1_cnt" & vbCrLf & _
      "FROM (T_MELMAG LEFT OUTER JOIN (" & SUB1 & ") AS SUB1 ON MG_mag_id=CL_mag_id)" & vbCrLf & _
      "LEFT OUTER JOIN (" & SUB2 & ") AS SUB2 ON MG_mag_id=mag_id" & vbCrLf & _
      "WHERE MG_status=0" & vbCrLf & _
      "ORDER BY MG_mag_id"
RS.Open SQL, DB, adOpenForwardOnly, adLockReadOnly
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
function OnClick_mag_id(id) {
  var win;
  win = window.open("mag_info.asp?id=" + id, "mag_info", "resizable=yes,scrollbars=yes,width=740");
  win.focus();
}
//-->
</SCRIPT>
</head>
<body>

<!--#INCLUDE FILE="admin_header.asp"-->

<form method="post" name="form1" style="margin:0">
<table align="center" width="100%">
  <tr>
    <td align="left">■クリック数一覧</td>
  </tr>
</table>

<table border=1 cellpadding=1 cellspacing=1 width="100%" align="center">
  <tr bgcolor="#FFE0E0">
    <th>メルマガID</th>
    <th>メルマガ名</th>
    <th>クリック数</th>
    <th>掲載部数</th>
    <th>クリック率</th>
  </tr>
<%
line = 1
Do Until RS.EOF
  If line Mod 2 = 0 Then
    Writeln "<tr bgcolor='#FFFFFF'>"
  Else
    Writeln "<tr bgcolor='#E0FFE0'>"
  End If

  keisai_busuu = NVL(RS("keisai_busuu"), 0)
  click_count = NVL(RS("S1_cnt"), 0)
  If keisai_busuu = 0 Then
    click_rate = "<br>"
  Else
    click_rate = FormatNumber(CDbl(click_count) / keisai_busuu, 1, -1, 0, 0) & "%"
  End If

  Writeln "<td align='center'><a href='JavaScript:OnClick_mag_id(""" & RS("MG_mag_id") & """)'>" & RS("MG_mag_id") & "</a></td>"
  Writeln "<td>" & ReplaceTag(RS("MG_m_name")) & "</td>"
  Writeln "<td align='right'>" & NumFormat(click_count) & "</td>"
  Writeln "<td align='right'>" & NumFormat(keisai_busuu) & "</td>"
  Writeln "<td align='right'>" & click_rate & "</td>"
  Writeln "</tr>"

  RS.MoveNext
  line = line + 1
Loop
RS.close
%>

</table>
<input type="hidden" name="line" value="<%=line - 1%>">
</form>

<!--#INCLUDE FILE="admin_footer.asp"-->

</body>
</html>
