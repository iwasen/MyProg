Attribute VB_Name = "modReceiveMail"
Option Explicit

Const ID_NUMBER = "★12345678/12345678☆"
Const ENQ_START = "（アンケートここから）"
Const ENQ_END = "（アンケートここまで）"
Const IMP_START = "（感想文ここから）"
Const IMP_END = "（感想文ここまで）"
Const ANS_START = "（回答はこの下の行から）"
Const ANS_END = "（回答はこの上の行まで）"
Const QES_NO = "◆Ｑ"
Const ANS_NO = "◇Ａ"
Const ENQ_SEIRI = "□アンケート整理番号"
Const IMP_SEIRI = "□感想文整理番号"

Const ERR_MAIL = "MAILER-DAEMON"

Const ERR_NORMAL = "0"
Const ERR_NOANSWER = "1"
Const ERR_INVALID = "2"
Const ERR_INSUFF = "3"

Private dCurrentDate As Date
Private sMailKind As String
Private bClaimKeyword As Boolean
Private lClaimKeyword As Long
Private sClaimKeyword() As String

'=======================================
'   メール受信処理
'=======================================
Public Function ReceiveMail() As Long
    Dim oraDS As OraDynaset
    Dim sSql As String
    Dim lCount As Long
    Dim sUserID As String
    Dim sPassword As String
    Dim sServer As String

    lCount = 0
    dCurrentDate = Now

    'TEXTメール受信処理
    sSql = "SELECT MA_MAIL_KIND,MA_USER_ID,MA_PASSWORD,MA_POP3_SERVER FROM T_MAIL_TYPE"
    Set oraDS = oraDB.CreateDynaset(sSql, ORADYN_READONLY)
    Do Until oraDS.EOF
        sMailKind = oraDS(0).Value
        sUserID = oraDS(1).Value
        sPassword = oraDS(2).Value
        If Not IsNull(oraDS(3).Value) Then
            sServer = oraDS(3).Value
        Else
            sServer = sCtPop3Server
        End If

        lCount = lCount + ReceiveTextMail(sServer, sUserID, sPassword)

        oraDS.MoveNext
    Loop

    'HTMLメール受信処理
    lCount = lCount + ReceiveHtmlMail()

    ReceiveMail = lCount
End Function

'=======================================
'   メールアカウントごとの受信処理
'=======================================
Private Function ReceiveTextMail(sServer As String, sUser As String, sPassword As String)
    Dim oraDS As OraDynaset
    Dim sSql As String
    Dim vFiles As Variant
    Dim lMailNum As Long
    Dim vFile As Variant
    Dim vArray As Variant
    Dim lCount As Long

    On Error GoTo ErrHandler

    'メール受信
    vFiles = RecvMail(sServer, sUser, sPassword, lMailNum)

    '受信したメールを受信ログに保存
    If lMailNum > 0 And IsArray(vFiles) Then   ' OK ?

        oraDB.BeginTrans

        sSql = "SELECT * FROM T_RECV_MAIL WHERE 0=1"
        Set oraDS = oraDB.CreateDynaset(sSql, ORADYN_DEFAULT)

        For Each vFile In vFiles
            vArray = objMail.ReadMail(vFile, "subject:from:date:received:nofile:", "")
            If IsArray(vArray) Then   ' OK ?
                Call Receive1Mail(oraDS, vArray)
                lCount = lCount + 1
            End If
        Next

        oraDS.Close

        oraDB.CommitTrans

        'テンポラリファイル削除
        DeleteMailTemp vFiles

        Call DeleteMail(sServer, sUser, sPassword, lMailNum)
    End If

    ReceiveTextMail = lCount
    Exit Function

ErrHandler:
    oraDB.Rollback

    'エラーログ書き込み
    Call WriteLog(LOG_ERROR, "ReceiveMail.bas: " & Err.Description)

    'テンポラリファイル削除
    DeleteMailTemp vFiles
End Function

'=======================================
'   HTMLメール受信処理
'=======================================
Private Function ReceiveHtmlMail()
    Dim oraDS As OraDynaset
    Dim oraDS2 As OraDynaset
    Dim oraDS3 As OraDynaset
    Dim sSql As String
    Dim lCount As Long
    Dim vData As Variant
    Dim sDate As String
    Dim sSubject As String
    Dim sFrom As String
    Dim vDate As Variant
    Dim vReceived As Variant
    Dim sBody As String
    Dim sMailId As String
    Dim sMemberId As String

    On Error GoTo ErrHandler

    sSql = "SELECT * FROM T_HTML_ANSWER"
    Set oraDS = oraDB.CreateDynaset(sSql, ORADYN_DEFAULT)
    If Not oraDS.EOF Then
        sSql = "SELECT * FROM T_RECV_MAIL WHERE 0=1"
        Set oraDS2 = oraDB.CreateDynaset(sSql, ORADYN_DEFAULT)
        Do Until oraDS.EOF
            oraDB.BeginTrans

            sMailId = oraDS("HA_mail_id").Value
            sMemberId = oraDS("HA_member_id").Value
            sBody = oraDS("HA_answer").Value
            vDate = oraDS("HA_date").Value
            vReceived = oraDS("HA_date").Value

            sSql = "SELECT SM_subject FROM T_SEND_MAIL WHERE SM_mail_id='" & sMailId & "'"
            Set oraDS3 = oraDB.CreateDynaset(sSql, ORADYN_READONLY)
            If Not oraDS3.EOF Then
                sSubject = "Re: " & oraDS3(0).Value
            Else
                sSubject = ""
            End If

            sSql = "SELECT MM_mail_adr FROM T_MEMBER WHERE MM_member_id='" & sMemberId & "'"
            Set oraDS3 = oraDB.CreateDynaset(sSql, ORADYN_READONLY)
            If Not oraDS3.EOF Then
                sFrom = oraDS3(0).Value
            Else
                sFrom = ""
            End If

            If oraDS("HA_kind").Value = "E" Then
                sBody = "------" & ENQ_START & "------" & vbCrLf & _
                        ENQ_SEIRI & "★" & sMailId & "/" & sMemberId & "☆" & vbCrLf & _
                         sBody & _
                       "------" & ENQ_END & "------"
            Else
                sBody = "------" & IMP_START & "------" & vbCrLf & _
                        IMP_SEIRI & "★" & sMailId & "/" & sMemberId & "☆" & vbCrLf & _
                        sBody & _
                        "------" & IMP_END & "------"
            End If

            AnalyzeMail oraDS2, sMailId, sMemberId, sSubject, sFrom, sBody, vDate, vReceived

            oraDS.Delete

            oraDB.CommitTrans

            lCount = lCount + 1
            oraDS.MoveNext
        Loop
    
        oraDS.Close
    End If

    ReceiveHtmlMail = lCount
    Exit Function

ErrHandler:
    oraDB.Rollback

    'エラーログ書き込み
    Call WriteLog(LOG_ERROR, "Program Error: " & Err.Description)
End Function

'=======================================
'   １メールごとの処理
'=======================================
Private Sub Receive1Mail(oraDS As OraDynaset, vArray As Variant)
    Dim vData As Variant
    Dim sDate As String
    Dim sSubject As String
    Dim sFrom As String
    Dim vDate As Variant
    Dim vReceived As Variant
    Dim sBody As String
    Dim sMailId As String
    Dim sMemberId As String

    sMailId = ""
    sMemberId = ""

    'メール内容分解
    For Each vData In vArray
        If LCase(Left(vData, 8)) = "subject:" Then
            sSubject = Trim(Mid(vData, 9))
        ElseIf LCase(Left(vData, 5)) = "from:" Then
            sFrom = Trim(Mid(vData, 6))
        ElseIf LCase(Left(vData, 5)) = "date:" Then
            sDate = Trim(Mid(vData, 6))
            If IsDate(sDate) Then
                vDate = CDate(sDate)
            End If
        ElseIf LCase(Left(vData, 9)) = "received:" Then
            If sMailKind = "F" Then
                vReceived = Null
            Else
                If IsEmpty(vReceived) Then
                    vReceived = GetReceivedDate(Trim(Mid(vData, 10)))
                End If
            End If
        ElseIf LCase(Left(vData, 5)) = "body:" Then
            sBody = Mid(vData, 6)
            GetIdNumber sBody, sMailId, sMemberId
        End If
    Next

    'エラーメールを無視
    If InStr(sFrom, ERR_MAIL) > 0 Then
        Exit Sub
    End If

    '受信メール解析
    AnalyzeMail oraDS, sMailId, sMemberId, sSubject, sFrom, sBody, vDate, vReceived

End Sub

'=======================================
'   受信メール解析処理
'=======================================
Public Sub AnalyzeMail(oraDS As OraDynaset, sMailId As String, sMemberId As String, sSubject As String, sFrom As String, sBody As String, vDate As Variant, vReceived As Variant)
    Dim sSeqNo As String
    Dim bEnqValid As Boolean
    Dim bImpValid As Boolean
    Dim sMailType As String

    oraDS.AddNew
    sSeqNo = GetNextVal("RM_seq_no")
    oraDS("RM_seq_no").Value = sSeqNo
    oraDS("RM_subject").Value = LeftB(sSubject, 100)
    oraDS("RM_from").Value = LeftB(sFrom, 100)
    oraDS("RM_date").Value = vDate
    oraDS("RM_body").Value = Empty
    oraDS("RM_recv_date").Value = vReceived
    oraDS("RM_mail_id").Value = sMailId
    oraDS("RM_member_id").Value = sMemberId

    If sBody <> "" And sMailId <> "" And sMemberId <> "" Then
        sMailType = Kaiseki(vReceived, sBody, sMailId, sMemberId, sSeqNo, bEnqValid, bImpValid, sFrom)
        If bEnqValid Then
            oraDS("RM_enq_valid").Value = "1"
            oraDB.ExecuteSQL "UPDATE T_SEND_MAIL SET SM_enq_count=NVL(SM_enq_count,0)+1 WHERE SM_mail_id='" & sMailId & "'"
        End If
        If bImpValid Then
            oraDS("RM_imp_valid").Value = "1"
            oraDB.ExecuteSQL "UPDATE T_SEND_MAIL SET SM_imp_count=NVL(SM_imp_count,0)+1 WHERE SM_mail_id='" & sMailId & "'"
        End If
    End If

    oraDS.Update

    oraDS.MoveLast
    oraDS.Edit
    oraDS("RM_body").Value.Write sBody
    oraDS.Update

    'クレームチェック
    ClaimCheck sSeqNo, sBody

    'レスポンスJOB起動
    If bEnqValid And sMailType = "S" Then
        ResponseJob sMailId, sMemberId, sSeqNo
    End If

End Sub

'=======================================
'   受信日付の取得
'=======================================
Private Function GetReceivedDate(sDate As String) As Variant
    Dim lPos1 As Long
    Dim lPos2 As Long
    Dim sTemp As String

    GetReceivedDate = dCurrentDate
    lPos1 = InStrRev(sDate, ";")
    If lPos1 > 0 Then
        lPos2 = InStr(lPos1, sDate, "+")
        If lPos2 = 0 Then
            lPos2 = InStr(lPos1, sDate, "-")
            If lPos2 = 0 Then
                lPos2 = Len(sDate)
            End If
        End If
        sTemp = Mid(sDate, lPos1 + 1, lPos2 - lPos1 - 1)
        If IsDate(sTemp) Then
            GetReceivedDate = CDate(sTemp)
        End If
    End If
End Function

'=======================================
'   メールIDとメンバーIDの取得
'=======================================
Public Sub GetIdNumber(sBody As String, sMailId As String, sMemberId As String)
    Dim lPos1 As Long
    Dim lPos2 As Long
    Dim lMemberId As Long

    lPos1 = 1
    Do While True
        lPos1 = InStr(lPos1, sBody, "★")
        If lPos1 = 0 Then
            Exit Do
        End If

        If NumCheck(Mid(sBody, lPos1 + 1, LEN_MAIL_ID)) Then
            If Mid(sBody, lPos1 + LEN_MAIL_ID + 1, 1) = "/" Then
                lPos2 = InStr(lPos1 + LEN_MAIL_ID + 1, sBody, "☆")
                If lPos2 > 0 Then
                    lMemberId = lPos2 - lPos1 - LEN_MAIL_ID - 2
                    If lMemberId <= LEN_MEMBER_ID Then
                        If NumCheck(Mid(sBody, lPos1 + LEN_MAIL_ID + 2, lMemberId)) Then
                            sMailId = Mid(sBody, lPos1 + 1, LEN_MAIL_ID)
                            sMemberId = Right(String(LEN_MEMBER_ID, "0") & Mid(sBody, lPos1 + LEN_MAIL_ID + 2, lMemberId), LEN_MEMBER_ID)
                            Exit Do
                        End If
                    End If
                End If
            End If
        End If

        lPos1 = lPos1 + 1
    Loop
End Sub

'=======================================
'   受信メール本文解析
'=======================================
Private Function Kaiseki(vDate As Variant, sBody As String, sMailId As String, sMemberId As String, sSeqNo As String, bEnqValid As Boolean, bImpValid As Boolean, sFrom As String) As String
    Dim oraDS As OraDynaset
    Dim oraDS2 As OraDynaset
    Dim sSql As String
    Dim lPoint As Long
    Dim bEnqLimit As Boolean
    Dim bImpLimit As Boolean
    Dim sEnqError As String

    sSql = "SELECT SM_mail_kind,SM_limit_date,SM_imp_limit,SM_point_enquete,SM_point_kansou FROM T_SEND_MAIL WHERE SM_mail_id='" & sMailId & "'"
    Set oraDS = oraDB.CreateDynaset(sSql, ORADYN_READONLY)
    If Not oraDS.EOF Then
        Kaiseki = oraDS("SM_mail_kind").Value

        Select Case oraDS("SM_mail_kind").Value
        Case "Z", "S"
            '締切りチェック
            bEnqLimit = CheckLimit(vDate, oraDS("SM_limit_date").Value)
            bImpLimit = CheckLimit(vDate, oraDS("SM_imp_limit").Value)

            '回答取得
            GetAnswer sBody, sMailId, sSeqNo, bEnqValid, bImpValid, bEnqLimit, bImpLimit, sEnqError

            'アンケートポイント付与
            If bEnqValid Then
                sSql = "SELECT * FROM T_RECV_MAIL WHERE RM_mail_id='" & sMailId & "' AND RM_member_id='" & sMemberId & "' AND RM_enq_valid='1'"
                Set oraDS2 = oraDB.CreateDynaset(sSql, ORADYN_DEFAULT)
                If oraDS2.EOF Then
                    lPoint = NVL(oraDS("SM_point_enquete").Value, 0)
                    If lPoint <> 0 Then
                        AddPoint sMemberId, "11", lPoint, sMailId
                    End If
                Else
                    oraDS2.Edit
                    oraDS2("RM_enq_valid").Value = Null
                    oraDB.ExecuteSQL "UPDATE T_SEND_MAIL SET SM_enq_count=NVL(SM_enq_count,0)-1 WHERE SM_mail_id='" & sMailId & "'"
                    oraDS2.Update
                End If
                Set oraDS2 = Nothing
            End If

            '感想文ポイント付与
            If bImpValid Then
                sSql = "SELECT * FROM T_RECV_MAIL WHERE RM_mail_id='" & sMailId & "' AND RM_member_id='" & sMemberId & "' AND RM_imp_valid='1'"
                Set oraDS2 = oraDB.CreateDynaset(sSql, ORADYN_DEFAULT)
                If oraDS2.EOF Then
                    lPoint = NVL(oraDS("SM_point_kansou").Value, 0)
                    If lPoint <> 0 Then
                        AddPoint sMemberId, "12", lPoint, sMailId
                    End If
                Else
                    oraDS2.Edit
                    oraDS2("RM_imp_valid").Value = Null
                    oraDB.ExecuteSQL "UPDATE T_SEND_MAIL SET SM_imp_count=NVL(SM_imp_count,0)-1 WHERE SM_mail_id='" & sMailId & "'"
                    oraDS2.Update
                End If
                Set oraDS2 = Nothing
            End If

            'エラー回答処理
            If bEnqLimit And sEnqError <> ERR_NORMAL Then
                ErrorEnqueteMail sSeqNo, sMailId, sEnqError, sBody, sMemberId, sFrom
            End If
        End Select
    End If
End Function

'=======================================
'   締切りチェック処理
'=======================================
Private Function CheckLimit(vDate As Variant, vLimit As Variant) As Boolean

    Dim bOK As Boolean

    bOK = False
    If IsNull(vLimit) Then
        bOK = True
    ElseIf IsNull(vDate) Then
        bOK = True
    Else
        If dCurrentDate <= vLimit Then
            bOK = True
        Else
            If IsDate(vDate) Then
                If vDate <= vLimit Then
                    bOK = True
                End If
            End If
        End If
    End If

    CheckLimit = bOK

End Function

'=======================================
'   回答処理
'=======================================
Private Sub GetAnswer(sBody As String, sMailId As String, sSeqNo As String, bEnqValid As Boolean, bImpValid As Boolean, bEnqLimit As Boolean, bImpLimit As Boolean, sEnqError As String)
    Dim oraDSe As OraDynaset
    Dim oraDSi As OraDynaset
    Dim sSql As String
    Dim ary As Variant
    Dim line As Long
    Dim state As String
    Dim state2 As String
    Dim astart As Long
    Dim s As Variant
    Dim no As Long
    Dim lEnqCount As Long
    Dim lImpCount As Long
    Dim bEnqChecked As Boolean
    Dim bImpChecked As Boolean
    Dim bEnqAnswer() As Boolean
    Dim bEnqInvalid As Boolean
    Dim bImpInvalid As Boolean
    Dim i As Integer

    sSql = "SELECT * FROM T_QUESTION WHERE QU_mail_id='" & sMailId & "' AND QU_question_type='E'"
    Set oraDSe = oraDB.CreateDynaset(sSql, ORADYN_READONLY)
    lEnqCount = oraDSe.RecordCount
    ReDim bEnqAnswer(lEnqCount)

    sSql = "SELECT * FROM T_QUESTION WHERE QU_mail_id='" & sMailId & "' AND QU_question_type='I'"
    Set oraDSi = oraDB.CreateDynaset(sSql, ORADYN_READONLY)
    lImpCount = oraDSi.RecordCount

    line = 0
    no = 0
    astart = 0
    state = ""

    ary = Split(sBody, vbCrLf)
    For Each s In ary
        Select Case state
        Case ""
            If SeparatorCheck(s, ENQ_START) Or InStr(s, ENQ_SEIRI) > 0 Then
                If lEnqCount <> 0 And bEnqChecked = False Then
                    state = "E"
                End If
            ElseIf SeparatorCheck(s, IMP_START) Or InStr(s, IMP_SEIRI) > 0 Then
                If lImpCount <> 0 And bImpChecked = False Then
                    state = "I"
                End If
            ElseIf InStr(s, QES_NO) > 0 Then
                If lEnqCount <> 0 And lImpCount = 0 And bEnqChecked = False Then
                    state = "E"
                    no = GetQuestionNo(s)
                ElseIf lImpCount <> 0 And lEnqCount = 0 And bImpChecked = False Then
                    state = "I"
                    no = GetQuestionNo(s)
                End If
            End If
        Case "E"
            bEnqChecked = True
            If SeparatorCheck(s, ENQ_END) Then
                If astart > 0 Then
                    SaveAnswer oraDSe, sSeqNo, ary, astart, line - 1, no, "E", False, bEnqLimit, bEnqValid, bEnqInvalid
                    astart = 0
                End If
                state = ""
            ElseIf SeparatorCheck(s, IMP_START) Or InStr(s, IMP_SEIRI) > 0 Then
                If astart > 0 Then
                    SaveAnswer oraDSe, sSeqNo, ary, astart, line - 1, no, "E", False, bEnqLimit, bEnqValid, bEnqInvalid
                    astart = 0
                End If
                state = "I"
            ElseIf SeparatorCheck(s, IMP_END) Then
                state = ""
            ElseIf SeparatorCheck(s, ANS_START) Then
                If no > 0 And no <= lEnqCount Then
                    bEnqAnswer(no) = True
                End If
                state2 = state
                state = "A"
                astart = line + 1
            ElseIf InStr(s, QES_NO) > 0 Then
                If astart > 0 Then
                    SaveAnswer oraDSe, sSeqNo, ary, astart, line - 1, no, "E", False, bEnqLimit, bEnqValid, bEnqInvalid
                    astart = 0
                End If
                no = GetQuestionNo(s)
            ElseIf InStr(s, ANS_NO) > 0 Then
                If astart > 0 Then
                    SaveAnswer oraDSe, sSeqNo, ary, astart, line - 1, no, "E", False, bEnqLimit, bEnqValid, bEnqInvalid
                    astart = 0
                End If
                no = GetAnswerNo(s)
                If no > 0 And no <= lEnqCount Then
                    astart = line
                    bEnqAnswer(no) = True
                End If
            End If
        Case "I"
            bImpChecked = True
            If SeparatorCheck(s, ENQ_START) Or InStr(s, ENQ_SEIRI) > 0 Then
                If astart > 0 Then
                    SaveAnswer oraDSi, sSeqNo, ary, astart, line - 1, no, "I", False, bImpLimit, bImpValid, bImpInvalid
                    astart = 0
                End If
                state = "E"
            ElseIf SeparatorCheck(s, ENQ_END) Then
                state = ""
            ElseIf SeparatorCheck(s, IMP_END) Then
                If astart > 0 Then
                    SaveAnswer oraDSi, sSeqNo, ary, astart, line - 1, no, "I", False, bImpLimit, bImpValid, bImpInvalid
                    astart = 0
                End If
                state = ""
            ElseIf SeparatorCheck(s, ANS_START) Then
                state2 = state
                state = "A"
                astart = line + 1
            ElseIf InStr(s, QES_NO) > 0 Then
                If astart > 0 Then
                    SaveAnswer oraDSi, sSeqNo, ary, astart, line - 1, no, "I", False, bImpLimit, bImpValid, bImpInvalid
                    astart = 0
                End If
                no = GetQuestionNo(s)
            ElseIf InStr(s, ANS_NO) > 0 Then
                If astart > 0 Then
                    SaveAnswer oraDSi, sSeqNo, ary, astart, line - 1, no, "I", False, bImpLimit, bImpValid, bImpInvalid
                    astart = 0
                End If
                no = GetAnswerNo(s)
                If no > 0 And no <= lImpCount Then
                    astart = line
                End If
            End If
        Case "A"
            If SeparatorCheck(s, ENQ_START) Then
                state = "E"
            ElseIf SeparatorCheck(s, ENQ_END) Then
                state = ""
            ElseIf SeparatorCheck(s, IMP_START) Then
                state = "I"
            ElseIf SeparatorCheck(s, IMP_END) Then
                state = ""
            ElseIf SeparatorCheck(s, ANS_START) Then
                astart = line + 1
            ElseIf SeparatorCheck(s, ANS_END) Then
                If astart > 0 Then
                    If state2 = "E" Then
                        SaveAnswer oraDSe, sSeqNo, ary, astart, line - 1, no, "E", True, bEnqLimit, bEnqValid, bEnqInvalid
                    Else
                        SaveAnswer oraDSi, sSeqNo, ary, astart, line - 1, no, "I", True, bImpLimit, bImpValid, bImpInvalid
                    End If
                    astart = 0
                End If
                state = state2
            End If
        End Select

        line = line + 1
    Next

    If astart > 0 Then
        If state = "A" Then
            Select Case state2
            Case "E"
                SaveAnswer oraDSe, sSeqNo, ary, astart, line - 1, no, "E", True, bEnqLimit, bEnqValid, bEnqInvalid
            Case "I"
                SaveAnswer oraDSi, sSeqNo, ary, astart, line - 1, no, "I", True, bImpLimit, bImpValid, bImpInvalid
            End Select
        Else
            Select Case state
            Case "E"
                SaveAnswer oraDSe, sSeqNo, ary, astart, line - 1, no, "E", False, bEnqLimit, bEnqValid, bEnqInvalid
            Case "I"
                SaveAnswer oraDSi, sSeqNo, ary, astart, line - 1, no, "I", False, bImpLimit, bImpValid, bImpInvalid
            End Select
        End If
    End If

    sEnqError = ERR_NORMAL
    If lEnqCount <> 0 And bEnqChecked Then
        If bEnqInvalid Then
            sEnqError = ERR_INVALID
        Else
            For i = 1 To lEnqCount
                If bEnqAnswer(i) = False Then
                    sEnqError = ERR_INSUFF
                    Exit For
                End If
            Next
            If sEnqError = ERR_NORMAL And bEnqValid = False Then
                sEnqError = ERR_NOANSWER
            End If
        End If
    End If
End Sub

'=======================================
'   セパレータチェック
'=======================================
Private Function SeparatorCheck(s As Variant, spr As String)
    Dim p As Long
    Dim t As String

    SeparatorCheck = False

    p = InStr(s, spr)
    If p > 1 Then
        t = Mid(s, p - 1, 1)
        If t = "-" Or t = "－" Then
            t = Mid(s, p + Len(spr), 1)
            If t = "-" Or t = "－" Then
                SeparatorCheck = True
            End If
        End If
    End If
End Function

'=======================================
'   質問番号取得処理
'=======================================
Private Function GetQuestionNo(s As Variant) As Long
    Dim p As Long

    p = InStr(s, QES_NO)
    If p > 0 Then
        GetQuestionNo = GetNumber(Mid(s, p + Len(QES_NO)))
    Else
        GetQuestionNo = 0
    End If
End Function

'=======================================
'   回答番号取得処理
'=======================================
Private Function GetAnswerNo(s As Variant) As Long
    Dim p As Long

    p = InStr(s, ANS_NO)
    If p > 0 Then
        GetAnswerNo = GetNumber(Mid(s, p + Len(ANS_NO)))
    Else
        GetAnswerNo = 0
    End If
End Function

'=======================================
'   番号取得処理
'=======================================
Private Function GetNumber(s As Variant) As Long
    Dim p As Long
    Dim num As String

    p = InStr(s, "．")
    If p = 0 Then
        p = InStr(s, ".")
        If p = 0 Then
            GetNumber = 0
            Exit Function
        End If
    End If

    num = ToHankaku(Left(s, p - 1))
    If NumCheck(num) Then
        GetNumber = Val(num)
    Else
        GetNumber = 0
    End If
End Function

'=======================================
'   回答保存処理
'=======================================
Private Sub SaveAnswer(oraDS As OraDynaset, sSeqNo As String, ary As Variant, astart As Long, qend As Long, qno As Long, qtype As String, bFree As Boolean, bLimit As Boolean, bValid As Boolean, bInvalid As Boolean)
    Dim sSql As String
    Dim oraDS2 As OraDynaset
    Dim i As Long
    Dim ary2 As Variant
    Dim sValid As String
    Dim sInvalid As String

    If Not bLimit Then
        Exit Sub
    End If

    If qend < astart Then
        Exit Sub
    End If

    oraDS.FindFirst "QU_question_no=" & qno
    If oraDS.NoMatch Then
        Exit Sub
    End If

    Do While ary(qend) = ""
        qend = qend - 1
    Loop

    If qend < astart Then
        Exit Sub
    End If

    ReDim ary2(qend - astart)
    For i = 0 To qend - astart
        ary2(i) = ary(astart + i)
    Next

    SaveAnswerNo sSeqNo, ary2, qtype, qno, oraDS("QU_question_sel").Value, sValid, sInvalid, bFree

    sSql = "SELECT * FROM T_ANSWER WHERE AN_seq_no=" & sSeqNo & " AND AN_question_type='" & qtype & "' AND AN_question_no=" & qno
    Set oraDS2 = oraDB.CreateDynaset(sSql, ORADYN_DEFAULT)
    If oraDS2.EOF Then
        oraDS2.AddNew
    Else
        oraDS2.Edit
    End If
    oraDS2("AN_seq_no").Value = sSeqNo
    oraDS2("AN_question_type").Value = qtype
    oraDS2("AN_question_no").Value = qno
    oraDS2("AN_answer_text").Value = Left(Join(ary2, vbCrLf), 1000)
    oraDS2("AN_answer_valid").Value = sValid
    oraDS2("AN_answer_invalid").Value = sInvalid
    oraDS2.Update

    If sValid = "1" Then
        bValid = True
    End If

    If sInvalid = "1" Then
        bInvalid = True
    End If
End Sub

'=======================================
'   回答番号保存処理
'=======================================
Private Sub SaveAnswerNo(sSeqNo As String, ary As Variant, qtype As String, qno As Long, iSel As Integer, sValid As String, sInvalid As String, bFree As Boolean)
    Dim s As Variant
    Dim lPos1 As Long
    Dim lPos2 As Long
    Dim sNum As String
    Dim lNum As Long
    Dim sSql As String
    Dim sTemp As String
    Dim i As Long

    On Error Resume Next

    sValid = "0"
    sInvalid = "0"

    If iSel <> 0 Then
        For Each s In ary
            lPos1 = 1
            Do While True
                lPos1 = InStr(lPos1, s, "＜")
                If lPos1 = 0 Then
                    Exit Do
                End If
                lPos2 = InStr(lPos1, s, "＞")
                If lPos2 = 0 Then
                    Exit Do
                End If

                sNum = ToHankaku(Trim(Mid(s, lPos1 + 1, lPos2 - lPos1 - 1)))
                If sNum = "" Then
                ElseIf NumCheck(sNum) Then
                    lNum = CLng(sNum)
                    If lNum >= 1 And lNum <= iSel Then
                        sSql = "INSERT INTO T_ANSWER_NO VALUES(" & sSeqNo & ",'" & qtype & "'," & qno & "," & sNum & ")"
                        oraDB.ExecuteSQL sSql
                        sValid = "1"
                    Else
                        sInvalid = "1"
                    End If
                Else
                    sInvalid = "1"
                End If

                lPos1 = lPos2
            Loop
        Next
    Else
        If bFree Then
            If UBound(ary) >= 1 Then
                sTemp = ary(0)
                For i = 1 To UBound(ary)
                    If ary(i) <> sTemp Then
                        sValid = "1"
                        Exit For
                    End If
                Next
            ElseIf UBound(ary) = 0 Then
                If Len(ary(0)) >= 3 Then
                    sValid = "1"
                End If
            End If
        Else
            For Each s In ary
                lPos1 = InStr(s, "｛")
                If lPos1 > 0 Then
                    lPos2 = InStr(lPos1, s, "｝")
                    If lPos2 > 0 Then
                        If Len(Trim(Mid(s, lPos1 + 1, lPos2 - lPos1 - 1))) > 0 Then
                            sValid = "1"
                            Exit For
                        End If
                    End If
                End If
            Next
        End If
    End If
End Sub

'=======================================
'　レスポンスJOB起動処理
'=======================================
Private Sub ResponseJob(sMailId As String, sMemberId As String, sSeqNo As String)
    Dim sSql As String
    Dim oraDS As OraDynaset
    Dim oraDS2 As OraDynaset
    Dim vWhere As Variant

    sSql = "SELECT JB_send_timing,JB_send_timing_h,JB_send_timing_d,SM_mail_id,SM_start_date,SM_sql" & vbCrLf & _
           "FROM T_JOB,T_SEND_MAIL WHERE JB_job_type='1' AND JB_parent_job_id IN" & vbCrLf & _
           "(SELECT JB_job_id FROM T_JOB WHERE JB_mail_id='" & sMailId & "')" & vbCrLf & _
           "AND SM_permit='1' AND SM_status<>'9' AND JB_mail_id=SM_mail_id"
    Set oraDS = oraDB.CreateDynaset(sSql, ORADYN_READONLY)
    Do Until oraDS.EOF
        vWhere = ReadCLOB(oraDS("SM_sql").Value)
        If Not IsNull(vWhere) Then
            sSql = "SELECT MM_member_id FROM T_MEMBER WHERE MM_member_id='" & sMemberId & "' AND " & vWhere
            Set oraDS2 = oraDB.CreateDynaset(sSql, ORADYN_READONLY)
            If Not oraDS2.EOF Then
                sSql = "SELECT * FROM T_SENDMAIL_RR WHERE SR_mail_id='" & oraDS("SM_mail_id").Value & "' AND SR_member_id='" & sMemberId & "'"
                Set oraDS2 = oraDB.CreateDynaset(sSql, ORADYN_DEFAULT)
                If oraDS2.EOF Then
                    oraDS2.AddNew
                    oraDS2("SR_mail_id").Value = oraDS("SM_mail_id").Value
                    oraDS2("SR_member_id").Value = sMemberId
                    oraDS2("SR_status").Value = "0"
                    Select Case oraDS("JB_send_timing").Value
                    Case "1"
                        oraDS2("SR_start_date").Value = oraDS("SM_start_date").Value
                    Case "2"
                        oraDS2("SR_start_date").Value = DateAdd("h", NVL(oraDS("JB_send_timing_h").Value, 0), dCurrentDate)
                    Case "3"
                        oraDS2("SR_start_date").Value = DateAdd("d", NVL(oraDS("JB_send_timing_d").Value, 0), dCurrentDate)
                    End Select
                    oraDS2.Update
                End If
            End If
        End If
        oraDS.MoveNext
    Loop
End Sub

'=======================================
'   クレームチェック処理
'=======================================
Private Sub ClaimCheck(sSeqNo As String, sBody As String)
    Dim sSql As String
    Dim oraDS As OraDynaset
    Dim i As Long

    'キーワード取得
    If bClaimKeyword = False Then
        sSql = "SELECT CK_keyword FROM T_CLAIM_KEYWORD"
        Set oraDS = oraDB.CreateDynaset(sSql, ORADYN_READONLY)
        Do Until oraDS.EOF
            ReDim Preserve sClaimKeyword(lClaimKeyword)
            sClaimKeyword(lClaimKeyword) = oraDS(0).Value
            lClaimKeyword = lClaimKeyword + 1
            oraDS.MoveNext
        Loop
        bClaimKeyword = True
    End If

    If lClaimKeyword > 0 Then
        For i = 0 To lClaimKeyword - 1
            If InStr(sBody, sClaimKeyword(i)) > 0 Then
                sSql = "INSERT INTO T_CLAIM_MAIL VALUES (" & sSeqNo & ",'0')"
                oraDB.ExecuteSQL sSql
                Exit For
            End If
        Next
    End If
End Sub

'=======================================
'   アンケート回答エラー処理
'=======================================
Private Sub ErrorEnqueteMail(sSeqNo As String, sMailId As String, sEnqError As String, sBody As String, sMemberId As String, sFrom As String)
    Dim sSql As String
    Dim oraDS As OraDynaset
    Dim sStatus As String
    Dim sTemplateId As String
    Dim sSubject2 As String
    Dim sFrom2 As String
    Dim sBody2 As String
    Dim sMailAddr As String
    Dim sSubject As String

    sSql = "SELECT MM_mail_adr FROM T_MEMBER WHERE MM_member_id='" & sMemberId & "' AND MM_status='0'"
    Set oraDS = oraDB.CreateDynaset(sSql, ORADYN_READONLY)
    If oraDS.EOF Then
        Exit Sub
    End If
    sMailAddr = oraDS("MM_mail_adr").Value

    sSql = "SELECT SM_subject FROM T_SEND_MAIL WHERE SM_mail_id='" & sMailId & "'"
    Set oraDS = oraDB.CreateDynaset(sSql, ORADYN_READONLY)
    If oraDS.EOF Then
        Exit Sub
    End If
    sSubject = oraDS("SM_subject").Value

    If sEnqError = ERR_NOANSWER Then
        If sCtEnqError <> "1" Or InStr(sFrom, sMailAddr) = 0 Then
            Exit Sub
        End If
        sStatus = "2"
        sTemplateId = "ENQNOANS"
    Else
        sStatus = "0"
        sTemplateId = "ENQERR"
    End If

    sSql = "SELECT * FROM T_ENQUETE_ERR WHERE EE_seq_no=" & sSeqNo
    Set oraDS = oraDB.CreateDynaset(sSql, ORADYN_DEFAULT)
    If oraDS.EOF Then
        oraDS.AddNew
        oraDS("EE_seq_no").Value = sSeqNo
        oraDS("EE_error_code").Value = sEnqError
        oraDS("EE_body").Value = Empty
        oraDS("EE_status").Value = sStatus
        oraDS.Update

        GetSendMailInfo sTemplateId, sSubject2, sFrom2, sBody2
        sBody2 = Replace(sBody2, "%SUBJECT%", sSubject)
        sBody2 = Replace(sBody2, "%REPLAY_MESSAGE%", sBody)
        oraDS.Edit
        oraDS("EE_body").Value.Write sBody2
        oraDS.Update
    End If

End Sub
