<!--#INCLUDE FILE="../melmel_inc.asp"-->
<!--#INCLUDE FILE="admin_inc.asp"-->
<%
'******************************************************
' System :めるめる・net事務局用ページ
' Content:広告罫線マスタ修正画面
'******************************************************

SetHeaderTitle "広告罫線マスタ修正", TC_MASTER

Dim keisen_id

keisen_id = Request.QueryString("id")
If IsEmpty(keisen_id) Then
  Response.Redirect("index.asp")
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
function OnSubmit_form1() {
  return confirm("更新します。よろしいですか？");
}
function OnClick_sakujo() {
  if (confirm("この広告罫線を削除してよろしいですか？")) {
    location.href = "keisen_master5.asp?id=<%=keisen_id%>";
  }
}
//-->
</SCRIPT>
</head>
<body>

<!--#INCLUDE FILE="admin_header.asp"-->

<%
Dim DB, RS, SQL

Set DB = OpenDB()
Set RS = Server.CreateObject("ADODB.RecordSet")
SQL = "SELECT * FROM T_KEISEN WHERE KE_keisen_id='" & keisen_id & "'"
RS.Open SQL, DB, adOpenForwardOnly, adLockReadOnly
%>
<center>
<form method="post" action="keisen_master4.asp" name="form1" onSubmit="return OnSubmit_form1();">
  <input type="hidden" name="keisen_id" value="<%=RS("KE_keisen_id")%>">
  <table border=0 cellspacing=0 cellpadding=0 width='700'>
    <tr>
      <td>■広告罫線情報 <font size="-1">（KKKKKKには広告ID、MMMMMにはメルマガＩＤが埋め込まれます）</font></td>
    </tr>
    <tr>
      <td>
        <table border=0 cellspacing=2 cellpadding=3 width="100%">
          <tr>
            <td class="m3" width="20%">罫線ID</td>
            <td class="n3"><%=RS("KE_keisen_id")%></td>
          </tr>
          <tr>
            <td class="m3">開始罫線</td>
            <td class="n3"><input type="text" name="start_line" size=78 class="np" value="<%=RS("KE_start_line")%>"></td>
          </tr>
          <tr>
            <td class="m3">終了罫線</td>
            <td class="n3"><input type="text" name="end_line" size=78 class="np" value="<%=RS("KE_end_line")%>"></td>
          </tr>
        </table>
      </td>
    </tr>
  </table>

  <br>
  <input type="submit" value=" 更新 ">
  <input type="reset" value="リセット">
  <input type="button" value=" 削除 " onClick="OnClick_sakujo()">
  <input type="button" value=" 戻る " onClick="history.back()">

</form>
</center>

<%
RS.Close
%>

<!--#INCLUDE FILE="admin_footer.asp"-->

</body>
</html>
