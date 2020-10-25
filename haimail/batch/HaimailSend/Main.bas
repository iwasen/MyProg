Attribute VB_Name = "modMain"
Option Explicit

'�f�[�^�x�[�X�ڑ����
Const ORA_DBNAME = "haimail"
Const ORA_USER = "haimail/do!house"

'���O���
Public Const LOG_NORMAL0 = "0"
Public Const LOG_NORMAL1 = "1"
Public Const LOG_ERROR = "9"

'INI�t�@�C��
Public Const iniFile = ".\HaimailService.ini"

'Win32API
Public Declare Function GetPrivateProfileInt Lib "kernel32" Alias "GetPrivateProfileIntA" (ByVal lpApplicationName As String, ByVal lpKeyName As String, ByVal nDefault As Long, ByVal lpFileName As String) As Long
Public Declare Function WritePrivateProfileString Lib "kernel32" Alias "WritePrivateProfileStringA" (ByVal lpApplicationName As String, ByVal lpKeyName As Any, ByVal lpString As Any, ByVal lpFileName As String) As Long
Public Declare Function GetPrivateProfileString Lib "kernel32" Alias "GetPrivateProfileStringA" (ByVal lpApplicationName As String, ByVal lpKeyName As Any, ByVal lpDefault As String, ByVal lpReturnedString As String, ByVal nSize As Long, ByVal lpFileName As String) As Long

'�O���[�o���ϐ�
Public oraSess As OraSessionClass
Public oraDB As OraDatabase
Private objMail As Basp21
Public sCtSmtpServer1 As String
Public sCtSmtpServer2 As String
Public sCtSokuhouAdr As String
Public sCtSendReportAdr As String
Public lCtReminder As Long
Public sCtSiteUrl As String

'=======================================
'   ���C������
'=======================================
Public Sub Main()

    Dim lDeliveryMail As Long
    Dim lBirthdayMail As Long
    Dim lReminderMail As Long
    Dim sKind As String
    Dim nSchedule As Integer

    '���ɋN�����������牽�����Ȃ�
    If App.PrevInstance Then
        Exit Sub
    End If

    '�f�[�^�x�[�X�ڑ�
    If ConxxxtDB() = False Then
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

    '�N���b�N���X�|���XJOB����
    ClickResponse

    '���[���z�M
    lDeliveryMail = DeliveryMail

    '���ւ��`�F�b�N
    nSchedule = CheckSchedule("EveryDay")
    If nSchedule <> 0 Then
        If nSchedule = 1 Then
            '�a�������[�����M
            lBirthdayMail = BirthdayMail
    
            '���{�o�^�҂ւ̍Ñ����[��
            lReminderMail = ReminderMail
        End If
        SetSchedule "EveryDay", DateAdd("d", 1, Date)
    End If

    '���|�[�g���[�����M
    ReportMail

    '�������������O�ɏ�������
    If lDeliveryMail = 0 And lBirthdayMail = 0 And lReminderMail = 0 Then
        sKind = LOG_NORMAL0
    Else
        sKind = LOG_NORMAL1
    End If
    Call WriteLog(sKind, "�������� " & _
        "[�z�M���[��:" & lDeliveryMail & "][�a�������[��:" & lBirthdayMail & "][�Ñ����[��:" & lReminderMail & "]")

    oraDB.Close

End Sub

'=======================================
'   �c�a�ڑ�����
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
'   �R���g���[���}�X�^���擾
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
        sCtSokuhouAdr = NVL(objDS("CT_sokuhou_adr").Value, "")
        lCtReminder = NVL(objDS("CT_reminder").Value, 0)
        sCtSendReportAdr = NVL(objDS("CT_send_report_adr").Value, "")
        sCtSiteUrl = objDS("CT_site_url").Value
        ReadContrxxxaster = True
    Else
        Call WriteLog(LOG_ERROR, "�R���g���[���}�X�^�̓ǂݍ��݂Ɏ��s���܂����B")
        ReadContrxxxaster = False
    End If

End Function

'=======================================
'   �X�P�W���[���ݒ�
'=======================================
Public Sub SetSchedule(sId As String, dTime As Date)

    Dim sDate As String

    sDate = Format(dTime, "yyyy/mm/dd hh:nn:ss")
    Call WritePrivateProfileString("Schedule", sId, sDate, iniFile)

End Sub

'=======================================
'   �X�P�W���[���`�F�b�N
'=======================================
Public Function CheckSchedule(sId As String) As Integer

    Dim sNextDate As String
    Dim pos As Long
    Dim dNextDate As Date

    sNextDate = String(20, " ")
    Call GetPrivateProfileString("Schedule", sId, "", sNextDate, 20, iniFile)
    pos = InStr(sNextDate, Chr(0))
    If pos > 0 Then
        sNextDate = Left(sNextDate, pos - 1)
    End If
    sNextDate = Trim(sNextDate)

    If sNextDate <> "" Then
        If Now >= CDate(sNextDate) Then
            CheckSchedule = 1
        Else
            CheckSchedule = 0
        End If
    Else
        CheckSchedule = -1
    End If

End Function

'=======================================
'   ���[���R���|�[�l���g(BASP21)�쐬
'=======================================
Private Function ConxxxtMail() As Boolean

    Set objMail = CreateObject("basp21")

    ConxxxtMail = True

End Function

'=======================================
'   ���[�����M����
'=======================================
Public Function SendMail(mailto As String, mailfrom As String, subject As String, body As String) As Variant

    Dim sMailTo As String
    Dim sBody As String

    On Error Resume Next

    SendMail = objMail.SendMail(sCtSmtpServer1, mailto, mailfrom, subject, body, "")
    If SendMail <> "" Then
        Call WriteLog(LOG_ERROR, "���[���̑��M�Ɏ��s���܂����B[Server:" & sCtSmtpServer1 & "][Msg:" & SendMail & "]")
        If sCtSmtpServer2 <> "" Then
            SendMail = objMail.SendMail(sCtSmtpServer2, sMailTo, mailfrom, subject, sBody, "")
            If SendMail <> "" Then
                Call WriteLog(LOG_ERROR, "���[���̑��M�Ɏ��s���܂����B[Server:" & sCtSmtpServer2 & "][Msg:" & SendMail & "]")
            End If
        End If
    End If
End Function

'=======================================
'   ���M���[���e���v���[�g�擾
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
        Call WriteLog(LOG_ERROR, "���M���[���e���v���[�g�̎擾�Ɏ��s���܂����B[ID:" & id & "]")
    End If

End Sub

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
                    body = "�͂��߁[��Enet�ŃG���[���������܂����B" & vbCrLf & vbCrLf & _
                        "Message:" & sMessage & vbCrLf
                    SendMail "error@haimail.net", "error@haimail.net", "�y�͂��߁[��Enet�z���������G���[��", body
                End If
            End If
        End If
    End If
'@@@@@@@@@@@@@@@@@@@@
End Sub

'=======================================
'�@���p�������Z�ł�Len()�֐�
'=======================================
Public Function HankakuLen(s) As Integer
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

  HankakuLen = n
End Function

'=======================================
'�@�����ԍ��擾
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
'�@���[���A�h���X�`�F�b�N
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
'�@�����`�F�b�N
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
'�@SQL������ϊ�
'=======================================
Public Function SqlStr(str) As String
    SqlStr = Replace(str, "'", "''")
End Function

'=======================================
'�@CLOB�̓ǂݍ���
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

'=======================================
'�@�|�C���g�ǉ�����
'=======================================
Public Function AddPoint(sMemberId As String, sPointType As String, lPoint As Long, sMailId As String)
    Dim sSql As String
    Dim oraDS As OraDynaset
    Dim lSeqNo As Long

    oraDB.BeginTrans

    Do While True
        sSql = "SELECT MAX(PL_seq_no) FROM T_POINT_LOG" & vbCrLf & _
                    "WHERE PL_member_id='" & sMemberId & "' AND PL_point_type='" & sPointType & "'"
        Set oraDS = oraDB.CreateDynaset(sSql, ORADYN_READONLY)
        If oraDS.EOF Then
            lSeqNo = 1
        Else
            lSeqNo = NVL(oraDS(0).Value, 0) + 1
        End If

        sSql = "SELECT * FROM T_POINT_LOG" & vbCrLf & _
                    "WHERE PL_member_id='" & sMemberId & "' AND PL_point_type='" & sPointType & "' AND PL_seq_no=" & lSeqNo
        Set oraDS = oraDB.CreateDynaset(sSql, ORADYN_DEFAULT)
        If oraDS.EOF Then
            oraDS.AddNew
            oraDS("PL_member_id").Value = sMemberId
            oraDS("PL_point_type").Value = sPointType
            oraDS("PL_seq_no").Value = lSeqNo
            oraDS("PL_point").Value = lPoint
            oraDS("PL_date").Value = Now
            oraDS("PL_recv_mail").Value = sMailId
            oraDS.Update
            Exit Do
        End If
    Loop

    sSql = "SELECT * FROM T_POINT_TOTAL" & vbCrLf & _
                "WHERE PT_member_id='" & sMemberId & "' AND PT_point_type='" & sPointType & "'"
    Set oraDS = oraDB.CreateDynaset(sSql, ORADYN_DEFAULT)
    If oraDS.EOF Then
        oraDS.AddNew
        oraDS("PT_member_id").Value = sMemberId
        oraDS("PT_point_type").Value = sPointType
        oraDS("PT_total_point").Value = lPoint
    Else
        oraDS.Edit
        oraDS("PT_total_point").Value = CDbl(oraDS("PT_total_point").Value) + lPoint
    End If
    oraDS.Update

    oraDB.CommitTrans
End Function

'=======================================
'�@�����̉��s���P�����ɂ���
'=======================================
Public Function LaxxxrLf(vText As Variant) As String
    LaxxxrLf = NVL(vText, "")
    Do While Right(LaxxxrLf, 2) = vbCrLf
        LaxxxrLf = Left(LaxxxrLf, Len(LaxxxrLf) - 2)
    Loop
    LaxxxrLf = LaxxxrLf & vbCrLf
End Function
