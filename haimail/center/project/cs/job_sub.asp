<%
'=== ジョブ種別表示 ===
Private Sub ShowJobType(job_type)
	Select Case job_type
	Case "0"
		Response.Write "発信JOB"
	Case "1"
		Response.Write "アンケートレスポンスJOB"
	Case "2"
		Response.Write "クリックレスポンスJOB"
	End Select
End Sub

'=== 日付表示 ===
Private Sub ShowDate(dt, h)
	If Not IsNull(dt) Then
		Response.Write Year(dt) & "/" & Month(dt) & "/" & Day(dt)
		If h Then
			If Minute(dt) = 0 Then
				If Hour(dt) < 12 Then
					Response.Write " AM" & Hour(dt) & ":00"
				Else
					Response.Write " PM" & Hour(dt) & ":00"
				End If
			Else
				Response.Write " 終日"
			End If
		End If
	Else
		Response.Write "指定なし"
	End If
End Sub

'=== 送信対象表示 ===
Private Sub ShowVirginMail(virgin_mail)
	Select Case virgin_mail
	Case "0"
		Response.Write "既存会員のみ"
	Case "1"
		Response.Write "新規会員のみ"
	Case "2"
		Response.Write "既存会員と新規会員"
	Case Else
		Response.Write "指定なし"
	End Select
End Sub

'=== 発信タイミング表示 ===
Private Sub ShowSendTiming(send_timing, send_timing_h, send_timing_d)
	Select Case send_timing
	Case "1"
		Response.Write "受信後、発信開始日から一斉に。あとはその都度、個別送信"
	Case "2"
		Response.Write "受信時間から " & send_timing_h & " 時間後に個別送信"
	Case "3"
		Response.Write "受信日から " & send_timing_d & " 日後に個別送信"
	Case Else
		Response.Write "指定なし"
	End Select
End Sub

'=== AND/OR表示 ===
Private Sub ShowAndOr(and_or)
	Select Case and_or
	Case "A"
		Response.Write "AND"
	Case "O"
		Response.Write "OR"
	End Select
End Sub

'=== 興味ジャンル表示 ===
Private Sub ShowKyoumiGenre(kyoumi_genre_shitei, kyoumi_genre_andor, kyoumi_genre1, kyoumi_genre2)
	Dim i, n, sql, ds

	If kyoumi_genre_shitei = "1" Then
		n = NVL(Len(kyoumi_genre1), 0)
		If Len(kyoumi_genre2) > n Then
			n = Len(kyoumi_genre2)
		End If

		For i = 1 To n
			If Mid(kyoumi_genre1, i, 1) = "1" Or Mid(kyoumi_genre2, i, 1) = "1" Then
				sql = "SELECT KG_item_text FROM T_KYOUMI_GENRE WHERE KG_index=" & i
				Set ds = CreateDynaset(sql, ORADYN_READONLY)
				If Not ds.EOF Then
					Response.Write ds(0).Value & "　"

					If Mid(kyoumi_genre1, i, 1) = "1" Then
						If Mid(kyoumi_genre2, i, 1) = "1" Then
							Response.Write "かなり／普通"
						Else
							Response.Write "普通"
						End If
					Else
							Response.Write "かなり"
					End If

					Response.Write "<br>"
				End If
			End If
		Next
	Else
		Response.Write "指定なし"
	End If
End Sub

'=== 性別表示 ===
Private Sub ShowSeibetsu(seibetsu_m, seibetsu_f)
	If seibetsu_m = "1" Or seibetsu_f = "1" Then
		If seibetsu_m = "1" Then
			Response.Write "男"
			If seibetsu_f = "1" Then
				Response.Write "・"
			End If
		End If
		If seibetsu_f = "1" Then
			Response.Write "女"
		End If
	Else
		Response.Write "指定なし"
	End If
End Sub

'=== 年齢表示 ===
Private Sub ShowNenrei(nenrei_from, nenrei_to)
	If nenrei_from <> "" Or nenrei_to <> "" Then
		If nenrei_from <> "" Then
			Response.Write nenrei_from & "才"
		End If
		Response.Write "～"
		If nenrei_to <> "" Then
			Response.Write nenrei_to & "才"
		End If
	Else
		Response.Write "指定なし"
	End If
End Sub

'=== 未既婚表示 ===
Private Sub ShowMikikon(mikikon_mi, mikikon_ki)
	If mikikon_mi = "1" Or mikikon_ki = "1" Then
		If mikikon_mi = "1" Then
			Response.Write "独身"
			If mikikon_ki = "1" Then
				Response.Write "・"
			End If
		End If
		If mikikon_ki = "1" Then
			Response.Write "既婚"
		End If
	Else
		Response.Write "指定なし"
	End If
End Sub

'=== 職業表示 ===
Private Sub ShowShokugyou(shokugyou)
	Dim sql, ds, arry, item

	If shokugyou <> "" Then
		sql = ""
		arry = Split(shokugyou, ",")
		For Each item In arry
			If sql <> "" Then
				sql = sql & ","
			End If
			sql = sql & "'" & item & "'"
		Next
		If sql <> "" Then
			sql = "SELECT SG_shokugyou_name FROM T_SHOKUGYOU WHERE SG_shokugyou_code IN (" & sql & ")"
			Set ds = CreateDynaset(sql, ORADYN_READONLY)
			Do Until ds.EOF
				Response.Write ds(0).Value & "<br>"
				ds.MoveNext
			Loop
		End If
	Else
		Response.Write "指定なし"
	End If
End Sub

'=== 住所表示 ===
Private Sub ShowJuusho(kyojuu_shitei, kyojuu)
	If kyojuu_shitei = "1" Then
		Response.Write Replace(Replace(kyojuu, "/" , ""), ",", "<br>")
	Else
		Response.Write "指定なし"
	End If
End Sub

'=== よく行く店表示 ===
Private Sub ShowYokuikumise(yokuikumise_shitei, yokuikumise)
	Dim n, i, sql, ds

	If yokuikumise_shitei = "1" Then
		n = Len(yokuikumise)
		For i = 1 To n
			If Mid(yokuikumise, i, 1) = "1" Then
				sql = "SELECT YM_item_text FROM T_YOKUIKU_MISE WHERE YM_index=" & i
				Set ds = CreateDynaset(sql, ORADYN_READONLY)
				If Not ds.EOF Then
					Response.Write ds(0).Value & "<br>"
				End If
			End If
		Next
	Else
		Response.Write "指定なし"
	End If
End Sub

'=== どっち派表示 ===
Private Sub ShowDocchiha(docchiha_shitei, docchiha)
	Dim n, i, sql, ds

	If docchiha_shitei = "1" Then
		n = Len(docchiha)
		For i = 1 To n
			If Mid(docchiha, i, 1) <> " " Then
				sql = "SELECT DH_item1_text,DH_item2_text FROM T_DOCCHI_HA WHERE DH_index=" & i
				Set ds = CreateDynaset(sql, ORADYN_READONLY)
				If Not ds.EOF Then
					Select Case Mid(docchiha, i, 1)
					Case "0"
						Response.Write "どちらでもない<br>"
					Case "1"
						Response.Write ds(0).Value & "<br>"
					Case "2"
						Response.Write ds(1).Value & "<br>"
					End Select
				End If
			End If
		Next
	Else
		Response.Write "指定なし"
	End If
End Sub

'=== 条件文表示 ===
Private Sub ShowCondition(shitei, str)
	If shitei = "1" Then
		Response.Write str
	Else
		Response.Write "指定なし"
	End If
End Sub

'=== 条件文表示２ ===
Private Sub ShowCondition2(str)
	If str <> "" Then
		Response.Write str
	Else
		Response.Write "指定なし"
	End If
End Sub

'=== メンバーリスト表示 ===
Private Sub ShowMemberList(member_list_shitei, member_list)
	Dim arry, count, item

	If member_list_shitei = "1" Then
		count = 0
		If member_list <> "" Then
			arry = Split(member_list, ".")
			For Each item In arry
				If item <> "" Then
					count = count + 1
				End If
			Next
		End If
		Response.Write count & " 名"
	Else
		Response.Write "指定なし"
	End If
End Sub

'=== HTMLメール受信可否 ===
Private Sub ShowHtmlMail(html_mail_shitei, html_mail)
	If html_mail_shitei = "1" Then
		Select Case html_mail
		Case "0"
			Response.Write "受信不可"
		Case "1"
			Response.Write "受信可能"
		End Select
	Else
		Response.Write "指定なし"
	End If
End Sub
%>