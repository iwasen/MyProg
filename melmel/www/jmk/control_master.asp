<!--#INCLUDE FILE="../melmel_inc.asp"-->
<!--#INCLUDE FILE="admin_inc.asp"-->
<%
'******************************************************
' System :めるめる・net事務局用ページ
' Content:コントロールマスタ修正画面
'******************************************************

SetHeaderTitle "コントロールマスタ", TC_MASTER
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
  return confirm("コントロールマスタを更新します。よろしいですか？");
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
SQL = "SELECT * FROM T_CONTROL"
RS.Open SQL, DB, adOpenForwardOnly, adLockReadOnly
%>
<center>
<form method="post" action="control_master2.asp" name="form1" onSubmit="return OnSubmit_form1();">
  <table border=0 cellspacing=0 cellpadding=0 width="700">
    <tr>
      <td>■メールサーバ情報</td>
    </tr>
    <tr>
      <td>
        <table border=0 align="center" width="100%">
          <tr>
            <td class="m3" width="40%">メインSMTPサーバ名</td>
            <td class="n3"><input type="text" name="smtp_server1" size=50 value="<%=RS("CT_smtp_server1")%>"></td>
          </tr>
          <tr>
            <td class="m3">バックアップSMTPサーバ名</td>
            <td class="n3"><input type="text" name="smtp_server2" size=50 value="<%=RS("CT_smtp_server2")%>"></td>
          </tr>
          <tr>
            <td class="m3">メインPOP3サーバ名</td>
            <td class="n3"><input type="text" name="pop3_server1" size=50 value="<%=RS("CT_pop3_server1")%>"></td>
          </tr>
          <tr>
            <td class="m3">バックアップPOP3サーバ名</td>
            <td class="n3"><input type="text" name="pop3_server2" size=50 value="<%=RS("CT_pop3_server2")%>"></td>
          </tr>
        </table>
      </td>
    </tr>
    <tr><td><br></td></tr>
    <tr>
      <td>■メールアカウント情報</td>
    </tr>
    <tr>
      <td>
        <table border=0 align="center" width="100%">
          <tr>
            <td class="m3" width="40%">仮登録メルマガユーザ名</td>
            <td class="n3"><input type="text" name="pop3_kmag_user" size=50 value="<%=RS("CT_pop3_kmag_user")%>"></td>
          </tr>
          <tr>
            <td class="m3">仮登録メルマガパスワード</td>
            <td class="n3"><input type="password" name="pop3_kmag_pass" size=50 value="<%=RS("CT_pop3_kmag_pass")%>"></td>
          </tr>
          <tr>
            <td class="m3">本登録メルマガユーザ名</td>
            <td class="n3"><input type="text" name="pop3_hmag_user1" size=50 value="<%=RS("CT_pop3_hmag_user1")%>"></td>
          </tr>
          <tr>
            <td class="m3">本登録メルマガパスワード</td>
            <td class="n3"><input type="password" name="pop3_hmag_pass1" size=50 value="<%=RS("CT_pop3_hmag_pass1")%>"></td>
          </tr>
          <tr>
            <td class="m3">本登録メルマガユーザ名(Backup)</td>
            <td class="n3"><input type="text" name="pop3_hmag_user2" size=50 value="<%=RS("CT_pop3_hmag_user2")%>"></td>
          </tr>
          <tr>
            <td class="m3">本登録メルマガパスワード(Backup)</td>
            <td class="n3"><input type="password" name="pop3_hmag_pass2" size=50 value="<%=RS("CT_pop3_hmag_pass2")%>"></td>
          </tr>
          <tr>
            <td class="m3">掲載報告メールユーザ名</td>
            <td class="n3"><input type="text" name="pop3_report_user" size=50 value="<%=RS("CT_pop3_report_user")%>"></td>
          </tr>
          <tr>
            <td class="m3">掲載報告メールパスワード</td>
            <td class="n3"><input type="password" name="pop3_report_pass" size=50 value="<%=RS("CT_pop3_report_pass")%>"></td>
          </tr>
          <tr>
            <td class="m3">掲載依頼テストメールユーザ名</td>
            <td class="n3"><input type="text" name="pop3_test_user" size=50 value="<%=RS("CT_pop3_test_user")%>"></td>
          </tr>
          <tr>
            <td class="m3">掲載依頼テストメールパスワード</td>
            <td class="n3"><input type="password" name="pop3_test_pass" size=50 value="<%=RS("CT_pop3_test_pass")%>"></td>
          </tr>
          <tr>
            <td class="m3">掲載依頼テストメール送信先アドレス</td>
            <td class="n3"><input type="text" name="test_mail" size=50 value="<%=RS("CT_test_mail")%>"></td>
          </tr>
        </table>
      </td>
    </tr>
    <tr><td><br></td></tr>
    <tr>
      <td>■自動処理設定情報</td>
    </tr>
    <tr>
      <td>
        <table border=0 align="center" width="100%">
          <tr>
            <td class="m3" width=280>自動処理実行インターバル</td>
            <td class="n3"><input type="text" name="auto_interval" value="<%=RS("CT_auto_interval")%>"> 秒</td>
          </tr>
          <tr>
            <td class="m3" width=280>ログ保存期間</td>
            <td class="n3"><input type="text" name="log_limit" value="<%=RS("CT_log_limit")%>"> 日</td>
          </tr>
        </table>
      </td>
    </tr>
  </table>
  </br>
  <input type="submit" value=" 更新 " name="submit1">
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
