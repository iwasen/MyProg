Attribute VB_Name = "modMain"
Option Explicit

'データベース接続情報
Const ADO_PROVIDER = "sqloledb"
Const ADO_SERVER = ""
Const ADO_DATABASE = "melmeldb"
Const ADO_USERID = "melmeladm"
Const ADO_PASSWORD = "do!house"

'ログ種類
Public Const LOG_NORMAL0 = 0
Public Const LOG_NORMAL1 = 1
Public Const LOG_ERROR = 9

'デフォルト広告開始、終了罫線
Public Const KOUKOKU_START_LINE = "=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-//    PR//-=-="
Public Const KOUKOKU_END_LINE = "=-=-//KKKKKK-MMMMM//-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-="

'クリックカウント用URL
Public Const ClickURL = "http://www.melmel.net/m.asp?p=123456789012"

'広告最小文字数
Public Const MIN_MOJISUU = 35

'Win32API
Public Declare Function DeleteFile Lib "kernel32" Alias "DeleteFileA" (ByVal lpFileName As String) As Long
Private Declare Function GetTempPath Lib "kernel32" _
         Alias "GetTempPathA" (ByVal nBufferLength As Long, _
         ByVal lpBuffer As String) As Long
Public Declare Function GetPrivateProfileInt Lib "kernel32" Alias "GetPrivateProfileIntA" (ByVal lpApplicationName As String, ByVal lpKeyName As String, ByVal nDefault As Long, ByVal lpFileName As String) As Long
Public Declare Function WritePrivateProfileString Lib "kernel32" Alias "WritePrivateProfileStringA" (ByVal lpApplicationName As String, ByVal lpKeyName As Any, ByVal lpString As Any, ByVal lpFileName As String) As Long

'グローバル変数
Public objDB As ADODB.Conxxxtion
Public objMail As Basp21
Public sSmtpServer1 As String
Public sSmtpServer2 As String
Public sPop3Server1 As String
Public sPop3Server2 As String
Public sPop3KmagUser As String
Public sPop3KmagPass As String
Public sPop3HmagUser1 As String
Public sPop3HmagPass1 As String
Public sPop3HmagUser2 As String
Public sPop3HmagPass2 As String
Public sPop3ReportUser As String
Public sPop3ReportPass As String
Public sPop3TestUser As String
Public sPop3TestPass As String
Public sFromAddress As String
Private lLogLimit As Long
Private lAutoInterval As Long
Private sTempDir As String

'=======================================
'   メイン処理
'=======================================
Public Sub Main()

    Dim iKariMag As Integer
    Dim iHonMag As Integer
    Dim iHoukoku As Integer
    Dim iKoukokuTest As Integer
    Dim iKind As Integer

    '既に起動中だったら何もしない
    If App.PrevInstance Then
        Exit Sub
    End If

    'データベース接続
    If ConxxxtDB(objDB) = False Then
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

    '仮登録メルマガ受信
    iKariMag = RecvKariMag

    '本登録メルマガ受信
    iHonMag = RecvHonMag

    '掲載実績報告書受信
    iHoukoku = RecvHoukoku

    '広告テストメール受信
    iKoukokuTest = RecvKoukokuTest

    '実績ポイント処理
    Call SetJissekiPoint

    '処理件数をログに書き込み
    If iKariMag = 0 And iHonMag = 0 And iHoukoku = 0 And iKoukokuTest = 0 Then
        iKind = LOG_NORMAL0
    Else
        iKind = LOG_NORMAL1
    End If
    Call WriteLog(iKind, "処理件数 " & _
        "[仮登録メルマガ:" & iKariMag & "]" & _
        "[本登録メルマガ:" & iHonMag & "]" & _
        "[掲載報告書:" & iHoukoku & "]" & _
        "[広告テスト:" & iKoukokuTest & "]")

    '保存期限を過ぎたログを削除
    Call DeleteLog

    '自動処理起動インターバルを設定
    Call SetInterval

    objDB.Close

End Sub

'=======================================
'   ＤＢ接続処理
'=======================================
Private Function ConxxxtDB(objDB As ADODB.Conxxxtion) As Boolean

    On Error Resume Next

    Set objDB = CreateObject("ADODB.Conxxxtion")

    With objDB

    .Provider = ADO_PROVIDER
    .Properties("Data Source").Value = ADO_SERVER
    .Properties("Initial Catalog").Value = ADO_DATABASE
    .Properties("User ID").Value = ADO_USERID
    .Properties("Password").Value = ADO_PASSWORD
  
    .Open
    If Err.Number = 0 Then
        ConxxxtDB = True
    Else
        ConxxxtDB = False
    End If

    End With

End Function

'=======================================
'   コントロールマスタ情報取得
'=======================================
Private Function ReadContrxxxaster() As Boolean

    Dim objRS As New ADODB.Recordset

    On Error Resume Next

    objRS.Open "select * from T_CONTROL", objDB, adOpenForwardOnly, adLockReadOnly

    If Not objRS.EOF Then
        sSmtpServer1 = objRS("CT_smtp_server1")
        sSmtpServer2 = NVL(objRS("CT_smtp_server2"), "")
        sPop3Server1 = objRS("CT_pop3_server1")
        sPop3Server2 = NVL(objRS("CT_pop3_server2"), "")
        sPop3KmagUser = objRS("CT_pop3_kmag_user")
        sPop3KmagPass = objRS("CT_pop3_kmag_pass")
        sPop3HmagUser1 = objRS("CT_pop3_hmag_user1")
        sPop3HmagPass1 = objRS("CT_pop3_hmag_pass1")
        sPop3HmagUser2 = objRS("CT_pop3_hmag_user2")
        sPop3HmagPass2 = objRS("CT_pop3_hmag_pass2")
        sPop3ReportUser = objRS("CT_pop3_report_user")
        sPop3ReportPass = objRS("CT_pop3_report_pass")
        sPop3TestUser = objRS("CT_pop3_test_user")
        sPop3TestPass = objRS("CT_pop3_test_pass")
        sFromAddress = objRS("CT_from_adr")
        lLogLimit = objRS("CT_log_limit")
        lAutoInterval = objRS("CT_auto_interval")
        ReadContrxxxaster = True
    Else
        Call WriteLog(LOG_ERROR, "コントロールマスタの読み込みに失敗しました。")
        ReadContrxxxaster = False
    End If

    objRS.Close

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
    If lNum > 256 Then
        lNum = 256
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
'   メール送信処理
'=======================================
Public Function SendMail(mailto As String, mailfrom As String, subject As String, body As String) As Variant

    On Error Resume Next

    SendMail = objMail.SendMail(sSmtpServer1, mailto, mailfrom, subject, body, "")
    If SendMail <> "" Then
        Call WriteLog(LOG_ERROR, "メールの送信に失敗しました。[Server:" & sSmtpServer1 & "][Msg:" & SendMail & "]")
        If sSmtpServer2 <> "" Then
            SendMail = objMail.SendMail(sSmtpServer2, mailto, mailfrom, subject, body, "")
            If SendMail <> "" Then
                Call WriteLog(LOG_ERROR, "メールの送信に失敗しました。[Server:" & sSmtpServer2 & "][Msg:" & SendMail & "]")
            End If
        End If
    End If
End Function

'=======================================
'   送信メールテンプレート取得
'=======================================
Public Sub GetSendMailInfo(id As String, subject As String, from As String, body As String)

    Dim sSql As String
    Dim objRS As ADODB.Recordset

    sSql = "SELECT * FROM T_SENDMAIL WHERE SM_id='" & id & "'"
    Set objRS = objDB.Execute(sSql)
    If Not objRS.EOF Then
        subject = objRS("SM_subject")
        from = objRS("SM_from")
        body = objRS("SM_body")
    Else
        Call WriteLog(LOG_ERROR, "送信メールテンプレートの取得に失敗しました。[ID:" & id & "]")
    End If

    objRS.Close

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
Public Sub WriteLog(iKind As Integer, sMessage As String)

    Dim sSql As String

    On Error Resume Next

    sSql = "INSERT INTO T_AUTOLOG (AL_kind,AL_message,AL_datetime) VALUES" & _
            "(" & iKind & ",'" & Replace(sMessage, "'", "''") & "','" & Now() & "')"
    objDB.Execute sSql

End Sub

'=======================================
'   保存期限を過ぎたログを削除
'=======================================
Private Sub DeleteLog()

    Dim sSql As String

    On Error Resume Next

    sSql = "DELETE T_AUTOLOG WHERE DATEADD(day," & lLogLimit & ",AL_datetime) < GETDATE()"
    objDB.Execute sSql

End Sub

'=======================================
'   自動処理起動インターバルを設定
'=======================================
Private Sub SetInterval()

    Const iniFile = ".\MelAuto.ini"
    Dim n As Long

    n = GetPrivateProfileInt("Settings", "Interval", 0, iniFile)
    If n <> lAutoInterval And n <> 0 Then
        Call WritePrivateProfileString("Settings", "Interval", Str$(lAutoInterval), iniFile)
    End If

End Sub

'=======================================
'　全角文字換算でのRight()関数
'=======================================
Public Function ZenkakuRight(s, l) As String
    Dim a As Integer
    Dim i As Integer
    Dim n As Integer

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

'=======================================
'　全角文字換算でのLen()関数
'=======================================
Public Function ZenkakuLen(s) As Integer
    Dim a As Integer
    Dim i As Integer
    Dim n As Integer

  n = 0
  For i = 1 To Len(s)
    a = Asc(Mid(s, i, 1))
    If a >= 0 And a < 256 Then
      n = n + 1
    Else
      n = n + 2
    End If
  Next

  ZenkakuLen = n \ 2
End Function

'=======================================
'   実績ポイントリセット処理
'=======================================
Public Sub ResetJissekiPoint(sMagId As String, sKoukokuId As String)

    Dim sSql As String

    '実績ポイントを再計算するために回数にNULLをセット
    sSql = "UPDATE T_JPOINT SET JP_kaisuu=NULL WHERE JP_mag_id='" & sMagId & "' AND JP_koukoku_id='" & sKoukokuId & "'"
    objDB.Execute sSql

End Sub


'=======================================
'   実績ポイント加算処理
'=======================================
Public Sub SetJissekiPoint()

    Dim sSql As String
    Dim sSub1 As String
    Dim sSub2 As String
    Dim objRS As ADODB.Recordset
    Dim dPoint As Double

    On Error GoTo ErrHandler

    objDB.BeginTrans

    '掲載報告ログから掲載数と部数を取得
    sSub1 = "SELECT mag_id,koukoku_id,COUNT(*) AS cnt,SUM(busuu) AS busuu" & _
            " FROM V_KEISAI_HOUKOKU" & _
            " GROUP BY mag_id,koukoku_id"

    '実績ポイントが処理されていないものだけ抽出
    sSub2 = "SELECT mag_id,koukoku_id,cnt,busuu,JP_mag_id,JP_point" & _
            " FROM (" & sSub1 & ") AS SUB1 LEFT OUTER JOIN T_JPOINT ON mag_id=JP_mag_id AND koukoku_id=JP_koukoku_id" & _
            " WHERE JP_mag_id IS NULL OR JP_kaisuu IS NULL"

    '広告行数と発行者IDを取得
    sSql = "SELECT mag_id,koukoku_id,cnt,busuu,KK_koukoku_line,KK_koukoku_type,MG_mel_id,JP_mag_id,JP_point" & _
            " FROM (" & sSub2 & ") AS SUB2" & _
            " INNER JOIN V_KOUKOKU ON koukoku_id=KK_koukoku_id" & _
            " INNER JOIN T_MELMAG ON mag_id=MG_mag_id"

    Set objRS = CreateObject("ADODB.RecordSet")
    objRS.Open sSql, objDB, adOpenKeyset, adLockReadOnly

    Do Until objRS.EOF
        'ポイントの計算
        Select Case objRS("KK_koukoku_type")
        Case "A", "B"
            Select Case objRS("KK_koukoku_line")
            Case 5
                If objRS("cnt") = 1 Then
                    dPoint = objRS("busuu") * 0.3   'タイプA/B、5行、１通
                Else
                    dPoint = objRS("busuu") * 0.2   'タイプA/B、5行、２通以上
                End If
            Case 10
                If objRS("cnt") = 1 Then
                    dPoint = objRS("busuu") * 0.5   'タイプA/B、10行、１通
                Else
                    dPoint = objRS("busuu") * 0.4   'タイプA/B、10行、２通以上
                End If
            Case Else
                dPoint = 0
            End Select
        Case "Q", "R"
            Select Case objRS("KK_koukoku_line")
            Case 5
                If objRS("cnt") = 1 Then
                    dPoint = objRS("busuu") * 0.15  'タイプQ/R、5行、１通
                Else
                    dPoint = objRS("busuu") * 0.1   'タイプQ/R、5行、２通以上
                End If
            Case 10
                If objRS("cnt") = 1 Then
                    dPoint = objRS("busuu") * 0.2   'タイプQ/R、10行、１通
                Else
                    dPoint = objRS("busuu") * 0.15  'タイプQ/R、10行、２通以上
                End If
            Case Else
                dPoint = 0
            End Select
        End Select

        If IsNull(objRS("JP_mag_id")) Then
            '実績ポイントログに追加
            sSql = "INSERT INTO T_JPOINT VALUES ('" & _
                    objRS("mag_id") & "','" & _
                    objRS("koukoku_id") & "'," & _
                    objRS("cnt") & "," & _
                    objRS("busuu") & "," & _
                    dPoint & ",GETDATE())"
            objDB.Execute sSql
        Else
            sSql = "UPDATE T_JPOINT SET JP_kaisuu=" & objRS("cnt") & ",JP_busuu=" & objRS("busuu") & ",JP_point=" & dPoint & _
                   " WHERE JP_mag_id='" & objRS("mag_id") & "' AND JP_koukoku_id='" & objRS("koukoku_id") & "'"
            objDB.Execute sSql
            dPoint = dPoint - NVL(objRS("JP_point"), 0)
        End If

        '発行者マスタの実績ポイントに加算
        sSql = "UPDATE T_MELMEL SET MM_point=MM_point+" & dPoint & " WHERE MM_mel_id='" & objRS("MG_mel_id") & "'"
        objDB.Execute sSql

        objRS.MoveNext
    Loop

    objRS.Close
    objDB.CommitTrans

    Exit Sub

ErrHandler:
    objDB.RollbackTrans

    If objDB.Errors.Count <> 0 Then
        Call WriteLog(LOG_ERROR, "SQL Server Error: " & objDB.Errors.Item(0))
    Else
        Call WriteLog(LOG_ERROR, "Program Error: " & Err.Description)
    End If

End Sub

'=======================================
'   クリックカウント用URL埋め込み
'=======================================
Public Sub SetClickCountURL(sKoukokuMsg As String, vKoukokuId As Variant, vMagId As Variant, vKoukokuUrl As Variant)

    Dim sKoukokuId As String
    Dim sMagId As String
    Dim sRand As String
    Dim iOffset As Integer
    Dim iCheckSum As Integer
    Dim sParam As String
    Dim sURL As String
    Dim i As Integer

    If IsNull(vKoukokuUrl) Or IsEmpty(vKoukokuUrl) Then
        Exit Sub
    End If

    sKoukokuId = vKoukokuId
    sMagId = vMagId

    '乱数列
    sRand = "5374112481591282435131234042196122667712144723901683762966630824862004266405453034177296253053378416"

    '広告ID暗号化
    iOffset = CLng(sMagId) Mod 100
    For i = 1 To 6
      Mid(sKoukokuId, i, 1) = Chr((CInt(Mid(sKoukokuId, i, 1)) + CInt(Mid(sRand, iOffset + 1, 1))) Mod 10 + Asc("0"))
      iOffset = (iOffset + 1) Mod 100
    Next

    'メルマガID暗号化
    iOffset = CLng(sKoukokuId) Mod 100
    For i = 1 To 5
      Mid(sMagId, i, 1) = Chr((CInt(Mid(sMagId, i, 1)) + CInt(Mid(sRand, iOffset + 1, 1))) Mod 10 + Asc("0"))
      iOffset = (iOffset + 1) Mod 100
    Next

    'パラメータ生成
    sParam = sKoukokuId & sMagId

    'チェックサム付加
    iCheckSum = 0
    For i = 1 To 11
      iCheckSum = (iCheckSum + CInt(Mid(sParam, i, 1))) Mod 10
    Next
    sParam = sParam & Chr((10 - iCheckSum) Mod 10 + Asc("0"))

    'クリックカウント用URL埋め込み
    sURL = Replace(ClickURL, "123456789012", sParam)
    sKoukokuMsg = Replace(sKoukokuMsg, ClickURL, sURL)

End Sub



