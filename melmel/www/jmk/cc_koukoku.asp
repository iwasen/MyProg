<!--#INCLUDE FILE="../melmel_inc.asp"-->
<!--#INCLUDE FILE="admin_inc.asp"-->
<%
'******************************************************
' System :めるめる・net事務局用ページ
' Content:広告別クリック数表示
'******************************************************

SetHeaderTitle "広告別クリック数", TC_KOUKOKU
'Response.CacheControl = "no-cache"
%>
<%
Dim DB, RS, SQL, SUB1, line, keisai_busuu, click_count, click_rate

Set DB = OpenDB()
Set RS = Server.CreateObject("ADODB.RecordSet")
SUB1 = "SELECT CL_koukoku_id,COUNT(*) AS S1_cnt FROM T_CLICKLOG GROUP BY CL_koukoku_id"
SQL = "SELECT KK_koukoku_id,KK_koukoku_name,KK_client_name,keisai_busuu,S1_cnt" & vbCrLf & _
      "FROM (V_KOUKOKU LEFT OUTER JOIN (" & SUB1 & ") AS SUB1 ON KK_koukoku_id=CL_koukoku_id)" & vbCrLf & _
      "LEFT OUTER JOIN V_KEISAI_BUSUU ON KK_koukoku_id=koukoku_id" & vbCrLf & _
      "WHERE ISNULL(KK_koukoku_url,'')<>''" & vbCrLf & _
      "ORDER BY KK_koukoku_id DESC"
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
function OnClick_koukoku_id(id) {
  var win;
  win = window.open("koukoku_info.asp?id=" + id, "koukoku_info", "resizable=yes,scrollbars=yes,width=840");
  win.focus();
}
function OnClick_date() {
  if (sel_check()) {
    document.form1.action = "cc_koukoku_date.asp";
		document.form1.target = "";
    document.form1.submit();
  }
}
function OnClick_time() {
  if (sel_check()) {
    document.form1.action = "cc_koukoku_time.asp";
		document.form1.target = "";
    document.form1.submit();
  }
}
function OnClick_melmag() {
  if (sel_check()) {
    document.form1.action = "cc_koukoku_mag.asp";
		document.form1.target = "";
    document.form1.submit();
  }
}
function OnClick_report() {
  if (sel_check()) {
    document.form1.action = "click_report.asp";
		document.form1.target = "_blank";
    document.form1.submit();
  }
}
function sel_check() {
  var i, n;
  n = document.form1.line.value;
  if (n == 0) {
    alert("表示する広告がありません。");
    return false;
  } else if (n == 1) {
    if (document.form1.sel.checked)
      return true;
  } else {
    for (i = 0; i < n; i++) {
      if (document.form1.sel[i].checked)
        return true;
    }
  }
  alert("表示する広告を選択してください。");
  return false;
}
//-->
</SCRIPT>
</head>
<body>

<!--#INCLUDE FILE="admin_header.asp"-->

<form method="post" name="form1" style="margin:0">
<table align="center" width="100%">
  <tr>
    <td align="left">■クリック数一覧 <font size="-1">（表示したい広告を選択して右上のボタンをクリックしてください）</font></td>
    <td align="right">
      <input type="button" value="日付別" onClick="OnClick_date()">
      <input type="button" value="時間別" onClick="OnClick_time()">
      <input type="button" value="メルマガ属性別" onClick="OnClick_melmag()">
      <input type="button" value="ｸﾘｯｸﾚﾎﾟｰﾄ" onClick="OnClick_report()">
    </td>
  </tr>
</table>

<table border=1 cellpadding=1 cellspacing=1 width="100%" align="center">
  <tr bgcolor="#FFE0E0">
    <th>広告ID</th>
    <th>広告名</th>
    <th>広告依頼主名</th>
    <th>クリック数</th>
    <th>掲載部数</th>
    <th>クリック率</th>
    <th>選択</th>
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
    click_rate = "-"
  Else
    click_rate = FormatPercent(CDbl(click_count) / keisai_busuu, 3)
  End If

  Writeln "<td align='center'><a href='JavaScript:OnClick_koukoku_id(""" & RS("KK_koukoku_id") & """)'>" & RS("KK_koukoku_id") & "</a></td>"
  Writeln "<td>" & ReplaceTag(RS("KK_koukoku_name")) & "</td>"
  Writeln "<td>" & ReplaceTag(RS("KK_client_name")) & "</td>"
  Writeln "<td align='right'>" & NumFormat(click_count) & "</td>"
  Writeln "<td align='right'>" & NumFormat(keisai_busuu) & "</td>"
  Writeln "<td align='right'>" & click_rate & "</td>"
  Writeln "<td align='center'><input type='radio' name='sel' value='" & RS("KK_koukoku_id") & "'></td>"
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
