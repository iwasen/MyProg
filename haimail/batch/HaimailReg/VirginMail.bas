Attribute VB_Name = "modVirginMail"
Option Explicit

Public Sub SendVirginMail()
    Dim sSql As String
    Dim oraDS As OraDynaset

    On Error GoTo ErrHandler

    oraDB.BeginTrans

    sSql = "SELECT NM_member_id FROM T_NEW_MEMBER"
    Set oraDS = oraDB.CreateDynaset(sSql, ORADYN_DEFAULT)
    Do Until oraDS.EOF
        SendVirginMail1 oraDS(0).Value
        oraDS.Delete
        oraDS.MoveNext
    Loop

    oraDB.CommitTrans

    Exit Sub

ErrHandler:
    oraDB.Rollback

    Call WriteLog(LOG_ERROR, "VirginMail.bas: " & Err.Description)
End Sub

Public Sub SendVirginMail1(sMemberId As String)

    Dim sSql As String
    Dim sSub1 As String
    Dim oraDS As OraDynaset
    Dim oraDS2 As OraDynaset
    Dim vWhere As Variant
    Dim dSendTime As Date

    sSql = "SELECT SM_mail_id,SM_sql,SM_virgin_day,SM_virgin_hour" & vbCrLf & _
           "FROM T_SEND_MAIL" & vbCrLf & _
           "WHERE SM_permit='1' AND SM_status<>'9' AND SYSDATE BETWEEN SM_start_date AND SM_end_date" & vbCrLf & _
           " AND (SM_virgin_mail='1' OR SM_virgin_mail='2')"
    Set oraDS = oraDB.CreateDynaset(sSql, ORADYN_READONLY)
    Do Until oraDS.EOF
        vWhere = ReadCLOB(oraDS("SM_sql").Value)

        If IsNull(oraDS("SM_virgin_day").Value) Then
            dSendTime = Now
        Else
            dSendTime = DateAdd("h", oraDS("SM_virgin_hour").Value, DateAdd("d", oraDS("SM_virgin_day").Value, Date))
        End If

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
                    oraDS2("SR_start_date").Value = dSendTime
                    oraDS2("SR_status").Value = "0"
                    oraDS2.Update
                End If
            End If
        End If
        oraDS.MoveNext
    Loop

End Sub

