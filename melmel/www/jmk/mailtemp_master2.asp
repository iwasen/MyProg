<!--#INCLUDE FILE="../melmel_inc.asp"-->
<!--#INCLUDE FILE="admin_inc.asp"-->
<%
'******************************************************
' System :めるめる・net事務局用ページ
' Content:送信メールテンプレート修正画面
'******************************************************

SetHeaderTitle "送信メールテンプレート修正", TC_MASTER
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
  with (document.form1) {
    if (subject.value == "") {
      alert("Subjectを入力してください。");
      subject.focus();
      return false;
    }
    if (from.value == "") {
      alert("Fromを入力してください。");
      from.focus();
      return false;
    }
    if (body.value == "") {
      alert("本文を入力してください。");
      body.focus();
      return false;
    }
  }
  return confirm("送信メールテンプレートを更新します。よろしいですか？");
}
//-->
</SCRIPT>
</head>
<body>

<!--#INCLUDE FILE="admin_header.asp"-->

<%
Dim DB, RS, SQL, id

id = Request.QueryString("id")
If IsEmpty(id) Then
  Response.Redirect("index.asp")
End If

Set DB = OpenDB()
Set RS = Server.CreateObject("ADODB.RecordSet")
SQL = "SELECT * FROM T_SENDMAIL WHERE SM_id='" & id & "'"
RS.Open SQL, DB, adOpenForwardOnly, adLockReadOnly
%>
<center>
<form method="post" action="mailtemp_master3.asp" name="form1" onSubmit="return OnSubmit_form1();">
  <input type="hidden" name="id" value="<%=RS("SM_id")%>">
  <table border=0 cellspacing=0 cellpadding=0 width='750'>
    <tr>
      <td>■メール情報 <font size="-1">（"%"で囲まれた部分はプログラムでデータを埋め込むので、変更しないでください）</font></td>
    </tr>
    <tr>
      <td>
        <table border=0 cellspacing=2 cellpadding=3 width="100%">
          <tr>
            <td class="m3" width="20%">種類</td>
            <td class="n3"><%=RS("SM_guide")%></td>
          </tr>
          <tr>
            <td class="m3">Subject</td>
            <td class="n3"><input type="text" name="subject" size=80 value="<%=RS("SM_subject")%>"></td>
          </tr>
          <tr>
            <td class="m3">From</td>
            <td class="n3"><input type="text" name="from" size=80 value="<%=RS("SM_from")%>"></td>
          </tr>
          <tr>
            <td class="m3">本文</td>
            <td class="n3"><textarea cols=80 rows=30 name="body"><%=RS("SM_body")%></textarea></td>
          </tr>
        </table>
      </td>
    </tr>
  </table>

  <br>
  <input type="submit" value=" 更新 ">
  <input type="reset" value="リセット">
  <input type="button" value=" 戻る " onClick="history.back()">

</form>
</center>

<%
RS.Close
%>

<!--#INCLUDE FILE="admin_footer.asp"-->

</body>
</html>
