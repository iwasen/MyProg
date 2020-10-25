<!--#INCLUDE FILE="../inc/common.asp"-->
<!--#INCLUDE FILE="../inc/database.asp"-->
<!--#INCLUDE FILE="../inc/sub.asp"-->
<!--#INCLUDE FILE="../inc/login.asp"-->
<%
'******************************************************
' System :はいめーる・net事務局用ページ
' Content:職業マスタ更新処理
'******************************************************

SetHeaderTitle "職業マスタ更新"
Response.CacheControl = "no-cache"

Dim ds, sql, seq_no, id, shokugyou_id

shokugyou_id = Request.Form("shokugyou_id")

If Not IsEmpty(shokugyou_id) Then
	shokugyou_id = MakeID(shokugyou_id, 2)

	sql = "SELECT max(SG_shokugyou_code) FROM T_SHOKUGYOU"
	Set ds = CreateDynaset(sql, ORADYN_READONLY)
	id = NVL(ds(0), 0) + 1

	sql = "SELECT * FROM T_SHOKUGYOU WHERE SG_shokugyou_code='" & shokugyou_id & "'"
	Set ds = CreateDynaset(sql, ORADYN_DEFAULT)

		If ds.EOF Then
			ds.Addnew
			ds("SG_shokugyou_code").Value = MakeID(id, 2)
		Else
			ds.Edit
		End If
		ds("SG_seq_no").Value = Request.Form("seq_no")
		ds("SG_shokugyou_name").Value = Request.Form("shokugyou_name")
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
<input type="button" value=" ＯＫ " onclick="location.href='shokugyou.asp'">
</center>


<!--#INCLUDE FILE="../inc/footer.asp"-->

</body>
</html>
<%
Else
	Response.Redirect("shokugyou.asp")
End If
%>