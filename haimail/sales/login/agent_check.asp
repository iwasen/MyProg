<!--#INCLUDE FILE="../inc/common.asp"-->
<!--#INCLUDE FILE="../inc/database.asp"-->
<!--#INCLUDE FILE="../inc/sub.asp"-->
<!--#INCLUDE FILE="../inc/login_check.asp"-->
<%
'******************************************************
' System :�͂��߁[��Enet�c�Ɨp�y�[�W
' Content:�㗝�X���O�C���`�F�b�N����
'******************************************************

Dim login_id, password, super

login_id = Request.Form("login_id")
password = Request.Form("password")
super = Request.Form("super")

If IsEmpty(login_id) Then
	Response.Redirect "agent.asp"
End If

If LoginCheck(LOGIN_AGENT, Trim(login_id), Trim(password), super) Then
	Response.Redirect "../top/index.asp"
Else
	Response.Redirect "login_error.asp?t=A"
End If
%>