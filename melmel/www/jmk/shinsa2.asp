<!--#INCLUDE FILE="../melmel_inc.asp"-->
<!--#INCLUDE FILE="admin_inc.asp"-->
<%
'******************************************************
' System :�߂�߂�Enet�����Ǘp�y�[�W
' Content:���o�^�R�����ʏ���
'******************************************************

Dim DB, SQL, RS, seq_no, ok, pwd
Dim subject, mailfrom, mailto, body, result

'�p�����[���擾
seq_no = CLng(Request.QueryString("id"))
ok = Request.QueryString("ok")

'���o�^�f�[�^�I�[�v��
Set DB = OpenDB()
Set RS = Server.CreateObject("ADODB.RecordSet")
SQL = "SELECT * FROM T_PROVREG WHERE PR_seq_no=" & seq_no
RS.Open SQL, DB, adOpenKeyset, adLockPessimistic

If Not RS.EOF Then
  If ok = "1" Then
    '�R��OK�̏ꍇ
    pwd = GenaratePassword()
    GetSendMailInfo "REGOK", subject, mailfrom, body
    mailto = RS("PR_mail_adr")
    body = Replace(body, "%MAGAZINE%", RS("PR_m_name"))
    body = Replace(body, "%HAKKOUSHA%", RS("PR_h_name"))
    body = Replace(body, "%ID%", Request.QueryString("id"))
    body = Replace(body, "%PASSWORD%", pwd)

    RS("PR_password") = pwd
    RS("PR_status") = 1
  Else
    '�R��NG�̏ꍇ
    GetSendMailInfo "REGNG", subject, mailfrom, body
    mailto = RS("PR_mail_adr")
    body = Replace(body, "%MAGAZINE%", RS("PR_m_name"))
    body = Replace(body, "%HAKKOUSHA%", RS("PR_h_name"))

    RS("PR_status") = 3
  End If

  '���[�����M
  If Request.QueryString("nomail") = "1" Then
    RS.Update
  Else
    result = SendMail(mailto, mailfrom, subject, body)
    If result = "" Then
      'DB�X�V
      RS.Update
    Else
      RS.CancelUpdate
      ShowErrorMessage "���[�����M�Ɏ��s���܂����B[" & result & "]"
    End If
  End If
End If

RS.Close

Response.Redirect("shinsa.asp")

'�p�X���[�h�쐬
Function GenaratePassword()
	Dim pwd, pch1, pch2, pch0
	Randomize
  pwd = ""
	Do While Len(pwd) < 8
	  pch1 = Int(Rnd * 100)
		pch2 = Int(Rnd * 10)
		pch0 = pch1 + pch2
		If (pch0 >= 48 And pch0 <= 57) Or (pch0 >= 65 And pch0 <= 90) Or (pch0 >= 97 And pch0 <= 122) Then
			pwd = pwd & Chr(pch0)
		End If
	Loop
  GenaratePassword = pwd
End Function
%>
