<!--#INCLUDE FILE="../inc/common.asp"-->
<!--#INCLUDE FILE="../inc/sub.asp"-->
<!--#INCLUDE FILE="../inc/database.asp"-->
<!--#INCLUDE FILE="../inc/login.asp"-->
<%
'******************************************************
' System :はいめーる・net営業用ページ
' Content:企業担当者情報削除処理
'******************************************************

'=== メイン処理 ===
SetHeaderTitle "企業担当者情報削除"

Dim sql, tantousha_id

tantousha_id = Request.QueryString("id")
If IsEmpty(tantousha_id) Then
	SystemError "empty tantousha_id"
End If

sql = "DELETE T_TANTOUSHA WHERE TS_tantousha_id='" & tantousha_id & "'"
ExecuteSQL(sql)
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
<p class="np">担当者情報を削除しました。<br>
		新規で登録される際には、「企業/担当者登録」メニューTOPページで、発信企業名をクリックしてください。</p>
<input type="button" value=" ＯＫ " onclick="location.href='index.asp'">
</center>

<!--#INCLUDE FILE="../inc/footer.asp"-->

</body>
</html>
