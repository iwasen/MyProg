Attribute VB_Name = "modRegMail"
Option Explicit

Private iMaxQuestion As Integer  '質問項目数

'=======================================
'   登録メール受信処理
'=======================================
Public Function RecvRegMail() As Integer

    Dim oraDS As OraDynaset
    Dim sSql As String
    Dim vFiles As Variant
    Dim lMailNum As Long
    Dim vFile As Variant
    Dim vArray As Variant
    Dim vData As Variant
    Dim iCount As Integer

    On Error GoTo ErrHandler

    'ゆっくり登録メール受信
    vFiles = RecvMail(sCtPop3Server, sCtPop3RegUser, sCtPop3RegPass, lMailNum)

    '受信したメールをチェック
    If lMailNum > 0 And IsArray(vFiles) Then   ' OK ?

        iMaxQuestion = GetItemNum()

        oraDB.BeginTrans

        'ゆっくり登録メール受信ログオープン
        sSql = "SELECT * FROM T_TOUROKU_MAIL WHERE 0=1"
        Set oraDS = oraDB.CreateDynaset(sSql, ORADYN_DEFAULT)

        For Each vFile In vFiles
            vArray = objMail.ReadMail(vFile, "subject:from:date:nofile:", "")
            If IsArray(vArray) Then   ' OK ?
                Call Receive1Mail(oraDS, vArray)
                iCount = iCount + 1
            End If
        Next

        oraDS.Close

        oraDB.CommitTrans

        'テンポラリファイル削除
        DeleteMailTemp vFiles

        Call DeleteMail(sCtPop3Server, sCtPop3RegUser, sCtPop3RegPass, lMailNum)
    End If

    RecvRegMail = iCount
    Exit Function

ErrHandler:
    oraDB.Rollback

    'エラーログ書き込み
    Call WriteLog(LOG_ERROR, "RegMail.bas: " & Err.Description)

    'テンポラリファイル削除
    DeleteMailTemp vFiles

End Function

'=======================================
'   １メールごとの処理
'=======================================
Private Sub Receive1Mail(oraDS As OraDynaset, vArray As Variant)

    Dim vData As Variant
    Dim sDate As String
    Dim sFrom As String
    Dim sBody As String
    Dim sBody2 As String
    Dim sSeqNo As String
    ReDim bCheck(iMaxQuestion) As Boolean
    Dim cRegInfo As New clsRegInfo
    Dim i As Integer
    Dim bError As Boolean
    Dim bKyoumiGenreErr As Boolean

'ミレニアムキャンペーン用特別処理===
    If iMaxQuestion >= 83 Then
        bCheck(83) = True
    End If
'=================================

    oraDS.AddNew

    sSeqNo = GetNextVal("TM_seq_no")
    oraDS("TM_seq_no").Value = sSeqNo
    oraDS("TM_recv_date").Value = Now
    For Each vData In vArray
        If LCase(Left(vData, 8)) = "subject:" Then
            oraDS("TM_subject").Value = Trim(Mid(vData, 9))
        ElseIf LCase(Left(vData, 5)) = "from:" Then
            sFrom = LeftB(Trim(Mid(vData, 6)), 100)
            oraDS("TM_from").Value = sFrom
        ElseIf LCase(Left(vData, 5)) = "date:" Then
            sDate = Trim(Mid(vData, 6))
            If IsDate(sDate) Then
                oraDS("TM_date").Value = CDate(sDate)
            End If
        ElseIf LCase(Left(vData, 5)) = "body:" Then
            sBody = Trim(Mid(vData, 6))
            sBody2 = FormatBody(sBody)
            oraDS("TM_body").Value = Empty
            Call CheckMail(sBody2, cRegInfo, bCheck)
        End If
    Next

    oraDS.Update

    If sBody <> "" Then
        oraDS.MoveLast
        oraDS.Edit
        oraDS("TM_body").Value.Write sBody
        oraDS.Update
    End If

    bError = False
    For i = 1 To iMaxQuestion
        If bCheck(i) = False Then
            bError = True
            Exit For
        End If
    Next

    If InStr(cRegInfo.kyoumi_genre, "1") = 0 And InStr(cRegInfo.kyoumi_genre, "2") = 0 Then
        bError = True
        bKyoumiGenreErr = True
    End If

    If bError Then
        Call ErrorReplay(sFrom, sBody2, bCheck, sSeqNo, bKyoumiGenreErr)
    Else
        Call KariTouroku(cRegInfo, sFrom)
    End If
End Sub

'=======================================
'   メール本文のフォーマット
'=======================================
Private Function FormatBody(sBody As String) As String

    Dim arry As Variant
    Dim lLine As Long
    Dim i As Long
    Dim lPos1 As Long
    Dim lPos2 As Long
    Dim lOffset As Long
    Dim lIndex As Long
    Dim arry2() As String

    If sBody = "" Then
        Exit Function
    End If

    arry = Split(sBody, vbCrLf)
    If IsArray(arry) Then
        lLine = UBound(arry)
        lPos1 = 0
        For i = 0 To lLine
            If lPos1 = 0 Then
                If InStr(arry(i), "（返信はここから）-") > 0 Then
                    lPos1 = i + 1
                End If
            End If
            lOffset = InStr(arry(i), "◇Ａ")
            If lOffset > 0 Then
                Exit For
            End If
        Next

        lPos2 = lLine
        For i = lLine To 0 Step -1
            If InStr(arry(i), "（返信はここまで）-") > 0 Then
                lPos2 = i - 1
                Exit For
            End If
        Next

        If lPos1 < lPos2 Then
            ReDim arry2(lPos2 - lPos1)
            lIndex = 0
            For i = lPos1 To lPos2
                If lOffset <= 1 Then
                    arry2(lIndex) = arry(i)
                Else
                    arry2(lIndex) = Mid(arry(i), lOffset)
                End If
                lIndex = lIndex + 1
            Next
    
            FormatBody = Join(arry2, vbCrLf)
        Else
            FormatBody = sBody
        End If
    End If
End Function

'=======================================
'   メール内容のチェック
'=======================================
Private Sub CheckMail(sBody As String, cRegInfo As clsRegInfo, bCheck As Variant)
    Dim sSql As String
    Dim oraDS As OraDynaset
    Dim sText As String
    Dim lPosA As Long
    Dim lPosQ As Long
    Dim lQANo As Long
    Dim sAnswer As String

    sText = Replace(sBody, vbCrLf, "")
    lPosQ = 1

    Do While lPosQ <> 0
        lPosA = InStr(lPosQ, sText, "◇Ａ")
        If lPosA = 0 Then
            Exit Do
        End If

        lQANo = GetNumber(Mid(sText, lPosA + 2, 3), 0)

        lPosQ = InStr(lPosA, sText, "◆Ｑ")
        If lPosQ = 0 Then
            sAnswer = Mid(sText, lPosA)
        Else
            sAnswer = Mid(sText, lPosA, lPosQ - lPosA)
        End If

        If lQANo >= 1 And lQANo <= iMaxQuestion Then
            sSql = "SELECT * FROM T_TOUROKU_FORM WHERE TF_version_no=" & VER_REGIST_FORM & " AND TF_qa_no=" & lQANo
            Set oraDS = oraDB.CreateDynaset(sSql, ORADYN_READONLY)
            If Not oraDS.EOF Then
                Select Case oraDS("TF_bunrui_code").Value
                Case "01"
                    bCheck(lQANo) = KyoumiGenre(sAnswer, oraDS, cRegInfo)
                Case "02"
                    bCheck(lQANo) = Profile(sAnswer, oraDS, cRegInfo)
                Case "03"
                    bCheck(lQANo) = YokuikuMise(sAnswer, oraDS, cRegInfo)
                Case "04"
                    bCheck(lQANo) = DocchiHa(sAnswer, oraDS, cRegInfo)
                Case "05"
                    bCheck(lQANo) = OshiraseMail(sAnswer, oraDS, cRegInfo)
                Case "06"
                    bCheck(lQANo) = present(sAnswer, oraDS, cRegInfo)
                End Select
            End If
        End If
    Loop

End Sub

'=======================================
'   数値取得
'=======================================
Private Function GetNumber(sText As String, lDefault As Long) As Long
    Const zenkaku = "０１２３４５６７８９"
    Const hankaku = "0123456789"
    Dim sNum As String
    Dim s As String
    Dim i As Integer
    Dim n As Integer

    sText = Trim(sText)
    For i = 1 To Len(sText)
        s = Mid(sText, i, 1)
        n = InStr(zenkaku, s)
        If n > 0 Then
            s = Mid(hankaku, n, 1)
        ElseIf InStr(hankaku, s) = 0 Then
            If s <> "." And s <> "．" Then
                sNum = "-1"
            End If
            Exit For
        End If

        sNum = sNum & s
    Next

    If sNum = "" Then
        GetNumber = lDefault
    Else
        On Error Resume Next
        GetNumber = Val(sNum)
    End If
End Function

'=======================================
'   回答番号取得
'=======================================
Private Function GetAnswerNumber(sAnswer As String, lDefault As Long) As Collection
    Dim lPos1 As Long
    Dim lPos2 As Long

    Set GetAnswerNumber = New Collection
    lPos2 = 1

    Do While True
        lPos1 = InStr(lPos2, sAnswer, "＜")
        If lPos1 = 0 Then
            Exit Do
        End If

        lPos2 = InStr(lPos1, sAnswer, "＞")
        If lPos2 = 0 Then
            Exit Do
        End If

        GetAnswerNumber.Add GetNumber(Mid(sAnswer, lPos1 + 1, lPos2 - lPos1 - 1), lDefault)
    Loop
End Function

'=======================================
'   興味ジャンル回答処理
'=======================================
Private Function KyoumiGenre(sAnswer As String, oraDS As OraDynaset, cRegInfo As clsRegInfo) As Boolean
    Dim cAnswer As Collection
    Dim sFlag As String
    Dim sKyoumiGenre As String

    Set cAnswer = GetAnswerNumber(sAnswer, 1)
    If cAnswer.Count > 0 Then
        Select Case cAnswer(1)
        Case 1
            sFlag = "0"
        Case 2
            sFlag = "1"
        Case 3
            sFlag = "2"
        Case Else
            Exit Function
        End Select

        sKyoumiGenre = cRegInfo.kyoumi_genre
        Mid(sKyoumiGenre, CInt(oraDS("TF_index").Value), 1) = sFlag
        cRegInfo.kyoumi_genre = sKyoumiGenre
    End If

    KyoumiGenre = True
End Function

'=======================================
'   個人情報回答処理
'=======================================
Private Function Profile(sAnswer As String, oraDS As OraDynaset, cRegInfo As clsRegInfo) As Boolean
    Select Case oraDS("TF_index")
    Case 1  'あなたご自身のこと
        Profile = MailAdr(sAnswer, cRegInfo)
    Case 2  '性別
        Profile = Seibetsu(sAnswer, cRegInfo)
    Case 3  '未既婚
        Profile = Mikikon(sAnswer, cRegInfo)
    Case 4  'ＨＴＭＬメール受信設定
        Profile = HtmlMail(sAnswer, cRegInfo)
    Case 5  '職業
        Profile = Shokugyou(sAnswer, cRegInfo)
    End Select
End Function

'=======================================
'   メールアドレス、郵便番号等回答処理
'=======================================
Private Function MailAdr(sAnswer As String, cRegInfo As clsRegInfo) As Boolean
    Dim lPos As Long
    Dim sSeinengappi As String

    lPos = InStr(sAnswer, "メールアドレス")
    If lPos = 0 Then
        Exit Function
    End If
    cRegInfo.mail_adr = GetAnswerStr(sAnswer, lPos)
    If MailAdrCheck(cRegInfo.mail_adr) = False Then
        Exit Function
    End If

    lPos = InStr(lPos, sAnswer, "西暦")
    If lPos = 0 Then
        Exit Function
    End If
    sSeinengappi = GetAnswerStr(sAnswer, lPos)

    lPos = InStr(lPos, sAnswer, "年")
    If lPos = 0 Then
        Exit Function
    End If
    sSeinengappi = sSeinengappi & "/" & GetAnswerStr(sAnswer, lPos)

    lPos = InStr(lPos, sAnswer, "月")
    If lPos = 0 Then
        Exit Function
    End If
    sSeinengappi = sSeinengappi & "/" & GetAnswerStr(sAnswer, lPos)

    If Not IsDate(sSeinengappi) Then
        Exit Function
    End If
    cRegInfo.seinengappi = CDate(sSeinengappi)

    lPos = InStr(sAnswer, "居住地郵便番号")
    If lPos = 0 Then
        Exit Function
    End If
    cRegInfo.zip_kyojuu = CheckZipCode(ToHankaku(GetAnswerStr(sAnswer, lPos)))
    If Len(cRegInfo.zip_kyojuu) <> 7 Then
        Exit Function
    End If

    lPos = InStr(sAnswer, "勤務地郵便番号")
    If lPos = 0 Then
        Exit Function
    End If
    cRegInfo.zip_kinmu = CheckZipCode(ToHankaku(GetAnswerStr(sAnswer, lPos)))
    If Len(cRegInfo.zip_kinmu) <> 7 And Len(cRegInfo.zip_kinmu) <> 0 Then
        Exit Function
    End If

    MailAdr = True
End Function

'=======================================
'   メールアドレス、郵便番号等回答処理
'=======================================
Private Function GetAnswerStr(sAnswer As String, lStartPos As Long) As String
    Dim lPos1 As Long
    Dim lPos2 As Long

    lPos1 = InStr(lStartPos, sAnswer, "｛")
    If lPos1 = 0 Then
        lPos1 = InStr(lStartPos, sAnswer, "{")
        If lPos1 = 0 Then
            Exit Function
        End If
    End If

    lPos2 = InStr(lPos1, sAnswer, "｝")
    If lPos2 = 0 Then
        lPos2 = InStr(lPos1, sAnswer, "}")
        If lPos2 = 0 Then
            Exit Function
        End If
    End If

    GetAnswerStr = Trim(Mid(sAnswer, lPos1 + 1, lPos2 - lPos1 - 1))
End Function

'=======================================
'   性別回答処理
'=======================================
Private Function Seibetsu(sAnswer As String, cRegInfo As clsRegInfo) As Boolean
    Dim cAnswer As Collection

    Set cAnswer = GetAnswerNumber(sAnswer, 0)
    If cAnswer.Count > 0 Then
        Select Case cAnswer(1)
        Case 1
            cRegInfo.Seibetsu = "0"
        Case 2
            cRegInfo.Seibetsu = "1"
        Case Else
            Exit Function
        End Select
    Else
        Exit Function
    End If

    Seibetsu = True
End Function

'=======================================
'   未既婚回答処理
'=======================================
Private Function Mikikon(sAnswer As String, cRegInfo As clsRegInfo) As Boolean
    Dim cAnswer As Collection

    Set cAnswer = GetAnswerNumber(sAnswer, 0)
    If cAnswer.Count > 0 Then
        Select Case cAnswer(1)
        Case 1
            cRegInfo.Mikikon = "0"
        Case 2
            cRegInfo.Mikikon = "1"
        Case Else
            Exit Function
        End Select
    Else
        Exit Function
    End If

    Mikikon = True
End Function

'=======================================
'   ＨＴＭＬメール受信設定回答処理
'=======================================
Private Function HtmlMail(sAnswer As String, cRegInfo As clsRegInfo) As Boolean
    Dim cAnswer As Collection

    Set cAnswer = GetAnswerNumber(sAnswer, 0)
    If cAnswer.Count > 0 Then
        Select Case cAnswer(1)
        Case 1
            cRegInfo.html_mail = "1"
        Case 2
            cRegInfo.html_mail = "0"
        Case Else
            Exit Function
        End Select
    Else
        Exit Function
    End If

    HtmlMail = True
End Function

'=======================================
'   職業回答処理
'=======================================
Private Function Shokugyou(sAnswer As String, cRegInfo As clsRegInfo) As Boolean
    Dim cAnswer As Collection
    Dim lAnswer As Long

    Set cAnswer = GetAnswerNumber(sAnswer, 0)
    If cAnswer.Count > 0 Then
        lAnswer = cAnswer(1)
        If lAnswer >= 1 And lAnswer <= MAX_SHOKYGYOU Then
            cRegInfo.Shokugyou = Format(lAnswer, "00")
        Else
            Exit Function
        End If
    Else
        Exit Function
    End If

    Shokugyou = True
End Function

'=======================================
'   よく行く店回答処理
'=======================================
Private Function YokuikuMise(sAnswer As String, oraDS As OraDynaset, cRegInfo As clsRegInfo) As Boolean
    Dim cAnswer As Collection
    Dim lAnswer As Long
    Dim sYokuikuMise As String
    Dim i As Integer
    Dim bCheck() As Boolean

    Set cAnswer = GetAnswerNumber(sAnswer, 0)
    ReDim bCheck(cAnswer.Count)
    Do Until oraDS.EOF
        For i = 1 To cAnswer.Count
            lAnswer = cAnswer(i)
            If lAnswer = oraDS("TF_answer_no").Value Then
                sYokuikuMise = cRegInfo.yokuiku_mise
                Mid(sYokuikuMise, CInt(oraDS("TF_index").Value), 1) = "1"
                cRegInfo.yokuiku_mise = sYokuikuMise
                bCheck(i) = True
            ElseIf lAnswer = 0 Then
                bCheck(i) = True
            End If
        Next
        oraDS.MoveNext
    Loop

    For i = 1 To cAnswer.Count
        If bCheck(i) = False Then
            Exit Function
        End If
    Next

    YokuikuMise = True
End Function

'=======================================
'   どっち派回答処理
'=======================================
Private Function DocchiHa(sAnswer As String, oraDS As OraDynaset, cRegInfo As clsRegInfo) As Boolean
    Dim cAnswer As Collection
    Dim sFlag As String
    Dim sDocchiHa As String

    Set cAnswer = GetAnswerNumber(sAnswer, 3)
    If cAnswer.Count > 0 Then
        Select Case cAnswer(1)
        Case 1
            sFlag = "1"
        Case 2
            sFlag = "2"
        Case 3
            sFlag = "0"
        Case Else
            Exit Function
        End Select

        sDocchiHa = cRegInfo.docchi_ha
        Mid(sDocchiHa, CInt(oraDS("TF_index").Value), 1) = sFlag
        cRegInfo.docchi_ha = sDocchiHa
    End If

    DocchiHa = True
End Function

'=======================================
'   お知らせメール回答処理
'=======================================
Private Function OshiraseMail(sAnswer As String, oraDS As OraDynaset, cRegInfo As clsRegInfo) As Boolean
    Dim cAnswer As Collection

    Set cAnswer = GetAnswerNumber(sAnswer, 0)
    If cAnswer.Count > 0 Then
        Select Case cAnswer(1)
        Case 1
            cRegInfo.oshirase_mail = "1"
        Case 2
            cRegInfo.oshirase_mail = "0"
        Case Else
            Exit Function
        End Select
    Else
        Exit Function
    End If

    OshiraseMail = True
End Function

'=======================================
'   プレゼント回答処理
'=======================================
Private Function present(sAnswer As String, oraDS As OraDynaset, cRegInfo As clsRegInfo) As Boolean
    Dim cAnswer As Collection

    Set cAnswer = GetAnswerNumber(sAnswer, 0)
    If cAnswer.Count > 0 Then
        If cAnswer(1) >= 1 And cAnswer(1) <= 4 Then
            cRegInfo.present_no = cAnswer(1)
        ElseIf cAnswer(1) <> 0 Then
            Exit Function
        End If
    End If

    present = True
End Function

'=======================================
'   エラーメール返信処理
'=======================================
Private Sub ErrorReplay(sFrom As String, sBody As String, bCheck As Variant, sSeqNo As String, bKyoumiGenreErr As Boolean)

    Dim subject As String
    Dim mailfrom As String
    Dim body As String
    Dim sErrList As String
    Dim i As Integer
    Dim iCount As Integer
    Dim sErrNo As String
    Dim sSql As String
    Dim oraDS As OraDynaset
    Dim lErrCount As Long

    If bKyoumiGenreErr Then
        sErrList = "■興味ジャンルは必ず１つ以上、２または３をご記入ください。" & vbCrLf & vbCrLf
    End If

    For i = 1 To iMaxQuestion
        If bCheck(i) = False Then
            If iCount > 0 Then
                If iCount Mod 8 = 0 Then
                    sErrList = sErrList & vbCrLf
                Else
                    sErrList = sErrList & "，"
                End If
            End If

            If i < 10 Then
                sErrNo = ToZenkaku(CStr(i))
            Else
                sErrNo = CStr(i)
            End If
            sErrList = sErrList & "◆Ｑ" & sErrNo
            iCount = iCount + 1
        End If
    Next

    GetSendMailInfo "REGERR", subject, mailfrom, body
    body = Replace(body, "%ERROR_LIST%", sErrList)
    body = Replace(body, "%REPLAY_MESSAGE%", sBody)

    sSql = "SELECT MAX(TE_err_count) FROM T_TOUROKU_MAIL,T_TOUROKU_ERR" & vbCrLf & _
           "WHERE TM_seq_no=TE_seq_no AND TM_from='" & SqlStr(sFrom) & "'"
    Set oraDS = oraDB.CreateDynaset(sSql, ORADYN_READONLY)
    If oraDS.EOF Then
        lErrCount = 1
    Else
        lErrCount = NVL(oraDS(0).Value, 0) + 1
    End If

    sSql = "SELECT * FROM T_TOUROKU_ERR WHERE TE_seq_no=" & sSeqNo
    Set oraDS = oraDB.CreateDynaset(sSql, ORADYN_DEFAULT)
    If oraDS.EOF Then
        oraDS.AddNew
        oraDS("TE_seq_no").Value = sSeqNo
        oraDS("TE_reply").Value = Empty
        If sCtErrorMail = "0" Then
            oraDS("TE_status").Value = "0"
        Else
            oraDS("TE_status").Value = "2"
        End If
        oraDS("TE_err_count") = lErrCount
        oraDS.Update

        oraDS.MoveLast
        oraDS.Edit
        oraDS("TE_reply").Value.Write body
        oraDS.Update
    End If
End Sub

'=======================================
'   仮登録処理
'=======================================
Private Sub KariTouroku(cRegInfo As clsRegInfo, sFrom As String)
    Dim sSql As String
    Dim oraDS As OraDynaset
    Dim subject As String
    Dim mailfrom As String
    Dim body As String

    '登録済みのチェック
    sSql = "SELECT MM_member_id FROM T_MEMBER WHERE MM_mail_adr='" & SqlStr(cRegInfo.mail_adr) & "' AND MM_status<>'9'"
    Set oraDS = oraDB.CreateDynaset(sSql, ORADYN_READONLY)
    If Not oraDS.EOF Then
        Exit Sub
    End If

    cRegInfo.password = GenaratePassword()

    '友達紹介データ番号設定
    sSql = "SELECT SK_shoukai_no" & vbCrLf & _
                "FROM T_SHOUKAI,T_HISHOUKAI" & vbCrLf & _
                "WHERE HS_mail_adr='" & SqlStr(cRegInfo.mail_adr) & "' AND HS_regist_flag='0' AND SK_shoukai_no=HS_shoukai_no" & vbCrLf & _
                "ORDER BY SK_shoukai_no"
    Set oraDS = oraDB.CreateDynaset(sSql, ORADYN_READONLY)
    If Not oraDS.EOF Then
        cRegInfo.shoukai_no = oraDS(0).Value
    End If

    'PRIDの設定
    sSql = "SELECT TR_pr_id FROM T_TOUROKU_REQ WHERE TR_mail_adr='" & cRegInfo.mail_adr & "' AND TR_pr_id IS NOT NULL"
    Set oraDS = oraDB.CreateDynaset(sSql, ORADYN_READONLY)
    If Not oraDS.EOF Then
        cRegInfo.pr_id = oraDS(0).Value
    End If

    'メールで登録
    cRegInfo.regist_type = "1"

    '仮登録ＤＢに保存
    cRegInfo.StoreDB

    '仮登録完了メール送信
    GetSendMailInfo "KARIREGM", subject, mailfrom, body
    body = Replace(body, "%PARAMETER%", cRegInfo.seq_no)
    body = Replace(body, "%PASSWORD%", cRegInfo.password)
    Call SendMail(cRegInfo.mail_adr, mailfrom, subject, body)

    '登録エラーメール削除
    sSql = "DELETE T_TOUROKU_ERR WHERE TE_seq_no IN" & vbCrLf & _
           "(SELECT TM_seq_no FROM T_TOUROKU_MAIL WHERE TM_from='" & SqlStr(sFrom) & "') AND TE_status='3'"
    oraDB.ExecuteSQL sSql
End Sub

'=======================================
'   質問項目数取得
'=======================================
Private Function GetItemNum() As Integer
    Dim sSql As String
    Dim oraDS As OraDynaset

    sSql = "SELECT MAX(TF_qa_no) FROM T_TOUROKU_FORM WHERE TF_version_no=" & VER_REGIST_FORM & ""
    Set oraDS = oraDB.CreateDynaset(sSql, ORADYN_READONLY)
    If Not oraDS.EOF Then
        GetItemNum = oraDS(0).Value
    End If
End Function

'=======================================
'   郵便番号チェック
'=======================================
Private Function CheckZipCode(sZip As String) As String
    Dim s As String
    Dim sSql As String
    Dim oraDS As OraDynaset

    If Mid(sZip, 4, 1) = "-" Then
        s = Left(sZip, 3) & Mid(sZip, 5)
    Else
        s = sZip
    End If

    If s <> "0000000" Then
        sSql = "SELECT ZC_zip_code FROM T_ZIPCODE WHERE ZC_zip_code='" & s & "'"
        Set oraDS = oraDB.CreateDynaset(sSql, ORADYN_READONLY)
        If oraDS.EOF Then
            s = ""
        End If
    End If

    CheckZipCode = s
End Function
