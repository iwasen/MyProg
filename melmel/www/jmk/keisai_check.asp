<!--#INCLUDE FILE="../melmel_inc.asp"-->
<!--#INCLUDE FILE="admin_inc.asp"-->
<%
'******************************************************
' System :めるめる・net事務局用ページ
' Content:掲載状況チェック一覧
'******************************************************

SetHeaderTitle "掲載状況チェック", TC_KOUKOKU
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
function OnClick_mag_id(koukoku_id,mag_id,seq_no,line) {
  var win;
  win = window.open("keisai_check2.asp" +
      "?koukoku_id=" + koukoku_id +
      "&mag_id=" + mag_id +
      "&seq_no=" + seq_no +
      "&line=" + line, "keisai_check2", "resizable=yes,width=600,height=700");
  win.focus();
}
//-->
</SCRIPT>
</head>
<body>

<!--#INCLUDE FILE="admin_header.asp"-->

<%
Dim DB, RS, SQL, line

'DBオープン
Set DB = OpenDB()
Set RS = Server.CreateObject("ADODB.RecordSet")
SQL = "SELECT MG_mag_id,MG_m_name,KK_koukoku_id,KK_koukoku_name,HM_date,HM_seq_no,HD_index_no" & vbCrLf & _
      "FROM T_HMAILLOG INNER JOIN ((T_HMAILDAT INNER JOIN T_MELMAG ON HD_mag_id=MG_mag_id)" & vbCrLf & _
      " INNER JOIN V_KOUKOKU ON HD_koukoku_id=KK_koukoku_id) ON HM_seq_no=HD_seq_no" & vbCrLf & _
      "WHERE HD_check_status=0" & vbCrLf & _
      "ORDER BY HM_seq_no,HD_index_no"
RS.Open SQL, DB, adOpenKeyset, adLockReadOnly
%>

<table width="100%" border=0 cellpadding=0 cellspacing=0><tr><td>
<form method="post" name="form1" action="keisai_check3.asp" onSubmit="return OnSubmit_form1();">

<table width="100%">
  <tr>
    <td align="left">■掲載された広告が依頼したものと異なるメルマガの一覧 <font size="-1">（広告IDをクリックすると広告内容が表示されます）</font></td>
    <td align="right">件数：<%=RS.RecordCount%></td>
  </tr>
</table>

<table border=1 cellpadding=1 cellspacing=1 width="100%" align="center">
  <tr bgcolor='#FFE0E0'>
    <th>広告ID</th>
    <th>広告名</th>
    <th>メルマガID</th>
    <th>メルマガ名</th>
    <th>発行日</th>
    <th>判定</th>
  </tr>

<%
line = 1
Do Until RS.EOF
  If line Mod 2 = 0 Then
    Writeln "<tr bgcolor='#FFFFFF'>"
  Else
    Writeln "<tr bgcolor='#E0FFE0'>"
  End If

  Writeln "<td align='center'><a href=""JavaScript:OnClick_mag_id('" & RS("KK_koukoku_id")  & "','" & RS("MG_mag_id") & "'," & RS("HM_seq_no") & "," & line & ")"">" & RS("KK_koukoku_id") & "</a></td>"
  Writeln "<td>" & ReplaceTag(RS("KK_koukoku_name")) & "</td>"
  Writeln "<td align='center'>" & RS("MG_mag_id") & "</td>"
  Writeln "<td>" & ReplaceTag(RS("MG_m_name")) & "</td>"
  Writeln "<td align='center'>" & RS("HM_date") & "</td>"
  Writeln "<td align='center'><nobr><input type='radio' name='hantei" & line & "' value='ok'>ＯＫ"
  Writeln "<input type='radio' name='hantei" & line & "' value='ng'>ＮＧ</nobr>"
  Writeln "<input type='hidden' name='seq_no" & line & "' value='" & RS("HM_seq_no") & "'>"
  Writeln "<input type='hidden' name='index_no" & line & "' value='" & RS("HD_index_no") & "'></td>"
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
