<!--#INCLUDE FILE="../inc/common.asp"-->
<!--#INCLUDE FILE="../inc/sub.asp"-->
<!--#INCLUDE FILE="../inc/database.asp"-->
<!--#INCLUDE FILE="../inc/login.asp"-->
<%
'******************************************************
' System :はいめーる・net営業用ページ
' Content:企業報削除処理
'******************************************************

'=== メイン処理 ===
SetHeaderTitle "企業情報削除"

Dim sql, company_id

company_id = Request.QueryString("id")
If IsEmpty(company_id) Then
	SystemError "empty company_id"
End If

BeginTrans
sql = "DELETE T_COMPANY WHERE CM_company_id='" & company_id & "'"
ExecuteSQL(sql)
sql = "DELETE T_TANTOUSHA WHERE TS_company_id='" & company_id & "'"
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
<p class="np">発信企業情報を削除しました。<br>
		新規で登録される際には、「企業/担当者登録」メニュー　TOPページで、「新規登録」ボタンをクリックしてください。</p>
<input type="button" value=" ＯＫ " onclick="location.href='index.asp'">
</center>

<!--#INCLUDE FILE="../inc/footer.asp"-->

</body>
</html>
