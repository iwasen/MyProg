<!--#INCLUDE FILE="../melmel_inc.asp"-->
<!--#INCLUDE FILE="admin_inc.asp"-->
<%
'******************************************************
' System :めるめる・net事務局用ページ
' Content:自己申告部数チェック一覧表示
'******************************************************

SetHeaderTitle "自己申告部数チェックリスト", TC_HAKKOUSHA
Response.CacheControl = "no-cache"
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
function OnSubmit_form1() {
  if (confirm("処理を実行します。よろしいですか？"))
    return true;
  else
    return false;
}
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

<%
Dim b, objBASP, fsize, fname, CSV
Dim DB, RS, SQL
Dim bdata, sdata, sarray, line
Dim c1, c2, c3
Dim mag_id

'DBオープン
Set DB = OpenDB()

'CSVファイルをテンポラリにコピー
b = Request.BinaryRead(Request.TotalBytes)
Set objBASP = Server.CreateObject("basp21")
fsize = objBASP.FormFileSize(b, "csv")
If fsize > 0 Then
  bdata = objBASP.FormBinary(b, "csv")
  sdata = objBASP.Kconv(bdata, 4, 1)
  sarray = Split(sdata, vbCrLf)
  SQL = "DELETE T_MAG2LOG"
  DB.Execute SQL

  For Each line In sarray
    c1 = Instr(1, line, ",")
    If c1 > 0 Then
      c2 = Instr(c1 + 1, line, ",")
      If c2 > 0 Then
        c3 = Instr(c2 + 1, line, ",")
        If c3 > 0 Then
          SQL = "INSERT INTO T_MAG2LOG (ML_date, ML_mag2_id, ML_busuu, ML_m_name) VALUES ('" & _
              Left(line, c1 - 1) & "','" &_
              Mid(line, c1 + 1, c2 - c1 - 1) & "'," & _
              Mid(line, c2 + 1, c3 - c2 - 1) & ",'" & _
              Replace(Mid(line, c3 + 1), "'", "''") & "')"
          DB.Execute SQL
        End If
      End If
    End If
  Next

  SQL = "DELETE T_MAG2BUSUU"
  DB.Execute SQL

  SQL = "INSERT INTO T_MAG2BUSUU" & vbCrLf & _
        "SELECT MG_mag_id, MAX(MAX_busuu)" & vbCrLf & _
        "FROM V_MAG2BUSUU INNER JOIN T_MELMAG ON ML_mag2_id=MG_mgmg_id" & vbCrLf & _
        "GROUP BY MG_mag_id"
  DB.Execute SQL
End If

Set RS = Server.CreateObject("ADODB.RecordSet")
SQL = "SELECT MG_mag_id,MG_m_name,MG_new_busuu,MB_busuu,(CAST(MG_new_busuu AS REAL) / MB_busuu) AS val1,(CAST(MG_new_busuu AS REAL) / MB_busuu * MG_magmag_keisuu) AS val2" & vbCrLf & _
      "FROM (T_MAG2BUSUU INNER JOIN T_MELMAG ON MB_mag_id=MG_mag_id) INNER JOIN T_MELMEL ON MG_mel_id=MM_mel_id" & vbCrLf & _
      "WHERE CAST(MG_new_busuu AS REAL) / MB_busuu * MG_magmag_keisuu > 1.1" & vbCrLf & _
      "ORDER BY val2 DESC"
RS.Open SQL, DB, adOpenKeyset, adLockReadOnly
%>

<table width="100%" border=0 cellpadding=0 cellspacing=0><tr><td>
<form method="post" name="form1" action="busuu_check3.asp" onSubmit="return OnSubmit_form1();">

<table width="100%">
  <tr>
    <td align="left">■自己申告部数が許容値を超えているメルマガの一覧 <font size="-1">（メルマガIDをクリックすると詳細情報が表示されます）</font></td>
    <td align="right">件数：<%=RS.RecordCount%></td>
  </tr>
</table>

<table border=1 cellpadding=1 cellspacing=1 width="100%" align="center">
  <tr bgcolor='#FFE0E0'>
    <th>メルマガID</th>
    <th>メルマガ名</th>
    <th>申告部数</th>
    <th>まぐまぐ部数</th>
    <th>申告/まぐ</th>
    <th>×まぐ係数</th>
    <th>処置</th>
  </tr>

<%
line = 1
Do Until RS.EOF
  If line Mod 2 = 0 Then
    Writeln "<tr bgcolor='#FFFFFF'>"
  Else
    Writeln "<tr bgcolor='#E0FFE0'>"
  End If

  mag_id = RS("MG_mag_id")
  Writeln "<td align='center'><a href='JavaScript:OnClick_mag_id(""" & mag_id & """)'>" & mag_id & "</a>"
  Writeln "<input type='hidden' name='mag_id" & line & "' value='" & mag_id & "'></td>"
  Writeln "<td>" & ReplaceTag(RS("MG_m_name")) & "</td>"
  Writeln "<td align='right'>" & NumFormat(RS("MG_new_busuu")) & "</td>"
  Writeln "<td align='right'>" & NumFormat(RS("MB_busuu")) & "</td>"
  Writeln "<td align='right'>" & FormatNumber(RS("val1") * 100, 1) & "%</td>"
  Writeln "<td align='right'>" & FormatNumber(RS("val2") * 100, 1) & "%</td>"
  Writeln "<td align='center'><nobr><input type='radio' name='shochi" & line & "' value='ok'>ＯＫ"
  Writeln "<input type='radio' name='shochi" & line & "' value='ng'>解約</nobr></td>"
  Writeln "</tr>"

  RS.MoveNext
  line = line + 1
Loop
RS.close
%>

</table>

<input type="hidden" name="rc" value=<%=line - 1%>>

<center>
<br>
<input type="submit" value="　ＯＫ　">
<input type="reset" value="リセット">
</center>

</form>
</td></tr></table>

<!--#INCLUDE FILE="admin_footer.asp"-->

</body>
</html>
