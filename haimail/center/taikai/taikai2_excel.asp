<!--#INCLUDE FILE="../inc/common.asp"-->
<!--#INCLUDE FILE="../inc/sub.asp"-->
<!--#INCLUDE FILE="../inc/database.asp"-->
<!--#INCLUDE FILE="../inc/login.asp"-->
<%
'=== 退会時年齢取得 ===
Function TaikaiAge(seinengappi, taikai_date)

	If Month(taikai_date) * 100 + Day(taikai_date) >= Month(seinengappi) * 100 + Day(seinengappi) Then
		TaikaiAge = Year(taikai_date) - Year(seinengappi)
	Else
		TaikaiAge = Year(taikai_date) - Year(seinengappi) - 1
	End If
End Function

'=== 年代選択 ===
function GetNendai(n)
Dim i
	If n < 10 Then
		Getnendai = 1
	Else
		If n < 60	Then
			For i = 2 To 11
				If n => i*5 And n < (i + 1)*5  Then
					Getnendai = i
					Exit For
				End If
			Next
		Else
			Getnendai = 12
		End If
	End If
End function

'=== DBよりデータ抽出 ===
Private Sub ReadData()
	Dim sql, ds, i, n

	For i = 1 To 11
		hon(i) = 0
		taikai(i) = 0
		For n = 1 To 2
			seibetsu(i,n) = 0
		Next
		For n = 1 To 12
			nendai(i,n) = 0
		Next
		For n = 1 To s_count
			shokugyou(i,n) = 0
		Next
	Next

	sql = "SELECT TO_CHAR(MM_regist_date, 'YYYY') AS MM_year,COUNT(*) AS MM_count" & vbCrLf & _
				"FROM T_MEMBER" & vbCrLf & _
				"WHERE TO_CHAR(MM_regist_date,'YYYY') BETWEEN '2000' AND '2010'" & vbCrLf & _
				"GROUP BY TO_CHAR(MM_regist_date, 'YYYY')"
	Set ds = CreateDynaset(sql, ORADYN_READONLY)
	Do Until ds.EOF
		hon(CInt(Mid(ds("MM_year").Value, 3, 2))+1) = ds("MM_count").Value
		ds.MoveNext
	Loop

	sql = "SELECT TO_CHAR(TK_taikai_date, 'YYYY') AS TK_year,COUNT(*) AS TK_count" & vbCrLf & _
				"FROM T_TAIKAI" & vbCrLf & _
				"WHERE TO_CHAR(TK_taikai_date,'YYYY') BETWEEN '2000' AND '2010'" & vbCrLf & _
				"GROUP BY TO_CHAR(TK_taikai_date, 'YYYY')"
	Set ds = CreateDynaset(sql, ORADYN_READONLY)
	Do Until ds.EOF
		taikai(CInt(Mid(ds("TK_year").Value, 3, 2))+1) = ds("TK_count").Value
		ds.MoveNext
	Loop
	sql = "SELECT TK_taikai_date,MM_seibetsu,MM_shokugyou,MM_seinengappi" & vbCrLf & _
				"FROM T_TAIKAI, T_MEMBER, T_SHOKUGYOU" & vbCrLf & _
				"WHERE SG_shokugyou_code = MM_shokugyou(+) AND TK_member_id = MM_member_id" & vbCrLf & _
				"ORDER BY TK_taikai_date"
	Set ds = CreateDynaset(sql, ORADYN_READONLY)


	Do Until ds.EOF
		For i = 1 To 11
			If Not IsNull(ds("TK_taikai_date").Value) Then
				If CInt(Mid(year(ds("TK_taikai_date").Value), 3, 2)) + 1 = i Then

					n = CInt(ds("MM_seibetsu").Value) + 1
					seibetsu(i, n) = seibetsu(i, n) + 1

					n = TaikaiAge(ds("MM_seinengappi").Value, ds("TK_taikai_date").Value)
					n = GetNendai(n)
					nendai(i, n) = nendai(i, n) + 1

					For n = 1 To s_count
						If shokugyou_id(n) = ds("MM_shokugyou").Value Then
							shokugyou(i, n) = shokugyou(i, n) + 1
							Exit For
						End If
					Next

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

	filename = "taikai2.xlt"
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

'= Sheet1 =
	For i = 1 To 11
			objExcelSheet1.Cells(22, i + 1).Value = taikai(i)
			objExcelSheet1.Cells(23, i + 1).Value = hon(i)
	Next

'= Sheet2 =
	For i = 1 To 11
		For n = 1 To 2
			objExcelSheet2.Cells(n + 21, i + 1).Value = seibetsu(i, n)
		Next
	Next
'= Sheet3 =
	For i = 1 To 11
		For n = 1 To 12
			objExcelSheet3.Cells(n + 21, i + 1).Value = nendai(i, n)
		Next
	Next
'= Sheet4 =
	For i = 1 To 11
		For n = 1 To s_count
			objExcelSheet4.Cells(i + 21, 1).Value = shokugyou_name(i)
			objExcelSheet4.Cells(n + 21, i + 1).Value = shokugyou(i,n)
			objExcelSheet4.Cells(n + 21, 13).Value = "= sum(RC[-10]:RC[-1])"
		Next
			objExcelSheet4.Cells(n + 21, i + 1).Value = "= sum(R[-" & s_count & "]C:R[-1]C)"
	Next

	objExcelSheet4.Cells(n + 21, 1).Value = "計"
	objExcelSheet4.Cells(n + 21, 13).Value = "= sum(RC[-11]:RC[-1])"

	Set objXLChart = objExcelSheet4.ChartObjects(2).Chart

'= データ範囲指定 =
	objXLChart.SetSourceData objExcelSheet4.Range(objExcelSheet4.Cells(22, 1),objExcelSheet4.Cells(s_count + 21, 12))

'= 罫線 =
	objExcelSheet4.Range(objExcelSheet4.Cells(22, 1), objExcelSheet4.Cells(s_count + 22, 13)).Borders.LineStyle = xlContinuous

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
	Response.AddHeader "Content-Disposition", "inline; filename=""taikai2.xls"""
	Response.BinaryWrite barray
End Sub

'=== メイン処理 ===
Dim sql, ds
Dim hon(11), taikai(11), seibetsu(11, 2), shokugyou(11, 99), nendai(11, 13)
Dim temp, objXLChart, s_count, shokugyou_name(99), shokugyou_id(99)

	temp = "C:\TEMP\" & Session.SessionID & ".xls"

	sql = "SELECT *" & vbCrLf & _
				"FROM T_SHOKUGYOU" & vbCrLf & _
				"ORDER BY SG_seq_no"
	Set ds = CreateDynaset(sql, ORADYN_READONLY)

	s_count = 0
	Do Until ds.EOF
		s_count = s_count + 1
		shokugyou_name(s_count) = ds("SG_shokugyou_name").Value
		shokugyou_id(s_count) = ds("SG_shokugyou_code").Value
		ds.MoveNext
	Loop

	ReadData()
	WriteExcel temp
	OutputExcel temp
%>