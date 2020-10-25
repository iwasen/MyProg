Attribute VB_Name = "modBirthdayMail"
Option Explicit

'=======================================
'   誕生日メール送信
'=======================================
Public Function BirthdayMail() As Long

    Dim dToDay As Date

    On Error GoTo ErrHandler

    dToDay = Date

    BirthdayMail = SendBirthdayMail(dToDay)
    Exit Function

ErrHandler:
    Call WriteLog(LOG_ERROR, "BirthdayMail.bas: " & Err.Description)

End Function

'=======================================
'   誕生日メール配信処理
'=======================================
Private Function SendBirthdayMail(dToDay As Date) As Long

    Dim sSql As String
    Dim oraDS As OraDynaset
    Dim oraDS2 As OraDynaset
    Dim sSeibetsu As String
    Dim iAge As Integer
    Dim sFromAdr As String
    Dim sToAdr As String
    Dim sSubject As String
    Dim sBody As String
    Dim sResult As String
    Dim lCount As Long

    lCount = 0

    sSql = "SELECT MM_mail_adr,MM_seinengappi,MM_seibetsu,PT_point" & vbCrLf & _
           "FROM T_MEMBER,V_TOTAL_POINT" & vbCrLf & _
           "WHERE MM_status='0' AND TO_NUMBER(TO_CHAR(MM_seinengappi, 'MM'))=" & Month(dToDay) & " AND TO_NUMBER(TO_CHAR(MM_seinengappi, 'DD'))=" & Day(dToDay) & " AND MM_member_id=PT_member_id(+)"
    Set oraDS = oraDB.CreateDynaset(sSql, ORADYN_READONLY)
    Do Until oraDS.EOF
        sSeibetsu = oraDS("MM_seibetsu").Value
        iAge = Year(dToDay) - Year(oraDS("MM_seinengappi").Value)
        sSql = "SELECT *" & vbCrLf & _
               "FROM T_BIRTHDAY_MAIL" & vbCrLf & _
               "WHERE (BM_seibetsu IS NULL OR BM_seibetsu='" & sSeibetsu & "')" & vbCrLf & _
               " AND (BM_age_from IS NULL OR BM_age_from<=" & iAge & ")" & vbCrLf & _
               " AND (BM_age_to IS NULL OR BM_age_to>=" & iAge & ")" & vbCrLf & _
               " AND BM_valid_flag='1'" & vbCrLf & _
               "ORDER BY BM_priority"
        Set oraDS2 = oraDB.CreateDynaset(sSql, ORADYN_READONLY)
        If Not oraDS2.EOF Then
            sFromAdr = oraDS2("BM_from_adr").Value
            sToAdr = oraDS("MM_mail_adr").Value
            sSubject = oraDS2("BM_subject").Value
            sBody = ReadCLOB(oraDS2("BM_body").Value)

            sBody = Replace(sBody, "%POINT%", NVL(oraDS("PT_point").Value, 0))
            sBody = Replace(sBody, "%AGE%", iAge)
            sResult = SendMail(sToAdr, sFromAdr, sSubject, sBody)

            lCount = lCount + 1
        End If

        oraDS.MoveNext
    Loop

    SendBirthdayMail = lCount

End Function
