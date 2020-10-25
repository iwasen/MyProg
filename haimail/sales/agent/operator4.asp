<!--#INCLUDE FILE="../inc/common.asp"-->
<!--#INCLUDE FILE="../inc/sub.asp"-->
<!--#INCLUDE FILE="../inc/database.asp"-->
<!--#INCLUDE FILE="../inc/login.asp"-->
<%
'******************************************************
' System :はいめーる・net営業用ページ
' Content:代理店担当者情報削除処理
'******************************************************

'=== メイン処理 ===
SetHeaderTitle "代理店担当者情報削除"

Dim sql, operator_id

operator_id = Request.QueryString("id")
If IsEmpty(operator_id) Then
	SystemError "empty operator_id"
End If

sql = "DELETE T_OPERATOR WHERE OP_operator_id='" & operator_id & "'"
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
<p class="np">代理店担当者情報を削除しました。<br>新規でご担当者様を登録される場合には、「代理店/担当者登録」メニュー　TOPページで、代理店名をクリックしてください。</p>
<input type="button" value=" ＯＫ " onclick="location.href='index.asp'">
</center>

<!--#INCLUDE FILE="../inc/footer.asp"-->

</body>
</html>
