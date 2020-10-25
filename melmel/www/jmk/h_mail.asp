<!--#INCLUDE FILE="../melmel_inc.asp"-->
<!--#INCLUDE FILE="admin_inc.asp"-->
<%
'******************************************************
' System :めるめる・net事務局用ページ
' Content:発行者宛てメール入力画面
'******************************************************

SetHeaderTitle "発行者宛てメール", TC_HAKKOUSHA
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
function OnClick_sentaku() {
  var win;
  win = window.open("h_mail3.asp", "h_mail3", "resizable=yes,width=700,height=600,scrollbars=yes");
  win.focus();
}
function OnSubmit_form1() {
  with (document.form1) {
    if (mailto.value == "") {
      alert("宛先を入力してください。");
      mailto.focus();
      return false;
    }
    if (mailfrom.value == "") {
      alert("送信元を入力してください。");
      mailfrom.focus();
      return false;
    }
    if (subject.value == "") {
      alert("件名を入力してください。");
      subject.focus();
      return false;
    }
    if (body.value == "") {
      alert("本文を入力してください。");
      body.focus();
      return false;
    }
  }
  if (confirm("メールを送信します。よろしいですか？")) {
    document.all("msg").style.display = "";
    return true;
  } else
    return false;
}
//-->
</SCRIPT>
</head>
<body>

<!--#INCLUDE FILE="admin_header.asp"-->

<%
Dim mailfrom, subject, body

Call GetSendMailInfo("HMAIL", subject, mailfrom, body)
%>

<center>
<form method="post" action="h_mail2.asp" name="form1" onSubmit="return OnSubmit_form1();">
  <table border=0 cellspacing=0 cellpadding=0 width="700">
    <tr>
      <td>■宛先は発行者IDをカンマで区切って入力するか、「選択」ボタンをクリックしてください。</td>
    </tr>
    <tr>
      <td>
        <table border=0 cellspacing=2 cellpadding=3 width='100%'>
          <tr>
            <td class="m1" width="14%">宛先</td>
            <td class="n1"><textarea name="mailto" cols=70 rows=5></textarea> <input type="button" value="選択" onClick="OnClick_sentaku()"></td>
          </tr>
          <tr>
            <td class="m1" width="14%">送信元</td>
            <td class="n1"><input type="text" name="mailfrom" size=80 value="<%=mailfrom%>"></td>
          </tr>
          <tr>
            <td class="m1" width="14%">件名</td>
            <td class="n1"><input type="text" name="subject" size=80 value="<%=subject%>"></td>
          </tr>
        </table>
      </td>
    </tr>
    <tr>
      <td><br></td>
    </tr>
    <tr>
      <td>■<small>%HAKKOUSHA%</small>には発行者名、<small>%MELMELID%</small>には発行者ID、<small>%POINT%</small>には現ポイント数が埋め込まれます。</small></td>
    </tr>
    <tr>
      <td>
        <table border=0 cellspacing=2 cellpadding=3 width='100%'>
          <tr>
            <td class="m1" width="14%">本文</td>
            <td class="n1"><textarea name="body" cols=80 rows=30><%=body%></textarea></td>
          </tr>
        </table>
      </td>
    </tr>
  </table>

  <br>
  <input type="submit" value=" 送信 ">
  <input type="reset" value="リセット">
</form>
<div id="msg" style="display:none"><font color="red"><b>メールを送信中です。しばらくお待ちください・・・</b></font></div>

<!--#INCLUDE FILE="admin_footer.asp"-->

</body>
</html>
