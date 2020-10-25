<!--#INCLUDE FILE="../inc/common.asp"-->
<!--#INCLUDE FILE="../inc/sub.asp"-->
<!--#INCLUDE FILE="../inc/database.asp"-->
<!--#INCLUDE FILE="../inc/login.asp"-->
<%
'******************************************************
' System :はいめーる・net事務局用ページ
' Content:コントロールマスタＤＢ書き込み処理
'******************************************************

SetHeaderTitle "コントロールマスタ更新"

Dim sql, ds

If Not IsEmpty(Request.Form("submit1")) Then
	sql = "SELECT * FROM T_CONTROL WHERE CT_control_id='0'"
	Set ds = CreateDynaset(sql, ORADYN_DEFAULT)
	If ds.EOF Then
		ds.AddNew
	Else
		ds.Edit
	End If

	ds("CT_smtp_server1").Value = Request.Form("smtp_server1")
	ds("CT_smtp_server2").Value = Request.Form("smtp_server2")
	ds("CT_pop3_server").Value = Request.Form("pop3_server")
	ds("CT_pop3_regist_user").Value = Request.Form("pop3_regist_user")
	ds("CT_pop3_regist_pass").Value = Request.Form("pop3_regist_pass")
	ds("CT_auto_interval").Value = Request.Form("auto_interval")
	ds("CT_log_limit").Value = Request.Form("log_limit")
	ds("CT_error_mail").Value = Request.Form("error_mail")
	ds("CT_sokuhou_adr").Value = Request.Form("sokuhou_adr")
	ds("CT_reminder").Value = Request.Form("reminder")
	ds("CT_enq_error").Value = Request.Form("enq_error")
	ds("CT_reserve_adr").Value = Request.Form("reserve_adr")
	ds("CT_send_report_adr").Value = Request.Form("send_report_adr")
	ds("CT_ud_user").Value = Request.Form("ud_user")
	ds("CT_ud_pass").Value = Request.Form("ud_pass")
	ds("CT_ud_count").Value = Request.Form("ud_count")
	ds.Update
%>
<html>
<head>
<meta HTTP-EQUIV="Content-Type" Content="text/html; charset=x-sjis">
<meta HTTP-EQUIV="Pragma" Content="no-cache">
<title><%=title_text%></title>
<link rel="stylesheet" type="text/css" href="../haimail.css">
</head>
<body>

<!--#INCLUDE FILE="../inc/header.asp"-->

<center>
<p>更新しました。</p>
<input type="button" value=" ＯＫ " onclick="location.href='index.asp'">
</center>

<!--#INCLUDE FILE="../inc/footer.asp"-->

</body>
</html>
<%
Else
	Response.Redirect("index.asp")
End If
%>