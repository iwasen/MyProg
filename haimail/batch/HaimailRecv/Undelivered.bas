Attribute VB_Name = "modUndelivered"
Option Explicit

Private lCount As Long

'=======================================
'   不達メール受信処理
'=======================================
Public Function UndeliveredMail() As Long
    Dim vFiles As Variant
    Dim lMailNum As Long
    Dim vFile As Variant
    Dim vArray As Variant

    lCount = 0

    On Error GoTo ErrHandler

    If sCtUdUser <> "" Then
        'メール受信
        vFiles = RecvMail(sCtPop3Server, sCtUdUser, sCtUdPass, lMailNum)
    
        '受信したメールを受信ログに保存
        If lMailNum > 0 And IsArray(vFiles) Then   ' OK ?
    
            oraDB.BeginTrans
        
            For Each vFile In vFiles
                vArray = objMail.ReadMail(vFile, "nofile:", "")
                If IsArray(vArray) Then   ' OK ?
                    Call UndeliveredProc(vArray)
                End If
            Next
        
            oraDB.CommitTrans
    
            'テンポラリファイル削除
            DeleteMailTemp vFiles
    
            Call DeleteMail(sCtPop3Server, sCtUdUser, sCtUdPass, lMailNum)
        End If
    End If

    UndeliveredMail = lCount
    Exit Function

ErrHandler:
    oraDB.Rollback

    'エラーログ書き込み
    Call WriteLog(LOG_ERROR, "Undelivered.bas: " & Err.Description)

    'テンポラリファイル削除
    DeleteMailTemp vFiles
End Function

'=======================================
'   １メールごとの処理
'=======================================
Private Sub UndeliveredProc(vArray As Variant)
    Dim vData As Variant
    Dim sBody As String
    Dim ary As Variant
    Dim s As Variant
    Dim sMailId As String
    Dim sMemberId As String
    Dim sHaimail As String
    Dim bWarning As Boolean
    Dim p As Integer

    'メール内容分解
    For Each vData In vArray
        If LCase(Left(vData, 5)) = "body:" Then
            sBody = Mid(vData, 6)
        End If
    Next

    If sBody <> "" Then
        ary = Split(sBody, vbCrLf)
        For Each s In ary
            If Left(s, 10) = "X-Haimail:" Then
                sHaimail = Trim(Mid(s, 11))
            ElseIf InStr(s, "(warning-timeout)") > 0 Then
                bWarning = True
            End If
        Next
    End If

    If Not bWarning Then
        If sHaimail <> "" Then
            p = InStr(sHaimail, "-")
            If p > 1 Then
                sMailId = Left(sHaimail, p - 1)
                sMemberId = Right(String(LEN_MEMBER_ID, "0") & Mid(sHaimail, p + 1), LEN_MEMBER_ID)
            End If
        Else
            GetIdNumber sBody, sMailId, sMemberId
        End If

        If Len(sMailId) = 8 And IsNumeric(sMemberId) Then
            UndeliveredCount sMailId, sMemberId
        End If
    End If

End Sub

'=======================================
'   １メールごとの処理
'=======================================
Private Sub UndeliveredCount(sMailId As String, sMemberId As String)
    Dim sSql As String
    Dim sSQ1 As String
    Dim sSQ2 As String
    Dim oraDS As OraDynaset
    Dim oraDS2 As OraDynaset
    Dim iUdCount As Integer

    sSql = "SELECT * FROM T_MEMBER WHERE MM_member_id='" & SqlStr(sMemberId) & "' AND MM_status<>'9'"
    Set oraDS = oraDB.CreateDynaset(sSql, ORADYN_READONLY)
    If Not oraDS.EOF Then
        sSql = "SELECT * FROM T_UNDELIVERED WHERE UD_member_id='" & SqlStr(sMemberId) & "'"
        Set oraDS = oraDB.CreateDynaset(sSql, ORADYN_DEFAULT)
        If oraDS.EOF Then
            oraDS.AddNew
            oraDS("UD_member_id").Value = sMemberId
            oraDS("UD_count").Value = 1
            oraDS("UD_mail_id").Value = sMailId
            oraDS.Update
        Else
            sSQ1 = "SELECT SR_date FROM T_SENDMAIL_RR WHERE SR_mail_id='" & sMailId & "' AND SR_member_id='" & sMemberId & "'"
            sSQ2 = "SELECT MAX(SR_date) FROM T_SENDMAIL_RR WHERE SR_member_id='" & sMemberId & "'" & _
                    " AND SR_date<(" & sSQ1 & ") AND SR_status='1'"
            sSql = "SELECT SR_mail_id FROM T_SENDMAIL_RR WHERE SR_mail_id='" & oraDS("UD_mail_id").Value & "'" & _
                    " AND SR_member_id='" & sMemberId & "'" & _
                    " AND SR_date=(" & sSQ2 & ")"
            Set oraDS2 = oraDB.CreateDynaset(sSql, ORADYN_READONLY)
            If oraDS2(0).Value = oraDS("UD_mail_id").Value Then
                iUdCount = oraDS("UD_count").Value + 1
            Else
                iUdCount = 1
            End If
            oraDS.Edit
            oraDS("UD_count").Value = iUdCount
            oraDS("UD_mail_id").Value = sMailId
            oraDS.Update

            If iCtUdCount > 0 And iUdCount >= iCtUdCount Then
                DeleteMember sMemberId
            End If
        End If

        lCount = lCount + 1
    End If
End Sub

'=======================================
'   メンバー削除処理
'=======================================
Private Sub DeleteMember(sMemberId As String)
    Dim sSql As String

    sSql = "UPDATE T_MEMBER SET MM_status='9' WHERE MM_member_id='" & sMemberId & "'"
    oraDB.ExecuteSQL sSql

    sSql = "INSERT INTO T_TAIKAI (TK_member_id,TK_taikai_date,TK_taikai_flag) VALUES (" & _
            "'" & sMemberId & "'," & _
            "SYSDATE," & _
            "'2')"
    oraDB.ExecuteSQL sSql

    sSql = "DELETE FROM T_UNDELIVERED WHERE UD_member_id='" & sMemberId & "'"
    oraDB.ExecuteSQL sSql

    sSql = "BEGIN S_DELETE_SEARCH_DATA('" & sMemberId & "'); END;"
    oraDB.ExecuteSQL sSql
End Sub
