<!--#INCLUDE FILE="../melmel_inc.asp"-->
<!--#INCLUDE FILE="admin_inc.asp"-->
<!--#INCLUDE FILE="waku_kanri_inc.asp"-->
<%
'******************************************************
' System :めるめる・net事務局用ページ
' Content:個別掲載状況表示
'******************************************************

SetHeaderTitle "個別掲載状況", TC_KOUKOKU
Response.CacheControl = "no-cache"

Dim koukoku_id

koukoku_id = Request.QueryString("id")
If IsEmpty(koukoku_id) Then
  Response.Redirect("sokuhou.asp")
End If
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
function OnClick_houkoku(seq) {
  var win;
  win = window.open("report_info.asp?seq=" + seq, "report_info", "resizable=yes,width=730,height=500");
  win.focus();
}
//-->
</SCRIPT>
</head>
<body>

<!--#INCLUDE FILE="admin_header.asp"-->

<%
Dim DB, RS, SQL, SUB1, SUB2
Dim koukoku_name, koukoku_type, koukoku_url, line, color, hakkou_busuu, shuu_keisuu, kitai_keisuu, kitai_busuu

Set DB = OpenDB()
Set RS = Server.CreateObject("ADODB.RecordSet")

SQL = "SELECT KK_koukoku_name,KK_koukoku_type,KK_koukoku_url FROM V_KOUKOKU WHERE KK_koukoku_id='" & koukoku_id & "'"
RS.Open SQL, DB, adOpenForwardOnly, adLockReadOnly
If RS.EOF Then
  Response.Redirect "sokuhou.asp"
End If
koukoku_name = RS("KK_koukoku_name")
koukoku_type = RS("KK_koukoku_type")
koukoku_url = RS("KK_koukoku_url")
RS.Close

SUB1 = "SELECT mag_id,COUNT(mag_id) AS keisai_cnt,SUM(busuu) AS keisai_busuu FROM V_KEISAI_ALL WHERE koukoku_id='" & koukoku_id & "' GROUP BY mag_id"

SUB2 = "SELECT CL_mag_id,COUNT(*) AS click_cnt FROM T_CLICKLOG WHERE CL_koukoku_id='" & koukoku_id & "' GROUP BY CL_mag_id"

SQL = "SELECT KS_mag_id,MG_m_name,MG_new_busuu,MG_shuu_keisuu2,keisai_cnt,keisai_busuu,RP_seq_no," & vbCrLf & _
      " IK_irai_Atype,IK_keisai_Atype,IK_irai_Btype,IK_keisai_Btype,click_cnt" & vbCrLf & _
      "FROM (((((T_KEISAI LEFT OUTER JOIN (" & SUB1 & ") AS SUB1 ON KS_mag_id=mag_id)" & vbCrLf & _
      " LEFT OUTER JOIN T_REPORTLOG ON KS_koukoku_id=RP_koukoku_id AND KS_mag_id=RP_mag_id)" & vbCrLf & _
      " LEFT OUTER JOIN T_IRAIKEISAI ON KS_mag_id=IK_mag_id)" & vbCrLf & _
      " LEFT OUTER JOIN (" & SUB2 & ") AS SUB2 ON KS_mag_id=CL_mag_id)" & vbCrLf & _
      " INNER JOIN T_MELMAG ON KS_mag_id=MG_mag_id)" & vbCrLf & _
      " INNER JOIN V_KOUKOKU ON KS_koukoku_id=KK_koukoku_id" & vbCrLf & _
      "WHERE KS_koukoku_id='" & koukoku_id & "'" & vbCrLf & _
      "ORDER BY KS_mag_id"
RS.Open SQL, DB, adOpenKeyset, adLockReadOnly
%>
<center>
  <table width="100%">
    <tr>
      <td align="left">■<%=koukoku_id%>&nbsp;<%=koukoku_name%> <font size="-1">（メルマガIDをクリックするとメルマガ情報、到着をクリックすると報告内容が表示されます）</font></td>
      <td align="right">件数：<%=RS.RecordCount%></td>
    </tr>
  </table>

<table border=1 cellpadding=1 cellspacing=1 width="100%" align="center">
  <tr bgcolor="#FFE0E0">
    <th>メルマガID</th>
    <th>メルマガ名</th>
    <th>掲載回数</th>
    <th>掲載部数</th>
    <th>期待部数</th>
    <th>ｸﾘｯｸ数</th>
    <th>ｸﾘｯｸ率</th>
    <th>報告書</th>
  </tr>

<%
line = 1
Do Until RS.EOF
  If line Mod 2 = 0 Then
    Writeln "<tr bgcolor='#FFFFFF'>"
  Else
    Writeln "<tr bgcolor='#E0FFE0'>"
  End If

  Select Case koukoku_type
  Case "A", "Q"
    hakkou_busuu = RS("MG_new_busuu")
    shuu_keisuu = 1
    kitai_keisuu = (NVL(RS("IK_keisai_Atype"), 0) + shuu_keisuu * 0.8) / (NVL(RS("IK_irai_Atype"), 0) + 1)
  Case "B", "R"
    hakkou_busuu = RS("MG_new_busuu") * RS("MG_shuu_keisuu2")
    shuu_keisuu = RS("MG_shuu_keisuu2")
    kitai_keisuu = (NVL(RS("IK_keisai_Btype"), 0) + shuu_keisuu * 0.8) / (NVL(RS("IK_irai_Btype"), 0) + 1)
  End Select
  kitai_busuu = Int(RS("MG_new_busuu") * kitai_keisuu)

  Writeln "<td align='center'><a href='JavaScript:OnClick_mag_id(""" & RS("KS_mag_id") & """)'>" & RS("KS_mag_id") & "</a></td>"
  Writeln "<td>" & ReplaceTag(RS("MG_m_name")) & "</td>"
  Writeln "<td align='right'>" & NumFormat(RS("keisai_cnt")) & "</td>"
  Writeln "<td align='right'>" & NumFormat(RS("keisai_busuu")) & "</td>"
  Writeln "<td align='right'>" & NumFormat(kitai_busuu) & "</td>"

  If NVL(koukoku_url, "") = "" Then
    Writeln "<td align='right'>-</td>"
    Writeln "<td align='right'>-</td>"
  Else
    Writeln "<td align='right'>" & NumFormat(RS("click_cnt")) & "</td>"
    If RS("keisai_busuu") > 0 Then
      Writeln "<td align='right'>" & FormatPercent(CDbl(NVL(RS("click_cnt"), 0)) / CDbl(RS("keisai_busuu")), 3) & "</td>"
    Else
      Writeln "<td align='right'>-</td>"
    End If
  End If

  If Not IsNull(RS("RP_seq_no")) Then
    Writeln "<td align='center'><a href='JavaScript:OnClick_houkoku(""" & RS("RP_seq_no") & """)'><nobr>到着</nobr></a></td>"
  Else
    Writeln "<td align='center'><nobr>未到着</nobr></td>"
  End If
  Writeln "</tr>"

  RS.MoveNext
  line = line + 1
Loop
RS.close
%>
</table>

<form>
<input type="button" value=" 戻る " onClick="history.back()">
</form>

</center>

<!--#INCLUDE FILE="admin_footer.asp"-->

</body>
</html>
