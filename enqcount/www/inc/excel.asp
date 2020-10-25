<%
Const xlInsideHorizontal = 12

'=== •\ƒf[ƒ^ƒZƒbƒg ===
Sub SetTableData(objWorkSheet, data, row, col, nrow, ncol, fit)
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

'=== ƒZƒ‹ˆÊ’uŽæ“¾ ===
Sub GetPosition(objWorkBook, name, row, col)
	Dim objName

	Set objName = objWorkBook.Names(name)
	col = objName.RefersToRange.Column
	row = objName.RefersToRange.row
End Sub

'=== ”¼Šp•ÏŠ· ===
Function ToHankaku(str)
	Const zenkaku = _
			"‚O‚P‚Q‚R‚S‚T‚U‚V‚W‚XD|—‚`‚a‚b‚c‚d‚e‚f‚g‚h‚i‚j‚k‚l‚m‚n‚o‚p‚q‚r‚s‚t‚u‚v‚w‚x‚y‚‚‚‚ƒ‚„‚…‚†‚‡‚ˆ‚‰‚Š‚‹‚Œ‚‚Ž‚‚‚‘‚’‚“‚”‚•‚–‚—‚˜‚™‚š"
	Const hankaku = _
			"0123456789.-@ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz"
	Dim i, l

	ToHankaku = str
	l = Len(zenkaku)
	For i = 1 To l
		ToHankaku = Replace(ToHankaku, Mid(zenkaku, i, 1), Mid(hankaku, i, 1))
	Next
End Function

'=== ƒGƒNƒZƒ‹ƒtƒ@ƒCƒ‹‚ðo—Í ===
Sub OutputExcel(tempfile, filename)
	Dim basp, barray, fso

	Set basp= Server.CreateObject("basp21")
	barray = basp.BinaryRead(tempfile)

	Set fso = CreateObject("Scripting.FileSystemObject")
	fso.DeleteFile tempfile

	Response.Expires = 0
	Response.Clear

	Response.ContentType = "application/vnd.ms-excel"
	Response.AddHeader "Content-Disposition", "inline; filename=""" & filename & """"
	Response.BinaryWrite barray
End Sub

'=== ƒeƒ“ƒ|ƒ‰ƒŠƒtƒ@ƒCƒ‹–¼Žæ“¾ ===
Function GetTempFile()
	'GetTempFile = Request.ServerVariables("TEMP") & "\" & session.SessionID & ".xls"
	GetTempFile = "C:\TEMP\" & session.SessionID & ".xls"
End Function
%>