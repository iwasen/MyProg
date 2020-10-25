<!--#INCLUDE FILE="../melmel_inc.asp"-->
<!--#INCLUDE FILE="admin_inc.asp"-->
<%
'******************************************************
' System :めるめる・net事務局用ページ
' Content:自己申告部数チェックCSVファイル入力
'******************************************************

SetHeaderTitle "自己申告部数チェック", TC_HAKKOUSHA
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
  if (document.form1.csv.value != "") {
    document.all("msg").style.display = "";
  }
}
//-->
</SCRIPT>
</head>
<body>

<!--#INCLUDE FILE="admin_header.asp"-->

<center>
  <form method="post" name="form1" action="busuu_check2.asp" ENCtype="multipart/form-data" onSubmit="OnSubmit_form1()">
    <p>■まぐまぐ月間ログのCSVファイルを入力してください。<font size="-1">（入力しなくても実行はできます）</font></p>
    <p>まぐまぐ月間ログ <input type="file" name="csv" size=50></p>
    <input type="submit" name="form1" value=" 実行 ">
  <div id="msg" style="display:none"><br><font color="red"><b>ファイルを送信中です。しばらくお待ちください・・・</b></font></div>
  </form>
</center>

<!--#INCLUDE FILE="admin_footer.asp"-->

</body>
</html>
