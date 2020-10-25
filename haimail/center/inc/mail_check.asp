<!--#INCLUDE FILE="html.asp"-->
<%
Const ID_NUMBER = "★12345678/12345678☆"
Const ENQ_START = "（アンケートここから）"
Const ENQ_END = "（アンケートここまで）"
Const IMP_START = "（感想文ここから）"
Const IMP_END = "（感想文ここまで）"
Const ANS_START = "（回答はこの下の行から）"
Const ANS_END = "（回答はこの上の行まで）"
Const QES_NO = "◆Ｑ"
Const ANS_NO = "◇Ａ"
Const ENQ_SEIRI = "□アンケート整理番号"
Const IMP_SEIRI = "□感想文整理番号"
Const MAX_COL = 72

Class CMailQuestion
	Dim question_no
	Dim question_text
	Dim question_sel
	Dim question_fa
	Dim answer_sel
	Dim answer_fa1
	Dim answer_fa2
End Class

Class CTag
	Dim tag
	Dim e_type
	Dim name
	Dim value
	Dim text
End Class

'=== メールコンテンツチェック ===
Private Function MailCheck(mail_format, body, enq_check, imp_check, enq_question, imp_question, mail_err)
	If mail_format = "0" Then
		MailCheck = TextMailCheck(body, enq_check, imp_check, enq_question, imp_question, mail_err)
	Else
		MailCheck = HtmlMailCheck(body, enq_check, imp_check, enq_question, imp_question, mail_err)
	End If
End Function

'=== テキストメールコンテンツチェック ===
Private Function TextMailCheck(body, enq_check, imp_check, enq_question, imp_question, mail_err)
	Dim ary, line, id_num, state, state2, qstart, s, qno, qno2, ano, sel, tmp, enq_exist, imp_exist
	Dim answer_sel, answer_fa1, answer_fa2

	Set enq_question = Server.CreateObject("Scripting.Dictionary")
	Set imp_question = Server.CreateObject("Scripting.Dictionary")
	Set mail_err = Server.CreateObject("Scripting.Dictionary")

	line = 0
	qno2 = 0
	qstart = 0
	id_num = 0
	answer_sel = 0
	answer_fa1 = 0
	answer_fa2 = 0
	sel = 0
	enq_exist = False
	imp_exist = False
	state = ""

	ary = Split(body, vbCrLf)
	For Each s In ary
		CheckKishuIzon mail_err, line, s

		Select Case state
		Case ""
			If SeparatorCheck(s, ENQ_START) Then
				If enq_exist Then
					SetError mail_err, line, "アンケートの開始が複数存在します"
				Else
					qno2 = 0
					state = "E"
				End If
			ElseIf SeparatorCheck(s, ENQ_END) Then
				SetError mail_err, line, "アンケートの開始がありません"
			ElseIf SeparatorCheck(s, IMP_START) Then
				If imp_exist Then
					SetError mail_err, line, "感想文の開始が複数存在します"
				Else
					qno2 = 0
					state = "I"
				End If
			ElseIf SeparatorCheck(s, IMP_END) Then
				SetError mail_err, line, "感想文の開始がありません"
			End If
		Case "E"	'アンケート
			enq_exist = True
			If SeparatorCheck(s, ENQ_START) Then
				SetError mail_err, line, "アンケートの開始が重複しています"
			ElseIf SeparatorCheck(s, ENQ_END) Then
				If qstart > 0 Then
						If answer_sel = 0 Then
							sel = 0
						End If
					Call SaveQuestion(enq_question, GetQuestionText(ary, qstart, line - 1), qno2, state, sel, answer_sel, answer_fa1, answer_fa2)
					qstart = 0
				End If
				state = ""
			ElseIf SeparatorCheck(s, IMP_START) Then
				SetError mail_err, line, "アンケートの中で感想文が開始しています"
			ElseIf SeparatorCheck(s, IMP_END) Then
				SetError mail_err, line, "アンケートの中で感想文が終了しています"
			ElseIf SeparatorCheck(s, ANS_START) Then
				state2 = state
				state = "A"
			ElseIf SeparatorCheck(s, ANS_END) Then
				SetError mail_err, line, "回答の開始がありません"
			ElseIf InStr(s, QES_NO) > 0 Then
				qno = GetQuestionNo(s)
				If qno = 0 Then
					SetError mail_err, line, "質問番号が正しくありません"
				ElseIf qno <> qno2 + 1 Then
					SetError mail_err, line, "質問番号が連続していません"
				Else
					If qstart > 0 Then
						If answer_sel = 0 Then
							sel = 0
						End If
						Call SaveQuestion(enq_question, GetQuestionText(ary, qstart, line - 1), qno2, state, sel, answer_sel, answer_fa1, answer_fa2)
					End If
					qno2 = qno
					qstart = line
					answer_sel = 0
					answer_fa1 = 0
					answer_fa2 = 0
					sel = 0
				End If
			ElseIf InStr(s, ANS_NO) > 0 Then
				ano = GetAnswerNo(s)
				If ano = 0 Then
					SetError mail_err, line, "回答番号が正しくありません"
				ElseIf ano <> qno2 Then
					SetError mail_err, line, "質問番号と回答番号が一致していません"
				Else
					answer_sel = 0
					answer_fa1 = 0
					answer_fa2 = 0
				End If
			ElseIf InStr(s, "◆Ａ") > 0 Or InStr(s, "◇Ｑ") Then
				SetError mail_err, line, "質問記号または回答記号が違います"
			End If
			If qstart > 0 Then
				answer_sel = answer_sel + count_sel(s, mail_err, line)
				answer_fa1 = answer_fa1 + count_fa1(s, mail_err, line)
				tmp = GetSelNo(s)
				If tmp > sel Then
					sel = tmp
				End If
			End If
		Case "I"	'感想文
			imp_exist = True
			If SeparatorCheck(s, ENQ_START) Then
				SetError mail_err, line, "感想文の中でアンケートが開始しています"
			ElseIf SeparatorCheck(s, ENQ_END) Then
				SetError mail_err, line, "感想文の中でアンケートが終了しています"
			ElseIf SeparatorCheck(s, IMP_START) Then
				SetError mail_err, line, "感想文の開始が重複しています"
			ElseIf SeparatorCheck(s, IMP_END) Then
				If qstart > 0 Then
						If answer_sel = 0 Then
							sel = 0
						End If
					Call SaveQuestion(imp_question, GetQuestionText(ary, qstart, line - 1), qno2, state, sel, answer_sel, answer_fa1, answer_fa2)
					qstart = 0
				End If
				state = ""
			ElseIf SeparatorCheck(s, ANS_START) Then
				state2 = state
				state = "A"
			ElseIf SeparatorCheck(s, ANS_END) Then
				SetError mail_err, line, "回答の開始がありません"
			ElseIf InStr(s, QES_NO) > 0 Then
				qno = GetQuestionNo(s)
				If qno = 0 Then
					SetError mail_err, line, "質問番号が正しくありません"
				ElseIf qno <> qno2 + 1 Then
					SetError mail_err, line, "質問番号が連続していません"
				Else
					If qstart > 0 Then
						If answer_sel = 0 Then
							sel = 0
						End If
						Call SaveQuestion(imp_question, GetQuestionText(ary, qstart, line - 1), qno2, state, sel, answer_sel, answer_fa1, answer_fa2)
					End If
					qno2 = qno
					qstart = line
					answer_sel = 0
					answer_fa1 = 0
					answer_fa2 = 0
					sel = 0
				End If
			ElseIf InStr(s, ANS_NO) > 0 Then
				ano = GetAnswerNo(s)
				If ano = 0 Then
					SetError mail_err, line, "回答番号が正しくありません"
				ElseIf ano <> qno2 Then
					SetError mail_err, line, "質問番号と回答番号が一致していません"
				Else
					answer_sel = 0
					answer_fa1 = 0
					answer_fa2 = 0
				End If
			ElseIf InStr(s, "◆Ａ") > 0 Or InStr(s, "◇Ｑ") Then
				SetError mail_err, line, "質問記号または回答記号が違います"
			End If
			If qstart > 0 Then
				answer_sel = answer_sel + count_sel(s, mail_err, line)
				answer_fa1 = answer_fa1 + count_fa1(s, mail_err, line)
				tmp = GetSelNo(s)
				If tmp > sel Then
					sel = tmp
				End If
			End If
		Case "A"	'回答欄
			If SeparatorCheck(s, ENQ_START) Then
				SetError mail_err, line, "回答の中にアンケートの開始があります"
			ElseIf SeparatorCheck(s, ENQ_END) Then
				SetError mail_err, line, "回答の中にアンケートの終了があります"
			ElseIf SeparatorCheck(s, IMP_START) Then
				SetError mail_err, line, "回答の中に感想文の開始があります"
			ElseIf SeparatorCheck(s, IMP_END) Then
				SetError mail_err, line, "回答の中に感想文の終了があります"
			ElseIf SeparatorCheck(s, ANS_START) Then
				SetError mail_err, line, "回答の中に回答の開始があります"
			ElseIf SeparatorCheck(s, ANS_END) Then
				answer_fa2 = answer_fa2 + 1
				state = state2
			ElseIf InStr(s, QES_NO) > 0 Then
				SetError mail_err, line, "回答の中に質問番号があります"
			ElseIf InStr(s, ANS_NO) > 0 Then
				SetError mail_err, line, "回答の中に回答番号があります"
			End If
		End Select

		If InStr(s, ID_NUMBER) > 0 Then
			id_num = id_num + 1
		End If

		If HankakuLen(s) > MAX_COL Then
				SetError mail_err, line, "１行の文字数が３６文字を超えています"
		End If

		line = line + 1
	Next

	line = line - 1
	If id_num = 0 And (enq_check Or imp_check) Then
		SetError mail_err, line, "整理番号がありません"
	End If

	Select Case state
	Case "E"
		SetError mail_err, line, "アンケートの終了がありません"
	Case "I"
		SetError mail_err, line, "感想文の終了がありません"
	Case "A"
		SetError mail_err, line, "回答の終了がありません"
	End Select

	If enq_check And enq_exist = False Then
		SetError mail_err, line, "アンケートの開始がありません"
	End If

	If imp_check And imp_exist = False Then
		SetError mail_err, line, "感想文の開始がありません"
	End If

	If mail_err.Count = 0 Then
		TextMailCheck = True
	Else
		TextMailCheck = False
	End If
End Function

'=== HTMLメールコンテンツチェック ===
Private Function HtmlMailCheck(body, enq_check, imp_check, enq_question, imp_question, mail_err)
	Dim ary, line, state, state2, qtext, s, s2, qno, qno2, ano, sel, enq_exist, imp_exist
	Dim answer_sel, answer_fa1, answer_fa2, question
	Dim tag, form, form_end, member_id, mail_id

	Set enq_question = Server.CreateObject("Scripting.Dictionary")
	Set imp_question = Server.CreateObject("Scripting.Dictionary")
	Set mail_err = Server.CreateObject("Scripting.Dictionary")
	Set tag = New CTag

	line = 0
	qno2 = 0
	qtext = ""
	answer_sel = 0
	answer_fa1 = 0
	answer_fa2 = 0
	sel = 0
	enq_exist = False
	imp_exist = False
	state = ""
	form = False

	ary = Split(body, vbCrLf)
	For Each s In ary
		CheckKishuIzon mail_err, line, s

		TagCheck s, tag

		s2 = LCase(s)
		If InStr(s2, "<form") > 0 Then
			member_id = ""
			mail_id = ""
			form = True
		End If
		If InStr(s2, "</form") > 0 Then
			form_end = True
			form = False
		Else
			form_end = False
		End If

		Select Case state
		Case ""
			If tag.tag = "input" Then
				If form Then
					Select Case tag.name
					Case "_answer"
						Select Case tag.value
						Case "E"
							If enq_exist Then
								SetError mail_err, line, "アンケートが複数存在します"
							Else
								Set question = enq_question
								state = "E"
							End If
						Case "I"
							If imp_exist Then
								SetError mail_err, line, "感想文が複数存在します"
							Else
								Set question = imp_question
								state = "I"
							End If
						End Select
					End Select
				Else
					SetError mail_err, line, "<form>タグがみつかりません"
				End If
			End If

		Case "E", "I"
			If state = "E" Then
				enq_exist = True
			Else
				imp_exist = True
			End If

			If InStr(tag.text, QES_NO) > 0 Then
				qno = GetQuestionNo(s)
				If qno = 0 Then
					SetError mail_err, line, "質問番号が正しくありません"
				ElseIf qno <> qno2 + 1 Then
					SetError mail_err, line, "質問番号が連続していません"
				Else
					If qno2 > 0 Then
						Call SaveQuestion(question, qtext, qno2, state, sel, answer_sel, answer_fa1, answer_fa2)
					End If
					qtext = ""
					qno2 = qno
					answer_sel = 0
					answer_fa1 = 0
					answer_fa2 = 0
					sel = 0
				End If
			End If

			If form_end Then
				If qno2 > 0 Then
					If (answer_sel > 0 Or answer_fa1 > 0) And answer_fa2 = 1 Then
						answer_fa1 = answer_fa1 + 1
						answer_fa2 = 0
					End If

					Call SaveQuestion(question, qtext, qno2, state, sel, answer_sel, answer_fa1, answer_fa2)
					qtext = ""
				End If
				qno2 = 0
				state = ""
			End If

			Select Case tag.tag
			Case "input"
				Select Case tag.e_type
				Case "hidden"
					Select Case tag.name
					Case "_member_id"
						member_id = True
					Case "_mail_id"
						mail_id = True
					End Select
				Case "text", "radio", "checkbox"
					If tag.name = "Q" & qno Then
						Select Case tag.e_type
						Case "text", ""
							answer_fa1 = answer_fa1 + 1
						Case "radio"
							If IsNumeric(tag.value) Then
								If CLng(tag.value) > sel Then
									sel = CLng(tag.value)
									AddSelNo sel, tag
								End If
								answer_sel = 1
							Else
								SetError mail_err, line, "<input>タグのvalueが正しくありません"
							End If
						Case "checkbox"
							If IsNumeric(tag.value) Then
								If CLng(tag.value) > sel Then
									sel = CLng(tag.value)
									AddSelNo sel, tag
								End If
								answer_sel = answer_sel + 1
							Else
								SetError mail_err, line, "<input>タグのvalueが正しくありません"
							End If
						End Select
					Else
						SetError mail_err, line, "<input>タグのnameが正しくありません"
					End If
				End Select
			Case "textarea"
				If tag.name = "Q" & qno Then
					answer_fa2 = 1
				Else
					SetError mail_err, line, "<input>タグのnameが正しくありません"
				End If
			End Select

			If Trim(tag.text) <> "" Then
				qtext = qtext & tag.text & vbCrLf
			End If
		End Select

		line = line + 1
	Next

	line = line - 1

	Select Case state
	Case "E"
		SetError mail_err, line, "アンケート<form>タグの終了がありません"
	Case "I"
		SetError mail_err, line, "感想文の<form>タグの終了がありません"
	End Select

	If enq_check And enq_exist = False Then
		SetError mail_err, line, "アンケートの開始がありません"
	End If

	If imp_check And imp_exist = False Then
		SetError mail_err, line, "感想文の開始がありません"
	End If

	If mail_err.Count = 0 Then
		HtmlMailCheck = True
	Else
		HtmlMailCheck = False
	End If
End Function

'=== セパレータチェック ===
Private Function SeparatorCheck(s, spr)
	Dim p, t

	SeparatorCheck = False

	p = InStr(s, spr)
	If p > 1 Then
		t = Mid(s, p - 1, 1)
		If t = "-" Or t = "－" Then
			t = Mid(s, p + Len(spr), 1)
			If t = "-" Or t = "－" Then
				SeparatorCheck = True
			End If
		End If
	End If
End Function

'=== 質問番号取得 ===
Private Function GetQuestionNo(s)
	Dim p

	p = InStr(s, QES_NO)
	If p > 0 Then
		GetQuestionNo = GetNumber(Mid(s, p + Len(QES_NO)))
	Else
		GetQuestionNo = 0
	End If
End Function

'=== 回答番号取得 ===
Private Function GetAnswerNo(s)
	Dim p

	p = InStr(s, ANS_NO)
	If p > 0 Then
		GetAnswerNo = GetNumber(Mid(s, p + Len(ANS_NO)))
	Else
		GetAnswerNo = 0
	End If
End Function

'=== 番号取得 ===
Private Function GetNumber(s)
	Dim p, num

	p = InStr(s, "．")
	If p = 0 Then
		p = InStr(s, ".")
	End If

	If p > 1 Then
		num = ToHankaku(Trim(Left(s, p - 1)))
		If NumCheck(num) Then
			GetNumber = CInt(num)
		Else
			GetNumber = 0
		End If
	Else
		GetNumber = 0
	End If
End Function

'=== 選択肢番号取得 ===
Private Function GetSelNo(s)
	Dim p1, p2, sel, num, tmp

	sel = 0
	p1 = 1
	Do While True
		p2 = InStr(p1, s, "．")
		If p2 > 0 Then
			p1 = p2
		Else
			P1 = InStr(p1, s, ".")
			If p1 = 0 Then
				Exit Do
			End If
		End If

		p2 = p1
		num = ""
		Do While p2 > 1
			p2 = p2 - 1
			tmp = ToHankaku(Mid(s, p2, 1))
			If NumCheck(tmp) = False Then
				If tmp <> " " And tmp <> "　" Then
					num = ""
				End If
				Exit Do
			End If
			num = tmp & num
		Loop
		If num <> "" Then
			num = CLng(num)
			If num > sel Then
				sel = num
			End If
		End If

		p1 = p1 + 1
	Loop

	GetSelNo = sel
End Function

'=== 定型回答記号カウント ===
Private Function count_sel(s, mail_err, line)
	count_sel = count_answer(s, mail_err, line, "＜", "＞")
End Function

'=== 定性回答記号カウント ===
Private Function count_fa1(s, mail_err, line)
	count_fa1 = count_answer(s, mail_err, line, "｛", "｝")
End Function

'=== 回答記号カウント ===
Private Function count_answer(s, mail_err, line, st, ed)
	Dim n, n1, n2

	count_answer = 0
	n = 1
	Do While True
		n1 = InStr(n, s, st)
		n2 = InStr(n, s, ed)

		If n1 = 0 And n2 = 0 Then
			Exit Do
		End If

		If n1 = 0 Or n2 > 0 And n2 < n1 Then
			SetError mail_err, line, "回答記号""" & st & """がありません"
			Exit Do
		End If

		n = n1 + 1
		n1 = InStr(n, s, st)
		n2 = InStr(n, s, ed)

		If n2 = 0 Or n1 > 0 And n1 < n2 Then
			SetError mail_err, line, "回答記号""" & ed & """がありません"
			Exit Do
		End If

		count_answer = count_answer + 1
		n = n2 + 1
	Loop
End Function

'=== 質問文取得 ===
Private Function GetQuestionText(ary, qstart, qend)
	Dim i

	Do While ary(qend) = ""
		qend = qend - 1
	Loop

	ReDim ary2(qend - qstart)
	For i = 0 To qend - qstart
		ary2(i) = ary(qstart + i)
	Next

	GetQuestionText = Join(ary2, vbCrLf)
End Function

'=== 質問項目保存 ===
Private Sub SaveQuestion(question, qtext, qno, qtype, sel, answer_sel, answer_fa1, answer_fa2)
	Dim item

	'番号入力記号があって項目が０の場合は１とする
	If sel = 0 And answer_sel > 0 Then
		sel = 1
	End If

	Set item = New CMailQuestion
	item.question_no = qno
	item.question_text = qtext
	item.question_sel = sel
	item.question_fa = answer_fa1
	item.answer_sel = answer_sel
	item.answer_fa1 = answer_fa1
	item.answer_fa2 = answer_fa2
	Set question.Item(qno) = item
End Sub

'=== エラー内容保存 ===
Private Sub SetError(mail_err, line, msg)
	mail_err.Item(line) = mail_err.Item(line) & ">error: " & msg & vbCrLf
End Sub 

'=== 機種依存文字チェック ===
Private Sub CheckKishuIzon(mail_err, line, str)
	Dim n, i, c, bad

	n = Len(str)
	For i = 1 To n
		bad = False
		c = Asc(Mid(str, i, 1)) And &HFFFF&

		If c >= &H00A0& And c <= &H00FF& Then
			bad = True
		ElseIf c >= &H8540& And c <= &H889E& Then
			bad = True
		ElseIf c >= &HEB40& And c <= &HEFFC& Then
			bad = True
		ElseIf c >= &HF040& Then
			bad = True
		End If 

		If bad Then
			SetError mail_err, line, """" & Mid(str, i, 1) & """ は機種依存文字です"
		End If
	Next
End Sub

'=== 質問項目をDBに保存 ===
Sub StoreQuestion(mail_id, enq_question, imp_question)
	Dim sql, ds

	sql = "DELETE T_QUESTION WHERE QU_mail_id='" & mail_id & "'"
	ExecuteSQL sql

	sql = "SELECT * FROM T_QUESTION WHERE 0=1"
	Set ds = CreateDynaset(sql, ORADYN_DEFAULT)
	StoreQuestionSub mail_id, ds, enq_question, "E"
	StoreQuestionSub mail_id, ds, imp_question, "I"
End Sub

Sub StoreQuestionSub(mail_id, ds, question, question_type)
	Dim key, item

	For Each key In question
		Set item = question.Item(key)
		ds.AddNew
		ds("QU_mail_id").Value = mail_id
		ds("QU_question_type").Value = question_type
		ds("QU_question_no").Value = item.question_no
		ds("QU_question_text").Value = item.question_text
		ds("QU_question_sel").Value = item.question_sel
		ds("QU_question_fa").Value = item.question_fa
		ds.Update
	Next
End Sub

'=== HTMLタグチェック ===
Sub TagCheck(s, tag)
	Dim n1, n2, element_name, element_val, tag_name

	tag.tag = ""
	tag.e_type = ""
	tag.name = ""
	tag.value = ""
	tag.text = ""

	n1 = 1
	Do While True
		n2 = InStr(n1, s, "<")
		If n2 > 0 Then
			tag.text = tag.text & Mid(s, n1, n2 - n1)
			tag_name = ""
			n2 = n2 + 1
			Do While True
				element_name = HtmlElementName(s, n2)
				If element_name = "" Then
					Exit Do
				End If

				If tag_name = "" Then
					tag_name = element_name
					If tag_name = "input" Or tag_name = "textarea" Then
						tag.tag = tag_name
					End If
				Else
					element_val = HtmlElementValue(s, n2)
					If tag_name = "input" Or tag_name = "textarea" Then
						Select Case element_name
						Case "type"
							tag.e_type = element_val
						Case "name"
							tag.name = element_val
						Case "value"
							tag.value = element_val
						End Select
					End If
				End If
			Loop
			n1 = n2 + 1
		Else
			tag.text = tag.text & Mid(s, n1)
			Exit Do
		End If
	Loop
End Sub

'=== 選択番号付加 ===
Sub AddSelNo(sel, tag)
	Dim no

	no = GetNumber(tag.text)
	If no <> sel Then
		tag.text = ToZenkaku(CStr(sel)) & "．" & tag.text
	End If
End Sub
%>