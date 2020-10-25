<%
If Request.Cookies("user_login_id") = "" Then
	Response.Redirect "../login/login.asp"
End If
%>