<!--#INCLUDE FILE="../melmel_inc.asp"-->
<%

If Session("mel_id") = "" Then
	Response.Redirect "./false.html"
Else

	If Request.Form("ssn") = "" or Request.Form("zen") = "" Then

		Response.Write "�G���[���������܂����Boffice@melmel.net �ɂ��₢���킹�������B"

	Else

		Session("mel_id") = Session("mel_id")
		Session("ssn") = Request.Form("ssn")
		Session("zen") = Request.Form("zen")

		Dim DB, RS, SQL
    Dim subject, mailfrom, body

    Set DB = OpenDB()
    Set RS = Server.CreateObject("ADODB.RecordSet")

    '�|�C���g���Z���O�ɏ�������
		SQL = "SELECT * FROM T_SPOINT WHERE 0=1"
		RS.Open SQL, DB, adOpenKeyset, adLockPessimistic
		RS.AddNew
		RS("SP_mel_id") = Session("mel_id")
		RS("SP_cur_point") = Session("zen")
		RS("SP_req_point") = Session("ssn")
		RS("SP_req_date") = Now()
    RS("SP_file") = False
    RS("SP_seisan") = True
		RS.Update
    RS.Close

    '���s�҃}�X�^�̌��|�C���g�����猸�Z
    SQL = "UPDATE T_MELMEL SET MM_point=MM_point-" & CLng(Session("ssn")) & " WHERE MM_mel_id='" & Session("mel_id")  & "'"
    DB.Execute SQL

    '�|�C���g���Z�m�F���[�����M
    SQL = "SELECT MM_h_name,MM_mail_adr,MM_point FROM T_MELMEL WHERE MM_mel_id='" & Session("mel_id") & "'"
		RS.Open SQL, DB, adOpenForwardOnly, adLockReadOnly
    If Not RS.EOF Then
      Call GetSendMailInfo("SEISAN", subject, mailfrom, body)
      body = Replace(body, "%HAKKOUSHA%", RS("MM_h_name"))
      body = Replace(body, "%MELMELID%", Session("mel_id"))
      body = Replace(body, "%SEISAN_DATE%", DateFormat(Now()))
      body = Replace(body, "%SEISAN_POINT%", Session("ssn"))
      body = Replace(body, "%NOKORI_POINT%", NumFormat(Number(RS("MM_point"))))
      Call SendMail(RS("MM_mail_adr"), mailfrom, subject, body)
    End If
    RS.Close

		Response.Redirect "pend.asp"

	End if

end if
%>
