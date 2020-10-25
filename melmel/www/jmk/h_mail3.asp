<!--#INCLUDE FILE="../melmel_inc.asp"-->
<!--#INCLUDE FILE="admin_inc.asp"-->
<%
'******************************************************
' System :めるめる・net事務局用ページ
' Content:発行者宛てメールの送信先選択画面
'******************************************************

SetHeaderTitle "送信先発行者選択", TC_HAKKOUSHA
Response.CacheControl = "no-cache"
%>
<html>
<HEAD>
<META HTTP-EQUIV="Content-Type" Content="text/html; charset=x-sjis">
<meta HTTP-EQUIV="Pragma" Content="no-cache">
<META name="GENERATOR" Content="Microsoft Visual Studio 6.0">
<title><%=title_text%></title>
<LINK rel="stylesheet" type="text/css" href="admin.css">
<SCRIPT LANGUAGE=javascript>
<!--
var sels = new Array(0);
function OnClick_sel(line) {
  var i;
  if (document.form1.sel[line].checked)
    sels[sels.length] = line;
  else {
    for (i = 0; i < sels.length; i++) {
      if (sels[i] == line) {
        sels[i] = -1;
        break;
      }
    }
  }
}
function OnClick_OK() {
  var i, n, s;
  //sels.sort();
  s = ""
  for (i = 0; i < sels.length; i++) {
    n = sels[i];
    if (n != -1) {
      s += document.form1.sel[n].value + "\n";
    }
  }
  opener.document.form1.mailto.value += s;
  window.close();
}
function OnClick_all() {
  var i;
  sels = new Array(document.form1.sel.length);
  for (i = 0; i < document.form1.sel.length; i++) {
    document.form1.sel[i].checked = true;
    sels[i] = i;
  }
}
function OnClick_katsudou() {
  var i;
  sels = new Array(0);
  for (i = 0; i < document.form1.sel.length; i++) {
    if (document.form1.status[i].value == 0) {
      document.form1.sel[i].checked = true;
      sels[sels.length] = i;
    } else
      document.form1.sel[i].checked = false;
  }
}
function OnClick_kyuukai() {
  var i;
  sels = new Array(0);
  for (i = 0; i < document.form1.sel.length; i++) {
    if (document.form1.status[i].value == 8) {
      document.form1.sel[i].checked = true;
      sels[sels.length] = i;
    } else
      document.form1.sel[i].checked = false;
  }
}
//-->
</SCRIPT>
</HEAD>
<body>

<%
Dim DB, RS, SQL, line, sort

Sub TableHeader(text, sno)
  If sno = sort Then
    Writeln "<th><font color='brown'>" & text & "</font></th>"
  Else
    Writeln "<th class='sort' onClick='location.href=""h_mail3.asp?sort=" & sno & """'>" & text & "</th>"
  End If
End Sub

sort = Request.QueryString("sort")
If IsEmpty(sort) Then
  sort = 1
Else
  sort = CInt(sort)
End If

Set DB = OpenDB()
Set RS = Server.CreateObject("ADODB.RecordSet")
SQL = "SELECT MM_mel_id,MM_h_name,MM_mail_adr,MM_status FROM T_MELMEL WHERE MM_status<>9 ORDER BY " & sort
RS.Open SQL, DB, adOpenForwardOnly, adLockReadOnly
%>

<table width="100%" border=0 cellpadding=0 cellspacing=0><tr><td>
<form name="form1" style="margin:0">

<table width="100%" border=0 cellpadding=0 cellspacing=0>
  <tr>
    <td align="left">■送信先の発行者を選択してください。</td>
    <td align="right">
      <input type="button" value="活動中" onClick="OnClick_katsudou()">
      <input type="button" value="休会中" onClick="OnClick_kyuukai()">
      <input type="button" value="全選択" onClick="OnClick_all()">
      <input type="button" value=" ＯＫ " onClick="OnClick_OK()">
    </td>
  </tr>
</table>

<table border=1 cellpadding=1 cellspacing=1 width="100%" align="center">
  <tr bgcolor='#FFE0E0'>
<%
TableHeader "発行者ID", 1
TableHeader "発行者名", 2
TableHeader "e-mailアドレス", 3
%>
      <th>選択</th>
    </tr>
<%
line = 0
Do Until RS.EOF
  If line Mod 2 Then
    Writeln "<tr bgcolor='#FFFFFF'>"
  Else
    Writeln "<tr bgcolor='#E0FFE0'>"
  End If
  Writeln "<td align='center'>" & RS("MM_mel_id") & "<input type='hidden' name='status' value='" & RS("MM_status") & "'></td>"
  Writeln "<td>" & RS("MM_h_name") & "</td>"
  Writeln "<td>" & RS("MM_mail_adr") & "</td>"
  Writeln "<td align='center'><input type='checkbox' name='sel' value='" & RS("MM_mel_id") & "(" & RS("MM_h_name") & ")" & "' onClick='OnClick_sel(" & line & ")'></td>"
  Writeln "</tr>"

  RS.MoveNext
  line = line + 1
Loop
RS.close
%>
</table>

<center>
<br>
<input type="button" value=" ＯＫ " onClick="OnClick_OK()">
</center>

</form>
</td></tr></table>

</body>
</html>
