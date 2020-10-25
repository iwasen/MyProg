<%
'=== •\Ž¦s” ===
Sub select_displine(selected)
	Dim i

	echo "<option " & value_selected(0, selected) & ">- ‘S‚Ä -</option>"
	For i = 10 To 100 Step 10
		echo "<option " & value_selected(i, selected) & ">" & i & "</option>"
	Next
End Sub
%>