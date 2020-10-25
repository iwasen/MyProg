<!--#INCLUDE FILE="../inc/common.asp"-->
<!--#INCLUDE FILE="../inc/database.asp"-->
<!--#INCLUDE FILE="../inc/sub.asp"-->
<!--#INCLUDE FILE="../inc/login.asp"-->
<%
'******************************************************
' System :はいめーる・net事務局用ページ
' Content:不達メールアドレス削除処理
'******************************************************

SetHeaderTitle "不達メールアドレス削除"

Dim ds, sql, delete_id, member_id

Set delete_id = Request.Form("delete_id")

If Not IsEmpty(delete_id) Then
	BeginTrans

	For Each member_id In delete_id
		sql = "UPDATE T_MEMBER SET MM_status='9' WHERE MM_member_id='" & member_id & "'"
		ExecuteSQL sql

		sql = "INSERT INTO T_TAIKAI (TK_member_id,TK_taikai_date,TK_taikai_flag) VALUES (" & _
					"'" & member_id & "'," & _
					"SYSDATE," & _
					"'3')"
		ExecuteSQL sql

		sql = "DELETE FROM T_UNDELIVERED WHERE UD_member_id='" & member_id & "'"
		ExecuteSQL sql

		sql = "BEGIN S_DELETE_SEARCH_DATA('" & member_id & "'); END;"
		ExecuteSQL sql
	Next

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
<p>メールアドレスを削除しました。</p>
<input type="button" value="　OK　" onclick="location.href='cl_list.asp'">
</center>
<br>
<!--#INCLUDE FILE="../inc/footer.asp"-->
</body>
</html>
<%
Else
	Response.Redirect("cl_list.asp")
End If
%>