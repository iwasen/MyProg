<!--#INCLUDE FILE="../melmel_inc.asp"-->
<!--#INCLUDE FILE="admin_inc.asp"-->
<%
'******************************************************
' System :めるめる・net事務局用ページ
' Content:クリックレポート出力
'******************************************************

'=== 親の広告ID取得 ===
Private Function GetParentKoukoku(koukoku_id)
	Dim SQL, RS

	SQL = "SELECT ISNULL(KK_tsuika_id,KK_koukoku_id) FROM V_KOUKOKU WHERE KK_koukoku_id='" & koukoku_id & "'"
	Set RS = DB.Execute(SQL)
	GetParentKoukoku = RS(0)
End Function

'=== エクセルにデータ書き込み ===
Private Sub WriteExcel(koukoku_id, temp)
	Const xlEdgeLeft = 7
	Const xlEdgeTop = 8
	Const xlEdgeBottom = 9
	Const xlEdgeRight = 10
	Dim SQL, RS, SUB1
	Dim objExcelApp, objExcelBook, objExcelSheets, objExcelSheet, objName, objChart
	Dim path, i, filename, dt
	Dim pub_start, pub_end, rec_count, st_row, row, col
	Dim click_count, keisai_busuu, click_total, keisai_total

	filename = "click_report.xlt"
	path = Server.MapPath(filename)

	Set objExcelApp = Server.CreateObject("Excel.Application")
  objExcelApp.DisplayAlerts = False
  objExcelApp.Visible = False

	objExcelApp.Workbooks.Open(path)

	Set objExcelBook = objExcelApp.ActiveWorkbook
	Set objExcelSheets = objExcelBook.Worksheets
	Set objExcelSheet = objExcelBook.Sheets(1)

	With objExcelSheet

		Set RS = Server.CreateObject("ADODB.RecordSet")

		SQL = "SELECT MIN(KK_pub_start),MAX(KK_pub_end) FROM V_KOUKOKU WHERE KK_koukoku_id='" & koukoku_id & "' OR KK_tsuika_id='" & koukoku_id & "'"
		RS.Open SQL, DB, adOpenForwardOnly, adLockReadOnly
		pub_start = RS(0)
		pub_end = RS(1)
		RS.Close

		SQL = "SELECT * FROM V_KOUKOKU WHERE KK_koukoku_id='" & koukoku_id & "'"
		RS.Open SQL, DB, adOpenForwardOnly, adLockReadOnly
		.Cells(1, 1).Value = RS("KK_client_name") & "御中"
		.Cells(5, 3).Value = RS("KK_koukoku_name")
		.Cells(6, 3).Value = DateFormat(pub_start)
		.Cells(7, 3).Value = DateFormat(pub_end)
		.Cells(8, 3).Value = NumFormat(RS("KK_busuu"))
		.Cells(9, 3).Value = "タイプ" & RS("KK_koukoku_type") & "-" & RS("KK_koukoku_line") & "行"
		.Cells(5, 5).Value = RS("KK_koukoku_url")
		.Cells(7, 4).Value = RS("KK_koukoku_msg")

		'１０行広告の場合の位置合わせ
		If RS("KK_koukoku_line") = 10 Then
			For i = 1 To 4
				.Rows(11).Insert
			Next
		End If
		RS.Close

		'全体クリック数
		SUB1 = "SELECT CL_koukoku_id,COUNT(*) AS click_count FROM T_CLICKLOG GROUP BY CL_koukoku_id"
		SQL = "SELECT KK_koukoku_name,KK_client_name,KK_pub_start,KK_pub_end,click_count,keisai_busuu" & vbCrLf & _
		      "FROM (V_KOUKOKU LEFT OUTER JOIN (" & SUB1 & ") AS SUB1 ON KK_koukoku_id=CL_koukoku_id)" & vbCrLf & _
		      "LEFT OUTER JOIN V_KEISAI_BUSUU ON KK_koukoku_id=koukoku_id" & vbCrLf & _
					"WHERE KK_koukoku_id='" & koukoku_id & "' OR KK_tsuika_id='" & koukoku_id & "'" & vbCrLf & _
					"ORDER BY KK_koukoku_id"
		RS.Open SQL, DB, adOpenKeyset, adLockReadOnly
		rec_count = RS.RecordCount
		Set objName = objExcelBook.Names("全体クリック数")
		st_row = objName.RefersToRange.Row
		click_total = 0
		keisai_total = 0

		For i = 0 To rec_count - 1
			row = st_row + i
			If i = 0 Then
				.Cells(row, 2).Value = RS("KK_koukoku_name")
				.Cells(row, 3).Value = RS("KK_client_name")
			Else
				.Rows(row).Insert
				For col = 4 To 8
					.Cells(row, col).Borders.LineStyle = xlContinuous
					.Cells(row, col).Borders(xlEdgeTop).Weight = xlHairline
				Next
			End If

			click_count = NVL(RS("click_count"), 0)
			keisai_busuu = NVL(RS("keisai_busuu"), 0)

			.Cells(row, 4).Value = DateFormat(RS("KK_pub_start"))
			.Cells(row, 5).Value = DateFormat(RS("KK_pub_end"))
			.Cells(row, 6).Value = NumFormat(click_count)
			.Cells(row, 7).Value = NumFormat(keisai_busuu)
			If keisai_busuu = 0 Then
				.Cells(row, 8).Value = "-"
			Else
				.Cells(row, 8).Value = FormatNumber(CDbl(click_count) / keisai_busuu * 100, 3, -1, 0, 0) & "%"
			End If

			click_total = click_total + click_count
			keisai_total = keisai_total + keisai_busuu

			RS.MoveNext
		Next
		RS.close

		row = st_row + i
		.Cells(row, 6).Value = NumFormat(click_total)
		.Cells(row, 7).Value = NumFormat(keisai_total)
		If keisai_total = 0 Then
			.Cells(row, 8).Value = "-"
		Else
			.Cells(row, 8).Value = FormatNumber(CDbl(click_total) / keisai_total * 100, 3, -1, 0, 0) & "%"
		End If

		.Range(.Cells(st_row, 2), .Cells(st_row + rec_count - 1, 2)).MergeCells = True
		.Range(.Cells(st_row, 3), .Cells(st_row + rec_count - 1, 3)).MergeCells = True
		.Range(.Cells(st_row, 2), .Cells(st_row + rec_count - 1, 2)).Borders.LineStyle = xlContinuous

		'時間別クリック数
		SUB1 = "SELECT *,DATEPART(hour,CL_date) AS S1_time FROM T_CLICKLOG"
		SQL = "SELECT COUNT(*) AS cnt,S1_time" & vbCrLf & _
		      "FROM (" & SUB1 & ") AS SUB1" & vbCrLf & _
					"WHERE CL_koukoku_id IN (SELECT KK_koukoku_id FROM V_KOUKOKU WHERE KK_koukoku_id='" & koukoku_id & "' OR KK_tsuika_id='" & koukoku_id & "')" & vbCrLf & _
		      "GROUP BY S1_time" & vbCrLf & _
		      "ORDER BY S1_time"
		RS.Open SQL, DB, adOpenForwardOnly, adLockReadOnly
		rec_count = RS.RecordCount
		Set objName = objExcelBook.Names("時間別クリック数")
		st_row = objName.RefersToRange.Row
		click_total = 0

		For i = 0 To 23
		  If RS.EOF Then
		    click_count = 0
		  Else
		    If i = RS("S1_time") Then
		      click_count = RS("cnt")
		      RS.MoveNext
		    Else
		      click_count = 0
		    End If
		  End If
			row = st_row + i
			.Cells(row, 3).Value = NumFormat(click_count)
			click_total = click_total + click_count
		Next
		RS.close
		row = st_row + i
		.Cells(row, 3).Value = NumFormat(click_total)

		'日別クリック数
		SUB1 = "SELECT *,CAST(CAST(CAST(CL_date AS REAL) AS INT) AS DATETIME) AS S1_date FROM T_CLICKLOG"
		SQL = "SELECT COUNT(*) AS cnt,S1_date" & vbCrLf & _
		      "FROM (" & SUB1 & ") AS SUB1" & vbCrLf & _
						"WHERE CL_koukoku_id IN (SELECT KK_koukoku_id FROM V_KOUKOKU WHERE KK_koukoku_id='" & koukoku_id & "' OR KK_tsuika_id='" & koukoku_id & "')" & vbCrLf & _
		      "GROUP BY S1_date" & vbCrLf & _
		      "ORDER BY S1_date"
		RS.Open SQL, DB, adOpenForwardOnly, adLockReadOnly
		Set objName = objExcelBook.Names("日付別クリック数")
		st_row = objName.RefersToRange.Row
		click_total = 0
		i = 0
		Do Until RS.EOF
			row = st_row + i
			If i > 0 Then
				.Range(.Cells(row, 5), .Cells(row, 6)).Insert
				For col = 5 To 6
					.Cells(row, col).Borders(xlEdgeTop).Weight = xlHairline
				Next
				If i Mod 2 = 0 Then
					.Range(.Cells(row, 5), .Cells(row, 6)).Interior.Color = .Cells(st_row, 5).Interior.Color
				Else
					.Range(.Cells(row, 5), .Cells(row, 6)).Interior.ColorIndex = 2
				End If
				If i > 23 Then
					.Rows(row + 2).Insert
				End If
			End If

			click_count = RS("cnt")
			.Cells(row, 5).Value = DateFormat(RS("S1_date"))
			.Cells(row, 6).Value = NumFormat(click_count)
			click_total = click_total + click_count
			i = i + 1
		  RS.MoveNext
		Loop
		RS.close
		.Cells(row + 1, 6).Value = NumFormat(click_total)

		'グラフのデータ範囲
		Set objChart = .ChartObjects(2).Chart
		objChart.SetSourceData .Range(.Cells(st_row, 5), .Cells(row, 6))
	End With

	objExcelBook.SaveAs temp

	objExcelApp.Quit
End Sub

'=== エクセルファイルを出力 ===
Private Sub OutputExcel(temp)
	Dim basp, barray, fso

	Set basp= Server.CreateObject("basp21")
	barray = basp.BinaryRead(temp)

	Set fso = CreateObject("Scripting.FileSystemObject")
	fso.DeleteFile temp

	Response.Expires = 0
	Response.Clear

	Response.ContentType = "application/vnd.ms-excel"
	Response.AddHeader "Content-Disposition", "inline; filename=""クリックレポート.xls"""
	Response.BinaryWrite barray
End Sub

'=== メイン処理 ===
Dim DB, SQL, koukoku_id, temp

koukoku_id = Request.Form("sel")
If IsEmpty(koukoku_id) Then
  Response.End
End If

Set DB = OpenDB()

temp = Server.MapPath("../temp/" & session.SessionID & ".xls")

koukoku_id = GetParentKoukoku(koukoku_id)

WriteExcel koukoku_id, temp
OutputExcel temp
%>