<!--#INCLUDE FILE="../inc/common.asp"-->
<!--#INCLUDE FILE="../inc/sub.asp"-->
<!--#INCLUDE FILE="../inc/database.asp"-->
<!--#INCLUDE FILE="../inc/login.asp"-->
<%

'=== DBよりデータ抽出 ===
Private Sub ReadData(s_year)
	Dim sql, ds, i, n

	For i = 1 To 12
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

	sql = "SELECT TO_CHAR(MM_regist_date, 'MM') AS MM_month,COUNT(*) AS MM_count" & vbCrLf & _
				"FROM T_MEMBER" & vbCrLf & _
				"WHERE TO_CHAR(MM_regist_date,'YYYY')='" & s_year & "'" & vbCrLf & _
				"GROUP BY TO_CHAR(MM_regist_date, 'MM')"
	Set ds = CreateDynaset(sql, ORADYN_READONLY)
	Do Until ds.EOF
		hon(CInt(ds("MM_month").Value)) = ds("MM_count").Value
		ds.MoveNext
	Loop

	sql = "SELECT TO_CHAR(TK_taikai_date, 'MM') AS TK_month,COUNT(*) AS TK_count" & vbCrLf & _
				"FROM T_TAIKAI" & vbCrLf & _
				"WHERE TO_CHAR(TK_taikai_date,'YYYY')='" & s_year & "'" & vbCrLf & _
				"GROUP BY TO_CHAR(TK_taikai_date, 'MM')"
	Set ds = CreateDynaset(sql, ORADYN_READONLY)
	Do Until ds.EOF
		taikai(CInt(ds("TK_month").Value)) = ds("TK_count").Value
		ds.MoveNext
	Loop
End Sub

'=== エクセルにデータ書き込み ===
Private Sub WriteExcel(temp)
	Dim objExcelApp, objExcelBook, objExcelSheets
	Dim objExcelSheet1, objExcelSheet2, objExcelSheet3, objExcelSheet4
	Dim path, i, n, filename, dt, nendai2, sub1, s

	filename = "taikai3.xlt"
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
	objExcelSheet1.Cells(4, 1).Value = "　" & s_year & "年"

	For i = 1 To 12
		dt = s_year & "/" & i
		If IsDate(dt) Then
			objExcelSheet1.Cells(22, i + 1).Value = taikai(i)
			objExcelSheet1.Cells(23, i + 1).Value = hon(i)
		End If
	Next

'= Sheet2 =
	objExcelSheet2.Cells(4, 1).Value = "　" & s_year & "年"

	sql = "SELECT MM_seibetsu, TO_CHAR(TK_taikai_date, 'MM') AS TK_month, COUNT(*)" & vbCrLF & _
				"FROM T_member, T_taikai" & vbCrLF & _
				"WHERE TK_member_id = MM_member_id AND TO_CHAR(TK_taikai_date,'YYYY')='" & s_year & "'" & vbCrLF & _
				"GROUP BY MM_seibetsu, TO_CHAR(TK_taikai_date, 'MM')" & vbCrLF & _
				"ORDER BY TO_CHAR(TK_taikai_date, 'MM')"
	Set ds = CreateDynaset(sql, ORADYN_READONLY)

	Do Until ds.EOF
		n = ds(0).value
		i = ds(1).value
		objExcelSheet2.Cells(n + 22, i + 1).Value = ds(2).value
		ds.MoveNext
	Loop

'= Sheet3 =

	objExcelSheet3.Cells(4, 1).Value = "　" & s_year & "年"

	sql = "SELECT TO_NENDAI2(TK_taikai_date,MM_seinengappi) nendai2, TO_CHAR(TK_taikai_date, 'MM') AS TK_month, COUNT(*)" & vbCrLF & _
				"FROM T_member, T_taikai" & vbCrLF & _
				"WHERE TK_member_id = MM_member_id AND TO_CHAR(TK_taikai_date,'YYYY')='" & s_year & "'" & vbCrLF & _
				"GROUP BY TO_NENDAI2(TK_taikai_date, MM_seinengappi), TO_CHAR(TK_taikai_date, 'MM')" & vbCrLF & _
				"ORDER BY TO_CHAR(TK_taikai_date, 'MM')"
	Set ds = CreateDynaset(sql, ORADYN_READONLY)

	Do Until ds.EOF
		n = ds(0).value
		i = ds(1).value
		objExcelSheet3.Cells(n + 21, i + 1).Value = ds(2).value
		ds.MoveNext
	Loop

'= Sheet4 =
	objExcelSheet4.Cells(4, 1).Value = "　" & s_year & "年"

	sub1 = "SELECT MM_shokugyou, TO_CHAR(TK_taikai_date, 'MM') AS TK_month, COUNT(*)" & vbCrLF & _
				"FROM T_MEMBER, T_TAIKAI" & vbCrLF & _
				"WHERE TK_member_id = MM_member_id AND TO_CHAR(TK_taikai_date,'YYYY')='" & s_year & "'" & vbCrLF & _
				"GROUP BY MM_shokugyou, TO_CHAR(TK_taikai_date, 'MM')" & vbCrLF & _
				"ORDER BY TO_CHAR(TK_taikai_date, 'MM')"
	sql = "SELECT *" & vbCrLF & _
				"FROM (" & sub1 & "), T_SHOKUGYOU" & vbCrLF & _
				"WHERE MM_shokugyou(+) = SG_shokugyou_code" & vbCrLF & _
				"ORDER BY SG_seq_no"
	Set ds = CreateDynaset(sql, ORADYN_READONLY)

	n = 0
	s = 0
	Do Until ds.EOF
		If Not s = ds("SG_seq_no").value Then
			n = n + 1
			shokugyou_name(n) = ds("SG_shokugyou_name").value
		End If
		If Not IsNull(ds(1).value) Then
			i = ds(1).value
			shokugyou(i, n) = Number(ds(2).value)
		End If
		s = ds("SG_seq_no").value
		ds.MoveNext
	Loop

	For i = 1 To 12
		For n = 1 To s_count
			objExcelSheet4.Cells(n + 21, 1).Value = shokugyou_name(n)
			objExcelSheet4.Cells(n + 21, i + 1).Value = shokugyou(i,n)
			objExcelSheet4.Cells(n + 21, 14).Value = "= sum(RC[-12]:RC[-1])"
		Next
			objExcelSheet4.Cells(n + 21, i + 1).Formula = "= sum(R[-" & s_count & "]C:R[-1]C)"
	Next
	objExcelSheet4.Cells(n + 21, 1).Value = "計"
	objExcelSheet4.Cells(n + 21, 14).Value = "= sum(RC[-12]:RC[-1])"

	Set objXLChart = objExcelSheet4.ChartObjects(2).Chart

'= データ範囲指定 =
	objXLChart.SetSourceData objExcelSheet4.Range(objExcelSheet4.Cells(22, 1),objExcelSheet4.Cells(s_count + 21, 13))

'= 罫線 =
	objExcelSheet4.Range(objExcelSheet4.Cells(22, 1), objExcelSheet4.Cells(s_count + 22, 14)).Borders.LineStyle = xlContinuous

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
	Response.AddHeader "Content-Disposition", "inline; filename=""taikai3.xls"""
	Response.BinaryWrite barray
End Sub

'=== メイン処理 ===
Dim sql, ds
Dim hon(12), taikai(12), seibetsu(12, 2), shokugyou(12, 99), nendai(12, 13)
Dim s_year, temp, objXLChart, s_count, shokugyou_name(99), shokugyou_id(99)

	s_year = Request.Form("s_year")

	If IsEmpty(s_year) Then
		Response.Redirect "index.asp"
	End If

	temp = "C:\TEMP\" & Session.SessionID & ".xls"

	sql = "SELECT *" & vbCrLf & _
				"FROM T_SHOKUGYOU" & vbCrLf & _
				"ORDER BY SG_seq_no"
	Set ds = CreateDynaset(sql, ORADYN_READONLY)
	s_count = ds.RecordCount

	ReadData s_year
	WriteExcel temp
	OutputExcel temp
%>