<!--#INCLUDE FILE="../inc/common.asp"-->
<!--#INCLUDE FILE="../inc/database.asp"-->
<!--#INCLUDE FILE="../inc/sub.asp"-->
<!--#INCLUDE FILE="../inc/login.asp"-->
<%
'******************************************************
' System :はいめーる・net事務局用ページ
' Content:事務局マスタ更新処理
'******************************************************

SetHeaderTitle "事務局マスタ更新"
Response.CacheControl = "no-cache"

Dim ds, sql, user_id

user_id = Request.Form("user_id")
If IsEmpty(user_id) Then
	SystemError "empty user_id"
End If

sql = "SELECT * FROM T_CENTER WHERE CN_user_id='" & user_id & "'"
Set ds = CreateDynaset(sql, ORADYN_DEFAULT)
If ds.EOF Then
	ds.Addnew
	ds("CN_user_id").Value = user_id
Else
	ds.Edit
End If
ds("CN_password").Value = Request.Form("password")
ds("CN_name").Value = Request.Form("name")
ds("CN_mail_adr").Value = Request.Form("mail_adr")
ds("CN_privilege").Value = Request.Form("privilege")
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
<input type="button" value=" ＯＫ " onclick="location.href='center.asp'">
</center>


<!--#INCLUDE FILE="../inc/footer.asp"-->

</body>
</html>
