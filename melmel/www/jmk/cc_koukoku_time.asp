<!--#INCLUDE FILE="../melmel_inc.asp"-->
<!--#INCLUDE FILE="admin_inc.asp"-->
<%
'******************************************************
' System :めるめる・net事務局用ページ
' Content:時間別クリック数表示
'******************************************************

SetHeaderTitle "時間別クリック数", TC_KOUKOKU
Response.CacheControl = "no-cache"
%>
<%
Dim DB, RS, SQL, SUB1, line, hour, cnt
Dim koukoku_id, koukoku_name

koukoku_id = Request.Form("sel")
If IsEmpty(koukoku_id) Then
  Response.Redirect("cc_koukoku.asp")
End If

Set DB = OpenDB()
Set RS = Server.CreateObject("ADODB.RecordSet")

SQL = "SELECT KK_koukoku_name FROM V_KOUKOKU WHERE KK_koukoku_id='" & koukoku_id & "'"
RS.Open SQL, DB, adOpenForwardOnly, adLockReadOnly
If Not RS.EOF Then
  koukoku_name = RS("KK_koukoku_name")
End If
RS.Close

SUB1 = "SELECT *,DATEPART(hour,CL_date) AS S1_time FROM T_CLICKLOG"
SQL = "SELECT COUNT(*) AS cnt,S1_time" & vbCrLf & _
      "FROM (" & SUB1 & ") AS SUB1" & vbCrLf & _
      "WHERE CL_koukoku_id='" & koukoku_id & "'" & vbCrLf & _
      "GROUP BY S1_time" & vbCrLf & _
      "ORDER BY S1_time"
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
//-->
</SCRIPT>
</head>
<body>

<!--#INCLUDE FILE="admin_header.asp"-->

<form method="post" name="form1" style="margin:0">
<table align="center" width="50%">
  <tr>
    <td align="left">■<%=koukoku_id%>&nbsp;<%=koukoku_name%></td>
    <td align="right"><input type="button" value=" 戻る " onClick="history.back()"></td>
  </tr>
</table>

<table border=1 cellpadding=1 cellspacing=1 width="50%" align="center">
  <tr bgcolor="#FFE0E0">
    <th>時間</th>
    <th>クリック数</th>
  </tr>
<%
line = 1
For hour = 0 To 23
  If RS.EOF Then
    cnt = 0
  Else
    If hour = RS("S1_time") Then
      cnt = RS("cnt")
      RS.MoveNext
    Else
      cnt = 0
    End If
  End If

  If line Mod 2 = 0 Then
    Writeln "<tr bgcolor='#FFFFFF'>"
  Else
    Writeln "<tr bgcolor='#E0FFE0'>"
  End If
  Writeln "<td align='center'>" & hour & "</td>"
  Writeln "<td align='right'>" & cnt & "</td>"
  Writeln "</tr>"

  line = line + 1
Next
RS.close
%>

</table>
</form>

<!--#INCLUDE FILE="admin_footer.asp"-->

</body>
</html>
