<!--#INCLUDE FILE="../../inc/common.asp"-->
<!--#INCLUDE FILE="../../inc/sub.asp"-->
<!--#INCLUDE FILE="../../inc/database.asp"-->
<!--#INCLUDE FILE="../../inc/login.asp"-->
<%
'******************************************************
' System :はいめーる・net事務局用ページ
' Content:種別月別レスポンス推移出力処理
'******************************************************

Const xlEdgeLeft = 7
Const xlEdgeTop = 8
Const xlEdgeBottom = 9
Const xlEdgeRight = 10
Const xlInsideHorizontal = 12
Const xlLineStyleNone = -4142

'=== 率計算 ===
Private Function GetRate(n, total)
	If Number(total) = 0 Then
		GetRate = ""
	Else
		GetRate = Round(Number(n) / Number(total) * 100, 0)
	End If
End Function

'=== セル位置取得 ===
Private Sub GetPosition(objWorkBook, name, row, col)
	Dim objName

	Set objName = objWorkBook.Names(name)
	col = objName.RefersToRange.Column
	row = objName.RefersToRange.Row
End Sub

'=== エクセルにデータ書き込み ===
Private Sub WriteExcel(s_year, s_month, tempfile)
	Dim sql, sub1, sub2, ds
	Dim objExcelApp, objWorkBook, objWorkSheets, objChart
	Dim path, row, col, col2, width

	path = Server.MapPath("excel/pjt_response.xlt")

	Set objExcelApp = Server.CreateObject("Excel.Application")
	objExcelApp.DisplayAlerts = False
	objExcelApp.Visible = False

	objExcelApp.Workbooks.Open(path)

	Set objWorkBook = objExcelApp.ActiveWorkbook
	Set objWorkSheets = objWorkBook.Worksheets

	'シート１
	With objWorkBook.Sheets(1)
		'日付
		objWorkBook.Names("DATE").RefersToRange.Value = s_year & "年" & s_month & "月"

		'テーブル
		sub1 = "SELECT RM_mail_id,COUNT(RM_enq_valid) AS RM_enq_count,COUNT(RM_imp_valid) AS RM_imp_count FROM T_RECV_MAIL GROUP BY RM_mail_id"
		sub2 = "SELECT CC_job_id,COUNT(*) AS CC_count FROM T_MAIL_CLICK,T_CLICK_COUNT WHERE MC_ct_id=CC_ct_id GROUP BY CC_job_id"
		sql = "SELECT SUM(RM_enq_count) RM_enq_count,SUM(RM_imp_count) RM_imp_count,SUM(CC_count) CC_count," & vbCrLf & _
					" SUM(DECODE(NVL(RM_enq_count,0),0,0,SM_real_send)) SM_enq_count," & vbCrLf & _
					" SUM(DECODE(NVL(RM_imp_count,0),0,0,SM_real_send)) SM_imp_count," & vbCrLf & _
					" SUM(DECODE(NVL(CC_count,0),0,0,SM_real_send)) SM_cc_count," & vbCrLf & _
					" JB_project_id,MIN(SM_start_date) SM_start_date" & vbCrLf & _
					"FROM (" & sub1 & "),(" & sub2 & "),T_JOB,T_SEND_MAIL" & vbCRLf & _
					"WHERE TO_CHAR(SM_start_date,'YYYYMM')='" & s_year & Right("0" & s_month, 2) & "' AND JB_mail_id=SM_mail_id AND JB_mail_id=RM_mail_id(+) AND JB_job_id=CC_job_id(+)" & vbCrLf & _
					"GROUP BY JB_project_id" & vbCrLf & _
					"ORDER BY JB_project_id"
		Set ds = CreateDynaset(sql, ORADYN_READONLY)

		GetPosition objWorkBook, "TABLE", row, col
		col2 = col
		width = .Columns(col + 1).ColumnWidth
		Do Until ds.EOF
			col = col + 1
			.Columns(col).ColumnWidth = width
			.Cells(row + 0, col).Value = GetRate(ds("RM_enq_count").Value, ds("SM_enq_count").Value)
			.Cells(row + 1, col).Value = GetRate(ds("CC_count").Value, ds("SM_cc_count").Value)
			.Cells(row + 2, col).Value = GetRate(ds("RM_imp_count").Value, ds("SM_imp_count").Value)
			.Cells(row + 3, col).Value = ds("JB_project_id").Value
			.Cells(row + 4, col).Value = Day(ds("SM_start_date").Value)
			.Cells(row + 5, col).Value = GetWeekName(ds("SM_start_date").Value)
			ds.MoveNext
		Loop

		'.Range(.Columns(col2 + 2), .Columns(col)).Width = width

		'グラフ
		If col > col2 Then
			Set objChart = .ChartObjects(1).Chart
			objChart.SeriesCollection(1).Values = .Range(.Cells(row + 1, col2 + 1), .Cells(row + 1, col))
			objChart.SeriesCollection(2).Values = .Range(.Cells(row + 2, col2 + 1), .Cells(row + 2, col))
			objChart.SeriesCollection(3).Values = .Range(.Cells(row + 0, col2 + 1), .Cells(row + 0, col))
			objChart.Axes(xlCategory).CategoryNames = .Range(.Cells(row + 3, col2 + 1), .Cells(row + 3, col))
		End If
	End With

	objWorkBook.SaveAs tempfile

	objExcelApp.Quit
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
	Response.AddHeader "Content-Disposition", "inline; filename=""種別月別レスポンス推移.xls"""
	Response.BinaryWrite barray
End Sub

'=== メイン処理 ===
Dim s_year, s_month, tempfile

s_year = Request.Form("s_year")
If IsEmpty(s_year) Then
	SystemError "s_year is empty"
End If

s_month = Request.Form("s_month")
If IsEmpty(s_month) Then
	SystemError "s_month is empty"
End If

tempfile = "C:\TEMP\" & session.SessionID & ".xls"

WriteExcel s_year, s_month, tempfile
OutputExcel tempfile
%>