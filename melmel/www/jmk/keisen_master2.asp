<!--#INCLUDE FILE="../melmel_inc.asp"-->
<!--#INCLUDE FILE="admin_inc.asp"-->
<%
'******************************************************
' System :めるめる・net事務局用ページ
' Content:広告罫線マスタ登録画面
'******************************************************

SetHeaderTitle "広告罫線マスタ登録", TC_MASTER
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
  return confirm("登録します。よろしいですか？");
}
//-->
</SCRIPT>
</head>
<body onLoad="document.form1.keisen_id.focus()">

<!--#INCLUDE FILE="admin_header.asp"-->

<center>
<form method="post" action="keisen_master4.asp" name="form1" onSubmit="return OnSubmit_form1();">
  <table border=0 cellspacing=0 cellpadding=0 width='700'>
    <tr>
      <td>■広告罫線情報 <font size="-1">（KKKKKKには広告ID、MMMMMにはメルマガＩＤが埋め込まれます）</font></td>
    </tr>
    <tr>
      <td>
        <table border=0 cellspacing=2 cellpadding=3 width="100%">
          <tr>
            <td class="m3" width="20%">罫線ID</td>
            <td class="n3"><input type="text" name="keisen_id" size=1 maxlength=1></td>
          </tr>
          <tr>
            <td class="m3">開始罫線</td>
            <td class="n3"><input type="text" name="start_line" size=78 maxlength=100 class="np"></td>
          </tr>
          <tr>
            <td class="m3">終了罫線</td>
            <td class="n3"><input type="text" name="end_line" size=78 maxlength=100 class="np"></td>
          </tr>
        </table>
      </td>
    </tr>
  </table>

  <br>
  <input type="submit" value=" 登録 ">
  <input type="reset" value="リセット">
  <input type="button" value=" 戻る " onClick="history.back()">

</form>
</center>

<!--#INCLUDE FILE="admin_footer.asp"-->

</body>
</html>
