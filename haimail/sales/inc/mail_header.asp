<%
Const HEADER_COMPANY = "%COMPANY%"
Const HEADER_TANTOUSHA = "%TANTOUSHA%"
Const HEADER_LIMIT = "%LIMIT%"
Const FOOTER_KYOUMI = "%KYOUMI_GENRE%"
Const IMPRESSION_LIMIT = "%LIMIT%"

'=== �Y�ؓ��t�擾 ===
Private Function LimitDateFormat(dt)
	Dim str, h, m, s

	If IsNull(dt) Then
		str = "�Ȃ�"
	Else
		h = Hour(dt)
		m = Minute(dt)

		str = DateFormat(dt) & "�i" & GetWeekName(dt) & "�j"
		If h <> 0 Or m <> 0 Then
			If m = 0 Then
				If h < 12 Then
					str = str & "am"
				Else
					str = str & "pm"
					h = h - 12
				End If
				str = str & Right("0" & h, 2) & ":" & Right("0" & m, 2)
			End If
		End If
	End If

	LimitDateFormat = str
End Function

'=== ���z���Y�ؓ��t�擾 ===
Private Function ImpDateFormat(dt)
	Dim str, h, m, s

	If IsNull(dt) Then
		str = "�Ȃ�"
	Else
		str = ToZenkaku(Year(dt)) & "�N" & ToZenkaku(Month(dt)) & "��" & ToZenkaku(Day(dt)) & "���i" & GetWeekName(dt) & "�j"
		h = Hour(dt)
		m = Minute(dt)
		If h <> 0 Or m <> 0 Then
			If m = 0 Then
				If h < 12 Then
					str = str & "����"
				Else
					str = str & "����"
					h = h - 12
				End If
				str = str & ToZenkaku(h) & "�F" & ToZenkaku(Right("0" & m, 2))
			End If
		End If
	End If

	ImpDateFormat = str
End Function

'=== �����W�������w��擾 ===
Private Function KyoumiGenreFormat(kyoumi_genre1, kyoumi_genre2)
	Const START_TEXT = "���̃��[���́A"
	Const END_TEXT = "�ɋ����̂�����ɂ����肵�܂����B"
	Const LEADER = ":�@"
	Const MAX_COL = 72
	Dim sql, ds, str, text, bytes, i, col, n

	str = "���̃��[���́A"
	col = StrBytes(LEADER) + 2 + StrBytes(str)

	sql = ""
	n = Len(kyoumi_genre1)
	If Len(kyoumi_genre2) > n Then
		n = Len(kyoumi_genre2)
	End If
	For i = 1 To n
		If Mid(kyoumi_genre1, i, 1) = "1" Or Mid(kyoumi_genre2, i, 1) = "1" Then
			If sql <> "" Then
				sql = sql & ","
			End If
			sql = sql & i
		End If
	Next

	If sql <> "" Then
		sql = "SELECT KG_item_text FROM T_KYOUMI_GENRE WHERE KG_index IN (" & sql & ") ORDER BY KG_index"
		Set ds = CreateDynaset(sql, ORADYN_READONLY)
		Do Until ds.Eof
			text = ds(0).Value
			bytes = StrBytes(text)

			If col + bytes + 4 > MAX_COL Then
				str = str & vbCrLf & LEADER
				col = StrBytes(LEADER)
			End If

			str = str & "�u" & text & "�v"
			col = col + bytes + 4
			ds.MoveNext
		Loop
	End If

	If col + StrBytes(END_TEXT) > MAX_COL Then
		str = str & vbCrLf & LEADER & "�@"
	End If
	str = str & END_TEXT

	KyoumiGenreFormat = str
End Function

'=== �w�b�_�擾 ===
Private Function GetHeader(id, tantousha_id)
	Dim sql, ds, header, name

	sql = "SELECT MH_text FROM T_MAIL_HEADER WHERE MH_header_id=" & id
	Set ds = CreateDynaset(sql, ORADYN_READONLY)
	If Not ds.EOF Then
		header = ds(0).Value

		If InStr(header, HEADER_COMPANY) > 0 Or InStr(header, HEADER_TANTOUSHA) > 0 Then
			sql = "SELECT TS_name1,TS_name2,CM_name" & vbCrLf & _
						"FROM T_TANTOUSHA,T_COMPANY" & vbCrLf & _
						"WHERE TS_tantousha_id='" & tantousha_id & "' AND TS_company_id=CM_company_id"
			Set ds = CreateDynaset(sql, ORADYN_READONLY)
			If Not ds.EOF Then
				header = Replace(header, HEADER_COMPANY, ds("CM_name").Value)

				name = ""
				If Not IsNull(ds("TS_name1").Value) Then
					name = name & ds("TS_name1").Value
				End If
				If Not IsNull(ds("TS_name2").Value) Then
					name = name & " " & ds("TS_name2").Value
				End If
				header = Replace(header, HEADER_TANTOUSHA, name)
			End If
		End If

		If InStr(header, HEADER_LIMIT) > 0 Then
			header = Replace(header, HEADER_LIMIT, LimitDateFormat(Null))
		End If
	End If

	GetHeader = header
End Function

'=== ���z���擾 ===
Private Function GetImpression(id, imp_limit)
	Dim sql, ds, impression

	sql = "SELECT MI_text FROM T_MAIL_IMP WHERE MI_impression_id=" & id
	Set ds = CreateDynaset(sql, ORADYN_READONLY)
	If Not ds.EOF Then
		impression = ds(0).Value

		If InStr(impression, IMPRESSION_LIMIT) > 0 Then
			impression = Replace(impression, IMPRESSION_LIMIT, ImpDateFormat(imp_limit))
		End If
	End If

	GetImpression = impression
End Function

'=== �t�b�^�擾 ===
Private Function GetFooter(id, kyoumi_genre1, kyoumi_genre2)
	Dim sql, ds, footer

	sql = "SELECT MF_text FROM T_MAIL_FOOTER WHERE MF_footer_id=" & id
	Set ds = CreateDynaset(sql, ORADYN_READONLY)
	If Not ds.EOF Then
		footer = ds(0).Value

		If InStr(footer, FOOTER_KYOUMI) > 0 Then
			footer = Replace(footer, FOOTER_KYOUMI, KyoumiGenreFormat(kyoumi_genre1, kyoumi_genre2))
		End If
	End If

	GetFooter = footer
End Function

'=== �{���Ƀw�b�_�A���z���A�t�b�^��t�� ===
Function AddMailHeader(body, header, impression, footer)
	AddMailHeader = body

	'�w�b�_�t��
	If Len(header) > 0 Then
		AddMailHeader = LaxxxrLf(header) & vbCrLf & AddMailHeader
	End If

	'���z���t��
	If Len(impression) > 0 Then
		AddMailHeader = AddMailHeader & vbCrLf & LaxxxrLf(impression)
	End If

	'�t�b�^�t��
	If Len(footer) > 0 Then
		AddMailHeader = AddMailHeader & vbCrLf & LaxxxrLf(footer)
	End If
End Function
%>