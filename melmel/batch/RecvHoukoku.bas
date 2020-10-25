Attribute VB_Name = "modRecvHoukoku"
Option Explicit
Option Compare Text

'=======================================
'   掲載実績報告書受信処理
'=======================================
Public Function RecvHoukoku() As Integer

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
    vFiles = RecvMail(sPop3Server1, sPop3ReportUser, sPop3ReportPass, lMailNum)

    '受信したメールを本登録メルマガ受信ログに保存
    If lMailNum > 0 And IsArray(vFiles) Then   ' OK ?

        objDB.BeginTrans

        '掲載報告書受信ログオープン
        Set objRS = CreateObject("ADODB.RecordSet")
        sSql = "SELECT * FROM T_REPORTLOG WHERE 0=1"
        objRS.Open sSql, objDB, adOpenKeyset, adLockPessimistic

        For Each vFile In vFiles
            vArray = objMail.ReadMail(vFile, "subject:from:date:nofile:", "")
            If IsArray(vArray) Then   ' OK ?
                Call SetMailData(objRS, vArray)
                iCount = iCount + 1
            End If
        Next

        objRS.Close

        objDB.CommitTrans

        'テンポラリファイル削除
        DeleteMailTemp vFiles

        Call DeleteMail(sPop3Server1, sPop3ReportUser, sPop3ReportPass, lMailNum)
    End If

    RecvHoukoku = iCount
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
    Dim sSql As String
    Dim lBusuu As Long
    Dim sDate As String
    Dim bOK As Boolean
    Dim objRS2 As ADODB.Recordset

    'T_REPORTDATのシーケンス番号が０のデータを削除
    sSql = "DELETE T_REPORTDAT WHERE RD_seq_no=0"
    objDB.Execute sSql

    objRS.AddNew

    bOK = False
    lBusuu = 0
    For Each vData In vArray
        If LCase(Left(vData, 8)) = "subject:" Then
            objRS("RP_subject") = Trim(Mid(vData, 9))
        ElseIf LCase(Left(vData, 5)) = "from:" Then
            objRS("RP_from") = Trim(Mid(vData, 6))
        ElseIf LCase(Left(vData, 5)) = "date:" Then
            sDate = Trim(Mid(vData, 6))
            If IsDate(sDate) Then
                objRS("RP_date") = CDate(sDate)
            End If
        ElseIf LCase(Left(vData, 5)) = "body:" Then
            objRS("RP_body") = Trim(Mid(vData, 6))
            bOK = CheckHoukoku(objRS, vData, lBusuu)
        End If
    Next

    If bOK Then
        '既に受信済みかチェック
        sSql = "SELECT RP_seq_no FROM T_REPORTLOG" & _
                " WHERE RP_koukoku_id='" & objRS("RP_koukoku_id") & "'" & _
                " AND RP_mag_id='" & objRS("RP_mag_id") & "'"
        Set objRS2 = objDB.Execute(sSql)
        If Not objRS2.EOF Then
            '受信済みだったらそれを消す
            sSql = "DELETE T_REPORTLOG WHERE RP_seq_no=" & objRS2(0)
            objDB.Execute sSql
    
            sSql = "DELETE T_REPORTDAT WHERE RD_seq_no=" & objRS2(0)
            objDB.Execute sSql
    
            '実績ポイントをリセット
            Call ResetJissekiPoint(objRS("RP_mag_id"), objRS("RP_koukoku_id"))
        End If

        objRS2.Close

        objRS.Update

        'T_REPORTDATのシーケンス番号を書き換える
        sSql = "UPDATE T_REPORTDAT SET RD_seq_no=" & objRS("RP_seq_no") & " WHERE RD_seq_no=0"
        objDB.Execute sSql
    
        If lBusuu <> 0 Then
            'メルマガマスタの最新部数を更新
            sSql = "UPDATE T_MELMAG SET MG_new_busuu=" & lBusuu & " WHERE MG_mag_id='" & objRS("RP_mag_id") & "'"
            objDB.Execute sSql
        End If
    Else
        objRS.CancelUpdate
    End If

End Sub

'=======================================
'   報告内容チェック
'=======================================
Private Function CheckHoukoku(objRS As ADODB.Recordset, vBody As Variant, lBusuu As Long) As Boolean

    Dim vArray As Variant
    Dim vLine As Variant
    Dim iCount As Integer
    Dim iPos1 As Integer, iPos2 As Integer
    Dim sDate As String
    Dim sBusuu As String
    Dim sSql As String
    Dim objRS2 As ADODB.Recordset
    Dim dtPubStart As Date
    Dim dtPubEnd As Date
    Dim dtDate As Date
    Dim iMaxKoukoku As Integer
    Dim bKoukokuId As Boolean
    Dim bMagId As Boolean

    vArray = Split(vBody, vbCrLf)

    bKoukokuId = False
    bMagId = False
    iCount = 1
    lBusuu = 0
    sDate = ""

    If IsArray(vArray) Then
        For Each vLine In vArray
            If InStr(vLine, "メルマガID[") > 0 Then
                objRS("RP_mag_id") = Mid(vLine, InStr(vLine, "[") + 1, 5)
                bKoukokuId = True
            ElseIf InStr(vLine, "広告ID[") > 0 Then
                objRS("RP_koukoku_id") = Trim(Mid(vLine, InStr(vLine, "[") + 1, 6))
                sSql = "SELECT *" & _
                       " FROM T_KEISAI" & _
                       " WHERE KS_koukoku_id='" & objRS("RP_koukoku_id") & "' AND KS_mag_id='" & objRS("RP_mag_id") & "'"
                Set objRS2 = objDB.Execute(sSql)
                If objRS2.EOF Then
                    objRS2.Close
                    Exit For
                End If
                objRS2.Close

                sSql = "SELECT KK_pub_start,KK_pub_end,KK_koukoku_type" & _
                       " FROM V_KOUKOKU" & _
                       " WHERE KK_koukoku_id='" & objRS("RP_koukoku_id") & "' AND KK_status<>9"
                Set objRS2 = objDB.Execute(sSql)
                If objRS2.EOF Then
                    objRS2.Close
                    Exit For
                End If
                dtPubStart = objRS2(0)
                dtPubEnd = objRS2(1)
                Select Case objRS2(2)
                Case "A", "Q"
                    iMaxKoukoku = 1
                Case "B", "R"
                    iMaxKoukoku = DateDiff("d", dtPubStart, dtPubEnd) + 1
                Case Else
                    iMaxKoukoku = 0
                End Select
                objRS2.Close
                bMagId = True
            ElseIf InStr(vLine, "発行日[") > 0 Then
                iPos1 = InStr(vLine, "[")
                iPos2 = InStr(iPos1, vLine, "]")
                If iPos2 > 0 Then
                    sDate = Mid(vLine, iPos1 + 1, iPos2 - iPos1 - 1)
                Else
                    sDate = Mid(vLine, iPos1 + 1)
                End If
                sDate = Replace(sDate, " ", "")
                If Not IsDate(sDate) Then
                   sDate = ""
                Else
                    dtDate = CDate(sDate)
                    If dtDate < dtPubStart Or dtDate > dtPubEnd Then
                        sDate = ""
                    End If
                End If
            ElseIf InStr(vLine, "発行部数[") > 0 And sDate <> "" And bKoukokuId And bMagId Then
                iPos1 = InStr(vLine, "[")
                iPos2 = InStr(iPos1, vLine, "]")
                If iPos2 > 0 Then
                    sBusuu = Mid(vLine, iPos1 + 1, iPos2 - iPos1 - 1)
                Else
                    sBusuu = Mid(vLine, iPos1 + 1)
                End If
                sBusuu = Replace(sBusuu, ",", "")
                sBusuu = Replace(sBusuu, " ", "")
                If IsNumeric(sBusuu) Then
                    If iCount > iMaxKoukoku Then
                        Exit For
                    End If

                    If CLng(sBusuu) > 0 Then
                        sSql = "SELECT * FROM T_REPORTDAT WHERE RD_seq_no=0 AND RD_pub_date='" & dtDate & "'"
                        Set objRS2 = objDB.Execute(sSql)
                        If objRS2.EOF Then
                            lBusuu = CLng(sBusuu)
                            sSql = "INSERT INTO T_REPORTDAT VALUES" & _
                                    "(0," & iCount & ",'" & dtDate & "'," & lBusuu & ")"
                            objDB.Execute sSql
                            iCount = iCount + 1
                        End If
                        objRS2.Close
                    End If
                End If
            End If
        Next
    End If

    If iCount > 1 Then
        CheckHoukoku = True
    Else
        CheckHoukoku = False
    End If

End Function
