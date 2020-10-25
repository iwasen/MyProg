<!--#INCLUDE FILE="../melmel_inc.asp"-->
<!--#INCLUDE FILE="admin_inc.asp"-->
<%
'******************************************************
' System :めるめる・net事務局用ページ
' Content:本登録初期チェック一覧表示
'******************************************************

SetHeaderTitle "本登録初期チェック", TC_HAKKOUSHA
Response.CacheControl = "no-cache"
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

<%
Dim DB, RS, SQL, line

Set DB = OpenDB()
Set RS = Server.CreateObject("ADODB.RecordSet")
SQL = "SELECT MG_mag_id,MG_m_name,MM_h_name,MM_mail_adr,MG_regist_date" & vbCrLf & _
      "FROM V_MELMAG" & vbCrLf & _
      "WHERE COALESCE(MG_kakunin_flag,0)=0 Or MG_shuu_keisuu IS NULL" & vbCrLf & _
      "ORDER BY MG_mag_id"
RS.Open SQL, DB, adOpenKeyset, adLockReadOnly
%>

<table width="100%">
  <tr>
    <td align="left">■本登録後、初期チェックされていないメルマガの一覧 <font size="-1">（メルマガIDをクリックするとメルマガ情報の修正画面が表示されます）</font></td>
    <td align="right">件数：<%=RS.RecordCount%></td>
  </tr>
</table>

<table border=1 cellpadding=1 cellspacing=1 width="100%" align="center">
  <tr bgcolor="#FFE0E0">
    <th>メルマガID</th>
    <th>メルマガ名</th>
    <th>発行者名</th>
    <th>e-mailアドレス</th>
    <th>登録日</th>
  </tr>

<%
line = 1
Do Until RS.EOF
  If line Mod 2 = 0 Then
    Writeln "<tr bgcolor='#FFFFFF'>"
  Else
    Writeln "<tr bgcolor='#E0FFE0'>"
  End If
  Writeln "<td align='center'><a href='init_check2.asp?id=" & RS("MG_mag_id") & "'>"  & RS("MG_mag_id") & "</a></td>"
  Writeln "<td>" & ReplaceTag(RS("MG_m_name")) & "</td>"
  Writeln "<td>" & ReplaceTag(RS("MM_h_name")) & "</td>"
  Writeln "<td>" & ReplaceTag(RS("MM_mail_adr")) & "</td>"
  Writeln "<td align='center'>" & DateFormat(RS("MG_regist_date")) & "</td>"
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
