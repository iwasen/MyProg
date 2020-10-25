<!--#INCLUDE FILE="../inc/common.asp"-->
<!--#INCLUDE FILE="../inc/database.asp"-->
<!--#INCLUDE FILE="../inc/sub.asp"-->
<!--#INCLUDE FILE="../inc/login.asp"-->
<%
'******************************************************
' System :はいめーる・net事務局用ページ
' Content:ヘッダ、感想文、フッタ更新処理
'******************************************************

SetHeaderTitle "ヘッダ、感想文、フッタ更新"

Dim ds, sql, header, impression, footer, id

id = Request.Form("id")
If IsEmpty(id) Then
	SystemError "empty id"
End If

header = Request.Form("header")
If Not IsEmpty(header) Then
	sql = "SELECT * FROM T_MAIL_HEADER WHERE MH_header_id=" & id
	Set ds = CreateDynaset(sql, ORADYN_DEFAULT)
	If Not ds.EOF Then
		ds.Edit
		ds("MH_text").Value = header
		ds.Update
	End If
End If

impression = Request.Form("impression")
If Not IsEmpty(impression) Then
	sql = "SELECT * FROM T_MAIL_IMP WHERE MI_impression_id=" & id
	Set ds = CreateDynaset(sql, ORADYN_DEFAULT)
	If Not ds.EOF Then
		ds.Edit
		ds("MI_text").Value = impression
		ds.Update
	End If
End If

footer = Request.Form("footer")
If Not IsEmpty(footer) Then
	sql = "SELECT * FROM T_MAIL_FOOTER WHERE MF_footer_id=" & id
	Set ds = CreateDynaset(sql, ORADYN_DEFAULT)
	If Not ds.EOF Then
		ds.Edit
		ds("MF_text").Value = footer
		ds.Update
	End If
End If
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
<input type="button" value=" ＯＫ " onclick="location.href='header.asp'">
</center>

<!--#INCLUDE FILE="../inc/footer.asp"-->

</body>
</html>
