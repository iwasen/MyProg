<!--#INCLUDE FILE="../inc/common.asp"-->
<!--#INCLUDE FILE="../inc/sub.asp"-->
<!--#INCLUDE FILE="../inc/database.asp"-->

<%

'=== DBよりデータ抽出 ===
Private Sub ReadData(s_year)
	Dim sql, ds, i, n

	For i = 1 To 12

		For n = 1 To 11
			shokugyou(i,n) = 0
		Next
	Next


	sql = "SELECT *" & vbCrLf & _
				"FROM T_SHOKUGYOU" & vbCrLf & _
				"ORDER BY SG_seq_no"
	Set ds = CreateDynaset(sql, ORADYN_READONLY)

	shokugyou_count = 1
	Do Until ds.EOF
		shokugyou_name(shokugyou_count) = ds("SG_shokugyou_name").Value
		shokugyou_count = shokugyou_count + 1
		ds.MoveNext
	Loop

	sql = "SELECT *" & vbCrLf & _
				"FROM T_TAIKAI, T_MEMBER, T_SHOKUGYOU" & vbCrLf & _
				"WHERE SG_shokugyou_code = MM_shokugyou(+) AND TK_member_id = MM_member_id AND TO_CHAR(TK_taikai_date,'YYYY')='" & s_year & "'" & vbCrLf & _
				"ORDER BY TK_taikai_date"
	Set ds = CreateDynaset(sql, ORADYN_READONLY)



	Do Until ds.EOF
		For i = 1 To 12
			If Not IsNull(ds("TK_taikai_date").Value) Then
				If CInt(Month(ds("TK_taikai_date").Value)) = i Then

					n = CInt(ds("MM_shokugyou").Value)
					shokugyou(i, n) = shokugyou(i, n) + 1

					i = i - 1
					ds.MoveNext
				End If
			End If
		Next
	Loop
End Sub

'=== エクセルにデータ書き込み ===
Private Sub WriteExcel(temp)
	Dim objExcelApp, objExcelBook, objExcelSheets
	Dim objExcelSheet1, objExcelSheet2, objExcelSheet3, objExcelSheet4
	Dim path, i, n, filename, dt

	filename = "taikai8.xlt"
	path = Server.MapPath(filename)

	Set objExcelApp = Server.CreateObject("Excel.Application")
	objExcelApp.DisplayAlerts = False
	objExcelApp.Visible = False

	objExcelApp.Workbooks.Open(path)

	Set objExcelBook = objExcelApp.ActiveWorkbook
	Set objExcelSheets = objExcelBook.Worksheets
	Set objExcelSheet1 = objExcelBook.Sheets(1)
	Set objExcelSheet2 = objExcelBook.Sheets(2)
	Set objExcelSheet3 = objExcelBook.Sheets(3)
	Set objExcelSheet4 = objExcelBook.Sheets(4)


'= Sheet4 =
	objExcelSheet4.Cells(4, 1).Value = "　" & s_year & "年"
	For i = 1 To shokugyou_count
		objExcelSheet4.Cells(i+21, 1).Value = shokugyou_name(i)
	Next

	For i = 1 To 12
		For n = 1 To 11
			objExcelSheet4.Cells(n+21, i + 1).Value = shokugyou(i,n)
		Next
	Next

'========= ここ！ ===========
objExcelApp.Range("A22:M32").Select
Set objXLChart = objExcelApp.Charts.Add()
'============================

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
	Response.AddHeader "Content-Disposition", "inline; filename=""taikai8.xls"""
	Response.BinaryWrite barray
End Sub

'=== メイン処理 ===
Dim hon(12), taikai(12), seibetsu(12, 2), shokugyou(12, 99), nendai(12, 13), shokugyou_name(99)
Dim s_year, temp, shokugyou_count, objXLChart

	s_year = Request.Form("s_year")
	s_year = "2000"


	If IsEmpty(s_year) Then
		Response.Redirect "index.asp"
	End If

	temp = "C:\TEMP\" & Session.SessionID & ".xls"

	ReadData s_year
	WriteExcel temp
	OutputExcel temp
%>