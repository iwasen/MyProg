<!--#INCLUDE FILE="../inc/common.asp"-->
<!--#INCLUDE FILE="../inc/database.asp"-->
<!--#INCLUDE FILE="../inc/sub.asp"-->
<!--#INCLUDE FILE="../inc/login.asp"-->
<%
'******************************************************
' System :はいめーる・net事務局用ページ
' Content:不達メールアドレス更新処理
'******************************************************

SetHeaderTitle "不達メールアドレス更新"

Dim ds, sql, member_id, mail_adr, msg, ok

member_id = Request.Form("member_id")
mail_adr = Request.Form("mail_adr")

If Not IsEmpty(member_id) Then
	sql = "SELECT * FROM T_MEMBER WHERE MM_mail_adr='" & SqlStr(mail_adr) & "' AND MM_status<>'9'"
	Set ds = CreateDynaset(sql, ORADYN_READONLY)
	If ds.EOF Then
		BeginTrans

		sql = "UPDATE T_MEMBER SET MM_mail_adr='" & SqlStr(mail_adr) & "' WHERE MM_member_id='" & member_id & "'"
		ExecuteSQL sql

		sql = "DELETE FROM T_UNDELIVERED WHERE UD_member_id='" & member_id & "'"
		ExecuteSQL sql

		CommitTrans

		msg = "メールアドレスを更新しました。"
		ok = "location.href='cl_list.asp'"
	Else
		msg = "入力したメールは既に使われています。"
		ok = "history.back()"
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
<p><%=msg%></p>
<input type="button" value="　OK　" onclick="<%=ok%>">
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