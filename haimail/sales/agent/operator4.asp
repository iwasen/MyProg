<!--#INCLUDE FILE="../inc/common.asp"-->
<!--#INCLUDE FILE="../inc/sub.asp"-->
<!--#INCLUDE FILE="../inc/database.asp"-->
<!--#INCLUDE FILE="../inc/login.asp"-->
<%
'******************************************************
' System :�͂��߁[��Enet�c�Ɨp�y�[�W
' Content:�㗝�X�S���ҏ��폜����
'******************************************************

'=== ���C������ ===
SetHeaderTitle "�㗝�X�S���ҏ��폜"

Dim sql, operator_id

operator_id = Request.QueryString("id")
If IsEmpty(operator_id) Then
	SystemError "empty operator_id"
End If

sql = "DELETE T_OPERATOR WHERE OP_operator_id='" & operator_id & "'"
ExecuteSQL(sql)
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
<p class="np">�㗝�X�S���ҏ����폜���܂����B<br>�V�K�ł��S���җl��o�^�����ꍇ�ɂ́A�u�㗝�X/�S���ғo�^�v���j���[�@TOP�y�[�W�ŁA�㗝�X�����N���b�N���Ă��������B</p>
<input type="button" value=" �n�j " onclick="location.href='index.asp'">
</center>

<!--#INCLUDE FILE="../inc/footer.asp"-->

</body>
</html>
