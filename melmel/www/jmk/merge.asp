<!--#INCLUDE FILE="../melmel_inc.asp"-->
<!--#INCLUDE FILE="admin_inc.asp"-->
<%
'******************************************************
' System :めるめる・net事務局用ページ
' Content:発行者ID統合画面
'******************************************************

SetHeaderTitle "発行者ID統合", TC_HAKKOUSHA
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
  var rt;
  with (document.form1) {
    if (mel_id.value.length != 5) {
      alert("発行者ID１（５桁）を入力してください。");
      mel_id.focus();
      return false;
    }
    if (mel_id2.value.length != 5) {
      alert("発行者ID２（５桁）を入力してください。");
      mel_id2.focus();
      return false;
    }
    if (mel_id.value == mel_id2.value) {
      alert("発行者IDが同じです。");
      mel_id.focus();
      return false;
    }
    rt = confirm("発行者ID[" + mel_id.value + "]に[" + mel_id2.value + "]を統合します。よろしいですか？");
  }
  return rt;
}
//-->
</SCRIPT>
</head>
<body onLoad="document.form1.mel_id.focus()">

<!--#INCLUDE FILE="admin_header.asp"-->

<center>
  <form method="post" name="form1" action="merge2.asp" onSubmit="return OnSubmit_form1();">
    <p>■発行者ID1に発行者ID2を統合します。</p>
    <table border=0 cellspacing=0 cellpadding=4>
      <tr>
        <td>発行者ID１</td>
        <td><input type="text" name="mel_id" maxlength=5></td>
      </tr>
      <tr>
        <td>発行者ID２</td>
        <td><input type="text" name="mel_id2" maxlength=5></td>
      </tr>
    </table>
    <br>
    <input type="submit" value=" 統合 ">
  </form>
</center>

<!--#INCLUDE FILE="admin_footer.asp"-->

</body>
</html>
