<!--#INCLUDE FILE="../../inc/common.asp"-->
<!--#INCLUDE FILE="../../inc/sub.asp"-->
<!--#INCLUDE FILE="../../inc/database.asp"-->
<!--#INCLUDE FILE="../../inc/login.asp"-->
<%
'******************************************************
' System :はいめーる・net事務局用ページ
' Content:日別送信／受信推移出力処理
'******************************************************

Const xlEdgeLeft = 7
Const xlEdgeTop = 8
Const xlEdgeBottom = 9
Const xlEdgeRight = 10
Const xlInsideHorizontal = 12
Const xlLineStyleNone = -4142

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
	Dim path, row, col, dt, i_month, count(2), decode

	path = Server.MapPath("excel/send_recv.xlt")

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

		'送信テーブル
		decode = "DECODE(SM_mail_kind,'S',DECODE(JB_mail_type,'LT',0,'HG',0,'CR',1,'TG',1,'OH',0,2),2)"
		sql = "SELECT SUM(SM_real_send) SM_count,TRUNC(SM_start_date,'DD') SM_start_ymd," & decode & " mail_kind" & vbCrLf & _
					"FROM T_SEND_MAIL,T_JOB" & vbCrLf & _
					"WHERE TO_CHAR(SM_start_date,'YYYYMM')='" & s_year & Right("0" & s_month, 2) & "' AND SM_mail_id=JB_mail_id(+)" & vbCrLf & _
					"GROUP BY " & decode & ",TRUNC(SM_start_date,'DD')" & vbCrLf & _
					"ORDER BY SM_start_ymd"
		Set ds = CreateDynaset(sql, ORADYN_READONLY)

		GetPosition objWorkBook, "TABLE_SEND", row, col
		dt = CDate(s_year & "/" & s_month & "/1")
		i_month = CInt(s_month)
		Do While Month(dt) = i_month
			count(0) = 0
			count(1) = 0
			count(2) = 0
			Do Until ds.EOF
				If ds("SM_start_ymd").Value <> dt Then
					Exit Do
				End If
				count(CInt(ds("mail_kind").Value)) = ds("SM_count").Value
				ds.MoveNext
			Loop
			col = col + 1
			.Cells(row + 0, col).Value = count(0)
			.Cells(row + 1, col).Value = count(1)
			.Cells(row + 2, col).Value = count(2)

			dt = DateAdd("d", 1, dt)
		Loop

		'受信テーブル
		sql = "SELECT COUNT(DISTINCT(RM_member_id||RM_mail_id)) RM_count,TRUNC(RM_recv_date,'DD') RM_recv_ymd," & decode & " mail_kind" & vbCrLf & _
					"FROM T_RECV_MAIL,T_SEND_MAIL,T_JOB" & vbCrLf & _
					"WHERE TO_CHAR(SM_start_date,'YYYYMM')='" & s_year & Right("0" & s_month, 2) & "' AND SM_mail_id=JB_mail_id(+) AND SM_mail_id=RM_mail_id AND (RM_enq_valid='1' OR RM_imp_valid='1')" & vbCrLf & _
					"GROUP BY " & decode & ",TRUNC(RM_recv_date,'DD')" & vbCrLf & _
					"ORDER BY RM_recv_ymd"
		Set ds = CreateDynaset(sql, ORADYN_READONLY)

		GetPosition objWorkBook, "TABLE_RECV", row, col
		dt = CDate(s_year & "/" & s_month & "/1")
		i_month = CInt(s_month)
		Do While Month(dt) = i_month
			count(0) = 0
			count(1) = 0
			count(2) = 0
			Do Until ds.EOF
				If ds("RM_recv_ymd").Value <> dt Then
					Exit Do
				End If
				count(CInt(ds("mail_kind").Value)) = ds("RM_count").Value
				ds.MoveNext
			Loop
			col = col + 1
			.Cells(row + 0, col).Value = count(0)
			.Cells(row + 1, col).Value = count(1)
			.Cells(row + 2, col).Value = count(2)

			dt = DateAdd("d", 1, dt)
		Loop
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
	Response.AddHeader "Content-Disposition", "inline; filename=""日別送信／受信推移.xls"""
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