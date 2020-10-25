<%
'******************************************************
' System :アンケート集計サービス
' Content:リスト表示関数
'******************************************************

Dim sort_col, sort_dir

'=== ORDER BY句生成 ===
Function sort_order(def_col, def_dir, col_name)
	Dim order, ary

	sort_col = Request.QueryString("sort_col")
	sort_dir = Request.QueryString("sort_dir")

	If IsEmpty(sort_col) Then
		sort_col = def_col
	Else
		sort_col = CInt(sort_col)
	End If

	If IsEmpty(sort_dir) Then
		sort_dir = def_dir
	Else
		sort_dir = CInt(sort_dir)
	End If

	order = Split(col_name, "/")(sort_col - 1)
	If sort_dir = 1 Then
		ary = Split(order, ",")
		ary(0) = ary(0) & " desc"
		order = Join(ary, ",")
	End If

	sort_order = "ORDER BY " & order
End Function

'=== ソートリストヘッダ ===
Function sort_header(column, text)
	Dim bgcolor, dir, title

	If column <> 0 Then
		If column = sort_col Then
			If sort_dir = 0 Then
				bgcolor = " bgcolor='skyblue'"
				dir = 1
				title = "クリックすると" & text & "で降順にソートします"
			Else
				bgcolor = " bgcolor='pink'"
				dir = 0
				title = "クリックすると" & text & "で昇順にソートします"
			End If
		Else
			dir = 0
			title = "クリックすると" & text & "で昇順にソートします"
		End If
		echo "<th" & bgcolor & " onclick='sort_list(" & column & "," & dir & ")' style='cursor:hand' title='" & title & "'>" & text & "</th>"
	Else
		echo "<th>" & text & "</th>"
	End If
End Function

Function disp_limit()
	If Request("displine") <> "" Then
		disp_limit = CLng(Request("displine"))
	Else
		disp_limit = 50
	End If
End Function
%>