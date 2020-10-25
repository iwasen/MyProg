<!--#INCLUDE FILE="../inc/common.asp"-->
<!--#INCLUDE FILE="../inc/database.asp"-->
<!--#INCLUDE FILE="../inc/sub.asp"-->
<!--#INCLUDE FILE="../inc/login.asp"-->
<%
'******************************************************
' System :はいめーる・net事務局用ページ
' Content:代理店情報削除処理
'******************************************************

SetHeaderTitle "代理店情報削除"

Dim ds, sql, dairiten_id

dairiten_id = Request.QueryString("id")
If IsEmpty(dairiten_id) Then
	SystemError "empty dairiten_id"
End If

BeginTrans
sql = "DELETE T_DAIRITEN WHERE DA_dairiten_id='" & dairiten_id & "'"
ExecuteSQL(sql)
sql = "DELETE T_OPERATOR WHERE OP_dairiten_id='" & dairiten_id & "'"
ExecuteSQL(sql)
CommitTrans
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
<p>削除しました。</p>
<input type="button" value=" ＯＫ " onclick="location.href='eigyou.asp'">
</center>


<!--#INCLUDE FILE="../inc/footer.asp"-->

</body>
</html>
