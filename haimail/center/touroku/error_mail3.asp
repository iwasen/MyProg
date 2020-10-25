<!--#INCLUDE FILE="../inc/common.asp"-->
<!--#INCLUDE FILE="../inc/sub.asp"-->
<!--#INCLUDE FILE="../inc/database.asp"-->
<!--#INCLUDE FILE="../inc/login.asp"-->
<%
'******************************************************
' System :はいめーる・net事務局用ページ
' Content:登録エラーメール処理
'******************************************************

'=== エラーメールの保存 ===
Private Sub SaveErrorMail(seq_no, body, status)
	Dim sql, ds

	sql = "SELECT * FROM T_TOUROKU_ERR WHERE TE_seq_no=" & seq_no & " AND TE_status='0'"
	Set ds = CreateDynaset(sql, ORADYN_DEFAULT)
	If Not ds.EOF Then
		ds.Edit
		ds("TE_reply").Value.Trim(0)
		ds("TE_reply").Value.Write body
		ds("TE_status").Value = status
		ds.Update
	End If
End Sub

'=== メイン処理 ===
Dim seq_no, body

seq_no = Request.Form("seq_no")
If IsEmpty(seq_no) Then
	Response.Redirect "error_mail.asp"
End If

body = Request.Form("body")

Select Case Request.Form("process")
Case "1"
	Call SaveErrorMail(seq_no, body, "1")
Case "2"
	Call SaveErrorMail(seq_no, body, "2")
Case "3"
	ExecuteSQL "DELETE T_TOUROKU_ERR WHERE TE_seq_no=" & seq_no
End Select

Response.Redirect "error_mail.asp"
%>
