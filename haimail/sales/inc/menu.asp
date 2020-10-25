<%
Sub DispMenu(cols, rows, menu, href)
	Dim i, j
%>
<SCRIPT LANGUAGE=javascript>
<!--
function onMouseOver(c) {
	c.style.color = "yellow";
}
function onMouseOut(c) {
	c.style.color = "white";
}
//-->
</SCRIPT>

<table align="center" border=0 cellpadding=0 cellspacing=0 width="90%">
<tr>
<%
	For i = 1 To cols
		If i <> 1 Then
			Writeln "<td width='1%'></td>"
		End If
		Writeln "<td width='10%' valign='top'>"
		Writeln "<table border=0 cellpadding=4 cellspacing=15 width='100%'>"
		For j = 1 To rows
			If menu(i, j) <> "" Then
				Writeln "<tr>"
				If href(i, j) <> "" Then
					Writeln "<a href='" & href(i, j) & "'>"
					Writeln "<td align='center' class='menu' onMouseOver='onMouseOver(this)' onMouseOut='onMouseOut(this)'><nobr>" & menu(i, j) & "</nobr></td>"
					Writeln "</a>"
				Else
					Writeln "<td align='center' class='menu'><nobr>" & menu(i, j) & "</nobr></td>"
				End If
				Writeln "</tr>"
			End If
		Next
		Writeln "</table>"
		Writeln "</td>"
	Next
%>
</tr>
</table>
<%
End Sub
%>