<!--#INCLUDE FILE="../inc/common.asp"-->
<!--#INCLUDE FILE="../inc/sub.asp"-->
<!--#INCLUDE FILE="../inc/database.asp"-->
<!--#INCLUDE FILE="../inc/login.asp"-->
<%
'**************************************************************
' System :�͂��߁[��Enet�����Ǘp�y�[�W
' Content:�����o�[�l�����i�l���[�U�[�T�|�[�g:�����މ�j
'**************************************************************

'=== ��ԃR�[�h�ݒ� ===
Private Sub SetStatus(member_id, status)
	Dim sql

	sql = "update T_MEMBER set MM_status='" & status & "' where MM_member_id='" & member_id & "'"
	ExecuteSQL sql
End Sub

'=== �މ�R�ݒ� ===
Private Sub SetTaikaiRiyuu(member_id)
	Dim sql

	sql = "insert into T_TAIKAI (TK_member_id,TK_taikai_riyuu,TK_taikai_date) values ('" & member_id & "','�͂��߁[��Z���^�[�ɂ�鋭���މ�',SYSDATE)"
	ExecuteSQL sql
End Sub

'=== �މ�R�폜 ===
Private Sub DeleteTaikaiRiyuu(member_id)
	Dim sql

	sql = "delete T_TAIKAI where TK_member_id='" & member_id & "'"
	ExecuteSQL sql
End Sub

'=== ���C������ ===
Dim member_id, status, msg

member_id = Request.Form("id")
If IsEmpty(member_id) Then
	SystemError "member_id is empty"
End If
status = Request.Form("status")

SetStatus member_id, status

If status = "9" Then
	SetTaikaiRiyuu member_id
	msg = "�����o�[ID[" & member_id & "] ����������މ���܂����B"
Else
	DeleteTaikaiRiyuu member_id
	msg = "�����o�[ID[" & member_id & "] ����̃T�[�r�X���ĊJ���܂����B"
End If

'���^�C�g���ݒ�
SetHeaderTitle "�����o�[�l�����i�����މ�j"
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
<p>
<%=msg%>
</p>
<br>
<input type="button" value=" �߂� " onClick="location.href='6-3.asp?id=<%=member_id%>'">
</form>
</center>

<!--#INCLUDE FILE="../inc/footer.asp"-->

</body>
</html>
