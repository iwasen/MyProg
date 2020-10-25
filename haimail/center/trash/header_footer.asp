<!--#INCLUDE FILE="../inc/common.asp"-->
<!--#INCLUDE FILE="../inc/database.asp"-->
<!--#INCLUDE FILE="../inc/sub.asp"-->
<!--#INCLUDE FILE="../inc/login.asp"-->
<%
'******************************************************
' System :はいめーる・net事務局用ページ
' Content:ヘッダ、感想文、フッタ修正画面
'******************************************************

Dim ds, sql, header, impression, footer

SetHeaderTitle "ヘッダ，フッタ，感想文修正"

sql = "SELECT MH_text FROM T_MAIL_HEADER WHERE MH_header_id=1"
Set ds = CreateDynaset(sql, ORADYN_READONLY)
If Not ds.EOF Then
	header = ds(0).Value
End If

sql = "SELECT MI_text FROM T_MAIL_IMP WHERE MI_impression_id=1"
Set ds = CreateDynaset(sql, ORADYN_READONLY)
If Not ds.EOF Then
	impression = ds(0).Value
End If

sql = "SELECT MF_text FROM T_MAIL_FOOTER WHERE MF_footer_id=1"
Set ds = CreateDynaset(sql, ORADYN_READONLY)
If Not ds.EOF Then
	footer = ds(0).Value
End If
%>
<html>
<head>
<meta HTTP-EQUIV="Content-Type" Content="text/html; charset=x-sjis">
<meta HTTP-EQUIV="Pragma" Content="no-cache">
<meta name="GENERATOR" Content="Microsoft Visual Studio 6.0">
<title><%=title_text%></title>
<link rel="stylesheet" type="text/css" href="../haimail.css">
<SCRIPT LANGUAGE=javascript>
<!--
function OnSubmit_form1() {
  with (document.form1) {
    if (header.value == "") {
      alert("ヘッダを入力してください。");
      header.focus();
      return false;
    }
    if (impression.value == "") {
      alert("感想文を入力してください。");
      impression.focus();
      return false;
    }
    if (footer.value == "") {
      alert("フッタを入力してください。");
      footervalue.focus();
      return false;
    }
  }
  return confirm("ヘッダ／感想文／フッタを更新します。よろしいですか？");
}
//-->
</SCRIPT>
</head>
<body>

<!--#INCLUDE FILE="../inc/header.asp"-->

<center>
<form method="post" action="header_footer2.asp" name="form1" onSubmit="return OnSubmit_form1();">
  <table border=0 cellspacing=0 cellpadding=0 width='750'>
    <tr>
      <td class="m0">■メールヘッダ／感想文／フッタ情報</td>
    </tr>
    <tr>
      <td>
        <table border=0 cellspacing=2 cellpadding=3 width="100%">
          <tr>
            <td class="m3" width="15%">ヘッダ</td>
            <td class="n3"><textarea cols=80 rows=4 name="header" class="np"><%=header%></textarea></td>
          </tr>
          <tr>
            <td class="m3">感想文</td>
            <td class="n3"><textarea cols=80 rows=30 name="impression" class="np"><%=impression%></textarea></td>
          </tr>
          <tr>
            <td class="m3">フッタ</td>
            <td class="n3"><textarea cols=80 rows=12 name="footer" class="np"><%=footer%></textarea></td>
          </tr>
        </table>
      </td>
    </tr>
  </table>

  <br>
  <input type="submit" value=" 更新 ">
  <input type="reset" value="リセット">
  <input type="button" value=" 戻る " onclick="history.back()">

</form>
</center>

<!--#INCLUDE FILE="../inc/footer.asp"-->

</body>
</html>
