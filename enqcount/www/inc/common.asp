<%@ Language=VBScript %>
<% Option Explicit %>
<%
Server.ScriptTimeout = 3600

Const MUST_ITEM = "<sup><font color='green'>*</font></sup>"
Const LIST_TABLE = "border=1 cellspacing=0 cellpadding=1 bordercolorlight='#f0f0f0' frame='box'"

'=== HTML Encode ===
Function HTMLEncode(text)
	If IsNull(text) Then
		HTMLEncode = ""
	Else
		HTMLEncode = Server.HTMLEncode(text)
	End If
End Function

'=== value 出力 ===
Function value(v)
	value = "value=""" & HTMLEncode(Trim(v)) & """"
End Function

'=== value, selected 出力 ===
Function value_selected(value, selected)
	If value = selected Then
		value_selected = "value='" & value & "' selected"
	Else
		value_selected = "value='" & value & "'"
	End If
End Function

'=== disabled 出力 ===
Function disabled(flag)
	If flag Then
		disabled = " disabled"
	End If
End Function

Sub echo(str)
	Response.Write str
	Response.Write vbCrLf
End Sub

'=== 相対ルートパス取得 ===
Function GetRoot()
	Dim n, path

	n = Len(Request.ServerVariables("APPL_PHYSICAL_PATH"))

	GetRoot = ""
	path = Request.ServerVariables("PATH_TRANSLATED")
	Do While True
		n = InStr(n + 1, path, "\")
		If n > 0 Then
			GetRoot = GetRoot & "../"
		Else
			Exit Do
		End If
	Loop
End Function
%>