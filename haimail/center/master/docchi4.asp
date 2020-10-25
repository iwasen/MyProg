<!--#INCLUDE FILE="../inc/common.asp"-->
<!--#INCLUDE FILE="../inc/database.asp"-->
<!--#INCLUDE FILE="../inc/sub.asp"-->
<!--#INCLUDE FILE="../inc/login.asp"-->
<%
'******************************************************
' System :はいめーる・net事務局用ページ
' Content:どっち派マスタ更新処理
'******************************************************

SetHeaderTitle "どっち派マスタ更新"
Response.CacheControl = "no-cache"

Dim ds, sql, line, id, docchi_id

docchi_id = Request.Form("docchi_id")

If Not IsEmpty(docchi_id) Then
	sql = "SELECT max(DH_index) FROM T_DOCCHI_HA"
	Set ds = CreateDynaset(sql, ORADYN_READONLY)
	id = ds(0) + 1

	sql = "SELECT * FROM T_DOCCHI_HA WHERE DH_index='" & docchi_id & "'"
	Set ds = CreateDynaset(sql, ORADYN_DEFAULT)

	If ds.EOF Then
		ds.Addnew
		ds("DH_index").Value = id
	Else
		ds.Edit
		ds("DH_index").Value = docchi_id
	End If
	ds("DH_item_no").Value = Request.Form("item_no")
	ds("DH_item1_text").Value = Request.Form("item1_text")
	ds("DH_item2_text").Value = Request.Form("item2_text")
	ds("DH_valid_flag").Value = Request.Form("docchiha")
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
<input type="button" value=" ＯＫ " onclick="location.href='docchi.asp'">
</center>
<!--#INCLUDE FILE="../inc/footer.asp"-->
</body>
</html>
<%
Else
	Response.Redirect("docchi.asp")
End If
%>