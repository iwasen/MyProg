<!--#INCLUDE FILE="../inc/common.asp"-->
<!--#INCLUDE FILE="../inc/sub.asp"-->
<%
Dim login_type
Dim url

If Request.Cookies("login") <> "" Then
	login_type = Left(Request.Cookies("login"), 1)
End If

Response.Cookies("login") = ""

Session.Abandon

Select Case login_type
Case LOGIN_CLIENT
	url = top_url & "client.asp"
Case LOGIN_GUEST
	url = top_url & "guest.asp"
Case Else
	url = top_url & "index.asp"
End Select

Response.Redirect url
%>