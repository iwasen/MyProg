<!--#INCLUDE FILE="../melmel_inc.asp"-->
<!--#INCLUDE FILE="admin_inc.asp"-->
<%
'******************************************************
' System :傔傞傔傞丒net帠柋嬊梡儁乕僕
' Content:弌峞峀崘堦棗昞帵
'******************************************************

SetHeaderTitle "弌峞張棟", TC_KOUKOKU
Response.CacheControl = "no-cache"

Dim DB, RS, SQL, line, koukoku_id

Set DB = OpenDB()
Set RS = Server.CreateObject("ADODB.RecordSet")
SQL = "SELECT KK_koukoku_id,KK_koukoku_name,KK_client_name,KK_company,KK_koukoku_shuu,KK_pub_start,KK_busuu,KK_status,KK_tsuika_id" & vbCrLf & _
      "FROM V_KOUKOKU" & vbCrLf & _
      "WHERE KK_status=1 OR KK_status=2 OR KK_status=3" & vbCrLf & _
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
<SCRIPT LANGUAGE=javascript>
<!--
function OnClick_koukoku_id(id) {
  var win;
  win = window.open("koukoku_info.asp?id=" + id, "koukoku_info", "resizable=yes,scrollbars=yes,width=840");
  win.focus();
}
function OnSubmit_form1() {
  return confirm("峀崘僥僗僩儊乕儖傪憲怣偟傑偡偐丠");
}
//-->
</SCRIPT>
</head>
<body>

<!--#INCLUDE FILE="admin_header.asp"-->

<table width="100%" border=0 cellpadding=0 cellspacing=0><tr><td>
<form method="post" name="form1" action="shukkou2.asp" onSubmit="return OnSubmit_form1();">

<table width="100%">
  <tr>
    <td align="left">仭擖峞嵪傒偺峀崘堦棗 <font size="-1">乮峀崘ID傪僋儕僢僋偡傞偲峀崘偺徻嵶忣曬傪昞帵偟傑偡乯</font></td>
    <td align="right">審悢丗<%=RS.RecordCount%></td>
  </tr>
</table>

<table border=1 cellpadding=1 cellspacing=1 width="100%" align="center">
  <tr bgcolor="#FFE0E0">
    <th>峀崘ID</th>
    <th>峀崘柤</th>
    <th>峀崘埶棅庡柤</th>
    <th>慻怐柤</th>
    <th>宖嵹廡</th>
    <th>敪峴晹悢</th>
    <th>忬懺</th>
    <th>媒囊百</th>
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
  Writeln "<td align='center'><a href='JavaScript:OnClick_koukoku_id(""" & koukoku_id & """)'>" & koukoku_id & "</a></td>"
  Writeln "<td>" & ReplaceTag(RS("KK_koukoku_name")) & "</td>"
  If IsNull(RS("KK_tsuika_id")) Then
    Writeln "<td>" & ReplaceTag(RS("KK_client_name")) & "</td>"
    Writeln "<td>" & BlankToBR(ReplaceTag(RS("KK_company"))) & "</td>"
  Else
    Writeln "<td colspan=2>亂峀崘ID:" & RS("KK_tsuika_id") & "偺捛壛埶棅暘亃</td>"
  End If
  Writeln "<td align='center'>" & RS("KK_koukoku_shuu") & " (" & DateFormat(RS("KK_pub_start")) & ")</td>"
  Writeln "<td align='right'>" & NumFormat(RS("KK_busuu")) & "</td>"
  Select Case RS("KK_status")
  Case 1
    Writeln "<td align='center'><font color='red'>媒囊百枹憲怣</font></td>"
    Writeln "<td align='center'><input type='checkbox' name='testmail' value='" & koukoku_id & "' checked></td>"
  Case 2
    Writeln "<td align='center'><font color='blue'>媒囊百憲怣嵪</font></td>"
    Writeln "<td align='center'><input type='checkbox' name='testmail' value='" & koukoku_id & "'></td>"
  Case 3
    Writeln "<td align='center'>埶棅嵪傒</td>"
    Writeln "<td align='center'><br></td>"
  End Select
  Writeln "</tr>"

  RS.MoveNext
  line = line + 1
Loop
RS.close
%>

</table>

<center>
<br>
<input type="submit" value="媒囊百憲怣">
</center>

</form>
</td></tr></table>

<!--#INCLUDE FILE="admin_footer.asp"-->

</body>
</html>
