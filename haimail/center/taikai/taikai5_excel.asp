<!--#INCLUDE FILE="../inc/common.asp"-->
<!--#INCLUDE FILE="../inc/sub.asp"-->
<!--#INCLUDE FILE="../inc/database.asp"-->
<!--#INCLUDE FILE="../inc/login.asp"-->
<%
'=== DBよりデータ抽出 ===
Private Sub ReadData(start_ym, end_ym)
	Dim sql, ds, i, m, d

	For i = 1 To 23
		taikai(i) = 0
	Next

	sql = "SELECT MM_regist_date,TK_taikai_date" & vbCrLf & _
				"FROM T_TAIKAI, T_MEMBER" & vbCrLf & _
				"WHERE TK_member_id = MM_member_id AND TO_CHAR(MM_regist_date,'YYYYMM') BETWEEN '" & start_ym & "' AND '" & end_ym & "'" & vbCrLf & _
				"ORDER BY TK_member_id"
	Set ds = CreateDynaset(sql, ORADYN_READONLY)

	Do Until ds.EOF
		d = DateDiff("d",ds("MM_regist_date").value,ds("TK_taikai_date").value)+1
		m = DateDiff("m",ds("MM_regist_date").value,ds("TK_taikai_date").value)
		If d =< 10 Then
			i = d
			taikai(i) = taikai(i) + 1
		Else
			If d =< 365 Then
				If Day(ds("TK_taikai_date").value) >= Day(ds("MM_regist_date").value) Then
					taikai(m+11) = taikai(m+11) + 1
				Else
					taikai(m+10) = taikai(m+10) + 1
				End If
			Else
					taikai(23) = taikai(23) + 1
			End If
		End If
		ds.MoveNext
	Loop
End Sub

'=== エクセルにデータ書き込み ===
Private Sub WriteExcel(temp)
	Dim objExcelApp, objExcelBook, objExcelSheets, objExcelSheet
	Dim path, i, filename, dt

	filename = "taikai5.xlt"
	path = Server.MapPath(filename)

	Set objExcelApp = Server.CreateObject("Excel.Application")
	objExcelApp.DisplayAlerts = False
	objExcelApp.Visible = False

	objExcelApp.Workbooks.Open(path)

	Set objExcelBook = objExcelApp.ActiveWorkbook
	Set objExcelSheets = objExcelBook.Worksheets
	Set objExcelSheet = objExcelBook.Sheets(1)

	objExcelSheet.Cells(4, 1).Value = s_year & "年" & s_month & "月～" & e_year & "年" & e_month & "月"

	For i = 1 To 23
			objExcelSheet.Cells(22, i + 1).Value = taikai(i)
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
	Response.AddHeader "Content-Disposition", "inline; filename=""taikai5.xls"""
	Response.BinaryWrite barray
End Sub

'=== メイン処理 ===
Dim taikai(23)

	Dim s_year, s_month, e_year, e_month, start_ym, end_ym, temp

	s_year = Request.Form("s_year")
	s_month = Right("0" & Request.Form("s_month"), 2)
	e_year = Request.Form("e_year")
	e_month = Right("0" & Request.Form("e_month"), 2)

	start_ym = s_year & s_month
	end_ym = e_year & e_month

	If IsEmpty(start_ym) Or IsEmpty(end_ym) Then
		Response.Redirect "index.asp"
	End If

	temp = "C:\TEMP\" & Session.SessionID & ".xls"

	ReadData start_ym, end_ym
	WriteExcel temp
	OutputExcel temp
%>