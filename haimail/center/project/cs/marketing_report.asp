<!--#INCLUDE FILE="../../inc/common.asp"-->
<!--#INCLUDE FILE="../../inc/sub.asp"-->
<!--#INCLUDE FILE="../../inc/database.asp"-->
<!--#INCLUDE FILE="../../inc/mail_check.asp"-->
<!--#INCLUDE FILE="../../inc/login.asp"-->
<%
'******************************************************
' System :はいめーる・net事務局・営業共通ページ
' Content:マーケティング実施報告書作成処理
'******************************************************

Const xlEdgeLeft = 7
Const xlEdgeTop = 8
Const xlEdgeBottom = 9
Const xlEdgeRight = 10
Const xlInsideHorizontal = 12
Const xlLineStyleNone = -4142

'=== クリック率計算 ===
Private Function GetRate(n, total)
	If Number(total) = 0 Then
		GetRate = "-"
	Else
		GetRate = FormatPercent(Number(n) / Number(total), 2)
	End If
End Function

'=== 集計結果セット ===
Private Sub SetData(objRange, haishin, click, click_uniq, resp)
	With objRange
		.Columns(6).Value = Number(haishin)
		.Columns(8).Value = Number(click)
		.Columns(9).Value = GetRate(click, haishin)
		.Columns(10).Value = Number(click_uniq)
		.Columns(11).Value = GetRate(click_uniq, haishin)
		.Columns(12).Value = Number(resp)
		.Columns(14).Value = GetRate(resp, haishin)
	End With
End Sub

'=== セル位置取得 ===
Private Sub GetPosition(objWorkBook, name, row, col)
	Dim objName

	Set objName = objWorkBook.Names(name)
	col = objName.RefersToRange.Column
	row = objName.RefersToRange.Row
End Sub

'=== セルをチェック状態にセット ===
Private Sub CheckCell(cell)
	cell.Interior.ColorIndex = 37
	cell.Font.Bold = True
End Sub

'=== 住所セット ===
Private Sub SetJuusho(objName, addr)
	Dim objRange, ary, item, n, first

	ary = Split(addr, ",")
	For Each item In ary
		n = Instr(item, "/")
		If n > 0 Then
			item = Mid(item, n + 1)
		End If
		Set objRange = objName.RefersToRange.Find(item)
		If Not objRange Is Nothing Then
			first = objRange.Address
			Do
				If objRange.Value = item Then
					CheckCell objRange
					Exit Do
				End If
				Set objRange = objName.RefersToRange.FindNext(objRange)
			Loop While Not objRange Is Nothing And objRange.Address <> first
		End If
	Next
End Sub

'=== エクセルにデータ書き込み ===
Private Sub WriteExcel(job_id, tempfile)
	Dim sql, sub1, sub2, sub3, sub4, ds, ds2
	Dim objExcelApp, objWorkBook, objWorkSheets, objChart, objWorkSheetSA, objWorkSheetFA, objWorkSheetFA2
	Dim path, mail_id, mail_type, row, col, count, i, n, click, resp, ary, item, item2, nendai
	Dim total_send, total_click, total_resp, sum, cur_date, nSheet

	path = Server.MapPath("excel/marketing_report.xlt")

	Set objExcelApp = Server.CreateObject("Excel.Application")
	objExcelApp.DisplayAlerts = False
	objExcelApp.Visible = False

	objExcelApp.Workbooks.Open(path)

	Set objWorkBook = objExcelApp.ActiveWorkbook
	Set objWorkSheets = objWorkBook.Worksheets

	'シート１
	With objWorkBook.Sheets(1)
		'日付
		objWorkBook.Names("日付").RefersToRange.Value = DateFormat(Date)
	End With

	'シート２
	With objWorkBook.Sheets(2)
		'発信条件
		GetPosition objWorkBook, "発信条件", row, col
		sql = "SELECT *" & vbCrLf & _
					"FROM T_JOB,T_SEND_MAIL,T_SEARCH" & vbCrLf & _
					"WHERE JB_job_id=" & job_id & " AND JB_mail_id=SM_mail_id(+) AND JB_search_id=SE_search_id(+)"
		Set ds = CreateDynaset(sql, ORADYN_READONLY)
		If Not ds.EOF Then
			.Cells(row + 1, col + 2).Value = Year(ds("SM_start_date").Value)
			.Cells(row + 1, col + 4).Value = Month(ds("SM_start_date").Value)
			.Cells(row + 1, col + 6).Value = Day(ds("SM_start_date").Value)
			.Cells(row + 1, col + 8).Value = Hour(ds("SM_start_date").Value)
			.Cells(row + 2, col + 2).Value = ds("SM_send_num").Value
			.Cells(row + 3, col + 2).Value = ds("SM_bunkatsu_kankaku").Value
			.Cells(row + 4, col + 2).Value = ds("SM_bunkatsu_kaisuu").Value
			.Cells(row + 5, col + 2).Value = ds("SM_point_jushin").Value
			.Cells(row + 6, col + 2).Value = ds("SM_point_enquete").Value
			.Cells(row + 7, col + 2).Value = ds("SM_point_kansou").Value
			.Cells(row + 8, col + 2).Value = Year(ds("SM_limit_date").Value)
			.Cells(row + 8, col + 4).Value = Month(ds("SM_limit_date").Value)
			.Cells(row + 8, col + 6).Value = Day(ds("SM_limit_date").Value)
			.Cells(row + 8, col + 8).Value = Hour(ds("SM_limit_date").Value)
			mail_id = ds("JB_mail_id").Value
			mail_type = ds("JB_mail_type").Value
		End If

		'性別
		GetPosition objWorkBook, "ターゲット1", row, col
		If ds("SE_seibetsu_m") = "1" Then
			CheckCell .Cells(row + 1, col + 1)
		End If
		If ds("SE_seibetsu_f") = "1" Then
			CheckCell .Cells(row + 1, col + 2)
		End If

		'年齢
		.Cells(row + 2, col + 1).Value = ds("SE_nenrei_from").Value
		.Cells(row + 2, col + 2).Value = "～"
		.Cells(row + 2, col + 3).Value = ds("SE_nenrei_to").Value
		.Cells(row + 2, col + 4).Value = "才"

		'未既婚
		If ds("SE_mikikon_mi") = "1" Then
			CheckCell .Cells(row + 3, col + 1)
		End If
		If ds("SE_mikikon_ki") = "1" Then
			CheckCell .Cells(row + 3, col + 2)
		End If

		'職業
		If Not IsNull(ds("SE_shokugyou").Value) Then
			ary = Split(ds("SE_shokugyou").Value, ",")
			For Each item In ary
				GetPosition objWorkBook, "SG_" & item, row, col
				CheckCell .Cells(row, col)
			Next
		End If

		'居住地
		If ds("SE_kyojuu_shitei").Value = "1" Then
			If Not IsNull(ds("SE_kyojuu").Value) Then
				SetJuusho objWorkBook.Names("居住地"), ds("SE_kyojuu").Value
			End If
		Else
			GetPosition objWorkBook, "居住地指定なし", row, col
			CheckCell .Cells(row, col)
		End If

		'勤務地
		If ds("SE_kinmu_shitei").Value = "1" Then
			If Not IsNull(ds("SE_kinmu").Value) Then
				SetJuusho objWorkBook.Names("勤務地"), ds("SE_kinmu").Value
			End If
		Else
			GetPosition objWorkBook, "勤務地指定なし", row, col
			CheckCell .Cells(row, col)
		End If
	End With

	'シート３
	With objWorkBook.Sheets(3)
		'よく行く店
		If ds("SE_yokuikumise_shitei").Value = "1" Then
			item = ds("SE_yokuikumise").Value
			For i = 1 To Len(item)
				If Mid(item, i, 1) = "1" Then
					GetPosition objWorkBook, "YM_" & i, row, col
					CheckCell .Cells(row, col)
				End If
			Next
		Else
			GetPosition objWorkBook, "YM_0", row, col
			CheckCell .Cells(row, col)
		End If

		'どっち派
		If ds("SE_docchiha_shitei").Value = "1" Then
			item = ds("SE_docchiha").Value
			For i = 1 To Len(item)
				If Mid(item, i, 1) <> " " Then
					GetPosition objWorkBook, "DH_" & i & "_" & Mid(item, i, 1), row, col
					CheckCell .Cells(row, col)
				End If
			Next
		Else
			GetPosition objWorkBook, "DH_0", row, col
			CheckCell .Cells(row, col)
		End If
	End With

	'シート４
	With objWorkBook.Sheets(4)
		'興味ジャンル
		If ds("SE_kyoumi_genre_shitei").Value = "1" Then
			If ds("SE_kyoumi_genre_andor").Value = "O" Then
				GetPosition objWorkBook, "KG_OR", row, col
			Else
				GetPosition objWorkBook, "KG_AND", row, col
			End If
			CheckCell .Cells(row, col)

			item = ds("SE_kyoumi_genre1").Value
			item2 = ds("SE_kyoumi_genre2").Value
			For i = 1 To Len(item)
				If Mid(item, i, 1) = "1" Or Mid(item2, i, 1) = "1" Then
					GetPosition objWorkBook, "KG_" & i, row, col
					CheckCell .Cells(row, col)
				End If
			Next
		End If

		'コンテンツ
		GetPosition objWorkBook, "MT_" & mail_type, row, col
		CheckCell .Cells(row, col)

		'リレーション
		If IsNull(ds("JB_parent_job_id").Value) Then
			GetPosition objWorkBook, "RL_NONE", row, col
			CheckCell .Cells(row, col)
		Else
			GetPosition objWorkBook, "RL_THANK", row, col
			CheckCell .Cells(row, col)
		End If
	End With

	'集計共通サブクエリ
'	sub1 = "SELECT MC_member_id,SUM(count) AS MC_count,COUNT(*) AS MC_count_uniq" & vbCrLf & _
'				"FROM T_CLICK_COUNT,(SELECT MC_member_id,MC_ct_id,COUNT(*) AS count FROM T_MAIL_CLICK GROUP BY MC_ct_id,MC_member_id)" & vbCrLf & _
'				"WHERE CC_job_id=" & job_id & " AND CC_ct_id=MC_ct_id" & vbCrLf & _
'				"GROUP BY MC_member_id"
	sub1 = "SELECT MC_member_id,SUM(count) AS MC_count,COUNT(*) AS MC_count_uniq" & vbCrLf & _
				"FROM (SELECT MC_member_id,MC_ct_id,COUNT(*) AS count FROM T_MAIL_CLICK,T_CLICK_COUNT WHERE CC_job_id=" & job_id & " AND MC_ct_id=CC_ct_id GROUP BY MC_ct_id,MC_member_id)" & vbCrLf & _
				"GROUP BY MC_member_id"
	sub2 = "SELECT DISTINCT RM_member_id" & vbCrLf & _
				"FROM T_RECV_MAIL" & vbCrLf & _
				"WHERE RM_mail_id='" & mail_id & "' AND " & valid
	sub3 = "SELECT /*+ INDEX(T_SENDMAIL_RR) */ SR_member_id,MC_count,MC_count_uniq,RM_member_id" & vbCrLf & _
				"FROM T_SENDMAIL_RR,(" & sub1 & "),(" & sub2 & ")" & vbCrLf & _
				"WHERE SR_mail_id='" & mail_id & "' AND SR_status='1' AND SR_member_id=MC_member_id(+) AND SR_member_id=RM_member_id(+)"

	'シート５
	With objWorkBook.Sheets(5)
		'合計
		GetPosition objWorkBook, "合計", row, col
		sql = "SELECT COUNT(*),SUM(MC_count),SUM(MC_count_uniq),COUNT(RM_member_id)" & vbCrLf & _
					"FROM (" & sub3 & ")"
		Set ds = CreateDynaset(sql, ORADYN_READONLY)
		If Not ds.EOF Then
			SetData .Rows(row), ds(0).Value, ds(1).Value, ds(2).Value, ds(3).Value
			total_send = Number(ds(0).Value)
			total_click = Number(ds(1).Value)
			total_resp = Number(ds(3).Value)
		End If

		'性別
		GetPosition objWorkBook, "性別", row, col
		sql = "SELECT COUNT(*),SUM(MC_count),SUM(MC_count_uniq),COUNT(RM_member_id),MM_seibetsu" & vbCrLf & _
					"FROM (" & sub3 & "),T_MEMBER" & vbCrLf & _
					"WHERE SR_member_id=MM_member_id" & vbCrLf & _
					"GROUP BY MM_seibetsu"
		Set ds = CreateDynaset(sql, ORADYN_READONLY)
		Do Until ds.EOF
			Select Case ds("MM_seibetsu")
			Case "0"
				SetData .Rows(row), ds(0).Value, ds(1).Value, ds(2).Value, ds(3).Value
			Case "1"
				SetData .Rows(row + 1), ds(0).Value, ds(1).Value, ds(2).Value, ds(3).Value
			End Select
			ds.MoveNext
		Loop

		'年齢
		GetPosition objWorkBook, "年齢", row, col
		sql = "SELECT COUNT(*),SUM(MC_count),SUM(MC_count_uniq),COUNT(RM_member_id),TO_NENDAI3(MM_seinengappi) nendai" & vbCrLf & _
					"FROM (" & sub3 & "),T_MEMBER" & vbCrLf & _
					"WHERE SR_member_id=MM_member_id" & vbCrLf & _
					"GROUP BY TO_NENDAI3(MM_seinengappi)"
		Set ds = CreateDynaset(sql, ORADYN_READONLY)
		Do Until ds.EOF
			nendai = ds("nendai").Value
			If nendai >=1 Then
				SetData .Rows(row + nendai - 1), ds(0).Value, ds(1).Value, ds(2).Value, ds(3).Value
			End If
			ds.MoveNext
		Loop

		'未既婚
		GetPosition objWorkBook, "未既婚", row, col
		sql = "SELECT COUNT(*),SUM(MC_count),SUM(MC_count_uniq),COUNT(RM_member_id),MM_mikikon" & vbCrLf & _
					"FROM (" & sub3 & "),T_MEMBER" & vbCrLf & _
					"WHERE SR_member_id=MM_member_id" & vbCrLf & _
					"GROUP BY MM_mikikon"
		Set ds = CreateDynaset(sql, ORADYN_READONLY)
		Do Until ds.EOF
			Select Case ds("MM_mikikon")
			Case "0"
				SetData .Rows(row), ds(0).Value, ds(1).Value, ds(2).Value, ds(3).Value
			Case "1"
				SetData .Rows(row + 1), ds(0).Value, ds(1).Value, ds(2).Value, ds(3).Value
			End Select
			ds.MoveNext
		Loop

		'職業
		GetPosition objWorkBook, "職業", row, col
		sub4 = "SELECT COUNT(*),SUM(MC_count),SUM(MC_count_uniq),COUNT(RM_member_id),MM_shokugyou" & vbCrLf & _
					"FROM (" & sub3 & "),T_MEMBER" & vbCrLf & _
					"WHERE SR_member_id=MM_member_id" & vbCrLf & _
					"GROUP BY MM_shokugyou"
		sql = "SELECT *" & vbCrLf & _
					"FROM (" & sub4 & "),T_SHOKUGYOU" & vbCrLf & _
					"WHERE MM_shokugyou(+)=SG_shokugyou_code" & vbCrLf & _
					"ORDER BY SG_seq_no"
		Set ds = CreateDynaset(sql, ORADYN_READONLY)
		Do Until ds.EOF
			SetData .Rows(row), ds(0).Value, ds(1).Value, ds(2).Value, ds(3).Value
			row = row + 1
			ds.MoveNext
		Loop
	End With

	'シート６
	With objWorkBook.Sheets(6)
		'住所
		GetPosition objWorkBook, "居住地2", row, col
		sub4 = "SELECT COUNT(*),SUM(MC_count),SUM(MC_count_uniq),COUNT(RM_member_id),ZC_todoufuken" & vbCrLf & _
					"FROM (" & sub3 & "),T_MEMBER,T_ZIPCODE" & vbCrLf & _
					"WHERE SR_member_id=MM_member_id AND MM_zip_kyojuu=ZC_zip_uniq" & vbCrLf & _
					"GROUP BY ZC_todoufuken"
		sql = "SELECT *" & vbCrLf & _
					"FROM (" & sub4 & "),T_TODOUFUKEN" & vbCrLf & _
					"WHERE TD_todoufuken=ZC_todoufuken(+)" & vbCrLf & _
					"ORDER BY TD_seq_no"
		Set ds = CreateDynaset(sql, ORADYN_READONLY)
		Do Until ds.EOF
			.Cells(row, col + 1).Value = ds("TD_todoufuken").Value
			SetData .Rows(row), ds(0).Value, ds(1).Value, ds(2).Value, ds(3).Value
			row = row + 1
			ds.MoveNext
		Loop
	End With

	'シート７
	With objWorkBook.Sheets(7)
		'時間帯
		GetPosition objWorkBook, "時間帯", row, col
		sql = "SELECT COUNT(*),TO_CHAR(MC_date,'HH24') MC_time" & vbCrLf & _
					"FROM T_CLICK_COUNT,T_MAIL_CLICK" & vbCrLf & _
					"WHERE CC_job_id=" & job_id & " AND CC_ct_id=MC_ct_id AND MC_date IS NOT NULL" & vbCrLf & _
					"GROUP BY TO_CHAR(MC_date,'HH24')" & vbCrLf & _
					"ORDER BY MC_time"
		Set ds = CreateDynaset(sql, ORADYN_READONLY)
		sql = "SELECT COUNT(*),TO_CHAR(RM_recv_date,'HH24') RM_time" & vbCrLf & _
					"FROM T_RECV_MAIL" & vbCrLf & _
					"WHERE RM_mail_id='" & mail_id & "' AND " & valid & " AND RM_recv_date IS NOT NULL" & vbCrLf & _
					"GROUP BY TO_CHAR(RM_recv_date,'HH24')" & vbCrLf & _
					"ORDER BY RM_time"
		Set ds2 = CreateDynaset(sql, ORADYN_READONLY)
		For i = 0 To 23
			row = row + 1

			n = 0
			If Not ds.EOF Then
				If CInt(ds("MC_time").Value) = i Then
					n = Number(ds(0).Value)
					ds.MoveNext
				End If
			End If
			.Cells(row, 3).Value = n
			.Cells(row, 4).Value = GetRate(n, total_click)

			n = 0
			If Not ds2.EOF Then
				If CInt(ds2("RM_time").Value) = i Then
					n = Number(ds2(0).Value)
					ds2.MoveNext
				End If
			End If
			.Cells(row, 5).Value = n
			.Cells(row, 6).Value = GetRate(n, total_resp)
		Next
	End With

	'シート８
	With objWorkBook.Sheets(8)
		'日別クリック数
		GetPosition objWorkBook, "日数", row, col
		sql = "SELECT COUNT(*),TRUNC(MC_date,'DD') MC_date" & vbCrLf & _
					"FROM T_CLICK_COUNT,T_MAIL_CLICK" & vbCrLf & _
					"WHERE CC_job_id=" & job_id & " AND CC_ct_id=MC_ct_id" & vbCrLf & _
					"GROUP BY TRUNC(MC_date,'DD')" & vbCrLf & _
					"ORDER BY MC_date"
		Set ds = CreateDynaset(sql, ORADYN_READONLY)
		If Not ds.EOF Then
			cur_date = CDate(ds("MC_date").Value)
			count = 0
			sum = 0
			Do Until ds.EOF
				If CDate(ds("MC_date").Value) = cur_date Then
					click = ds(0).Value
					ds.MoveNext
				Else
					click = 0
				End If

				If count > 0 Then
					.Rows(row).Insert
					.Range(.Rows(row - 1), .Rows(row)).FillDown
					.Range(.Cells(row, 2), .Cells(row, 6)).Borders(xlEdgeTop).Weight = xlHairline
				End If

				sum = sum + click
				If sum > total_click Then
					sum = total_click
				End If

				.Cells(row, 2).Value = count + 1
				.Cells(row, 3).Value = DateFormat(cur_date)
				.Cells(row, 4).Value = click
				.Cells(row, 5).Value = sum
				.Cells(row, 6).Value = GetRate(sum, total_click)
				row = row + 1
				count = count + 1
				cur_date = DateAdd("d", 1, cur_date)
			Loop
		End If
		objWorkBook.Names("発信数").RefersToRange.Value = total_send
		GetPosition objWorkBook, "日数", row, col
		Set objChart = .ChartObjects(1).Chart
		objChart.SeriesCollection(1).Values = .Range(.Cells(row, 4), .Cells(row + count - 1, 4))
		objChart.SeriesCollection(2).Values = .Range(.Cells(row, 6), .Cells(row + count - 1, 6))
		objChart.Axes(xlCategory).CategoryNames = .Range(.Cells(row, 2), .Cells(row + count - 1, 3))
	End With

	'シート９
	With objWorkBook.Sheets(9)
		'日別レスポンス数
		GetPosition objWorkBook, "日数2", row, col
		sql = "SELECT COUNT(*),TRUNC(RM_recv_date,'DD') RM_date" & vbCrLf & _
					"FROM T_RECV_MAIL" & vbCrLf & _
					"WHERE RM_mail_id='" & mail_id & "' AND " & valid & " AND RM_recv_date IS NOT NULL" & vbCrLf & _
					"GROUP BY TRUNC(RM_recv_date,'DD')" & vbCrLf & _
					"ORDER BY RM_date"
		Set ds = CreateDynaset(sql, ORADYN_READONLY)
		If Not ds.EOF Then
			cur_date = CDate(ds("RM_date").Value)
			count = 0
			sum = 0
			Do Until ds.EOF
				If CDate(ds("RM_date").Value) = cur_date Then
					resp = ds(0).Value
					ds.MoveNext
				Else
					resp = 0
				End If

				If count > 0 Then
					.Rows(row).Insert
					.Range(.Rows(row - 1), .Rows(row)).FillDown
					.Range(.Cells(row, 2), .Cells(row, 6)).Borders(xlEdgeTop).Weight = xlHairline
				End If

				sum = sum + resp
				If sum > total_resp Then
					sum = total_resp
				End If

				.Cells(row, 2).Value = count + 1
				.Cells(row, 3).Value = DateFormat(cur_date)
				.Cells(row, 4).Value = resp
				.Cells(row, 5).Value = sum
				.Cells(row, 6).Value = GetRate(sum, total_resp)
				row = row + 1
				count = count + 1
				cur_date = DateAdd("d", 1, cur_date)
			Loop
		End If
		objWorkBook.Names("発信数2").RefersToRange.Value = total_send
		GetPosition objWorkBook, "日数2", row, col
		Set objChart = .ChartObjects(1).Chart
		objChart.SeriesCollection(1).Values = .Range(.Cells(row, 4), .Cells(row + count - 1, 4))
		objChart.SeriesCollection(2).Values = .Range(.Cells(row, 6), .Cells(row + count - 1, 6))
		objChart.Axes(xlCategory).CategoryNames = .Range(.Cells(row, 2), .Cells(row + count - 1, 3))
	End With

	'単純集計
	Set objWorkSheetSA = objWorkSheets("単純集計SA")
	Set objWorkSheetFA = objWorkSheets("単純集計FA")
	Set objWorkSheetFA2 = objWorkSheets("単純集計FA2")
	nSheet = 10
	sql = "SELECT *" & vbCrLf & _
				"FROM T_QUESTION" & vbCrLf & _
				"WHERE QU_mail_id='" & mail_id & "' AND QU_question_type='" & question_type & "'" & vbCrLf & _
				"ORDER BY QU_question_no"
	Set ds = CreateDynaset(sql, ORADYN_READONLY)
	Do Until ds.EOF
		If ds("QU_question_sel").Value > 0 Then
			objWorkSheetSA.Copy objWorkSheets(nSheet)
			AnswerSA ds, objWorkSheets(nSheet)
			nSheet = nSheet + 1
		End If

		If ds("QU_question_sel").Value = 0 Or ds("QU_question_fa").Value > 0 Then
			If question_type = "I" And ds("QU_question_no").Value = 2 Then
				objWorkSheetFA2.Copy objWorkSheets(nSheet)
			Else
				objWorkSheetFA.Copy objWorkSheets(nSheet)
			End If
			AnswerFA ds, objWorkSheets(nSheet)
			nSheet = nSheet + 1
		End If

		ds.MoveNext
	Loop
	objWorkSheetSA.Delete
	objWorkSheetFA.Delete
	objWorkSheetFA2.Delete

	objWorkSheets(1).Activate

	objWorkBook.SaveAs tempfile

	objExcelApp.Quit
End Sub

'=== 単純集計（選択） ===
Private Sub AnswerSA(ds, objWorkSheet)
	Dim row, col, sel_item

	With objWorkSheet
		objWorkSheet.name = "単純集計(" & ds("QU_question_no").Value & ")"

		GetPosition objWorkSheet, "質問番号1", row, col
		.Cells(row, col).Value = QES_NO & ToZenkaku(ds("QU_question_no").Value)

		GetPosition objWorkSheet, "質問内容1", row, col
		SetQuestionText objWorkSheet, ds("QU_question_text").Value, row, col

		sel_item = GetSelectionItem(ds("QU_question_sel").Value, ds("QU_question_text").Value)

		GetPosition objWorkSheet, "選択肢", row, col
		SetSelection objWorkSheet, sel_item, row, col

		GetPosition objWorkSheet, "選択集計", row, col
		SelectionResult objWorkSheet, ds("QU_mail_id").Value, ds("QU_question_no").Value, sel_item, row, col
	End With

End Sub

'=== 質問内容セット ===
Private Sub SetQuestionText(objWorkSheet, text, row, col)
	Dim ary, s, count, p

	With objWorkSheet
		ary = Split(text, vbCrLf)
		count = 0
		For Each s In ary
			s = Trim(s)
			If s <> "" Then
				If Left(s, 2) = ANS_NO Then
					Exit For
				End If

				If InStr(s, ANS_START) > 0 Then
					Exit For
				End If

				If ToHankaku(Left(s, 2)) = "1." Then
					Exit For
				End If

				If count = 0 Then
					If Left(s, 2) = QES_NO Then
						p = InStr(s, "．")
						If p > 0 Then
							s = Mid(s, p + 1)
						End If
					End If
				Else
					.Rows(row).Insert
					.Rows(44).Delete
				End If
				.Cells(row, col).Value = s
				row = row + 1
				count = count + 1
			End If
		Next
	End With
End Sub

'=== 選択肢取得 ===
Private Function GetSelectionItem(question_sel, text)
	Dim ary, i, s, p1, p2, p3, num, answer_no, tmp
	ReDim sel_item(question_sel)

	ary = Split(text, vbCrLf)
	For i = 0 To Ubound(ary)
		ary(i) = Trim(ary(i))
	Next
	s = Join(ary, " ")

	p1 = InStr(s, "１．")
	If p1 > 0 Then
		s = Mid(s, p1)
	End If

	p1 = InStr(s, ANS_NO)
	If p1 > 0 Then
		s = Left(s, p1 - 1)
	End If

	p1 = 1
	Do While True
		p2 = InStr(p1, s, "．")
		If p2 = 0 Then
			p2 = InStr(p1, s, ".")
			If p2 = 0 Then
				Exit Do
			End If
		End If

		num = ""
		p3 = p2
		Do While p3 > 1
			p3 = p3 - 1
			tmp = ToHankaku(Mid(s, p3, 1))
			If NumCheck(tmp) = False Then
				If tmp <> " " And tmp <> "　" Then
					num = ""
				End If
				Exit Do
			End If
			num = tmp & num
		Loop
		If num <> "" Then
			If answer_no > 0 Then
				sel_item(answer_no) = Trim(Mid(s, p1, p3 - p1))
			End If
			answer_no = CInt(num)
		End If
		p1 = p2 + 1
	Loop
	sel_item(answer_no) = Trim(Mid(s, p1))

	GetSelectionItem = sel_item
End Function

'=== 選択肢セット ===
Private Sub SetSelection(objWorkSheet, sel_item, row, col)
	Dim i

	With objWorkSheet
		For i = 1 To UBound(sel_item)
			If i > 1 Then
				.Rows(row).Insert
				.Rows(44).Delete
			End If
			.Cells(row, col).Value = i
			.Cells(row, col + 1).Value = sel_item(i)
			row = row + 1
		Next
	End With
End Sub

'=== 選択結果集計 ===
Private Sub SelectionResult(objWorkSheet, mail_id, question_no, sel_item, row, col)
	Dim sql, ds, i, n, yuukou, fumei, higaitou, row_start, objChart

	row_start = row

	With objWorkSheet
		.Cells(row, col + 1).Value = "Q" & question_no
		row = row + 1

		'有効回答集計
		sql = "SELECT AS_answer_no,COUNT(*) AS_count" & vbCrLf & _
					"FROM T_RECV_MAIL,T_ANSWER,T_ANSWER_NO" & vbCrLf & _
					"WHERE RM_mail_id='" & mail_id & "' AND " & valid & vbCrLf & _
					" AND RM_seq_no=AN_seq_no AND AN_question_type='" & question_type & "' AND AN_question_no=" & question_no & vbCrLf & _
					" AND RM_seq_no=AS_seq_no AND AS_question_type='" & question_type & "' AND AS_question_no=" & question_no & vbCrLf & _
					"GROUP BY AS_answer_no" & vbCrLf & _
					"ORDER BY AS_answer_no"
		Set ds = CreateDynaset(sql, ORADYN_READONLY)
		For i = 1 To UBound(sel_item)
			If i > 1 Then
				.Rows(row).Insert
				If Row < 41 Then
					.Rows(44).Delete
				End If
				.Range(.Rows(row - 1), .Rows(row)).FillDown
				.Range(.Cells(row, 2), .Cells(row, 9)).Borders(xlEdgeTop).Weight = xlHairline
				AdjustExp .Cells(row, col + 5)
				AdjustExp .Cells(row, col + 6)
				AdjustExp .Cells(row, col + 7)
			End If

			.Cells(row, col).Value = i
			.Cells(row, col + 1).Value = sel_item(i)
			n = 0
			If Not ds.EOF Then
				If CLng(ds(0).Value) = i Then
					n = CLng(NVL(ds(1).Value, 0))
					ds.MoveNext
				End If
			End If
			.Cells(row, col + 4).Value = n

			row = row + 1
		Next

		'回答数集計
		sql = "SELECT COUNT(*) AN_count,AN_answer_valid,AN_answer_invalid" & vbCrLf & _
					"FROM T_RECV_MAIL,T_ANSWER" & vbCrLf & _
					"WHERE RM_mail_id='" & mail_id & "' AND " & valid & vbCrLf & _
					" AND RM_seq_no=AN_seq_no AND AN_question_type='" & question_type & "' AND AN_question_no=" & question_no & vbCrLf & _
					"GROUP BY AN_answer_valid,AN_answer_invalid"
		Set ds = CreateDynaset(sql, ORADYN_READONLY)
		Do Until ds.EOF
			If ds("AN_answer_valid").Value = "1" Then
				yuukou = yuukou + CLng(ds(0).Value)
			Else
				If ds("AN_answer_invalid").Value = "1" Then
					higaitou = higaitou + CLng(ds(0).Value)
				End If
				If ds("AN_answer_valid").Value = "0" And ds("AN_answer_invalid").Value = "0" Then
					fumei = fumei + 1
				End If
			End If
			ds.MoveNext
		Loop

		'不明数セット
		.Cells(row, col + 4).Value = fumei
		row = row + 1

		'非該当数セット
		.Cells(row, col + 4).Value = higaitou
		row = row + 1

		'合計セット
		.Cells(row, col + 4) = yuukou + fumei + higaitou
		.Cells(row, col + 6) = yuukou + fumei
		.Cells(row, col + 7) = yuukou

		'グラフ参照データ設定
		Set objChart = .ChartObjects(1).Chart
		objChart.SeriesCollection(1).Values = .Range(.Cells(row_start + 1, col + 7), .Cells(row - 3, col + 7))
		objChart.Axes(xlCategory).CategoryNames = .Range(.Cells(row_start + 1, col + 1), .Cells(row - 3, col + 1))
	End With

End Sub

'=== 計算式補正 ===
Private Sub AdjustExp(cell)
	Dim s, p

	s = cell.Formula
	p = InStr(s, "/")
	cell.Formula = Left(s, p + 1) & CLng(Mid(s, p + 2)) - 1
End Sub

'=== 単純集計（ＦＡ） ===
Private Sub AnswerFA(ds, objWorkSheet)
	Dim row, col

	With objWorkSheet
		if ds("QU_question_sel").Value = 0 Then
			objWorkSheet.name = "単純集計(" & ds("QU_question_no").Value & ")"
		Else
			objWorkSheet.name = "単純集計(" & ds("QU_question_no").Value & "-2)"
		End If

		GetPosition objWorkSheet, "質問番号2", row, col
		.Cells(row, col).Value = QES_NO & ToZenkaku(ds("QU_question_no").Value)

		GetPosition objWorkSheet, "質問内容2", row, col
		SetQuestionText objWorkSheet, ds("QU_question_text").Value, row, col

		GetPosition objWorkSheet, "回答結果", row, col
		If question_type = "I" And ds("QU_question_no").Value = 2 Then
			AnswerResult2 objWorkSheet, ds("QU_mail_id").Value, ds("QU_question_no").Value, ds("QU_question_fa").Value, row, col
		Else
			AnswerResult objWorkSheet, ds("QU_mail_id").Value, ds("QU_question_no").Value, ds("QU_question_fa").Value, row, col
		End If
	End With
End Sub

'=== 回答結果セット ===
Private Sub AnswerResult(objWorkSheet, mail_id, question_no, question_fa, row, col)
	Dim sql, ds, i, ans, count, data()

	With objWorkSheet
		row = row + 1

		sql = "SELECT MM_seibetsu,MM_seinengappi,MM_mikikon,SG_shokugyou_name,ZC_todoufuken,AN_answer_text" & vbCrLf & _
					"FROM T_RECV_MAIL,T_ANSWER,T_MEMBER,T_SHOKUGYOU,T_ZIPCODE" & vbCrLf & _
					"WHERE RM_mail_id='" & mail_id & "' AND " & valid & vbCrLf & _
					" AND RM_seq_no=AN_seq_no AND AN_question_type='" & question_type & "' AND AN_question_no=" & question_no & vbCrLf & _
					" AND RM_member_id=MM_member_id AND MM_shokugyou=SG_shokugyou_code(+) AND MM_zip_kyojuu=ZC_zip_uniq(+)" & vbCrLf & _
					"ORDER BY RM_seq_no"
		Set ds = CreateDynaset(sql, ORADYN_READONLY)
		i = 1
		count = 0
		ReDim data(ds.RecordCount, 6)
		Do Until ds.EOF
			ans = GetAnswerItem(question_fa, ds("AN_answer_text").Value)
			If ans <> "" Then
				data(count, 0) = i
				data(count, 1) = SeibetsuText(ds("MM_seibetsu").Value)
				data(count, 2) = GetAge(ds("MM_seinengappi").Value)
				data(count, 3) = MikikonText(ds("MM_mikikon").Value)
				data(count, 4) = ds("SG_shokugyou_name").Value
				data(count, 5) = ds("ZC_todoufuken").Value
				data(count, 6) = ans
				count = count + 1
			End If

			i = i + 1
			ds.MoveNext
		Loop

		If count > 0 Then
			SetTableData objWorkSheet, data, row, col, count, 7, True
		End If

		GetPosition objWorkSheet, "有効数", row, col
		.Cells(row, col).Value = count
	End With
End Sub

'=== 回答結果セット（感想文Q1回答付き） ===
Private Sub AnswerResult2(objWorkSheet, mail_id, question_no, question_fa, row, col)
	Dim sub1, sub2, sql, ds, i, ans, count, data()

	With objWorkSheet
		row = row + 1

		sub1 = "SELECT RM_seq_no,MM_seibetsu,MM_seinengappi,MM_mikikon,SG_shokugyou_name,ZC_todoufuken,AN_answer_text" & vbCrLf & _
					"FROM T_RECV_MAIL,T_ANSWER,T_MEMBER,T_SHOKUGYOU,T_ZIPCODE" & vbCrLf & _
					"WHERE RM_mail_id='" & mail_id & "' AND " & valid & vbCrLf & _
					" AND RM_seq_no=AN_seq_no AND AN_question_type='" & question_type & "' AND AN_question_no=" & question_no & vbCrLf & _
					" AND RM_member_id=MM_member_id AND MM_shokugyou=SG_shokugyou_code(+) AND MM_zip_kyojuu=ZC_zip_uniq(+)"

		sub2 = "SELECT AS_seq_no,AS_answer_no" & vbCrLf & _
					"FROM T_ANSWER_NO" & vbCrLf & _
					"WHERE AS_question_type='" & question_type & "' AND AS_question_no=" & question_no - 1

		sql = "SELECT *" & vbCrLf & _
					"FROM (" & sub1 & "),(" & sub2 & ")" & vbCrLf & _
					"WHERE RM_seq_no=AS_seq_no(+)" & vbCrLf & _
					"ORDER BY RM_seq_no"
		Set ds = CreateDynaset(sql, ORADYN_READONLY)
		i = 1
		count = 0
		ReDim data(ds.RecordCount, 7)
		Do Until ds.EOF
			ans = GetAnswerItem(question_fa, ds("AN_answer_text").Value)
			If ans <> "" Then
				data(count, 0) = i
				data(count, 1) = SeibetsuText(ds("MM_seibetsu").Value)
				data(count, 2) = GetAge(ds("MM_seinengappi").Value)
				data(count, 3) = MikikonText(ds("MM_mikikon").Value)
				data(count, 4) = ds("SG_shokugyou_name").Value
				data(count, 5) = ds("ZC_todoufuken").Value
				data(count, 6) = ds("AS_answer_no").Value
				data(count, 7) = ans
				count = count + 1
			End If

			i = i + 1
			ds.MoveNext
		Loop

		If count > 0 Then
			SetTableData objWorkSheet, data, row, col, count, 8, True
		End If

		GetPosition objWorkSheet, "有効数", row, col
		.Cells(row, col).Value = count
	End With
End Sub

'=== 回答取得 ===
Private Function GetAnswerItem(question_fa, answer_text)
	Dim ans, p1, p2, tmp, i, ok

	ans = DeleteQuote(answer_text)
	If question_fa = 0 Then
		GetAnswerItem = ans
	Else
		ans = Replace(ans, vbCrLf, "")
		p1 = 1
		ok = False
		GetAnswerItem = ""
		For i = 0 To question_fa - 1
			tmp = ""
			If p1 > 0 Then
				p1 = InStr(p1, ans, "｛")
				If p1 > 0 Then
					p2 = InStr(p1, ans, "｝")
					If p2 > 0 Then
						tmp = Trim(Mid(ans, p1 + 1, p2 - p1 - 1))
						p1 = p2
					End If
				End If
			End If
			If i > 0 Then
				GetAnswerItem = GetAnswerItem & vbCrLf
			End If
			If tmp <> "" Then
				ok = True
			End If
			GetAnswerItem = GetAnswerItem & tmp
		Next
		If ok = False Then
			GetAnswerItem = ""
		End If
	End If
End Function

'=== 表データセット ===
Private Sub SetTableData(objWorkSheet, data, row, col, nrow, ncol, fit)
	Dim i

	With objWorkSheet
		For i = 2 To nrow
			.Rows(row + 1).Insert
		Next

		With .Range(.Cells(row, col), .Cells(row + nrow - 1, col + ncol - 1))
			If nrow >= 2 Then
				.Rows.FillDown
				.Borders(xlInsideHorizontal).Weight = xlHairline
			End If
			.Value = data
			If fit Then
				.Rows.AutoFit
			End If
		End With
	End With
End Sub

'=== エクセルファイルを出力 ===
Private Sub OutputExcel(tempfile)
	Dim basp, barray, fso

	Set basp= Server.CreateObject("basp21")
	barray = basp.BinaryRead(tempfile)

	Set fso = CreateObject("Scripting.FileSystemObject")
	fso.DeleteFile tempfile

	Response.Expires = 0
	Response.Clear

	Response.ContentType = "application/vnd.ms-excel"
	Response.AddHeader "Content-Disposition", "inline; filename=""マーケティング実施報告書.xls"""
	Response.BinaryWrite barray
End Sub

'=== メイン処理 ===
Dim job_id, tempfile
Dim valid, question_type

job_id = Request.QueryString("job_id")
If IsEmpty(job_id) Then
	SystemError "job_id is empty"
End If

If Request.QueryString("file") = "enq.dat" Then
	valid = "RM_enq_valid='1'"
	question_type = "E"
Else
	valid = "RM_imp_valid='1'"
	question_type = "I"
End If

tempfile = "C:\TEMP\" & session.SessionID & ".xls"

WriteExcel job_id, tempfile
OutputExcel tempfile
%>