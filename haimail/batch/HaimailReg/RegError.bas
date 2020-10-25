Attribute VB_Name = "modRegError"
Option Explicit

Public Function SendErrMail() As Integer
    Dim sSql As String
    Dim oraDS As OraDynaset

    On Error GoTo ErrHandler

    sSql = "SELECT * FROM T_TOUROKU_MAIL,T_TOUROKU_ERR" & vbCrLf & _
           "WHERE (TE_status='1' OR TE_status='2') AND TM_seq_no=TE_seq_no"
    Set oraDS = oraDB.CreateDynaset(sSql, ORADYN_READONLY)
    Do Until oraDS.EOF
        Select Case oraDS("TE_status").Value
        Case "1"
            Call ReplyOffice(oraDS)
        Case "2"
            Call ReplySender(oraDS)
        End Select

        SendErrMail = SendErrMail + 1
        oraDS.MoveNext
    Loop
    Exit Function

ErrHandler:
    Call WriteLog(LOG_ERROR, "RegError.bas: " & Err.Description)
End Function

Private Sub ReplyOffice(oraDS As OraDynaset)
    Dim sSubject As String
    Dim sFrom As String
    Dim sBody As String
    Dim sSql As String

    GetSendMailInfo "REGERR", sSubject, sFrom, sBody
    sBody = ReadCLOB(oraDS("TE_reply").Value)
    If SendMail(sFrom, NVL(oraDS("TM_from").Value, ""), NVL(oraDS("TM_subject").Value, ""), sBody) = "" Then
        sSql = "UPDATE T_TOUROKU_ERR SET TE_status='3' WHERE TE_seq_no=" & oraDS("TE_seq_no").Value
        oraDB.ExecuteSQL sSql
    End If
End Sub

Private Sub ReplySender(oraDS As OraDynaset)
    Dim sSubject As String
    Dim sFrom As String
    Dim sBody As String
    Dim sSql As String

    GetSendMailInfo "REGERR", sSubject, sFrom, sBody
    sBody = ReadCLOB(oraDS("TE_reply").Value)
    If SendMail(oraDS("TM_from").Value, sFrom, sSubject, sBody) = "" Then
        sSql = "UPDATE T_TOUROKU_ERR SET TE_status='3' WHERE TE_seq_no=" & oraDS("TE_seq_no").Value
        oraDB.ExecuteSQL sSql
    End If
End Sub

