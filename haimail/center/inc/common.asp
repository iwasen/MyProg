<% @LANGUAGE = "VBScript" %>
<% Option Explicit %>
<%
'Response.CacheControl = "no-cache"
Response.Buffer = True

'=== init ===
Server.ScriptTimeout = 600
Session.Timeout = 20

'=== System Constant ===
Const MAX_KYOUMIGENRE = 200
Const MAX_YOKUIKUMISE = 200
Const MAX_DOCCHIHA = 100

'=== チェックボックスの状態取得 ===
Function Checkbox(cb)
	If IsNull(cb) Or IsEmpty(cb) Or cb = "" Then
		Checkbox = False
	Else
		Checkbox = True
	End If
End Function

'=== チェックボックスの状態設定 ===
Function Checked(cb)
	If cb = "1" Then
		Checked = " checked"
	End If
End Function

'=== ラジオボタンの状態設定 ===
Function RadioChecked(v1, v2)
	If v1 = v2 Then
		RadioChecked = " checked"
	End If
End Function

'=== チェックボックスの状態設定2 ===
Function NotChecked(cb)
	If cb Then
	Else
		NotChecked = " checked"
	End If
End Function

'=== 選択ボックスの状態設定 ===
Function Selected(v1, v2)
	If v1 = v2 Then
		Selected = " selected"
	End If
End Function

'=== メール送信 ===
Function SendMail(mailto, mailfrom, subject, body)
	Dim BASP21
	Dim ds, sql

	If Left(mailfrom, 1) = "?" Then
		Exit Function
	End If

	sql = "SELECT CT_smtp_server1,CT_smtp_server2 FROM T_CONTROL WHERE CT_control_id='0'"
	Set ds = CreateDynaset(sql, ORADYN_READONLY)
	If Not ds.EOF Then
		Set BASP21 = Server.CreateObject("basp21")
		SendMail = BASP21.SendMail(ds(0).Value, mailto, mailfrom, subject, body, "")
		If SendMail <> "" And ds(1).Value <> "" Then
			SendMail = BASP21.SendMail(ds(1).Value, mailto, mailfrom, subject, body, "")
		End If
	Else
		SendMail = "コントロールマスタからメールサーバ名を取得できません。"
	End If
End Function

'=== メール送信情報取得 ===
Sub GetSendMailInfo(id, subject, from, body)
	Dim sql, ds

	sql = "SELECT * FROM T_MAIL_TEMPLATE WHERE MT_id='" & id & "'"
	Set ds = CreateDynaset(sql, ORADYN_READONLY)
	If Not ds.EOF Then
		subject = ds("MT_subject")
		from  = ds("MT_from")
		body = ReadCLOB(ds("MT_body").Value)
	End If
End Sub

'=== 文字列-数値変換（Null は 0 とする） ===
Function Number(str)
	If IsNull(str) Then
		Number = 0
	Else
		Number = CDbl(str)
	End If
End Function

'=== １行出力 ===
Sub Writeln(str)
	Response.Write str
	Response.Write vbCrLf
End Sub

'=== 日付をYYYY/MM/DDの文字列に変換 ===
Function DateFormat(dt)
	If IsNull(dt) Then
		DateFormat = ""
	Else
		DateFormat = Year(dt) & "/" & Right("0" & Month(dt), 2) & "/" & Right("0" & Day(dt), 2)
	End If
End Function

'=== 日時をYYYY/MM/DD HH:MM:SSの文字列に変換 ===
Function TimeFormat(tm)
	If IsNull(tm) Then
		TimeFormat = ""
	Else
		TimeFormat = Year(tm) & "/" & Right("0" & Month(tm), 2) & "/" & Right("0" & Day(tm), 2) & " " & _
			Right("0" & Hour(tm), 2) & ":" & Right("0" & Minute(tm), 2) & ":" & Right("0" & Second(tm), 2)
	End If
End Function

'=== NULL値変換 ===
Function NVL(val, nv)
	If IsNull(val) Then
		NVL = nv
	Else
		NVL = val
	End If
End Function

'=== 数値のカンマ区切り ===
Function NumFormat(num)
	If IsNull(num) Then
		NumFormat = "0"
	Else
		NumFormat = FormatNumber(num, 0, -1, 0, -1)
	End If
End Function

'=== 空文字を<br>に変換 ===
Function BlankToBR(text)
	If IsNull(text) Or text = "" Then
		BlankToBR = "<br>"
	Else
		BlankToBR = text
	End If
End Function

'=== HTMLのタグを表示可能文字に変換 ===
Function ReplaceTag(text)
	If IsNull(text) Then
		ReplaceTag = ""
	Else
		ReplaceTag = Replace(HTMLEncode(text), vbCrLf, "<br>")
	End If
End Function

'=== HTML Encode ===
Function HTMLEncode(text)
	If IsNull(text) Then
		HTMLEncode = ""
	Else
		HTMLEncode = Server.HTMLEncode(text)
	End If
End Function

'=== 数字チェック ===
Function NumCheck(num)
	Dim i, n

	NumCheck = True
	n = Len(num)
	For i = 1 To n
		If Mid(num, i, 1) < "0" Or Mid(num, i, 1) > "9" Then
			NumCheck = False
			Exit For
		End If
	Next
End Function

'=== 入力エラー記号表示 ===
Sub ErrMark(err_flag)
	If err_flag = True Then
		Response.Write "<sup><font color='red'>*</font></sup>"
	End If
End Sub

'=== メールアドレスチェック ===
Function MailAdrCheck(mail_adr)
	Const mc = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ{}./+-*=~!#$%^&_?"
	Dim a, c, i, n

	a = 0
	n = Len(mail_adr)
	For i = 1 To n
		c = Mid(mail_adr, i, 1)
		If c = "@" Then
			a = a + 1
			If a = 2 Then
				Exit For
			End If
		ElseIf InStr(mc, c) = 0 Then
			Exit For
		End If
	Next

	If a = 1 And i - 1 = n Then
		MailAdrCheck = True
	Else
		MailAdrCheck = False
	End If
End Function

'=== 郵便番号前３桁取得 ===
Function Zip1(zip_code)
	Zip1 = Left(zip_code, 3)
End Function

'=== 郵便番号後４桁取得 ===
Function Zip2(zip_code)
	If Len(zip_code) > 3 Then
		Zip2 = Mid(zip_code, 4)
	Else
		Zip2 = ""
	End If
End Function

'=== 文字列バイト数取得 ===
Function StrBytes(str)
	Dim a, i, l, n

	n = 0
	l = Len(str)
	For i = 1 To l
		a = Asc(Mid(str, i, 1))
		If a >= 0 And a < 256 Then
			n = n + 1
		Else
			n = n + 2
		End If
	Next

	StrBytes = n
End Function

'=== 順序番号取得 ===
Function GetNextVal(name)
	Dim sql, ds

	sql = "SELECT " & name & ".NEXTVAL FROM DUAL"
	Set ds = CreateDynaset(sql, ORADYN_READONLY)
	If Not ds.EOF Then
		GetNextVal = ds.Fields(0).Value
	End If
End Function

'=== 半角変換 ===
Function ToHankaku(str)
	Const zenkaku = _
			"０１２３４５６７８９．－＠ＡＢＣＤＥＦＧＨＩＪＫＬＭＮＯＰＱＲＳＴＵＶＷＸＹＺａｂｃｄｅｆｇｈｉｊｋｌｍｎｏｐｑｒｓｔｕｖｗｘｙｚ"
	Const hankaku = _
			"0123456789.-@ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz"
	Dim i, l

	ToHankaku = str
	l = Len(zenkaku)
	For i = 1 To l
		ToHankaku = Replace(ToHankaku, Mid(zenkaku, i, 1), Mid(hankaku, i, 1))
	Next
End Function

'=== SQL文字列変換 ===
Function SqlStr(str)
	SqlStr = Replace(str, "'", "''")
End Function

'=== 年齢取得 ===
Function GetAge(seinengappi)
	Dim today

	today = Date
	If Month(today) * 100 + Day(today) >= Month(seinengappi) * 100 + Day(seinengappi) Then
		GetAge = Year(today) - Year(seinengappi)
	Else
		GetAge = Year(today) - Year(seinengappi) - 1
	End If
End Function

'=== 郵便番号から住所取得 ===
Function GetAddress(zip)
	Dim sql, ds

	sql = "SELECT ZC_todoufuken,ZC_shikuchouson,ZC_chouiki FROM T_ZIPCODE WHERE ZC_zip_uniq='" & zip & "'"
	Set ds = CreateDynaset(sql, ORADYN_READONLY)
	If ds.EOF Then
		GetAddress = ""
	Else
		GetAddress = ds(0).Value & ds(1).Value & ds(2).Value
	End If
End Function

'=== CLOBの読み込み ===
Function ReadCLOB(clob)
	Dim size

	If IsObject(clob) Then
		If Not clob.IsNull Then
			size = clob.Size
			If size > 0 Then
				clob.Read ReadCLOB, size * 2
			End If
		End If
	End If
End Function
%>