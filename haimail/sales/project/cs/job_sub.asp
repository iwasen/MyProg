<%
'=== �W���u��ʕ\�� ===
Private Sub ShowJobType(job_type)
	Select Case job_type
	Case "0"
		Response.Write "���MJOB"
	Case "1"
		Response.Write "�A���P�[�g���X�|���XJOB"
	Case "2"
		Response.Write "�N���b�N���X�|���XJOB"
	End Select
End Sub

'=== ���t�\�� ===
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
				Response.Write " �I��"
			End If
		End If
	Else
		Response.Write "�w��Ȃ�"
	End If
End Sub

'=== ���M�Ώە\�� ===
Private Sub ShowVirginMail(virgin_mail)
	Select Case virgin_mail
	Case "0"
		Response.Write "��������̂�"
	Case "1"
		Response.Write "�V�K����̂�"
	Case "2"
		Response.Write "��������ƐV�K���"
	Case Else
		Response.Write "�w��Ȃ�"
	End Select
End Sub

'=== ���M�^�C�~���O�\�� ===
Private Sub ShowSendTiming(send_timing, send_timing_h, send_timing_d)
	Select Case send_timing
	Case "1"
		Response.Write "��M��A���M�J�n�������ĂɁB���Ƃ͂��̓s�x�A�ʑ��M"
	Case "2"
		Response.Write "��M���Ԃ��� " & send_timing_h & " ���Ԍ�Ɍʑ��M"
	Case "3"
		Response.Write "��M������ " & send_timing_d & " ����Ɍʑ��M"
	Case Else
		Response.Write "�w��Ȃ�"
	End Select
End Sub

'=== AND/OR�\�� ===
Private Sub ShowAndOr(and_or)
	Select Case and_or
	Case "A"
		Response.Write "AND"
	Case "O"
		Response.Write "OR"
	End Select
End Sub

'=== �����W�������\�� ===
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
					Response.Write ds(0).Value & "�@"

					If Mid(kyoumi_genre1, i, 1) = "1" Then
						If Mid(kyoumi_genre2, i, 1) = "1" Then
							Response.Write "���Ȃ�^����"
						Else
							Response.Write "����"
						End If
					Else
							Response.Write "���Ȃ�"
					End If

					Response.Write "<br>"
				End If
			End If
		Next
	Else
		Response.Write "�w��Ȃ�"
	End If
End Sub

'=== ���ʕ\�� ===
Private Sub ShowSeibetsu(seibetsu_m, seibetsu_f)
	If seibetsu_m = "1" Or seibetsu_f = "1" Then
		If seibetsu_m = "1" Then
			Response.Write "�j"
			If seibetsu_f = "1" Then
				Response.Write "�E"
			End If
		End If
		If seibetsu_f = "1" Then
			Response.Write "��"
		End If
	Else
		Response.Write "�w��Ȃ�"
	End If
End Sub

'=== �N��\�� ===
Private Sub ShowNenrei(nenrei_from, nenrei_to)
	If nenrei_from <> "" Or nenrei_to <> "" Then
		If nenrei_from <> "" Then
			Response.Write nenrei_from & "��"
		End If
		Response.Write "�`"
		If nenrei_to <> "" Then
			Response.Write nenrei_to & "��"
		End If
	Else
		Response.Write "�w��Ȃ�"
	End If
End Sub

'=== �������\�� ===
Private Sub ShowMikikon(mikikon_mi, mikikon_ki)
	If mikikon_mi = "1" Or mikikon_ki = "1" Then
		If mikikon_mi = "1" Then
			Response.Write "�Ɛg"
			If mikikon_ki = "1" Then
				Response.Write "�E"
			End If
		End If
		If mikikon_ki = "1" Then
			Response.Write "����"
		End If
	Else
		Response.Write "�w��Ȃ�"
	End If
End Sub

'=== �E�ƕ\�� ===
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
		Response.Write "�w��Ȃ�"
	End If
End Sub

'=== �Z���\�� ===
Private Sub ShowJuusho(kyojuu_shitei, kyojuu)
	If kyojuu_shitei = "1" Then
		Response.Write Replace(Replace(kyojuu, "/" , ""), ",", "<br>")
	Else
		Response.Write "�w��Ȃ�"
	End If
End Sub

'=== �悭�s���X�\�� ===
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
		Response.Write "�w��Ȃ�"
	End If
End Sub

'=== �ǂ����h�\�� ===
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
						Response.Write "�ǂ���ł��Ȃ�<br>"
					Case "1"
						Response.Write ds(0).Value & "<br>"
					Case "2"
						Response.Write ds(1).Value & "<br>"
					End Select
				End If
			End If
		Next
	Else
		Response.Write "�w��Ȃ�"
	End If
End Sub

'=== �������\�� ===
Private Sub ShowCondition(shitei, str)
	If shitei = "1" Then
		Response.Write str
	Else
		Response.Write "�w��Ȃ�"
	End If
End Sub

'=== �������\���Q ===
Private Sub ShowCondition2(str)
	If str <> "" Then
		Response.Write str
	Else
		Response.Write "�w��Ȃ�"
	End If
End Sub

'=== �����o�[���X�g�\�� ===
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
		Response.Write count & " ��"
	Else
		Response.Write "�w��Ȃ�"
	End If
End Sub

'=== HTML���[����M�� ===
Private Sub ShowHtmlMail(html_mail_shitei, html_mail)
	If html_mail_shitei = "1" Then
		Select Case html_mail
		Case "0"
			Response.Write "��M�s��"
		Case "1"
			Response.Write "��M�\"
		End Select
	Else
		Response.Write "�w��Ȃ�"
	End If
End Sub
%>