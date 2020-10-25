<!--#INCLUDE FILE="../melmel_inc.asp"-->
<!--#INCLUDE FILE="admin_inc.asp"-->
<%
'******************************************************
' System :めるめる・net事務局用ページ
' Content:コントロールマスタＤＢ書き込み処理
'******************************************************

SetHeaderTitle "コントロールマスタ更新", TC_MASTER
%>
<%
Dim DB, RS, SQL

If Not IsEmpty(Request.Form("submit1")) Then
  Set DB = OpenDB()
  Set RS = Server.CreateObject("ADODB.RecordSet")

  SQL = "SELECT * FROM T_CONTROL"
  RS.Open SQL, DB, adOpenKeyset, adLockPessimistic
  If RS.EOF Then
    RS.AddNew
  End If

  RS("CT_smtp_server1") = Request.Form("smtp_server1")
  RS("CT_smtp_server2") = Request.Form("smtp_server2")
  RS("CT_pop3_server1") = Request.Form("pop3_server1")
  RS("CT_pop3_server2") = Request.Form("pop3_server2")
  RS("CT_pop3_kmag_user") = Request.Form("pop3_kmag_user")
  RS("CT_pop3_kmag_pass") = Request.Form("pop3_kmag_pass")
  RS("CT_pop3_hmag_user1") = Request.Form("pop3_hmag_user1")
  RS("CT_pop3_hmag_pass1") = Request.Form("pop3_hmag_pass1")
  RS("CT_pop3_hmag_user2") = Request.Form("pop3_hmag_user2")
  RS("CT_pop3_hmag_pass2") = Request.Form("pop3_hmag_pass2")
  RS("CT_pop3_report_user") = Request.Form("pop3_report_user")
  RS("CT_pop3_report_pass") = Request.Form("pop3_report_pass")
  RS("CT_pop3_test_user") = Request.Form("pop3_test_user")
  RS("CT_pop3_test_pass") = Request.Form("pop3_test_pass")
  RS("CT_test_mail") = Request.Form("test_mail")
  RS("CT_auto_interval") = Request.Form("auto_interval")
  RS("CT_log_limit") = Request.Form("log_limit")
  RS.Update
  RS.Close
%>
<html>
<head>
<meta HTTP-EQUIV="Content-Type" Content="text/html; charset=x-sjis">
<meta HTTP-EQUIV="Pragma" Content="no-cache">
<meta name="GENERATOR" Content="Microsoft Visual Studio 6.0">
<title><%=title_text%></title>
<link rel="stylesheet" type="text/css" href="admin.css">
</head>
<body>

<!--#INCLUDE FILE="admin_header.asp"-->

<center>
<p>更新しました。</p>
<input type="button" value=" ＯＫ " onClick="location.href='index.asp'">
</center>

<!--#INCLUDE FILE="admin_footer.asp"-->

</body>
</html>
<%
Else
  Response.Redirect("index.asp")
End If
%>