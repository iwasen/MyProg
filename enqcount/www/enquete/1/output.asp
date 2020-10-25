<!--#INCLUDE FILE="../../inc/common.asp"-->
<!--#INCLUDE FILE="../../inc/database.asp"-->
<!--#INCLUDE FILE="../../inc/excel.asp"-->
<%
Const SEL_MIN = 2
Const SEL_MAX = 30

Dim enquete_id, tempfile
Dim col_sex, col_age, col_mikikon, col_shokugyou, col_kyojuu, col_output, col_side1, col_side2, col_fa

'=== プロファイルセット ===
Sub SetProfile(objWorkSheet, col_no, row, col, chart_no)
	Dim rs, sub1, sql, cnt, temp, objChart

	With objWorkSheet

	sub1 = "SELECT as_sel_no,COUNT(*) AS cnt FROM t_answer_sel WHERE as_enquete_id=" & enquete_id & " AND as_column_no=" & col_no & " GROUP BY as_sel_no"
	sql = "SELECT qs_sel_text,cnt FROM t_question_sel LEFT JOIN (" & sub1 & ") AS sub1 ON qs_sel_no=as_sel_no WHERE qs_enquete_id=" & enquete_id & " AND qs_column_no=" & col_no & " ORDER BY qs_sel_no"
	Set rs = CreateRecordset(sql, adOpenForwardOnly, adLockReadOnly)

	cnt = 1
	Do Until rs.EOF
		temp = rs("qs_sel_text")

		If cnt > 1 Then
			.Range(.Cells(row + cnt, col), .Cells(row + cnt, col + 2)).Insert
			If .Cells(row + cnt + 3, col) = "" Then
				.Range(.Cells(row + cnt - 1, col), .Cells(row + cnt, col + 2)).FillDown
				.Range(.Cells(row + cnt + 2, col), .Cells(row + cnt + 2, col + 2)).Delete
			Else
				.Range(.Cells(row + cnt - 1, col), .Cells(row + cnt, col + 8)).FillDown
			End If
		End If

		.Cells(row + cnt, col) = ToHankaku(temp)
		.Cells(row + cnt, col + 1) = rs("cnt")
		cnt = cnt + 1

		rs.MoveNext
	Loop

	If cnt > 1 Then
		.Cells(row + cnt, col + 1) = "=SUM(" & .Cells(row + 1, col + 1).Address(False, False) & ":" & .Cells(row + cnt - 1, col + 1).Address(False, False) & ")"
		Set objChart = .ChartObjects(chart_no).Chart
		objChart.Axes(xlCategory).CategoryNames = .Range(.Cells(row + 1, col), .Cells(row + cnt - 1, col))
		objChart.SeriesCollection(1).Values = .Range(.Cells(row + 1, col + 2), .Cells(row + cnt - 1, col + 2))
	End If

	End With
End Sub

'=== 数値取得 ===
Function GetNumber(str)
	Dim length, i, j

	length = Len(str)
	j = 0
	For i = 1 To length
		If Not IsNumeric(Left(str, i)) Then
			Exit For
		End If
		j = i
	Next

	If j > 0 Then
		GetNumber = CLng(Left(str, j))
	End If
End Function

'=== 表紙 ===
Sub Cover(objWorkBook, objWorkSheets)
	Dim sql, rs, objWorkSheet, objShape, temp

	Set objWorkSheet = objWorkSheets("表紙")
	Set objShape = objWorkSheet.Shapes("表紙_タイトル")
	sql = "SELECT en_title FROM t_enquete WHERE en_enquete_id=" & enquete_id
	Set rs = CreateRecordset(sql, adOpenForwardOnly, adLockReadOnly)
	objShape.TextFrame.Characters.text = Replace(objShape.TextFrame.Characters.text, "TITLE", rs("en_title"))
	temp = objWorkBook.Names("表紙_日付").RefersToRange.Value
	temp = Replace(temp, "YYYY", Year(Date))
	temp = Replace(temp, "M", Month(Date))
	objWorkBook.Names("表紙_日付").RefersToRange.Value = temp
End Sub

'=== 調査概要 ===
Sub ChousaGaiyou(objWorkBook, objWorkSheets)
	Dim sql, sub1, sub2, rs, rs2, objWorkSheet, s, temp, row, col, sex_m, sex_f, age_max, age_min, mikon, kikon, cnt_cat, cnt_fa, n, n_row, n_col, ary, i, j

	Set objWorkSheet = objWorkSheets("調査概要")

	With objWorkSheet

	'性別
	If col_sex > 0 Then
		sex_m = ""
		sex_f = ""
		sql = "SELECT qs_sel_text FROM t_question_sel WHERE qs_enquete_id=" & enquete_id & " AND qs_column_no=" & col_sex
		Set rs = CreateRecordset(sql, adOpenForwardOnly, adLockReadOnly)
		Do Until rs.EOF
			temp = rs(0)
			If InStr(temp, "男") > 0 Then
				sex_m = "男"
			ElseIf InStr(temp, "女") > 0 Then
				sex_f = "女"
			End If
			rs.MoveNext
		Loop
		s = sex_m & sex_f
	Else
		s = "不問"
	End If
	GetPosition objWorkBook, "調査概要_性別", row, col
	.Cells(row, col) = "：　" & s

	'年代
	If col_age > 0 Then
		age_max = 0
		age_min = 99
		sql = "SELECT qs_sel_text FROM t_question_sel WHERE qs_enquete_id=" & enquete_id & " AND qs_column_no=" & col_age
		Set rs = CreateRecordset(sql, adOpenForwardOnly, adLockReadOnly)
		Do Until rs.EOF
			temp = GetNumber(ToHankaku(rs(0)))
			If temp > age_max Then
				age_max = temp
			End If
			If temp < age_min Then
				age_min = temp
			End If
			rs.MoveNext
		Loop
		If age_max > 0 Then
			s = age_min & "歳～" & ((age_max \ 10) * 10 + 9) & "歳"
		Else
			s = ""
		End If
	Else
		s = "不問"
	End If
	GetPosition objWorkBook, "調査概要_年代", row, col
	.Cells(row, col) = "：　" & s

	'未既婚
	If col_mikikon > 0 Then
		mikon = ""
		kikon = ""
		sql = "SELECT qs_sel_text FROM t_question_sel WHERE qs_enquete_id=" & enquete_id & " AND qs_column_no=" & col_mikikon
		Set rs = CreateRecordset(sql, adOpenForwardOnly, adLockReadOnly)
		Do Until rs.EOF
			temp = rs(0)
			If InStr(temp, "未") > 0 Then
				mikon = "未"
			ElseIf InStr(temp, "既") > 0 Then
				kikon = "既"
			End If
			rs.MoveNext
		Loop
		s = mikon & kikon & "婚"
	Else
		s = "不問"
	End If
	GetPosition objWorkBook, "調査概要_未既婚", row, col
	.Cells(row, col) = "：　" & s

	'居住地域
	If col_kyojuu > 0 Then
		s = ""
		sql = "SELECT qs_sel_text FROM t_question_sel WHERE qs_enquete_id=" & enquete_id & " AND qs_column_no=" & col_kyojuu & " ORDER BY qs_sel_no"
		Set rs = CreateRecordset(sql, adOpenForwardOnly, adLockReadOnly)
		Do Until rs.EOF
			If s <> "" Then
				s = s & ","
			End If
			s = s & rs(0)
			rs.MoveNext
		Loop
	Else
		s = "不問"
	End If
	GetPosition objWorkBook, "調査概要_居住地域", row, col
	.Cells(row, col) = "：　" & s

	'職業
	If col_shokugyou > 0 Then
		s = ""
		sql = "SELECT qs_sel_text FROM t_question_sel WHERE qs_enquete_id=" & enquete_id & " AND qs_column_no=" & col_shokugyou & " ORDER BY qs_sel_no"
		Set rs = CreateRecordset(sql, adOpenForwardOnly, adLockReadOnly)
		Do Until rs.EOF
			If s <> "" Then
				s = s & ","
			End If
			s = s & rs(0)
			rs.MoveNext
		Loop
	Else
		s = "不問"
	End If
	GetPosition objWorkBook, "調査概要_職業", row, col
	.Cells(row, col) = "：　" & s

	'質問数
	cnt_cat = 0
	cnt_fa = 0
	If IsArray(col_output) Then
		sql = "SELECT qu_question_type FROM t_question WHERE qu_enquete_id=" & enquete_id & " AND qu_column_no IN (" & Join(col_output, ",") & ")"
		Set rs = CreateRecordset(sql, adOpenForwardOnly, adLockReadOnly)
		Do Until rs.EOF
			Select Case rs("qu_question_type")
			Case "SA", "MA"
				cnt_cat = cnt_cat + 1
			Case "FA"
				cnt_fa = cnt_fa + 1
			End Select
			rs.MoveNext
		Loop
	End If
	GetPosition objWorkBook, "調査概要_質問数", row, col
	temp = .Cells(row, col)
	temp = Replace(temp, "CA", cnt_cat)
	temp = Replace(temp, "FA", cnt_fa)
	.Cells(row, col) = temp

	'有効回答数
	If col_sex > 0 And col_age > 0 Then
		GetPosition objWorkBook, "調査概要_有効回答数", row, col

		sql = "SELECT qs_sel_text FROM t_question_sel WHERE qs_enquete_id=" & enquete_id & " AND qs_column_no=" & col_sex & " ORDER BY qs_sel_no"
		Set rs = CreateRecordset(sql, adOpenKeyset, adLockReadOnly)
		n_col = rs.RecordCount

		sql = "SELECT qs_sel_text FROM t_question_sel WHERE qs_enquete_id=" & enquete_id & " AND qs_column_no=" & col_age & " ORDER BY qs_sel_no"
		Set rs2 = CreateRecordset(sql, adOpenKeyset, adLockReadOnly)
		n_row = rs2.RecordCount

		If n_row > 0 And n_col > 0 Then
			ReDim ary(n_row, n_col)

			n = 1
			Do Until rs.EOF
				ary(0, n) = rs(0)
				n = n + 1
				rs.MoveNext
			Loop

			n = 1
			Do Until rs2.EOF
				ary(n, 0) = ToHankaku(rs2(0))
				n = n + 1
				rs2.MoveNext
			Loop

			sub1 = "SELECT q1.qs_sel_no AS q1_sel_no,q2.qs_sel_no AS q2_sel_no" & _
				   " FROM (SELECT qs_sel_no FROM t_question_sel WHERE qs_enquete_id=" & enquete_id & " AND qs_column_no=" & col_sex & ") AS q1" & _
				   " CROSS JOIN (SELECT qs_sel_no FROM t_question_sel WHERE qs_enquete_id=" & enquete_id & " AND qs_column_no=" & col_age & ") AS q2"
	
			sub2 = "SELECT a1.as_sel_no AS a1_sel_no,a2.as_sel_no AS a2_sel_no,COUNT(*) AS cnt" & _
				   " FROM (SELECT as_record_no,as_sel_no FROM t_answer_sel WHERE as_enquete_id=" & enquete_id & " AND as_column_no=" & col_sex & ") AS a1" & _
				   " JOIN (SELECT as_record_no,as_sel_no FROM t_answer_sel WHERE as_enquete_id=" & enquete_id & " AND as_column_no=" & col_age & ") AS a2 ON a1.as_record_no=a2.as_record_no" & _
				   " GROUP BY a1.as_sel_no,a2.as_sel_no"
		
			sql = "SELECT COALESCE(SUM(cnt),0) FROM (" & sub1 & ") AS sub1 LEFT JOIN (" & sub2 & ") AS sub2 ON q1_sel_no=a1_sel_no AND q2_sel_no=a2_sel_no" & _
				  " GROUP BY q1_sel_no,q2_sel_no" & _
				  " ORDER BY q1_sel_no,q2_sel_no"
			Set rs = CreateRecordset(sql, adOpenForwardOnly, adLockReadOnly)
			For i = 1 To n_col
				For j = 1 To n_row
					ary(j, i) = rs(0)
					rs.MoveNext
				Next
			Next

			For i = 5 To n_row
				.Rows(row + 2).Insert
			Next

			For i = 3 To n_row Step -1
				.Rows(row + 1).Delete
			Next

			.Range(.Cells(row, col), .Cells(row + n_row, col + n_col)) = ary
		End If
	End If

	End With
End Sub

'=== プロファイル ===
Sub Profile(objWorkBook, objWorkSheets)
	Dim objWorkSheet, row, col

	'プロファイルシート
	Set objWorkSheet = objWorkSheets("PF")

	'プロファイル（性別）
	If col_sex > 0 Then
		GetPosition objWorkBook, "PF_性別", row, col
		SetProfile objWorkSheet, col_sex, row, col, 3
	End If

	'プロファイル（年代）
	If col_age > 0 Then
		GetPosition objWorkBook, "PF_年代", row, col
		SetProfile objWorkSheet, col_age, row, col, 4
	End If

	'プロファイル（未既婚）
	If col_mikikon > 0 Then
		GetPosition objWorkBook, "PF_未既婚", row, col
		SetProfile objWorkSheet, col_mikikon, row, col, 5
	End If

	'プロファイル（職業）
	If col_shokugyou > 0 Then
		GetPosition objWorkBook, "PF_職業", row, col
		SetProfile objWorkSheet, col_shokugyou, row, col, 1
	End If

	'プロファイル（居住地域）
	If col_kyojuu > 0 Then
		GetPosition objWorkBook, "PF_居住地域", row, col
		SetProfile objWorkSheet, col_kyojuu, row, col, 2
	End If
End Sub

'=== 選択回答処理 ===
Sub SelectAnswer(objWorkBook, objWorkSheets, enquete_id, col_no)
	Dim sql, sub1, sub2, rs, question_name, question_text, question_type, row, col, qno, qmsg, cnt, temp, row_type, i, j, k, cnt1, cnt2, cnt3, cnt4, h, x, y, nx, ny
	Dim objWorkSheet, objRange

	sql = "SELECT COUNT(DISTINCT(COALESCE(qs_sel_text,as_sel_no))) FROM t_question_sel FULL JOIN t_answer_sel ON as_enquete_id=qs_enquete_id AND as_column_no=qs_column_no WHERE qs_enquete_id=" & enquete_id & " AND qs_column_no=" & col_no
	Set rs = CreateRecordset(sql, adOpenForwardOnly, adLockReadOnly)
	cnt3 = rs(0)
	cnt4 = cnt3

	If cnt3 < SEL_MIN Then
		Exit Sub
	End If

	If cnt3 > SEL_MAX Then
		cnt3 = SEL_MAX
	End If

	Set objWorkSheet = objWorkSheets(cnt3 & "コ")

	objWorkSheet.Copy objWorkSheets("FA")
	Set objWorkSheet = objWorkSheets("FA").Previous

	With objWorkSheet

	sql = "SELECT qu_question_type,qu_question_name,qu_question_text FROM t_question WHERE qu_enquete_id=" & enquete_id & " AND qu_column_no=" & col_no
	Set rs = CreateRecordset(sql, adOpenForwardOnly, adLockReadOnly)
	question_name = rs("qu_question_name")
	question_text = rs("qu_question_text")
	question_type = rs("qu_question_type")
	.name = question_name

	GetQuesionNo question_text, qno, qmsg

	GetPosition objWorkBook, "集計" & cnt3 & "_タイトル", row, col
	.Cells(row, col) = qno & question_name

	GetPosition objWorkBook, "集計" & cnt3 & "_種別", row, col
	.Cells(row, col) = "≪" & question_type & "≫"
	row_type = row

	GetPosition objWorkBook, "集計" & cnt3 & "_結果", row, col

	ClearTable objWorkSheet, row, col

	' 選択肢が30より多い場合は拡張する
	If cnt4 > cnt3 Then
		For i = 1 To cnt4 - cnt3
			.Range(.Cells(row - 1, col + 6), .Cells(row + 13, col + 6)).Insert
		Next
	End If

	sql = "SELECT qs_sel_text FROM t_question_sel WHERE qs_enquete_id=" & enquete_id & " AND qs_column_no=" & col_no & " ORDER BY CASE WHEN ISNUMERIC(qs_sel_no)=1 THEN RIGHT('000'+qs_sel_no,3) ELSE qs_sel_no END"
	Set rs = CreateRecordset(sql, adOpenForwardOnly, adLockReadOnly)
	cnt = 3
	Do Until rs.EOF
		.Cells(row + 1, col + cnt) = rs("qs_sel_text")
		cnt = cnt + 1
		rs.MoveNext
	Loop

	sql = "SELECT qs_sel_text FROM t_question_sel WHERE qs_enquete_id=" & enquete_id & " AND qs_column_no=" & col_side2 & " ORDER BY qs_sel_no"
	Set rs = CreateRecordset(sql, adOpenKeyset, adLockReadOnly)
	cnt2 = rs.RecordCount
	If cnt2 > 4 Then
		Set objRange = .Range(.Rows(row + 6), .Rows(row + 7))
		For i = 5 To cnt2
			.Rows(row + 8).Insert
			.Rows(row + 8).Insert
			objRange.Copy .Range(.Rows(row + 8), .Rows(row + 9))
		Next
	End If

	If cnt2 < 4 Then
		.Range(.Rows(row + 6), .Rows(row + (4 - cnt2) * 2 + 5)).Delete
	End If

	cnt = 0
	Do Until rs.EOF
		.Cells(row + cnt * 2 + 6, col + 1) = rs("qs_sel_text")
		cnt = cnt + 1
		rs.MoveNext
	Loop

	.Range(.Cells(row + 4, col), .Cells(row + cnt2 * 2 + 5, col)).MergeCells = True

	sql = "SELECT qs_sel_text FROM t_question_sel WHERE qs_enquete_id=" & enquete_id & " AND qs_column_no=" & col_side1 & " ORDER BY qs_sel_no"
	Set rs = CreateRecordset(sql, adOpenKeyset, adLockReadOnly)
	cnt1 = rs.RecordCount
	h = cnt2 * 2 + 2
	If cnt1 > 1 Then
		Set objRange = .Range(.Rows(row + 4), .Rows(row + h + 3))
		For i = 2 To cnt1
			For j = 1 To h
				.Rows(row + h + 4).Insert
			Next
			objRange.Copy .Range(.Rows(row + h + 4), .Rows(row + h * 2 + 3))
		Next
	End If

	cnt = 0
	Do Until rs.EOF
		.Cells(row + cnt * h + 4, col) = rs("qs_sel_text")
		cnt = cnt + 1
		rs.MoveNext
	Loop

	sub1 = "SELECT q1.qs_sel_no AS q1_sel_no,q2.qs_sel_no AS q2_sel_no,q3.qs_sel_no AS q3_sel_no" & _
		   " FROM (SELECT qs_sel_no FROM t_question_sel WHERE qs_enquete_id=" & enquete_id & " AND qs_column_no=" & col_side1 & ") AS q1" & _
		   " CROSS JOIN (SELECT qs_sel_no FROM t_question_sel WHERE qs_enquete_id=" & enquete_id & " AND qs_column_no=" & col_side2 & ") AS q2" & _
		   " CROSS JOIN (SELECT qs_sel_no FROM t_question_sel WHERE qs_enquete_id=" & enquete_id & " AND qs_column_no=" & col_no & ") AS q3"

	sub2 = "SELECT a1.as_sel_no AS a1_sel_no,a2.as_sel_no AS a2_sel_no,a3.as_sel_no AS a3_sel_no,COUNT(*) AS cnt" & _
		   " FROM (SELECT as_record_no,as_sel_no FROM t_answer_sel WHERE as_enquete_id=" & enquete_id & " AND as_column_no=" & col_side1 & ") AS a1" & _
		   " JOIN (SELECT as_record_no,as_sel_no FROM t_answer_sel WHERE as_enquete_id=" & enquete_id & " AND as_column_no=" & col_side2 & ") AS a2 ON a1.as_record_no=a2.as_record_no" & _
		   " JOIN (SELECT as_record_no,as_sel_no FROM t_answer_sel WHERE as_enquete_id=" & enquete_id & " AND as_column_no=" & col_no & ") AS a3 ON a1.as_record_no=a3.as_record_no" & _
		   " GROUP BY a1.as_sel_no,a2.as_sel_no,a3.as_sel_no"

	sql = "SELECT COALESCE(SUM(cnt),0) FROM (" & sub1 & ") AS sub1 LEFT JOIN (" & sub2 & ") AS sub2 ON q1_sel_no=a1_sel_no AND q2_sel_no=a2_sel_no AND q3_sel_no=a3_sel_no" & _
		  " GROUP BY q1_sel_no,q2_sel_no,q3_sel_no" & _
		  " ORDER BY q1_sel_no,q2_sel_no,CASE WHEN ISNUMERIC(q3_sel_no)=1 THEN RIGHT('000'+q3_sel_no,3) ELSE q3_sel_no END"
	Set rs = CreateRecordset(sql, adOpenForwardOnly, adLockReadOnly)

	nx = (cnt4 + 1) * 2
	ny = (cnt1 * (cnt2 + 1) + 1) * 2
	ReDim ary(ny, nx)

	For k = -1 To cnt4 - 1
		x = k + 1
		temp = ""
		For i = 0 To cnt1 - 1
			y = ((cnt2 + 1) * i + 1) * 2
			If i > 0 Then
				temp = temp & ","
			End If
			temp = temp & .Cells(row + y + 2, col + k + 3).Address(False, False)
		Next
		ary(0, x) = "=SUM(" & temp & ")"
		ary(1, x) = "=" & .Cells(row + 2, col + x + 2).Address(False, False) & "/MAX(" & .Cells(row + 2, col + 2).Address(False, False) & ",1)*100"
	Next

	For i = 0 To cnt1 - 1
		For k = -1 To cnt4 - 1
			x = k + 1
			temp = ""
			For j = 0 To cnt2 - 1
				y = ((cnt2 + 1) * i + j + 2) * 2
				If j > 0 Then
					temp = temp & ","
				End If
				temp = temp & .Cells(row + y + 2, col + k + 3).Address(False, False)
			Next
			y = ((cnt2 + 1) * i + 1) * 2
			ary(y, x) = "=SUM(" & temp & ")"
			ary(y + 1, x) = "=" & .Cells(row + y + 2, col + x + 2).Address(False, False) & "/MAX(" & .Cells(row + y + 2, col + 2).Address(False, False) & ",1)*100"
		Next

		For j = 0 To cnt2 - 1
			y = ((cnt2 + 1) * i + j + 2) * 2
			ary(y, 0) = "=SUM(" & .Cells(row + y + 2, col + 3).Address(False, False) & ":" & .Cells(row + y + 2, col + cnt4 + 2).Address(False, False) & ")"
			ary(y + 1, 0) = "=" & .Cells(row + y + 2, col + 2).Address(False, False) & "/MAX(" & .Cells(row + y + 2, col + 2).Address(False, False) & ",1)*100"
			For k = 0 To cnt4 - 1
				x = k + 1
				ary(y, x) = rs(0)
				ary(y + 1, x) = "=" & .Cells(row + y + 2, col + x + 2).Address(False, False) & "/MAX(" & .Cells(row + y + 2, col + 2).Address(False, False) & ",1)*100"
				rs.MoveNext
			Next
		Next
	Next
	.Range(.Cells(row + 2, col + 2), .Cells(row + ny + 2, col + nx + 2)) = ary

	GetPosition objWorkBook, "集計" & cnt3 & "_質問", row, col
	.Cells(row, col) = qno
	SetQuestionText objWorkSheet, qmsg, row, col + 1, row_type - 1

	End With
End Sub

'=== 自由回答処理 ===
Sub FreeAnswer(objWorkBook, objWorkSheets, enquete_id, col_no)
	Dim sql, rs, question_name, question_text, qno, qmsg, row, col, cnt, ncol
	Dim select_sex, join_sex, order_sex, select_age, join_age, order_age, select_alpha, join_alpha
	Dim objWorkSheet

	Set objWorkSheet = objWorkSheets("FA")
	objWorkSheet.Copy objWorkSheet
	Set objWorkSheet = objWorkSheet.Previous

	With objWorkSheet

	sql = "SELECT qu_question_name,qu_question_text FROM t_question WHERE qu_enquete_id=" & enquete_id & " AND qu_column_no=" & col_no
	Set rs = CreateRecordset(sql, adOpenForwardOnly, adLockReadOnly)
	question_name = rs("qu_question_name")
	question_text = rs("qu_question_text")
	.name = question_name

	GetQuesionNo question_text, qno, qmsg

	GetPosition objWorkBook, "FA_タイトル", row, col
	.Cells(row, col) = qno & question_name

	GetPosition objWorkBook, "FA_結果", row, col

	If col_sex > 0 Then
		select_sex = ",a2.qs_sel_text AS a2_sel_text"
		join_sex = " JOIN (SELECT as_record_no,as_sel_no,qs_sel_text FROM t_answer_sel JOIN t_question_sel ON qs_enquete_id=as_enquete_id AND qs_column_no=as_column_no AND qs_sel_no=as_sel_no WHERE as_enquete_id=" & enquete_id & " AND as_column_no=" & col_sex & ") AS a2 ON a2.as_record_no=a1.an_record_no"
		order_sex = "a2.as_sel_no,"
	Else
		select_sex = ",'' AS a2_sel_text"
	End If

	If col_age > 0 Then
		select_age = ",a3.qs_sel_text AS a3_sel_text"
		join_age = " JOIN (SELECT as_record_no,as_sel_no,qs_sel_text FROM t_answer_sel JOIN t_question_sel ON qs_enquete_id=as_enquete_id AND qs_column_no=as_column_no AND qs_sel_no=as_sel_no WHERE as_enquete_id=" & enquete_id & " AND as_column_no=" & col_age & ") AS a3 ON a3.as_record_no=a1.an_record_no"
		order_sex = "a3.as_sel_no,"
	Else
		select_age = ",'' AS a3_sel_text"
	End If

	If col_fa > 0 Then
		sql = "SELECT qu_question_text FROM t_question WHERE qu_enquete_id=" & enquete_id & " AND qu_column_no=" & col_fa
		Set rs = CreateRecordset(sql, adOpenForwardOnly, adLockReadOnly)
		.Cells(row, col + 4) = rs(0)
		select_alpha = ",a4.qs_sel_text AS a4_sel_text"
		join_alpha = " JOIN (SELECT as_record_no,as_sel_no,qs_sel_text FROM t_answer_sel JOIN t_question_sel ON qs_enquete_id=as_enquete_id AND qs_column_no=as_column_no AND qs_sel_no=as_sel_no WHERE as_enquete_id=" & enquete_id & " AND as_column_no=" & col_fa & ") AS a4 ON a4.as_record_no=a1.an_record_no"
		ncol = 6
	Else
		.Columns(col + 5).ColumnWidth = .Columns(col + 4).ColumnWidth + .Columns(col + 5).ColumnWidth
		.Columns(col + 4).Delete
		ncol = 5
	End If

	sql = "SELECT an_record_no,an_answer" & select_sex & select_age & select_alpha & _
		  " FROM (SELECT an_record_no,an_answer FROM t_answer WHERE an_enquete_id=" & enquete_id & " AND an_column_no=" & col_no & ") AS a1" & _
		  join_sex & join_age & join_alpha & _
		  " WHERE an_answer IS NOT NULL" & _
		  " ORDER BY " & order_sex & order_age & "a1.an_record_no"
	Set rs = CreateRecordset(sql, adOpenKeyset, adLockReadOnly)
	ReDim ary(rs.RecordCount, ncol - 1)
	cnt = 0
	Do Until rs.EOF
		ary(cnt, 0) = cnt + 1
		ary(cnt, 1) = rs("an_record_no")
		ary(cnt, 2) = rs("a2_sel_text")
		ary(cnt, 3) = rs("a3_sel_text")
		If col_fa > 0 Then
			ary(cnt, 4) = rs("a4_sel_text")
			ary(cnt, 5) = rs("an_answer")
		Else
			ary(cnt, 4) = rs("an_answer")
		End If
		cnt = cnt + 1
		rs.MoveNext
	Loop

	SetTableData objWorkSheet, ary, row + 1, col, rs.RecordCount, ncol, True

	GetPosition objWorkBook, "FA_質問", row, col
	.Cells(row, col) = qno
	SetQuestionText objWorkSheet, qmsg, row, col + 1, row + 1

	End With
End Sub

'=== 質問番号と質問文の分離 ===
Sub GetQuesionNo(text, qno, qmsg)
	Dim p

	If ToHankaku(Left(text, 1)) = "Q" Then
		p = InStr(text, "．")
		If p = 0 Then
			p = InStr(text, ".")
		End If
	End If

	If p > 0 Then
		qno = Left(text, p)
		qmsg = Mid(text, p + 1)
	Else
		qno = ""
		qmsg = text
	End If
End Sub

'=== 設問文セット ===
Sub SetQuestionText(objWorkSheet, text, row, col, maxrow)
	Dim ary, s, cnt

	With objWorkSheet

	ary = Split(text, vbCrLf)
	objWorkSheet.Cells(row, col) = ""
	cnt = 0
	For Each s In ary
		If row + cnt >= maxrow Then
			.Rows(row + cnt).Insert
		End If

		.Range(.Cells(row + cnt, col), .Cells(row + cnt + 1, col)).FillDown
		.Cells(row + cnt, col) = s
		.Cells(row + cnt, col).Font.Bold = True

		cnt = cnt + 1
	Next

	End With
End Sub

'=== 表をクリア ===
Sub ClearTable(objWorkSheet, row, col)
	Dim i

	With objWorkSheet

	.Range(.Rows(row + 14), .Rows(row + 23)).Delete
	.Cells(row + 4, col).UnMerge
	'.Range(.Rows(row + 8), .Rows(row + 11)).Delete

	End With
End Sub

'=== エクセルにデータ書き込み ===
Private Sub WriteExcel(filename)
	Dim objExcelApp, objWorkBook, objWorkSheets, objWorkSheet
	Dim path, col_no, cnt
	Dim sql, rs

	path = Server.MapPath("../../template/" & filename)

	Set objExcelApp = Server.CreateObject("Excel.Application")
	objExcelApp.DisplayAlerts = False
	objExcelApp.Visible = False
	objExcelApp.Interactive = False
	objExcelApp.Workbooks.Open path

	Set objWorkBook = objExcelApp.ActiveWorkbook
	Set objWorkSheets = objWorkBook.Worksheets

	On Error Resume Next

	'表紙
	Cover objWorkBook, objWorkSheets

	'調査概要
	ChousaGaiyou objWorkBook, objWorkSheets

	'プロファイル
	Profile objWorkBook, objWorkSheets

	'集計結果
	For Each col_no In col_output
		sql = "SELECT qu_question_type FROM t_question WHERE qu_enquete_id=" & sql_number(enquete_id) & " AND qu_column_no=" & sql_number(col_no)
		Set rs = CreateRecordset(sql, adOpenForwardOnly, adLockReadOnly)
		Select Case rs("qu_question_type")
		Case "SA", "MA"
			SelectAnswer objWorkBook, objWorkSheets, enquete_id, col_no
		Case "FA"
			FreeAnswer objWorkBook, objWorkSheets, enquete_id, col_no
		End Select
	Next

	For cnt = SEL_MIN To SEL_MAX
		Set objWorkSheet = objWorkSheets(cnt & "コ")
		objWorkSheet.Delete
	Next

	Set objWorkSheet = objWorkSheets("FA")
	objWorkSheet.Delete

	objWorkSheets(1).Activate
	objWorkBook.SaveAs tempfile
	objExcelApp.Quit
End Sub

'=== メイン処理 ===
Dim sql, rs, template_id, filename

enquete_id = Request.Form("enquete_id")
template_id = Request.Form("template_id")
col_sex = Request.Form("sex")
col_age = Request.Form("age")
col_mikikon = Request.Form("mikikon")
col_shokugyou = Request.Form("shokugyou")
col_kyojuu = Request.Form("kyojuu")
col_fa = Request.Form("fa")
col_side1 = Request.Form("side1")
col_side2 = Request.Form("side2")
Set col_output = Request.Form("output")

tempfile = GetTempFile()

sql = "SELECT te_filename FROM t_template WHERE te_template_id=" & sql_number(template_id)
Set rs = CreateRecordset(sql, adOpenForwardOnly, adLockReadOnly)
filename = rs("te_filename")

WriteExcel filename
OutputExcel tempfile, Replace(filename, ".", "_" & enquete_id & ".", 1, 1)
%>