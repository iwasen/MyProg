<!--#INCLUDE FILE="../melmel_inc.asp"-->
<!--#INCLUDE FILE="admin_inc.asp"-->
<%
'******************************************************
' System :めるめる・net事務局用ページ
' Content:発行者宛てメール送信処理
'******************************************************

SetHeaderTitle "発行者宛てメール送信結果", TC_HAKKOUSHA
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
Dim DB, RS, SQL, mel_array, mel_id, count, first
Dim subject, mailfrom, mailto, body, result

count = 0
mailto = Request.Form("mailto")
If Not IsEmpty(mailto) Then
  Set DB = OpenDB()
  Set RS = Server.CreateObject("ADODB.RecordSet")

  SQL = "SELECT MM_mel_id,MM_mail_adr,MM_h_name,MM_point FROM T_MELMEL WHERE MM_mel_id IN ("
  mailto = Replace(mailto, vbCrLf, ",")
  mel_array = Split(mailto, ",")
  first = true
  For Each mel_id In mel_array
    mel_id = Trim(mel_id)
    If Len(mel_id) >= 5 Then
      If first Then
        first = False
      Else
        SQL = SQL + ","
      End If
      SQL = SQL & "'" & Left(mel_id, 5) & "'"
    End If
  Next
  SQL = SQL + ")"

  RS.Open SQL, DB, adOpenForwardOnly, adLockReadOnly

  Do Until RS.EOF
    subject = Request.Form("subject")
    mailfrom = Request.Form("mailfrom")
    mailto = RS("MM_mail_adr")
    body = Request.Form("body")

    '発行者宛てメール送信
    body = Replace(body, "%HAKKOUSHA%", NVL(RS("MM_h_name"), ""))
    body = Replace(body, "%MELMELID%", RS("MM_mel_id"))
    body = Replace(body, "%POINT%", NumFormat(RS("MM_point")))
    body = Replace(body, "%EMAIL%", mailto)
    result = SendMail(mailto, mailfrom, subject, body)
    If result <> "" Then
			Writeln "メール送信エラー [ID:" & RS("MM_mel_id") & "][e-mail:" & RS("MM_mail_adr") & "][result=" & ReplaceTag(result) & "]<br>"
'      ShowErrorMessage "メール送信に失敗しました。[" & result & "]"
		Else
	    count = count + 1
    End If

    RS.MoveNext
  Loop
  RS.Close
End If
%>

<center>
<p><%=count%> 件送信しました。</p>
<input type="button" value=" ＯＫ " onClick="location.href='index.asp'">
</center>

<!--#INCLUDE FILE="admin_footer.asp"-->

</body>
</html>
