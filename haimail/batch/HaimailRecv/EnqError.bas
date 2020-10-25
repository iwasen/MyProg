Attribute VB_Name = "modEnqError"
Option Explicit

'=======================================
'   アンケート回答エラー処理
'=======================================
Public Function EnqueteError() As Long
    Dim sSql As String
    Dim oraDS As OraDynaset

    On Error GoTo ErrHandler

    sSql = "SELECT EE_seq_no,EE_status,EE_body,RM_mail_id,RM_subject,RM_from,RM_date,RM_recv_date,RM_member_id,MM_mail_adr,MM_status" & vbCrLf & _
           "FROM T_MEMBER,T_RECV_MAIL,T_ENQUETE_ERR" & vbCrLf & _
           "WHERE EE_status<>'0' AND RM_seq_no=EE_seq_no AND MM_member_id=RM_member_id"
    Set oraDS = oraDB.CreateDynaset(sSql, ORADYN_READONLY)
    Do Until oraDS.EOF
        If oraDS("MM_status") = 0 Then
            Select Case oraDS("EE_status").Value
            Case "1"
                Call ReplyOffice(oraDS)
            Case "2"
                Call ReplySender(oraDS)
            End Select

            EnqueteError = EnqueteError + 1
        End If

        sSql = "DELETE T_ENQUETE_ERR WHERE EE_seq_no=" & oraDS("EE_seq_no").Value
        oraDB.ExecuteSQL sSql

        oraDS.MoveNext
    Loop
    Exit Function

ErrHandler:
    Call WriteLog(LOG_ERROR, "EnqError.bas: " & Err.Description)
End Function

Private Sub ReplyOffice(oraDS As OraDynaset)
    Dim sSql As String
    Dim oraDS2 As OraDynaset
    Dim sBody As String

    sSql = "SELECT * FROM T_RECV_MAIL WHERE 0=1"
    Set oraDS2 = oraDB.CreateDynaset(sSql, ORADYN_DEFAULT)

    sBody = ReadCLOB(oraDS("EE_body").Value)
    AnalyzeMail oraDS2, oraDS("RM_mail_id").Value, oraDS("RM_member_id").Value, NVL(oraDS("RM_subject").Value, ""), NVL(oraDS("RM_from").Value, ""), sBody, oraDS("RM_date").Value, oraDS("RM_recv_date").Value
End Sub

Private Sub ReplySender(oraDS As OraDynaset)
    Dim sSubject As String
    Dim sFrom As String
    Dim sBody As String

    GetSendMailInfo "ENQERR", sSubject, sFrom, sBody
    sBody = ReadCLOB(oraDS("EE_body").Value)
    SendMail oraDS("MM_mail_adr").Value, sFrom, sSubject, sBody
End Sub


