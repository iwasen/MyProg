Attribute VB_Name = "modClickResponse"
Option Explicit

'=======================================
'   クリックレスポンスJOB処理
'=======================================
Public Sub ClickResponse()

    Dim sSql As String
    Dim sSub1 As String
    Dim oraDS As OraDynaset
    Dim oraDS2 As OraDynaset
    Dim sJobId As String
    Dim vWhere As Variant

    On Error GoTo ErrHandler

    sSql = "SELECT JB_job_id,JB_mail_id,JB_parent_job_id,SM_sql,SM_start_date" & vbCrLf & _
           "FROM T_JOB,T_SEND_MAIL" & vbCrLf & _
           "WHERE JB_job_type='2' AND SM_permit='1' AND SM_status<>'9' AND JB_mail_id=SM_mail_id"
    Set oraDS = oraDB.CreateDynaset(sSql, ORADYN_READONLY)
    Do Until oraDS.EOF
        sJobId = oraDS("JB_job_id").Value
        vWhere = ReadCLOB(oraDS("SM_sql").Value)
        If Not IsNull(vWhere) Then
            sSub1 = "SELECT SR_member_id FROM T_SENDMAIL_RR WHERE SR_mail_id='" & oraDS("JB_mail_id").Value & "'"
            sSql = "SELECT MM_member_id FROM T_MEMBER" & vbCrLf & _
                    "WHERE " & vWhere & " AND MM_member_id NOT IN (" & sSub1 & ")"
            Set oraDS2 = oraDB.CreateDynaset(sSql, ORADYN_READONLY)
            If Not oraDS2.EOF Then
                oraDB.BeginTrans

                On Error Resume Next
                Do Until oraDS2.EOF
                    sSql = "INSERT INTO T_SENDMAIL_RR (SR_mail_id,SR_member_id,SR_start_date,SR_status)" & vbCrLf & _
                            "VALUES ('" & oraDS("JB_mail_id").Value & "','" & oraDS2("MM_member_id").Value & "','" & oraDS("SM_start_date").Value & "','0')"
                    oraDB.ExecuteSQL sSql
                    oraDS2.MoveNext
                Loop
                On Error GoTo ErrHandler

                oraDB.CommitTrans
            End If
        End If

        oraDS.MoveNext
    Loop
    Exit Sub

ErrHandler:
    Call WriteLog(LOG_ERROR, "ClickResponse.bas: " & Err.Description)
End Sub
