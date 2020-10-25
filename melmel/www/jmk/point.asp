<!--#INCLUDE FILE="../melmel_inc.asp"-->
<!--#INCLUDE FILE="admin_inc.asp"-->
<%
'******************************************************
' System :めるめる・net事務局用ページ
' Content:ポイント管理一覧表示
'******************************************************

SetHeaderTitle "ポイント管理", TC_HAKKOUSHA
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
function OnClick_mel_id(id) {
  var win;
  win = window.open("mel_info.asp?id=" + id, "mel_info", "resizable=yes,scrollbars=yes,width=740");
  win.focus();
}
function OnClick_sakujo(seq_no) {
  if (confirm("指定したデータを削除します。よろしいですか？"))
    location.href = "point4.asp?no=" + seq_no;
}
function OnSubmit_form1() {
  if (document.form1.mel_id.value == "") {
    alert("発行者IDを入力してください。");
    document.form1.mel_id.focus();
    return false;
  }
  return true;
}
//-->
</SCRIPT>
</head>
<body>

<!--#INCLUDE FILE="admin_header.asp"-->

<%
Dim DB, RS, SQL, line

Set DB = OpenDB()
Set RS = Server.CreateObject("ADODB.RecordSet")
SQL = "SELECT MP_seq_no,MP_mel_id,MM_h_name,MP_point,MP_comment,MP_date" & vbCrLf & _
      "FROM T_MPOINT INNER JOIN T_MELMEL ON MP_mel_id=MM_mel_id" & vbCrLf & _
      "ORDER BY MP_seq_no DESC"
RS.Open SQL, DB, adOpenKeyset, adLockReadOnly
%>

<form method="post" action="point2.asp" name="form1" style="margin:0" onSubmit="return OnSubmit_form1()">
<table width="100%">
  <tr>
    <td align="left">
      ■発行者ID&nbsp;<input type="text" name="mel_id">
      <input type="submit" value="新規入力">
    </td>
    <td align="right">件数：<%=RS.RecordCount%></td>
  </tr>
</table>

<table border=1 cellpadding=1 cellspacing=1 width="100%" align="center">
  <tr bgcolor="#FFE0E0">
    <th>発行者ID</th>
    <th>発行者名</th>
    <th>ポイント</th>
    <th>コメント</th>
    <th>登録日</th>
    <th>削除</th>
  </tr>

<%
line = 1
Do Until RS.EOF
  If line Mod 2 = 0 Then
    Writeln "<tr bgcolor='#FFFFFF'>"
  Else
    Writeln "<tr bgcolor='#E0FFE0'>"
  End If
  Writeln "<td align='center'><a href='JavaScript:OnClick_mel_id(""" & RS("MP_mel_id") & """)'>" & RS("MP_mel_id") & "</a></td>"
  Writeln "<td>" & ReplaceTag(RS("MM_h_name")) & "</td>"
  Writeln "<td align='right'>" & FormatNumber(RS("MP_point"), 2, -1, 0, 0) & "</td>"
  Writeln "<td>" & ReplaceTag(RS("MP_comment")) & "</td>"
  Writeln "<td align='center'>" & DateFormat(RS("MP_date")) & "</td>"
  Writeln "<td align='center'><input type='button' value='削除' onClick='OnClick_sakujo(""" & RS("MP_seq_no") & """)'></td>"
  Writeln "</tr>"

  RS.MoveNext
  line = line + 1
Loop
RS.close
%>

</table>
</form>

<!--#INCLUDE FILE="admin_footer.asp"-->

</body>
</html>
