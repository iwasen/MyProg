<%
'=== クリックカウント用URL名生成 ===
Function ClickCountURL(ct_id)
	ClickCountURL = "http://www.haimail.net/ct/c.asp?" & ct_id & "-%CLICK%"
End Function

'=== URLの終了位置検索 ===
Function SearchUrlEnd(pos, body)
	SearchUrlEnd = FindStr(pos, body, " 　""'>" & vbCrLf)
	If SearchUrlEnd = 0 Then
		SearchUrlEnd = Len(body)
	End If
End Function

'=== 文字検索 ===
Private Function FindStr(pos, str, find)
	Dim i, sz

	FindStr = 0
	sz = Len(str)
	For i = pos To sz
		If InStr(find, Mid(str, i, 1)) > 0 Then
			FindStr = i
			Exit For
		End If
	Next
End Function
%>