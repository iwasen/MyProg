<!--#INCLUDE FILE="../melmel_inc.asp"-->
<!--#INCLUDE FILE="admin_inc.asp"-->
<%
'******************************************************
' System :めるめる・net事務局用ページ
' Content:掲載実績報告書印刷処理
'******************************************************

SetHeaderTitle "掲載実績報告書", TC_KOUKOKU
Response.ContentType = "application/msword"
%>
<html>
<head>
<meta HTTP-EQUIV="Content-Type" Content="text/html; charset=x-sjis">
<meta HTTP-EQUIV="Pragma" Content="no-cache">
<meta name="GENERATOR" Content="Microsoft Visual Studio 6.0">
<title><%=title_text%></title>
<style type="text/css">
<!--
BODY
{
    BACKGROUND-COLOR: #ffffff;
    COLOR: #000000;
    font-family: 'ＭＳ Ｐ明朝';
    font-size: 10pt
}
TABLE
{
    BACKGROUND-COLOR: #ffffff;
    COLOR: #000000;
    font-family: 'ＭＳ Ｐ明朝';
    font-size: 10pt;
    padding: 1px
}
XMP
{
    font-family: 'ＭＳ ゴシック';
    font-size: 9pt;
}
TABLE.list
{
    font-family: 'ＭＳ Ｐ明朝';
    font-size: 9pt;
    border-width: 1px
}
-->
</style>
</head>
<body onLoad="PagePrint();">
<%
Dim DB, RS, SQL, SQL1
Dim date_num, date_tbl(), shisuu_tbl(), busuu_tbl(), koukoku_id, pub_start, pub_end, dt, i, j, shisuu_total, busuu_total, txt

koukoku_id = Request.QueryString("id")

Set DB = OpenDB()
Set RS = Server.CreateObject("ADODB.RecordSet")
SQL = "SELECT KK_koukoku_name,KK_client_name,KK_busuu,KK_koukoku_msg,KK_pub_start,KK_pub_end" & vbCrLf & _
      "FROM V_KOUKOKU" & vbCrLf & _
      "WHERE KK_koukoku_id='" & koukoku_id & "'"
RS.Open SQL, DB, adOpenForwardOnly, adLockReadOnly
If Not RS.EOF Then
%>
<table border=0 cellspacing=0>
  <tr>
    <td>
      <table border=0 cellspacing=0 width="100%">
        <tr>
          <td align="left" width="20%"></td>
          <td align="center" width="60%"><font size=5><b>掲載実績報告書</b></font></td>
          <td align="right" width="20%"><%=DateFormat(date)%></td>
        </tr>
      </table>
    </td>
  </tr>
  <tr>
    <td><hr></td>
  </tr>
  <tr>
    <td>
      <table border=0 cellspacing=0>
        <tr>
          <td align="left">■広告依頼主名：</td>
          <td align="left"><%=RS("KK_client_name")%> 様</td>
        </tr>
        <tr>
          <td align="left">■広告名：</td>
          <td align="left"><%=RS("KK_koukoku_name")%></td>
        </tr>
        <tr>
          <td align="left">■保証発行部数：</td>
          <td align="left"><%=NumFormat(RS("KK_busuu"))%> 部</td>
        </tr>
      </table>
    </td>
  </tr>
  <tr>
  </tr>
  <tr>
    <td>
      <table border=0 cellspacing=0>
        <tr>
          <td align="left" colspan=1 valign="top">■広告本文：</td>
          <td align="left" colspan=8><table border=1 cellspacing=0><tr><td colspan=8 align="left" valign="top"><xmp><%=RS("KK_koukoku_msg")%></xmp></td></tr></table></td>
        </tr>
      </table>
    </td>
  </tr>
  <tr>
  </tr>
  <tr>
    <td>
      ■日別掲載数
      <table border=1 cellspacing=0 width='100%' class='list'>
<%
  RS.Close
  SQL = "SELECT MIN(KK_pub_start) AS pub_start,MAX(KK_pub_end) AS pub_end" & vbCrLf & _
        "FROM V_KOUKOKU" & vbCrLf & _
        "WHERE KK_koukoku_id='" & koukoku_id & "' OR KK_tsuika_id='" & koukoku_id & "'"
  RS.Open SQL, DB, adOpenForwardOnly, adLockPessimistic
  pub_start = RS("pub_start")
  pub_end = RS("pub_end")
  date_num = DateDiff("d", pub_start, pub_end) + 1
  RS.Close

  SQL1 = "SELECT KK_koukoku_id FROM V_KOUKOKU WHERE KK_koukoku_id='" & koukoku_id & "' OR KK_tsuika_id='" & koukoku_id & "'"

  SQL = "SELECT pub_date,COUNT(*) AS shisuu,SUM(busuu) AS busuu" & vbCrLf & _
        "FROM V_KEISAI_ALL" & vbCrLf & _
        "WHERE koukoku_id IN (" & SQL1 & ")" & vbCrLf & _
        "GROUP BY pub_date" & vbCrLf & _
        "ORDER BY pub_date"
  RS.Open SQL, DB, adOpenForwardOnly, adLockReadOnly

  ReDim date_tbl(date_num), shisuu_tbl(date_num), busuu_tbl(date_num)
  shisuu_total = 0
  busuu_total = 0
  dt = pub_start
  for i = 1 To date_num
    date_tbl(i) = DateFormat(dt)
    If Not RS.EOF Then
      If RS("pub_date") = dt Then
        shisuu_tbl(i) = NumFormat(RS("shisuu"))
        shisuu_total = shisuu_total + RS("shisuu")
        busuu_tbl(i) = NumFormat(RS("busuu"))
        busuu_total = busuu_total + RS("busuu")
        RS.MoveNext
      Else
        shisuu_tbl(i) = "0"
        busuu_tbl(i) = "0"
      End If
    Else
      shisuu_tbl(i) = "0"
      busuu_tbl(i) = "0"
    End If

    dt = DateAdd("d", 1, dt)
  Next
  RS.Close

  For i = 1 To date_num Step 7
    If i <> 1 then
      Writeln "<tr><td colspan=9 height=0></td></tr>"
    End If

    '日付表示
    Writeln "<tr>"
    Writeln "<td align='center' width='10%'><br></td>"
    For j = i To i + 6
      If j <= date_num Then
        Writeln "<td align='center' width='10%'>" & date_tbl(j) & "</td>"
      Else
        Writeln "<td align='center' width='10%'><br></td>"
      End If
    Next
    If j <= date_num Then
      Writeln "<td align='right' width='10%'><br></td>"
    Else
      Writeln "<td align='center' width='10%'>合計</td>"
    End If
    Writeln "</tr>"

    '誌数表示
    Writeln "<tr>"
    Writeln "<td align='center'>掲載誌数</td>"
    For j = i To i + 6
      If j <= date_num Then
        Writeln "<td align='right' width='10%'>" & shisuu_tbl(j) & "</td>"
      Else
        Writeln "<td align='right' width='10%'><br></td>"
      End If
    Next
    If j <= date_num Then
      Writeln "<td align='right' width='10%'><br></td>"
    Else
      Writeln "<td align='right' width='10%'>" & NumFormat(shisuu_total) & "</td>"
    End If
    Writeln "</tr>"

    '部数表示
    Writeln "<tr>"
    Writeln "<td align='center'>掲載部数</td>"
    For j = i To i + 6
      If j <= date_num Then
        Writeln "<td align='right' width='10%'>" & busuu_tbl(j) & "</td>"
      Else
        Writeln "<td align='right' width='10%'><br></td>"
      End If
    Next
    If j <= date_num Then
      Writeln "<td align='right' width='10%'><br></td>"
    Else
      Writeln "<td align='right' width='10%'>" & NumFormat(busuu_total) & "</td>"
    End If
    Writeln "</tr>"
  Next
%>
      </table>
    </td>
  </tr>
  <tr>
  </tr>
  <tr>
    <td>
      ■掲載メルマガリスト
      <table border=1 cellspacing=0 width="100%" class="list">
        <tr>
          <td align="center" width="15%">日付</td>
          <td align="center" width="70%">掲載メールマガジン</td>
          <td align="center" width="15%">部数</td>
        </tr>
<%
  SQL = "SELECT pub_date,MG_m_name,busuu" & _
        " FROM V_KEISAI_ALL INNER JOIN T_MELMAG ON mag_id=MG_mag_id" & _
        " WHERE koukoku_id IN (" & SQL1 & ")" & _
        " ORDER BY pub_date,mag_id"
  RS.Open SQL, DB, adOpenForwardOnly, adLockReadOnly
  Do Until RS.EOF
    Writeln "<tr>"
    Writeln "<td align='center'>" & DateFormat(RS("pub_date")) & "</td>"
    Writeln "<td align='left'>" & ReplaceTag(RS("MG_m_name")) & "</td>"
    Writeln "<td align='right'>" & NumFormat(RS("busuu")) & "</td>"
    Writeln "</tr>"
    RS.MoveNext
  Loop
%>
      </table>
    </td>
  </tr>
  <tr>
    <td><hr></td>
  </tr>
  <tr>
    <td align="right">めるめる・net事務局</td>
  </tr>
</table>
<%
  RS.Close

  SQL = "UPDATE V_KOUKOKU SET KK_status=4" & vbCrLf & _
        "WHERE (KK_koukoku_id='" & koukoku_id & "' OR KK_tsuika_id='" & koukoku_id & "') AND KK_status=3"
  DB.Execute SQL
End If
%>
</body>
</html>
