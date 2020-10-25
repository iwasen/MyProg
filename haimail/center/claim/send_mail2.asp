<!--#INCLUDE FILE="../inc/common.asp"-->
<!--#INCLUDE FILE="../inc/sub.asp"-->
<!--#INCLUDE FILE="../inc/database.asp"-->
<!--#INCLUDE FILE="../inc/login.asp"-->
<!--#INCLUDE FILE="../inc/menu.asp"-->
<%
'******************************************************
' System :はいめーる・net事務局用ページ
' Content:クレーム管理／メール送信処理
'******************************************************

'=== メイン処理 ===
Dim sql, ds, seq_no, subject, from, body
		
SetHeaderTitle "クレーム対応メール送信結果"
Response.CacheControl = "no-cache"

seq_no = Request.Form("seq_no")
If IsEmpty(seq_no) Then
	SystemError "empty seq_no"
End If

SendMail Request.Form("to"), Request.Form("from"), Request.Form("subject"), Request.Form("body")
%>
<html>
<head>
<meta HTTP-EQUIV="Content-Type" Content="text/html; charset=x-sjis">
<title><%=title_text%></title>
<link rel="stylesheet" type="text/css" href="../haimail.css">
</head>
<body>

<!--#INCLUDE FILE="../inc/header.asp"-->

<center>
<p>メールを送信しました。</p>
<input type="button" value=" ＯＫ " onclick="location.href='set_status.asp?seq=<%=seq_no%>&status=1'">
</center>

<!--#INCLUDE FILE="../inc/footer.asp"-->

</body>
</html>
