Attribute VB_Name = "modMain"
Option Explicit

'�f�[�^�x�[�X�ڑ����
Const ADO_PROVIDER = "sqloledb"
Const ADO_SERVER = ""
Const ADO_DATABASE = "melmeldb"
Const ADO_USERID = "melmeladm"
Const ADO_PASSWORD = "do!house"

'���O���
Public Const LOG_NORMAL0 = 0
Public Const LOG_NORMAL1 = 1
Public Const LOG_ERROR = 9

'�f�t�H���g�L���J�n�A�I���r��
Public Const KOUKOKU_START_LINE = "=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-//    PR//-=-="
Public Const KOUKOKU_END_LINE = "=-=-//KKKKKK-MMMMM//-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-="

'�N���b�N�J�E���g�pURL
Public Const ClickURL = "http://www.melmel.net/m.asp?p=123456789012"

'�L���ŏ�������
Public Const MIN_MOJISUU = 35

'Win32API
Public Declare Function DeleteFile Lib "kernel32" Alias "DeleteFileA" (ByVal lpFileName As String) As Long
Private Declare Function GetTempPath Lib "kernel32" _
         Alias "GetTempPathA" (ByVal nBufferLength As Long, _
         ByVal lpBuffer As String) As Long
Public Declare Function GetPrivateProfileInt Lib "kernel32" Alias "GetPrivateProfileIntA" (ByVal lpApplicationName As String, ByVal lpKeyName As String, ByVal nDefault As Long, ByVal lpFileName As String) As Long
Public Declare Function WritePrivateProfileString Lib "kernel32" Alias "WritePrivateProfileStringA" (ByVal lpApplicationName As String, ByVal lpKeyName As Any, ByVal lpString As Any, ByVal lpFileName As String) As Long

'�O���[�o���ϐ�
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
'   ���C������
'=======================================
Public Sub Main()

    Dim iKariMag As Integer
    Dim iHonMag As Integer
    Dim iHoukoku As Integer
    Dim iKoukokuTest As Integer
    Dim iKind As Integer

    '���ɋN�����������牽�����Ȃ�
    If App.PrevInstance Then
        Exit Sub
    End If

    '�f�[�^�x�[�X�ڑ�
    If ConxxxtDB(objDB) = False Then
        Exit Sub
    End If

    '�R���g���[���}�X�^���擾
    If ReadContrxxxaster = False Then
        Exit Sub
    End If

    '���[���ڑ�
    If ConxxxtMail = False Then
        Exit Sub
    End If

    '�e���|�����f�B���N�g���擾
    sTempDir = GetTempDir()

    '���o�^�����}�K��M
    iKariMag = RecvKariMag

    '�{�o�^�����}�K��M
    iHonMag = RecvHonMag

    '�f�ڎ��ѕ񍐏���M
    iHoukoku = RecvHoukoku

    '�L���e�X�g���[����M
    iKoukokuTest = RecvKoukokuTest

    '���у|�C���g����
    Call SetJissekiPoint

    '�������������O�ɏ�������
    If iKariMag = 0 And iHonMag = 0 And iHoukoku = 0 And iKoukokuTest = 0 Then
        iKind = LOG_NORMAL0
    Else
        iKind = LOG_NORMAL1
    End If
    Call WriteLog(iKind, "�������� " & _
        "[���o�^�����}�K:" & iKariMag & "]" & _
        "[�{�o�^�����}�K:" & iHonMag & "]" & _
        "[�f�ڕ񍐏�:" & iHoukoku & "]" & _
        "[�L���e�X�g:" & iKoukokuTest & "]")

    '�ۑ��������߂������O���폜
    Call DeleteLog

    '���������N���C���^�[�o����ݒ�
    Call SetInterval

    objDB.Close

End Sub

'=======================================
'   �c�a�ڑ�����
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
'   �R���g���[���}�X�^���擾
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
        Call WriteLog(LOG_ERROR, "�R���g���[���}�X�^�̓ǂݍ��݂Ɏ��s���܂����B")
        ReadContrxxxaster = False
    End If

    objRS.Close

End Function

'=======================================
'   ���[���R���|�[�l���g(BASP21)�쐬
'=======================================
Private Function ConxxxtMail() As Boolean

    Set objMail = CreateObject("basp21")

    ConxxxtMail = True

End Function

'=======================================
'   ���[����M����
'=======================================
Public Function RecvMail(sPop3Server As String, sPop3User As String, sPop3Pass As String, lNum As Long) As Variant

    Dim vStat As Variant

    On Error Resume Next

    '��M�������[�������擾
    vStat = objMail.RcvMail(sPop3Server, sPop3User, sPop3Pass, "STAT", sTempDir)
    If VarType(vStat) = vbString Then
        If vStat <> "" Then
            Call WriteLog(LOG_ERROR, "���[����M�Ɏ��s���܂����B[Server:" & sPop3Server & "][Msg:" & vStat & "]")
        End If
        Exit Function
    End If
    lNum = CLng(Left(vStat(0), InStr(vStat(0), " ") - 1))
    If lNum > 256 Then
        lNum = 256
    End If

    '�O�łȂ���Ύ�M
    If lNum > 0 Then
        RecvMail = objMail.RcvMail(sPop3Server, sPop3User, sPop3Pass, "SAVE 1-" & lNum, sTempDir)
        If VarType(RecvMail) = vbString Then
            If RecvMail <> "" Then
                Call WriteLog(LOG_ERROR, "���[����M�Ɏ��s���܂����B[Server:" & sPop3Server & "][Msg:" & RecvMail & "]")
            End If
        End If
    End If
End Function

'=======================================
'   ���[���폜����
'=======================================
Public Sub DeleteMail(sPop3Server As String, sPop3User As String, sPop3Pass As String, lNum As Long)

    Dim vDelete As Variant

    On Error Resume Next

    If lNum > 0 Then
        vDelete = objMail.RcvMail(sPop3Server, sPop3User, sPop3Pass, "DELE 1-" & lNum, sTempDir)
    End If
End Sub

'=======================================
'   ���[���e���|�����t�@�C���폜����
'=======================================
Public Sub DeleteMailTemp(vFiles As Variant)

    Dim vFile As Variant

    On Error Resume Next

    If IsArray(vFiles) Then
        For Each vFile In vFiles
            '�e���|�����t�@�C���폜
            Call DeleteFile(vFile)
        Next
    End If
End Sub

'=======================================
'   ���[�����M����
'=======================================
Public Function SendMail(mailto As String, mailfrom As String, subject As String, body As String) As Variant

    On Error Resume Next

    SendMail = objMail.SendMail(sSmtpServer1, mailto, mailfrom, subject, body, "")
    If SendMail <> "" Then
        Call WriteLog(LOG_ERROR, "���[���̑��M�Ɏ��s���܂����B[Server:" & sSmtpServer1 & "][Msg:" & SendMail & "]")
        If sSmtpServer2 <> "" Then
            SendMail = objMail.SendMail(sSmtpServer2, mailto, mailfrom, subject, body, "")
            If SendMail <> "" Then
                Call WriteLog(LOG_ERROR, "���[���̑��M�Ɏ��s���܂����B[Server:" & sSmtpServer2 & "][Msg:" & SendMail & "]")
            End If
        End If
    End If
End Function

'=======================================
'   ���M���[���e���v���[�g�擾
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
        Call WriteLog(LOG_ERROR, "���M���[���e���v���[�g�̎擾�Ɏ��s���܂����B[ID:" & id & "]")
    End If

    objRS.Close

End Sub

'=======================================
'   �e���|�����f�B���N�g���擾
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
'   NULL�l�ϊ�����
'=======================================
Public Function NVL(vValue As Variant, vNullValue) As String

    If IsNull(vValue) Then
        NVL = vNullValue
    Else
        NVL = vValue
    End If

End Function

'=======================================
'   ���O�������ݏ���
'=======================================
Public Sub WriteLog(iKind As Integer, sMessage As String)

    Dim sSql As String

    On Error Resume Next

    sSql = "INSERT INTO T_AUTOLOG (AL_kind,AL_message,AL_datetime) VALUES" & _
            "(" & iKind & ",'" & Replace(sMessage, "'", "''") & "','" & Now() & "')"
    objDB.Execute sSql

End Sub

'=======================================
'   �ۑ��������߂������O���폜
'=======================================
Private Sub DeleteLog()

    Dim sSql As String

    On Error Resume Next

    sSql = "DELETE T_AUTOLOG WHERE DATEADD(day," & lLogLimit & ",AL_datetime) < GETDATE()"
    objDB.Execute sSql

End Sub

'=======================================
'   ���������N���C���^�[�o����ݒ�
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
'�@�S�p�������Z�ł�Right()�֐�
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
'�@�S�p�������Z�ł�Len()�֐�
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
'   ���у|�C���g���Z�b�g����
'=======================================
Public Sub ResetJissekiPoint(sMagId As String, sKoukokuId As String)

    Dim sSql As String

    '���у|�C���g���Čv�Z���邽�߂ɉ񐔂�NULL���Z�b�g
    sSql = "UPDATE T_JPOINT SET JP_kaisuu=NULL WHERE JP_mag_id='" & sMagId & "' AND JP_koukoku_id='" & sKoukokuId & "'"
    objDB.Execute sSql

End Sub


'=======================================
'   ���у|�C���g���Z����
'=======================================
Public Sub SetJissekiPoint()

    Dim sSql As String
    Dim sSub1 As String
    Dim sSub2 As String
    Dim objRS As ADODB.Recordset
    Dim dPoint As Double

    On Error GoTo ErrHandler

    objDB.BeginTrans

    '�f�ڕ񍐃��O����f�ڐ��ƕ������擾
    sSub1 = "SELECT mag_id,koukoku_id,COUNT(*) AS cnt,SUM(busuu) AS busuu" & _
            " FROM V_KEISAI_HOUKOKU" & _
            " GROUP BY mag_id,koukoku_id"

    '���у|�C���g����������Ă��Ȃ����̂������o
    sSub2 = "SELECT mag_id,koukoku_id,cnt,busuu,JP_mag_id,JP_point" & _
            " FROM (" & sSub1 & ") AS SUB1 LEFT OUTER JOIN T_JPOINT ON mag_id=JP_mag_id AND koukoku_id=JP_koukoku_id" & _
            " WHERE JP_mag_id IS NULL OR JP_kaisuu IS NULL"

    '�L���s���Ɣ��s��ID���擾
    sSql = "SELECT mag_id,koukoku_id,cnt,busuu,KK_koukoku_line,KK_koukoku_type,MG_mel_id,JP_mag_id,JP_point" & _
            " FROM (" & sSub2 & ") AS SUB2" & _
            " INNER JOIN V_KOUKOKU ON koukoku_id=KK_koukoku_id" & _
            " INNER JOIN T_MELMAG ON mag_id=MG_mag_id"

    Set objRS = CreateObject("ADODB.RecordSet")
    objRS.Open sSql, objDB, adOpenKeyset, adLockReadOnly

    Do Until objRS.EOF
        '�|�C���g�̌v�Z
        Select Case objRS("KK_koukoku_type")
        Case "A", "B"
            Select Case objRS("KK_koukoku_line")
            Case 5
                If objRS("cnt") = 1 Then
                    dPoint = objRS("busuu") * 0.3   '�^�C�vA/B�A5�s�A�P��
                Else
                    dPoint = objRS("busuu") * 0.2   '�^�C�vA/B�A5�s�A�Q�ʈȏ�
                End If
            Case 10
                If objRS("cnt") = 1 Then
                    dPoint = objRS("busuu") * 0.5   '�^�C�vA/B�A10�s�A�P��
                Else
                    dPoint = objRS("busuu") * 0.4   '�^�C�vA/B�A10�s�A�Q�ʈȏ�
                End If
            Case Else
                dPoint = 0
            End Select
        Case "Q", "R"
            Select Case objRS("KK_koukoku_line")
            Case 5
                If objRS("cnt") = 1 Then
                    dPoint = objRS("busuu") * 0.15  '�^�C�vQ/R�A5�s�A�P��
                Else
                    dPoint = objRS("busuu") * 0.1   '�^�C�vQ/R�A5�s�A�Q�ʈȏ�
                End If
            Case 10
                If objRS("cnt") = 1 Then
                    dPoint = objRS("busuu") * 0.2   '�^�C�vQ/R�A10�s�A�P��
                Else
                    dPoint = objRS("busuu") * 0.15  '�^�C�vQ/R�A10�s�A�Q�ʈȏ�
                End If
            Case Else
                dPoint = 0
            End Select
        End Select

        If IsNull(objRS("JP_mag_id")) Then
            '���у|�C���g���O�ɒǉ�
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

        '���s�҃}�X�^�̎��у|�C���g�ɉ��Z
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
'   �N���b�N�J�E���g�pURL���ߍ���
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

    '������
    sRand = "5374112481591282435131234042196122667712144723901683762966630824862004266405453034177296253053378416"

    '�L��ID�Í���
    iOffset = CLng(sMagId) Mod 100
    For i = 1 To 6
      Mid(sKoukokuId, i, 1) = Chr((CInt(Mid(sKoukokuId, i, 1)) + CInt(Mid(sRand, iOffset + 1, 1))) Mod 10 + Asc("0"))
      iOffset = (iOffset + 1) Mod 100
    Next

    '�����}�KID�Í���
    iOffset = CLng(sKoukokuId) Mod 100
    For i = 1 To 5
      Mid(sMagId, i, 1) = Chr((CInt(Mid(sMagId, i, 1)) + CInt(Mid(sRand, iOffset + 1, 1))) Mod 10 + Asc("0"))
      iOffset = (iOffset + 1) Mod 100
    Next

    '�p�����[�^����
    sParam = sKoukokuId & sMagId

    '�`�F�b�N�T���t��
    iCheckSum = 0
    For i = 1 To 11
      iCheckSum = (iCheckSum + CInt(Mid(sParam, i, 1))) Mod 10
    Next
    sParam = sParam & Chr((10 - iCheckSum) Mod 10 + Asc("0"))

    '�N���b�N�J�E���g�pURL���ߍ���
    sURL = Replace(ClickURL, "123456789012", sParam)
    sKoukokuMsg = Replace(sKoukokuMsg, ClickURL, sURL)

End Sub



