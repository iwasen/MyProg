<%
'******************************************************
' System :�A���P�[�g�W�v�T�[�r�X
' Content:���O�I�t����
'******************************************************

Response.Cookies("user_login_id") = ""
Session.Abandon
Response.Redirect "login.asp"
%>