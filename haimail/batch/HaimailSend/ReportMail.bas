Attribute VB_Name = "modReportMail"
Option Explicit

Private Const REPORT_HOUR1 = 9
Private Const REPORT_HOUR2 = 18
Private Const SCHEDULE_ID = "ReportMail"

'=======================================
'   レポートメール送信
'=======================================
Public Sub ReportMail()

    Dim dNow As Date
    Dim dDate As Date
    Dim nSchedule As Integer

    On Error GoTo ErrHandler

    dNow = Now
    dDate = Date

'SendReport "2001/02/02 18:00:00", "2001/02/03 09:00:00"
'Exit Sub

    nSchedule = CheckSchedule(SCHEDULE_ID)
    If nSchedule <> 0 Then
        If nSchedule = 1 And sCtSendReportAdr <> "" Then
            If Hour(dNow) = REPORT_HOUR1 Then
                SendReport DateAdd("h", REPORT_HOUR2, DateAdd("d", -1, dDate)), DateAdd("h", REPORT_HOUR1, dDate)
            ElseIf Hour(dNow) = REPORT_HOUR2 Then
                SendReport DateAdd("h", REPORT_HOUR1, dDate), DateAdd("h", REPORT_HOUR2, dDate)
            End If
        End If
        If Hour(dNow) < REPORT_HOUR2 Then
            SetSchedule SCHEDULE_ID, DateAdd("h", REPORT_HOUR2, dDate)
        Else
            SetSchedule SCHEDULE_ID, DateAdd("h", REPORT_HOUR1, DateAdd("d", 1, dDate))
        End If
    End If
    Exit Sub

ErrHandler:
    Call WriteLog(LOG_ERROR, "ReportMail.bas: " & Err.Description)

End Sub

'=======================================
'   レポートメール送信
'=======================================
Private Sub SendReport(dStart As Date, dEnd As Date)

    Dim sSendList As String
    Dim sSubject As String
    Dim sFrom As String
    Dim sBody As String
    Dim sSub1 As String
    Dim sSub2 As String
    Dim sSql As String
    Dim oraDS As OraDynaset
    Dim lTotal As Long

    sSendList = "プロジェクトID ジョブID  ジョブ名             配信数  送信終了時間" & vbCrLf & _
                "-------------------------------------------------------------------------" & vbCrLf

    sSub1 = "SELECT JB_project_id,JB_job_id,JB_job_name,COUNT(*) send_count,MAX(SR_date) last_date" & vbCrLf & _
            "FROM T_SENDMAIL_RR,T_SEND_MAIL,T_JOB" & vbCrLf & _
            "WHERE SM_status='9' AND SM_virgin_mail='0' AND JB_job_type='0' AND SM_mail_id=SR_mail_id AND JB_mail_id=SM_mail_id AND SR_status='1'" & vbCrLf & _
            "GROUP BY JB_project_id,JB_job_id,JB_job_name" & vbCrLf & _
            "HAVING MAX(SR_date)>=" & ToDate(dStart) & " AND MAX(SR_date)<" & ToDate(dEnd)

    sSub2 = "SELECT JB_project_id,JB_job_id,JB_job_name,COUNT(*) send_count,MAX(SR_date) last_date" & vbCrLf & _
            "FROM T_SENDMAIL_RR,T_SEND_MAIL,T_JOB" & vbCrLf & _
            "WHERE SR_date>=" & ToDate(dStart) & " AND SR_date<" & ToDate(dEnd) & " AND (SM_virgin_mail<>'0' OR JB_job_type<>'0') AND SM_mail_id=SR_mail_id AND JB_mail_id=SM_mail_id AND SR_status='1'" & vbCrLf & _
            "GROUP BY JB_project_id,JB_job_id,JB_job_name"

    sSql = sSub1 & " UNION " & sSub2 & vbCrLf & _
            "ORDER BY JB_job_id"
    Set oraDS = oraDB.CreateDynaset(sSql, ORADYN_READONLY)
    If Not oraDS.EOF Then
        lTotal = 0
        Do Until oraDS.EOF
            sSendList = sSendList & _
                    AdjustLength(oraDS("JB_project_id").Value, 14) & " " & _
                    AdjustLength(oraDS("JB_job_id").Value, 9) & " " & _
                    AdjustLength(oraDS("JB_job_name").Value, 16) & " " & _
                    Format(Format(oraDS("send_count").Value, "##,###,###"), "@@@@@@@@@@") & "  " & _
                    oraDS("last_date").Value & _
                    vbCrLf
            lTotal = lTotal + oraDS("send_count").Value
            oraDS.MoveNext
        Loop
        sSendList = sSendList & "-------------------------------------------------------------------------" & vbCrLf
        sSendList = sSendList & "配信総数：" & Format(lTotal, "###,###,###") & " 通"

        GetSendMailInfo "SENDREP", sSubject, sFrom, sBody
        sBody = Replace(sBody, "%START_DATE%", Format(dStart, "yyyy/mm/dd hh:nn:ss"))
        sBody = Replace(sBody, "%END_DATE%", Format(dEnd, "yyyy/mm/dd hh:nn:ss"))
        sBody = Replace(sBody, "%SEND_LIST%", sSendList)

        SendMail sCtSendReportAdr, sFrom, sSubject, sBody
    End If
End Sub

'=======================================
'   Oracle日付フォーマット変換
'=======================================
Private Function ToDate(dDateTime As Date) As String
    ToDate = "TO_DATE('" & Format(dDateTime, "yyyy/mm/dd hh:nn:ss") & "','yyyy/mm/dd hh24:mi:ss')"
End Function

'=======================================
'   文字数調整
'=======================================
Private Function AdjustLength(sText As String, iLen As Integer) As String

    Dim sStr As String
    Dim iStrLen As Integer

    sStr = sText
    Do While True
        iStrLen = HankakuLen(sStr)
        If iStrLen > iLen Then
            sStr = Left(sStr, Len(sStr) - 1)
        Else
            Exit Do
        End If
    Loop

    If iStrLen < iLen Then
        sStr = sStr & String(iLen - iStrLen, " ")
    End If

    AdjustLength = sStr

End Function
