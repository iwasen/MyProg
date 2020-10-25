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

'=== �`�F�b�N�{�b�N�X�̏�Ԏ擾 ===
Function Checkbox(cb)
	If IsNull(cb) Or IsEmpty(cb) Or cb = "" Then
		Checkbox = False
	Else
		Checkbox = True
	End If
End Function

'=== �`�F�b�N�{�b�N�X�̏�Ԑݒ� ===
Function Checked(cb)
	If cb = "1" Then
		Checked = " checked"
	End If
End Function

'=== ���W�I�{�^���̏�Ԑݒ� ===
Function RadioChecked(v1, v2)
	If v1 = v2 Then
		RadioChecked = " checked"
	End If
End Function

'=== �`�F�b�N�{�b�N�X�̏�Ԑݒ�2 ===
Function NotChecked(cb)
	If cb Then
	Else
		NotChecked = " checked"
	End If
End Function

'=== �I���{�b�N�X�̏�Ԑݒ� ===
Function Selected(v1, v2)
	If v1 = v2 Then
		Selected = " selected"
	End If
End Function

'=== ���[�����M ===
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
		SendMail = "�R���g���[���}�X�^���烁�[���T�[�o�����擾�ł��܂���B"
	End If
End Function

'=== ���[�����M���擾 ===
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

'=== ������-���l�ϊ��iNull �� 0 �Ƃ���j ===
Function Number(str)
	If IsNull(str) Then
		Number = 0
	Else
		Number = CDbl(str)
	End If
End Function

'=== �P�s�o�� ===
Sub Writeln(str)
	Response.Write str
	Response.Write vbCrLf
End Sub

'=== ���t��YYYY/MM/DD�̕�����ɕϊ� ===
Function DateFormat(dt)
	If IsNull(dt) Then
		DateFormat = ""
	Else
		DateFormat = Year(dt) & "/" & Right("0" & Month(dt), 2) & "/" & Right("0" & Day(dt), 2)
	End If
End Function

'=== ������YYYY/MM/DD HH:MM:SS�̕�����ɕϊ� ===
Function TimeFormat(tm)
	If IsNull(tm) Then
		TimeFormat = ""
	Else
		TimeFormat = Year(tm) & "/" & Right("0" & Month(tm), 2) & "/" & Right("0" & Day(tm), 2) & " " & _
			Right("0" & Hour(tm), 2) & ":" & Right("0" & Minute(tm), 2) & ":" & Right("0" & Second(tm), 2)
	End If
End Function

'=== NULL�l�ϊ� ===
Function NVL(val, nv)
	If IsNull(val) Then
		NVL = nv
	Else
		NVL = val
	End If
End Function

'=== ���l�̃J���}��؂� ===
Function NumFormat(num)
	If IsNull(num) Then
		NumFormat = "0"
	Else
		NumFormat = FormatNumber(num, 0, -1, 0, -1)
	End If
End Function

'=== �󕶎���<br>�ɕϊ� ===
Function BlankToBR(text)
	If IsNull(text) Or text = "" Then
		BlankToBR = "<br>"
	Else
		BlankToBR = text
	End If
End Function

'=== HTML�̃^�O��\���\�����ɕϊ� ===
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

'=== �����`�F�b�N ===
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

'=== ���̓G���[�L���\�� ===
Sub ErrMark(err_flag)
	If err_flag = True Then
		Response.Write "<sup><font color='red'>*</font></sup>"
	End If
End Sub

'=== ���[���A�h���X�`�F�b�N ===
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

'=== �X�֔ԍ��O�R���擾 ===
Function Zip1(zip_code)
	Zip1 = Left(zip_code, 3)
End Function

'=== �X�֔ԍ���S���擾 ===
Function Zip2(zip_code)
	If Len(zip_code) > 3 Then
		Zip2 = Mid(zip_code, 4)
	Else
		Zip2 = ""
	End If
End Function

'=== ������o�C�g���擾 ===
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

'=== �����ԍ��擾 ===
Function GetNextVal(name)
	Dim sql, ds

	sql = "SELECT " & name & ".NEXTVAL FROM DUAL"
	Set ds = CreateDynaset(sql, ORADYN_READONLY)
	If Not ds.EOF Then
		GetNextVal = ds.Fields(0).Value
	End If
End Function

'=== ���p�ϊ� ===
Function ToHankaku(str)
	Const zenkaku = _
			"�O�P�Q�R�S�T�U�V�W�X�D�|���`�a�b�c�d�e�f�g�h�i�j�k�l�m�n�o�p�q�r�s�t�u�v�w�x�y����������������������������������������������������"
	Const hankaku = _
			"0123456789.-@ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz"
	Dim i, l

	ToHankaku = str
	l = Len(zenkaku)
	For i = 1 To l
		ToHankaku = Replace(ToHankaku, Mid(zenkaku, i, 1), Mid(hankaku, i, 1))
	Next
End Function

'=== SQL������ϊ� ===
Function SqlStr(str)
	SqlStr = Replace(str, "'", "''")
End Function

'=== �N��擾 ===
Function GetAge(seinengappi)
	Dim today

	today = Date
	If Month(today) * 100 + Day(today) >= Month(seinengappi) * 100 + Day(seinengappi) Then
		GetAge = Year(today) - Year(seinengappi)
	Else
		GetAge = Year(today) - Year(seinengappi) - 1
	End If
End Function

'=== �X�֔ԍ�����Z���擾 ===
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

'=== CLOB�̓ǂݍ��� ===
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