<%
'=== ジャンル表示 ===
Function category_name(cn)
	Select Case cn
		Case "01"
			category_name = "コンビニ"
		Case "02"
			category_name = "スーパー"
		Case "03"
			category_name = "その他(業態)"
		Case "04"
			category_name = "その他(店)"
	End Select
End Function
%>