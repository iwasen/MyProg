<% @LANGUAGE = "VBScript" %>
<% Option Explicit %>
<%
'Response.CacheControl = "no-cache"
Response.Buffer = True

'=== init ===
Server.ScriptTimeout = 600
Session.Timeout = 20

'=== DataSource ===
Const ADO_PROVIDER = "sqloledb"
Const ADO_SERVER = "sv1"
Const ADO_DATABASE = "melmeldb"
Const ADO_USERID = "melmeladm"
Const ADO_PASSWORD = "do!house"

'=== SMTP/POP ===
Const OFFICE_ADDRESS = "めるめる・net事務局 <office@melmel.net>"
Const ANONYMOUS ="anonymous@melmel.net"

Const MIN_MOJISUU = 35

'=== データベース接続 ===
Function OpenDB()
  Dim DB

  Set DB = Server.CreateObject("ADODB.Conxxxtion")

  With DB
  .Provider = ADO_PROVIDER
  .Properties("Data Source").Value = ADO_SERVER
  .Properties("Initial Catalog").Value = ADO_DATABASE
  .Properties("User ID").Value = ADO_USERID
  .Properties("Password").Value = ADO_PASSWORD
  .Open
	End With

	Set OpenDB = DB
End Function

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
  If cb Then
    Checked = " checked"
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
  If v1 = v2 then
    Selected = " selected"
  End If
End Function

'=== メール送信 ===
Function SendMail(mailto, mailfrom, subject, body)
  Dim BASP21
  Dim DB, RS, SQL

  Set DB = OpenDB()
  SQL = "SELECT CT_smtp_server1,CT_smtp_server2 FROM T_CONTROL"
  Set RS = DB.Execute(SQL)
  If Not RS.EOF Then
    Set BASP21 = Server.CreateObject("basp21")
	  SendMail = BASP21.SendMail(RS(0), mailto, mailfrom, subject, body, "")
    If SendMail <> "" And RS(1) <> "" Then
	    SendMail = BASP21.SendMail(RS(1), mailto, mailfrom, subject, body, "")
    End If
  Else
    SendMail = "コントロールマスタからメールサーバ名を取得できません。"
  End If
  RS.Close
End Function

'=== メール送信情報取得 ===
Sub GetSendMailInfo(id, subject, from, body)
  Dim DB, SQL, RS

  Set DB = OpenDB()
  SQL = "SELECT * FROM T_SENDMAIL WHERE SM_id='" & id & "'"
  Set RS = DB.Execute(SQL)
  If Not RS.EOF Then
    subject = RS("SM_subject")
    from  = RS("SM_from")
    body = RS("SM_body")
  End If

  RS.Close
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
    ReplaceTag = Replace(text, "&", "&amp;")
    ReplaceTag = Replace(ReplaceTag, "<", "&lt;")
    ReplaceTag = Replace(ReplaceTag, ">", "&gt;")
    ReplaceTag = Replace(ReplaceTag, """", "&quot;")
    ReplaceTag = Replace(ReplaceTag, " ", "&nbsp;")
    ReplaceTag = Replace(ReplaceTag, vbCrLf, "<br>")
  End If
End Function

'=== 全角文字換算でのRight()関数 ===
Function ZenkakuRight(s, l)
  Dim a, i, n

  n = 0
  For i = 0 To Len(s) - 1
    a = Asc(Mid(s, Len(s) - i, 1))
    If a >= 0 And a < 256 Then
      n = n + 1
    Else
      n = n + 2
    End If

    If n > l * 2 Then
      Exit For
    End If
  Next

  ZenkakuRight = Right(s, i)
End Function

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

	If n > 0 And i - 1 = n Then
	 	MailAdrCheck = True
	Else
	 	MailAdrCheck = False
	End If
End Function
%>
