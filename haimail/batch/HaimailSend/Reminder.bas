Attribute VB_Name = "modReminder"
Option Explicit

'=======================================
'   催促メール送信
'=======================================
Public Function ReminderMail() As Long

    Dim dToDay As Date

    On Error GoTo ErrHandler

    If lCtReminder > 0 Then
        dToDay = Date
        ReminderMail = SendReminderMail(dToDay)
    End If
    Exit Function

ErrHandler:
    Call WriteLog(LOG_ERROR, "Reminder.bas: " & Err.Description)

End Function

'=======================================
'   催促メール配信処理
'=======================================
Private Function SendReminderMail(dToDay As Date) As Long

    Dim sSql As String
    Dim oraDS As OraDynaset
    Dim sFromAdr As String
    Dim sToAdr As String
    Dim sSubject As String
    Dim sBody As String
    Dim sBody2 As String
    Dim sResult As String
    Dim lCount As Long

    lCount = 0

    sSql = "SELECT KT_seq_no,KT_mail_adr,KT_reminder_flag" & vbCrLf & _
           "FROM T_KARI_TOUROKU" & vbCrLf & _
           "WHERE KT_status='0' AND KT_reminder_flag='0' AND KT_regist_date<'" & DateAdd("d", -lCtReminder, dToDay) & "'"
    Set oraDS = oraDB.CreateDynaset(sSql, ORADYN_DEFAULT)
    If Not oraDS.EOF Then
        GetSendMailInfo "REMINDER", sSubject, sFromAdr, sBody
        Do Until oraDS.EOF
            oraDS.Edit
            oraDS("KT_reminder_flag") = "1"
            oraDS.Update

            sToAdr = oraDS("KT_mail_adr").Value
            sBody2 = Replace(sBody, "%PARAMETER%", oraDS("KT_seq_no").Value)
            sResult = SendMail(sToAdr, sFromAdr, sSubject, sBody2)

            lCount = lCount + 1
            oraDS.MoveNext
        Loop
    End If

    SendReminderMail = lCount

End Function
