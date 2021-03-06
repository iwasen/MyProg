<%
'=== 日時をYYYY/MM/DD HH:MMの文字列に変換 ===
Function format_datetime(tm)
	If IsNull(tm) Then
		format_datetime = "-"
	Else
		format_datetime = Year(tm) & "/" & Right("0" & Month(tm), 2) & "/" & Right("0" & Day(tm), 2) & " " & _
			Right("0" & Hour(tm), 2) & ":" & Right("0" & Minute(tm), 2)
	End If
End Function

'=== 日時をYYYY/MM/DDの文字列に変換 ===
Function format_date(tm)
	If IsNull(tm) Then
		format_datetime = "-"
	Else
		format_date = Year(tm) & "/" & Right("0" & Month(tm), 2) & "/" & Right("0" & Day(tm), 2)
	End If
End Function
%>