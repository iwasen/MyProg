<!--#INCLUDE FILE="../melmel_inc.asp"-->
<html>

<head>
<title>�{�o�^�����y�[�W</title>
<meta HTTP-EQUIV="Content-Type" Content="text/html; charset=x-sjis">
<meta HTTP-EQUIV="Pragma" Content="no-cache">
</head>

<body>
<%
Dim DB, RS1, RS2, SQL
Dim subject, mailfrom, body, mel_id, seq_no

seq_no = Request("id")
If IsEmpty(seq_no) Then
	Writeln "<form>"
	Writeln "�����}�K�}�X�^�ɂ͓o�^����Ă��āA���s�҃}�X�^�ɂ͓o�^����Ă��Ȃ��h�c�̏C�����s���܂��B<br>"
	Writeln "�w�肵���h�c�̉��o�^�f�[�^�𔭍s�҃}�X�^�ɓo�^���܂��B<br><br>"
	Writeln "�����}�K�h�c�@<input type='text' name='id'>�@<input type='submit' value='�o�^'>"
	Writeln "</form>"
Else
	Set DB = OpenDB()
	Set RS1 = Server.CreateObject("ADODB.RecordSet")

	SQL = "SELECT * FROM T_PROVREG WHERE PR_seq_no=" & seq_no
	RS1.Open SQL, DB, adOpenForwardOnly, adLockReadOnly

	If RS1.EOF Then
		'--- Repeated Entry ---
		Response.Write "���o�^�f�[�^������܂���"
	Else
		mel_id = Right("0000" & RS1("PR_seq_no"), 5)
	  Set RS2 = Server.CreateObject("ADODB.RecordSet")
		SQL = "SELECT MG_mel_id FROM T_MELMAG WHERE MG_mag_id='" & mel_id & "'"
	  RS2.Open SQL, DB, adOpenKeyset, adLockPessimistic
		If RS2.EOF Then
			Writeln "�w�肵���h�c�̓����}�K�}�X�^�ɓo�^����Ă��܂���B"
		Else
			If Not IsNull(RS2(0)) Then
				Writeln "�w�肵���h�c�̃����}�K�}�X�^�̔��s�҂͂��łɑ��݂��܂��B"
			Else
				RS2(0) = mel_id
				RS2.Update
				RS2.Close

			  '--- ���s�҃}�X�^�ɖ��o�^�Ȃ�o�^����
				SQL = "SELECT * FROM T_MELMEL WHERE MM_mel_id='" & mel_id & "'"
			  RS2.Open SQL, DB, adOpenKeyset, adLockPessimistic

			  If RS2.EOF Then
			    '--- ���s�ҐV�K�o�^ ---
			    RS2.AddNew
			    RS2("MM_mel_id") = mel_id
			    RS2("MM_mail_adr") = RS1("PR_mail_adr")
			    RS2("MM_h_name") = RS1("PR_h_name")
			    RS2("MM_c_name") = RS1("PR_c_name")
			    RS2("MM_password") = RS1("PR_password")
			    RS2("MM_bk_acc") = ""
			    RS2("MM_bk_nm") = ""
			    RS2("MM_bk_st") = ""
			    RS2("MM_bk_knd") = 0
			    RS2("MM_bk_knm") = ""
			    RS2("MM_regist_date") = Now()
			    RS2("MM_last_update") = Now()
			    RS2("MM_status") = 0
			    RS2("MM_point") = 0
			    RS2.Update
					Response.Write "���s�҃}�X�^�ɓo�^���܂����B"
				Else
					Response.Write "���s�҃}�X�^�ɂ��łɓo�^�ς݂ł��B"
			  End If
			End If
		End If
	End If
End If
%>
</body>
</html>
