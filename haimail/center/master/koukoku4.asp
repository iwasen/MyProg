<!--#INCLUDE FILE="../inc/common.asp"-->
<!--#INCLUDE FILE="../inc/database.asp"-->
<!--#INCLUDE FILE="../inc/sub.asp"-->
<!--#INCLUDE FILE="../inc/login.asp"-->
<%
'******************************************************
' System :はいめーる・net事務局用ページ
' Content:広告タイプマスタ更新処理
'******************************************************

SetHeaderTitle "広告タイプマスタ更新"
Response.CacheControl = "no-cache"

Dim ds, sql, id, koukoku_id

koukoku_id = Request.Form("koukoku_id")

If Not IsEmpty(koukoku_id) Then
	koukoku_id = MakeID(koukoku_id, 2)

	sql = "SELECT max(KT_koukoku_id) FROM T_KOUKOKU_TYPE WHERE KT_koukoku_id<>'99'"
	Set ds = CreateDynaset(sql, ORADYN_READONLY)
	id = NVL(ds(0), 0) + 1

	sql = "SELECT * FROM T_KOUKOKU_TYPE WHERE KT_koukoku_id='" & koukoku_id & "'"
	Set ds = CreateDynaset(sql, ORADYN_DEFAULT)
	If ds.EOF Then
		ds.Addnew
		ds("KT_koukoku_id").Value = MakeID(id, 2)
	Else
		ds.Edit
	End If
	ds("KT_name").Value = Request.Form("koukoku_name")
	ds("KT_order").Value = Request.Form("order")
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
<input type="button" value=" ＯＫ " onclick="location.href='koukoku.asp'">
</center>


<!--#INCLUDE FILE="../inc/footer.asp"-->

</body>
</html>
<%
Else
	Response.Redirect("koukoku.asp")
End If
%>