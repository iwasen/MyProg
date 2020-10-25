<!--#INCLUDE FILE="../inc/common.asp"-->
<!--#INCLUDE FILE="../inc/database.asp"-->
<!--#INCLUDE FILE="../inc/sub.asp"-->
<!--#INCLUDE FILE="../inc/login.asp"-->
<%
'******************************************************
' System :はいめーる・net事務局用ページ
' Content:クレーム管理／キーワード更新
'******************************************************

'=== メイン処理 ===
Dim ds, sql, keyword_id

SetHeaderTitle "クレームキーワード更新"
Response.CacheControl = "no-cache"

keyword_id = Request.Form("keyword_id")
If IsEmpty(keyword_id) Then
	SystemError "empty keyword_id"
End If

sql = "SELECT * FROM T_CLAIM_KEYWORD WHERE CK_keyword_id='" & keyword_id & "'"
Set ds = CreateDynaset(sql, ORADYN_DEFAULT)
If ds.EOF Then
	ds.Addnew
	ds("CK_keyword_id").Value = keyword_id
Else
	ds.Edit
End If
ds("CK_keyword").Value = Request.Form("keyword")
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
<input type="button" value=" ＯＫ " onclick="location.href='keyword.asp'">
</center>


<!--#INCLUDE FILE="../inc/footer.asp"-->

</body>
</html>
