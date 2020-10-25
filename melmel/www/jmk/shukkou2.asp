<!--#INCLUDE FILE="../melmel_inc.asp"-->
<!--#INCLUDE FILE="admin_inc.asp"-->
<!--#INCLUDE FILE="click_inc.asp"-->
<%
'******************************************************
' System :�߂�߂�Enet�����Ǘp�y�[�W
' Content:�L���e�X�g���[�����M����
'******************************************************

SetHeaderTitle "�L���e�X�g���[�����M", TC_KOUKOKU
%>
<html>
<head>
<meta HTTP-EQUIV="Content-Type" Content="text/html; charset=x-sjis">
<meta HTTP-EQUIV="Pragma" Content="no-cache">
<meta name="GENERATOR" Content="Microsoft Visual Studio 6.0">
<title><%=title_text%></title>
<link rel="stylesheet" type="text/css" href="admin.css">
</head>
<body>

<!--#INCLUDE FILE="admin_header.asp"-->

<%
Const KOUKOKU_TEST_FROM = "koukoku_ok@melmel.net"
Dim DB, RS, SQL, testmail, koukoku_id, koukoku_msg, first, count, start_line, end_line, end_line2, mag_id, bError
Dim subject, mailfrom, mailto, body, result

count = 0
Set testmail = Request.Form("testmail")
If Not IsEmpty(testmail.Item) Then
  Set DB = OpenDB()
  Set RS = Server.CreateObject("ADODB.RecordSet")

  SQL = "SELECT CT_test_mail FROM T_CONTROL"
  RS.Open SQL, DB, adOpenForwardOnly, adLockReadOnly
  If Not RS.EOF Then
    mailto = RS(0)
  Else
    ShowErrorMessage "�R���g���[���}�X�^����e�X�g���[�����M��A�h���X���擾�ł��܂���B"
  End If
  RS.Close

  mag_id = "00000"

  '�L���r���擾
  SQL = "SELECT TOP 1 * FROM T_KEISEN ORDER BY KE_keisen_id"
  Set RS = DB.Execute(SQL)
  If Not RS.EOF Then
    start_line = ZenkakuRight(RS("KE_start_line"), MIN_MOJISUU)
    end_line = ZenkakuRight(RS("KE_end_line"), MIN_MOJISUU)
  Else
    start_line = sDefaultStartLine
    end_line = sDefaultEndLine
  End If
  RS.Close
  end_line = Replace(end_line, "MMMMM", mag_id)

  SQL = "SELECT KK_koukoku_id,KK_koukoku_name,KK_client_name,KK_koukoku_type,KK_koukoku_line,KK_limit_date,KK_koukoku_msg,KK_koukoku_url,KK_pub_start,KK_pub_end" & vbCrLf & _
        "FROM V_KOUKOKU" & vbCrLf & _
        "WHERE KK_koukoku_id IN ("
  first = true
  For Each koukoku_id In testmail
    If first Then
      first = False
    Else
      SQL = SQL + ","
    End If
    SQL = SQL & "'" & koukoku_id & "'"
  Next
  SQL = SQL + ")"

  RS.Open SQL, DB, adOpenForwardOnly, adLockReadOnly
  Do Until RS.EOF
    koukoku_id = RS("KK_koukoku_id")
    end_line2 = Replace(end_line, "KKKKKK", koukoku_id)

    Select Case RS("KK_koukoku_type")
    Case "A", "Q"
        If SendTypeA(RS, start_line, end_line2, mailto) = False Then
            bError = True
        End If
    Case "B", "R"
        If SendTypeB(RS, start_line, end_line2, mailto) = False Then
            bError = True
        End If
    End Select

    If bError = False Then
      '�L���}�X�^�̏�ԃR�[�h���e�X�g���[�����M�ς݂ɐݒ�
      SQL = "UPDATE V_KOUKOKU SET KK_status=2 WHERE KK_koukoku_id='" & koukoku_id & "'"
      DB.Execute SQL
      count = count + 1
    Else
      ShowErrorMessage "���[�����M�Ɏ��s���܂����B[" & result & "]"
      Exit Do
    End If

    RS.MoveNext
  Loop
  RS.Close
End If

'=======================================
'   TYPE-A,Q�f�ڈ˗����[�����M
'=======================================
Private Function SendTypeA(objRS, start_line, end_line, mailto)

    Dim sSubject
    Dim sMailFrom
    Dim sBody
    Dim sSql
    Dim sKoukoku

    Call GetSendMailInfo("TYPE" & RS("KK_koukoku_type") & objRS("KK_koukoku_line"), sSubject, sMailFrom, sBody)
    sSubject = sSubject & " [ID=" & objRS("KK_koukoku_id") & "]"
    sMailFrom = KOUKOKU_TEST_FROM

    '�N���b�N�J�E���g�pURL���ߍ���
    sKoukoku = objRS("KK_koukoku_msg")
    Call SetClickCountURL(sKoukoku, objRS("KK_koukoku_id"), mag_id, objRS("KK_koukoku_url"))

    '�L���r���t��
    sKoukoku = start_line & vbCrLf & sKoukoku & vbCrLf & end_line

    sBody = Replace(sBody, "%MAGAZINE%", "�L���e�X�g")
    sBody = Replace(sBody, "%HAKKOUSHA%", "�߂�߂�Enet������")
    sBody = Replace(sBody, "%FROM_DATE%", DateFormat(objRS("KK_pub_start")))
    sBody = Replace(sBody, "%TO_DATE%", DateFormat(objRS("KK_pub_end")))
    sBody = Replace(sBody, "%KOUKOKU_MSG%", sKoukoku)
    sBody = Replace(sBody, "%KOUKOKU_ID%", objRS("KK_koukoku_id"))
    sBody = Replace(sBody, "%MELMAG_ID%", mag_id)
    sBody = Replace(sBody, "%LIMIT_DATE%", objRS("KK_limit_date"))
    sBody = Replace(sBody, "%HAKKOU_DATE%", Year(objRS("KK_pub_start")) & "/  /  ")
    If SendMail(mailto, sMailFrom, sSubject, sBody) = "" Then
        SendTypeA = True
    Else
        SendTypeA = False
    End If

End Function

'=======================================
'   TYPE-B�f�ڈ˗����[�����M
'=======================================
Private Function SendTypeB(objRS, start_line, end_line, mailto)

    Dim sKoukoku
    Dim sSubject
    Dim sMailFrom
    Dim sBody
    Dim sSql
    Dim dDate
    Dim sDateBusuu

    Call GetSendMailInfo("TYPE" & RS("KK_koukoku_type") & objRS("KK_koukoku_line"), sSubject, sMailFrom, sBody)
    sSubject = sSubject & " [ID=" & objRS("KK_koukoku_id") & "]"
    sMailFrom = KOUKOKU_TEST_FROM

    '�N���b�N�J�E���g�pURL���ߍ���
    sKoukoku = objRS("KK_koukoku_msg")
    Call SetClickCountURL(sKoukoku, objRS("KK_koukoku_id"), mag_id, objRS("KK_koukoku_url"))

    '�L���r���t��
    sKoukoku = start_line & vbCrLf & sKoukoku & vbCrLf & end_line

    sBody = Replace(sBody, "%MAGAZINE%", "�L���e�X�g")
    sBody = Replace(sBody, "%HAKKOUSHA%", "�߂�߂�Enet������")
    sBody = Replace(sBody, "%FROM_DATE%", DateFormat(objRS("KK_pub_start")))
    sBody = Replace(sBody, "%TO_DATE%", DateFormat(objRS("KK_pub_end")))
    sBody = Replace(sBody, "%KOUKOKU_MSG%", sKoukoku)
    sBody = Replace(sBody, "%KOUKOKU_ID%", objRS("KK_koukoku_id"))
    sBody = Replace(sBody, "%MELMAG_ID%", mag_id)
    sBody = Replace(sBody, "%LIMIT_DATE%", objRS("KK_limit_date"))

    sDateBusuu = ""
    dDate = objRS("KK_pub_start")
    Do While dDate <= objRS("KK_pub_end")
        sDateBusuu = sDateBusuu & "���s��[" & DateFormat(dDate) & "]" & vbCrLf
        sDateBusuu = sDateBusuu & "���s����[      ]��" & vbCrLf
        dDate = DateAdd("d", 1, dDate)
    Loop
    If Right(sDateBusuu, 2) = vbCrLf Then
        sDateBusuu = Left(sDateBusuu, Len(sDateBusuu) - 2)
    End If
    sBody = Replace(sBody, "%HAKKOU_DATE_BUSUU%", sDateBusuu)

    If SendMail(mailto, sMailFrom, sSubject, sBody) = "" Then
        SendTypeB = True
    Else
        SendTypeB = False
    End If

End Function
%>

<center>
<p><%=count%> �����M���܂����B</p>
<input type="button" value=" �n�j " onClick="location.href='shukkou.asp'">
</center>

<!--#INCLUDE FILE="admin_footer.asp"-->

</body>
</html>
