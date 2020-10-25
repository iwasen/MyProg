<!--#INCLUDE FILE="../param.asp"-->
<%
'=== 権限 ===
Const PM_REGIST_AGENT = 1
Const PM_REGIST_OPERATOR = 2
Const PM_REGIST_CLIENT = 3
Const PM_REGIST_PROJECT = 4
Const PM_REGIST_JOB_A = 5
Const PM_REGIST_JOB_B = 6
Const PM_JOB_BUNKATSU_SOUSHIN = 7
Const PM_JOB_HENSHIN_POINT = 8
Const PM_JOB_SOUSHIN_TAISHOU = 9
Const PM_JOB_JOGAI_TAISHOU = 10
Const PM_JOB_HACCHUU = 11
Const PM_JOB_CLICK_COUNTER = 12
Const PM_MITSUMORI = 13
Const PM_HACCHUU = 14
Const PM_CLICK_REPORT = 15
Const PM_CLICK_URL = 16
Const PM_ENQ_USE = 17
Const PM_ENQ_LOG = 18
Const PM_ENQ_CSV = 19
Const PM_ENQ_REPORT = 20
Const PM_IMP_NUMBER = 21
Const PM_IMP_10LOG = 22
Const PM_IMP_LOG = 23
Const PM_IMP_CSV = 24
Const PM_IMP_REPORT = 25
Const PM_AGENT_PERMISSION = 26
Const PM_CLIENT_PERMISSION = 27
Const PM_GUEST_SEARCH = 28
Const PM_CLICK_CSV = 29
Const PM_PROJECT_UPDATE = 30
Const PM_PROJECT_DELETE = 31
Const PM_JOB_UPDATE = 32
Const PM_JOB_DELETE = 33
Const PM_JOGAI_BAITAI = 34
Const PM_TAISHOU_BAITAI = 35

Const LOGIN_CENTER = "H"
Const LOGIN_AGENT = "A"
Const LOGIN_CLIENT = "C"
Const LOGIN_GUEST = "G"

Const LIST_TABLE = "border=1 cellspacing=0 cellpadding=1 bordercolorlight=""#f0f0f0"" frame=""box"""

Dim title_text

'=== ヘッダタイトル設定 ===
Sub SetHeaderTitle(title)
	title_text = title
End Sub

'=== エラーメッセージ表示 ===
Sub ShowErrorMessage(message)
	Session("err_msg") = message
	Server.Transfer("error_msg.asp")
End Sub

'=== </xmp>を表示可能文字に変換 ===
Function ReplaceXmp(text)
	ReplaceXmp = Replace(text, "</xmp>", "<／xmp>")
	ReplaceXmp = Replace(ReplaceXmp, "</XMP>", "<／XMP>")
End Function

'=== 空文字をNullに変換 ===
Function BlankToNull(text)
	If text = "" Then
		BlankToNull = Null
	Else
		BlankToNull = text
	End If
End Function

'=== 年の選択肢出力 ===
Sub SelectYear1(y)
	SelectYearSub y, 2000, 2010
End Sub

'当年から５年後まで
Sub SelectYear2(y)
	Dim cy

	cy = Year(Date)
	SelectYearSub y, cy, cy + 5
End Sub

'2000年から当年まで
Sub SelectYear3(y)
	Dim cy

	cy = Year(Date)
	SelectYearSub y, 2000, cy
End Sub

'2001年から当年＋５年後まで
Sub SelectYear4(y)
	Dim cy

	cy = Year(Date)
	SelectYearSub y, 2001, cy + 5
End Sub

'開始終了年指定
Sub SelectYearSub(y, s, e)
	Dim i

	Writeln "<option value=''" & Selected(NVL(y, ""), "") & "></option>"

	For i = s To e
		Writeln "<option value='" & i & "'" & Selected(Trim(y), CStr(i)) & ">" & i & "</option>"
	Next
End Sub

'=== 月の選択肢出力 ===
Sub SelectMonth(m)
	Dim i

	For i = 1 To 12
		Writeln "<option value='" & i & "'" & Selected(Trim(m), CStr(i)) & ">" & i & "</option>"
	Next
End Sub

'=== 日の選択肢出力 ===
Sub SelectDay(d)
	Dim i

	For i = 1 To 31
		Writeln "<option value='" & i & "'" & Selected(Trim(d), CStr(i)) & ">" & i & "</option>"
	Next
End Sub

'=== 時間の選択肢出力 ===
Sub SelectHour(h)
	Dim i, hour

	For i = 0 To 23
		If i < 12 Then
			hour = "AM " & i & "時"
		Else
			hour = "PM " & i - 12 & "時"
		End If
		Writeln "<option value='" & i & "'" & Selected(Trim(h), CStr(i)) & ">" & hour & "</option>"
	Next
End Sub

'=== 時間の選択肢出力（終日有り） ===
Sub SelectHour2(h)
	Writeln "<option value='-1'" & Selected(Trim(h), "-1") & ">終日</option>"
	SelectHour h
End Sub

'=== 現在の順序番号取得 ===
Function GetCurrVal(name)
	Dim sql, ds

	sql = "SELECT " & name & ".CURRVAL FROM DUAL"
	Set ds = CreateDynaset(sql, ORADYN_READONLY)
	If Not ds.EOF Then
		GetCurrVal = ds.Fields(0).Value
	End If
End Function

'=== 性別のテキスト表示 ===
Function SeibetsuText(seibetsu)
	Select Case seibetsu
	Case "0"
		SeibetsuText = "男"
	Case "1"
		SeibetsuText = "女"
	Case Else
		SeibetsuText = ""
	End Select
End Function

'=== 未既婚のテキスト表示 ===
Function MikikonText(mikikon)
	Select Case mikikon
	Case "0"
		MikikonText = "独身"
	Case "1"
		MikikonText = "既婚"
	Case Else
		MikikonText = ""
	End Select
End Function

'=== 数値からID作成 ===
Function MakeID(num, l)
	MakeID = Right(String(l, "0") & num, l)
End Function

'=== 文字表示（NULLは-） ===
Function StrFormat2(str)
	If IsNull(str) Then
		StrFormat2 = "-"
	Else
		StrFormat2 = str
	End If
End Function

'=== 数値表示（NULLは-） ===
Function NumFormat2(num)
	If IsNull(num) Then
		NumFormat2 = "-"
	Else
		NumFormat2 = FormatNumber(num, 0, -1, 0, -1)
	End If
End Function

'=== 日付をYYYY/MM/DDの文字列に変換（NULLは-) ===
Function DateFormat2(dt)
	If IsNull(dt) Then
		DateFormat2 = "-"
	Else
		DateFormat2 = Year(dt) & "/" & Right("0" & Month(dt), 2) & "/" & Right("0" & Day(dt), 2)
	End If
End Function

'=== 開始日付～終了日付に変換 ===
Function DateFormat3(dt_start, dt_end)
	If IsNull(dt_start) And IsNull(dt_end) Then
		DateFormat3 = "-"
	Else
		DateFormat3 = DateFormat(dt_start) & "～" & DateFormat(dt_end)
	End If
End Function

'=== 日時をYYYY/MM/DD HH:MMの文字列に変換 ===
Function TimeFormat2(tm)
	If IsNull(tm) Then
		TimeFormat2 = "-"
	Else
		TimeFormat2 = Year(tm) & "/" & Right("0" & Month(tm), 2) & "/" & Right("0" & Day(tm), 2) & " " & _
			Right("0" & Hour(tm), 2) & ":" & Right("0" & Minute(tm), 2)
	End If
End Function

'=== コントロール情報取得 ===
Function GetControlInfo(id)
	Dim sql, ds

	sql = "SELECT " & id & " FROM T_CONTROL WHERE CT_control_id='0'"
	Set ds = CreateDynaset(sql, ORADYN_READONLY)
	If Not ds.EOF Then
		GetControlInfo = ds(0).Value
	End If
End Function

'=== value, checked 出力 ===
Sub ValueChecked(value, def)
	Response.Write " value='" & value & "'"
	If value = def Then
		Response.Write " checked"
	End If
End Sub

'=== value, selected 出力 ===
Sub ValueSelected(value, def)
	Response.Write " value='" & value & "'"
	If value = def Then
		Response.Write " selected"
	End If
End Sub

'=== CSV出力 ===
Sub SetCSV(csv, data)
	If csv <> "" Then
		csv = csv &  ","
	End If
	If InStr(data, ",") > 0 Or InStr(data, """") > 0 Or InStr(data, vbCrLf) > 0Then
		csv = csv & """" & Replace(data, """", """""") & """"
	Else
		csv = csv & data
	End If
End Sub

'=== システムエラー処理 ===
Sub SystemError(msg)
	Dim server_name, url, body

	server_name = Request.ServerVariables("SERVER_NAME")
	url = Request.ServerVariables("URL")

	ExecuteSQL "INSERT INTO T_ERROR_LOG VALUES (EL_seq_no.NextVal,'" & server_name & "','" & url & "','" & msg & "',SYSDATE)"

	If server_name <> "localhost" Then
		body = "はいめーる・netでエラーが発生しました。" & vbCrLf & vbCrLf & _
					 "Server Name:" & server_name & vbCrLf & _
					 "URL:" & url & vbCrLf & _
					 "Message:" & msg & vbCrLf
		SendMail "error@haimail.net", "error@haimail.net", "【はいめーる・net】システムエラー報告", body
	End If

	Response.Redirect error_page
End Sub

'=== メールID生成 ===
Function MakeMailID()
	Dim i, sql, ds

	Randomize
	Do While True
		MakeMailID = ""
		For i = 1 To 8
			MakeMailID = MakeMailID & chr(Int(rnd * 10) + &h30)
		Next

		sql = "SELECT SM_mail_id FROM T_SEND_MAIL WHERE SM_mail_id='" & MakeMailID & "'"
		Set ds = CreateDynaset(sql, ORADYN_READONLY)
		If ds.EOF Then
			Exit Do
		End If
	Loop
End Function

'=== 表示／非表示設定 ===
Function Display(flag)
	If flag = "1" Then
		Display = ""
	Else
		Display = " style='display:none'"
	End If
End Function

'=== 引用記号削除（１行） ===
Function DeleteQuote1(text)
	If Left(text, 2) = "> " Then
		DeleteQuote1 = Mid(text, 3)
	ElseIf Left(text, 1) = ">" Then
		DeleteQuote1 = Mid(text, 2)
	Else
		DeleteQuote1 = text
	End If
End Function

'=== 引用記号削除（複数行） ===
Function DeleteQuote(text)
	Dim i, j, ary, n1, n2, n

	ary = Split(text, vbCrLf)
	For i = 0 To Ubound(ary)
		ary(i) = DeleteQuote1(ary(i))
	Next

	For i = 0 To Ubound(ary)
		If ary(i) <> "" Then
			n1 = i
			Exit For
		End IF
	Next

	For i = Ubound(ary) To 0 Step -1
		If ary(i) <> "" Then
			n2 = i
			Exit For
		End IF
	Next

	n = n2 - n1
	If Ubound(ary) >= 0 And n >= 0 Then
		Redim ary2(n)
		For i = 0 To n
			ary2(i) = ary(n1 + i)
		Next
		DeleteQuote = Join(ary2, vbCrLf)
	Else
		DeleteQuote = ""
	End If
End Function

'=== 半角文字換算でのLen()関数 ===
Public Function HankakuLen(s)
	Dim a, i, n

	n = 0
	For i = 1 To Len(s)
		a = Asc(Mid(s, i, 1))
		If a >= 0 And a < 256 Then
			n = n + 1
		Else
			n = n + 2
		End If
	Next

	HankakuLen = n
End Function

'=== 全角変換 ===
Function ToZenkaku(str)
	Const zenkaku = _
			"０１２３４５６７８９．－＠ＡＢＣＤＥＦＧＨＩＪＫＬＭＮＯＰＱＲＳＴＵＶＷＸＹＺａｂｃｄｅｆｇｈｉｊｋｌｍｎｏｐｑｒｓｔｕｖｗｘｙｚ"
	Const hankaku = _
			"0123456789.-@ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz"
	Dim i, l

	ToZenkaku = str
	l = Len(hankaku)
	For i = 1 To l
		ToZenkaku = Replace(ToZenkaku, Mid(hankaku, i, 1), Mid(zenkaku, i, 1))
	Next
End Function

'=== 末尾の改行を１つだけにする ===
Function LaxxxrLf(text)
	LaxxxrLf = NVL(text, "")
	Do While Right(LaxxxrLf, 2) = vbCrLf
		LaxxxrLf = Left(LaxxxrLf, Len(LaxxxrLf) - 2)
	Loop
	LaxxxrLf = LaxxxrLf & vbCrLf
End Function

'=== 文字の置換 ===
Function SetAt(str, pos, ch)
	SetAt = Left(str, pos - 1) & ch & Mid(str, pos + 1)
End Function

'=== 表示ＯＮ／ＯＦＦ設定 ===
Private Sub Show(flag)
	If Not flag Then
		Response.Write " style='display:none'"
	End If
End Sub

'=== 同一項目連結処理用クラス ===
Class CJoinSameItem
	Dim m_tbl(), m_count, m_index
	Dim item

	Public Sub Prepare(ds, col)
		If Not ds.EOF Then
			Redim m_tbl(ds.RecordCount)
			item = ds(col).Value
			m_count = 0
			m_index = 0
			Do Until ds.EOF
				If item = ds(col).Value Then
					m_count = m_count + 1
				Else
					m_tbl(m_index) = m_count
					m_index = m_index + 1
					m_count = 1
					item = ds(col).Value
				End If
				ds.MoveNext
			Loop
			m_tbl(m_index) = m_count
			ds.MoveFirst
		End If
		m_count = 0
		m_index = 0
	End Sub

	Public Function GetRowCount()
		If m_count = 0 Then
			m_count = m_tbl(m_index)
			m_index = m_index + 1
			GetRowCount = m_count
		Else
			GetRowCount = 0
		End If
		m_count = m_count - 1
	End Function
End Class

'=== 曜日取得 ===
Function GetWeekName(dt)
	Select Case Weekday(dt)
	Case 1
		GetWeekName = "日"
	Case 2
		GetWeekName = "月"
	Case 3
		GetWeekName = "火"
	Case 4
		GetWeekName = "水"
	Case 5
		GetWeekName = "木"
	Case 6
		GetWeekName = "金"
	Case 7
		GetWeekName = "土"
	End Select
End Function

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

'=== 行数選択肢 ===
Sub SelectDispLine(nline)
	Dim n

	Writeln "<option value='0'" & Selected(nline, 0) & ">- 全て -</option>"
	For n = 10 To 100 Step 10
		Writeln "<option value='" & n & "'" & Selected(nline, n) & ">" & n & "行</option>"
	Next
End Sub

'=== 複数項目のカンマ連結 ===
Function JoinItem(items)
	Dim item

	JoinItem = ""
	If IsObject(items) Then
		For Each item In Items
			If JoinItem <> "" Then
				JoinItem = JoinItem & ","
			End If
			JoinItem = JoinItem & item
		Next
	End If
End Function

'=== メール種類名取得 ===
Function MailTypeText(mail_type)
	Select Case mail_type
	Case "LT"
		MailTypeText = "手紙"
	Case "HG"
		MailTypeText = "葉書"
	Case "CR"
		MailTypeText = "チラシ"
	Case "OH"
		MailTypeText = "往復葉書"
	Case "TG"
		MailTypeText = "電報"
	End Select
End Function

'=== メール形式名取得 ===
Function MailFormatText(mail_format)
	Select Case mail_format
	Case "0"
		MailFormatText = "テキスト"
	Case "1"
		MailFormatText = "テキストHTML"
	Case "2"
		MailFormatText = "HTML"
	End Select
End Function

'=== 承認状態表示 ===
Private Function ApproveStatus(status)
	Select Case status
	Case "0"
		ApproveStatus = "未承認"
	Case "1"
		ApproveStatus = "承認待ち"
	Case "2"
		ApproveStatus = "承認済み"
	End Select
End Function

'=== 行末のCRLFを削除 ===
Private Function DeleteLaxxxrLf(text)
	DeleteLaxxxrLf = NVL(text, "")
	Do While Right(DeleteLaxxxrLf, 2) = vbCrLf
		DeleteLaxxxrLf = Left(DeleteLaxxxrLf, Len(DeleteLaxxxrLf) - 2)
	Loop
End Function

'=== チェックボックスの状態設定 ===
Private Function Checked2(cb)
	If cb <> "" Then
		Checked2 = " checked"
	Else
		Checked2 = ""
	End If
End Function

'=== 表示／非表示設定 ===
Function Display2(flag)
	If flag <> "" Then
		Display2 = ""
	Else
		Display2 = " style='display:none'"
	End If
End Function
%>