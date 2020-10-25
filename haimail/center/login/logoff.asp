<!--#INCLUDE FILE="../inc/common.asp"-->
<!--#INCLUDE FILE="../inc/sub.asp"-->
<%
Response.Cookies("login") = ""

Session.Abandon

Response.Redirect login_page
%>