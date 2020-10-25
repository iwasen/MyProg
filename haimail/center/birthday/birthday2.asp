<!--#INCLUDE FILE="../inc/common.asp"-->
<!--#INCLUDE FILE="../inc/sub.asp"-->
<!--#INCLUDE FILE="../inc/database.asp"-->
<!--#INCLUDE FILE="../inc/login.asp"-->
<%
'******************************************************
' System :はいめーる・net事務局用ページ
' Content:誕生日メール登録処理
'******************************************************

'=== 次のシーケンス番号取得 ===
Private Function GetNextSeqNo()
	Dim sql, ds

	sql = "SELECT MAX(BM_seq_no) FROM T_BIRTHDAY_MAIL"
	Set ds = CreateDynaset(sql, ORADYN_READONLY)
	If ds.EOF Then
		GetNextSeqNo = 1
	Else
		GetNextSeqNo = CLng(NVL(ds(0).Value, 0)) + 1
	End If
ENd Function

'=== メイン処理 ===
Dim sql, ds
Dim seq_no

seq_no = Request.Form("seq_no")
If IsEmpty(seq_no) Then
	seq_no = GetNextSeqNo()
End If

sql = "SELECT * FROM T_BIRTHDAY_MAIL WHERE BM_seq_no=" & seq_no
Set ds = CreateDynaset(sql, ORADYN_DEFAULT)
If ds.EOF Then
	ds.AddNew
	ds("BM_seq_no").Value = seq_no
	ds("BM_body").Value = Empty
Else
	ds.Edit
End If
ds("BM_priority").Value = Request.Form("priority")
ds("BM_seibetsu").Value = Request.Form("seibetsu")
ds("BM_age_from").Value = Request.Form("age_from")
ds("BM_age_to").Value = Request.Form("age_to")
ds("BM_from_adr").Value = Request.Form("from_adr")
ds("BM_subject").Value = Request.Form("subject")
ds("BM_valid_flag").Value = Request.Form("valid_flag")
ds.Update

ds.Edit
ds("BM_body").Value.Trim(0)
ds("BM_body").Value.Write Request.Form("body")
ds.Update

Response.Redirect "index.asp"
%>
