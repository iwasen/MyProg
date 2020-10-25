<!--#INCLUDE FILE="../melmel_inc.asp"-->
<!--#INCLUDE FILE="admin_inc.asp"-->
<!--#INCLUDE FILE="waku_kanri_inc.asp"-->
<%
'******************************************************
' System :めるめる・net事務局用ページ
' Content:掲載状況速報表示
'******************************************************

SetHeaderTitle "掲載状況速報", TC_KOUKOKU
'Response.CacheControl = "no-cache"
%>
<html>
<head>
<meta HTTP-EQUIV="Content-Type" Content="text/html; charset=x-sjis">
<meta HTTP-EQUIV="Pragma" Content="no-cache">
<meta HTTP-EQUIV="Refresh" Content="180">
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

<center>
  <table width="100%">
    <tr>
      <td align="left">■掲載状況速報 <font size="-1">（広告IDをクリックすると広告の詳細情報、依頼数をクリックすると個別掲載状況を表示します）</font></td>
    </tr>
  </table>

<table border=1 cellpadding=1 cellspacing=1 width="100%" align="center">
  <tr bgcolor="#FFE0E0">
    <th>広告ID</th>
    <th>広告名</th>
    <th>掲載部数</th>
    <th>保証部数</th>
    <th>ｸﾘｯｸ数</th>
    <th>依頼数</th>
    <th>報告書</th>
    <th>状態</th>
  </tr>

<%
Dim DB, RS, SQL, SUB1, SUB2, SUB3, line, keisai_busuu, hoshou_busuu, color, status

Set DB = OpenDB()
Set RS = Server.CreateObject("ADODB.RecordSet")

SUB1 = "SELECT KS_koukoku_id,COUNT(*) AS cnt1 FROM T_KEISAI GROUP BY KS_koukoku_id"

SUB2 = "SELECT RP_koukoku_id,COUNT(*) AS cnt2 FROM T_REPORTLOG GROUP BY RP_koukoku_id"

SUB3 = "SELECT CL_koukoku_id,COUNT(*) AS click FROM T_CLICKLOG GROUP BY CL_koukoku_id"

SQL = "SELECT KK_koukoku_id,KK_koukoku_name,KK_koukoku_url,keisai_busuu,KK_busuu,cnt1,cnt2,click,KK_status" & vbCrLf & _
      "FROM (((V_KOUKOKU LEFT OUTER JOIN V_KEISAI_BUSUU ON KK_koukoku_id=koukoku_id)" & vbCrLf & _
      " LEFT OUTER JOIN (" & SUB1 & ") AS SUB1 ON KK_koukoku_id=KS_koukoku_id)" & vbCrLf & _
      " LEFT OUTER JOIN (" & SUB2 & ") AS SUB2 ON KK_koukoku_id=RP_koukoku_id)" & vbCrLf & _
      " LEFT OUTER JOIN (" & SUB3 & ") AS SUB3 ON KK_koukoku_id=CL_koukoku_id" & vbCrLf & _
      "WHERE KK_status BETWEEN 1 AND 4" & vbCrLf & _
      "ORDER BY KK_koukoku_id DESC"
RS.Open SQL, DB, adOpenForwardOnly, adLockReadOnly

line = 1
Do Until RS.EOF
  If line Mod 2 = 0 Then
    Writeln "<tr bgcolor='#FFFFFF'>"
  Else
    Writeln "<tr bgcolor='#E0FFE0'>"
  End If

  keisai_busuu = NVL(RS("keisai_busuu"), 0)
  hoshou_busuu = RS("KK_busuu")
  If keisai_busuu < hoshou_busuu Then
    color = "red"
  Else
    color = "blue"
  End If
  Writeln "<td align='center'><a href='JavaScript:OnClick_koukoku_id(""" & RS("KK_koukoku_id") & """)'>" & RS("KK_koukoku_id") & "</a></td>"
  Writeln "<td>" & ReplaceTag(RS("KK_koukoku_name")) & "</td>"
  Writeln "<td align='right'><font color='" & color & "'>" & NumFormat(keisai_busuu) & "</font></td>"
  Writeln "<td align='right'>" & NumFormat(hoshou_busuu) & "</td>"
  If NVL(RS("KK_koukoku_url"), "") = "" Then
    Writeln "<td align='right'>-</td>"
  Else
    Writeln "<td align='right'>" & NumFormat(RS("click")) & "</td>"
  End If
  Writeln "<td align='right'><a href='sokuhou2.asp?id=" & RS("KK_koukoku_id") & "'>" & NumFormat(NVL(RS("cnt1"), 0)) & "</a></td>"
  Writeln "<td align='right'>" & NumFormat(NVL(RS("cnt2"), 0)) & "</td>"
  Select Case RS("KK_status")
  Case 1
    status = "<font color='blue'><nobr>入稿済み</nobr></font>"
  Case 2
    status = "<font color='purple'><nobr>ﾃｽﾄﾒｰﾙ送信済み</nobr></font>"
  Case 3
    status = "<font color='red'><nobr>依頼済み</nobr></font>"
  Case 4
    status = "<font color='maroon'><nobr>報告書出力済み</nobr></font>"
  Case Else
    status = "不明"
  End Select
  Writeln "<td align='center'><font size='-1'>" & status & "</font></td>"
  Writeln "</tr>"

  RS.MoveNext
  line = line + 1
Loop
RS.close
%>
</table>

</center>

<!--#INCLUDE FILE="admin_footer.asp"-->

</body>
</html>
