<!--#INCLUDE FILE="../inc/common.asp"-->
<!--#INCLUDE FILE="../inc/sub.asp"-->
<!--#INCLUDE FILE="../inc/database.asp"-->
<!--#INCLUDE FILE="../inc/login.asp"-->
<%
'******************************************************
' System :はいめーる・net事務局用ページ
' Content:エラーメール再送処理
'******************************************************

'=== メイン処理 ===
SetHeaderTitle "ＰＲ登録結果"

Dim mail_id, sql, ds, num

mail_id = Request.QueryString("mail_id")
If IsEmpty(mail_id) Then
	SystemError "empty mail_id"
End If

SetHeaderTitle "送信エラーメール再送"

BeginTrans

sql = "UPDATE T_SENDMAIL_RR SET SR_status='0' where SR_mail_id='" & mail_id & "' AND SR_status='2'"
num = ExecuteSQL(sql)

If num > 0 Then
	sql = "UPDATE T_SEND_MAIL SET SM_status='4' WHERE SM_mail_id='" & mail_id & "' AND SM_status='9'"
	ExecuteSQL sql
End If

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
<p>再送を行うように設定しました。</p>
<input type="button" value=" 戻る " onclick="location.href='index.asp'">
</center>

<!--#INCLUDE FILE="../inc/footer.asp"-->

</body>
</html>
