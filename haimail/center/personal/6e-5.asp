<!--#INCLUDE FILE="../inc/common.asp"-->
<!--#INCLUDE FILE="../inc/sub.asp"-->
<!--#INCLUDE FILE="../inc/database.asp"-->
<!--#INCLUDE FILE="../inc/login.asp"-->
<%
'**************************************************************
' System :�͂��߁[��Enet�����Ǘp�y�[�W
' Content:�����o�[�l�����i�l���[�U�[�T�|�[�g:�����ꎞ��~�j
'**************************************************************

'=== ��Ԏ擾 ===
Private Sub GetStatus(member_id)
	Dim sql, ds

	sql = "select MM_status from T_MEMBER where MM_member_id='" & member_id & "'"
	Set ds = CreateDynaset(sql, ORADYN_READONLY)
	If Not ds.EOF Then
		status = ds(0).Value
	End If
End Sub

'=== ���C������ ===
Dim member_id, status, msg, new_status

member_id = Request.QueryString("id")
If IsEmpty(member_id) Then
	SystemError "member_id is empty"
End If

GetStatus member_id

Select Case status
Case "0"
	msg = "���̃����o�[�͌��ݗL����Ԃł��B�ꎞ��~�����܂����H"
	new_status = "1"
Case "1"
	msg = "���̃����o�[�͌��݈ꎞ��~���ł��B�T�[�r�X���ĊJ���܂����H"
	new_status = "0"
Case "9"
	msg = "���̃����o�[�͌��ݑމ�Ă��܂��B"
	new_status = ""
End Select

'���^�C�g���ݒ�
SetHeaderTitle "�����o�[�l�����i�����ꎞ��~�j"
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
<form method="post" action="6e-6.asp" name="form1">
	<p>
	<%=msg%>
	<input type="hidden" name="id" value="<%=member_id%>">
	<input type="hidden" name="status" value="<%=new_status%>">
	</p>
	<br>
<%If new_status <> "" Then%>
	<input type="submit" value=" �X�V ">
<%End If%>
	<input type="button" value=" �߂� " onClick="history.back()">
</form>
</center>

<!--#INCLUDE FILE="../inc/footer.asp"-->

</body>
</html>
