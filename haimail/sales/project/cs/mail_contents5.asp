<!--#INCLUDE FILE="../../inc/common.asp"-->
<!--#INCLUDE FILE="../../inc/sub.asp"-->
<!--#INCLUDE FILE="../../inc/database.asp"-->
<!--#INCLUDE FILE="../../inc/login.asp"-->
<!--#INCLUDE FILE="../../inc/mail_check.asp"-->
<%
'******************************************************
' System :はいめーる・net事務局・営業共通ページ
' Content:メールコンテンツチェック処理
'******************************************************

'=== 回答記号数表示 ===
Private Sub ShowAnswerKigou(question)
	Dim key, item, str

	For Each key In question
		Set item = question.Item(key)

		str = ""
		If item.answer_sel > 0 Then
			str = str & "定型回答＝" & ToZenkaku(item.answer_sel)
		End If

		If item.answer_fa1 > 0 Then
			If str <> "" Then
				str = str & "<br>"
			End If
			str = str & "定性回答A＝" & ToZenkaku(item.answer_fa1)
		End If

		If item.answer_fa2 > 0 Then
			If str <> "" Then
				str = str & "<br>"
			End If
			str = str & "定性回答B＝" & ToZenkaku(item.answer_fa2)
		End If

		Writeln "<tr>"
		Writeln "<td class='m3' width='20%'>Ｑ" & ToZenkaku(item.question_no) & "</td>"
		Writeln "<td class='n3'>" & str & "</td>"
		Writeln "</tr>"
	Next
End Sub

'=== メイン処理 ===
SetHeaderTitle "メールコンテンツチェック"

Dim job_id, enq_check, imp_check, enq_question, imp_question, mail_err, msg
Dim body, header, impression, footer, mail_format

If Number("0" & Request.Form("get_sa")) > 0 Or Number("0" & Request.Form("get_fa")) > 0 Then
	enq_check = True
Else
	enq_check = False
End If

If Request.Form("get_kansou") = "1" Then
	imp_check = True
Else
	imp_check = False
End If

body = LaxxxrLf(Request.Form("body"))
header = Request.Form("header")
impression =Request.Form("impression")
footer = Request.Form("footer")
mail_format = Request.Form("mail_format")

'ヘッダ付加
If Len(header) > 0 Then
	body = LaxxxrLf(header) & vbCrLf & body
End If

'感想文付加
If Len(impression) > 0 Then
	body = body & vbCrLf & LaxxxrLf(impression)
End If

'フッタ付加
If Len("footer") > 0 Then
	body = body & vbCrLf & LaxxxrLf(footer)
End If

If MailCheck(mail_format, body, enq_check, imp_check, enq_question, imp_question, mail_err) = False Then
	Set Session("mail_err") = mail_err
	Session("body_err") = body
	Response.Redirect "../../common/mail_err.asp"
End If

top_link = ""
%>
<html>
<head>
<meta HTTP-EQUIV="Content-Type" Content="text/html; charset=x-sjis">
<meta HTTP-EQUIV="Pragma" Content="no-cache">
<title><%=title_text%></title>
<link rel="stylesheet" type="text/css" href="../../haimail.css">
</head>
<body>

<!--#INCLUDE FILE="../../inc/header.asp"-->

<center>

<%If enq_question.Count = 0 And imp_question.Count = 0 Then%>
<p>■メールコンテンツにエラーはありませんでした</p>
<%End If%>

<%If enq_question.Count > 0 Then%>
	<table border=0 cellspacing=0 cellpadding=0 width='70%'>
		<tr>
			<td class="m0">■以下のアンケート回答記号を検出しました</td>
		</tr>
		<tr>
			<td>
				<table border=0 cellspacing=2 cellpadding=3 width='100%'>
<%ShowAnswerKigou enq_question%>
				</table>
			</td>
		</tr>
	</table>
<%End If%>

<%If imp_question.Count > 0 Then%>
	<br>
	<table border=0 cellspacing=0 cellpadding=0 width='70%'>
		<tr>
			<td class="m0">■以下の感想文回答記号を検出しました</td>
		</tr>
		<tr>
			<td>
				<table border=0 cellspacing=2 cellpadding=3 width='100%'>
<%ShowAnswerKigou imp_question%>
				</table>
			</td>
		</tr>
	</table>
<%End If%>

<br>
<input type="button" value="　閉じる　" onclick="window.close()">
</center>

<!--#INCLUDE FILE="../../inc/footer.asp"-->

</body>
</html>
