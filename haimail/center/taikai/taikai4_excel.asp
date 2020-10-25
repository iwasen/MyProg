<!--#INCLUDE FILE="../inc/common.asp"-->
<!--#INCLUDE FILE="../inc/sub.asp"-->
<!--#INCLUDE FILE="../inc/database.asp"-->
<!--#INCLUDE FILE="../inc/login.asp"-->
<%
'=== DBよりデータ抽出 ===
Private Sub ReadData(year, month)
	Dim sql, ds, i

	For i = 1 To 31
		hon(i) = 0
		taikai(i) = 0
	Next

	sql = "SELECT TO_CHAR(MM_regist_date, 'DD') AS MM_day,COUNT(*) AS MM_count FROM T_MEMBER" & vbCrLf & _
				"WHERE TO_CHAR(MM_regist_date,'YYYY')='" & year & "' AND TO_NUMBER(TO_CHAR(MM_regist_date,'MM'))=TO_NUMBER(" & month & ")" & vbCrLf & _
				"GROUP BY TO_CHAR(MM_regist_date, 'DD')"
	Set ds = CreateDynaset(sql, ORADYN_READONLY)
	Do Until ds.EOF
		hon(CInt(ds("MM_day").Value)) = ds("MM_count").Value
		ds.MoveNext
	Loop

	sql = "SELECT TO_CHAR(TK_taikai_date, 'DD') AS TK_day,COUNT(*) AS TK_count FROM T_TAIKAI" & vbCrLf & _
				"WHERE TO_CHAR(TK_taikai_date,'YYYY')='" & year & "' AND TO_NUMBER(TO_CHAR(TK_taikai_date,'MM'))=TO_NUMBER(" & month & ")" & vbCrLf & _
				"GROUP BY TO_CHAR(TK_taikai_date, 'DD')"
	Set ds = CreateDynaset(sql, ORADYN_READONLY)
	Do Until ds.EOF
		taikai(CInt(ds("TK_day").Value)) = ds("TK_count").Value
		ds.MoveNext
	Loop
End Sub

'=== エクセルにデータ書き込み ===
Private Sub WriteExcel(temp)
	Const week = "日月火水木金土"
	Dim objExcelApp, objExcelBook, objExcelSheets, objExcelSheet
	Dim path, i, filename, dt
	Dim t_total, h_total

	filename = "taikai4.xlt"
	path = Server.MapPath(filename)

	Set objExcelApp = Server.CreateObject("Excel.Application")
	objExcelApp.DisplayAlerts = False
	objExcelApp.Visible = False

	objExcelApp.Workbooks.Open(path)

	Set objExcelBook = objExcelApp.ActiveWorkbook
	Set objExcelSheets = objExcelBook.Worksheets
	Set objExcelSheet = objExcelBook.Sheets(1)

	objExcelSheet.Cells(4, 1).Value = "　" & year & "年" & month & "月"

	For i = 1 To 31
		dt = year & "/" & month & "/" & i
		If IsDate(dt) Then
			objExcelSheet.Cells(21, i + 1).Value = Mid(week, Weekday(CDate(dt)), 1)
			objExcelSheet.Cells(22, i + 1).Value = taikai(i)
			objExcelSheet.Cells(23, i + 1).Value = hon(i)
		End If
	Next
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
	Response.AddHeader "Content-Disposition", "inline; filename=""taikai4.xls"""
	Response.BinaryWrite barray
End Sub

'=== メイン処理 ===
Dim hon(31), taikai(31)

	Dim year, month, temp

	year = Request.Form("s_year")
	month = Request.Form("s_month")

	If IsEmpty(year) Or IsEmpty(month) Then
		Response.Redirect "index.asp"
	End If

	temp = "C:\TEMP\" & Session.SessionID & ".xls"

	ReadData year, month
	WriteExcel temp
	OutputExcel temp
%>