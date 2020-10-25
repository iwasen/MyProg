Attribute VB_Name = "modMain"
Option Explicit

'データベース接続情報
Const ORA_DBNAME = "haimail"
Const ORA_USER = "haimail/do!house"

'ログ種類
Public Const LOG_NORMAL0 = "0"
Public Const LOG_NORMAL1 = "1"
Public Const LOG_ERROR = "9"

'項目数
Public Const MAX_KYOUMI_GENRE = 200
Public Const MAX_YOKUIKU_MISE = 200
Public Const MAX_DOCCHI_HA = 100
Public Const MAX_SHOKYGYOU = 11

'登録フォームのバージョン
Public Const VER_REGIST_FORM = 1

'INIファイル
Public Const iniFile = ".\HaimailService.ini"

'Win32API
Public Declare Function DeleteFile Lib "kernel32" Alias "DeleteFileA" (ByVal lpFileName As String) As Long
Private Declare Function GetTempPath Lib "kernel32" _
         Alias "GetTempPathA" (ByVal nBufferLength As Long, _
         ByVal lpBuffer As String) As Long
Public Declare Function GetPrivateProfileInt Lib "kernel32" Alias "GetPrivateProfileIntA" (ByVal lpApplicationName As String, ByVal lpKeyName As String, ByVal nDefault As Long, ByVal lpFileName As String) As Long
Public Declare Function WritePrivateProfileString Lib "kernel32" Alias "WritePrivateProfileStringA" (ByVal lpApplicationName As String, ByVal lpKeyName As Any, ByVal lpString As Any, ByVal lpFileName As String) As Long

'グローバル変数
Public oraSess As OraSessionClass
Public oraDB As OraDatabase
Public objMail As Basp21
Public sCtSmtpServer1 As String
Public sCtSmtpServer2 As String
Public sCtPop3Server As String
Public sCtPop3RegUser As String
Public sCtPop3RegPass As String
Private lCtLogLimit As Long
Private lCtAutoInterval As Long
Public sCtErrorMail As String
Private sTempDir As String

'=======================================
'   メイン処理
'=======================================
Public Sub Main()

    Dim iRegMail As Integer
    Dim iRegErr As Integer
    Dim sKind As String

    '既に起動中だったら何もしない
    If App.PrevInstance Then
        Exit Sub
    End If

    'データベース接続
    If ConxxxtDB() = False Then
        Exit Sub
    End If

    'コントロールマスタ情報取得
    If ReadContrxxxaster = False Then
        Exit Sub
    End If

    'メール接続
    If ConxxxtMail = False Then
        Exit Sub
    End If

    'テンポラリディレクトリ取得
    sTempDir = GetTempDir()

    '仮登録メール受信
    iRegMail = RecvRegMail

    '仮登録エラーメール処理
    iRegErr = SendErrMail

    'バージンメール送信処理
    SendVirginMail

    '特別ポイント付与
    Call SpcialPoint

    '処理件数をログに書き込み
    If iRegMail = 0 And iRegErr = 0 Then
        sKind = LOG_NORMAL0
    Else
        sKind = LOG_NORMAL1
    End If
    Call WriteLog(sKind, "処理件数 " & _
        "[仮登録メール:" & iRegMail & "]" & _
        "[登録エラー処理:" & iRegErr & "]")

    '保存期限を過ぎたログを削除
    Call DeleteLog

    '自動処理起動インターバルを設定
    Call SetInterval

    oraDB.Close

End Sub

'=======================================
'   ＤＢ接続処理
'=======================================
Private Function ConxxxtDB() As Boolean

    On Error Resume Next

    Set oraSess = New OraSessionClass
    Set oraDB = oraSess.OpenDatabase(ORA_DBNAME, ORA_USER, ORADB_DEFAULT)

    If Err.Number = 0 Then
        ConxxxtDB = True
    Else
        ConxxxtDB = False
    End If

End Function

'=======================================
'   コントロールマスタ情報取得
'=======================================
Private Function ReadContrxxxaster() As Boolean

    Dim sSql As String
    Dim objDS As OraDynaset

    On Error Resume Next

    sSql = "SELECT * FROM T_CONTROL WHERE CT_control_id='0'"
    Set objDS = oraDB.CreateDynaset(sSql, ORADYN_READONLY)

    If Not objDS.EOF Then
        sCtSmtpServer1 = objDS("CT_smtp_server1").Value
        sCtSmtpServer2 = NVL(objDS("CT_smtp_server2").Value, "")
        sCtPop3Server = objDS("CT_pop3_server").Value
        sCtPop3RegUser = objDS("CT_pop3_regist_user").Value
        sCtPop3RegPass = objDS("CT_pop3_regist_pass").Value
        lCtLogLimit = objDS("CT_log_limit").Value
        lCtAutoInterval = objDS("CT_auto_interval").Value
        sCtErrorMail = objDS("CT_error_mail").Value
        ReadContrxxxaster = True
    Else
        Call WriteLog(LOG_ERROR, "コントロールマスタの読み込みに失敗しました。")
        ReadContrxxxaster = False
    End If

End Function

'=======================================
'   メールコンポーネント(BASP21)作成
'=======================================
Private Function ConxxxtMail() As Boolean

    Set objMail = CreateObject("basp21")

    ConxxxtMail = True

End Function

'=======================================
'   メール受信処理
'=======================================
Public Function RecvMail(sPop3Server As String, sPop3User As String, sPop3Pass As String, lNum As Long) As Variant

    Dim vStat As Variant

    On Error Resume Next

    '受信したメール数を取得
    vStat = objMail.RcvMail(sPop3Server, sPop3User, sPop3Pass, "STAT", sTempDir)
    If VarType(vStat) = vbString Then
        If vStat <> "" Then
            Call WriteLog(LOG_ERROR, "メール受信に失敗しました。[Server:" & sPop3Server & "][Msg:" & vStat & "]")
        End If
        Exit Function
    End If
    lNum = CLng(Left(vStat(0), InStr(vStat(0), " ") - 1))
    If lNum > 50 Then
        lNum = 50
    End If

    '０でなければ受信
    If lNum > 0 Then
        RecvMail = objMail.RcvMail(sPop3Server, sPop3User, sPop3Pass, "SAVE 1-" & lNum, sTempDir)
        If VarType(RecvMail) = vbString Then
            If RecvMail <> "" Then
                Call WriteLog(LOG_ERROR, "メール受信に失敗しました。[Server:" & sPop3Server & "][Msg:" & RecvMail & "]")
            End If
        End If
    End If
End Function

'=======================================
'   メール削除処理
'=======================================
Public Sub DeleteMail(sPop3Server As String, sPop3User As String, sPop3Pass As String, lNum As Long)

    Dim vDelete As Variant

    On Error Resume Next

    If lNum > 0 Then
        vDelete = objMail.RcvMail(sPop3Server, sPop3User, sPop3Pass, "DELE 1-" & lNum, sTempDir)
    End If
End Sub

'=======================================
'   メール送信処理
'=======================================
Public Function SendMail(mailto As String, mailfrom As String, subject As String, body As String) As Variant

    On Error Resume Next

    SendMail = objMail.SendMail(sCtSmtpServer1, mailto, mailfrom, subject, body, "")
    If SendMail <> "" Then
        Call WriteLog(LOG_ERROR, "メールの送信に失敗しました。[Server:" & sCtSmtpServer1 & "][Msg:" & SendMail & "]")
        If sCtSmtpServer2 <> "" Then
            SendMail = objMail.SendMail(sCtSmtpServer2, mailto, mailfrom, subject, body, "")
            If SendMail <> "" Then
                Call WriteLog(LOG_ERROR, "メールの送信に失敗しました。[Server:" & sCtSmtpServer2 & "][Msg:" & SendMail & "]")
            End If
        End If
    End If
End Function

'=======================================
'   メールテンポラリファイル削除処理
'=======================================
Public Sub DeleteMailTemp(vFiles As Variant)

    Dim vFile As Variant

    On Error Resume Next

    If IsArray(vFiles) Then
        For Each vFile In vFiles
            'テンポラリファイル削除
            Call DeleteFile(vFile)
        Next
    End If
End Sub

'=======================================
'   送信メールテンプレート取得
'=======================================
Public Sub GetSendMailInfo(id As String, subject As String, from As String, body As String)

    Dim sSql As String
    Dim objDS As OraDynaset
    Dim vTemp As Variant

    sSql = "SELECT * FROM T_MAIL_TEMPLATE WHERE MT_id='" & id & "'"
    Set objDS = oraDB.CreateDynaset(sSql, ORADYN_READONLY)
    If Not objDS.EOF Then
        subject = objDS("MT_subject")
        from = objDS("MT_from")
        objDS("MT_body").Value.Read vTemp, 30000
        body = vTemp
    Else
        Call WriteLog(LOG_ERROR, "送信メールテンプレートの取得に失敗しました。[ID:" & id & "]")
    End If

End Sub

'=======================================
'   テンポラリディレクトリ取得
'=======================================
Private Function GetTempDir() As String
   
   Dim strTempPath As String
   Dim lngTempPath As Long
   
   strTempPath = String(144, vbNullChar)
   lngTempPath = Len(strTempPath)
   If (GetTempPath(lngTempPath, strTempPath) > 0) Then
      GetTempDir = Left(strTempPath, _
         InStr(1, strTempPath, vbNullChar) - 1)
   Else
      GetTempDir = ""
   End If
End Function

'=======================================
'   NULL値変換処理
'=======================================
Public Function NVL(vValue As Variant, vNullValue) As String

    If IsNull(vValue) Then
        NVL = vNullValue
    Else
        NVL = vValue
    End If

End Function

'=======================================
'   ログ書き込み処理
'=======================================
Public Sub WriteLog(sKind As String, sMessage As String)

    Dim sSql As String
    Dim sSeqNo As String
    Dim oraDS As OraDynaset

    On Error Resume Next

    sSeqNo = GetNextVal("AL_seq_no")

    sSql = "SELECT * FROM T_AUTOLOG WHERE AL_seq_no=" & sSeqNo
    Set oraDS = oraDB.CreateDynaset(sSql, ORADYN_DEFAULT)
    If oraDS.EOF Then
        oraDS.AddNew
    Else
        oraDS.Edit
    End If

    oraDS("AL_seq_no").Value = sSeqNo
    oraDS("AL_kind").Value = sKind
    oraDS("AL_message").Value = sMessage
    oraDS("AL_datetime").Value = Now
    oraDS.Update

'@@@@@@@@@@@@@@@@@@@@
    Dim body As String
    If sKind = LOG_ERROR Then
        sSql = "SELECT COUNT(*) FROM T_AUTOLOG WHERE AL_kind='" & LOG_ERROR & "'"
        Set oraDS = oraDB.CreateDynaset(sSql, ORADYN_READONLY)
        If Not oraDS.EOF Then
            If oraDS(0).Value = 1 Then
                If Left(sCtSmtpServer1, 5) = "hello" Then
                    body = "はいめーる・netでエラーが発生しました。" & vbCrLf & vbCrLf & _
                        "Message:" & sMessage & vbCrLf
                    SendMail "error@haimail.net", "error@haimail.net", "【はいめーる・net】自動処理エラー報告", body
                End If
            End If
        End If
    End If
'@@@@@@@@@@@@@@@@@@@@
End Sub

'=======================================
'   保存期限を過ぎたログを削除
'=======================================
Private Sub DeleteLog()

    Dim sSql As String

    On Error Resume Next

    sSql = "DELETE T_AUTOLOG WHERE AL_datetime+" & lCtLogLimit & "<SYSDATE"
    oraDB.ExecuteSQL sSql

End Sub

'=======================================
'   自動処理起動インターバルを設定
'=======================================
Private Sub SetInterval()

    Dim n As Long

    n = GetPrivateProfileInt("Settings", "Interval", 0, iniFile)
    If n <> lCtAutoInterval Then
        Call WritePrivateProfileString("Settings", "Interval", str$(lCtAutoInterval), iniFile)
    End If

End Sub

'=======================================
'　順序番号取得
'=======================================
Public Function GetNextVal(name) As String
    Dim sSql As String
    Dim oraDS As OraDynaset

    sSql = "SELECT " & name & ".NEXTVAL FROM DUAL"
    Set oraDS = oraDB.CreateDynaset(sSql, ORADYN_READONLY)
    If Not oraDS.EOF Then
        GetNextVal = oraDS.Fields(0).Value
    End If
End Function

'=======================================
'　メールアドレスチェック
'=======================================
Public Function MailAdrCheck(mail_adr As String) As Boolean
    Const mc = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ{}./+-*=~!#$%^&_?"
    Dim a As Integer
    Dim c As String
    Dim i As Integer
    Dim n As Integer

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

'=======================================
'　半角変換
'=======================================
Public Function ToHankaku(str As String) As String
    Const zenkaku = _
            "０１２３４５６７８９．－＠ＡＢＣＤＥＦＧＨＩＪＫＬＭＮＯＰＱＲＳＴＵＶＷＸＹＺａｂｃｄｅｆｇｈｉｊｋｌｍｎｏｐｑｒｓｔｕｖｗｘｙｚ"
    Const hankaku = _
            "0123456789.-@ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz"
    Dim i As Integer
    Dim l As Integer

    ToHankaku = str
    l = Len(zenkaku)
    For i = 1 To l
        ToHankaku = Replace(ToHankaku, Mid(zenkaku, i, 1), Mid(hankaku, i, 1))
    Next
End Function

'=======================================
'　全角変換
'=======================================
Public Function ToZenkaku(str As String) As String
    Const zenkaku = _
            "０１２３４５６７８９"
    Const hankaku = _
            "0123456789"
    Dim i As Integer
    Dim l As Integer

    ToZenkaku = str
    l = Len(hankaku)
    For i = 1 To l
        ToZenkaku = Replace(ToZenkaku, Mid(hankaku, i, 1), Mid(zenkaku, i, 1))
    Next
End Function

'=======================================
'　数字チェック
'=======================================
Public Function NumCheck(num) As Boolean
    Dim i As Integer
    Dim n As Integer

    NumCheck = True
    n = Len(num)
    For i = 1 To n
        If Mid(num, i, 1) < "0" Or Mid(num, i, 1) > "9" Then
            NumCheck = False
            Exit For
        End If
    Next
End Function

'=======================================
'　パスワード作成
'=======================================
Public Function GenaratePassword() As String
    Dim pwd As String
    Dim pch1 As Integer
    Dim pch2 As Integer
    Dim pch0 As Integer

    Randomize
    pwd = ""
    Do While Len(pwd) < 8
      pch1 = Int(Rnd * 100)
        pch2 = Int(Rnd * 10)
        pch0 = pch1 + pch2
        If (pch0 >= 48 And pch0 <= 57) Or (pch0 >= 65 And pch0 <= 90) Or (pch0 >= 97 And pch0 <= 122) Then
            pwd = pwd & Chr(pch0)
        End If
    Loop
  GenaratePassword = pwd
End Function

'=======================================
'　SQL文字列変換
'=======================================
Public Function SqlStr(str) As String
    SqlStr = Replace(str, "'", "''")
End Function

'=======================================
'　特別ポイント付与
'=======================================
Private Sub SpcialPoint()
    Dim sSql As String
    Dim oraDS As OraDynaset

    On Error GoTo ErrHandler

    oraDB.BeginTrans

    sSql = "SELECT SP_seq_no,SP_point_type,SP_point,MM_member_id FROM T_SP_POINT,T_MEMBER WHERE SP_status='0' AND SP_mail_adr=MM_mail_adr"
    Set oraDS = oraDB.CreateDynaset(sSql, ORADYN_READONLY)
    Do Until oraDS.EOF
        sSql = "UPDATE T_SP_POINT SET SP_status='1',SP_member_id='" & oraDS("MM_member_id").Value & "' WHERE SP_seq_no=" & oraDS("SP_seq_no").Value
        oraDB.ExecuteSQL sSql
        Call AddPoint(oraDS("MM_member_id").Value, oraDS("SP_point_type").Value, oraDS("SP_point").Value)
        oraDS.MoveNext
    Loop

    oraDB.CommitTrans
    Exit Sub

ErrHandler:
    oraDB.Rollback
    Call WriteLog(LOG_ERROR, "Program Error: " & Err.Description)
End Sub

'=======================================
'　ポイント追加処理
'=======================================
Private Sub AddPoint(member_id As String, point_type As String, point As Double)
    Dim sql As String
    Dim ds As OraDynaset
    Dim seq_no As Double

    Do While True
        sql = "SELECT MAX(PL_seq_no) FROM T_POINT_LOG" & vbCrLf & _
                    "WHERE PL_member_id='" & member_id & "' AND PL_point_type='" & point_type & "'"
        Set ds = oraDB.CreateDynaset(sql, ORADYN_READONLY)
        If ds.EOF Then
            seq_no = 1
        Else
            seq_no = NVL(ds(0).Value, 0) + 1
        End If

        sql = "SELECT * FROM T_POINT_LOG" & vbCrLf & _
                    "WHERE PL_member_id='" & member_id & "' AND PL_point_type='" & point_type & "' AND PL_seq_no=" & seq_no
        Set ds = oraDB.CreateDynaset(sql, ORADYN_DEFAULT)
        If ds.EOF Then
            ds.AddNew
            ds("PL_member_id").Value = member_id
            ds("PL_point_type").Value = point_type
            ds("PL_seq_no").Value = seq_no
            ds("PL_point").Value = point
            ds("PL_date").Value = Now
            ds.Update
            Exit Do
        End If
    Loop

    sql = "SELECT * FROM T_POINT_TOTAL" & vbCrLf & _
                "WHERE PT_member_id='" & member_id & "' AND PT_point_type='" & point_type & "'"
    Set ds = oraDB.CreateDynaset(sql, ORADYN_DEFAULT)
    If ds.EOF Then
        ds.AddNew
        ds("PT_member_id").Value = member_id
        ds("PT_point_type").Value = point_type
        ds("PT_total_point").Value = point
    Else
        ds.Edit
        ds("PT_total_point").Value = CDbl(ds("PT_total_point").Value) + point
    End If
    ds.Update
End Sub

'=======================================
'　CLOBの読み込み
'=======================================
Function ReadCLOB(clob As OraClob) As Variant
    Dim size As Long

    If IsObject(clob) Then
        If Not clob.IsNull Then
            size = clob.size
            If size > 0 Then
                clob.Read ReadCLOB, size * 2
            End If
        End If
    End If
End Function


