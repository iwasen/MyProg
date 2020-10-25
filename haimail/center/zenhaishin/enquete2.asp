<!--#INCLUDE FILE="../inc/common.asp"-->
<!--#INCLUDE FILE="../inc/database.asp"-->
<!--#INCLUDE FILE="../inc/sub.asp"-->
<!--#INCLUDE FILE="../inc/login.asp"-->
<%
'******************************************************
' System :はいめーる・net事務局用ページ
' Content:全配信アンケートレスポンスリスト表示画面
'******************************************************

Dim job_id, sub1, sql

job_id = Request.QueryString("job_id")
If IsEmpty(job_id) Then
	SystemError "job_id is empty"
End If

sub1 = "SELECT ZH_mail_id FROM T_ZENHAISHIN WHERE ZH_job_id=" & job_id
sql = "DELETE T_MAIL_READ" & vbCrLf & _
			"WHERE MR_login_type='" & g_login_type & "' AND MR_login_id='" & g_login_id & "' AND MR_enq_imp='E' AND MR_mail_id=(" & sub1 & ")"
ExecuteSQL sql

Response.Redirect "enquete.asp?job_id=" & job_id
%>