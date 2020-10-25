<!--#INCLUDE FILE="../inc/common.asp"-->
<!--#INCLUDE FILE="../inc/sub.asp"-->
<!--#INCLUDE FILE="../inc/database.asp"-->
<!--#INCLUDE FILE="../inc/login.asp"-->
<%
'******************************************************
' System :はいめーる・net事務局用ページ
' Content:アンケートエラーメール処理
'******************************************************

'=== エラーメールの保存 ===
Private Sub SaveErrorMail(seq_no, body, status)
	Dim sql, ds

	sql = "SELECT * FROM T_ENQUETE_ERR WHERE EE_seq_no=" & seq_no & " AND EE_status='0'"
	Set ds = CreateDynaset(sql, ORADYN_DEFAULT)
	If Not ds.EOF Then
		ds.Edit
		ds("EE_body").Value.Trim(0)
		ds("EE_body").Value.Write body
		ds("EE_status").Value = status
		ds.Update
	End If
End Sub

'=== メイン処理 ===
Dim seq_no, body

seq_no = Request.Form("seq_no")
If IsEmpty(seq_no) Then
	Response.Redirect "enq_error.asp"
End If

body = Request.Form("body")

Select Case Request.Form("process")
Case "1"
	Call SaveErrorMail(seq_no, body, "1")
Case "2"
	Call SaveErrorMail(seq_no, body, "2")
Case "3"
	ExecuteSQL "DELETE T_ENQUETE_ERR WHERE EE_seq_no=" & seq_no
End Select

Response.Redirect "enq_error.asp"
%>
