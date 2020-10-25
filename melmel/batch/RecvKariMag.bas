Attribute VB_Name = "modRecvKariMag"
Option Explicit

'=======================================
'   仮登録メール受信処理
'=======================================
Public Function RecvKariMag() As Integer

    Dim objRS As ADODB.Recordset
    Dim sSql As String
    Dim vFiles As Variant
    Dim lMailNum As Long
    Dim vFile As Variant
    Dim vArray As Variant
    Dim vData As Variant
    Dim iCount As Integer

    On Error GoTo ErrHandler

    'メール受信
    vFiles = RecvMail(sPop3Server1, sPop3KmagUser, sPop3KmagPass, lMailNum)

    '受信したメールを仮登録メルマガ受信ログに保存
    If lMailNum > 0 And IsArray(vFiles) Then   ' OK ?

        objDB.BeginTrans

        '仮登録メルマガ受信ログオープン
        Set objRS = CreateObject("ADODB.RecordSet")
        sSql = "SELECT * FROM T_KMAILLOG WHERE 0=1"
        objRS.Open sSql, objDB, adOpenKeyset, adLockPessimistic

        For Each vFile In vFiles
            vArray = objMail.ReadMail(vFile, "subject:from:date:X-Mag2Id:nofile:", "")
            If IsArray(vArray) Then   ' OK ?
                Call SetMailData(objRS, vArray)
                iCount = iCount + 1
            End If
        Next

        objRS.Close

        objDB.CommitTrans

        'テンポラリファイル削除
        DeleteMailTemp vFiles

        Call DeleteMail(sPop3Server1, sPop3KmagUser, sPop3KmagPass, lMailNum)
    End If

    RecvKariMag = iCount
    Exit Function

ErrHandler:
    objDB.RollbackTrans

    If objDB.Errors.Count <> 0 Then
        Call WriteLog(LOG_ERROR, "SQL Server Error: " & objDB.Errors.Item(0))
    Else
        Call WriteLog(LOG_ERROR, "Program Error: " & Err.Description)
    End If

    'テンポラリファイル削除
    DeleteMailTemp vFiles

End Function

'=======================================
'   １メールごとの処理
'=======================================
Private Sub SetMailData(objRS As ADODB.Recordset, vArray As Variant)

    Dim vData As Variant
    Dim sDate As String

    objRS.AddNew

    For Each vData In vArray
        If LCase(Left(vData, 8)) = "subject:" Then
            objRS("KM_subject") = Trim(Mid(vData, 9))
        ElseIf LCase(Left(vData, 5)) = "from:" Then
            objRS("KM_from") = Trim(Mid(vData, 6))
        ElseIf LCase(Left(vData, 5)) = "date:" Then
            sDate = Trim(Mid(vData, 6))
            If IsDate(sDate) Then
                objRS("KM_date") = CDate(sDate)
            End If
        ElseIf LCase(Left(vData, 9)) = "x-mag2id:" Then
            objRS("KM_mgmg_id") = Trim(Mid(vData, 10))
        ElseIf LCase(Left(vData, 5)) = "body:" Then
            objRS("KM_body") = Trim(Mid(vData, 6))
        End If
    Next

    objRS("KM_print_flag") = False

    objRS.Update

End Sub
