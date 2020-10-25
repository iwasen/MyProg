Attribute VB_Name = "modRecvHonMag"
Option Explicit

Private Type KeisenTbl
    sStartLine As String
    sEndLine As String
    iStartLineLen As Integer
    iEndLineLen As Integer
    iKoukokuIdPos As Integer
    iMagIdPos As Integer
End Type

'=======================================
'   �{�o�^�����}�K��M����
'=======================================
Public Function RecvHonMag() As Integer

    Dim objRS As ADODB.Recordset
    Dim tKeisen() As KeisenTbl
    Dim bKeisen As Boolean
    Dim vFiles As Variant
    Dim lMailNum As Long
    Dim vFile As Variant
    Dim vArray As Variant
    Dim vData As Variant
    Dim iCount As Integer

    On Error GoTo ErrHandler

    Set objRS = CreateObject("ADODB.RecordSet")

    '���[����M
    vFiles = RecvMail(sPop3Server1, sPop3HmagUser1, sPop3HmagPass1, lMailNum)

    '��M�������[����{�o�^�����}�K��M���O�ɕۑ�
    If lMailNum > 0 And IsArray(vFiles) Then   ' OK ?

        objDB.BeginTrans

        '�r���擾
        tKeisen = GetKeisen()
        bKeisen = True

        For Each vFile In vFiles
            vArray = objMail.ReadMail(vFile, "message-id:subject:from:date:X-Mag2Id:nofile:", "")
            If IsArray(vArray) Then   ' OK ?
                If SetMailData(objRS, vArray, tKeisen, False) Then
                    iCount = iCount + 1
                End If
            End If
        Next

        objDB.CommitTrans

        '�e���|�����t�@�C���폜
        DeleteMailTemp vFiles

        Call DeleteMail(sPop3Server1, sPop3HmagUser1, sPop3HmagPass1, lMailNum)
    End If

    '�o�b�N�A�b�v���[���T�[�o����擾
    If sPop3Server2 <> "" Then
        '���[����M
        vFiles = RecvMail(sPop3Server2, sPop3HmagUser2, sPop3HmagPass2, lMailNum)

        If lMailNum > 0 And IsArray(vFiles) Then   ' OK ?

            objDB.BeginTrans

            '�r���擾
            If Not bKeisen Then
                tKeisen = GetKeisen()
            End If

            For Each vFile In vFiles
                vArray = objMail.ReadMail(vFile, "message-id:subject:from:date:X-Mag2Id:nofile:", "")
                If IsArray(vArray) Then   ' OK ?
                    If SetMailData(objRS, vArray, tKeisen, True) Then
                        iCount = iCount + 1
                    End If
                End If
            Next

            objDB.CommitTrans

            '�e���|�����t�@�C���폜
            DeleteMailTemp vFiles

            Call DeleteMail(sPop3Server2, sPop3HmagUser2, sPop3HmagPass2, lMailNum)
        End If
    End If

    RecvHonMag = iCount
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
Private Function SetMailData(objRS As ADODB.Recordset, vArray As Variant, tKeisen() As KeisenTbl, bBackup As Boolean) As Boolean

    Dim vData As Variant
    Dim sDate As String
    Dim sSql As String
    Dim vBody As Variant
    Dim sMessageID As String
    Dim sSubject As String
    Dim sFrom As String
    Dim dDate As Date
    Dim bEOF As Boolean

    'Message-ID�̎擾
    For Each vData In vArray
        If LCase(Left(vData, 11)) = "message-id:" Then
            sMessageID = Trim(Mid(vData, 12))
        ElseIf LCase(Left(vData, 8)) = "subject:" Then
            sSubject = Trim(Mid(vData, 9))
        ElseIf LCase(Left(vData, 5)) = "from:" Then
            sFrom = Trim(Mid(vData, 6))
        ElseIf LCase(Left(vData, 5)) = "date:" Then
            sDate = Trim(Mid(vData, 6))
            If IsDate(sDate) Then
                dDate = CDate(sDate)
            End If
        End If
    Next

    '����̃��[�������邩�`�F�b�N
    If IsEmpty(sMessageID) Then
        sSql = "SELECT * FROM T_HMAILLOG" & _
               " WHERE HM_subject='" & sSubject & "' AND HM_from='" & sFrom & "' AND HM_date='" & dDate & "'"
    Else
        sSql = "SELECT * FROM T_HMAILLOG" & _
               " WHERE HM_message_id='" & sMessageID & "' OR (HM_subject='" & Replace(sSubject, "'", "''") & "' AND HM_from='" & Replace(sFrom, "'", "''") & "' AND HM_date='" & dDate & "')"
    End If
    objRS.Open sSql, objDB, adOpenKeyset, adLockPessimistic

    bEOF = objRS.EOF
    If Not bEOF Then
        objRS.Close
        sSql = "SELECT * FROM T_HMAILLOG2 WHERE 0=1"
        objRS.Open sSql, objDB, adOpenKeyset, adLockPessimistic
    End If

    'T_HMAILDAT�̃V�[�P���X�ԍ����O�̃f�[�^���폜
    sSql = "DELETE T_HMAILDAT WHERE HD_seq_no=0"
    objDB.Execute sSql

    objRS.AddNew

    For Each vData In vArray
        If LCase(Left(vData, 11)) = "message-id:" Then
            objRS("HM_message_id") = Trim(Mid(vData, 12))
        ElseIf LCase(Left(vData, 8)) = "subject:" Then
            objRS("HM_subject") = Trim(Mid(vData, 9))
        ElseIf LCase(Left(vData, 5)) = "from:" Then
            objRS("HM_from") = Trim(Mid(vData, 6))
        ElseIf LCase(Left(vData, 5)) = "date:" Then
            sDate = Trim(Mid(vData, 6))
            If IsDate(sDate) Then
                objRS("HM_date") = CDate(sDate)
            Else
                objRS("HM_date") = Now
            End If
        ElseIf LCase(Left(vData, 9)) = "x-mag2id:" Then
            objRS("HM_mag_id") = CheckMag2ID(Trim(Mid(vData, 10)))
        ElseIf LCase(Left(vData, 5)) = "body:" Then
            vBody = Mid(vData, 7)
            objRS("HM_body") = vBody
            If bEOF Then
                Call CheckKoukoku(objRS, vBody, tKeisen)
            End If
        End If
    Next

    objRS("HM_backup_flag") = bBackup
    objRS("HM_print_flag") = False

    objRS.Update

    'T_HMAILDAT�̃V�[�P���X�ԍ�������������
    sSql = "UPDATE T_HMAILDAT SET HD_seq_no=" & objRS("HM_seq_no") & " WHERE HD_seq_no=0"
    objDB.Execute sSql

    SetMailData = True

    objRS.Close
End Function

'=======================================
'   �L��������`�F�b�N
'=======================================
Private Sub CheckKoukoku(objRS As ADODB.Recordset, vBody As Variant, tKeisen() As KeisenTbl)

    Dim vArray As Variant
    Dim vLine As Variant
    Dim sLine As String
    Dim iKeisen As Integer
    Dim iOffset As Integer
    Dim bInKoukoku As Boolean
    Dim sKoukoku As String
    Dim sKoukokuOrg As String
    Dim iIndex As Integer
    Dim sKoukokuId As String
    Dim sMagId As String
    Dim iResult As Integer
    Dim sSql As String
    Dim objRS2 As ADODB.Recordset

    vArray = Split(vBody, vbCrLf)
    bInKoukoku = False
    iIndex = 1

    For Each vLine In vArray
        sLine = RTrim(vLine)
        If bInKoukoku = False Then
            iKeisen = CheckStartLine(sLine, tKeisen, iOffset)
            If iKeisen <> 0 Then
                sKoukoku = ""
                bInKoukoku = True
            End If
        Else
            '�I���r���`�F�b�N
            If CheckEndLine(sLine, tKeisen, iKeisen, sKoukokuId, sMagId) Then
                '�˗����Ă��邩�`�F�b�N
                sSql = "SELECT *" & _
                       " FROM T_KEISAI" & _
                       " WHERE KS_koukoku_id='" & sKoukokuId & "' AND KS_mag_id='" & sMagId & "'"
                Set objRS2 = objDB.Execute(sSql)
                If Not objRS2.EOF Then
                    objRS2.Close
                    '�L���}�X�^�ǂݏo��
                    sSql = "SELECT KK_koukoku_type,KK_pub_start,KK_pub_end,KK_koukoku_msg,KK_koukoku_url" & _
                           " FROM V_KOUKOKU" & _
                           " WHERE KK_koukoku_id='" & sKoukokuId & "' AND KK_status<>9"
                    Set objRS2 = objDB.Execute(sSql)
                    If Not objRS2.EOF Then
                        If InsertCheck(sKoukokuId, sMagId, objRS2("KK_koukoku_type"), objRS2("KK_pub_start"), objRS2("KK_pub_end"), objRS("HM_date")) Then
                            '������`�F�b�N
                            If iKeisen > 0 Then
                                sKoukokuOrg = objRS2("KK_koukoku_msg")
                                Call SetClickCountURL(sKoukokuOrg, sKoukokuId, sMagId, objRS2("KK_koukoku_url"))
                                iResult = CompareKoukoku(sKoukoku, sKoukokuOrg)
                            Else
                                iResult = 0
                            End If

                            objRS("HM_mag_id") = sMagId
                            sSql = "INSERT INTO T_HMAILDAT VALUES" & _
                                    "(0," & iIndex & ",'" & sMagId & "','" & sKoukokuId & "'," & iResult & ")"
                            objDB.Execute sSql
        
                            '���у|�C���g�̍Čv�Z
                            If iResult = 1 Then
                                Call ResetJissekiPoint(sMagId, sKoukokuId)
                            End If
        
                            '�P�����}�K�͍ő�R�L���܂�
                            iIndex = iIndex + 1
                            If iIndex > 3 Then
                                Exit For
                            End If
                        End If
                    End If
                End If
                objRS2.Close
                bInKoukoku = False
            Else
                sKoukoku = sKoukoku & Mid(sLine, iOffset + 1) & vbCrLf
            End If
        End If
    Next

End Sub

'=======================================
'   �L���J�n�r���`�F�b�N
'=======================================
Private Function CheckStartLine(sLine As String, tKeisen() As KeisenTbl, iOffset As Integer) As Integer

    Dim sTemp As String
    Dim i As Integer

    CheckStartLine = 0

    If InStr(sLine, "PR//") > 0 Then
        sTemp = LTrim(sLine)
        iOffset = Len(sLine) - Len(sTemp)
    
        For i = 1 To UBound(tKeisen)
            If Right(sTemp, tKeisen(i).iStartLineLen) = tKeisen(i).sStartLine Then
                iOffset = iOffset + ZenkakuLen(Left(sTemp, Len(sTemp) - tKeisen(i).iStartLineLen))
                CheckStartLine = i
                Exit For
            End If
        Next

        If CheckStartLine = 0 Then
            CheckStartLine = -1
        End If
    End If

End Function

'=======================================
'   �L���I���r���`�F�b�N
'=======================================
Private Function CheckEndLine(sLine As String, tKeisen() As KeisenTbl, iKeisen As Integer, sKoukokuId As String, sMagId As String) As Boolean

    Dim objRegExp As New RegExp
    Dim i As Integer
    Dim iOffset As Integer
    Dim iPos As Integer
    Dim sEndLine As String

    CheckEndLine = False

    iPos = InStr(sLine, "//")
    If iPos > 0 Then
        sKoukokuId = Mid(sLine, iPos + 2, 6)
        If NumCheck(sKoukokuId) Then
            If Mid(sLine, iPos + 8, 1) = "-" Then
                sMagId = Mid(sLine, iPos + 9, 5)
                If NumCheck(sMagId) Then
                    If Mid(sLine, iPos + 14, 2) = "//" Then
                        CheckEndLine = True
                    End If
                End If
            End If
        End If
    End If

    If CheckEndLine Then
        If iKeisen > 0 Then
            sEndLine = Right(sLine, tKeisen(iKeisen).iEndLineLen)

            objRegExp.Pattern = tKeisen(iKeisen).sEndLine
            objRegExp.Global = False
            objRegExp.IgnoreCase = False
        
            If objRegExp.Test(sLine) = False Then
                iKeisen = -1
            End If
        End If
    End If

End Function

'=======================================
'   �L�����`�F�b�N
'=======================================
Private Function InsertCheck(sKoukokuId As String, sMagId As String, sKoukokuType As String, dtPubStart As Date, dtPubEnd As Date, dtMailDate As Date) As Boolean

    Dim sSql As String
    Dim objRS As ADODB.Recordset
    Dim iCnt As Integer
    Dim iMaxKoukoku As Integer
    Dim dtPubEnd1 As Date

    '�����̌������擾
    sSql = "SELECT COUNT(*) FROM T_HMAILDAT WHERE HD_koukoku_id='" & sKoukokuId & "' AND HD_mag_id='" & sMagId & "'"
    Set objRS = objDB.Execute(sSql)
    iCnt = objRS(0)
    objRS.Close

    dtPubEnd1 = DateAdd("d", 1, dtPubEnd)

    '�ő勖�e�L�����擾
    Select Case sKoukokuType
    Case "A", "Q"
        iMaxKoukoku = 1
    Case "B", "R"
        iMaxKoukoku = DateDiff("d", dtPubStart, dtPubEnd1)
    Case Else
        iMaxKoukoku = 0
    End Select

    '�L�����`�F�b�N
    If iCnt < iMaxKoukoku Then
        '�ő勖�e�L������菭�Ȃ���΂n�j
        InsertCheck = True
    Else
        '���[���̓��t���f�ڊ��ԓ����`�F�b�N
        If dtMailDate >= dtPubStart And dtMailDate <= dtPubEnd1 Then
            '���ԓ�������������̂��̂Ɋ��ԊO�����邩�`�F�b�N
            sSql = "SELECT HD_seq_no,HD_index_no" & _
                   " FROM T_HMAILDAT INNER JOIN T_HMAILLOG ON HD_seq_no=HM_seq_no" & _
                   " WHERE HD_koukoku_id='" & sKoukokuId & "' AND HD_mag_id='" & sMagId & "'" & _
                   " AND (HM_date < '" & dtPubStart & "' OR HM_date > '" & dtPubEnd1 & "')"
            Set objRS = objDB.Execute(sSql)
            If objRS.EOF Then
                '����������m�f
                InsertCheck = False
            Else
                '�������炻����폜���Ăn�j
                sSql = "DELETE T_HMAILDAT WHERE HD_seq_no=" & objRS(0) & " AND HD_index_no=" & objRS(1)
                objDB.Execute sSql
                InsertCheck = True
            End If
            objRS.Close
        Else
            '���ԊO��������m�f
            InsertCheck = False
        End If
    End If

End Function

'=======================================
'   �L�����e��r
'=======================================
Private Function CompareKoukoku(sKoukoku As String, sKoukokuMsg As String) As Integer

    Dim vArray As Variant
    Dim vLine As Variant
    Dim sOriginal As String

    vArray = Split(sKoukokuMsg, vbCrLf)
    For Each vLine In vArray
        sOriginal = sOriginal & RTrim(vLine) & vbCrLf
    Next

    If sKoukoku = sOriginal Then
        CompareKoukoku = 1
    Else
        CompareKoukoku = 0
    End If

End Function

'=======================================
'   �܂��܂��h�c���烁���}�K�h�c�擾
'=======================================
Private Function CheckMag2ID(vData As Variant) As String

    Dim objRS As ADODB.Recordset
    Dim sSql As String

    If vData <> "" Then
        '�����}�K�}�X�^�I�[�v��
        sSql = "SELECT MG_mag_id FROM T_MELMAG WHERE MG_mgmg_id='" & vData & "'"
        Set objRS = objDB.Execute(sSql)
        If Not objRS.EOF Then
            CheckMag2ID = objRS(0)
        End If
        objRS.Close
    End If

End Function

'=======================================
'   �L���r���擾
'=======================================
Private Function GetKeisen() As KeisenTbl()

    Dim tKeisen() As KeisenTbl
    Dim iRec As Integer
    Dim sLine As String

    Dim objRS As ADODB.Recordset
    Dim sSql As String

    sSql = "SELECT KE_start_line,KE_end_line FROM T_KEISEN"
    Set objRS = objDB.Execute(sSql)

    iRec = 0
    Do Until objRS.EOF
        iRec = iRec + 1
        ReDim Preserve tKeisen(1 To iRec)
        
        tKeisen(iRec).sStartLine = ZenkakuRight(objRS("KE_start_line"), MIN_MOJISUU)

        sLine = ZenkakuRight(objRS("KE_end_line"), MIN_MOJISUU)
        tKeisen(iRec).iKoukokuIdPos = InStr(sLine, "KKKKKK")
        tKeisen(iRec).iMagIdPos = InStr(sLine, "MMMMM")
        sLine = Replace(sLine, "KKKKKK", "......")
        sLine = Replace(sLine, "MMMMM", ".....")
        tKeisen(iRec).sEndLine = sLine

        tKeisen(iRec).iStartLineLen = Len(tKeisen(iRec).sStartLine)
        tKeisen(iRec).iEndLineLen = Len(tKeisen(iRec).sEndLine)

        objRS.MoveNext
    Loop

    objRS.Close

    GetKeisen = tKeisen

End Function

'=======================================
'   �����`�F�b�N
'=======================================
Private Function NumCheck(sStr As String) As Boolean
    Dim i As Integer
    Dim s As String

    NumCheck = True

    For i = 1 To Len(sStr)
        s = Mid(sStr, i, 1)
        If s < "0" Or s > "9" Then
            NumCheck = False
            Exit For
        End If
    Next
End Function
