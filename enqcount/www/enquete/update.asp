<!--#INCLUDE FILE="../inc/common.asp"-->
<!--#INCLUDE FILE="../inc/database.asp"-->
<!--#INCLUDE FILE="../inc/login_check.asp"-->
<%
'******************************************************
' System :アンケート集計サービス
' Content:アンケート出力設定
'******************************************************

'=== 特定カラム取得 ===
Function find_column(enquete_id, name)
	Dim sql, rs

	sql = "SELECT qu_column_no,qu_question_name,qu_question_text FROM t_question WHERE qu_enquete_id=" & enquete_id
	Set rs = CreateRecordset(sql, adOpenForwardOnly, adLockReadOnly)
	Do Until rs.EOF
		If rs("qu_question_name") = name Or rs("qu_question_text") = name Then
			find_column = rs("qu_column_no")
			Exit Do
		End IF

		If InStr(rs("qu_question_name"), name) > 0 Or InStr(rs("qu_question_text"), name) > 0 Then
			find_column = rs("qu_column_no")
		End If
	
		rs.MoveNext
	Loop
End Function

'=== 選択回答処理 ===
Sub answer_sel(enquete_id, col_no)
	Dim sql, rs, ary, answer

	sql = "SELECT an_record_no,an_answer FROM t_answer WHERE an_enquete_id=" & sql_number(enquete_id) & " AND an_column_no=" & sql_number(col_no)
	Set rs = CreateRecordset(sql, adOpenKeyset, adLockReadOnly)
	Do Until rs.EOF
		answer = rs("an_answer").Value
		If answer <> "" Then
			ary = uniq_ary(Split(answer, ","))
			For Each answer In ary
				sql = "INSERT INTO t_answer_sel (as_enquete_id,as_column_no,as_record_no,as_sel_no) VALUES (" & _
							sql_number(enquete_id) & "," & _
							sql_number(col_no) & "," & _
							sql_number(rs("an_record_no")) & "," & _
							sql_char(answer) & ")"
				ExecuteSQL sql
			Next
		End If

		rs.MoveNext
	Loop

	sql = "INSERT INTO t_question_sel (qs_enquete_id,qs_column_no,qs_sel_no,qs_sel_text)" & _
				" SELECT " & enquete_id & "," & col_no & ",as_sel_no,as_sel_no FROM t_answer_sel WHERE as_enquete_id=" & sql_number(enquete_id) & " AND as_column_no=" & sql_number(col_no) & _
				" AND as_sel_no NOT IN (SELECT qs_sel_no FROM t_question_sel WHERE qs_enquete_id=" & sql_number(enquete_id) & " AND qs_column_no=" & sql_number(col_no) & ")" & _
				" GROUP BY as_sel_no ORDER BY as_sel_no"
	ExecuteSQL sql
End Sub

'=== 配列をユニーク化 ===
Function uniq_ary(ary)
	Dim ary2(), item, cnt, i

	cnt = 0
	For Each item In ary
		item = Trim(item)
		If item <> "" Then
			For i = 0 To cnt - 1
				If item = ary2(i) Then
					Exit For
				End If
			Next
			If i = cnt Then
				Redim Preserve ary2(cnt)
				ary2(cnt) = item
				cnt = cnt + 1
			End If
		End If
	Next

	uniq_ary = ary2
End Function

'=== メイン処理 ===
Dim sql, rs, enquete_id, col_no, sheet_no, col_sex, col_age

enquete_id = Request.Form("enquete_id")

'BeginTrans

sql = "DELETE FROM t_answer_sel WHERE as_enquete_id=" & sql_number(enquete_id)
ExecuteSQL sql

sql = "SELECT * FROM t_question WHERE qu_enquete_id=" & sql_number(enquete_id)
Set rs = CreateRecordset(sql, adOpenKeyset, adLockOptimistic)
Do Until rs.EOF
	col_no = rs("qu_column_no")
	rs("qu_question_name") = Request.Form("name" & col_no)
	rs("qu_question_type") = Request.Form("type" & col_no)
	rs("qu_question_text") = Request.Form("text" & col_no)
	rs.Update

	If Request.Form("type" & col_no) = "SA" Or Request.Form("type" & col_no) = "MA" Then
		answer_sel enquete_id, col_no
	End If

	rs.MoveNext
Loop

'CommitTrans

Response.Redirect "template.asp?enquete_id=" & enquete_id
%>