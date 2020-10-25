<!--#INCLUDE FILE="../melmel_inc.asp"-->
<!--#INCLUDE FILE="admin_inc.asp"-->
<%
'******************************************************
' System :めるめる・net事務局用ページ
' Content:クライアントマスタ登録画面
'******************************************************

SetHeaderTitle "クライアントマスタ登録", TC_MASTER
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
    if (client_id.value == "") {
      alert("クライアントIDを入力してください。");
      client_id.focus();
      return false;
    }
    if (client_id.value.length != 5) {
      alert("クライアントIDは５桁で入力してください。");
      client_id.focus();
      return false;
    }
    if (client_name.value == "") {
      alert("クライアント名を入力してください。");
      client_name.focus();
      return false;
    }
  }
  return confirm("登録します。よろしいですか？");
}
//-->
</SCRIPT>
</head>
<body onLoad="document.form1.client_id.focus()">

<!--#INCLUDE FILE="admin_header.asp"-->

<center>
<form method="post" action="client_master4.asp" name="form1" onSubmit="return OnSubmit_form1();">
  <table border=0 cellspacing=0 cellpadding=0 width='700'>
    <tr>
      <td>■クライアント情報</font></td>
    </tr>
    <tr>
      <td>
        <table border=0 cellspacing=2 cellpadding=3 width="100%">
          <tr>
            <td class="m3" width="25%">クライアントID</td>
            <td class="n3"><input type="text" name="client_id" size=5 maxlength=5> <font size="-1">（新しいＩＤを５桁の数字で入力してください）</font></td>
          </tr>
          <tr>
            <td class="m3">クライアント名</td>
            <td class="n3"><input type="text" name="client_name" size=90 maxlength=100></td>
          </tr>
          <tr>
            <td class="m3">組織名</td>
            <td class="n3"><input type="text" name="company" size=90 maxlength=100></td>
          </tr>
          <tr>
            <td class="m3">担当者名</td>
            <td class="n3"><input type="text" name="tantousha" size=50 maxlength=50></td>
          </tr>
          <tr>
            <td class="m3">パスワード</td>
            <td class="n3"><input type="text" name="password" size=20 maxlength=20></td>
          </tr>
          <tr>
            <td class="m3">連絡先メールアドレス</td>
            <td class="n3"><input type="text" name="mail_adr" size=50 maxlength=100></td>
          </tr>
          <tr>
            <td class="m3">連絡先電話番号</td>
            <td class="n3"><input type="text" name="tel_no" size=20 maxlength=20></td>
          </tr>
        </table>
      </td>
    </tr>
  </table>

  <br>
  <input type="hidden" name="new" value="1">
  <input type="submit" value=" 登録 ">
  <input type="reset" value="リセット">
  <input type="button" value=" 戻る " onClick="history.back()">

</form>
</center>

<!--#INCLUDE FILE="admin_footer.asp"-->

</body>
</html>
