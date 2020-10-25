VERSION 1.0 CLASS
BEGIN
  MultiUse = -1  'True
  Persistable = 0  'NotPersistable
  DataBindingBehavior = 0  'vbNone
  DataSourceBehavior  = 0  'vbNone
  MTSTransactionMode  = 0  'NotAnMTSObject
END
Attribute VB_Name = "clsSendMail"
Attribute VB_GlobalNameSpace = False
Attribute VB_Creatable = True
Attribute VB_PredeclaredId = False
Attribute VB_Exposed = False
Option Explicit

Private oraDSL As OraDynaset
Private oraDSE As OraDynaset
Private sSendMailId As String

'=======================================
'   メール送信データ作成
'=======================================
Public Sub SetSendMailData(sMailId As String, sSubject As String, sFrom As String, sBody As String, sMailFormat As String, sHtmlPict As String)

    Dim sSql As String
    Dim oraDS As OraDynaset
    Dim oraDS2 As OraDynaset
    Dim oraDS3 As OraDynaset
    Dim lFileId As Long
    Dim bArray As Variant
    Dim sBody2 As String
    Dim vContentId As Variant
    Dim iPos As Long

    sSql = "SELECT SD_SEND_MAIL_ID.NEXTVAL FROM DUAL"
    Set oraDS = oraDB.CreateDynaset(sSql, ORADYN_READONLY)
    sSendMailId = oraDS(0).Value

    sSql = "SELECT * FROM T_SEND_MAIL_DATA WHERE SD_send_mail_id=" & sSendMailId
    Set oraDS = oraDB.CreateDynaset(sSql, ORADYN_DEFAULT)
    oraDS.AddNew
    oraDS("SD_send_mail_id").Value = sSendMailId
    oraDS("SD_subject").Value = sSubject
    oraDS("SD_from").Value = sFrom
    oraDS("SD_body").Value = Empty
    oraDS("SD_status").Value = "0"
    If sMailFormat = "0" Then
        oraDS("SD_mail_type").Value = "0"
    Else
        oraDS("SD_mail_type").Value = "1"
    End If
    oraDS.Update

    sBody2 = sBody

    sSql = "SELECT * FROM T_ATTACHMENT WHERE AT_mail_id='" & sMailId & "' ORDER BY AT_seq_no"
    Set oraDS2 = oraDB.CreateDynaset(sSql, ORADYN_READONLY)
    If Not oraDS2.EOF Then
        lFileId = 1
        sSql = "SELECT * FROM T_SEND_MAIL_FILE WHERE SF_send_mail_id=" & sSendMailId
        Set oraDS3 = oraDB.CreateDynaset(sSql, ORADYN_DEFAULT)
        Do Until oraDS2.EOF
            vContentId = oraDS2("AT_content_type").Value
            If sHtmlPict = "0" Or IsNull(vContentId) Then
                oraDS3.AddNew
                oraDS3("SF_send_mail_id").Value = sSendMailId
                oraDS3("SF_file_id").Value = lFileId
                oraDS3("SF_file_name").Value = oraDS2("AT_file_name").Value
                oraDS3("SF_content_id").Value = vContentId
                oraDS3("SF_file_content").Value = Empty
                oraDS3.Update
        
                oraDS2("AT_file_body").Value.Read bArray
                oraDS3.MoveLast
                oraDS3.Edit
                oraDS3("SF_file_content").Value.Write bArray, LenB(bArray)
                oraDS3.Update

                If Not IsNull(vContentId) Then
                    sBody2 = Replace(sBody2, oraDS2("AT_file_name").Value, "cid:" & vContentId)
                End If

                lFileId = lFileId + 1
            Else
                sBody2 = Replace(sBody2, oraDS2("AT_file_name").Value, sCtSiteUrl & "pict.asp?cid=" & vContentId)
            End If

            oraDS2.MoveNext
        Loop
    End If

    If sMailFormat <> "0" Then
        iPos = InStr(sBody2, "<")
        If iPos > 0 Then
            If LCase(Mid(sBody2, iPos + 1, 4)) <> "body" Then
                sBody2 = "<BODY>" & vbCrLf & sBody2
            End If
        End If

        iPos = InStrRev(sBody2, "<")
        If iPos > 0 Then
            If LCase(Mid(sBody2, iPos + 1, 5)) <> "/body" Then
                sBody2 = sBody2 & "</BODY>"
            End If
        End If
            
        sBody2 = "<!DOCTYPE HTML PUBLIC ""-//W3C//DTD HTML 4.0 Transitional//EN"">" & vbCrLf & _
                "<HTML><HEAD><TITLE>はいめーる・net</TITLE>" & vbCrLf & _
                "<META http-equiv=Content-Type content=""text/html; charset=iso-2022-jp"">" & vbCrLf & _
                "</HEAD>" & vbCrLf & _
                sBody2 & _
                "</HTML>"
    End If

    oraDS.Edit
    oraDS("SD_body").Value.Write sBody2
    oraDS.Update

    sSql = "SELECT * FROM T_SEND_MAIL_LIST WHERE SL_send_mail_id=" & sSendMailId
    Set oraDSL = oraDB.CreateDynaset(sSql, ORADYN_DEFAULT)

    sSql = "SELECT * FROM T_SEND_MAIL_EMBED WHERE SE_send_mail_id=" & sSendMailId
    Set oraDSE = oraDB.CreateDynaset(sSql, ORADYN_DEFAULT)

End Sub

'=======================================
'   メール送信リスト作成
'=======================================
Public Sub SetSendMailList(sMailAdr As String, sMailId As String, sMemberId As String, lTotalPoint As Variant, lSubSeq As Long)

    Dim sSql As String

    On Error GoTo ErrRet

    oraDSL.AddNew
    oraDSL("SL_send_mail_id").Value = sSendMailId
    oraDSL("SL_to").Value = sMailAdr
    oraDSL("SL_sub_seq").Value = lSubSeq
    oraDSL("SL_mail_header").Value = "X-Haimail: " & sMailId & "-" & CDbl(sMemberId)
    oraDSL.Update

    SetSendMailEmbed lSubSeq, "★12345678/12345678☆", "★" & sMailId & "/" & CDbl(sMemberId) & "☆"
    SetSendMailEmbed lSubSeq, "%POINT%", CStr(lTotalPoint)
    SetSendMailEmbed lSubSeq, "%CLICK%", CDbl(sMemberId)
    SetSendMailEmbed lSubSeq, "%MEMBER_ID%", sMemberId
    SetSendMailEmbed lSubSeq, "%MAIL_ID%", sMailId
    SetSendMailEmbed lSubSeq, "%MAIL_ADDR%", sMailAdr

ErrRet:
End Sub

'=======================================
'   差込データ作成
'=======================================
Private Sub SetSendMailEmbed(lSubSeq As Long, sVariableName As String, sEmbedText As String)

    oraDSE.AddNew
    oraDSE("SE_send_mail_id").Value = sSendMailId
    oraDSE("SE_sub_seq").Value = lSubSeq
    oraDSE("SE_variable_name").Value = sVariableName
    oraDSE("SE_embed_text").Value = sEmbedText
    oraDSE.Update

End Sub

'=======================================
'   メール送信開始状態セット
'=======================================
Private Sub Class_Terminate()

    If sSendMailId <> "" Then
        oraDB.ExecuteSQL "UPDATE T_SEND_MAIL_DATA SET SD_STATUS='1' WHERE SD_send_mail_id=" & sSendMailId
    End If

End Sub
