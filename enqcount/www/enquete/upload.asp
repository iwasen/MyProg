<!--#INCLUDE FILE="../inc/common.asp"-->
<!--#INCLUDE FILE="../inc/database.asp"-->
<!--#INCLUDE FILE="../inc/login_check.asp"-->
<%
'******************************************************
' System :アンケート集計サービス
' Content:アンケートデータアップロード
'******************************************************

'=== CSV行分割 ===
Function split_csv(csv)
	Dim i, l, c, cnt, t

	l = Len(csv)
	cnt = 0
	For i = 1 To l
		c = Mid(csv, i, 1)
		If c = """" Then
			cnt = cnt + 1
		End If

		If Mid(csv, i, 2) = vbCrLf And cnt Mod 2 = 0 Then
			t = t & chr(0)
		Else
			t = t & c
		End If
	Next

	split_csv = Split(t, chr(0))
End Function

'=== CSVカラム取得 ===
Function read_csv(rec)
	Dim c, quote, p, dat, col, ary()

	quote = False
	dat = ""
	col = 0
	p = 1

	Do While True
		c = Mid(rec, p, 1)
		If c = "" Then
			Exit Do
		End If

		If quote Then
			If c = """" Then
				If Mid(rec, p, 2) = """""" Then
					p = p + 1
					dat = dat & c
				Else
					quote = False
				End If
			Else
				dat = dat & c
			End If
		Else
			If c = "," Then
				ReDim Preserve ary(col)
				ary(col) = Trim(dat)
				col = col + 1
				dat = ""
			ElseIf c = """" Then
				If dat = "" Then
					quote = True
				Else
					dat = dat & c
				End If
			Else
				dat = dat & c
			End If
		End If

		p = p + 1
	Loop

	If dat <> "" Then
		ReDim Preserve ary(col)
		ary(col) = Trim(dat)
	End If

	If col > 0 Then
		read_csv = ary
	End If
End Function

'=== 全角スペースのTrim ===
Function TrimZen(s)
	TrimZen = s
	If Len(TrimZen) > 0 Then
		Do While Mid(TrimZen, 1, 1) = "　"
			TrimZen = Mid(TrimZen, 2)
		Loop
	End If
End Function

'=== 質問項目保存 ===
Sub save_question(csv, enquete_id)
	Dim ary, row, col, row_no, col_no, nrow, ncol, cols, i, rs

	ary = split_csv(csv)

	nrow = Ubound(ary)
	If nrow = 0 Then
		Exit Sub
	End If

	sql = "DELETE FROM t_question_sel WHERE qs_enquete_id=" & sql_number(enquete_id)
	ExecuteSQL sql

	For row_no = 0 To nrow
		row = read_csv(ary(row_no))
		If IsArray(row) Then
			ncol = UBound(row)

			If ncol >= 1 Then
				col_no = row(0)

				sql = "SELECT * FROM t_question WHERE qu_enquete_id=" & sql_number(enquete_id) & " AND qu_column_no=" & col_no
				Set rs = CreateRecordset(sql, adOpenKeyset, adLockOptimistic)
				If Not rs.EOF Then
					rs("qu_question_type") = row(1)
					If ncol >= 2 Then
						rs("qu_question_text") = row(2)
					End If
					rs.Update

					For i = 3 To ncol
						col = row(i)

						cols = Split(col, ".", 2)
						If UBound(cols) = 1 Then
							sql = "INSERT INTO t_question_sel (qs_enquete_id,qs_column_no,qs_sel_no,qs_sel_text) VALUES (" & _
									sql_number(enquete_id) & "," & _
									sql_number(col_no) & "," & _
									sql_number(cols(0)) & "," & _
									sql_char(TrimZen(cols(1))) & ")"
							ExecuteSQL sql
						End If
					Next
				End IF
			End If
		End If
	Next
End Sub

'===回答データ保存 ===
Sub save_answer(csv, enquete_id)
	Dim ary, row, col, row_no, col_no, nrow

	ary = Split(csv, vbCrLf)

	nrow = Ubound(ary)
	If nrow = 0 Then
		Exit Sub
	End If

	sql = "DELETE FROM t_question WHERE qu_enquete_id=" & sql_number(enquete_id)
	ExecuteSQL sql

	row = read_csv(ary(0))
	col_no = 1
	For Each col In row
		sql = "INSERT INTO t_question (qu_enquete_id,qu_column_no,qu_question_name) VALUES (" & _
				sql_number(enquete_id) & "," & _
				sql_number(col_no) & "," & _
				sql_char(col) & ")"
		ExecuteSQL sql
		col_no = col_no + 1
	Next

	sql = "DELETE FROM t_answer WHERE an_enquete_id=" & sql_number(enquete_id)
	ExecuteSQL sql

	For row_no = 1 To nrow
		row = read_csv(ary(row_no))
		col_no = 1

		If IsArray(row) Then
			For Each col In row
				sql = "INSERT INTO t_answer (an_enquete_id,an_column_no,an_record_no,an_answer) VALUES (" & _
						sql_number(enquete_id) & "," & _
						sql_number(col_no) & "," & _
						sql_number(row_no) & "," & _
						sql_char(col) & ")"
				ExecuteSQL sql
				col_no = col_no + 1
			Next
		End If
	Next
End Sub

'=== メイン処理 ===
Dim b, basp, sql, enquete_id, csv

b = Request.BinaryRead(Request.TotalBytes)
Set basp = Server.CreateObject("basp21")

'BeginTrans

enquete_id = basp.Form(b, "enquete_id")
If enquete_id = "" Then
	sql = "INSERT INTO t_enquete (en_user_id,en_title,en_memo) VALUES (" & _
			sql_number(Request.Cookies("user_login_id")) & "," & _
			sql_char(basp.Form(b, "title")) & "," & _
			sql_char(basp.Form(b, "memo")) & ")"
	ExecuteSQL sql

	enquete_id = get_last_id()
Else
	sql = "UPDATE t_enquete SET " & _
				"en_title=" & sql_char(basp.Form(b, "title")) & "," & _
				"en_memo=" & sql_char(basp.Form(b, "memo")) & " WHERE en_enquete_id=" & sql_number(enquete_id)
	ExecuteSQL sql
End If

csv = basp.Kconv(basp.FormBinary(b, "answer_file"), 4, 1)
If csv <> "" Then
	save_answer csv, enquete_id
End If

csv = basp.Kconv(basp.FormBinary(b, "question_file"), 4, 1)
If csv <> "" Then
	save_question csv, enquete_id
End If

'CommitTrans

Response.Redirect "edit.asp?enquete_id=" & enquete_id
%>
