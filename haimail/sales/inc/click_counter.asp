<%
'=== �N���b�N�J�E���g�pURL������ ===
Function ClickCountURL(ct_id)
	ClickCountURL = "http://www.haimail.net/ct/c.asp?" & ct_id & "-%CLICK%"
End Function

'=== URL�̏I���ʒu���� ===
Function SearchUrlEnd(pos, body)
	SearchUrlEnd = FindStr(pos, body, " �@""'>" & vbCrLf)
	If SearchUrlEnd = 0 Then
		SearchUrlEnd = Len(body)
	End If
End Function

'=== �������� ===
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