Attribute VB_Name = "modRecvKoukokuTest"
Option Explicit

Private Const BCC_ADDR = "office@melmel.net"

'=======================================
'   �L���m�F�e�X�g���[����M����
'=======================================
Public Function RecvKoukokuTest() As Integer

    Dim sSql As String
    Dim vFiles As Variant
    Dim lMailNum As Long
    Dim vFile As Variant
    Dim vArray As Variant
    Dim vData As Variant
    Dim iCount As Integer

    On Error GoTo ErrHandler

    '���[����M
    vFiles = RecvMail(sPop3Server1, sPop3TestUser, sPop3TestPass, lMailNum)

    '��M�������[���ɂ��f�ڈ˗����[���𑗐M
    If lMailNum > 0 And IsArray(vFiles) Then ' OK ?

        objDB.BeginTrans

        For Each vFile In vFiles
            vArray = objMail.ReadMail(vFile, "subject:nofile:", "")
            If IsArray(vArray) Then   ' OK ?
                Call CheckMail(vArray)
                iCount = iCount + 1
            End If
        Next

        objDB.CommitTrans

        '�e���|�����t�@�C���폜
        DeleteMailTemp vFiles

        Call DeleteMail(sPop3Server1, sPop3TestUser, sPop3TestPass, lMailNum)

    End If

    RecvKoukokuTest = iCount
    Exit Function

ErrHandler:
    objDB.RollbackTrans

    If objDB.Errors.Count <> 0 Then
        Call WriteLog(LOG_ERROR, "SQL Server Error: " & objDB.Errors.Item(0))
    Else
        Call WriteLog(LOG_ERROR, "Program Error: " & Err.Description)
    End If

    '�e���|�����t�@�C���폜
    DeleteMailTemp vFiles

End Function

'=======================================
'   �P���[�����Ƃ̏���
'=======================================
Private Sub CheckMail(vArray As Variant)

    Dim vData As Variant

    For Each vData In vArray
        If LCase(Left(vData, 8)) = "subject:" Then
            Call CheckSubject(vData)
        End If
    Next

End Sub

'=======================================
'   �L���h�c����f�ڈ˗����[�����M
'=======================================
Private Sub CheckSubject(vSubject As Variant)

    Dim nPos As Integer
    Dim sKoukokuId As String

    nPos = InStr(vSubject, "ID=")
    If nPos > 0 Then
        sKoukokuId = Mid(vSubject, nPos + 3, 6)
        Call SendIraiMail(sKoukokuId)
    End If

End Sub

'=======================================
'   �f�ڈ˗����[���̑��M
'=======================================
Private Sub SendIraiMail(sKoukokuId As String)

    Dim objRS As ADODB.Recordset
    Dim sSql As String
    Dim bError As Boolean

    '�L���}�X�^�I�[�v��
    Set objRS = CreateObject("ADODB.RecordSet")
    sSql = "SELECT KS_koukoku_id,KS_mag_id,KS_send_mail," & _
            "  KK_koukoku_line,KK_koukoku_msg,KK_pub_start,KK_pub_end,KK_limit_date,KK_koukoku_type,KK_koukoku_url," & _
            "  MG_m_name,MG_moji_suu,MM_h_name,MM_mail_adr,KE_start_line,KE_end_line" & _
            " FROM ((T_KEISAI INNER JOIN V_KOUKOKU ON KS_koukoku_id=KK_koukoku_id)" & _
            " INNER JOIN V_MELMAG ON KS_mag_id=MG_mag_id) LEFT OUTER JOIN T_KEISEN ON MG_keisen_id=KE_keisen_id" & _
            " WHERE KS_koukoku_id='" & sKoukokuId & "' AND KK_status=2"
    objRS.Open sSql, objDB, adOpenKeyset, adLockReadOnly

    If Not objRS.EOF Then
        bError = False
        Do Until objRS.EOF
            If objRS("KS_send_mail") = False Then
                Select Case objRS("KK_koukoku_type")
                Case "A", "Q"
                    If SendTypeA(objRS) = False Then
                        bError = True
                    End If
                Case "B", "R"
                    If SendTypeB(objRS) = False Then
                        bError = True
                    End If
                End Select
            End If
    
            objRS.MoveNext
        Loop

        '�L���}�X�^�̏�ԃR�[�h���f�ڈ˗����[�����M�ς݂ɐݒ�
        If bError = False Then
            sSql = "UPDATE V_KOUKOKU SET KK_status=3 WHERE KK_koukoku_id='" & sKoukokuId & "'"
            objDB.Execute sSql
        End If
    End If

    objRS.Close
End Sub

'=======================================
'   TYPE-A�f�ڈ˗����[�����M
'=======================================
Private Function SendTypeA(objRS As ADODB.Recordset) As Boolean

    Dim iMojiSuu As Integer
    Dim sSubject As String
    Dim sMailFrom As String
    Dim sBody As String
    Dim sSql As String
    Dim sKoukoku As String
    Dim sMailTo As String

    Call GetSendMailInfo("TYPE" & objRS("KK_koukoku_type") & objRS("KK_koukoku_line"), sSubject, sMailFrom, sBody)

    '�������擾
    iMojiSuu = NVL(objRS("MG_moji_suu"), 0)
    If iMojiSuu < MIN_MOJISUU Then
        iMojiSuu = MIN_MOJISUU
    End If

    '�N���b�N�J�E���g�pURL���ߍ���
    sKoukoku = objRS("KK_koukoku_msg")
    Call SetClickCountURL(sKoukoku, objRS("KS_koukoku_id"), objRS("KS_mag_id"), objRS("KK_koukoku_url"))

    '�L���{���Z���^�����O
    Call Centering(sKoukoku, iMojiSuu)

    '�L���r���t��
    sKoukoku = GetStartKeisen(objRS, iMojiSuu) & vbCrLf & sKoukoku & vbCrLf & GetEndKeisen(objRS, iMojiSuu)

    sBody = Replace(sBody, "%MAGAZINE%", objRS("MG_m_name"))
    sBody = Replace(sBody, "%HAKKOUSHA%", objRS("MM_h_name"))
    sBody = Replace(sBody, "%FROM_DATE%", Format(objRS("KK_pub_start"), "YYYY/MM/DD"))
    sBody = Replace(sBody, "%TO_DATE%", Format(objRS("KK_pub_end"), "YYYY/MM/DD"))
    sBody = Replace(sBody, "%KOUKOKU_MSG%", sKoukoku)
    sBody = Replace(sBody, "%KOUKOKU_ID%", objRS("KS_koukoku_id"))
    sBody = Replace(sBody, "%MELMAG_ID%", objRS("KS_mag_id"))
    sBody = Replace(sBody, "%LIMIT_DATE%", objRS("KK_limit_date"))
    sBody = Replace(sBody, "%HAKKOU_DATE%", Format(objRS("KK_pub_start"), "YYYY/  /  "))

    '���M�惁�[���A�h���X�ioffice@melmel.net��bcc�j
    sMailTo = objRS("MM_mail_adr") & vbTab & "bcc" & vbTab & BCC_ADDR

    If SendMail(sMailTo, sMailFrom, sSubject, sBody) = "" Then
        '�f�ڈ˗����[�����M�ς݃t���O�Z�b�g
        sSql = "UPDATE T_KEISAI SET KS_send_mail=1 WHERE KS_koukoku_id='" & objRS("KS_koukoku_id") & "' AND KS_mag_id='" & objRS("KS_mag_id") & "'"
        objDB.Execute sSql
        SendTypeA = True
    Else
        SendTypeA = False
    End If

End Function

'=======================================
'   TYPE-B�f�ڈ˗����[�����M
'=======================================
Private Function SendTypeB(objRS As ADODB.Recordset) As Boolean

    Dim iMojiSuu As Integer
    Dim sKoukoku As String
    Dim sSubject As String
    Dim sMailFrom As String
    Dim sBody As String
    Dim sSql As String
    Dim dDate As Date
    Dim sDateBusuu As String
    Dim sMailTo As String

    Call GetSendMailInfo("TYPE" & objRS("KK_koukoku_type") & objRS("KK_koukoku_line"), sSubject, sMailFrom, sBody)

    '�������擾
    iMojiSuu = NVL(objRS("MG_moji_suu"), 0)
    If iMojiSuu < MIN_MOJISUU Then
        iMojiSuu = MIN_MOJISUU
    End If

    '�N���b�N�J�E���g�pURL���ߍ���
    sKoukoku = objRS("KK_koukoku_msg")
    Call SetClickCountURL(sKoukoku, objRS("KS_koukoku_id"), objRS("KS_mag_id"), objRS("KK_koukoku_url"))

    '�L���{���Z���^�����O
    Call Centering(sKoukoku, iMojiSuu)

    '�L���r���t��
    sKoukoku = GetStartKeisen(objRS, iMojiSuu) & vbCrLf & sKoukoku & vbCrLf & GetEndKeisen(objRS, iMojiSuu)

    sBody = Replace(sBody, "%MAGAZINE%", objRS("MG_m_name"))
    sBody = Replace(sBody, "%HAKKOUSHA%", objRS("MM_h_name"))
    sBody = Replace(sBody, "%FROM_DATE%", Format(objRS("KK_pub_start"), "YYYY/MM/DD"))
    sBody = Replace(sBody, "%TO_DATE%", Format(objRS("KK_pub_end"), "YYYY/MM/DD"))
    sBody = Replace(sBody, "%KOUKOKU_MSG%", sKoukoku)
    sBody = Replace(sBody, "%KOUKOKU_ID%", objRS("KS_koukoku_id"))
    sBody = Replace(sBody, "%MELMAG_ID%", objRS("KS_mag_id"))
    sBody = Replace(sBody, "%LIMIT_DATE%", objRS("KK_limit_date"))

    sDateBusuu = ""
    dDate = objRS("KK_pub_start")
    Do While dDate <= objRS("KK_pub_end")
        sDateBusuu = sDateBusuu & "���s��[" & Format(dDate, "YYYY/MM/DD") & "]" & vbCrLf
        sDateBusuu = sDateBusuu & "���s����[      ]��" & vbCrLf
        dDate = DateAdd("d", 1, dDate)
    Loop
    If Right(sDateBusuu, 2) = vbCrLf Then
        sDateBusuu = Left(sDateBusuu, Len(sDateBusuu) - 2)
    End If
    sBody = Replace(sBody, "%HAKKOU_DATE_BUSUU%", sDateBusuu)

    '���M�惁�[���A�h���X�ioffice@melmel.net��bcc�j
    sMailTo = objRS("MM_mail_adr") & vbTab & "bcc" & vbTab & BCC_ADDR

    If SendMail(sMailTo, sMailFrom, sSubject, sBody) = "" Then
        '�f�ڈ˗����[�����M�ς݃t���O�Z�b�g
        sSql = "UPDATE T_KEISAI SET KS_send_mail=1 WHERE KS_koukoku_id='" & objRS("KS_koukoku_id") & "' AND KS_mag_id='" & objRS("KS_mag_id") & "'"
        objDB.Execute sSql
        SendTypeB = True
    Else
        SendTypeB = False
    End If

End Function

'=======================================
'   �L���{���Z���^�����O
'=======================================
Private Sub Centering(sKoukoku As String, iMojiSuu As Integer)

    Dim vArray As Variant
    Dim vLine As Variant
    Dim sSpace As String
    Dim i As Integer

    If iMojiSuu > MIN_MOJISUU Then
        sSpace = Space(iMojiSuu - MIN_MOJISUU)
        vArray = Split(sKoukoku, vbCrLf)
        For i = 0 To UBound(vArray)
            vArray(i) = sSpace & vArray(i)
        Next
        sKoukoku = Join(vArray, vbCrLf)
    End If

End Sub


'=======================================
'   �J�n�r���擾
'=======================================
Private Function GetStartKeisen(objRS As ADODB.Recordset, iMojiSuu As Integer) As String

    Dim vLine As Variant
    
    vLine = objRS("KE_start_line")
    If IsNull(vLine) Then
        vLine = KOUKOKU_START_LINE
    End If

    GetStartKeisen = ZenkakuRight(vLine, iMojiSuu)

End Function

'=======================================
'   �I���r���擾
'=======================================
Private Function GetEndKeisen(objRS As ADODB.Recordset, iMojiSuu As Integer) As String

    Dim vLine As Variant

    vLine = objRS("KE_end_line")
    If IsNull(vLine) Then
        vLine = KOUKOKU_END_LINE
    End If

    vLine = Replace(vLine, "KKKKKK", objRS("KS_koukoku_id"))
    vLine = Replace(vLine, "MMMMM", objRS("KS_mag_id"))

    GetEndKeisen = ZenkakuRight(vLine, iMojiSuu)

End Function
