<!--#INCLUDE FILE="../inc/common.asp"-->
<!--#INCLUDE FILE="../inc/sub.asp"-->
<!--#INCLUDE FILE="../inc/database.asp"-->
<!--#INCLUDE FILE="../inc/login.asp"-->
<%
'******************************************************
' System :�͂��߁[��Enet�����Ǘp�y�[�W
' Content:�����o�[�l�����i�l���[�U�[�T�|�[�g�j
'******************************************************

'=== ������擾 ===
Private Sub ReadMember(member_id, mail_adr)
	Dim sql, ds, sub1, sub2, sub3, sub4, cond

	If member_id <> "" Then
		cond = " MM_member_id = '" & Right(String(10, "0") & Trim(member_id), 10) & "'"
	Else
		cond = " MM_mail_adr = '" & mail_adr & "'"
	End If

	sub1 = "SELECT SUM(PT_total_point) FROM T_POINT_TOTAL WHERE PT_member_id=MM_member_id"
	sub2 = "SELECT PT_total_point FROM T_POINT_TOTAL WHERE PT_point_type='10' AND PT_member_id=MM_member_id"
	sub3 = "SELECT PT_total_point FROM T_POINT_TOTAL WHERE PT_point_type='11' AND PT_member_id=MM_member_id"
	sub4 = "SELECT PT_total_point FROM T_POINT_TOTAL WHERE PT_point_type='12' AND PT_member_id=MM_member_id"

	sql = "SELECT MM_member_id,MM_password,MM_regist_date,MM_update_date,MM_update_cnt,MM_seibetsu,MM_mikikon,MM_mail_adr," & vbCrLf & _
				" MM_seinengappi,MM_html_mail,MM_oshirase_mail,MM_regist_type,MM_status,TK_taikai_date," & vbCrLf & _
				"(" & sub1 & ") PT_count_1,(" & sub2 & ") PT_count_2,(" & sub3 & ") PT_count_3,(" & sub4 & ") PT_count_4" & vbCrLf & _
				"FROM T_MEMBER,T_TAIKAI" & vbCrLf & _
				"WHERE" & cond & " AND MM_member_id=TK_member_id(+)" & vbCrLf & _
				"ORDER BY MM_member_id DESC"
	Set ds = CreateDynaset(sql, ORADYN_READONLY)
	If Not ds.EOF Then
		member_id = ds("MM_member_id").Value
		password = ds("MM_password").Value
		regist_date = DateFormat2(ds("MM_regist_date").Value)
		update_date = DateFormat2(ds("MM_update_date").Value)
		update_cnt = NumFormat(ds("MM_update_cnt").Value)
		total_point = NumFormat(ds("PT_count_1").Value)
		send_point = NumFormat(ds("PT_count_2").Value)
		ankeart_point = NumFormat(ds("PT_count_3").Value)
		kansou_point = NumFormat(ds("PT_count_4").Value)
		seibetsu = SeibetsuText(ds("MM_seibetsu").Value) & "��"
		mikikon = MikikonText(ds("MM_mikikon").Value)
		mail_adr = ds("MM_mail_adr").Value
		seinengappi = DateFormat2(ds("MM_seinengappi").Value)
		If ds("MM_html_mail").Value = "0" Then html_mail = "�@" Else html_mail = "��"
		If ds("MM_oshirase_mail").Value = "0" Then oshirase_mail = "�@" Else oshirase_mail = "��"
		If ds("MM_regist_type").Value = "0" Then regist_type = "���܂����o�^" Else regist_type = "�������o�^"
		taikai_date = DateFormat2(ds("TK_taikai_date").Value)
		status = StatusText(ds("MM_status").Value)
	Else
		err_flg = 8
	End If
End Sub

'=== ��ԃR�[�h ===
Private Function StatusText(status)
	Select Case status
	Case "0"
		StatusText = "�L��"
	Case "1"
		StatusText = "�ꎞ��~"
	Case "9"
		StatusText = "�މ�"
	Case Else
		StatusText = "-"
	End Select
End Function

'=== ���͂��߁[��h�c�^���[���A�h���X�G���[ ===
Private Sub SetErrorMessage()
	Dim rtn_url

	rtn_url = "location.href=""6-2.asp?f1=" & member_id & "&f2=" & mail_adr & """"
		
	Writeln "<table border=0 cellspacing=0 cellpadding=1 width='95%'>"
	Writeln "<tr><td><font color='#FF0000'>"
	If err_flg = 9 Then
		Writeln "�u�͂��߁[��h�c�v�A�u���[���A�h���X�v�����͂���Ă��܂���B<br>"
		Writeln "�ǂ��炩�͕K�����͂��Ă��������B<br>"
	ElseIf member_id <> "" Then
		Writeln "���͂��ꂽ�u�͂��߁[��h�c�v�͉���}�X�^�ɂ���܂���B<br>"
		Writeln "�������u�͂��߁[��h�c�v����͂��Ă��������B<br>"
	Else
		Writeln "���͂��ꂽ�u���[���A�h���X�v�͉���}�X�^�ɂ���܂���B<br>"
		Writeln "�������u���[���A�h���X�v����͂��Ă��������B<br>"
	End If
	Writeln "<br></font></td></tr>"
	Writeln "</table>"
	Writeln "<br clear='left'>"
	Writeln "<br>"
	Writeln "<input type='button' name='btn_end' value=' �߂� ' onClick='" & rtn_url & "'>"
End Sub

'=== ���l���\�� ===
Private Sub SetDataMessage()
	Dim rtn_url1, rtn_url2, rtn_url3, rtn_url4

	rtn_url1 = "location.href=""6a-4.asp?id=" & member_id & """"
	rtn_url2 = "location.href=""6b-4.asp?id=" & member_id & """"
	rtn_url3 = "location.href=""6e-5.asp?id=" & member_id & """"
	rtn_url4 = "location.href=""6f-5.asp?id=" & member_id & """"

	Writeln "<table border=1 cellspacing=1 cellpadding=0 width='100%' class='small'>"
	Writeln "<tr bgcolor='#E0FFE0'>"
	Writeln "<th>ID</th>"
	Writeln "<th>PW</th>"
	Writeln "<th>�o�^��</th>"
	Writeln "<th>�ŏI�X�V��</th>"
	Writeln "<th>�X�V��</th>"
	Writeln "<th>���v�߲��</th>"
	Writeln "<th>���M���Z�߲��</th>"
	Writeln "<th>�ݹ���߲��</th>"
	Writeln "<th>���z���߲��</th>"
	Writeln "</tr>"
	Writeln "<tr bgcolor='#FFEEE0'>"
	Writeln "<td align='center'>" & member_id & "</td>"
	Writeln "<td>" & password & "</td>"
	Writeln "<td align='center'>" & regist_date & "</td>"
	Writeln "<td align='center'>" & update_date & "</td>"
	Writeln "<td align='right'>" & update_cnt & "</td>"
	Writeln "<td align='right'>" & total_point & "</td>"
	Writeln "<td align='right'>" & send_point & "</td>"
	Writeln "<td align='right'>" & ankeart_point & "</td>"
	Writeln "<td align='right'>" & kansou_point & "</td>"
	Writeln "</tr>"

	Writeln "<tr bgcolor='#E0FFE0'>"
	Writeln "<th>����</th>"
	Writeln "<th>������</th>"
	Writeln "<th>Ұٱ��ڽ</th>"
	Writeln "<th>���N����</th>"
	Writeln "<th>HTML��M�ݒ�</th>"
	Writeln "<th>�޷��ݎ�M�ݒ�</th>"
	Writeln "<th>����敪</th>"
	Writeln "<th>�މ����</th>"
	Writeln "<th>���</th>"
	Writeln "</tr>"
	Writeln "<tr bgcolor='#FFEEE0'>"
	Writeln "<td align='center'>" & seibetsu & "</td>"
	Writeln "<td align='center'>" & mikikon & "</td>"
	Writeln "<td>" & mail_adr & "</td>"
	Writeln "<td align='center'>" & seinengappi & "</td>"
	Writeln "<td align='center'>" & html_mail & "</td>"
	Writeln "<td align='center'>" & oshirase_mail & "</td>"
	Writeln "<td align='center'>" & regist_type & "</td>"
	Writeln "<td align='center'>" & taikai_date & "</td>"
	Writeln "<td align='center'>" & status & "</td>"
	Writeln "</tr>"
	Writeln "</table>"
	Writeln "<br clear='left'>"
	Writeln "<br>"
	Writeln "<input type='button' value='�l���ڍ�' onClick='" & rtn_url1 & "'>"
	Writeln "<input type='button' value='����M����' onClick='" & rtn_url2 & "'>"
	Writeln "<input type='button' value='�����ꎞ��~' onClick='" & rtn_url3 & "'>"
	Writeln "<input type='button' value='�����މ�' onClick='" & rtn_url4 & "'>"
	Writeln "<input type='button' value=' �߂� ' onClick='location.href=""6-2.asp""'>"
	Writeln "<br>"
End Sub

'=== ���C������ ===
Dim err_flg
Dim member_id, password, regist_date, update_date, update_cnt, total_point, send_point, ankeart_point, kansou_point, seibetsu
Dim mikikon, mail_adr, seinengappi, html_mail, oshirase_mail, regist_type, taikai_date, status

'���N�G�X�g�^�N�G��������擾
member_id = Request("id")
mail_adr = Request("mail_adr")
If IsEMpty(member_id) And IsEMpty(mail_adr) Then
	Response.Redirect "6-2.asp"
End If

'�^�C�g���ݒ�
SetHeaderTitle "�����o�[�l����"

'������擾
ReadMember member_id, mail_adr
%>
<html>
<head>
<meta HTTP-EQUIV="Content-Type" Content="text/html; charset=x-sjis">
<meta HTTP-EQUIV="Pragma" Content="no-cache">
<title><%=title_text%></title>
<link rel="stylesheet" type="text/css" href="../haimail.css">
</head>
<body>

<!--#INCLUDE FILE="../inc/header.asp"-->

<center>
<form>
	<br>
<%
If err_flg = False Then
	SetDataMessage
Else
	SetErrorMessage
End If
%>
</form>
</center>

<!--#INCLUDE FILE="../inc/footer.asp"-->

</body>
</html>
