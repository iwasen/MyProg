<!--#INCLUDE FILE="../inc/common.asp"-->
<!--#INCLUDE FILE="../inc/database.asp"-->
<!--#INCLUDE FILE="../inc/sub.asp"-->
<!--#INCLUDE FILE="../inc/login.asp"-->
<%
'******************************************************
' System :はいめーる・net事務局用ページ
' Content:Webメールドメインマスタ更新処理
'******************************************************

SetHeaderTitle "Webメールドメインマスタ更新"
Response.CacheControl = "no-cache"

Dim ds, sql, id, domain_id

domain_id = Request.Form("domain_id")

If Not IsEmpty(domain_id) Then
	domain_id = MakeID(domain_id, 2)

	sql = "SELECT max(MD_domain_id) FROM T_MAIL_DOMAIN"
	Set ds = CreateDynaset(sql, ORADYN_READONLY)
	id = NVL(ds(0), 0) + 1

	sql = "SELECT * FROM T_MAIL_DOMAIN WHERE MD_domain_id='" & domain_id & "'"
	Set ds = CreateDynaset(sql, ORADYN_DEFAULT)
	If ds.EOF Then
		ds.Addnew
		ds("MD_domain_id").Value = MakeID(id, 2)
	Else
		ds.Edit
	End If
	ds("MD_name").Value = Request.Form("domain_name")
	ds("MD_order").Value = Request.Form("order")
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
<input type="button" value=" ＯＫ " onclick="location.href='mail_domain.asp'">
</center>


<!--#INCLUDE FILE="../inc/footer.asp"-->

</body>
</html>
<%
Else
	Response.Redirect("domain.asp")
End If
%>