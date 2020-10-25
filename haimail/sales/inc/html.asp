<%
'=== HTML要素名取得 ===
Function HtmlElementName(s, n)
	Dim str, c

	SkipSpace s, n

	str = ""
	Do While True
		c = Mid(s, n, 1)
		Select Case c
		Case " ", "=", ">", ""
			Exit Do
		Case Else
			str = str & c
		End Select
		n = n + 1
	Loop

	HtmlElementName = LCase(str)
End Function

'=== HTML要素値取得 ===
Function HtmlElementValue(s, n)
	Dim str, c, quote, quote2

	str = ""

	SkipSpace s, n

	If Mid(s, n, 1) = "=" Then
		n = n + 1
		SkipSpace s, n
		Do While True
			c = Mid(s, n, 1)
			If c = "" Then
				Exit Do
			End If

			If quote = "" Then
				Select Case c
				Case """", "'"
					c = ""
					quote2 = False
					quote = c
				Case ">", " "
					Exit Do
				End Select
			Else
				If quote2 = False Then
					If c = quote Then
						quote2 = True
						c = ""
					End If
				Else
					If c = quote Then
						quote2 = False
					Else
						n = n - 1
						quote = ""
					End If
				End If
			End If
			str = str & c
			n = n + 1
		Loop
	End If

	HtmlElementValue = str
End Function

'=== スペース読み飛ばし ===
Sub SkipSpace(s, n)
	Do While Mid(s, n, 1) = " "
		n = n + 1
	Loop
End Sub
%>