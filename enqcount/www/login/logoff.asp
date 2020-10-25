<%
'******************************************************
' System :アンケート集計サービス
' Content:ログオフ処理
'******************************************************

Response.Cookies("user_login_id") = ""
Session.Abandon
Response.Redirect "login.asp"
%>