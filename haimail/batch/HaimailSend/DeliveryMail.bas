Attribute VB_Name = "modDeliveryMail"
Option Explicit

'１回の最大送信数
Const MAX_SEND_MAIL = 1000
Const MAX_SEND_HTML = 100

Const STATUS_INIT = "0"
Const STATUS_SAMPLING = "1"
Const STATUS_SENDING = "2"
Const STATUS_ERROR = "3"
Const STATUS_WAITING = "4"
Const STATUS_END = "9"

Private m_lCount As Long

'=======================================
'   メール配信処理
'=======================================
Public Function DeliveryMail() As Long
    Dim sSql As String
    Dim oraDS As OraDynaset
    Dim sMailId As String
    Dim sMailKind As String
    Dim sSubject As String
    Dim sFrom As String
    Dim sBody As String
    Dim lPoint As Long
    Dim sStatus As String
    Dim sMailFormat As String
    Dim sHtmlPict As String

    On Error GoTo ErrHandler

    m_lCount = 0

    '配信終了日付を過ぎたメールの処置
    sSql = "SELECT * FROM T_SEND_MAIL WHERE SM_permit='1' AND SM_status<>'9' AND SM_end_date < SYSDATE ORDER BY SM_start_date"
    Set oraDS = oraDB.CreateDynaset(sSql, ORADYN_DEFAULT)
    Do Until oraDS.EOF
        sMailId = oraDS("SM_mail_id").Value
        sMailKind = oraDS("SM_mail_kind").Value
        sSubject = oraDS("SM_subject").Value
        sFrom = oraDS("SM_from").Value
        sBody = GetSendContents(oraDS)
        sMailFormat = NVL(oraDS("SM_mail_format").Value, "0")
        sHtmlPict = NVL(oraDS("SM_html_pict").Value, "0")

        '配信終了通知
        If NVL(oraDS("SM_notice_flag").Value, "0") = "0" Then
            SendNotice sMailId, sMailKind, sSubject, sFrom, sBody, sMailFormat, sHtmlPict, oraDS("SM_end_date").Value
        End If

        '未送信メール削除
        ExpireMail sMailId

        SetStatus sMailId, STATUS_END

        oraDS.MoveNext
    Loop

    sSql = "SELECT * FROM T_SEND_MAIL WHERE SM_permit='1' AND SM_status<>'9' AND SYSDATE BETWEEN SM_start_date AND SM_end_date ORDER BY SM_start_date"
    Set oraDS = oraDB.CreateDynaset(sSql, ORADYN_DEFAULT)
    Do Until oraDS.EOF
        sMailId = oraDS("SM_mail_id").Value
        sMailKind = oraDS("SM_mail_kind").Value
        sStatus = oraDS("SM_status").Value
        If sStatus = STATUS_INIT Then
            If SamplingData(sMailId, sMailKind, oraDS) Then
                sStatus = STATUS_SAMPLING
                SetStatus sMailId, sStatus
            End If
        End If

        If sStatus <> "0" Then
            sSubject = oraDS("SM_subject").Value
            sFrom = oraDS("SM_from").Value
            sBody = GetSendContents(oraDS)
            sMailFormat = NVL(oraDS("SM_mail_format").Value, "0")
            sHtmlPict = NVL(oraDS("SM_html_pict").Value, "0")
            lPoint = NVL(oraDS("SM_point_jushin").Value, 0)

            'ステータス送信中
            SetStatus sMailId, STATUS_SENDING

            'メール送信
            sStatus = SendMailAll(sMailId, sMailKind, sSubject, sFrom, sBody, sMailFormat, sHtmlPict, lPoint, oraDS)
            SetStatus sMailId, sStatus

            If sStatus = STATUS_END Then
                If NVL(oraDS("SM_notice_flag").Value, "0") = "0" Then
                    '配信終了通知
                    SendNotice sMailId, sMailKind, sSubject, sFrom, sBody, sMailFormat, sHtmlPict, Now
                End If

                '未送信メール削除
                ExpireMail sMailId
            End If
        End If

        oraDS.MoveNext
    Loop

    DeliveryMail = m_lCount
    Exit Function

ErrHandler:
    Call WriteLog(LOG_ERROR, "DeliveryMail.bas: " & Err.Description)
End Function

'=======================================
'   未送信データ削除処理
'=======================================
Private Sub ExpireMail(sMailId As String)

    Dim sSql As String
    Dim oraDS As OraDynaset

    sSql = "DELETE /*+ INDEX(T_SENDMAIL_RR) */ T_SENDMAIL_RR WHERE SR_mail_id='" & sMailId & "' AND SR_status='0'"
    oraDB.ExecuteSQL sSql

End Sub


'=======================================
'   配信先サンプリング処理
'=======================================
Private Function SamplingData(sMailId As String, sMailKind As String, oraDS As OraDynaset) As Boolean

    If oraDS("SM_virgin_mail").Value = "1" Then
        'バージンメールのみ
        SamplingData = True
    Else
        Select Case sMailKind
        Case "Z"    '全配信
            SamplingData = SamplingMember(sMailId)
        Case "B"    'ビジネス配信
            SamplingData = SamplingBusiness(sMailId)
        Case "S"    '営業配信
            SamplingData = SamplingSales(sMailId, oraDS)
        End Select
    End If

End Function

'=======================================
'   全配信サンプリング処理
'=======================================
Private Function SamplingMember(sMailId As String) As Boolean

    Dim sSql As String

    On Error Resume Next

    sSql = "INSERT INTO T_SENDMAIL_RR (SR_mail_id,SR_member_id,SR_status)" & vbCrLf & _
           "SELECT '" & sMailId & "',MM_member_id,'0' FROM T_MEMBER WHERE MM_status='0'"
    Err.Clear
    oraDB.ExecuteSQL sSql

    If Err.Number = 0 Then
        SamplingMember = True
    Else
        SamplingMember = False
    End If

End Function

'=======================================
'   ビジネス配信サンプリング処理
'=======================================
Private Function SamplingBusiness(sMailId As String) As Boolean

    Dim sSql As String

    On Error Resume Next

    sSql = "INSERT INTO T_SENDMAIL_RR2 (SR_mail_id,SR_member_id,SR_status)" & vbCrLf & _
           "SELECT '" & sMailId & "',HL_hml_id,'0' FROM T_HAIMAIL_LETTER"
    Err.Clear
    oraDB.ExecuteSQL sSql

    If Err.Number = 0 Then
        SamplingBusiness = True
    Else
        SamplingBusiness = False
    End If

End Function

'=======================================
'   営業配信サンプリング処理
'=======================================
Private Function SamplingSales(sMailId As String, oraDS As OraDynaset) As Boolean

    Dim dStartDate As Date
    Dim lSendNum As Long
    Dim lBunkatsuKankaku As Long
    Dim lBunkatsuKaisuu As Long
    Dim sSql As String
    Dim oraDS2 As OraDynaset
    Dim lRec As Long
    Dim sMemberId() As String
    Dim i As Long
    Dim j As Long
    Dim sTemp As String
    Dim lBunkatsuSuu As Long
    Dim lSendNum2 As Long

    On Error Resume Next

    SamplingSales = False

    sSql = "SELECT count(*) FROM T_SENDMAIL_RR WHERE SR_mail_id='" & sMailId & "'"
    Set oraDS2 = oraDB.CreateDynaset(sSql, ORADYN_READONLY)
    If Not oraDS2.EOF Then
        lSendNum2 = NVL(oraDS2(0).Value, 0)
    Else
        lSendNum2 = 0
    End If

    lSendNum = NVL(oraDS("SM_send_num").Value, 0) - lSendNum2
    If lSendNum <= 0 Then
        SamplingSales = True
        Exit Function
    End If

    dStartDate = oraDS("SM_start_date").Value
    lBunkatsuKankaku = NVL(oraDS("SM_bunkatsu_kankaku").Value, 0)
    lBunkatsuKaisuu = NVL(oraDS("SM_bunkatsu_kaisuu").Value, 0)
    sSql = ReadCLOB(oraDS("SM_sql").Value)
    If IsNull(sSql) Then
        Exit Function
    End If

    '追加発信の場合発信済みのメンバーを除く
    If lSendNum2 > 0 Then
        sSql = sSql & " AND MM_member_id NOT IN (SELECT SR_member_id FROM T_SENDMAIL_RR WHERE SR_mail_id='" & sMailId & "')"
    End If

    '検索条件に合うメンバーの抽出
    sSql = "SELECT MM_member_id FROM T_MEMBER WHERE " & sSql
    Set oraDS2 = oraDB.CreateDynaset(sSql, ORADYN_READONLY)
    lRec = oraDS2.RecordCount
    ReDim sMemberId(lRec)
    For i = 1 To lRec
        If oraDS2.EOF Then
            Exit For
        End If
        sMemberId(i) = oraDS2(0).Value
        oraDS2.MoveNext
    Next

    'ランダムに並べ替え
    Randomize
    For i = lRec To 1 Step -1
      j = Int(Rnd * i) + 1
      sTemp = sMemberId(i)
      sMemberId(i) = sMemberId(j)
      sMemberId(j) = sTemp
    Next

    '配信数
    If lRec < lSendNum Then
        lSendNum = lRec
    End If

    '分割配信
    If lBunkatsuKaisuu = 0 Then
        lBunkatsuKaisuu = 1
    End If

    sSql = "SELECT * FROM T_SENDMAIL_RR WHERE 0=1"
    Set oraDS2 = oraDB.CreateDynaset(sSql, ORADYN_DEFAULT)
    For i = 1 To lSendNum
        oraDS2.AddNew
        oraDS2("SR_mail_id").Value = sMailId
        oraDS2("SR_member_id").Value = sMemberId(i)
        oraDS2("SR_start_date").Value = DateAdd("d", Int(CDbl(i - 1) / lSendNum * lBunkatsuKaisuu), dStartDate)
        oraDS2("SR_status").Value = "0"
        oraDS2.Update
    Next

    SamplingSales = True

End Function

'=======================================
'   送信コンテンツ取得
'=======================================
Private Function GetSendContents(oraDS As OraDynaset) As String

    Dim sBody As String
    Dim vTemp As Variant

    sBody = LaxxxrLf(ReadCLOB(oraDS("SM_body").Value))

    'ヘッダ付加
    vTemp = oraDS("SM_header").Value
    If Len(vTemp) > 0 Then
        sBody = LaxxxrLf(vTemp) & vbCrLf & sBody
    End If

    '感想文付加
    vTemp = oraDS("SM_impression").Value
    If Len(vTemp) > 0 Then
        sBody = sBody & vbCrLf & LaxxxrLf(vTemp)
    End If

    'フッタ付加
    vTemp = oraDS("SM_footer").Value
    If Len(vTemp) > 0 Then
        sBody = sBody & vbCrLf & LaxxxrLf(vTemp)
    End If

    GetSendContents = sBody

End Function

'=======================================
'   配信終了通知送信
'=======================================
Private Sub SendNotice(sMailId As String, sMailKind As String, sSubject As String, sFrom As String, sBody As String, sMailFormat As String, sHtmlPict As String, dEndDate As Date)
    Dim sSql As String
    Dim oraDS As OraDynaset
    Dim sHeader As String
    Dim sSubject2 As String
    Dim sResult As String
    Dim objBasp As New Basp21
    Dim sAttachment As String
    Dim cSendMail As New clsSendMail
    Dim lSeqNo As Long

    Select Case sMailKind
    Case "Z"    '全配信
        sSubject2 = "【速報】" & sSubject
        sHeader = "全配信を終了しました。"
    Case "B"    'ビジネス配信
        sSubject2 = "【速報】" & sSubject
        sHeader = "ビジネス配信を終了しました。"
    Case "S"    '営業配信
        sSubject2 = "【掲載誌】" & sSubject
        sHeader = GetCompanyName(sMailId) & " ご担当者様" & vbCrLf & _
                 "――――――――――――――――――――――――――――――――――――" & vbCrLf & _
                 "ご利用頂き誠にありがとうございます。" & vbCrLf & _
                 "株式会社○○○○○○、はいめーるセンターです。" & vbCrLf & _
                 "ご依頼頂きました配信を終了いたしましたので、ご報告させていただきます。"
    End Select

    sHeader = sHeader & vbCrLf & vbCrLf & _
        "    ┌―――――――――――――――――――――――――――――┐" & vbCrLf & _
        ":　　【配信終了時間】" & Format(dEndDate, "yyyy年m月d日　hh：nn") & vbCrLf & _
        ":　　【最終配信通数】" & FormatNumber(GetSendNum(sMailId), 0) & "通" & vbCrLf & _
        "    └―――――――――――――――――――――――――――――┘" & vbCrLf & vbCrLf & _
        "【配信メールコンテンツは以下の通りです】" & vbCrLf & _
        "サブジェクト：" & sSubject & vbCrLf & vbCrLf & _
        "本文：" & vbCrLf & vbCrLf

    If sMailFormat <> "0" Then
        sHeader = Replace(sHeader, vbCrLf, "<br>" & vbCrLf)
    End If

    cSendMail.SetSendMailData sMailId, sSubject2, sFrom, sHeader & sBody, sMailFormat, sHtmlPict

    lSeqNo = 0
    If sCtSokuhouAdr <> "" Then
        lSeqNo = lSeqNo + 1
        cSendMail.SetSendMailList sCtSokuhouAdr, sMailId, "0", "***", lSeqNo
    End If

    sSql = "SELECT SN_mail_adr FROM T_SEND_NOTICE WHERE SN_mail_id='" & sMailId & "' ORDER BY SN_seq_no"
    Set oraDS = oraDB.CreateDynaset(sSql, ORADYN_READONLY)
    Do Until oraDS.EOF
        lSeqNo = lSeqNo + 1
        cSendMail.SetSendMailList oraDS(0).Value, sMailId, "0", "***", lSeqNo
        oraDS.MoveNext
    Loop

    oraDB.ExecuteSQL "UPDATE T_SEND_MAIL SET SM_notice_flag='1' WHERE SM_mail_id='" & sMailId & "'"
End Sub

'=======================================
'   企業名取得
'=======================================
Private Function GetCompanyName(sMailId As String) As String
    Dim sSql As String
    Dim oraDS As OraDynaset

    sSql = "SELECT CM_name" & vbCrLf & _
           "FROM T_JOB,T_PROJECT,T_TANTOUSHA,T_COMPANY" & vbCrLf & _
           "WHERE JB_mail_id='" & sMailId & "' AND PJ_project_id=JB_project_id AND TS_tantousha_id=PJ_tantousha_id AND CM_company_id=TS_company_id"
    Set oraDS = oraDB.CreateDynaset(sSql, ORADYN_READONLY)
    If Not oraDS.EOF Then
        GetCompanyName = oraDS(0).Value
    End If

End Function

'=======================================
'   配信数取得
'=======================================
Private Function GetSendNum(sMailId As String) As Long
    Dim sSql As String
    Dim oraDS As OraDynaset

    sSql = "SELECT NVL(SM_real_send,0)" & vbCrLf & _
           "FROM T_SEND_MAIL" & vbCrLf & _
           "WHERE SM_mail_id='" & sMailId & "'"
    Set oraDS = oraDB.CreateDynaset(sSql, ORADYN_READONLY)
    If Not oraDS.EOF Then
        GetSendNum = oraDS(0).Value
    End If

End Function

'=======================================
'   メール配信処理
'=======================================
Private Function SendMailAll(sMailId As String, sMailKind As String, sSubject As String, sFrom As String, sBody As String, sMailFormat As String, sHtmlPict As String, lPoint As Long, oraDS As OraDynaset) As String

    Dim lCount As Long
    Dim lSendNum As Long
    Dim lRealSend As Long
    Dim sVirginMail As String
    Dim lMaxSend As Long

    lCount = m_lCount

    If sMailFormat = "0" Then
        lMaxSend = MAX_SEND_MAIL
    Else
        lMaxSend = MAX_SEND_HTML
    End If

    Select Case sMailKind
    Case "Z"    '全配信
        SendMailAll = SendZenhaishin(sMailId, sSubject, sFrom, sBody, sMailFormat, sHtmlPict, lPoint, lMaxSend)
    Case "B"    'ビジネス配信
        SendMailAll = SendBusiness(sMailId, sSubject, sFrom, sBody, sMailFormat, sHtmlPict, lPoint, lMaxSend)
    Case "S"    '営業配信
        lSendNum = CLng(NVL(oraDS("SM_send_num").Value, 0))
        lRealSend = CLng(NVL(oraDS("SM_real_send").Value, 0))
        sVirginMail = NVL(oraDS("SM_virgin_mail").Value, "0")
        SendMailAll = SendSales(sMailId, sSubject, sFrom, sBody, sMailFormat, sHtmlPict, lPoint, lMaxSend, lSendNum, lRealSend, sVirginMail)
    End Select

    lCount = m_lCount - lCount
    If lCount > 0 Then
        oraDB.ExecuteSQL "UPDATE T_SEND_MAIL SET SM_real_send=NVL(SM_real_send,0)+" & lCount & " WHERE SM_mail_id='" & sMailId & "'"
    End If

End Function

'=======================================
'   全配信処理
'=======================================
Private Function SendZenhaishin(sMailId As String, sSubject As String, sFrom As String, sBody As String, sMailFormat As String, sHtmlPict As String, lPoint As Long, lMaxSend As Long) As String
    Dim sSql As String
    Dim oraDS As OraDynaset
    Dim sMemberId As String
    Dim sMailAdr As String
    Dim sResult As String
    Dim lCount As Long
    Dim lTotalPoint As Long
    Dim cSendMail As New clsSendMail

    lCount = 0

    sSql = "SELECT SR_member_id,MM_mail_adr,PT_point FROM T_SENDMAIL_RR,T_MEMBER,V_TOTAL_POINT" & vbCrLf & _
           "WHERE SR_mail_id='" & sMailId & "' AND SR_status='0' AND SR_member_id=MM_member_id AND SR_member_id=PT_member_id(+)"
    Set oraDS = oraDB.CreateDynaset(sSql, ORADYN_READONLY)
    Do Until oraDS.EOF
        If lCount = 0 Then
            cSendMail.SetSendMailData sMailId, sSubject, sFrom, sBody, sMailFormat, sHtmlPict
        End If

        sMemberId = oraDS(0).Value
        sMailAdr = oraDS(1).Value
        lTotalPoint = NVL(oraDS(2).Value, 0) + lPoint

        cSendMail.SetSendMailList sMailAdr, sMailId, sMemberId, lTotalPoint, lCount + 1
        m_lCount = m_lCount + 1

        sSql = "UPDATE T_SENDMAIL_RR SET SR_status='1',SR_date=SYSDATE,SR_send_error=NULL WHERE SR_mail_id='" & sMailId & "' AND SR_member_id='" & sMemberId & "'"
        oraDB.ExecuteSQL sSql
        If lPoint <> 0 Then
            Call AddPoint(sMemberId, "10", lPoint, sMailId)
        End If

        lCount = lCount + 1
        If lCount >= lMaxSend Then
            SendZenhaishin = STATUS_WAITING
            Exit Function
        End If

        oraDS.MoveNext
    Loop

    SendZenhaishin = STATUS_END

End Function

'=======================================
'   ビジネス配信処理
'=======================================
Private Function SendBusiness(sMailId As String, sSubject As String, sFrom As String, sBody As String, sMailFormat As String, sHtmlPict As String, lPoint As Long, lMaxSend As Long) As String
    Dim sSql As String
    Dim oraDS As OraDynaset
    Dim sMemberId As String
    Dim sMailAdr As String
    Dim sResult As String
    Dim lCount As Long
    Dim cSendMail As New clsSendMail

    lCount = 0

    sSql = "SELECT SR_member_id,HL_mail_adr FROM T_SENDMAIL_RR2,T_HAIMAIL_LETTER" & vbCrLf & _
           "WHERE SR_mail_id='" & sMailId & "' AND SR_status='0' AND SR_member_id=HL_hml_id"
    Set oraDS = oraDB.CreateDynaset(sSql, ORADYN_READONLY)
    Do Until oraDS.EOF
        If lCount = 0 Then
            cSendMail.SetSendMailData sMailId, sSubject, sFrom, sBody, sMailFormat, sHtmlPict
        End If

        sMemberId = oraDS(0).Value
        sMailAdr = oraDS(1).Value

        cSendMail.SetSendMailList sMailAdr, sMailId, sMemberId, 0, lCount + 1
        m_lCount = m_lCount + 1

        sSql = "UPDATE T_SENDMAIL_RR2 SET SR_status='1',SR_date=SYSDATE,SR_send_error=NULL WHERE SR_mail_id='" & sMailId & "' AND SR_member_id='" & sMemberId & "'"
        oraDB.ExecuteSQL sSql

        lCount = lCount + 1
        If lCount >= lMaxSend Then
            SendBusiness = STATUS_WAITING
            Exit Function
        End If

        oraDS.MoveNext
    Loop

    SendBusiness = STATUS_END

End Function

'=======================================
'   営業配信処理
'=======================================
Private Function SendSales(sMailId As String, sSubject As String, sFrom As String, sBody As String, sMailFormat As String, sHtmlPict As String, lPoint As Long, lMaxSend As Long, lSendNum As Long, lRealSend As Long, sVirginMail As String) As String
    Dim sSql As String
    Dim sSub1 As String
    Dim oraDS As OraDynaset
    Dim oraDS2 As OraDynaset
    Dim sMemberId As String
    Dim sMailAdr As String
    Dim sResult As String
    Dim lCount As Long
    Dim lTotalPoint As Long
    Dim sCompanyId As String
    Dim sJobType As String
    Dim cSendMail As New clsSendMail

    lCount = 0

    sSql = "SELECT JB_job_type,TS_company_id" & vbCrLf & _
           "FROM T_JOB,T_PROJECT,T_TANTOUSHA" & vbCrLf & _
           "WHERE JB_mail_id='" & sMailId & "' AND JB_project_id=PJ_project_id AND PJ_tantousha_id=TS_tantousha_id"
    Set oraDS = oraDB.CreateDynaset(sSql, ORADYN_READONLY)
    If Not oraDS.EOF Then
        sJobType = oraDS(0).Value
        sCompanyId = oraDS(1).Value
    End If

    sSub1 = "SELECT SC_member_id FROM T_SEND_COMPANY WHERE SC_company_id='" & sCompanyId & "'"
    sSql = "SELECT /*+ INDEX(T_SENDMAIL_RR) */ SR_member_id,MM_mail_adr,PT_point,SC_member_id,MM_status" & vbCrLf & _
           "FROM (" & sSub1 & "),T_SENDMAIL_RR,T_MEMBER,V_TOTAL_POINT" & vbCrLf & _
           "WHERE SR_mail_id='" & sMailId & "' AND SR_status='0' AND SR_start_date<=SYSDATE AND SR_member_id=MM_member_id AND SR_member_id=PT_member_id(+) AND SR_member_id=SC_member_id(+)"
    Set oraDS = oraDB.CreateDynaset(sSql, ORADYN_READONLY)
    Do Until oraDS.EOF
        sMemberId = oraDS(0).Value
        sMailAdr = oraDS(1).Value
        
        If oraDS(4).Value = "0" Then
            If lCount = 0 Then
                cSendMail.SetSendMailData sMailId, sSubject, sFrom, sBody, sMailFormat, sHtmlPict
            End If
    
            lTotalPoint = NVL(oraDS(2).Value, 0) + lPoint
    
            cSendMail.SetSendMailList sMailAdr, sMailId, sMemberId, lTotalPoint, lCount + 1
            m_lCount = m_lCount + 1
    
            sSql = "UPDATE T_SENDMAIL_RR SET SR_status='1',SR_date=SYSDATE,SR_send_error=NULL WHERE SR_mail_id='" & sMailId & "' AND SR_member_id='" & sMemberId & "'"
            oraDB.ExecuteSQL sSql
            If lPoint <> 0 Then
                Call AddPoint(sMemberId, "10", lPoint, sMailId)
            End If
    
            If sCompanyId <> "" Then
                If IsNull(oraDS(3).Value) Then
                    sSql = "INSERT INTO T_SEND_COMPANY VALUES ('" & sMemberId & "','" & sCompanyId & "','0')"
                    oraDB.ExecuteSQL sSql
                End If
            End If
    
            lCount = lCount + 1
    
            '最大配信数に達したら終了
            If lRealSend + lCount >= lSendNum Then
                SendSales = STATUS_END
                Exit Function
            End If
    
            If lCount >= lMaxSend Then
                SendSales = STATUS_WAITING
                Exit Function
            End If
        Else
            sSql = "DELETE FROM T_SENDMAIL_RR WHERE SR_mail_id='" & sMailId & "' AND SR_member_id='" & sMemberId & "'"
            oraDB.ExecuteSQL sSql
        End If

        oraDS.MoveNext
    Loop

    '終了判定
    If sVirginMail = "0" And sJobType = "0" Then
        sSql = "SELECT /*+ INDEX(T_SENDMAIL_RR) */ COUNT(*) FROM T_SENDMAIL_RR WHERE SR_mail_id='" & sMailId & "' AND SR_status='0'"
        Set oraDS = oraDB.CreateDynaset(sSql, ORADYN_READONLY)
        If NVL(oraDS(0).Value, 0) = 0 Then
            SendSales = STATUS_END
        Else
            SendSales = STATUS_WAITING
        End If
    Else
        SendSales = STATUS_WAITING
    End If

End Function

'=======================================
'   エラー有無チェック
'=======================================
Private Function ErrorCheck(sMailId As String) As Boolean

    Dim sSql As String
    Dim oraDS As OraDynaset

    sSql = "SELECT COUNT(*) FROM T_SENDMAIL_RR WHERE SR_mail_id='" & sMailId & "' AND SR_status='2'"
    Set oraDS = oraDB.CreateDynaset(sSql, ORADYN_READONLY)
    If oraDS.EOF Then
        ErrorCheck = False
    Else
        If oraDS(0).Value > 0 Then
            ErrorCheck = True
        Else
            ErrorCheck = False
        End If
    End If

End Function

'=======================================
'   添付ファイル作成
'=======================================
Private Function MakeAttachment(sMailId As String, objBasp As Basp21) As String
    Dim sSql As String
    Dim oraDS As OraDynaset
    Dim sTempFile As String
    Dim bArray As Variant
    Dim sFile As String

    sSql = "SELECT * FROM T_ATTACHMENT WHERE AT_mail_id='" & sMailId & "' ORDER BY AT_seq_no"
    Set oraDS = oraDB.CreateDynaset(sSql, ORADYN_READONLY)
    Do Until oraDS.EOF
        sTempFile = objBasp.GetTempFile()
        oraDS("AT_file_body").Value.Read bArray
        objBasp.BinaryWrite bArray, sTempFile

        If sFile <> "" Then
            sFile = sFile & vbTab
        End If
        sFile = sFile & sTempFile & "|" & oraDS("AT_file_name").Value

        oraDS.MoveNext
    Loop

    MakeAttachment = sFile
End Function

'=======================================
'   ステータス設定
'=======================================
Private Sub SetStatus(sMailId As String, sStatus As String)
    oraDB.ExecuteSQL "UPDATE T_SEND_MAIL SET SM_status='" & sStatus & "' WHERE SM_mail_id='" & sMailId & "'"
End Sub

